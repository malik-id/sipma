<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['user_id','actor_type','action','entity_type','entity_id','old_values','new_values','ip_address','user_agent','created_at'];
    protected function casts(): array { return ['old_values'=>'array','new_values'=>'array','created_at'=>'datetime']; }
    public function user() { return $this->belongsTo(User::class); }
    protected static function booted(): void { static::updating(fn () => throw new \LogicException('Audit tidak dapat diubah.')); static::deleting(fn () => throw new \LogicException('Audit tidak dapat dihapus.')); }
}

