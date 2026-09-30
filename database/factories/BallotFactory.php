<?php

namespace Database\Factories;

use App\Models\Ballot;
use App\Models\Candidate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BallotFactory extends Factory
{
    protected $model = Ballot::class;

    public function definition(): array
    {
        $uuid = (string) Str::uuid();

        return ['id' => $uuid, 'ballot_uuid' => $uuid, 'candidate_id' => Candidate::factory(), 'election_id' => fn (array $a) => Candidate::find($a['candidate_id'])->election_id, 'submitted_at' => now()->endOfDay(), 'created_at' => now()->endOfDay()];
    }
}
