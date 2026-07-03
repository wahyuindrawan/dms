<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentCategoryResource\Pages;
use App\Models\DocumentCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentCategoryResource extends Resource
{
    protected static ?string $model = DocumentCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $navigationLabel = 'Klaster Dokumen';
    protected static ?string $pluralModelLabel = 'Klaster Dokumen';
    protected static ?string $modelLabel = 'Klaster Dokumen';
    protected static ?int $navigationSort = 3;
    protected static ?string $slug = 'klaster-dokumen';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identitas Klaster')
                ->description('Klaster digunakan untuk mengelompokkan dokumen. Dapat dikaitkan ke satu jenis dokumen atau berlaku untuk semua.')
                ->icon('heroicon-o-tag')
                ->schema([
                    Forms\Components\TextInput::make('code')
                        ->label('Kode Klaster')
                        ->required()
                        ->maxLength(20)
                        ->unique(DocumentCategory::class, 'code', ignoreRecord: true)
                        ->placeholder('Contoh: KEUANGAN, SDM, UMUM')
                        ->alphaDash()
                        ->helperText('Hanya huruf, angka, dan tanda hubung (-).')
                        ->columnSpan(1),

                    Forms\Components\TextInput::make('name')
                        ->label('Nama Klaster')
                        ->required()
                        ->maxLength(100)
                        ->columnSpan(1),

                    Forms\Components\Select::make('document_type_id')
                        ->label('Berlaku untuk Jenis Dokumen')
                        ->relationship('documentType', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->placeholder('Semua Jenis Dokumen')
                        ->helperText('Kosongkan agar klaster berlaku untuk semua jenis dokumen.'),

                    Forms\Components\ColorPicker::make('color')
                        ->label('Warna Badge')
                        ->default('#3B82F6')
                        ->helperText('Warna yang ditampilkan pada badge klaster.'),

                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi')
                        ->rows(2)
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Pengaturan Tampilan')
                ->schema([
                    Forms\Components\Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),

                    Forms\Components\TextInput::make('sort_order')
                        ->label('Urutan Tampil')
                        ->numeric()
                        ->default(0),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Klaster')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('documentType.name')
                    ->label('Jenis Dokumen')
                    ->searchable()
                    ->sortable()
                    ->default('Semua Jenis')
                    ->placeholder('Semua Jenis'),

                Tables\Columns\ColorColumn::make('color')
                    ->label('Warna'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Dihapus')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('document_type_id')
                    ->label('Jenis Dokumen')
                    ->relationship('documentType', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->trueLabel('Hanya Aktif')
                    ->falseLabel('Hanya Non-Aktif'),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScope(SoftDeletingScope::class);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDocumentCategories::route('/'),
            'create' => Pages\CreateDocumentCategory::route('/create'),
            'edit'   => Pages\EditDocumentCategory::route('/{record}/edit'),
        ];
    }
}
