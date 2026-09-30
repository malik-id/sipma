<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRequirementRequest;
use App\Http\Requests\Admin\UpdateRequirementRequest;
use App\Models\AuditLog;
use App\Models\CandidateRequirement;
use App\Models\Election;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ElectionRequirementController extends Controller
{
    /**
     * Display a listing of requirements for the given election.
     */
    public function index(Election $election): View
    {
        $requirements = $election->requirements()->orderBy('sort_order')->get();

        return view('admin.elections.requirements.index', compact('election', 'requirements'));
    }

    /**
     * Show the form for creating a new requirement.
     */
    public function create(Election $election): View
    {
        $election->assertMutable();
        $nextSortOrder = ($election->requirements()->max('sort_order') ?? 0) + 1;

        return view('admin.elections.requirements.create', compact('election', 'nextSortOrder'));
    }

    /**
     * Store a newly created requirement in storage.
     */
    public function store(StoreRequirementRequest $request, Election $election): RedirectResponse
    {
        $election->assertMutable();

        $validated = $request->validated();
        $validated['required'] = $request->boolean('required');
        $validated['active'] = $request->boolean('active', true);

        // Ensure allowed_extensions is array if string given or defaults
        if ($validated['type'] === 'text') {
            $validated['allowed_extensions'] = null;
            $validated['max_file_size'] = null;
        }

        $requirement = $election->requirements()->create($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'actor_type' => 'admin',
            'action' => 'requirement.create',
            'entity_type' => CandidateRequirement::class,
            'entity_id' => (string) $requirement->id,
            'new_values' => $requirement->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return redirect()
            ->route('admin.elections.requirements.index', $election)
            ->with('success', "Syarat '{$requirement->name}' berhasil ditambahkan.");
    }

    /**
     * Show the form for editing the specified requirement.
     */
    public function edit(Election $election, CandidateRequirement $requirement): View
    {
        $election->assertMutable();

        return view('admin.elections.requirements.edit', compact('election', 'requirement'));
    }

    /**
     * Update the specified requirement in storage.
     */
    public function update(UpdateRequirementRequest $request, Election $election, CandidateRequirement $requirement): RedirectResponse
    {
        $election->assertMutable();

        $validated = $request->validated();
        $validated['required'] = $request->boolean('required');
        $validated['active'] = $request->boolean('active', true);

        if ($validated['type'] === 'text') {
            $validated['allowed_extensions'] = null;
            $validated['max_file_size'] = null;
        }

        $oldValues = $requirement->toArray();
        $requirement->update($validated);

        AuditLog::create([
            'user_id' => auth()->id(),
            'actor_type' => 'admin',
            'action' => 'requirement.update',
            'entity_type' => CandidateRequirement::class,
            'entity_id' => (string) $requirement->id,
            'old_values' => $oldValues,
            'new_values' => $requirement->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return redirect()
            ->route('admin.elections.requirements.index', $election)
            ->with('success', "Syarat '{$requirement->name}' berhasil diperbarui.");
    }

    /**
     * Toggle active status of a requirement.
     */
    public function toggle(Election $election, CandidateRequirement $requirement): RedirectResponse
    {
        $election->assertMutable();

        $requirement->update(['active' => ! $requirement->active]);

        $statusText = $requirement->active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Syarat '{$requirement->name}' berhasil {$statusText}.");
    }

    /**
     * Remove the specified requirement from storage.
     */
    public function destroy(Election $election, CandidateRequirement $requirement): RedirectResponse
    {
        $election->assertMutable();

        $name = $requirement->name;
        $requirement->delete();

        return redirect()
            ->route('admin.elections.requirements.index', $election)
            ->with('success', "Syarat '{$name}' berhasil dihapus.");
    }
}
