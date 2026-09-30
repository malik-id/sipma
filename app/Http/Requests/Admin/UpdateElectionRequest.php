<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateElectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'registration_start' => ['required', 'date'],
            'registration_end' => ['required', 'date', 'after:registration_start'],
            'verification_start' => ['required', 'date', 'after_or_equal:registration_start'],
            'verification_end' => ['required', 'date', 'after:verification_start'],
            'candidate_finalization_at' => ['nullable', 'date', 'after_or_equal:verification_end'],
            'campaign_start' => ['nullable', 'date'],
            'campaign_end' => ['nullable', 'date', 'after:campaign_start'],
            'voting_start' => ['required', 'date'],
            'voting_end' => ['required', 'date', 'after:voting_start'],
            'result_publish_at' => ['nullable', 'date', 'after_or_equal:voting_end'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama Periode',
            'registration_start' => 'Mulai Pendaftaran',
            'registration_end' => 'Selesai Pendaftaran',
            'verification_start' => 'Mulai Verifikasi',
            'verification_end' => 'Selesai Verifikasi',
            'candidate_finalization_at' => 'Finalisasi Calon',
            'campaign_start' => 'Mulai Kampanye',
            'campaign_end' => 'Selesai Kampanye',
            'voting_start' => 'Mulai Pemungutan Suara',
            'voting_end' => 'Selesai Pemungutan Suara',
            'result_publish_at' => 'Tanggal Publikasi Hasil',
        ];
    }
}
