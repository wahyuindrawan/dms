# Database Refactor Report — SIM Manajemen Dokumen
**Tanggal:** 2026-07-02 | **Versi Schema:** 2.0

---

## Ringkasan Eksekusi

| Aksi | Jumlah |
|------|--------|
| Migration lama ditandai `// DEPRECATED` | 12 |
| Migration lama tetap dipertahankan | 4 |
| Migration baru dibuat | 10 |

---

## Status Migration Lama

### ✅ Tetap Dipakai (4 file)

| File | Keterangan |
|------|-----------|
| `0001_01_01_000000_create_users_table` | Core Laravel, dikembangkan via migration baru |
| `0001_01_01_000001_create_cache_table` | Core Laravel, tidak berubah |
| `0001_01_01_000002_create_jobs_table` | Core Laravel, tidak berubah |
| `2025_12_08_013746_create_roles_table` | Dipakai, ditambah `is_active` + `sort_order` via `000003` |

### 🗑️ DEPRECATED — Rekomendasi: Pindah ke `database/migrations/legacy/`

| File | Digantikan Oleh |
|------|----------------|
| `2025_04_30_..._create_kategori_dokumen_table` | `000006_create_document_categories_table` |
| `2025_05_02_..._create_sumber_dokumen_table` | `units` + field `source_name` di `documents` |
| `2025_05_02_..._create_workers_table` | `000001_create_units_table` |
| `2025_05_02_..._create_surat_masuk_table` | `000007_create_documents_table` (`direction=incoming`) |
| `2025_05_03_..._create_surat_keluar_table` | `000007_create_documents_table` (`direction=outgoing`) |
| `2025_05_03_..._create_dokumen_lain_table` | `000007_create_documents_table` (`direction=internal`) |
| `2025_05_03_..._create_disposisi_table` | Didesain ulang — fase berikutnya (document_routing) |
| `2025_05_03_..._add_worker_id_to_users_table` | `000004_refactor_users_add_unit_and_role_id` |
| `2025_05_03_..._add_role_to_users_table` | `000004_refactor_users_add_unit_and_role_id` |
| `2025_05_04_..._create_agenda_table` | Out of scope — calon modul SIM Agenda terpisah |
| `2025_05_14_..._add_deleted_at_to_surat_masuk` | SoftDelete sudah ada di `documents` |
| `2025_05_18_..._add_deleted_at_to_surat_keluar` | SoftDelete sudah ada di `documents` |

---

## Migration Baru (Urutan Eksekusi)

| # | File | Tabel | Depends On |
|---|------|-------|-----------|
| 1 | `2026_07_02_000001_create_units_table` | `units` | — |
| 2 | `2026_07_02_000002_create_permissions_table` | `permissions`, `role_permission` | `roles` |
| 3 | `2026_07_02_000003_refactor_roles_add_columns` | `roles` (ALTER) | `roles` |
| 4 | `2026_07_02_000004_refactor_users_add_unit_and_role_id` | `users` (ALTER) | `units`, `roles` |
| 5 | `2026_07_02_000005_create_document_types_table` | `document_types` | — |
| 6 | `2026_07_02_000006_create_document_categories_table` | `document_categories` | `document_types` |
| 7 | `2026_07_02_000007_create_documents_table` | `documents` | `document_types`, `document_categories`, `units`, `users` |
| 8 | `2026_07_02_000008_create_sk_details_table` | `sk_details` | `documents` |
| 9 | `2026_07_02_000009_create_sop_details_table` | `sop_details` | `documents`, `units` |
| 10 | `2026_07_02_000010_create_document_files_table` | `document_files` | `documents`, `users` |

---

## ERD Final

