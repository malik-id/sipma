<?php
namespace App\Actions;
use App\Models\{Election, Student, RegistrationMember};
use App\Support\Rule;
final class CheckCandidateEligibilityAction
{
    public function handle(Student $student, Election $election, ?int $exceptRegistration = null): void
    {
        Rule::ensure($student->isActive(), 'Bakal calon harus mahasiswa aktif.', 'student');
        Rule::ensure($student->semester >= 3 && $student->semester <= 5, 'Bakal calon Ketua dan Wakil Ketua harus berada pada semester 3 sampai dengan semester 5.', 'semester');
        $query = RegistrationMember::where('election_id',$election->id)->where('student_id',$student->id);
        if ($exceptRegistration) { $query->where('candidate_registration_id','!=',$exceptRegistration); }
        Rule::ensure(! $query->exists(), 'Mahasiswa sudah terdaftar pada pasangan lain dalam periode ini.', 'student');
    }
}
