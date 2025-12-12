<div class="login-two-column-container">
    {{-- Kolom Kiri: Form Login --}}
    <div class="login-form-column">
        <div class="login-content-wrapper">
            <div class="login-card">
                {{-- Header Section --}}
                <div class="login-header-section">
                    <h2 class="login-heading">Selamat Datang</h2>
                    <p class="login-subheading">Silakan masuk ke Sistem Manajemen Surat</p>
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
                    <svg class="login-logo-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h2 class="login-info-title">Sistem Manajemen Surat</h2>
                <p class="login-info-subtitle">Platform Digital untuk Pengelolaan Surat Menyurat</p>
            </div>

            <div class="login-info-features">
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="feature-text">
                        <h3>Surat Masuk & Keluar</h3>
                        <p>Kelola surat masuk dan keluar dengan mudah dan terstruktur</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="feature-text">
                        <h3>Agenda & Disposisi</h3>
                        <p>Atur agenda dan disposisi dokumen secara efisien</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="feature-text">
                        <h3>Laporan & Monitoring</h3>
                        <p>Pantau dan analisis data surat secara real-time</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>