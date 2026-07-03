# Laporan: Modul Dokumen (SK / SOP / RUK / RPK)

## Arsitektur: DRY dengan PHP Traits

Untuk menghindari duplikasi kode, seluruh logika yang sama dipusatkan di dua trait:

| File | Peran |
|---|---|
| `App\Filament\Resources\Concerns\HasDocumentForm` | Shared form sections (info dasar, file upload) |
| `App\Filament\Resources\Concerns\HasDocumentTable` | Shared table columns, filters, actions |

Setiap resource hanya memanggil method yang dibutuhkan dan menambahkan section khusus masing-masing:

```php
class RukResource extends Resource
{
    use HasDocumentForm, HasDocumentTable; // ← inject shared logic

    public static function form(Form $form): Form
    {
        return $form->schema([
            static::documentInfoSection('RUK', 'RUK'),  // ← shared
            static::fileUploadSection('RUK'),            // ← shared
        ]);
    }
}
```

---

## Form per Dokumen

### RUK — Rencana Usulan Kegiatan
| Field | Component | Validasi |
|---|---|---|
| Nomor RUK | TextInput (auto, disabled) | — |
| **Nama / Judul** | TextInput | required, max:255 |
| **Tahun** (Tanggal) | DatePicker | required |
| Status | Select | required, default: draft |
| **Keterangan** | Textarea | nullable |
| **Upload File** | FileUpload (Repeater) | PDF/Word, max 10MB |

### RPK — Rencana Pelaksanaan Kegiatan
| Field | Component | Validasi |
|---|---|---|
| Nomor RPK | TextInput (auto, disabled) | — |
| **Nama / Judul** | TextInput | required, max:255 |
| **Tahun** (Tanggal) | DatePicker | required |
| Status | Select | required, default: draft |
| **Upload File** | FileUpload (Repeater) | PDF/Word, max 10MB |

### SK — Surat Keputusan
| Field | Component | Validasi |
|---|---|---|
| **Nomor SK** | TextInput (auto, disabled) | — |
| **Nama / Judul** | TextInput | required, max:255 |
| **Klaster (Cluster)** | Select Relationship | nullable |
| **Tahun** (Tanggal) | DatePicker | required |
| Arah Dokumen | Select | required (internal/keluar) |
| Status | Select | required, default: draft |
| Keterangan | Textarea | nullable |
| Tanggal Berlaku | DatePicker | required (di sk_details) |
| Tanggal Berakhir | DatePicker | nullable |
| Jenis Keputusan | Select | required (di sk_details) |
| Ruang Lingkup | Select | required (di sk_details) |
| Nama Penandatangan | TextInput | nullable |
| Jabatan Penandatangan | TextInput | nullable |
| Tentang / Isi Pokok | Textarea | nullable |
| Menimbang / Mengingat | Textarea | nullable |
| **Upload File** | FileUpload (Repeater) | PDF/Word, max 10MB |

### SOP — Standar Operasional Prosedur
| Field | Component | Validasi |
|---|---|---|
| **Nomor SOP** | TextInput (auto, disabled) | — |
| **Nama / Judul** | TextInput | required, max:255 |
| **Klaster (Cluster)** | Select Relationship | nullable |
| **Tahun** (Tanggal) | DatePicker | required |
| Status | Select | required, default: draft |
| Keterangan | Textarea | nullable |
| Versi SOP | TextInput | required, default: 1.0 |
| Nomor Revisi | TextInput numeric | required, default: 0 |
| Tanggal Berlaku | DatePicker | required (di sop_details) |
| Tanggal Review | DatePicker | nullable |
| **Program / Unit PJ** | Select Relationship (Unit) | nullable |
| Ruang Lingkup | Textarea | nullable |
| Tujuan SOP | Textarea | nullable |
| Referensi / Dasar Hukum | Textarea | nullable |
| **Upload File** | FileUpload (Repeater) | PDF/Word, max 10MB |

---

## Table per Dokumen

