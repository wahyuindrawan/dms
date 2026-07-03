# Audit Report: Surat-App
**Laravel 12 + Filament 3 (bukan Filament 4)**
**Tanggal Audit:** 2026-07-02

---

> [!CAUTION]
> **Temuan Kritis:** `composer.json` menyatakan `"filament/filament": "^3.0"` — bukan Filament 4. Seluruh kode Resource, Widget, dan Panel menggunakan API Filament v3. Ini perlu divalidasi ulang sebelum upgrade.

---

## 1. Tree Project

```
surat-app/
├── app/
│   ├── Filament/
│   │   ├── Pages/
│   │   │   ├── Auth/
│   │   │   │   └── Login.php              ← Custom login page
│   │   │   └── Dashboard.php              ← Custom dashboard
│   │   ├── Resources/
│   │   │   ├── AgendaResource.php
│   │   │   ├── AgendaResource/            ← Pages: List, Create, Edit
│   │   │   ├── DisposisiResource.php
│   │   │   ├── DisposisiResource/
│   │   │   ├── DokumenLainResource.php
│   │   │   ├── DokumenLainResource/
│   │   │   ├── KategoriDokumenResource.php
│   │   │   ├── KategoriDokumenResource/
│   │   │   ├── RoleResource.php
│   │   │   ├── RoleResource/
│   │   │   ├── SumberDokumenResource.php
│   │   │   ├── SumberDokumenResource/
│   │   │   ├── SuratKeluarResource.php
│   │   │   ├── SuratKeluarResource/
│   │   │   ├── SuratMasukResource.php
│   │   │   ├── SuratMasukResource/
│   │   │   ├── UserResource.php
│   │   │   ├── UserResource/
│   │   │   ├── WorkerResource.php
│   │   │   └── WorkerResource/
│   │   └── Widgets/
│   │       ├── AgendaHariIni.php
│   │       ├── DisposisiBadge.php
│   │       ├── StatsOverview.php
│   │       └── WelcomeWidget.php
│   ├── Http/
│   │   └── Controllers/
│   │       └── Controller.php             ← Kosong (base only)
│   ├── Models/
│   │   ├── Agenda.php
│   │   ├── Disposisi.php
│   │   ├── DokumenLain.php
│   │   ├── KategoriDokumen.php
│   │   ├── Role.php
│   │   ├── SumberDokumen.php
│   │   ├── SuratKeluar.php
│   │   ├── SuratMasuk.php
│   │   ├── User.php
│   │   └── Worker.php
│   ├── Policies/
│   │   ├── AgendaPolicy.php
│   │   ├── DisposisiPolicy.php
│   │   ├── DokumenLainPolicy.php
│   │   ├── KategoriDokumenPolicy.php
│   │   ├── RolePolicy.php
│   │   ├── SumberDokumenPolicy.php
│   │   ├── SuratKeluarPolicy.php
│   │   ├── SuratMasukPolicy.php
│   │   ├── UserPolicy.php
│   │   └── WorkerPolicy.php
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   └── Filament/
│   │       └── AdminPanelProvider.php
│   └── Traits/
│       └── ManageDocumentFileName.php
├── database/
│   ├── migrations/ (16 files)
│   ├── seeders/
│   │   ├── DatabaseSeeder.php
│   │   └── RoleSeeder.php
│   └── factories/
├── docker/
├── resources/
├── routes/
└── docker-compose.yml / docker-compose-default.yml
```

---

## 2. Daftar Migration

| # | File | Tabel | Status |
|---|------|-------|--------|
| 1 | `0001_01_01_000000_create_users_table` | `users` | ✅ Core Laravel |
| 2 | `0001_01_01_000001_create_cache_table` | `cache` | ✅ Core Laravel |
| 3 | `0001_01_01_000002_create_jobs_table` | `jobs`, `job_batches`, `failed_jobs` | ✅ Core Laravel |
| 4 | `2025_04_30_create_kategori_dokumen_table` | `kategori_dokumen` | ✅ Aktif |
| 5 | `2025_05_02_create_sumber_dokumen_table` | `sumber_dokumen` | ✅ Aktif |
| 6 | `2025_05_02_create_workers_table` | `workers` | ✅ Aktif |
| 7 | `2025_05_02_create_surat_masuk_table` | `surat_masuk` | ⚠️ Perlu audit kolom |
| 8 | `2025_05_03_create_surat_keluar_table` | `surat_keluar` | ⚠️ Perlu audit kolom |
| 9 | `2025_05_03_create_dokumen_lain_table` | `dokumen_lain` | ✅ Aktif |
| 10 | `2025_05_03_create_disposisi_table` | `disposisi` | ⚠️ Bug enum status |
| 11 | `2025_05_03_add_worker_id_to_users_table` | `users` | ✅ Aktif |
| 12 | `2025_05_03_add_role_to_users_table` | `users` | ⚠️ Redundan dengan tabel `roles` |
| 13 | `2025_05_04_create_agenda_table` | `agenda`, `agenda_worker` | ✅ Aktif |
| 14 | `2025_05_14_add_deleted_at_to_surat_masuk` | `surat_masuk` | ✅ SoftDelete |
| 15 | `2025_05_18_add_deleted_at_to_surat_keluar` | `surat_keluar` | ✅ SoftDelete |
| 16 | `2025_12_08_create_roles_table` | `roles` | ⚠️ Ditambah belakangan, inkonsisten urutan |

