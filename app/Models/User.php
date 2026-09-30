<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function assignedRole()
    {
        return $this->belongsTo(Role::class, 'role', 'name');
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin'], true);
    }

    public function isSupervisor(): bool
    {
        return $this->role === 'dosen_pendamping';
    }

    public function canAccessAdminPanel(): bool
    {
        return in_array($this->role, ['admin', 'super_admin', 'dosen_pendamping'], true);
    }

    public function hasPermission(string $permission): bool
    {
        if (! $this->active) {
            return false;
        }

        if ($this->role === 'super_admin') {
            return true;
        }

        if (in_array($this->role, ['admin', 'dosen_pendamping'], true)) {
            return in_array($permission, $this->assignedRole?->permissions ?? [], true);
        }

        return false;
    }
}
