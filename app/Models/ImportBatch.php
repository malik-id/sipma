<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportBatch extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id','user_id','election_id','type','mode','rows','summary','expires_at','committed_at'];
    protected function casts(): array { return ['rows'=>'array','summary'=>'array','expires_at'=>'datetime','committed_at'=>'datetime']; }
}

