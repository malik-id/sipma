<?php

namespace Database\Factories;

use App\Models\CandidateRequirement;
use App\Models\Election;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidateRequirementFactory extends Factory
{
    protected $model = CandidateRequirement::class;

    public function definition(): array
    {
        return ['election_id' => Election::factory(), 'name' => 'Surat pernyataan', 'type' => 'document', 'required' => true, 'active' => true, 'allowed_extensions' => ['pdf', 'jpg', 'jpeg', 'png'], 'max_file_size' => 2048, 'sort_order' => 0];
    }
}
