<x-layouts.app title="Notifications">
    <div class="grid gap-5 sm:grid-cols-3">
        @foreach ([['Total', $stats['total'], 'All notification records'], ['Unread', $stats['unread'], 'Needs attention'], ['Read', $stats['read'], 'Already cleared']] as [$label, $value, $description])
            <div class="metric-card">
                <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">{{ $label }}</p>
                <p class="mt-3 text-4xl font-black">{{ $value }}</p>
                <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">{{ $description }}</p>
            </div>
        @endforeach
    </div>

    <section class="card mt-6">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="section-title">Inbox</p>
                <h2 class="mt-1 text-2xl font-black">Notification center</h2>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Filter system activity and batch-handle the items that matter.</p>
            </div>
            <form method="GET" action="{{ route('notifications.index') }}" class="grid gap-3 md:grid-cols-4">
                <input class="md:col-span-2" name="q" placeholder="Search title or message" value="{{ $filters['q'] ?? '' }}">
                <select name="status">
                    @foreach (['all' => 'All statuses', 'unread' => 'Unread only', 'read' => 'Read only'] as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['status'] ?? 'all') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <div class="flex gap-3">
                    <select class="flex-1" name="type">
                        <option value="">All types</option>
                        @foreach ($types as $type)
                            <option value="{{ $type }}" @selected(($filters['type'] ?? null) === $type)>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                    <button class="btn-secondary" type="submit">Filter</button>
                </div>
            </form>
        </div>
    </section>

    <section class="card mt-6">
        <form method="POST" action="{{ route('notifications.bulk') }}">
            @csrf
            <div class="flex flex-col gap-3 border-b border-slate-100 pb-4 dark:border-white/10 md:flex-row md:items-center md:justify-between">
                <p class="text-sm text-slate-500 dark:text-slate-400">Select notifications, then apply a bulk action.</p>
                <div class="flex gap-3">
                    <select name="action">
                        <option value="read">Mark as read</option>
                        <option value="unread">Mark as unread</option>
                        <option value="delete">Delete selected</option>
                    </select>
                    <button class="btn-primary" type="submit">Apply</button>
                </div>
            </div>

            <div class="mt-5 space-y-3">
                @forelse ($notifications as $notification)
                    <div class="rounded-lg border border-slate-100 bg-slate-50 p-4 dark:border-white/10 dark:bg-slate-950">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div class="flex gap-3">
                                <input class="mt-1" name="notification_ids[]" type="checkbox" value="{{ $notification->id }}">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="font-semibold">{{ $notification->title }}</p>
                                        <span class="rounded-full px-2 py-1 text-[11px] font-bold {{ $notification->read_at ? 'bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-200' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-400/10 dark:text-emerald-300' }}">
                                            {{ $notification->read_at ? 'Read' : 'Unread' }}
                                        </span>
                                        <span class="rounded-full bg-indigo-50 px-2 py-1 text-[11px] font-bold text-indigo-700 dark:bg-indigo-400/10 dark:text-indigo-300">
                                            {{ ucfirst($notification->type) }}
                                        </span>
                                    </div>
                                    <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500 dark:text-slate-400">{{ $notification->message }}</p>
                                    <p class="mt-2 text-xs text-slate-400">{{ $notification->created_at->format('Y-m-d H:i') }}</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @if ($notification->read_at)
                                    <form method="POST" action="{{ route('notifications.unread', $notification) }}">
                                        @csrf
                                        <button class="btn-secondary" type="submit">Mark unread</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                        @csrf
                                        <input name="redirect" type="hidden" value="0">
                                        <button class="btn-secondary" type="submit">Mark read</button>
                                    </form>
                                @endif
                                @if ($notification->url)
                                    <a class="btn-secondary" href="{{ $notification->url }}">Open</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="rounded-lg bg-slate-50 p-4 text-sm text-slate-500 dark:bg-slate-950 dark:text-slate-400">No notifications matched the current filters.</p>
                @endforelse
            </div>
        </form>

        <div class="mt-4">{{ $notifications->links() }}</div>
    </section>
</x-layouts.app>
