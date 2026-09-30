<?php

namespace App\Enums;

enum VoterStatus: string
{
    case Eligible = 'eligible';
    case NotEligible = 'not_eligible';
    case Suspended = 'suspended';
}
