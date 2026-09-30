<?php

namespace App\Models;

use App\Enums\ElectionStatus;
use App\Support\Rule;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Election extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'registration_start',
        'registration_end',
        'verification_start',
        'verification_end',
        'candidate_finalization_at',
        'campaign_start',
        'campaign_end',
        'voting_start',
        'voting_end',
        'result_publish_at',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return array_merge(
            array_fill_keys([
                'registration_start',
                'registration_end',
                'verification_start',
                'verification_end',
                'candidate_finalization_at',
                'campaign_start',
                'campaign_end',
                'voting_start',
                'voting_end',
                'result_publish_at',
            ], 'datetime'),
            ['status' => ElectionStatus::class]
        );
    }

    protected static function booted(): void
    {
        static::creating(function (Election $election) {
            if (empty($election->slug)) {
                $election->slug = Str::slug($election->name).'-'.Str::lower(Str::random(5));
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function voters(): HasMany
    {
        return $this->hasMany(Voter::class);
    }

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }

    public function requirements(): HasMany
    {
        return $this->hasMany(CandidateRequirement::class)->orderBy('sort_order');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(CandidateRegistration::class);
    }

    public function participations(): HasMany
    {
        return $this->hasMany(VotingParticipation::class);
    }

    public function ballots(): HasMany
    {
        return $this->hasMany(Ballot::class);
    }

    public function registrationOpen(): bool
    {
        return $this->status === ElectionStatus::Registration
            && now()->gte($this->registration_start)
            && now()->lt($this->registration_end);
    }

    public function verificationOpen(): bool
    {
        return in_array($this->status, [ElectionStatus::Registration, ElectionStatus::Verification])
            && now()->gte($this->verification_start)
            && now()->lt($this->verification_end);
    }

    public function votingOpen(): bool
    {
        return $this->status === ElectionStatus::Voting
            && now()->gte($this->voting_start)
            && now()->lt($this->voting_end);
    }

    public function resultsPublic(): bool
    {
        return $this->status === ElectionStatus::Published
            && now()->gte($this->voting_end)
            && $this->result_publish_at
            && now()->gte($this->result_publish_at);
    }

    public function assertMutable(): void
    {
        Rule::ensure(
            now()->lt($this->voting_start) && ! $this->participations()->exists(),
            'Data pemilihan dibekukan sejak pemungutan suara dimulai atau sudah ada suara yang masuk.',
            'election'
        );
    }

    /**
     * Get the current active timeline stage name and next milestone for countdown display.
     */
    public function getCurrentPhaseInfo(): array
    {
        $now = now();

        if ($this->status === ElectionStatus::Draft) {
            return [
                'phase' => 'Penyusunan (Draft)',
                'badge' => 'Draft',
                'target_time' => $this->registration_start,
                'target_label' => 'Mulai Pendaftaran',
                'is_active' => false,
                'state' => 'draft',
            ];
        }

        if ($now->lt($this->registration_start)) {
            return [
                'phase' => 'Menuju Pembukaan Pendaftaran',
                'badge' => 'Segera Dibuka',
                'target_time' => $this->registration_start,
                'target_label' => 'Pendaftaran Dibuka Dalam',
                'is_active' => true,
                'state' => 'pre_registration',
            ];
        }

        if ($now->between($this->registration_start, $this->registration_end)) {
            return [
                'phase' => 'Pendaftaran Bakal Calon Dibuka',
                'badge' => 'Pendaftaran Berlangsung',
                'target_time' => $this->registration_end,
                'target_label' => 'Batas Akhir Pendaftaran',
                'is_active' => true,
                'state' => 'registration',
            ];
        }

        if ($now->between($this->verification_start, $this->verification_end)) {
            return [
                'phase' => 'Verifikasi Berkas & Administrasi',
                'badge' => 'Verifikasi',
                'target_time' => $this->verification_end,
                'target_label' => 'Selesai Verifikasi',
                'is_active' => true,
                'state' => 'verification',
            ];
        }

        if ($this->campaign_start && $this->campaign_end && $now->between($this->campaign_start, $this->campaign_end)) {
            return [
                'phase' => 'Masa Kampanye Calon',
                'badge' => 'Kampanye',
                'target_time' => $this->campaign_end,
                'target_label' => 'Akhir Masa Kampanye',
                'is_active' => true,
                'state' => 'campaign',
            ];
        }

        if ($now->lt($this->voting_start)) {
            return [
                'phase' => 'Masa Tenang Menjelang Voting',
                'badge' => 'Masa Tenang',
                'target_time' => $this->voting_start,
                'target_label' => 'Pemungutan Suara Dimulai Dalam',
                'is_active' => true,
                'state' => 'pre_voting',
            ];
        }

        if ($now->between($this->voting_start, $this->voting_end)) {
            return [
                'phase' => 'Pemungutan Suara (E-Voting) Aktif',
                'badge' => 'E-Voting Aktif',
                'target_time' => $this->voting_end,
                'target_label' => 'Batas Waktu Voting Berakhir Dalam',
                'is_active' => true,
                'state' => 'voting',
            ];
        }

        if ($this->result_publish_at && $now->lt($this->result_publish_at)) {
            return [
                'phase' => 'Perhitungan Suara & Rekapitulasi',
                'badge' => 'Rekapitulasi',
                'target_time' => $this->result_publish_at,
                'target_label' => 'Pengumuman Hasil Resmi Dalam',
                'is_active' => true,
                'state' => 'tallying',
            ];
        }

        return [
            'phase' => 'Pemilihan Selesai',
            'badge' => 'Selesai',
            'target_time' => null,
            'target_label' => 'Hasil Resmi Telah Diumumkan',
            'is_active' => false,
            'state' => 'completed',
        ];
    }
}