---

## 3. Daftar Model & Relasi

### Diagram Relasi

```
User ──── belongsTo ──── Worker
User ──── belongsTo ──── Role (via nama string, bukan FK!)

Worker ──── belongsTo ──── User
Worker ──── belongsToMany ──── Agenda (via agenda_worker)

SuratMasuk ──── belongsTo ──── KategoriDokumen
SuratMasuk ──── belongsTo ──── SumberDokumen
SuratMasuk ──── belongsTo ──── Worker (ditujukan_id)
SuratMasuk ──── [hasMany] ──── Disposisi  ← RELASI TIDAK DIDEKLARASIKAN di model

SuratKeluar ──── belongsTo ──── KategoriDokumen
SuratKeluar ──── belongsTo ──── SumberDokumen
SuratKeluar.ditujukan ← STRING bukan FK!

Disposisi ──── belongsTo ──── SuratMasuk
Disposisi ──── belongsTo ──── Worker (dari_worker_id)
Disposisi ──── belongsTo ──── Worker (ke_worker_id)

DokumenLain ──── belongsTo ──── KategoriDokumen
DokumenLain ──── belongsTo ──── SumberDokumen

Agenda ──── belongsToMany ──── Worker (via agenda_worker)

KategoriDokumen ── [tidak ada inverse relation]
SumberDokumen   ── [tidak ada inverse relation]
Role            ── [tidak ada relasi hasMany User]
```

### Detail Per Model

| Model | Trait | SoftDelete | Auto-code | Keterangan |
|-------|-------|------------|-----------|------------|
| `User` | - | ❌ | ❌ | role disimpan sebagai string |
| `Worker` | - | ❌ | ❌ | Relasi balik ke Agenda tidak ada |
| `SuratMasuk` | `ManageDocumentFileName` | ✅ | `SM-XXXX` | booted() memiliki logika rename |
| `SuratKeluar` | `ManageDocumentFileName` | ✅ | `SK-XXXX` | `ditujukan` = plain string, bukan FK |
| `Disposisi` | - | ❌ | ❌ | Kolom `dibaca_pada` di fillable tapi tidak di migration |
| `Agenda` | `ManageDocumentFileName` | ❌ | ❌ | Duplikasi logika rename dari Trait |
| `DokumenLain` | `ManageDocumentFileName` | ❌ | `DKM-XXXX` | Tidak ada SoftDelete |
| `KategoriDokumen` | - | ❌ | ❌ | Tidak ada inverse relation |
| `SumberDokumen` | - | ❌ | ❌ | Tidak ada inverse relation |
| `Role` | - | ❌ | ❌ | Tidak ada relasi `hasMany User` |

---

## 4. Daftar Filament Resource

| Resource | Nav Group | Sort | Fitur Khusus |
|----------|-----------|------|--------------|
| `SuratMasukResource` | Manajemen Surat | - | Modal detail, action Disposisi inline |
| `SuratKeluarResource` | Manajemen Surat | 2 | Modal detail, `withoutTrashed()` |
| `DokumenLainResource` | Manajemen Surat | 3 | Modal detail |
| `AgendaResource` | Tracking & Agenda | 4 | Select workers dengan "Semua Karyawan" |
| `DisposisiResource` | Tracking & Agenda | 5 | Filter by `ke_worker_id` jika bukan admin |
| `KategoriDokumenResource` | Data Dasar | 6 | Simple CRUD |
| `SumberDokumenResource` | Data Dasar | 7 | Simple CRUD |
| `WorkerResource` | Data Dasar | 8 | Simple CRUD |
| `UserResource` | Pengaturan | 9 | Custom `getRoleOptions()` |
| `RoleResource` | Pengaturan | 10 | Simple CRUD |

