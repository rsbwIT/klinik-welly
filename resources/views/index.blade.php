@extends('layout.layout')
@section('title', 'Dashboard Utama')

@section('konten')
    @if (Session::has('pesanSukses'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            <div>
                <strong>Selamat Datang!</strong> {{ Session::get('pesanSukses') }} di Sistem Informasi Klinik Perawat Luka Welly.
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- PANEL UTAMA POLOSAN BERSATU DENGAN LOGO KLINIK -->
    <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-body bg-white py-5 px-4 text-center">
            
            <!-- LOGO KLINIK -->
            <div class="mb-4">
                <div class="d-inline-block p-2 rounded-circle shadow-sm" style="background: #ffffff; border: 4px solid var(--theme-primary, #0284c7);">
                    <img src="{{ asset('img/klinik.jpeg') }}" alt="Logo Klinik Welly" 
                         class="rounded-circle" 
                         style="width: 140px; height: 140px; object-fit: cover;">
                </div>
            </div>

            <!-- NAMA & IDENTITAS KLINIK -->
            <h2 class="font-weight-bold text-dark mb-1" style="letter-spacing: 0.5px;">
                KLINIK PERAWAT MANDIRI WELLY
            </h2>
            <h5 class="font-weight-bold text-primary mb-3">
                Welly Prestikasari, S.Kep., Ners., M.Kep.
            </h5>
            <p class="text-muted mx-auto mb-4" style="max-width: 650px; font-size: 15px; line-height: 1.6;">
                Pelayanan Asuhan Keperawatan, Spesialis Perawatan Luka Modern, Terapi Luka Kronis / Diabetes, Pelayanan Pemeriksaan Laboratorium Sederhana, dan Pelatihan Padepokan Cettol.
            </p>

            <div class="d-inline-flex flex-wrap align-items-center justify-content-center py-2 px-3 mb-4 rounded-pill bg-light border text-secondary" style="font-size: 13px;">
                <span class="mr-3"><i class="fas fa-map-marker-alt text-danger mr-1"></i> Dusun 3 RT/RW 015/004, Desa Sidodadi Kec. Teluk Pandan - Kab. Pesawaran</span>
                <span class="mr-3"><i class="fab fa-whatsapp text-success mr-1"></i> 0813 6864 0404</span>
                <span><i class="fas fa-heartbeat text-info mr-1"></i> <em>"Merawat Luka, Mengembalikan Harapan"</em></span>
            </div>

            <hr class="my-4" style="max-width: 750px; opacity: 0.15;">

            <!-- STATISTIK RINGKAS TOTAL DI DALAM CARD POLOSAN -->
            <div class="row justify-content-center" style="max-width: 800px; margin: 0 auto;">
                <div class="col-sm-3 col-6 mb-2">
                    <div class="p-2 rounded bg-light border">
                        <div class="text-muted text-uppercase" style="font-size: 11px; font-weight: 700;">Pasien Hari Ini</div>
                        <div class="h4 font-weight-bold text-info mb-0">{{ $pasienHariIni ?? 0 }}</div>
                    </div>
                </div>
                <div class="col-sm-3 col-6 mb-2">
                    <div class="p-2 rounded bg-light border">
                        <div class="text-muted text-uppercase" style="font-size: 11px; font-weight: 700;">Total Kunjungan</div>
                        <div class="h4 font-weight-bold text-primary mb-0">{{ $totalPasien ?? 0 }}</div>
                    </div>
                </div>
                <div class="col-sm-3 col-6 mb-2">
                    <div class="p-2 rounded bg-light border">
                        <div class="text-muted text-uppercase" style="font-size: 11px; font-weight: 700;">Master Pasien</div>
                        <div class="h4 font-weight-bold text-success mb-0">{{ $totalMaster ?? 0 }}</div>
                    </div>
                </div>
                <div class="col-sm-3 col-6 mb-2">
                    <div class="p-2 rounded bg-light border">
                        <div class="text-muted text-uppercase" style="font-size: 11px; font-weight: 700;">Pemeriksaan Lab</div>
                        <div class="h4 font-weight-bold text-warning mb-0">{{ $totalLab ?? 0 }}</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection