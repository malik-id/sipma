<?php
namespace App\Actions;
use App\Models\{AuditLog, User};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
final class RecordAudit
{
    public function handle(?User $actor, string $action, Model $entity, array $before = [], array $after = []): void
    {
        // Explicit whitelist. Never accept request bodies, OAuth payloads, or ballot data.
        $keys = ['name','nim','email','study_program','class_year','semester','student_status','voter_status','status','candidate_number','role','active','permissions','registration_start','registration_end','verification_start','verification_end','voting_start','voting_end','result_publish_at','required','type','verification_status','total','created','updated','skipped','failed','key','value'];
        if ($entity instanceof \App\Models\Ballot || $entity instanceof \App\Models\VotingParticipation) {
            throw new \LogicException('Suara/partisipasi tidak boleh dicatat dalam audit administratif.');
        }
        AuditLog::create(['user_id'=>$actor?->id,'actor_type'=>$actor?->role ?? 'system','action'=>$action,'entity_type'=>$entity->getTable(),'entity_id'=>(string) $entity->getKey(),'old_values'=>Arr::only($before,$keys),'new_values'=>Arr::only($after,$keys),'ip_address'=>app()->runningInConsole() ? null : request()->ip(),'user_agent'=>app()->runningInConsole() ? null : mb_substr(request()->userAgent() ?? '',0,500),'created_at'=>now()]);
    }
}
