<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateRegistrationHistory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['candidate_registration_id', 'status_from', 'status_to', 'notes', 'changed_by', 'created_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }

    protected static function booted(): void
    {
        static::creating(function (self $model): void {
            $model->created_at ??= now();
        });
        static::updating(fn () => throw new \LogicException('Riwayat tidak dapat diubah.'));
    }
}
