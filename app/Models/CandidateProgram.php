<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateProgram extends Model
{
    use HasFactory;

    protected $fillable = ['candidate_registration_id','title','description','sort_order'];
}

