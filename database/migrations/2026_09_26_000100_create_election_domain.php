<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $t) {
            $t->string('name')->primary();
            $t->json('permissions');
            $t->timestamps();
        });
        Schema::create('students', function (Blueprint $t) {
            $t->id();
            $t->string('nim', 30)->unique();
            $t->string('name');
            $t->string('email')->unique();
            $t->string('study_program', 100);
            $t->unsignedSmallInteger('class_year');
            $t->unsignedTinyInteger('semester')->index();
            $t->string('student_status', 20)->default('active');
            $t->string('google_id')->nullable()->unique();
            $t->string('phone', 30)->nullable();
            $t->timestamps();
        });
        Schema::table('users', function (Blueprint $t) {
            $t->foreignId('student_id')->nullable()->unique()->constrained()->restrictOnDelete();
            $t->string('role')->default('student')->index();
            $t->boolean('active')->default(true);
        });
        Schema::create('elections', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->text('description')->nullable();
            foreach (['registration_start','registration_end','verification_start','verification_end','voting_start','voting_end'] as $column) {
                $t->dateTime($column);
            }
            foreach (['candidate_finalization_at','campaign_start','campaign_end','result_publish_at'] as $column) {
                $t->dateTime($column)->nullable();
            }
            $t->string('status', 30)->default('draft')->index();
            $t->foreignId('created_by')->nullable()->constrained('users')->restrictOnDelete();
            $t->timestamps();
        });
        Schema::create('voters', function (Blueprint $t) {
            $t->id();
            $t->foreignId('election_id')->constrained()->restrictOnDelete();
            $t->foreignId('student_id')->constrained()->restrictOnDelete();
            $t->string('voter_status', 20)->default('not_eligible');
            $t->dateTime('verified_at')->nullable();
            $t->foreignId('verified_by')->nullable()->constrained('users')->restrictOnDelete();
            $t->text('notes')->nullable();
            $t->timestamps();
            $t->unique(['election_id','student_id']);
            $t->unique(['id','election_id']);
            $t->index(['election_id','voter_status']);
        });
        Schema::create('candidate_requirements', function (Blueprint $t) {
            $t->id();
            $t->foreignId('election_id')->constrained()->restrictOnDelete();
            $t->string('name');
            $t->text('description')->nullable();
            $t->string('type', 30);
            $t->boolean('required')->default(true);
            $t->json('allowed_extensions')->nullable();
            $t->unsignedInteger('max_file_size')->nullable();
            $t->unsignedSmallInteger('sort_order')->default(0);
            $t->boolean('active')->default(true);
            $t->timestamps();
        });
        Schema::create('candidate_registrations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('election_id')->constrained()->restrictOnDelete();
            $t->string('registration_number')->nullable()->unique();
            $t->foreignId('chairman_student_id')->constrained('students')->restrictOnDelete();
            $t->foreignId('vice_chairman_student_id')->nullable()->constrained('students')->restrictOnDelete();
            $t->string('chairman_phone', 30)->nullable();
            $t->string('vice_chairman_phone', 30)->nullable();
            $t->text('vision')->nullable();
            $t->json('mission')->nullable();
            $t->string('photo_path')->nullable();
            $t->string('status', 30)->default('draft');
            foreach (['submitted_at','resubmitted_at','verified_at','established_at','revision_deadline'] as $column) {
                $t->dateTime($column)->nullable();
            }
            $t->foreignId('verified_by')->nullable()->constrained('users')->restrictOnDelete();
            $t->foreignId('reviewed_by')->nullable()->constrained('users')->restrictOnDelete();
            $t->text('rejection_reason')->nullable();
            $t->text('revision_notes')->nullable();
            $t->timestamps();
            $t->index(['election_id','status']);
        });
        Schema::create('registration_members', function (Blueprint $t) {
            $t->id();
            $t->foreignId('election_id')->constrained()->restrictOnDelete();
            $t->foreignId('candidate_registration_id')->constrained()->restrictOnDelete();
            $t->foreignId('student_id')->constrained()->restrictOnDelete();
            $t->string('position', 20);
            $t->unique(['election_id','student_id']);
            $t->unique(['candidate_registration_id','position'], 'members_registration_position_unique');
        });
        Schema::create('candidate_registration_documents', function (Blueprint $t) {
            $t->id();
            $t->foreignId('candidate_registration_id')->constrained('candidate_registrations', indexName: 'crd_candidate_reg_id_foreign')->restrictOnDelete();
            $t->foreignId('requirement_id')->constrained('candidate_requirements', indexName: 'crd_requirement_id_foreign')->restrictOnDelete();
            $t->string('document_type', 30)->default('document');
            $t->string('file_path');
            $t->string('original_filename');
            $t->string('mime_type', 100);
            $t->unsignedInteger('file_size');
            $t->unsignedInteger('version')->default(1);
            $t->dateTime('superseded_at')->nullable();
            $t->string('verification_status', 30)->default('pending');
            $t->text('verification_note')->nullable();
            $t->foreignId('verified_by')->nullable()->constrained('users')->restrictOnDelete();
            $t->dateTime('verified_at')->nullable();
            $t->timestamps();
            $t->unique(['candidate_registration_id','requirement_id','version'], 'registration_requirement_version_unique');
        });
        Schema::create('requirement_answers', function (Blueprint $t) {
            $t->id();
            $t->foreignId('candidate_registration_id')->constrained()->restrictOnDelete();
            $t->foreignId('requirement_id')->constrained('candidate_requirements')->restrictOnDelete();
            $t->text('value');
            $t->timestamps();
            $t->unique(['candidate_registration_id','requirement_id'], 'registration_requirement_answer_unique');
        });
        Schema::create('candidate_registration_histories', function (Blueprint $t) {
            $t->id();
            $t->foreignId('candidate_registration_id')->constrained('candidate_registrations', indexName: 'crh_candidate_reg_id_foreign')->restrictOnDelete();
            $t->string('status_from', 30)->nullable();
            $t->string('status_to', 30);
            $t->text('notes')->nullable();
            $t->foreignId('changed_by')->nullable()->constrained('users')->restrictOnDelete();
            $t->dateTime('created_at');
        });
        Schema::create('candidate_programs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('candidate_registration_id')->constrained('candidate_registrations', indexName: 'cp_candidate_reg_id_foreign')->restrictOnDelete();
            $t->string('title');
            $t->text('description')->nullable();
            $t->unsignedSmallInteger('sort_order')->default(0);
            $t->timestamps();
        });
        Schema::create('candidates', function (Blueprint $t) {
            $t->id();
            $t->foreignId('election_id')->constrained()->restrictOnDelete();
            $t->foreignId('candidate_registration_id')->nullable()->unique()->constrained()->restrictOnDelete();
            $t->unsignedSmallInteger('candidate_number')->nullable();
            $t->foreignId('chairman_student_id')->constrained('students')->restrictOnDelete();
            $t->foreignId('vice_chairman_student_id')->constrained('students')->restrictOnDelete();
            $t->text('vision');
            $t->json('mission');
            $t->string('photo_path')->nullable();
            $t->string('status', 20)->default('active');
            $t->dateTime('established_at');
            $t->timestamps();
            $t->unique(['election_id','candidate_number']);
            $t->unique(['id','election_id']);
        });
        Schema::create('voting_participations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('election_id')->constrained()->restrictOnDelete();
            $t->unsignedBigInteger('voter_id');
            $t->dateTime('voted_at');
            $t->dateTime('created_at');
            $t->unique(['election_id','voter_id']);
            $t->foreign(['voter_id','election_id'])->references(['id','election_id'])->on('voters')->restrictOnDelete();
        });
        Schema::create('ballots', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->foreignId('election_id')->constrained()->restrictOnDelete();
            $t->unsignedBigInteger('candidate_id');
            $t->uuid('ballot_uuid')->unique();
            $t->string('integrity_hash', 64)->nullable();
            $t->dateTime('submitted_at');
            $t->dateTime('created_at');
            $t->foreign(['candidate_id','election_id'])->references(['id','election_id'])->on('candidates')->restrictOnDelete();
            $t->index(['election_id','candidate_id']);
        });
        Schema::create('audit_logs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->constrained()->restrictOnDelete();
            $t->string('actor_type', 30);
            $t->string('action', 100)->index();
            $t->string('entity_type', 100);
            $t->string('entity_id')->nullable();
            $t->json('old_values')->nullable();
            $t->json('new_values')->nullable();
            $t->ipAddress('ip_address')->nullable();
            $t->string('user_agent', 500)->nullable();
            $t->dateTime('created_at')->index();
        });
        Schema::create('system_settings', function (Blueprint $t) {
            $t->string('key')->primary();
            $t->text('value')->nullable();
            $t->timestamps();
        });
        Schema::create('notifications', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->string('type');
            $t->morphs('notifiable');
            $t->text('data');
            $t->timestamp('read_at')->nullable();
            $t->timestamps();
        });
        Schema::create('import_batches', function (Blueprint $t) {
            $t->uuid('id')->primary();
            $t->foreignId('user_id')->constrained()->restrictOnDelete();
            $t->foreignId('election_id')->nullable()->constrained()->restrictOnDelete();
            $t->string('type', 20);
            $t->string('mode', 20);
            $t->json('rows');
            $t->json('summary')->nullable();
            $t->dateTime('expires_at');
            $t->dateTime('committed_at')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['import_batches','notifications','system_settings','audit_logs','ballots','voting_participations','candidates','candidate_programs','candidate_registration_histories','requirement_answers','candidate_registration_documents','registration_members','candidate_registrations','candidate_requirements','voters','elections'] as $table) {
            Schema::dropIfExists($table);
        }
        Schema::table('users', function (Blueprint $t) {
            $t->dropConstrainedForeignId('student_id');
            $t->dropColumn(['role','active']);
        });
        Schema::dropIfExists('students');
        Schema::dropIfExists('roles');
    }
};
