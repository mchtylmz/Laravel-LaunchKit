<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function (): void {
    Route::get('/user', function (Request $request) {
        $user = $request->user()->load('roles');
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'roles' => $user->getRoleNames(),
                'permissions' => $user->getAllPermissions()->pluck('name'),
                'created_at' => $user->created_at,
            ],
        ]);
    })->name('api.user');

    Route::get('/dashboard/stats', function (Request $request) {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'user_count' => \App\Models\User::count(),
                'role_count' => \Spatie\Permission\Models\Role::count(),
                'file_count' => \App\Models\ManagedFile::where('user_id', $user->id)->count(),
                'activity_count' => \Spatie\Activitylog\Models\Activity::count(),
            ],
        ]);
    })->name('api.dashboard.stats');
});
