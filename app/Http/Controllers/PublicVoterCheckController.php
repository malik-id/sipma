<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Student;
use App\Models\Voter;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicVoterCheckController extends Controller
{
    public function index(Request $request): View
    {
        $keyword = trim((string) $request->input('keyword'));
        $electionId = $request->input('election_id');

        $activeElections = Election::whereIn('status', [
            'upcoming',
            'registration',
            'verification',
            'candidate_finalization',
            'voting',
            'closed',
            'published',
        ])->latest('id')->get();

        $selectedElection = $electionId
            ? $activeElections->firstWhere('id', (int) $electionId)
            : $activeElections->first();

        $student = null;
        $voter = null;
        $searched = false;

        if ($keyword !== '') {
            $searched = true;
            $student = Student::where('nim', $keyword)
                ->orWhere('email', mb_strtolower($keyword))
                ->first();

            if ($student && $selectedElection) {
                $voter = Voter::where('election_id', $selectedElection->id)
                    ->where('student_id', $student->id)
                    ->first();
            }
        }

        return view('public.check-voter', [
            'keyword' => $keyword,
            'searched' => $searched,
            'student' => $student,
            'voter' => $voter,
            'elections' => $activeElections,
            'selectedElection' => $selectedElection,
        ]);
    }
}
