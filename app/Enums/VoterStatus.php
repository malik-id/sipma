<?php

namespace App\Enums;

enum VoterStatus: string
{
    case Eligible = 'eligible';
    case NotEligible = 'not_eligible';
    case Suspended = 'suspended';

    public function label(): string
    {
        return match ($this) {
            self::Eligible => 'Eligible (Berhak Memilih)',
            self::NotEligible => 'Tidak Berhak',
            self::Suspended => 'Ditangguhkan',
        };
    }
}
