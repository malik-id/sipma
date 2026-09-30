<?php

namespace App\Http\Controllers\Admin;

use App\Actions\RecordAudit;
use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SystemSettingController extends Controller
{
    public function index(): View
    {
        Gate::authorize('super-admin');

        $settings = SystemSetting::pluck('value', 'key')->all();

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request, RecordAudit $audit): RedirectResponse
    {
        Gate::authorize('super-admin');

        $validated = $request->validate([
            'app_name' => ['required', 'string', 'max:100'],
            'organization_name' => ['required', 'string', 'max:100'],
            'faculty_name' => ['required', 'string', 'max:150'],
            'contact_email' => ['nullable', 'email', 'max:100'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'announcement_banner' => ['nullable', 'string', 'max:1000'],
            'allowed_email_domain' => ['nullable', 'string', 'max:100'],
        ]);

        $old = SystemSetting::pluck('value', 'key')->all();

        foreach ($validated as $key => $value) {
            SystemSetting::set($key, $value);
        }

        $audit->handle($request->user(), 'system.settings_updated', null, $old, $validated);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Konfigurasi aplikasi berhasil diperbarui.');
    }
}
