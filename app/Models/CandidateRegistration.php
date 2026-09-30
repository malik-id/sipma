<?php

namespace App\Models;

use App\Enums\RegistrationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_id',
        'registration_number',
        'chairman_student_id',
        'vice_chairman_student_id',
        'chairman_phone',
        'vice_chairman_phone',
        'vision',
        'mission',
        'photo_path',
        'status',
        'submitted_at',
        'resubmitted_at',
        'verified_at',
        'established_at',
        'revision_deadline',
        'verified_by',
        'reviewed_by',
        'rejection_reason',
        'revision_notes',
    ];

    protected function casts(): array
    {
        return array_merge(array_fill_keys(['submitted_at', 'resubmitted_at', 'verified_at', 'established_at', 'revision_deadline'], 'datetime'), ['status' => RegistrationStatus::class, 'mission' => 'array']);
    }

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function chairman()
    {
        return $this->belongsTo(Student::class, 'chairman_student_id');
    }

    public function viceChairman()
    {
        return $this->belongsTo(Student::class, 'vice_chairman_student_id');
    }

    public function documents()
    {
        return $this->hasMany(CandidateRegistrationDocument::class);
    }

    public function currentDocuments()
    {
        return $this->documents()->whereNull('superseded_at');
    }

    public function histories()
    {
        return $this->hasMany(CandidateRegistrationHistory::class)->orderBy('id');
    }

    public function programs()
    {
        return $this->hasMany(CandidateProgram::class)->orderBy('sort_order');
    }

    public function members()
    {
        return $this->hasMany(RegistrationMember::class);
    }

    public function answers()
    {
        return $this->hasMany(RequirementAnswer::class);
    }

    public function candidate()
    {
        return $this->hasOne(Candidate::class);
    }
}
