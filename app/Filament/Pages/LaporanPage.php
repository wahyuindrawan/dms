<?php

namespace App\Filament\Pages;

use App\Exports\DocumentsExport;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\DocumentType;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Laporan Dokumen';
    protected static ?string $title           = 'Laporan Dokumen';
    protected static ?string $slug            = 'laporan-dokumen';
    protected static ?int    $navigationSort  = 10;
    protected static string  $view            = 'filament.pages.laporan';

    // Form state
    public array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'year'           => (int) date('Y'),
            'documentTypeId' => null,
            'unitId'         => null,
            'categoryId'     => null,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('year')
                    ->label('Tahun')
                    ->options(
                        collect(range(date('Y'), 2020))
                            ->mapWithKeys(fn ($y) => [$y => $y])
                            ->toArray()
                    )
                    ->placeholder('Semua Tahun')
                    ->searchable()
                    ->live(),

                Select::make('documentTypeId')
                    ->label('Jenis Dokumen')
                    ->options(DocumentType::pluck('name', 'id'))
                    ->placeholder('Semua Jenis')
                    ->searchable()
                    ->live(),

                Select::make('unitId')
                    ->label('Unit Organisasi')
                    ->options(Unit::where('is_active', true)->pluck('name', 'id'))
                    ->placeholder('Semua Unit')
                    ->searchable()
                    ->live(),

                Select::make('categoryId')
                    ->label('Klaster Dokumen')
                    ->options(DocumentCategory::where('is_active', true)->pluck('name', 'id'))
                    ->placeholder('Semua Klaster')
                    ->searchable()
                    ->live(),
            ])
            ->columns(4)
            ->statePath('data');
    }

    protected function getData(): array
    {
        return $this->form->getState();
    }

    public function getDocuments()
    {
        $data = $this->form->getState();

        return Document::query()
            ->with(['documentType', 'category', 'sourceUnit', 'creator'])
            ->when($data['year'], fn ($q) => $q->whereYear('document_date', $data['year']))
            ->when($data['documentTypeId'], fn ($q) => $q->where('document_type_id', $data['documentTypeId']))
            ->when($data['unitId'], fn ($q) => $q->where('source_unit_id', $data['unitId']))
            ->when($data['categoryId'], fn ($q) => $q->where('document_category_id', $data['categoryId']))
            ->orderBy('document_date', 'desc')
            ->get();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_excel')
                ->label('Export Excel')
                ->icon('heroicon-o-table-cells')
                ->color('success')
                ->action(function () {
                    $data = $this->form->getState();
                    $count = Document::query()
                        ->when($data['year'], fn ($q) => $q->whereYear('document_date', $data['year']))
                        ->count();

                    // Gunakan Queue jika data > 500 rows
                    $filename = 'laporan-dokumen-' . now()->format('Ymd-His') . '.xlsx';

                    if ($count > 500) {
                        (new DocumentsExport(
                            year: $data['year'],
                            documentTypeId: $data['documentTypeId'],
                            unitId: $data['unitId'],
                            categoryId: $data['categoryId'],
                        ))->queue($filename, 'local')->chain([]);

                        Notification::make()
                            ->title('Export dijadwalkan')
                            ->body("Data terlalu besar ({$count} baris). File akan dikirim via email setelah selesai.")
                            ->warning()
                            ->send();

                        return;
                    }

                    return Excel::download(
                        new DocumentsExport(
                            year: $data['year'],
                            documentTypeId: $data['documentTypeId'],
                            unitId: $data['unitId'],
                            categoryId: $data['categoryId'],
                        ),
                        $filename
                    );
                }),

            Action::make('export_pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->action(function () {
                    $data      = $this->form->getState();
                    $documents = $this->getDocuments();

                    $pdf = Pdf::loadView('exports.documents-pdf', [
                        'documents'   => $documents,
                        'year'        => $data['year'],
                        'typeName'    => $data['documentTypeId']
                            ? DocumentType::find($data['documentTypeId'])?->name
                            : null,
                        'unitName'    => $data['unitId']
                            ? Unit::find($data['unitId'])?->name
                            : null,
                        'clusterName' => $data['categoryId']
                            ? DocumentCategory::find($data['categoryId'])?->name
                            : null,
                    ])->setPaper('a4', 'landscape');

                    $filename = 'laporan-dokumen-' . now()->format('Ymd-His') . '.pdf';

                    // Log aktivitas download
                    activity()->log("Export PDF laporan dokumen ({$documents->count()} data)");

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        $filename
                    );
                }),
        ];
    }
}
