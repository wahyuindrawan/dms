@php
    use App\Models\Agenda;

    $agendaToday = Agenda::whereDate('waktu', now())->get();
@endphp

<x-filament::card>
    <h2 class="text-xl font-bold mb-4">Agenda Hari Ini</h2>

    @if ($agendaToday->isEmpty())
        <p class="text-gray-500">Tidak ada agenda untuk hari ini.</p>
    @else
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr>
                    <th class="px-4 py-2">Judul</th>
                    <th class="px-4 py-2">Waktu</th>
                    <th class="px-4 py-2">Tempat</th>
                    <th class="px-4 py-2">Pegawai</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($agendaToday as $agenda)
                    <tr class="bg-white border-b">
                        <td class="px-4 py-2">{{ $agenda->judul }}</td>
                        <td class="px-4 py-2">
                            {{ \Carbon\Carbon::parse($agenda->waktu)->format('d M Y') }}
                            @if ($agenda->jam)
                                , {{ $agenda->jam }}
                            @endif
                        </td>
                        <td class="px-4 py-2">{{ $agenda->tempat }}</td>
                        <td class="px-4 py-2">{{ $agenda->worker_id}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</x-filament::card>
