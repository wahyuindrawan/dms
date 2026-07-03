<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Dokumen</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #111; }
        h1   { font-size: 16px; text-align: center; margin-bottom: 4px; }
        .meta { text-align: center; font-size: 9px; color: #555; margin-bottom: 16px; }
        .filter-info { font-size: 9px; color: #444; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th    { background: #3B82F6; color: white; padding: 6px 8px; text-align: left; font-size: 9px; }
        td    { padding: 5px 8px; border-bottom: 1px solid #E5E7EB; font-size: 9px; }
        tr:nth-child(even) td { background: #F3F4F6; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-weight: bold; }
        .badge-draft    { background: #FEF3C7; color: #92400E; }
        .badge-active   { background: #D1FAE5; color: #065F46; }
        .badge-archived { background: #F3F4F6; color: #374151; }
        .badge-void     { background: #FEE2E2; color: #991B1B; }
        .footer { margin-top: 20px; font-size: 8px; color: #999; text-align: right; }
    </style>
</head>
<body>
    <h1>LAPORAN DOKUMEN</h1>
    <div class="meta">{{ config('app.name') }} — Dicetak pada {{ now()->format('d/m/Y H:i') }}</div>

    <div class="filter-info">
        <strong>Filter:</strong>
        Tahun: {{ $year ?? 'Semua' }} |
        Jenis: {{ $typeName ?? 'Semua' }} |
        Unit: {{ $unitName ?? 'Semua' }} |
        Klaster: {{ $clusterName ?? 'Semua' }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="15%">Nomor</th>
                <th width="30%">Nama / Judul</th>
                <th width="10%">Jenis</th>
                <th width="12%">Klaster</th>
                <th width="10%">Tanggal</th>
                <th width="9%">Status</th>
                <th width="10%">Dibuat Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($documents as $i => $doc)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $doc->doc_number ?? '—' }}</td>
                <td>{{ $doc->title }}</td>
                <td>{{ $doc->documentType?->code ?? '—' }}</td>
                <td>{{ $doc->category?->name ?? '—' }}</td>
                <td>{{ $doc->document_date?->format('d/m/Y') ?? '—' }}</td>
                <td>
                    <span class="badge badge-{{ $doc->status }}">{{ ucfirst($doc->status) }}</span>
                </td>
                <td>{{ $doc->creator?->name ?? '—' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center; padding: 20px; color: #999;">
                    Tidak ada data dengan filter yang dipilih.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Total: {{ $documents->count() }} dokumen
    </div>
</body>
</html>
