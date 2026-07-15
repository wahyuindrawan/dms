# Penjelasan CRUD Dokumen - DMS

## Arsitektur CRUD Dokumen

Sistem dokumen menggunakan **3 layer utama**:

1. **Resource** (SkResource, SopResource, RukResource, RpkResource) — controller CRUD
2. **Traits Reusable** (HasDocumentForm, HasDocumentTable) — komponen form & tabel shared
3. **Models** (Document, SkDetail, SopDetail, DocumentFile) — data layer

---

## 1. READ (Lihat Tabel) — ListSks:route('/')

**File:** `SkResource/Pages/ListSks.php:1-19`

```php
ListSks extends ListRecords
  → Render tabel dokumen
  → Header action: CreateAction (tombol "+ Buat Baru")
```

### Tabel Dokumen

**Diatur di:** `SkResource::table()` | `SopResource::table()`

**Kolom yang ditampilkan:**

| Kolom | Deskripsi | Dari |
|-------|-----------|------|
| `doc_number` | Nomor SK/SOP (bold, copyable) | baseDocumentColumns() |
| `title` | Judul dokumen | baseDocumentColumns() |
| `category.name` | Klaster/Kategori | clusterColumn() |
| `document_date` | Tahun (format Y) | documentDateColumn() |
| `skDetail.decree_type` | Jenis Keputusan (badge) | Spesifik SK |
| `skDetail.effective_date` | Tgl Berlaku | Spesifik SK |
| `status` | Status (badge warna) | statusColumn() |
| `files_count` | Jumlah file | filesCountColumn() |

### Filter Tersedia

- **Tahun** — Custom filter, range dari tahun sekarang ke 2020
- **Status** — Multi-select: Draft, Aktif, Diarsipkan, Dibatalkan
- **Klaster** — Multi-select kategori (jika withCluster=true)

### Actions Tabel

- **Edit** — Edit dokumen
- **Lihat File** — Preview file utama
- **Delete** — Hapus dokumen (dengan confirmation)

---

## 2. CREATE (Buat Baru) — CreateSk:route('/create')

**File:** `SkResource/Pages/CreateSk.php:1-12`

```php
CreateSk extends CreateRecord
  → Tampil form kosong
  → Submit → save ke database
```

### Form terdiri dari 4 Section

#### **Section 1: Informasi SK** (documentInfoSection)

**File:** `HasDocumentForm.php:26-117`

Shared fields untuk semua dokumen:

| Field | Type | Required | Catatan |
|-------|------|----------|---------|
| `doc_number` | Hidden | — | Auto-generate saat save |
| `title` | TextInput | Ya | Nama/Judul SK |
| `document_category_id` | Select | Tidak | Klaster (untuk SK & SOP saja) |
| `direction` | Select | Ya | Arah: Internal/Keluar (hanya SK) |
| `document_date` | DatePicker | Ya | Default: hari ini, format d/m/Y |
| `status` | Select | Ya | Draft/Aktif/Diarsipkan/Dibatalkan (default: Draft) |
| `notes` | Textarea | Tidak | Keterangan tambahan (3 baris) |

#### **Section 2: Detail SK** (skDetail)

**File:** `SkResource.php:45-101` + `SkDetail model`

Spesifik SK (relasi 1-to-1 ke tabel `sk_details`):

| Field | Type | Required | Catatan |
|-------|------|----------|---------|
| `effective_date` | DatePicker | Ya | Tanggal berlaku, format d/m/Y |
| `expiry_date` | DatePicker | Tidak | Tanggal berakhir |
| `decree_type` | Select | Ya | Pilihan: Pengangkatan, Pemberhentian, Penugasan, Penetapan, Kebijakan, Peraturan, Lainnya |
| `decree_scope` | Select | Ya | Internal atau Eksternal (default: Internal) |
| `signer_name` | TextInput | Tidak | Nama penandatangan (max 100) |
| `signer_position` | TextInput | Tidak | Jabatan penandatangan (max 100) |
| `regarding` | Textarea | Tidak | Tentang/Isi Pokok (3 baris) |
| `consideration` | Textarea | Tidak | Menimbang/Mengingat (3 baris) |

#### **Section 3: Detail SOP** (sopDetail)

**File:** `SopResource.php:45-98` + `SopDetail model`

Spesifik SOP (relasi 1-to-1 ke tabel `sop_details`):

