<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateRegistrationHistory extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['candidate_registration_id','status_from','status_to','notes','changed_by','created_at'];
    protected function casts(): array { return ['created_at'=>'datetime']; }
    protected static function booted(): void { static::updating(fn () => throw new \LogicException('Riwayat tidak dapat diubah.')); static::deleting(fn () => throw new \LogicException('Riwayat tidak dapat dihapus.')); }
}

