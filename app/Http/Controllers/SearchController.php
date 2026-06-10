<?php

namespace App\Http\Controllers;

use App\Models\ManagedFile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class SearchController extends Controller
{
    public function __invoke(Request $request): View
    {
        $q = $request->validate(['q' => ['required', 'string', 'max:120']])['q'];

        $users = User::query()
            ->where('name', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->limit(5)
            ->get();

        $files = ManagedFile::query()
            ->with('user')
            ->where('original_name', 'like', "%{$q}%")
            ->limit(5)
            ->get();

        $activities = Activity::query()
            ->with('causer')
            ->where('description', 'like', "%{$q}%")
            ->limit(10)
            ->get();

        return view('search.index', compact('q', 'users', 'files', 'activities'));
    }
}