### Kolom Bersama (Semua Resource)
| Kolom | Keterangan |
|---|---|
| Nomor | Searchable, sortable, copyable |
| Nama / Judul | Searchable, sortable, wrap |
| Tahun | Diformat dari `document_date → Y` |
| Status | Badge berwarna: warning/success/gray/danger |
| File | Badge count dari relasi `files` |

### Kolom Tambahan
| Resource | Kolom Tambahan |
|---|---|
| SK | Klaster (badge), Jenis Keputusan (badge), Tgl Berlaku |
| SOP | Klaster (badge), Versi (badge), Program / Unit PJ, Tgl Berlaku |

### Filter per Resource

| Filter | RUK | RPK | SK | SOP |
|---|:---:|:---:|:---:|:---:|
| Tahun | ✅ | ✅ | ✅ | ✅ |
| Status (multiple) | ✅ | ✅ | ✅ | ✅ |
| Klaster | ❌ | ❌ | ✅ | ✅ |

---

## Upload Flow

```
User pilih file di Repeater
    ↓
FileUpload → disk: public
           → directory: documents/{TYPE}/{YEAR}/
           → acceptedFileTypes: PDF, DOCX
           → maxSize: 10 MB (10240 KB)
           → storeFileNamesIn: file_name
           → downloadable: true
           → previewable: true
    ↓
Filament save Repeater → document_files table
  - document_id  (FK ke documents)
  - file_path    (path relatif di storage/public)
  - file_name    (nama asli file)
  - file_type    (original/signed/attachment/draft)
  - is_primary   (toggle)
  - uploaded_by  (auth()->id())
    ↓
Action "Lihat File" di Table → buka file di tab baru
  - Ambil primary file, atau fallback ke file pertama
  - URL: asset('storage/' . file_path)
```

---

## Validation Flow

```
Form submit
    ↓
[Shared — documentInfoSection()]
  title       → required | max:255
  document_date → required | date
  status      → required | in: draft, active, archived, void
  document_category_id → nullable (SK & SOP saja)
    ↓
[SK — Section skDetail via relationship()]
  effective_date → required | date
  decree_type    → required | in: pengangkatan, ...
  decree_scope   → required | in: internal, external
    ↓
[SOP — Section sopDetail via relationship()]
  version          -> required | max:20
  revision_number  -> required | numeric | min:0
  effective_date   -> required | date
    ↓
[Shared — fileUploadSection()]
  file_path → required per item | mimes: pdf, doc, docx | max: 10MB
  file_type → required per item
```

---

## Relasi Model

```
Document (table: documents)
  ├── document_type_id    → DocumentType (SK / SOP / RUK / RPK)
  ├── document_category_id → DocumentCategory (Klaster)
  ├── source_unit_id      → Unit
  ├── destination_unit_id → Unit
  ├── created_by          → User
  ├── updated_by          → User
  │
  ├── skDetail()   HasOne → SkDetail
  │     ├── effective_date, expiry_date
  │     ├── decree_type, decree_scope
  │     └── signer_name, signer_position, regarding, consideration
  │
  ├── sopDetail()  HasOne → SopDetail
  │     ├── version, revision_number
  │     ├── effective_date, review_date
  │     ├── process_owner_unit_id → Unit
  │     └── scope, purpose, reference
  │
  └── files()      HasMany → DocumentFile
        ├── file_path, file_name
        ├── file_type, is_primary
        └── uploaded_by → User
```

---

## Perbedaan Antar Resource (Query Filter)

```php
// RUK
->whereHas('documentType', fn($q) => $q->where('code', 'RUK'))

// RPK
->whereHas('documentType', fn($q) => $q->where('code', 'RPK'))

// SK
->whereHas('documentType', fn($q) => $q->where('code', 'SK'))

// SOP
->whereHas('documentType', fn($q) => $q->where('code', 'SOP'))
```

---

## Struktur File

```
app/Filament/Resources/
├── Concerns/
│   ├── HasDocumentForm.php    ← shared form sections
│   └── HasDocumentTable.php   ← shared columns, filters, actions
├── RukResource.php
├── RpkResource.php
├── SkResource.php
└── SopResource.php
```
