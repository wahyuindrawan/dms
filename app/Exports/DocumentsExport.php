<?php

namespace App\Exports;

use App\Models\Document;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DocumentsExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize,
    WithTitle,
    ShouldQueue
{
    use Exportable;

    public function __construct(
        protected ?int    $year            = null,
        protected ?int    $documentTypeId  = null,
        protected ?int    $unitId          = null,
        protected ?int    $categoryId      = null,
    ) {}

    public function query()
    {
        return Document::query()
            ->with(['documentType', 'category', 'sourceUnit', 'creator'])
            ->when($this->year, fn ($q) => $q->whereYear('document_date', $this->year))
            ->when($this->documentTypeId, fn ($q) => $q->where('document_type_id', $this->documentTypeId))
            ->when($this->unitId, fn ($q) => $q->where('source_unit_id', $this->unitId))
            ->when($this->categoryId, fn ($q) => $q->where('document_category_id', $this->categoryId))
            ->orderBy('document_date', 'desc');
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor Dokumen',
            'Judul / Nama',
            'Jenis Dokumen',
            'Klaster',
            'Unit',
            'Tanggal Dokumen',
            'Tahun',
            'Status',
            'Dibuat Oleh',
            'Tanggal Input',
        ];
    }

    public function map($doc): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $doc->doc_number ?? '-',
            $doc->title,
            $doc->documentType?->name ?? '-',
            $doc->category?->name ?? '-',
            $doc->sourceUnit?->name ?? '-',
            $doc->document_date?->format('d/m/Y') ?? '-',
            $doc->document_date?->format('Y') ?? '-',
            $doc->status,
            $doc->creator?->name ?? '-',
            $doc->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF3B82F6']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }

    public function title(): string
    {
        return 'Laporan Dokumen';
    }
}
