<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Klinik Welly</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('img/klinik.jpeg') }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,600,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style AdminLTE / SIMRS -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    <style>
        body.login-page {
            background-color: #e9ecef;
            background-image: 
                radial-gradient(circle at 50% 50%, rgba(2, 132, 199, 0.08) 0%, transparent 60%),
                linear-gradient(to bottom, #edf2f7 0%, #e2e8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Source Sans Pro', Tahoma, Arial, sans-serif;
            position: relative;
        }

        /* WATERMARK LOGO KLINIK TRANSPARAN RESMI */
        body.login-page::before {
            content: "";
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 480px;
            height: 480px;
            max-width: 85vw;
            max-height: 85vh;
            background-image: url("{{ asset('img/klinik.jpeg') }}");
            background-repeat: no-repeat;
            background-position: center center;
            background-size: contain;
            opacity: 0.08;
            pointer-events: none;
            z-index: 0;
            filter: grayscale(10%);
        }

        .login-box {
            width: 400px;
            max-width: 92%;
            position: relative;
            z-index: 2;
        }

        .card-simrs {
            border-top: 4px solid #0284c7;
            border-radius: 6px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            background: #ffffff;
            border-left: 1px solid #dcdfe4;
            border-right: 1px solid #dcdfe4;
            border-bottom: 1px solid #dcdfe4;
        }

        .card-simrs .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #edf2f7;
            padding: 24px 20px 14px 20px;
        }

        .logo-circle-box {
            width: 82px;
            height: 82px;
            margin: 0 auto 12px auto;
            border-radius: 50%;
            background: #ffffff;
            padding: 3px;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.18);
            border: 2px solid #0284c7;
            overflow: hidden;
        }

        .logo-circle-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .hospital-name {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .hospital-sub {
            font-size: 13px;
            font-weight: 600;
            color: #0284c7;
            margin-bottom: 4px;
        }

        .hospital-slogan {
            font-size: 11px;
            color: #64748b;
            font-style: italic;
        }

        .login-card-body {
            padding: 24px 28px 24px 28px;
        }

        .login-box-msg {
            font-size: 12.5px;
            color: #475569;
            margin-bottom: 18px;
            padding: 0;
            font-weight: 600;
        }

        .form-control {
            height: 38px;
            font-size: 13px;
            border-radius: 4px;
            border-color: #cbd5e1;
        }

        .form-control:focus {
            border-color: #0284c7;
            box-shadow: 0 0 0 2px rgba(2, 132, 199, 0.2);
        }

        .input-group-text {
            background-color: #f8fafc;
            border-color: #cbd5e1;
            color: #64748b;
            border-radius: 4px;
        }

        .btn-simrs-primary {
            background-color: #0284c7;
            border-color: #0284c7;
            color: #ffffff;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 0.3px;
            padding: 8px 16px;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .btn-simrs-primary:hover {
            background-color: #0369a1;
            border-color: #0369a1;
            color: #ffffff;
            box-shadow: 0 3px 8px rgba(2, 132, 199, 0.35);
        }

        .footer-simrs {
            font-size: 11px;
            color: #64748b;
            text-align: center;
            margin-top: 14px;
        }
    </style>
</head>

<body class="hold-transition login-page">
    @yield('konten')

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    @yield('scripts')
</body>

</html>
