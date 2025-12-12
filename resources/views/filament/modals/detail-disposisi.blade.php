<div class="p-6">
    <h2 class="text-lg font-semibold mb-4">{{ $record->suratMasuk->judul ?? 'Detail Disposisi' }}</h2>

    <div class="space-y-2">
        <div class="flex">
            <span class="w-32 font-semibold">Nomor Surat</span>
            <span>: {{ $record->suratMasuk->nomor_surat ?? '-' }}</span>
        </div>
        <div class="flex">
            <span class="w-32 font-semibold">Tanggal Surat</span>
            <span>: {{ $record->suratMasuk ? \Carbon\Carbon::parse($record->suratMasuk->tanggal_surat)->translatedFormat('d F Y') : '-' }}</span>
        </div>
        <div class="flex">
            <span class="w-32 font-semibold">Dari Pegawai</span>
            <span>: {{ $record->dariWorker->nama ?? '-' }}</span>
        </div>
        <div class="flex">
            <span class="w-32 font-semibold">Kepada Pegawai</span>
            <span>: {{ $record->keWorker->nama ?? '-' }}</span>
        </div>
        <div class="flex">
            <span class="w-32 font-semibold">Tanggal Disposisi</span>
            <span>: {{ \Carbon\Carbon::parse($record->created_at)->translatedFormat('d F Y H:i') }}</span>
        </div>
        <div class="flex">
            <span class="w-32 font-semibold">Status</span>
            <span>:
                @if ($record->status == 'belum')
                <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-800">Belum Diproses</span>
                @elseif ($record->status == 'proses')
                <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Sedang Diproses</span>
                @elseif ($record->status == 'selesai')
                <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Selesai</span>
                @else
                <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-800">{{ ucfirst($record->status) }}</span>
                @endif
            </span>
        </div>
        @if ($record->catatan)
        <div class="flex">
            <span class="w-32 font-semibold">Catatan</span>
            <span>: {{ $record->catatan }}</span>
        </div>
        @endif
    </div>

    @if ($record->suratMasuk && $record->suratMasuk->file_path)
    <div class="mt-6">
        <h3 class="text-sm font-semibold mb-1">Lampiran/Preview File:</h3>
        <span>
            @if (Str::endsWith($record->suratMasuk->file_path, ['.pdf']))
            <iframe src="{{ Storage::url($record->suratMasuk->file_path) }}" class="w-full h-96 border rounded"></iframe>
            @elseif (Str::endsWith($record->suratMasuk->file_path, ['.jpg', '.jpeg', '.png']))
            <img src="{{ Storage::url($record->suratMasuk->file_path) }}" class="max-w-full h-auto rounded" />
            @else
            <a href="{{ Storage::url($record->suratMasuk->file_path) }}" target="_blank" class="text-blue-600 underline">Lihat Lampiran</a>
            @endif
        </span>
    </div>
    @else
    <p class="text-sm text-gray-500 italic">Tidak ada file terlampir.</p>
    @endif
</div>