---

## 5. Daftar Policy

Semua policy terdaftar manual di `AppServiceProvider` via `Gate::policy()`.

| Policy | Admin | Pimpinan | TU | Karyawan |
|--------|-------|----------|----|---------|
| `SuratMasukPolicy` | CRUD+restore | view | CRUD | view |
| `SuratKeluarPolicy` | CRUD+restore | view | CRUD | view |
| `DisposisiPolicy` | CRUD+restore | CRUD | CRUD | view |
| `DokumenLainPolicy` | CRUD+restore | view | CRUD | view |
| `AgendaPolicy` | CRUD+restore | view | CRUD | view |
| `KategoriDokumenPolicy` | CRUD | - | CRUD | - |
| `SumberDokumenPolicy` | CRUD | - | CRUD | - |
| `WorkerPolicy` | CRUD | view | view | view |
| `UserPolicy` | CRUD (admin only) | - | - | - |
| `RolePolicy` | CRUD (admin only) | - | - | - |

---

## 6. Seeder

| File | Fungsi |
|------|--------|
| `DatabaseSeeder` | Memanggil `RoleSeeder`, lalu buat user `admin@mail.com` |
| `RoleSeeder` | Seed 4 role: admin, pimpinan, tu, karyawan |

> [!NOTE]
> Tidak ada seeder untuk `KategoriDokumen` dan `SumberDokumen`. Data master ini harus diisi manual setelah deploy.

---

## 7. Widget & Dashboard

| Widget | Tipe | Kolom | Catatan |
|--------|------|-------|---------|
| `WelcomeWidget` | Custom View | 1 | View-only |
| `DisposisiBadge` | Custom View | 1 | Data diambil di view, bukan di class |
| `AgendaHariIni` | Custom View | 1 | `isLazy = false`, data di view |
| `StatsOverview` | StatsOverviewWidget | full | 4 stat cards dengan URL ke create |

> [!WARNING]
> `StatsOverview` menggunakan `getCards()` — method Filament v2/v3 lama. Filament v3 terbaru merekomendasikan `getStats()`.

---

## 8. Navigasi

```
Dashboard
├── Manajemen Surat
│   ├── Surat Masuk
│   ├── Surat Keluar (sort: 2)
│   └── Dokumen Lain (sort: 3)
├── Tracking & Agenda
│   ├── Agenda (sort: 4)
│   └── Disposisi (sort: 5)
├── Data Dasar
│   ├── Kategori Dokumen (sort: 6)
│   ├── Asal Dokumen (sort: 7)
│   └── Karyawan (sort: 8)
└── Pengaturan
    ├── Pengguna (sort: 9)
    └── Peran Pengguna (sort: 10)
```

> [!NOTE]
> Dark mode dinonaktifkan (`->darkMode(false)`). Panel path adalah `/` (root).

---

## 9. Service Class & Observer

| Item | Status |
|------|--------|
| Service Class | ❌ Tidak ada |
| Observer | ❌ Tidak ada — logika observer ditulis langsung di `booted()` model |
| Middleware Custom | ❌ Tidak ada — hanya middleware Filament standar |
| Job/Queue | ❌ Tidak digunakan meskipun tabel `jobs` sudah ada |

---

## 10. Technical Debt

