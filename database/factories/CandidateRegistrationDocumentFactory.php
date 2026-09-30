<?php

namespace Database\Factories;

use App\Models\CandidateRegistration;
use App\Models\CandidateRegistrationDocument;
use App\Models\CandidateRequirement;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidateRegistrationDocumentFactory extends Factory
{
    protected $model = CandidateRegistrationDocument::class;

    public function definition(): array
    {
        return ['candidate_registration_id' => CandidateRegistration::factory(), 'requirement_id' => fn (array $a) => CandidateRequirement::factory()->create(['election_id' => CandidateRegistration::find($a['candidate_registration_id'])->election_id])->id, 'document_type' => 'document', 'file_path' => 'documents/fixture.pdf', 'original_filename' => 'persyaratan.pdf', 'mime_type' => 'application/pdf', 'file_size' => 1024, 'version' => 1, 'verification_status' => 'pending'];
    }
}
