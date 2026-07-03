<?php

namespace App\Filament\Resources\Concerns;

use App\Models\DocumentType;
use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait HasDocumentForm
 *
 * Menyediakan shared form components untuk semua resource dokumen.
 * Setiap resource memanggil method yang diperlukan dan menambahkan
 * section khusus masing-masing dokumen.
 */
trait HasDocumentForm
{
    /**
     * Section informasi utama dokumen.
     * @param string $typeCode  Kode jenis dokumen: SK, SOP, RUK, RPK
     * @param string $label     Label dokumen untuk placeholder
     * @param bool   $withCluster  Tampilkan pilihan Klaster/Cluster
     * @param bool   $withDirection  Tampilkan pilihan Arah Dokumen
     */
    protected static function documentInfoSection(
        string $typeCode,
        string $label,
        bool $withCluster = false,
        bool $withDirection = false,
    ): Forms\Components\Section {
        $type = DocumentType::where('code', $typeCode)->first();

        $fields = [
            Forms\Components\Hidden::make('document_type_id')
                ->default($type?->id),

            Forms\Components\Hidden::make('direction')
                ->default('internal'),

            Forms\Components\TextInput::make('doc_number')
                ->label("Nomor {$label}")
                ->placeholder('Akan terisi otomatis saat disimpan')
                ->disabled()
                ->dehydrated(false)
                ->columnSpan(1),

            Forms\Components\TextInput::make('title')
                ->label("Nama / Judul {$label}")
                ->required()
                ->maxLength(255)
                ->columnSpan(1),
        ];

        // Klaster (Cluster) — hanya untuk SK & SOP
        if ($withCluster) {
            $fields[] = Forms\Components\Select::make('document_category_id')
                ->label('Klaster / Kategori')
                ->relationship(
                    'category',
                    'name',
                    fn (Builder $query) => $query
                        ->where('document_type_id', $type?->id)
                        ->orWhereNull('document_type_id')
                        ->where('is_active', true)
                )
                ->searchable()
                ->preload()
                ->nullable()
                ->createOptionForm([
                    Forms\Components\TextInput::make('code')->required()->unique('document_categories', 'code'),
                    Forms\Components\TextInput::make('name')->required(),
                ])
                ->columnSpan(1);
        }

        // Arah dokumen — hanya untuk SK
        if ($withDirection) {
            $fields[] = Forms\Components\Select::make('direction')
                ->label('Arah Dokumen')
                ->options([
                    'internal' => 'Internal',
                    'outgoing' => 'Keluar',
                ])
                ->default('internal')
                ->required()
                ->columnSpan(1);
        }

        // Tahun / Tanggal
        $fields[] = Forms\Components\DatePicker::make('document_date')
            ->label("Tanggal {$label}")
            ->default(now())
            ->required()
            ->displayFormat('d/m/Y')
            ->columnSpan(1);

        // Status
        $fields[] = Forms\Components\Select::make('status')
            ->label('Status')
            ->options(self::statusOptions())
            ->default('draft')
            ->required()
            ->columnSpan(1);

        // Keterangan — notes
        $fields[] = Forms\Components\Textarea::make('notes')
            ->label('Keterangan Tambahan')
            ->rows(3)
            ->columnSpanFull();

        return Forms\Components\Section::make("Informasi {$label}")
            ->description("Data utama dokumen {$label}.")
            ->icon('heroicon-o-document-text')
            ->schema($fields)
            ->columns(2);
    }

    /**
     * Section file upload dengan PDF preview.
     * @param string $typeCode  Kode jenis dokumen untuk direktori penyimpanan
     */
    protected static function fileUploadSection(string $typeCode): Forms\Components\Section
    {
        $dir = 'documents/' . strtolower($typeCode) . '/' . date('Y');

        return Forms\Components\Section::make('File Dokumen')
            ->description('Upload file PDF. Centang "File Utama" untuk file yang paling relevan.')
            ->icon('heroicon-o-paper-clip')
            ->schema([
                Forms\Components\Repeater::make('files')
                    ->relationship('files')
                    ->schema([
                        Forms\Components\FileUpload::make('file_path')
                            ->label('Pilih File (PDF/Word)')
                            ->disk('public')
                            ->directory($dir)
                            ->acceptedFileTypes(['application/pdf', 'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->maxSize(10240) // 10 MB
                            ->required()
                            ->storeFileNamesIn('file_name')
                            ->downloadable()
                            ->previewable()
                            ->columnSpan(2),

                        Forms\Components\Select::make('file_type')
                            ->label('Jenis File')
                            ->options([
                                'original'   => 'Asli / Scan',
                                'signed'     => 'Telah Ditandatangani',
                                'attachment' => 'Lampiran',
                                'draft'      => 'Draft',
                            ])
                            ->default('original')
                            ->required()
                            ->columnSpan(1),

                        Forms\Components\Toggle::make('is_primary')
                            ->label('File Utama')
                            ->default(false)
                            ->helperText('Hanya satu file yang dapat menjadi file utama.')
                            ->columnSpan(1),

                        Forms\Components\Hidden::make('uploaded_by')
                            ->default(fn () => auth()->id()),
                    ])
                    ->columns(4)
                    ->addActionLabel('+ Tambah File')
                    ->reorderable()
                    ->collapsible(),
            ]);
    }

    /**
     * Status badge options yang konsisten di seluruh resource.
     */
    protected static function statusOptions(): array
    {
        return [
            'draft'    => 'Draft',
            'active'   => 'Aktif',
            'archived' => 'Diarsipkan',
            'void'     => 'Dibatalkan',
        ];
    }
}
