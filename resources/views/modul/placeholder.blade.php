@extends('layout.layout')

@section('title', $meta['title'])

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0 mt-3" style="border-radius: 14px;">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mr-3 shadow-sm" style="width: 48px; height: 48px;">
                            <i class="{{ $meta['icon'] }} text-{{ $meta['color'] }}" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 font-weight-bold text-dark">{{ $meta['title'] }}</h5>
                            <small class="text-muted"><span class="badge badge-light border text-uppercase">{{ $kategori }}</span> • Mengikuti Standar Modul mLITE SIMRS Khanza</small>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('rawatjalan.index') }}" class="btn btn-sm btn-outline-primary" style="border-radius: 8px;">
                            <i class="fas fa-arrow-left mr-1"></i> Ke Rawat Jalan
                        </a>
                    </div>
                </div>

                <div class="card-body p-4 text-center">
                    <div class="my-4">
                        <div class="d-inline-flex p-4 rounded-circle mb-3" style="background-color: rgba(2, 132, 199, 0.08);">
                            <i class="{{ $meta['icon'] }} text-{{ $meta['color'] }}" style="font-size: 3.5rem;"></i>
                        </div>
                        <h4 class="font-weight-bold text-dark mb-2">{{ $meta['title'] }}</h4>
                        <p class="text-muted mx-auto" style="max-width: 580px; font-size: 14px;">
                            {{ $meta['desc'] }}
                        </p>
                    </div>

                    <div class="alert alert-info d-inline-block text-left p-3 shadow-sm border-0 mb-4" style="border-radius: 10px; max-width: 650px; background-color: #f0f9ff; border-left: 4px solid #0284c7 !important;">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-info-circle text-primary mt-1 mr-2" style="font-size: 1.2rem;"></i>
                            <div>
                                <strong class="text-dark d-block">Struktur Navigasi mLITE Terpasang</strong>
                                <span class="text-muted" style="font-size: 13px;">
                                    Modul ini telah diselaraskan dengan database SIMRS Khanza & mLITE. Halaman fungsional operasional dapat segera dihubungkan ke tabel data master terkait.
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center gap-2 mt-2">
                        <a href="{{ route('rawatjalan.index') }}" class="btn btn-primary px-4 py-2" style="border-radius: 8px;">
                            <i class="fas fa-procedures mr-1"></i> Buka Antrian Rawat Jalan
                        </a>
                        <a href="{{ route('masterPasien.create') }}" class="btn btn-outline-secondary px-4 py-2" style="border-radius: 8px;">
                            <i class="fas fa-user-plus mr-1"></i> Pendaftaran Pasien Baru
                        </a>
                    </div>
                </div>

                <div class="card-footer bg-light py-3 border-top d-flex justify-content-between align-items-center" style="border-bottom-left-radius: 14px; border-bottom-right-radius: 14px;">
                    <small class="text-muted"><i class="fas fa-shield-alt text-success mr-1"></i> Klinik Perawat Luka Welly • Modul mLITE Compatibility Mode</small>
                    <small class="text-muted font-italic">Port 8001</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

