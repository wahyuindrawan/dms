<div class="p-6">
    <h2 class="text-lg font-semibold mb-4">{{ $record->judul }}</h2>

    <div class="space-y-2">
        <div class="flex">
            <span class="w-32 font-semibold">Waktu</span>
            <span>: {{ $record->waktu ? \Carbon\Carbon::parse($record->waktu)->translatedFormat('d F Y, H:i') : '-' }}</span>
        </div>
        <div class="flex">
            <span class="w-32 font-semibold">Tempat</span>
            <span>: {{ $record->tempat ?? '-' }}</span>
        </div>
        <div class="flex">
            <span class="w-32 font-semibold">Peserta</span>
            <span>:
                @php
                $totalWorker = \App\Models\Worker::count();
                $isPesertaAll = count($record->workers) === $totalWorker;
                @endphp
                @if ($isPesertaAll)
                Semua Karyawan
                @else
                {{ $record->workers->pluck('nama')->implode(', ') }}
                @endif
            </span>
        </div>
        @if ($record->deskripsi)
        <div class="flex">
            <span class="w-32 font-semibold">Deskripsi</span>
            <span>: {{ $record->deskripsi }}</span>
        </div>
        @endif
    </div>

    @if ($record->dokumen_path)
    <div class="mt-6">
        <h3 class="text-sm font-semibold mb-1">Lampiran/Preview File:</h3>
        <span>
            @if (Str::endsWith($record->dokumen_path, ['.pdf']))
            <iframe src="{{ Storage::url($record->dokumen_path) }}" class="w-full h-96 border rounded"></iframe>
            @elseif (Str::endsWith($record->dokumen_path, ['.jpg', '.jpeg', '.png']))
            <img src="{{ Storage::url($record->dokumen_path) }}" class="max-w-full h-auto rounded" />
            @else
            <a href="{{ Storage::url($record->dokumen_path) }}" target="_blank" class="text-blue-600 underline">Lihat Lampiran</a>
            @endif
        </span>
    </div>
    @else
    <p class="text-sm text-gray-500 italic">Tidak ada file terlampir.</p>
    @endif
</div>