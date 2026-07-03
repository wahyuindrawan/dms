# Laporan: Dashboard, Laporan & Activity Log

## Packages yang Diinstal

| Package | Versi | Fungsi |
|---|---|---|
| `spatie/laravel-activitylog` | ^4.12 | Log semua aktivitas ke DB |
| `maatwebsite/excel` | ^3.1 | Export Excel dengan Queue support |
| `barryvdh/laravel-dompdf` | ^3.1 | Generate PDF |

---

## 1. Dashboard Widgets

Urutan widget di dashboard (responsive, 2 kolom pada layar ≥ sm):

| Widget | Class | Sort | Colspan |
|---|---|---|---|
| Welcome | `WelcomeWidget` | — | full |
| Stats Overview | `StatsOverview` | 1 | full |
| Grafik per Tahun | `DocumentsPerYearChart` | 3 | 1 |
| Grafik per Jenis | `DocumentsPerTypeChart` | 4 | 1 |
| Dokumen Terbaru | `RecentDocumentsWidget` | 2 | full |

### StatsOverview — 5 Kartu
- Total Dokumen + trend bulan ini vs bulan lalu (⬆/⬇)
- SK, SOP, RUK, RPK — masing-masing dengan link ke resource

### DocumentsPerYearChart
- **Line chart** — tren 5 tahun terakhir
- Data: `GROUP BY YEAR(document_date) COUNT(*)`

### DocumentsPerTypeChart
- **Doughnut chart** — distribusi per jenis dokumen
- Data: `DocumentType::withCount('documents')`

### RecentDocumentsWidget
- **Table widget** — 8 dokumen terbaru
- Kolom: Nomor, Judul, Jenis (badge), Klaster, Status, Tanggal, Dibuat Oleh
- Auto-eager load: `documentType`, `category`, `creator`

---

## 2. Laporan Dokumen

**Halaman:** `App\Filament\Pages\LaporanPage` → `/laporan-dokumen`

### Filter (Live)
| Filter | Component | Sumber Data |
|---|---|---|
| Tahun | Select | Range 2020 – sekarang |
| Jenis Dokumen | Select | `DocumentType::pluck(name, id)` |
| Unit Organisasi | Select | `Unit::where(is_active)->pluck(name, id)` |
| Klaster Dokumen | Select | `DocumentCategory::where(is_active)->pluck(name, id)` |

### Preview
- Tabel HTML live di halaman (tidak perlu submit)
- Menampilkan jumlah data ditemukan

### Export Excel
```
if ($count > 500) {
    // → Queue: DocumentsExport::queue($filename)
    // → Notifikasi: "File akan dikirim via email"
} else {
    // → Download langsung: Excel::download()
}
```

**Class:** `App\Exports\DocumentsExport`
- Implements: `FromQuery`, `WithHeadings`, `WithMapping`, `WithStyles`, `ShouldAutoSize`, `ShouldQueue`
- Style: header biru, auto-size kolom

### Export PDF
- Template: `resources/views/exports/documents-pdf.blade.php`
- Engine: DomPDF, paper A4 landscape
- Log aktivitas otomatis saat export

---

## 3. Activity Log

### Auto-logging pada Model Document
`LogsActivity` trait dipasang di `Document` model:
```php
->logOnly(['title', 'doc_number', 'status', 'document_type_id', 'document_category_id'])
->logOnlyDirty()     // hanya catat field yang berubah
->dontSubmitEmptyLogs()
->useLogName('document')
->setDescriptionForEvent(fn($event) => ...)
```

**Event yang dicatat:**
| Event | Trigger |
|---|---|
| `created` | Dokumen baru disimpan |
| `updated` | Dokumen diubah |
| `deleted` | Dokumen dihapus (soft delete) |
| Login | `Auth\Events\Login` |
| Logout | `Auth\Events\Logout` |
| Login gagal | `Auth\Events\Failed` |
| Export PDF | Manual via `activity()->log()` |

### Listener: `App\Listeners\LogAuthActivity`
Terdaftar di `AppServiceProvider::boot()`:
- `handleLogin()` — mencatat IP + user agent
- `handleLogout()` — mencatat IP
- `handleFailed()` — mencatat IP + email yang dicoba

### ActivityLogResource
- **Navigasi:** Pengaturan → Log Aktivitas (sort: 99)
- **Read-only** (`canCreate(): false`)
- **Filter:** Log name, Event, Pengguna, Rentang tanggal
- **Auto-refresh:** `->poll('30s')` — tabel refresh tiap 30 detik
- **Halaman:** List + View detail

---

## 4. Performance Recommendations

> [!TIP]
> **Database Indexing** — Pastikan index berikut ada di tabel `activity_log`:
> ```sql
> ALTER TABLE activity_log ADD INDEX idx_causer (causer_type, causer_id);
> ALTER TABLE activity_log ADD INDEX idx_event (event);
> ALTER TABLE activity_log ADD INDEX idx_log_name (log_name);
> ALTER TABLE activity_log ADD INDEX idx_created_at (created_at);
> ```

> [!TIP]
> **Cache Widget** — Tambahkan `protected static ?string $pollingInterval = null;` di chart widget dan gunakan cache manual untuk query berat:
> ```php
> cache()->remember('docs_per_year', 3600, fn() => Document::query()->groupBy('year')->get());
> ```

> [!TIP]
> **Queue Worker** — Jalankan `php artisan queue:work --tries=3` untuk memproses export Excel besar secara background. Gunakan **Redis** sebagai queue driver di production.

> [!WARNING]
> **Log Retention** — Activity log akan membesar seiring waktu. Jadwalkan pembersihan otomatis:
> ```php
> // config/activitylog.php
> 'delete_records_older_than_days' => 365,
> ```
> Tambahkan ke `schedule()` di `routes/console.php`:
> ```php
> Schedule::command('activitylog:clean')->monthly();
> ```

> [!NOTE]
> **Export Queue di Production** — Set `QUEUE_CONNECTION=redis` di `.env` dan pastikan `mail` dikonfigurasi untuk notifikasi email saat export selesai (via `ShouldQueue::chain()`).

---

## Struktur File yang Dibuat

```
app/
├── Exports/
│   └── DocumentsExport.php       ← Excel export dengan Queue
├── Filament/
│   ├── Pages/
│   │   ├── Dashboard.php         ← Updated: semua widget
│   │   └── LaporanPage.php       ← Baru: laporan + export
│   ├── Resources/
│   │   └── ActivityLogResource/  ← Baru: lihat activity log
│   └── Widgets/
│       ├── StatsOverview.php     ← Updated: trend + 5 cards
│       ├── RecentDocumentsWidget.php  ← Baru
│       ├── DocumentsPerYearChart.php  ← Baru (line chart)
│       └── DocumentsPerTypeChart.php  ← Baru (doughnut chart)
├── Listeners/
│   └── LogAuthActivity.php       ← Login/logout listener
└── Models/
    └── Document.php              ← Updated: LogsActivity trait

resources/views/
├── exports/
│   └── documents-pdf.blade.php  ← Template PDF laporan
└── filament/pages/
    └── laporan.blade.php        ← View halaman laporan
```
