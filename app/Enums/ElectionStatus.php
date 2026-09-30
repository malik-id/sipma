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

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft (Penyusunan)',
            self::Registration => 'Pendaftaran Calon',
            self::Verification => 'Verifikasi Berkas',
            self::CandidateFinalization => 'Penetapan Calon',
            self::Upcoming => 'Masa Tenang / Menjelang Voting',
            self::Voting => 'Pemungutan Suara (Voting)',
            self::Closed => 'Voting Ditutup',
            self::Published => 'Hasil Dipublikasikan',
            self::Archived => 'Diarsipkan',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Draft => 'bg-slate-100 text-slate-700 border-slate-300',
            self::Registration => 'bg-amber-50 text-amber-700 border-amber-300',
            self::Verification => 'bg-indigo-50 text-indigo-700 border-indigo-300',
            self::CandidateFinalization => 'bg-purple-50 text-purple-700 border-purple-300',
            self::Upcoming => 'bg-cyan-50 text-cyan-700 border-cyan-300',
            self::Voting => 'bg-emerald-50 text-emerald-700 border-emerald-300 animate-pulse',
            self::Closed => 'bg-rose-50 text-rose-700 border-rose-300',
            self::Published => 'bg-blue-50 text-blue-700 border-blue-300',
            self::Archived => 'bg-gray-100 text-gray-500 border-gray-300',
        };
    }
}
