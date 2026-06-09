<x-layouts.app title="File manager">
    <section class="card">
        <h2 class="text-lg font-semibold">Upload file</h2>
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
                    <div>
                        <h3 class="font-semibold">{{ $file->original_name }}</h3>
                        <p class="mt-1 text-sm text-slate-500">{{ $file->sizeForHumans() }} · {{ $file->user->name }}</p>
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
