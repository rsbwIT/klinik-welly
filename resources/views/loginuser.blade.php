@extends('layout.layoutlogin')
@section('title', 'Login Pelayanan')

@section('konten')
<div class="login-box">
    <div class="card card-simrs">
        <!-- HEADER KOP KLINIK SIMRS RESMI -->
        <div class="card-header text-center">
            <div class="logo-circle-box">
                <img src="{{ asset('img/klinik.jpeg') }}" alt="Logo Klinik Welly">
            </div>
            <div class="hospital-name">KLINIK PERAWAT MANDIRI WELLY</div>
            <div class="hospital-sub">Welly Prestikasari, S.Kep., Ns. CWCCA</div>
            <div class="hospital-slogan">"Merawat Luka, Mengembalikan Harapan"</div>
        </div>

        <div class="card-body login-card-body">
            <p class="login-box-msg text-center">
                <i class="fas fa-lock text-primary mr-1"></i> Masuk ke Sistem Pelayanan
            </p>

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show py-2 px-3 shadow-2xs font-weight-normal mb-3" style="font-size: 12px;" role="alert">
                    <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
                    <button type="button" class="close p-2" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <form action="{{ route('mesinLogin') }}" method="POST">
                @csrf
                <!-- INPUT EMAIL -->
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email / ID Pengguna" value="{{ old('email') }}" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                @error('email')
                    <div class="text-danger small mt-n2 mb-2" style="font-size: 11px;">{{ $message }}</div>
                @enderror

                <!-- INPUT PASSWORD -->
                <div class="input-group mb-3">
                    <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Kata Sandi / Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text" style="cursor: pointer;" onclick="togglePasswordLogin()" title="Lihat Password">
                            <span class="fas fa-eye" id="iconEye"></span>
                        </div>
                    </div>
                </div>

                <div class="row align-items-center mb-3">
                    <div class="col-7">
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" id="remember" name="remember" checked>
                            <label for="remember" style="font-size: 12px; font-weight: 500; color: #475569;">
                                Ingat sesi saya
                            </label>
                        </div>
                    </div>
                    <div class="col-5">
                        <button type="submit" class="btn btn-simrs-primary btn-block">
                            <i class="fas fa-sign-in-alt mr-1"></i> Masuk
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- FOOTER IDENTITAS -->
    <div class="footer-simrs">
        &copy; {{ date('Y') }} Klinik Perawat Mandiri Welly &bull; Bandar Lampung
    </div>
</div>
@endsection

@section('scripts')
<script>
    function togglePasswordLogin() {
        var pwd = document.getElementById('inputPassword');
        var icon = document.getElementById('iconEye');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            pwd.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection
