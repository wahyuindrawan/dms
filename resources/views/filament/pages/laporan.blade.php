<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Filter Form --}}
        <x-filament::section>
            <x-slot name="heading">Filter Laporan</x-slot>
            <x-slot name="description">Pilih filter untuk menyaring data laporan. Kosongkan untuk menampilkan semua.</x-slot>

            {{ $this->form }}
        </x-filament::section>

        {{-- Tabel Preview --}}
        <x-filament::section>
            <x-slot name="heading">
                Preview Data
                <span class="ml-2 text-sm font-normal text-gray-500">
                    ({{ $this->getDocuments()->count() }} dokumen ditemukan)
                </span>
            </x-slot>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nomor</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama / Judul</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jenis</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Klaster</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($this->getDocuments() as $i => $doc)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-2 text-gray-500">{{ $i + 1 }}</td>
                            <td class="px-4 py-2 font-mono text-xs font-semibold text-blue-700">
                                {{ $doc->doc_number ?? '—' }}
                            </td>
                            <td class="px-4 py-2 max-w-xs truncate">{{ $doc->title }}</td>
                            <td class="px-4 py-2">
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                    {{ $doc->documentType?->code ?? '—' }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-gray-600">{{ $doc->category?->name ?? '—' }}</td>
                            <td class="px-4 py-2 text-gray-600">
                                {{ $doc->document_date?->format('d/m/Y') ?? '—' }}
                            </td>
                            <td class="px-4 py-2">
                                @php
                                    $colors = [
                                        'draft'    => 'bg-yellow-100 text-yellow-800',
                                        'active'   => 'bg-green-100 text-green-800',
                                        'archived' => 'bg-gray-100 text-gray-800',
                                        'void'     => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $colors[$doc->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($doc->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                                <x-heroicon-o-document-magnifying-glass class="mx-auto h-10 w-10 mb-3 opacity-40" />
                                <p>Tidak ada dokumen dengan filter yang dipilih.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
