<?php

namespace App\Enums;

enum RegistrationStatus: string
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case UnderReview = 'under_review';
    case RevisionRequired = 'revision_required';
    case Resubmitted = 'resubmitted';
    case Verified = 'verified';
    case Rejected = 'rejected';
    case Established = 'established';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft', self::Submitted => 'Diajukan', self::UnderReview => 'Dalam pemeriksaan',
            self::RevisionRequired => 'Perlu perbaikan', self::Resubmitted => 'Dikirim ulang',
            self::Verified => 'Terverifikasi', self::Rejected => 'Ditolak', self::Established => 'Ditetapkan',
        };
    }

    public function permits(self $next): bool
    {
        return in_array($next, match ($this) {
            self::Draft => [self::Submitted],
            self::Submitted, self::Resubmitted => [self::UnderReview],
            self::UnderReview => [self::RevisionRequired, self::Verified, self::Rejected],
            self::RevisionRequired => [self::Resubmitted],
            self::Verified => [self::Established],
            default => [],
        }, true);
    }
}
