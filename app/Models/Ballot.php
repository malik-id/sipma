<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ballot extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id','ballot_uuid','election_id','candidate_id','integrity_hash','submitted_at','created_at'];
    protected function casts(): array { return ['submitted_at'=>'datetime','created_at'=>'datetime']; }
    public function election() { return $this->belongsTo(Election::class); }
    public function candidate() { return $this->belongsTo(Candidate::class); }
}

