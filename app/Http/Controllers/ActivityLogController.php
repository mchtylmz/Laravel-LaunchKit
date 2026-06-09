<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()?->can('view activity logs'), 403);

        return view('activity-logs.index', [
            'activities' => Activity::query()->latest()->paginate(15),
        ]);
    }
}
