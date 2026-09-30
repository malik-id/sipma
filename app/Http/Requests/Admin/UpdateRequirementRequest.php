<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequirementRequest extends FormRequest
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
            'type' => ['required', 'string', 'in:file,text,image'],
            'required' => ['boolean'],
            'allowed_extensions' => ['nullable', 'array'],
            'allowed_extensions.*' => ['string', 'in:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,zip'],
            'max_file_size' => ['nullable', 'integer', 'min:1', 'max:51200'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'active' => ['boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama Syarat',
            'type' => 'Tipe Syarat',
            'required' => 'Status Wajib',
            'allowed_extensions' => 'Ekstensi Diizinkan',
            'max_file_size' => 'Ukuran Maks File',
        ];
    }
}
