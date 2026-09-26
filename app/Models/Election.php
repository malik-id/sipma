<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    use HasFactory;

    protected $fillable = ['name','slug','description','registration_start','registration_end','verification_start','verification_end','candidate_finalization_at','campaign_start','campaign_end','voting_start','voting_end','result_publish_at','status','created_by'];
    protected function casts(): array { return array_merge(array_fill_keys(['registration_start','registration_end','verification_start','verification_end','candidate_finalization_at','campaign_start','campaign_end','voting_start','voting_end','result_publish_at'], 'datetime'), ['status'=>\App\Enums\ElectionStatus::class]); }
    public function voters() { return $this->hasMany(Voter::class); }
    public function candidates() { return $this->hasMany(Candidate::class); }
    public function requirements() { return $this->hasMany(CandidateRequirement::class)->orderBy('sort_order'); }
    public function registrations() { return $this->hasMany(CandidateRegistration::class); }
    public function participations() { return $this->hasMany(VotingParticipation::class); }
    public function ballots() { return $this->hasMany(Ballot::class); }
    public function registrationOpen(): bool { return $this->status === \App\Enums\ElectionStatus::Registration && now()->gte($this->registration_start) && now()->lt($this->registration_end); }
    public function verificationOpen(): bool { return in_array($this->status, [\App\Enums\ElectionStatus::Registration,\App\Enums\ElectionStatus::Verification]) && now()->gte($this->verification_start) && now()->lt($this->verification_end); }
    public function votingOpen(): bool { return $this->status === \App\Enums\ElectionStatus::Voting && now()->gte($this->voting_start) && now()->lt($this->voting_end); }
    public function resultsPublic(): bool { return $this->status === \App\Enums\ElectionStatus::Published && now()->gte($this->voting_end) && $this->result_publish_at && now()->gte($this->result_publish_at); }
    public function assertMutable(): void { \App\Support\Rule::ensure(now()->lt($this->voting_start) && ! $this->participations()->exists(), 'Data pemilihan dibekukan sejak pemungutan suara dimulai.', 'election'); }
}
