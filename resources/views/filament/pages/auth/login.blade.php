<div class="login-two-column-container">
    {{-- Kolom Kiri: Form Login --}}
    <div class="login-form-column">
        <div class="login-content-wrapper">
            <div class="login-card">
                {{-- Header Section --}}
                <div class="login-header-section">
                    <h2 class="login-heading">Selamat Datang</h2>
                    <p class="login-subheading">Silakan masuk ke SIM Manajemen Dokumen</p>
                </div>

                {{-- Form --}}
                <x-filament-panels::form wire:submit="authenticate">
                    {{ $this->form }}

                    <x-filament-panels::form.actions
                        :actions="$this->getCachedFormActions()"
                        :full-width="$this->hasFullWidthFormActions()" />
                </x-filament-panels::form>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Informasi/Branding --}}
    <div class="login-info-column">
        <div class="login-info-content">
            <div class="login-info-header">
                <div class="login-info-logo">
                    <x-heroicon-o-folder class="w-24 h-24" />
                </div>
                <h2 class="login-info-title">Sistem Informasi Manajemen Dokumen</h2>
                <p class="login-info-subtitle">Aplikasi Terintegrasi untuk Pengelolaan, Pengarsipan, dan Monitoring Dokumen Internal Organisasi.</p>
            </div>

            <div class="login-info-features">
                <div class="feature-item">
                    <div class="feature-icon">
                        <x-heroicon-o-document class="w-8 h-8" />
                    </div>
                    <div class="feature-text">
                        <h3>Pengelolaan Dokumen</h3>
                        <p>Mengelola dokumen RUK, RPK, Surat Keputusan, SOP, serta dokumen pendukung lainnya secara terstruktur.</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <x-heroicon-o-archive-box class="w-8 h-8" />
                    </div>
                    <div class="feature-text">
                        <h3>Arsip Digital</h3>
                        <p>Memudahkan pencarian dan pengelompokan dokumen berdasarkan tahun, unit kerja, jenis dokumen, dan klaster.</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <x-heroicon-o-chart-bar class="w-8 h-8" />
                    </div>
                    <div class="feature-text">
                        <h3>Dashboard & Laporan</h3>
                        <p>Menyajikan informasi statistik dokumen dan laporan sebagai dasar monitoring dan evaluasi.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>