```mermaid
erDiagram
    units {
        bigint id PK
        varchar code UK
        varchar name
        bigint parent_id FK
        enum type
        varchar head_name
        varchar head_position
        varchar phone
        varchar email
        text address
        boolean is_active
        smallint sort_order
        timestamps timestamps
    }

    roles {
        bigint id PK
        varchar nama UK
        varchar display_name
        text deskripsi
        varchar color
        boolean is_active
        smallint sort_order
        timestamps timestamps
    }

    permissions {
        bigint id PK
        varchar name UK
        varchar display_name
        varchar group
        text description
        timestamps timestamps
    }

    role_permission {
        bigint role_id FK
        bigint permission_id FK
    }

    users {
        bigint id PK
        varchar name
        varchar email UK
        varchar password
        bigint unit_id FK
        bigint role_id FK
        boolean is_active
        bigint worker_id FK "DEPRECATED"
        varchar role "DEPRECATED"
        timestamps timestamps
    }

    document_types {
        bigint id PK
        varchar code UK
        varchar name
        text description
        varchar prefix
        varchar numbering_format
        tinyint seq_length
        boolean has_detail
        varchar detail_table
        boolean is_active
        smallint sort_order
        timestamps timestamps
    }

    document_categories {
        bigint id PK
        varchar code UK
        varchar name
        text description
        bigint document_type_id FK
        varchar color
        boolean is_active
        smallint sort_order
        timestamps timestamps
    }

    documents {
        bigint id PK
        varchar doc_number UK
        varchar reference_number
        varchar title
        varchar subject
        bigint document_type_id FK
        bigint document_category_id FK
        enum direction
        enum source_type
        bigint source_unit_id FK
        varchar source_name
        enum destination_type
        bigint destination_unit_id FK
        varchar destination_name
        date document_date
        date received_date
        date issued_date
        enum status
        text notes
        bigint created_by FK
        bigint updated_by FK
        timestamp deleted_at
        timestamps timestamps
    }

    sk_details {
        bigint id PK
        bigint document_id FK UK
        date effective_date
        date expiry_date
        enum decree_type
        enum decree_scope
        text regarding
        text consideration
        varchar signer_name
        varchar signer_position
        timestamps timestamps
    }

    sop_details {
        bigint id PK
        bigint document_id FK UK
        varchar version
        smallint revision_number
        date effective_date
        date review_date
        bigint process_owner_unit_id FK
        text scope
        text purpose
        text reference
        timestamps timestamps
    }

    document_files {
        bigint id PK
        bigint document_id FK
        varchar file_name
        varchar file_path
        bigint file_size
        varchar mime_type
        varchar disk
        varchar file_hash
        boolean is_primary
        enum file_type
        bigint uploaded_by FK
        timestamps timestamps
    }

    units ||--o{ units : "parent_id"
    roles ||--o{ role_permission : "has"
    permissions ||--o{ role_permission : "assigned_to"
    roles ||--o{ users : "role_id"
    units ||--o{ users : "unit_id"
    document_types ||--o{ document_categories : "document_type_id"
    document_types ||--o{ documents : "document_type_id"
    document_categories ||--o{ documents : "document_category_id"
    units ||--o{ documents : "source_unit_id"
    units ||--o{ documents : "destination_unit_id"
    users ||--o{ documents : "created_by"
    users ||--o{ documents : "updated_by"
    documents ||--|| sk_details : "1-to-1"
    documents ||--|| sop_details : "1-to-1"
    documents ||--o{ document_files : "has"
    users ||--o{ document_files : "uploaded_by"
    units ||--o{ sop_details : "process_owner_unit_id"
```

---

## Referensi: Enum Values

### `units.type`
| Value | Keterangan |
|-------|-----------|
| `lembaga` | Level tertinggi organisasi |
| `direktorat` | Direktorat |
| `divisi` | Divisi |
| `bagian` | Bagian |
| `seksi` | Seksi |
| `unit` | Unit kerja umum (default) |

### `documents.direction`
| Value | Keterangan | Migration Lama |
|-------|-----------|----------------|
| `incoming` | Surat/dokumen masuk | `surat_masuk` |
| `outgoing` | Surat/dokumen keluar | `surat_keluar` |
| `internal` | Dokumen internal | `dokumen_lain` |

