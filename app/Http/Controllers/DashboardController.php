<?php

namespace App\Http\Controllers;

use App\Models\ManagedFile;
use App\Models\User;
use Illuminate\Support\Collection;
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
            'charts' => [
                'activities' => $this->dailyCounts(Activity::class),
                'users' => $this->dailyCounts(User::class),
                'files' => $this->dailyCounts(ManagedFile::class),
            ],
        ]);
    }

    /**
     * @param  class-string  $model
     */
    private function dailyCounts(string $model): Collection
    {
        $days = collect(range(6, 0))->map(fn (int $daysAgo) => now()->subDays($daysAgo));
        $counts = $model::query()
            ->whereDate('created_at', '>=', $days->first()->toDateString())
            ->get()
            ->groupBy(fn ($item) => $item->created_at->toDateString())
            ->map->count();

        return $days->map(fn ($day) => [
            'label' => $day->format('D'),
            'date' => $day->toDateString(),
            'count' => $counts->get($day->toDateString(), 0),
        ]);
    }
}
