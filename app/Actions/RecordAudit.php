<?php

namespace App\Actions;

use App\Models\AuditLog;
use App\Models\Ballot;
use App\Models\User;
use App\Models\VotingParticipation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

final class RecordAudit
{
    public function handle(?User $actor, string $action, ?Model $entity = null, ?array $before = [], ?array $after = []): void
    {
        // Explicit whitelist. Never accept request bodies, OAuth payloads, or ballot data.
        $keys = ['name', 'nim', 'email', 'study_program', 'class_year', 'semester', 'student_status', 'voter_status', 'status', 'candidate_number', 'role', 'active', 'permissions', 'registration_start', 'registration_end', 'verification_start', 'verification_end', 'voting_start', 'voting_end', 'result_publish_at', 'required', 'type', 'verification_status', 'total', 'created', 'updated', 'skipped', 'failed', 'key', 'value', 'election_id', 'filename', 'imported'];
        if ($entity instanceof Ballot || $entity instanceof VotingParticipation) {
            throw new \LogicException('Suara/partisipasi tidak boleh dicatat dalam audit administratif.');
        }
        AuditLog::create([
            'user_id' => $actor?->id,
            'actor_type' => $actor?->role ?? 'system',
            'action' => $action,
            'entity_type' => $entity ? $entity->getTable() : 'deleted_record',
            'entity_id' => $entity ? (string) $entity->getKey() : null,
            'old_values' => Arr::only($before ?? [], $keys),
            'new_values' => Arr::only($after ?? [], $keys),
            'ip_address' => app()->runningInConsole() ? null : request()->ip(),
            'user_agent' => app()->runningInConsole() ? null : mb_substr(request()->userAgent() ?? '', 0, 500),
            'created_at' => now(),
        ]);
    }
}