### `documents.status`
| Value | Keterangan |
|-------|-----------|
| `draft` | Masih dalam penyusunan |
| `active` | Aktif/berlaku |
| `archived` | Diarsipkan |
| `void` | Dibatalkan/tidak berlaku |

### `documents.source_type` / `destination_type`
| Value | Keterangan |
|-------|-----------|
| `unit` | Unit internal organisasi |
| `external` | Pihak luar organisasi |
| `person` | Perorangan (untuk destination) |

### `sk_details.decree_type`
| Value | Keterangan |
|-------|-----------|
| `pengangkatan` | SK Pengangkatan jabatan/pegawai |
| `pemberhentian` | SK Pemberhentian |
| `penugasan` | SK Penugasan |
| `penetapan` | SK Penetapan (default) |
| `kebijakan` | SK Kebijakan |
| `peraturan` | SK Peraturan/regulasi |
| `lainnya` | Jenis lain |

### `document_files.file_type`
| Value | Keterangan |
|-------|-----------|
| `original` | File asli upload (default) |
| `signed` | File yang sudah ditandatangani |
| `attachment` | Lampiran |
| `draft` | File draft/konsep |

---

## Referensi: Foreign Keys Lengkap

| Tabel | Kolom | References | On Delete |
|-------|-------|-----------|-----------|
| `units` | `parent_id` | `units.id` | SET NULL |
| `role_permission` | `role_id` | `roles.id` | CASCADE |
| `role_permission` | `permission_id` | `permissions.id` | CASCADE |
| `users` | `unit_id` | `units.id` | SET NULL |
| `users` | `role_id` | `roles.id` | SET NULL |
| `document_categories` | `document_type_id` | `document_types.id` | SET NULL |
| `documents` | `document_type_id` | `document_types.id` | RESTRICT |
| `documents` | `document_category_id` | `document_categories.id` | SET NULL |
| `documents` | `source_unit_id` | `units.id` | SET NULL |
| `documents` | `destination_unit_id` | `units.id` | SET NULL |
| `documents` | `created_by` | `users.id` | RESTRICT |
| `documents` | `updated_by` | `users.id` | SET NULL |
| `sk_details` | `document_id` | `documents.id` | CASCADE |
| `sop_details` | `document_id` | `documents.id` | CASCADE |
| `sop_details` | `process_owner_unit_id` | `units.id` | SET NULL |
| `document_files` | `document_id` | `documents.id` | CASCADE |
| `document_files` | `uploaded_by` | `users.id` | RESTRICT |

---

## Referensi: Unique Constraints

| Tabel | Kolom | Tipe |
|-------|-------|------|
| `units` | `code` | UNIQUE |
| `permissions` | `name` | UNIQUE |
| `role_permission` | `(role_id, permission_id)` | PRIMARY (Composite) |
| `users` | `email` | UNIQUE |
| `document_types` | `code` | UNIQUE |
| `document_categories` | `code` | UNIQUE |
| `documents` | `doc_number` | UNIQUE |
| `sk_details` | `document_id` | UNIQUE (enforce 1-to-1) |
| `sop_details` | `document_id` | UNIQUE (enforce 1-to-1) |

---

## Catatan Migrasi Data

> [!IMPORTANT]
> Setelah schema baru berjalan, perlu dibuat **DataMigrationSeeder** atau Artisan command untuk memindahkan data lama:
> 1. `workers` → `units` (type = 'unit')
> 2. `users.worker_id` → isi `users.unit_id` berdasarkan mapping
> 3. `users.role` (string) → isi `users.role_id` berdasarkan nama di tabel `roles`
> 4. `kategori_dokumen` → `document_categories`
> 5. `surat_masuk` → `documents` (direction=incoming) + `document_files`
> 6. `surat_keluar` → `documents` (direction=outgoing) + `document_files`
> 7. `dokumen_lain` → `documents` (direction=internal) + `document_files`

> [!WARNING]
> Kolom `workers.worker_id` dan `users.role` (string) **jangan dihapus** sebelum migrasi data selesai dan divalidasi. Buat migration cleanup terpisah setelah data migration berhasil.
