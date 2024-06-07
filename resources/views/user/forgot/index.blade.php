<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/js/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="/js/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/js/dist/css/adminlte.min.css">
    {{--  --}}
    {{-- <link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"> --}}
    <!-- ajax -->
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <style>
        .hidden {
            display: none;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
            <a href="#">Quản lý</a>
            </div>
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Lấy lại mật khẩu</p>
                    <form action="{{ route('user.recover') }}" method="POST">
                        <div class="input-group mb-3">
                            <input name="email" type="email" class="form-control" value="{{ old('email')}}" placeholder="Nhập email của bạn">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block">Lấy lại mật khẩu</button>
                            </div>
                        </div>
                        @csrf
                    </form>
                    <p class="mb-1">
                        <a href="{{ route('user.register') }}">Đăng ký</a>
                    </p>
                    <p class="mb-0">
                        <a href="{{ route('user.login') }}" class="text-center">Đã có tài khoản</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
    {{-- <script src="/js/plugins/jquery/jquery.min.js"></script> --}}
    <!-- Bootstrap 4 -->
    <script src="/js/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/js/dist/js/adminlte.min.js"></script>
    <!-- main.js-->
    {{-- <script src="/js/main.js"></script> --}}
    <div class="Toastify"></div>
    <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
    {!! Toastr::message() !!}
</body>

</html>
