<?php

namespace App\Http\Controllers\Admin;

use App\Actions\CalculateElectionResultAction;
use App\Actions\RecordAudit;
use App\Enums\ElectionStatus;
use App\Http\Controllers\Controller;
use App\Models\Election;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ElectionResultController extends Controller
{
    public function index(Request $request, CalculateElectionResultAction $calculator): View
    {
        Gate::authorize('publish-results');

        $elections = Election::latest('voting_end')->get();
        $selectedElectionId = $request->input('election_id');

        $activeElection = $selectedElectionId
            ? $elections->firstWhere('id', $selectedElectionId)
            : ($elections->firstWhere('status.value', 'published')
                ?? $elections->firstWhere('status.value', 'closed')
                ?? $elections->first());

        $results = null;

        if ($activeElection) {
            $results = $calculator->handle($activeElection, ignorePublicCheck: true);
        }

        return view('admin.results.index', compact('elections', 'activeElection', 'results'));
    }

    public function publish(Election $election): RedirectResponse
    {
        Gate::authorize('publish-results');

        if (now()->lt($election->voting_end)) {
            return back()->withErrors(['error' => 'Hasil tidak dapat dipublikasikan sebelum waktu pemungutan suara berakhir.']);
        }

        $before = ['status' => $election->status->value, 'result_publish_at' => $election->result_publish_at];

        $election->update([
            'status' => ElectionStatus::Published,
            'result_publish_at' => $election->result_publish_at && $election->result_publish_at->lte(now()) ? $election->result_publish_at : now(),
        ]);

        app(RecordAudit::class)->handle(auth()->user(), 'results.published', $election, $before, [
            'status' => ElectionStatus::Published->value,
            'result_publish_at' => $election->result_publish_at,
        ]);

        return back()->with('success', "Hasil pemilihan '{$election->name}' resmi dipublikasikan.");
    }

    public function exportExcel(Election $election, CalculateElectionResultAction $calculator): StreamedResponse
    {
        Gate::authorize('publish-results');

        $results = $calculator->handle($election, ignorePublicCheck: true);

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekapitulasi Suara');

        // Header info
        $sheet->setCellValue('A1', 'BERITA ACARA REKAPITULASI SUARA');
        $sheet->setCellValue('A2', $election->name);
        $sheet->setCellValue('A3', 'Tanggal Cetak: '.now()->format('d/m/Y H:i').' WITA');
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        $sheet->mergeCells('A3:F3');

        // Table headers
        $row = 5;
        $headers = ['No. Urut', 'Nama Ketua', 'Nama Wakil', 'Program Studi', 'Jumlah Suara', 'Persentase (%)'];
        foreach ($headers as $col => $header) {
            $sheet->setCellValue([$col + 1, $row], $header);
        }

        $row = 6;
        foreach ($results['candidates'] as $candidate) {
            $pct = $results['total'] > 0 ? round($candidate->ballots_count / $results['total'] * 100, 2) : 0;
            $sheet->setCellValue([1, $row], $candidate->candidate_number ?? '-');
            $sheet->setCellValue([2, $row], $candidate->chairman->name ?? '-');
            $sheet->setCellValue([3, $row], $candidate->viceChairman->name ?? '-');
            $sheet->setCellValue([4, $row], $candidate->chairman->study_program ?? '-');
            $sheet->setCellValue([5, $row], $candidate->ballots_count);
            $sheet->setCellValue([6, $row], $pct);
            $row++;
        }

        // Summary
        $row++;
        $sheet->setCellValue("A{$row}", 'Total Suara Sah');
        $sheet->setCellValue("B{$row}", $results['total']);
        $row++;
        $sheet->setCellValue("A{$row}", 'Total Pemilih Terdaftar');
        $sheet->setCellValue("B{$row}", $results['voters']);
        $row++;
        $sheet->setCellValue("A{$row}", 'Sudah Memilih');
        $sheet->setCellValue("B{$row}", $results['participated']);
        $row++;
        $sheet->setCellValue("A{$row}", 'Tingkat Partisipasi (%)');
        $sheet->setCellValue("B{$row}", $results['turnout']);

        $filename = 'rekapitulasi-suara-'.str($election->name)->slug().'-'.now()->format('Ymd').'.xlsx';

        $writer = new Xlsx($spreadsheet);

        return response()->stream(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function exportCsv(Election $election, CalculateElectionResultAction $calculator): StreamedResponse
    {
        Gate::authorize('publish-results');

        $results = $calculator->handle($election, ignorePublicCheck: true);
        $filename = 'rekapitulasi-suara-'.str($election->name)->slug().'-'.now()->format('Ymd').'.csv';

        return response()->stream(function () use ($results, $election) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Rekapitulasi Suara — '.$election->name]);
            fputcsv($handle, ['Tanggal Cetak', now()->format('d/m/Y H:i').' WITA']);
            fputcsv($handle, []);
            fputcsv($handle, ['No. Urut', 'Nama Ketua', 'Nama Wakil', 'Program Studi', 'Jumlah Suara', 'Persentase (%)']);

            foreach ($results['candidates'] as $candidate) {
                $pct = $results['total'] > 0 ? round($candidate->ballots_count / $results['total'] * 100, 2) : 0;
                fputcsv($handle, [
                    $candidate->candidate_number ?? '-',
                    $candidate->chairman->name ?? '-',
                    $candidate->viceChairman->name ?? '-',
                    $candidate->chairman->study_program ?? '-',
                    $candidate->ballots_count,
                    $pct,
                ]);
            }

            fputcsv($handle, []);
            fputcsv($handle, ['Total Suara Sah', $results['total']]);
            fputcsv($handle, ['Total Pemilih Terdaftar', $results['voters']]);
            fputcsv($handle, ['Sudah Memilih', $results['participated']]);
            fputcsv($handle, ['Tingkat Partisipasi (%)', $results['turnout']]);
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
