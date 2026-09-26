<?php
namespace App\Actions;
use App\Models\{Student, User};
use App\Support\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as GoogleUser;
final class AuthenticateGoogleStudentAction
{
    public function handle(GoogleUser $google): User
    {
        $raw = $google->getRaw();
        Rule::ensure(($raw['email_verified'] ?? $raw['verified_email'] ?? false) === true, 'Email Google belum terverifikasi.', 'email');
        $email = mb_strtolower(trim((string) $google->getEmail()));
        Rule::ensure(filter_var($email,FILTER_VALIDATE_EMAIL) !== false && filled($google->getId()), 'Identitas Google tidak valid.', 'email');
        return DB::transaction(function () use ($google,$email) {
            $student = Student::where('email',$email)->lockForUpdate()->first();
            Rule::ensure($student !== null, 'Akun Google Anda belum terdaftar pada database Fakultas Ilmu Komputer.', 'email');
            Rule::ensure($student->isActive(), 'Akun mahasiswa Anda tidak aktif. Hubungi panitia.', 'email');
            Rule::ensure(! $student->google_id || hash_equals($student->google_id,(string) $google->getId()), 'Identitas Google tidak cocok dengan akun terdaftar.', 'email');
            Rule::ensure(! Student::where('google_id',(string) $google->getId())->whereKeyNot($student->id)->exists(), 'Identitas Google sudah terhubung dengan akun lain.', 'email');
            $user = User::where('student_id',$student->id)->orWhere('email',$email)->lockForUpdate()->first();
            Rule::ensure(! $user || ($user->active && $user->role === 'student' && $user->student_id === $student->id), 'Akun tidak dapat digunakan untuk login mahasiswa.', 'email');
            $user ??= new User;
            $user->forceFill(['student_id'=>$student->id,'name'=>$student->name,'email'=>$email,'role'=>'student','active'=>true,'email_verified_at'=>now()]);
            if (! $user->exists) { $user->password = Str::random(64); }
            $user->save();
            $student->forceFill(['google_id'=>(string) $google->getId()])->save();
            return $user;
        },3);
    }
}
