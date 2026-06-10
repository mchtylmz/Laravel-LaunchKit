<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(auth()->user()?->can('view activity logs'), 403);

        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'causer_id' => ['nullable', 'integer', 'exists:users,id'],
            'log_name' => ['nullable', 'string', 'max:120'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
        ]);

        $query = Activity::query()
            ->with('causer')
            ->when($filters['q'] ?? null, fn ($query, $search) => $query->where('description', 'like', "%{$search}%"))
            ->when($filters['causer_id'] ?? null, fn ($query, $causerId) => $query->where('causer_id', $causerId))
            ->when($filters['log_name'] ?? null, fn ($query, $logName) => $query->where('log_name', $logName))
            ->when($filters['from'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '>=', $date))
            ->when($filters['to'] ?? null, fn ($query, $date) => $query->whereDate('created_at', '<=', $date))
            ->latest();

        return view('activity-logs.index', [
            'activities' => $query->paginate(15)->withQueryString(),
            'users' => User::query()->orderBy('name')->get(['id', 'name']),
            'logNames' => Activity::query()->select('log_name')->distinct()->orderBy('log_name')->pluck('log_name'),
            'filters' => $filters,
        ]);
    }
}
