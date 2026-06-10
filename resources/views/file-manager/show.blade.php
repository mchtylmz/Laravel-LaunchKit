<x-layouts.app title="File preview">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <p class="section-title">File manager</p>
            <h2 class="mt-1 text-2xl font-black">{{ $managedFile->original_name }}</h2>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                {{ $managedFile->sizeForHumans() }} ·
                {{ $managedFile->categoryLabel() }} ·
                Uploaded by {{ $managedFile->user->name }}
            </p>
        </div>
        <div class="flex gap-3">
            <a class="btn-secondary" href="{{ route('file-manager.download', $managedFile) }}">Download</a>
            <a class="btn-secondary" href="{{ route('file-manager.index') }}">Back</a>
        </div>
    </div>

    <div class="card">
        @if ($isImage)
            <img
                class="mx-auto max-h-[70vh] w-auto rounded-lg object-contain"
                src="{{ $fileUrl }}"
                alt="{{ $managedFile->original_name }}"
            >
        @elseif ($isPdf)
            <iframe
                class="h-[70vh] w-full rounded-lg"
                src="{{ $fileUrl }}"
                title="{{ $managedFile->original_name }}"
            ></iframe>
        @else
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <span class="text-5xl">📄</span>
                <h3 class="mt-4 text-lg font-semibold">Preview not available</h3>
                <p class="mt-2 text-sm text-slate-500">This file type cannot be previewed in the browser.</p>
                <a class="btn-primary mt-6" href="{{ route('file-manager.download', $managedFile) }}">Download file</a>
            </div>
        @endif
    </div>
</x-layouts.app>
