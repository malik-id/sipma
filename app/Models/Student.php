<?php

namespace App\Models;

use App\Enums\StudentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['nim', 'name', 'email', 'study_program', 'class_year', 'semester', 'student_status', 'phone'];

    protected function casts(): array
    {
        return ['semester' => 'integer', 'class_year' => 'integer', 'student_status' => StudentStatus::class];
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function voters()
    {
        return $this->hasMany(Voter::class);
    }

    public function registrations()
    {
        return $this->hasMany(CandidateRegistration::class, 'chairman_student_id');
    }

    public function setEmailAttribute($value): void
    {
        $this->attributes['email'] = mb_strtolower(trim($value));
    }

    public function isActive(): bool
    {
        return $this->student_status === StudentStatus::Active;
    }
}
