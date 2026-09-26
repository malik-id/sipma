<?php
namespace App\Enums;
enum ElectionStatus: string
{
    case Draft = 'draft';
    case Registration = 'registration';
    case Verification = 'verification';
    case CandidateFinalization = 'candidate_finalization';
    case Upcoming = 'upcoming';
    case Voting = 'voting';
    case Closed = 'closed';
    case Published = 'published';
    case Archived = 'archived';
}