| # | Kategori | Masalah | Severity |
|---|----------|---------|----------|
| TD-01 | **Dependency** | `composer.json` menyebut Filament `^3.0` tapi user menyebut "Filament 4" — versi tidak konsisten | 🔴 Kritis |
| TD-02 | **Dependency** | `spatie/laravel-permission ^6.23` ter-install tapi **tidak digunakan sama sekali** — sistem role dibuat custom | 🔴 Kritis |
| TD-03 | **Schema** | `users.role` disimpan sebagai `string` (kolom varchar), lalu ada juga tabel `roles` yang dibuat belakangan — dua sistem role berjalan paralel | 🔴 Kritis |
| TD-04 | **Schema** | Migration `create_roles_table` timestamp-nya `2025_12_08` — jauh setelah migration lain (Mei 2025), menandakan fitur ini ditambah belakangan secara tidak terencana | 🟠 Tinggi |
| TD-05 | **Model** | `Disposisi.$fillable` mencantumkan `dibaca_pada` (timestamp) tapi migration hanya membuat kolom `dibaca` (boolean) | 🔴 Kritis |
| TD-06 | **Model** | `Disposisi` migration menggunakan `enum('status', ['pending', 'selesai'])` tapi Resource form menggunakan option `['belum', 'proses', 'selesai']` — nilai enum tidak konsisten | 🔴 Kritis |
| TD-07 | **Model** | `SuratMasuk` migration punya kolom `deskripsi` tapi `$fillable` menyebut `keterangan` — nama kolom tidak sinkron antara migration dan model | 🔴 Kritis |
| TD-08 | **Model** | `SuratKeluar.ditujukan` adalah plain `string` bukan FK ke `workers` — berbeda arsitektur dengan `SuratMasuk.ditujukan_id` yang berupa FK | 🟠 Tinggi |
| TD-09 | **Logic** | `Agenda::booted()` menduplikasi logika rename file yang seharusnya sudah ditangani `ManageDocumentFileName` trait — trait menggunakan `file_path` tapi Agenda menggunakan `dokumen_path` | 🟠 Tinggi |
| TD-10 | **Logic** | `DisposisiResource::mount()` dideklarasikan di Resource class (bukan Page class) — method ini tidak akan dipanggil di Filament Resource | 🔴 Kritis |
| TD-11 | **Logic** | `SuratMasukResource` — action disposisi inline memanggil `Worker::all()->pluck(...)` tanpa eager loading, berpotensi N+1 | 🟡 Sedang |
| TD-12 | **Logic** | `AgendaResource` form menggunakan `->dehydrated(false)` untuk field `workers` tapi logika simpan ke pivot belum terlihat di Resource — kemungkinan data workers tidak tersimpan | 🔴 Kritis |
| TD-13 | **Relasi** | `SuratMasuk` tidak mendeklarasikan `hasMany(Disposisi::class)` — relasi satu arah saja | 🟡 Sedang |
| TD-14 | **Relasi** | `Worker` tidak mendeklarasikan relasi balik ke `Agenda` (pivot), `SuratMasuk`, maupun `Disposisi` | 🟡 Sedang |
| TD-15 | **Relasi** | `Role` tidak memiliki relasi `hasMany(User::class)` — padahal relasi `User::roleData()` sudah ada | 🟡 Sedang |
| TD-16 | **Auth** | Role check dilakukan dengan `$user->role === 'admin'` (string hardcode) di seluruh Policy — fragile jika nama role berubah | 🟠 Tinggi |
| TD-17 | **Widget** | `StatsOverview` menggunakan `getCards()` bukan `getStats()` — API lama, tidak sesuai Filament v3 terbaru | 🟡 Sedang |
| TD-18 | **Kode** | `SuratKeluar` — kode auto-generate `SK-XXXX` menggunakan `static::max('id')` tanpa `withTrashed()`, berbeda dengan `SuratMasuk` yang menggunakan `withTrashed()` | 🟡 Sedang |
| TD-19 | **View** | `SuratMasukResource` form field `deskripsi` menggunakan `Textarea::make('deskripsi')` tapi migration menyebut kolom `deskripsi` sedangkan model menyebut `keterangan` | 🔴 Kritis |
| TD-20 | **Arsitektur** | Tidak ada Observer terpisah — semua logika event (`creating`, `created`, `updated`) ditulis langsung di `booted()` model, sulit di-test dan di-maintain | 🟠 Tinggi |

---

## 11. Kategorisasi Komponen

### A. Dipakai Tanpa Perubahan

- `KategoriDokumenResource` + `KategoriDokumen` model
- `SumberDokumenResource` + `SumberDokumen` model
- `WorkerResource` + migration
- `RoleSeeder`
- `AdminPanelProvider` (konfigurasi panel sudah tepat)
- `Login.php` (custom login page)
- `UserPolicy`, `RolePolicy` (logika sederhana, admin-only)
- Semua migration core Laravel (users, cache, jobs)
- `ManageDocumentFileName` Trait (logika sudah benar, hanya perlu extension support)

### B. Dipakai Tetapi Perlu Dimodifikasi

