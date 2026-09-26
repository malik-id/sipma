<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequirementAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['candidate_registration_id','requirement_id','value'];
    public function requirement() { return $this->belongsTo(CandidateRequirement::class, 'requirement_id'); }
}

