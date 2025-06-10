<div class="p-6">
    <h2 class="text-lg font-semibold mb-4">{{ $record->judul }}</h2>

    <div class="space-y-2">
        <div class="flex">
            <span class="w-32 font-semibold">Nomor Surat</span>
            <span>: {{ $record->nomor_surat }}</span>
        </div>
        <div class="flex">
            <span class="w-32 font-semibold">Tanggal Surat</span>
            <span>: {{ \Carbon\Carbon::parse($record->tanggal_surat)->translatedFormat('d F Y') }}</span>
        </div>
        <div class="flex">
            <span class="w-32 font-semibold">Asal</span>
            <span>: {{ $record->sumber->nama ?? '-' }}</span>
        </div>
        <div class="flex">
            <span class="w-32 font-semibold">Perihal</span>
            <span>: {{ $record->perihal }}</span>
        </div>
        <div class="flex">
            <span class="w-32 font-semibold">Keterangan</span>
            <span>: {{ $record->deskripsi }}</span>
        </div>
    </div>

    @if ($record->file_path)
    <div class="mt-6">
        <h3 class="text-sm font-semibold mb-1">Lampiran/Preview File:</h3>
        <span>
            @if (Str::endsWith($record->file_path, ['.pdf']))
            <iframe src="{{ Storage::url($record->file_path) }}" class="w-full h-96 border rounded"></iframe>
            @elseif (Str::endsWith($record->file_path, ['.jpg', '.jpeg', '.png']))
            <img src="{{ Storage::url($record->file_path) }}" class="max-w-full h-auto rounded" />
            @else
            <a href="{{ Storage::url($record->file_path) }}" target="_blank" class="text-blue-600 underline">Lihat Lampiran</a>
            @endif
        </span>
    </div>
    @else
    <p class="text-sm text-gray-500 italic">Tidak ada file terlampir.</p>
    @endif
</div>