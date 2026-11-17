@extends('layout.layout')
@section('title', 'Tracker Aktivitas Pengguna')

@section('konten')
    <!-- HEADER PANEL TRACKER -->
    <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <div class="d-flex align-items-center mb-2 mb-md-0">
                    <div class="clinic-header-icon mr-3">
                        <i class="fas fa-history"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 font-weight-bold text-dark">Audit Trail & Tracker Aktivitas Pengguna</h5>
                        <small class="text-muted">Pencatatan real-time seluruh aktivitas operasional petugas di sistem klinik</small>
                    </div>
                </div>
                <div>
                    <span class="badge badge-success px-3 py-2 font-weight-bold">
                        <i class="fas fa-satellite-dish mr-1"></i> Live Tracker Aktif
                    </span>
                </div>
            </div>
        </div>

        <!-- FILTER PENCARIAN TRACKER -->
        <div class="card-body bg-light py-3 border-bottom">
            <form action="{{ route('tracker.index') }}" method="GET" class="row align-items-center m-0">
                <div class="col-md-3 form-group mb-2 mb-md-0 pl-0">
                    <label class="small font-weight-bold text-muted mb-1">Pengguna / Petugas:</label>
                    <select name="user_email" class="form-control form-control-sm">
                        <option value="">-- Semua Pengguna --</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->email }}" {{ request('user_email') == $u->email ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->role }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 form-group mb-2 mb-md-0">
                    <label class="small font-weight-bold text-muted mb-1">Modul:</label>
                    <select name="modul" class="form-control form-control-sm">
                        <option value="">-- Semua Modul --</option>
                        @foreach ($moduls as $m)
                            <option value="{{ $m }}" {{ request('modul') == $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 form-group mb-2 mb-md-0">
                    <label class="small font-weight-bold text-muted mb-1">Tanggal Aktivitas:</label>
                    <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ request('tanggal') }}">
                </div>
                <div class="col-md-3 form-group mb-0 pr-0 text-md-right mt-3 mt-md-0">
                    <button type="submit" class="btn btn-sm btn-primary px-3 font-weight-bold">
                        <i class="fas fa-filter mr-1"></i> Saring Log
                    </button>
                    @if (request()->hasAny(['user_email', 'modul', 'tanggal']))
                        <a href="{{ route('tracker.index') }}" class="btn btn-sm btn-outline-secondary ml-1">
                            <i class="fas fa-undo"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- TABEL LOG AKTIVITAS -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th width="40px" class="text-center">No</th>
                            <th width="150px">Waktu Log</th>
                            <th width="180px">Petugas / User</th>
                            <th width="130px">Modul</th>
                            <th width="100px" class="text-center">Aksi</th>
                            <th>Keterangan Aktivitas</th>
                            <th width="120px" class="text-center">IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $idx => $log)
                            <tr>
                                <td class="text-center font-weight-bold text-muted">{{ $logs->firstItem() + $idx }}</td>
                                <td>
                                    <div class="font-weight-bold text-dark small">{{ date('d/m/Y', strtotime($log->created_at)) }}</div>
                                    <small class="text-muted">{{ date('H:i:s', strtotime($log->created_at)) }} WIB</small>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark">{{ $log->user_name ?? '-' }}</div>
                                    <small class="text-muted">{{ $log->user_email }}</small>
                                    <div>
                                        <span class="badge {{ $log->user_role == 'admin' ? 'badge-success' : 'badge-info' }}" style="font-size: 10px;">
                                            {{ strtoupper($log->user_role) }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-light border font-weight-bold px-2 py-1 text-primary">
                                        {{ $log->modul }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $badgeClass = 'badge-secondary';
                                        if (in_array(strtolower($log->action), ['login', 'tambah', 'insert', 'create'])) $badgeClass = 'badge-success';
                                        elseif (in_array(strtolower($log->action), ['ubah', 'edit', 'update'])) $badgeClass = 'badge-warning text-dark';
                                        elseif (in_array(strtolower($log->action), ['hapus', 'delete'])) $badgeClass = 'badge-danger';
                                    @endphp
                                    <span class="badge {{ $badgeClass }} px-2 py-1 font-weight-bold">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small text-dark" style="max-width: 320px;">
                                        {{ $log->keterangan ?? '-' }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="font-monospace small text-muted">{{ $log->ip_address ?? '127.0.0.1' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-history d-block mb-3 text-secondary" style="font-size: 2.5rem; opacity: 0.3;"></i>
                                    <h6 class="font-weight-bold mb-1">Belum Ada Riwayat Tracker</h6>
                                    <p class="small mb-0">Aktivitas penambahan, perubahan, dan login pengguna akan tercatat di sini secara otomatis.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center p-3 flex-wrap">
                <small class="text-muted">Menampilkan {{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }} dari {{ $logs->total() }} log aktivitas</small>
                <div>
                    {{ $logs->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection

