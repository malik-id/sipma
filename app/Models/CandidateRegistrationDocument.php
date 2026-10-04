<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CandidateRegistrationDocument extends Model
{
    use HasFactory;

    protected $fillable = ['candidate_registration_id', 'requirement_id', 'document_type', 'file_path', 'original_filename', 'mime_type', 'file_size', 'version'];

    protected function casts(): array
    {
        return ['verified_at' => 'datetime', 'superseded_at' => 'datetime'];
    }

    public function registration()
    {
        return $this->belongsTo(CandidateRegistration::class, 'candidate_registration_id');
    }

    public function requirement()
    {
        return $this->belongsTo(CandidateRequirement::class, 'requirement_id');
    }

    public function fileExists(): bool
    {
        return Storage::disk('local')->exists($this->file_path)
            || Storage::disk('public')->exists($this->file_path);
    }
}
