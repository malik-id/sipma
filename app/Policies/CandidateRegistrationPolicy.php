<?php

namespace App\Policies;

use App\Models\CandidateRegistration;
use App\Models\User;

class CandidateRegistrationPolicy
{
    public function view(User $user, CandidateRegistration $registration): bool
    {
        return $user->hasPermission('review-registrations') || ($user->role === 'student' && $user->student_id && in_array($user->student_id, [$registration->chairman_student_id, $registration->vice_chairman_student_id]));
    }

    public function update(User $user, CandidateRegistration $registration): bool
    {
        return $user->active && $user->role === 'student' && $user->student_id === $registration->chairman_student_id;
    }
}
