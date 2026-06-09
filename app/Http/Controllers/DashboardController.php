<?php

namespace App\Http\Controllers;

use App\Models\ManagedFile;
use App\Models\User;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'stats' => [
                'users' => User::query()->count(),
                'roles' => Role::query()->count(),
                'files' => ManagedFile::query()->count(),
                'activities' => Activity::query()->count(),
            ],
            'activities' => Activity::query()->latest()->limit(6)->get(),
            'files' => ManagedFile::query()->with('user')->latest()->limit(5)->get(),
        ]);
    }
}
