<?php
namespace App\Actions;
use App\Models\Election;
final class CalculateElectionResultAction
{
    public function handle(Election $election): array
    {
        abort_unless($election->resultsPublic(), 403, 'Hasil belum dipublikasikan.');
        $candidates = $election->candidates()->with(['chairman','viceChairman'])->withCount('ballots')->orderBy('candidate_number')->get();
        $total = (int) $candidates->sum('ballots_count');
        $eligible = $election->voters()->where('voter_status','eligible')->count();
        $participated = $election->participations()->count();
        return ['candidates'=>$candidates,'total'=>$total,'eligible'=>$eligible,'voters'=>$election->voters()->count(),'participated'=>$participated,'turnout'=>$eligible ? round($participated/$eligible*100,2) : 0];
    }
}
