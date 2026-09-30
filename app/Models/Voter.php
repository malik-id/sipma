<?php

namespace App\Models;

use App\Enums\VoterStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voter extends Model
{
    use HasFactory;

    protected $fillable = ['election_id', 'student_id', 'voter_status', 'verified_at', 'verified_by', 'notes'];

    protected function casts(): array
    {
        return ['voter_status' => VoterStatus::class, 'verified_at' => 'datetime'];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function participation()
    {
        return $this->hasOne(VotingParticipation::class);
    }
}
