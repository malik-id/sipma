<?php

namespace Tests\Feature;

use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupervisorAndAuditLogTest extends TestCase
{
    use RefreshDatabase;

    private User $supervisor;

    private User $superAdmin;

    private User $admin;

    private User $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->supervisor = User::factory()->create([
            'role' => 'dosen_pendamping',
            'active' => true,
        ]);

        $this->superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'active' => true,
        ]);

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'active' => true,
        ]);

        $this->student = User::factory()->create([
            'role' => 'student',
            'active' => true,
        ]);
    }

    public function test_supervisor_can_access_admin_dashboard_and_audit_logs(): void
    {
        $this->actingAs($this->supervisor)
            ->get(route('admin.dashboard'))
            ->assertOk();

        $this->actingAs($this->supervisor)
            ->get(route('admin.audit-logs.index'))
            ->assertOk();
    }

    public function test_supervisor_cannot_access_settings_or_student_management(): void
    {
        $this->actingAs($this->supervisor)
            ->get(route('admin.settings.index'))
            ->assertForbidden();

        $this->actingAs($this->supervisor)
            ->get(route('admin.students.index'))
            ->assertForbidden();
    }

    public function test_super_admin_can_view_and_update_settings(): void
    {
        $this->actingAs($this->superAdmin)
            ->get(route('admin.settings.index'))
            ->assertOk();

        $this->actingAs($this->superAdmin)
            ->post(route('admin.settings.update'), [
                'organization_name' => 'HIMAKOM 2026/2027',
                'faculty_name' => 'Fakultas Ilmu Komputer',
                'app_name' => 'SIPMA Updated',
                'allow_registration' => '1',
            ])
            ->assertRedirect(route('admin.settings.index'))
            ->assertSessionHas('success');

        $this->assertEquals('HIMAKOM 2026/2027', SystemSetting::get('organization_name'));
        $this->assertEquals('SIPMA Updated', SystemSetting::get('app_name'));

        // Verify audit log recorded setting update
        $this->assertDatabaseHas('audit_logs', [
            'user_id' => $this->superAdmin->id,
            'action' => 'system.settings_updated',
        ]);
    }

    public function test_regular_admin_cannot_access_settings(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.settings.index'))
            ->assertForbidden();
    }
}
