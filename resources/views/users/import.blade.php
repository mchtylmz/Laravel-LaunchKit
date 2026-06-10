<x-layouts.app title="Import users">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <p class="section-title">Team access</p>
            <h2 class="mt-1 text-2xl font-black">Import users from CSV</h2>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Upload a CSV file with name, email, and password columns.</p>
        </div>
        <a class="btn-secondary" href="{{ route('users.index') }}">Back</a>
    </div>

    <div class="card max-w-2xl">
        <h3 class="text-lg font-bold">Upload CSV</h3>

        <div class="mt-4 rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm dark:border-slate-800 dark:bg-slate-950">
            <p class="font-semibold">Expected format:</p>
            <pre class="mt-2 font-mono text-xs">name,email,password
John Doe,john@example.com,secret123
Jane Doe,jane@example.com,secret456</pre>
        </div>

        <form method="POST" action="{{ route('users.import.store') }}" enctype="multipart/form-data" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-600 dark:text-slate-300">CSV file</label>
                <input class="w-full" name="csv" type="file" accept=".csv,.txt" required>
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-slate-600 dark:text-slate-300">Default role</label>
                <select class="w-full" name="default_role" required>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn-primary" type="submit">Import</button>
        </form>
    </div>
</x-layouts.app>