| Field | Type | Required | Catatan |
|-------|------|----------|---------|
| `version` | TextInput | Ya | Versi SOP (default: 1.0, max 20 char) |
| `revision_number` | TextInput | Ya | Nomor revisi (numeric, min 0, default: 0) |
| `effective_date` | DatePicker | Ya | Tanggal berlaku, format d/m/Y |
| `review_date` | DatePicker | Tidak | Tanggal evaluasi berkala |
| `process_owner_unit_id` | Select | Tidak | Program/Unit penanggung jawab (relationship ke Unit) |
| `scope` | Textarea | Tidak | Ruang lingkup (2 baris) |
| `purpose` | Textarea | Tidak | Tujuan SOP (2 baris) |
| `reference` | Textarea | Tidak | Referensi/Dasar hukum (2 baris) |

#### **Section 4: File Dokumen** (fileUploadSection)

**File:** `HasDocumentForm.php:123-173`

Upload repeater (boleh multiple files):

| Field | Type | Required | Catatan |
|-------|------|----------|---------|
| `file_path` | FileUpload | Ya | Format: PDF/Word, max 10MB, disk: public |
| `file_type` | Select | Ya | Pilihan: Asli/Scan, Telah Ditandatangani, Lampiran, Draft (default: original) |
| `is_primary` | Toggle | Tidak | File utama (hanya 1 file yang bisa primary) |
| `uploaded_by` | Hidden | — | Auto-fill dengan user yang login |

**Direktori penyimpanan:** `documents/{typeCode}/{year}/` (contoh: `documents/sk/2026/`)

---

## 3. UPDATE (Edit) — EditSk:route('/{record}/edit')

**File:** `SkResource/Pages/EditSk.php:1-19`

```php
EditSk extends EditRecord
  → Load dokumen existing
  → Form dengan data yg sudah ada
  → Submit → update database
  → Header action: DeleteAction (tombol Hapus)
```

**Form yang digunakan SAMA dengan CREATE** (`SkResource::form()`), hanya populated dengan data existing.

**Header Actions:**
- **Delete** — Hapus dokumen dengan confirmation

---

## 4. DELETE (Hapus)

**Lokasi:** 
- Di EditSk: `Actions\DeleteAction::make()` (header)
- Di Tabel: `baseDocumentActions()` → DeleteAction (dalam ActionGroup)

Menggunakan **soft delete** (SoftDeletes trait di model Document).

---

## Alur Data: INPUT → VALIDASI → SAVE

**Files terlibat:** Document model | SkDetail model | DocumentFile model

### Proses Simpan (Create/Update):

1. **Create** di SkResource/Pages/CreateSk
2. **Form validation** (required fields divalidasi Filament)
3. **Model booted event** (Document.php:137-153)
   - Auto-generate `doc_number` menggunakan format di DocumentType
   - Set `created_by` = user login (saat create)
   - Set `updated_by` = user login (saat update)
4. **Save** ke tabel:
   - `documents` — data utama dokumen
   - `sk_details` atau `sop_details` — detail spesifik dokumen type
   - `document_files` — file terlampir

### Auto-Generate Nomor Dokumen

**File:** `Document.php:155-184` → `generateNumber()`

Format: `{prefix}{seq}/{mm}/{yyyy}`

Contoh:
- SK: `SK-0001/07/2026`
- SOP: `SOP-0002/07/2026`

**Komponen:**
- `{prefix}` — Dari DocumentType.prefix
- `{seq}` — Sequence padding (default 4 digit, 0001, 0002, dst)
- `{mm}` — Bulan (07)
- `{yyyy}` — Tahun (2026)

Sequence di-reset setiap tahun.

---

## Struktur Database

### Tabel: documents (Master)

```sql
id, doc_number, reference_number, title, subject
document_type_id (FK → DocumentType)
document_category_id (FK → DocumentCategory)
direction (internal/outgoing/incoming)
source_type, source_unit_id, source_name
destination_type, destination_unit_id, destination_name
document_date, received_date, issued_date
status (draft/active/archived/void)
notes
created_by, updated_by (FK → User)
created_at, updated_at, deleted_at
```

### Tabel: sk_details (Detail SK)

```sql
id, document_id (FK → documents)
effective_date, expiry_date
decree_type, decree_scope
regarding, consideration
signer_name, signer_position
created_at, updated_at
```