| Komponen | Alasan Modifikasi |
|----------|-------------------|
| `Disposisi` model + migration | Sinkronkan `dibaca_pada` vs `dibaca`, enum `status` |
| `DisposisiResource` | Hapus `mount()` yang salah tempat; perbaiki enum status |
| `SuratMasuk` model | Sinkronkan nama kolom `deskripsi`/`keterangan` |
| `SuratMasukResource` | Perbaiki field `deskripsi` vs `keterangan` |
| `SuratKeluar` model | Ubah `ditujukan` ke FK jika perlu konsistensi |
| `SuratKeluarResource` | Selaraskan dengan perubahan model |
| `Agenda` model | Hapus duplikasi logika rename; extend Trait dengan support `dokumen_path` |
| `AgendaResource` | Implementasi simpan pivot `workers` yang hilang |
| `StatsOverview` widget | Ganti `getCards()` ke `getStats()` |
| `User` model | Pertimbangkan gunakan `role_id` FK ke tabel `roles` |
| Semua Policy | Gunakan konstanta role, bukan string hardcode |
| `AppServiceProvider` | Bisa dipersingkat dengan auto-discovery policy Laravel |
| `DatabaseSeeder` | Tambahkan seeder `KategoriDokumen` dan `SumberDokumen` |

### C. Sudah Tidak Relevan

| Komponen | Alasan |
|----------|--------|
| `spatie/laravel-permission` | Ter-install tapi tidak digunakan sama sekali — sistem role dibuat custom |
| `BelongsToMany` import di `User.php` | Di-import tapi tidak digunakan |
| `Illuminate\Auth\Access\Response` import | Di-import di semua Policy tapi tidak digunakan |
| Kolom `jobs`, `job_batches`, `failed_jobs` (tabel) | Queue tidak digunakan |

### D. Sebaiknya Dipindahkan / Direfactor

| Komponen | Rekomendasi |
|----------|-------------|
| Logika `booted()` di semua model | Pindahkan ke **Observer** class terpisah |
| Logika disposisi inline di `SuratMasukResource` | Pindahkan ke **Service class** `DisposisiService` |
| Query langsung di widget `StatsOverview` | Pindahkan ke **Repository** atau **Service** |
| HTML template string di `formatStateUsing()` | Pindahkan ke Blade partial / component |

---

## 12. Rekomendasi Refactor

### Prioritas 1 — Bug Fix (Segera)

1. **Sinkronkan enum `status` Disposisi** antara migration (`pending/selesai`) dan Resource form (`belum/proses/selesai`). Pilih satu, buat migration alter table.
2. **Perbaiki kolom `dibaca_pada`** di Disposisi — tambahkan ke migration atau hapus dari `$fillable`.
3. **Sinkronkan `deskripsi` vs `keterangan`** di `SuratMasuk` antara migration, model, dan resource.
4. **Perbaiki `AgendaResource`** — implementasikan simpan relasi `workers` ke pivot `agenda_worker` yang saat ini hilang (`->dehydrated(false)` tanpa afterSave logic).
5. **Hapus `mount()` dari `DisposisiResource`** — pindahkan ke `ViewDisposisi` page atau gunakan Observer.

### Prioritas 2 — Arsitektur

6. **Hapus `spatie/laravel-permission`** dari `composer.json` atau gunakan sepenuhnya — jangan biarkan menjadi dead dependency.
7. **Unifikasi sistem role** — pilih antara kolom string `users.role` atau FK `users.role_id` ke tabel `roles`. Saat ini keduanya hidup paralel dan membingungkan.
8. **Buat Observer** untuk `SuratMasuk`, `SuratKeluar`, `DokumenLain`, dan `Agenda` — pisahkan dari `booted()`.
9. **Buat `DisposisiService`** untuk enkapsulasi logika pembuatan disposisi dari Resource.
10. **Tambah konstanta role** di model `Role` atau enum PHP untuk menghindari string hardcode di seluruh Policy.

### Prioritas 3 — Konsistensi & Peningkatan

11. **`SuratKeluar.ditujukan`** — pertimbangkan ubah ke FK ke `workers` agar konsisten dengan `SuratMasuk.ditujukan_id`.
12. **Tambah inverse relation** di `KategoriDokumen`, `SumberDokumen`, `Role`.
13. **Tambah seeder** untuk `KategoriDokumen` dan `SumberDokumen`.
14. **Perbaiki `StatsOverview`** menggunakan `getStats()` sesuai Filament v3 terbaru.
15. **Ekstrak HTML inline** dari `formatStateUsing()` ke Blade component/partial.
16. **Tambah SoftDelete** pada `DokumenLain` dan `Agenda` untuk konsistensi.
17. **Validasi versi Filament** — konfirmasi apakah target upgrade ke Filament v4 atau tetap v3.
