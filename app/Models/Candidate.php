<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = ['election_id','candidate_registration_id','candidate_number','chairman_student_id','vice_chairman_student_id','vision','mission','photo_path','status','established_at'];
    protected function casts(): array { return ['mission'=>'array','established_at'=>'datetime']; }
    public function election() { return $this->belongsTo(Election::class); }
    public function registration() { return $this->belongsTo(CandidateRegistration::class, 'candidate_registration_id'); }
    public function chairman() { return $this->belongsTo(Student::class, 'chairman_student_id'); }
    public function viceChairman() { return $this->belongsTo(Student::class, 'vice_chairman_student_id'); }
    public function ballots() { return $this->hasMany(Ballot::class); }
    public function scopePublished($query) { return $query->where('status','active')->whereNotNull('candidate_number')->whereHas('election', fn ($q) => $q->whereNotIn('status',['draft','archived'])); }
}

