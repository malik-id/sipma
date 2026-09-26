<?php
namespace App\Http\Controllers;
use App\Actions\{AuthenticateGoogleStudentAction, RecordAudit};
use App\Http\Requests\AdminLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
class AuthController extends Controller
{
    public function login() { return view('auth.login'); }
    public function google()
    {
        if (! config('services.google.client_id') || ! config('services.google.client_secret')) { return redirect()->route('login')->withErrors(['email'=>'Login Google belum dikonfigurasi oleh pengelola. Hubungi panitia.']); }
        return Socialite::driver('google')->scopes(['openid','profile','email'])->redirect();
    }
    public function callback(Request $request, AuthenticateGoogleStudentAction $action)
    {
        try { $google = Socialite::driver('google')->user(); }
        catch (\Throwable) { return redirect()->route('login')->withErrors(['email'=>'Autentikasi Google tidak berhasil atau sesi kedaluwarsa. Silakan masuk kembali.']); }
        try { $user = $action->handle($google); }
        catch (ValidationException $e) { return redirect()->route('login')->withErrors($e->errors()); }
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }
    public function admin(AdminLoginRequest $request, RecordAudit $audit)
    {
        $user = User::where('email',$request->validated('email'))->whereIn('role',['admin','super_admin'])->where('active',true)->first();
        $valid = Hash::check($request->validated('password'), $user?->password ?? '$2y$12$9PHcy.MkfSycdjkEHH7j1.V0wvP4OW0khvuEtgcKwPLU.jYDjfxCK');
        if (! $user || ! $valid) { throw ValidationException::withMessages(['email'=>'Email atau kata sandi tidak sesuai.']); }
        Auth::login($user);
        $request->session()->regenerate();
        $audit->handle($user,'admin.login',$user);
        return redirect()->route('admin.dashboard');
    }
    public function logout(Request $request)
    {
        Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
