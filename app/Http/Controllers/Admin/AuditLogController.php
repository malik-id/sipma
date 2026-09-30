<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('view-audit');

        $query = AuditLog::with('user')->latest('id');

        if ($action = $request->input('action')) {
            $query->where('action', 'like', "%{$action}%");
        }

        if ($entityType = $request->input('entity_type')) {
            $query->where('entity_type', 'like', "%{$entityType}%");
        }

        if ($userId = $request->input('user_id')) {
            $query->where('user_id', $userId);
        }

        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                    ->orWhere('entity_type', 'like', "%{$search}%")
                    ->orWhere('entity_id', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            });
        }

        $logs = $query->paginate(25)->withQueryString();
        $users = User::whereIn('role', ['super_admin', 'admin', 'dosen_pendamping'])->get();

        $distinctActions = AuditLog::query()
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        return view('admin.audit-logs.index', compact('logs', 'users', 'distinctActions'));
    }
}
