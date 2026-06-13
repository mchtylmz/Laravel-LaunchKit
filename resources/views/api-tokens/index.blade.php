<x-layouts.app title="API Tokens">
    <div class="mb-6">
        <p class="section-title">Developer</p>
        <h2 class="mt-1 text-2xl font-black">API tokens</h2>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Manage tokens for programmatic access to the API.</p>
    </div>

    @if (session('token'))
        <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-900 dark:bg-emerald-950">
            <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-200">Your new token</p>
            <p class="mt-1 text-xs text-emerald-600 dark:text-emerald-400">Copy this now — it will not be shown again.</p>
            <div class="mt-2 rounded-md bg-white px-3 py-2 font-mono text-sm dark:bg-slate-900">
                {{ session('token') }}
            </div>
        </div>
    @endif

    <div class="card">
        <h3 class="text-lg font-bold">Create token</h3>
        <form method="POST" action="{{ route('api-tokens.store') }}" class="mt-5 flex flex-col gap-3 sm:flex-row">
            @csrf
            <input class="flex-1" name="name" placeholder="Token name (e.g. My App)" required>
            <button class="btn-primary" type="submit">Create token</button>
        </form>
    </div>

    <div class="card mt-6">
        <h3 class="text-lg font-bold">Your tokens</h3>
        @if ($tokens->isEmpty())
            <p class="mt-5 text-sm text-slate-500">No tokens created yet.</p>
        @else
            <div class="mt-5 space-y-3">
                @foreach ($tokens as $token)
                    <div class="flex items-center justify-between rounded-lg border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-950">
                        <div>
                            <p class="font-medium text-slate-900 dark:text-white">{{ $token->name }}</p>
                            <p class="text-xs text-slate-400">
                                Created {{ $token->created_at->diffForHumans() }}
                                @if ($token->last_used_at)
                                    · Last used {{ $token->last_used_at->diffForHumans() }}
                                @else
                                    · Never used
                                @endif
                            </p>
                        </div>
                        <form method="POST" action="{{ route('api-tokens.destroy', $token->id) }}" onsubmit="return confirm('Delete token &ldquo;{{ $token->name }}&rdquo;? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button class="rounded-lg border border-red-200 bg-white px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50 dark:border-red-900 dark:bg-slate-900 dark:text-red-400 dark:hover:bg-red-950" type="submit">Delete</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>