### Tabel: sop_details (Detail SOP)

```sql
id, document_id (FK → documents)
version, revision_number
effective_date, review_date
process_owner_unit_id (FK → Unit)
scope, purpose, reference
created_at, updated_at
```

### Tabel: document_files (File Terlampir)

```sql
id, document_id (FK → documents)
file_name, file_path, file_size
mime_type, disk, file_hash
is_primary (boolean)
file_type (original/signed/attachment/draft)
uploaded_by (FK → User)
created_at, updated_at
```

---

## Reusable Traits (DRY Pattern)

### HasDocumentForm (HasDocumentForm.php)

Menyediakan method reusable untuk form:

```php
documentInfoSection()      // Section utama dokumen
fileUploadSection()         // Section upload file
statusOptions()             // Static options status
```

**Keuntungan:** Hindari duplikasi code antara SK, SOP, RUK, RPK. Setiap resource tinggal panggil:

```php
static::documentInfoSection(typeCode: 'SK', label: 'SK', withCluster: true, withDirection: true)
```

### HasDocumentTable (HasDocumentTable.php)

Menyediakan method reusable untuk tabel:

```php
baseDocumentColumns()     // Kolom dasar (nomor, judul)
documentDateColumn()      // Kolom tahun
clusterColumn()           // Kolom klaster
statusColumn()            // Kolom status dengan badge
filesCountColumn()        // Kolom jumlah file
baseDocumentFilters()     // Filter tahun, status, klaster
baseDocumentActions()     // Action: Edit, Lihat File, Delete
baseDocumentBulkActions() // Bulk: Delete
```

---

## Alur Pengguna: 4 Menu + 1 Tabel

| Menu | Page | Route | Action | File |
|------|------|-------|--------|------|
| **1. Lihat** | ListSks | `/surat-keputusan` | Display tabel | ListSks.php |
| **2. Buat** | CreateSk | `/surat-keputusan/create` | Form baru (CREATE) | CreateSk.php |
| **3. Edit** | EditSk | `/surat-keputusan/{id}/edit` | Form existing (UPDATE) | EditSk.php |
| **4. Hapus** | EditSk | — | Delete action (DELETE) | DeleteAction |
| **Tabel** | ListSks | `/surat-keputusan` | 8 kolom + 3 filter + 3 action | SkResource::table() |

---

## Resource Registration

**File:** `SkResource.php` | `SopResource.php` | `RukResource.php` | `RpkResource.php`

Setiap resource:
- Extend `Resource` class dari Filament
- Use trait `HasDocumentForm`, `HasDocumentTable`
- Implement `form()`, `table()`, `getEloquentQuery()`, `getPages()`
- Set navigation properties (icon, label, group, sort)

**Query filter:** Hanya tampilkan dokumen sesuai tipe (SK, SOP, RUK, RPK)

```php
public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()
        ->whereHas('documentType', fn (Builder $q) => $q->where('code', 'SK'));
}
```

---

## ActivityLog

**File:** `Document.php:49-61`

Setiap perubahan dokumen dicatat di tabel `activity_log`:

- **Fields ditrack:** title, doc_number, status, document_type_id, document_category_id
- **Log name:** 'document'
- **Event:** created, updated, deleted dengan deskripsi otomatis

Contoh log: `"Dokumen \"SK Pengangkatan\" ditambahkan"`

---

## Fitur Tambahan

### 1. Copy Nomor Dokumen
Kolom `doc_number` di tabel punya action `->copyable()` untuk copy ke clipboard.

### 2. Searchable & Sortable
- Nomor dan judul: searchable, sortable
- Kategori, tahun, status: sortable
- Default sort: `document_date` (desc) — dokumen terbaru di atas

### 3. Toggleable Kolom
Pengguna bisa tampilkan/sembunyikan kolom tertentu (version, program, tgl berlaku).

### 4. Relationship Management
Field category (Klaster) bisa quick create dari form dengan `createOptionForm`:
- Input code (unique)
- Input name

---

## Summary

Sistem CRUD dokumen dirancang dengan:

- **Modularity** — Traits reusable, hindari duplikasi
- **Flexibility** — Setiap dokumen type punya section detail spesifik
- **Scalability** — Mudah tambah resource baru (RUK, RPK)
- **Auditability** — ActivityLog tercatat otomatis
- **User-friendly** — Filter, search, bulk actions, copy, preview file
