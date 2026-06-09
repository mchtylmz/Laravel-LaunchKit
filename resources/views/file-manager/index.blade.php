<x-layouts.app title="File manager">
    <section class="card">
        <p class="section-title">Storage</p>
        <h2 class="mt-1 text-2xl font-black">Upload file</h2>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Keep simple project assets and documents inside the starter kit.</p>
        <form method="POST" action="{{ route('file-manager.store') }}" enctype="multipart/form-data" class="mt-4 flex flex-col gap-3 sm:flex-row">
            @csrf
            <input class="flex-1" name="file" type="file" required>
            <button class="btn-primary" type="submit">Upload</button>
        </form>
    </section>

    <section class="mt-6 grid gap-4 lg:grid-cols-2">
        @foreach ($files as $file)
            <div class="card">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex gap-3">
                        <span class="grid h-10 w-10 place-items-center rounded-lg bg-slate-100 text-xs font-bold text-slate-500 dark:bg-slate-950">FILE</span>
                        <div>
                        <h3 class="font-semibold">{{ $file->original_name }}</h3>
                        <p class="mt-1 text-sm text-slate-500">{{ $file->sizeForHumans() }} · {{ $file->user->name }}</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a class="btn-secondary" href="{{ route('file-manager.download', $file) }}">Download</a>
                        <form method="POST" action="{{ route('file-manager.destroy', $file) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn-secondary" type="submit">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </section>
    <div class="mt-4">{{ $files->links() }}</div>
</x-layouts.app>
