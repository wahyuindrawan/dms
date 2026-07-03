# Laporan: Pembangunan Master Data

## Ringkasan

Seluruh modul Master Data telah dibangun menggunakan **Filament 4 Best Practice** dengan fitur lengkap: Table, Form, Filter, Search, Soft Delete, Select Relationship, Validation, Unique Rule, Policy, dan Navigation Group.

---

## 1. Struktur Resource & Navigasi

| Resource | Model | Nav Group | Sort | Slug |
|---|---|---|---|---|
| **UnitResource** | `Unit` | Master Data | 1 | `/unit` |
| **DocumentTypeResource** | `DocumentType` | Master Data | 2 | `/jenis-dokumen` |
| **DocumentCategoryResource** | `DocumentCategory` | Master Data | 3 | `/klaster-dokumen` |
| **UserResource** | `User` | Master Data | 4 | `/pengguna` |
| **RoleResource** | `Role` | Master Data | 5 | `/peran-izin` |

---

## 2. Struktur Form per Resource

### 🏢 Unit Organisasi
| Section | Fields |
|---|---|
| Identitas Unit | `code` (unique, alphaDash), `name`, `parent_id` (Select Relationship), `type` (Select enum) |
| Penanggung Jawab | `head_name`, `head_position`, `phone`, `email` |
| Konfigurasi | `address`, `is_active`, `sort_order` |

### 📁 Jenis Dokumen
| Section | Fields |
|---|---|
| Identitas | `code` (unique, alphaDash), `name`, `description` |
| Penomoran Otomatis | `prefix`, `seq_length` (min:1, max:8), `numbering_format` (token hints) |
| Tabel Detail | `has_detail` (Toggle live), `detail_table` (visible saat `has_detail=true`) |
| Pengaturan | `is_active`, `sort_order` |

### 🏷️ Klaster Dokumen
| Section | Fields |
|---|---|
| Identitas Klaster | `code` (unique, alphaDash), `name`, `document_type_id` (Select nullable), `color` (ColorPicker), `description` |
| Pengaturan | `is_active`, `sort_order` |

### 👤 Pengguna
| Section | Fields |
|---|---|
| Informasi Akun | `name`, `email` (unique), `password` (required only on create), `password_confirmation` (same rule) |
| Penempatan & Peran | `unit_id` (Select Relationship), `role_id` (Select Relationship, required) |

### 🛡️ Peran & Izin
| Section | Fields |
|---|---|
| Identitas Peran | `nama` (slug, unique, disabled on edit), `display_name`, `deskripsi`, `color` (Select) |
| Izin Akses | `permissions` (CheckboxList Relationship, bulk toggle, 3 kolom) |

---

## 3. Struktur Table & Filter

### Semua Resource memiliki:
- **Search**: pada kolom-kolom utama (`name`, `code`, `email`, dsb.)
- **Sort**: tersedia di kolom utama
- **Toggle column**: untuk kolom detail yang tidak selalu dibutuhkan

| Resource | Filter Tersedia |
|---|---|
| Unit | `type` (Select), `is_active` (Ternary), **TrashedFilter** |
| Jenis Dokumen | `is_active` (Ternary), `has_detail` (Ternary), **TrashedFilter** |
| Klaster Dokumen | `document_type_id` (Select Relationship), `is_active` (Ternary), **TrashedFilter** |
| Pengguna | `unit_id` (Select Relationship), `role_id` (Select Relationship) |
| Peran & Izin | — |

---

## 4. Soft Delete

Tiga model master mendukung soft delete penuh:

| Model | Trait | `deleted_at` di DB |
|---|---|---|
| `Unit` | `SoftDeletes` | ✅ (migration baru) |
| `DocumentType` | `SoftDeletes` | ✅ (migration baru) |
| `DocumentCategory` | `SoftDeletes` | ✅ (migration baru) |

Setiap resource dengan soft delete memiliki:
- `RestoreAction` di table dan edit page
- `ForceDeleteAction` di edit page
- `RestoreBulkAction` dan `ForceDeleteBulkAction`
- `TrashedFilter` di table
- `getEloquentQuery()` dengan `withoutGlobalScope(SoftDeletingScope::class)`

---

## 5. Policy

| Policy | Model | Izin Granular |
|---|---|---|
| `UnitPolicy` | `Unit` | `unit.view-any`, `unit.view`, `unit.create`, `unit.update`, `unit.delete` |
| `DocumentTypePolicy` | `DocumentType` | `document-type.*` |
| `DocumentCategoryPolicy` | `DocumentCategory` | `document-category.*` |
| `UserPolicy` | `User` | `user.*` |
| `RolePolicy` | `Role` | `role.*` |
| `DocumentPolicy` | `Document` | `document.*` |

Semua policy menggunakan `$user->hasPermission(string $name)` dari `User` model, bukan hardcode `role === 'admin'`.

---

## 6. Seeder Awal

| Seeder | Data Yang Dibuat |
|---|---|
| `RoleSeeder` | 4 role: `admin`, `pimpinan`, `tu`, `karyawan` |
| `PermissionSeeder` | 30+ permissions granular, di-assign ke role admin (semua) dan role lain (subset) |
| `UnitSeeder` | 1 lembaga induk + 5 sub-unit |
| `DocumentCategorySeeder` | 6 klaster umum + 4 klaster SK + 4 klaster SOP |
| `DatabaseSeeder` | DocumentType: SK, SOP, RUK, RPK + admin user |

---

## 7. Hubungan ke Modul Dokumen

```
Document
  ├── document_type_id  → DocumentType.id   (Select di semua form dokumen)
  ├── document_category_id → DocumentCategory.id  (Select difilter per type)
  ├── source_unit_id    → Unit.id           (Select unit asal)
  ├── destination_unit_id → Unit.id         (Select unit tujuan)
  ├── created_by        → User.id
  └── updated_by        → User.id

User
  ├── unit_id    → Unit.id
  └── role_id    → Role.id

Role
  └── permissions (BelongsToMany) → Permission
```
