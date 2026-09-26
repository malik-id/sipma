<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VotingParticipation extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['election_id','voter_id','voted_at','created_at'];
    protected function casts(): array { return ['voted_at'=>'datetime','created_at'=>'datetime']; }
    public function voter() { return $this->belongsTo(Voter::class); }
    public function election() { return $this->belongsTo(Election::class); }
}

