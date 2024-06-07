<nav class="main-header navbar navbar-expand navbar-white navbar-light tutorial">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ Auth::user()?->role == 1 ? route('admin.index') : route('user.home') }}" class="nav-link">TRANG
                CHỦ</a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="true">
                <i class="fa-solid fa-gear"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-left" style="left: inherit; right: 0px;">
                <p class="dropdown-item" style="font-weight: bold;">Cài đặt</p>
                <button style="padding-left: 40px" class="dropdown-item" data-target="#modalChangePassword"
                    data-toggle="modal">
                    -&emsp;Đổi mật khẩu
                </button>
                <a href="{{ route('user.logout') }}" style="padding-left: 40px" class="dropdown-item"
                    onclick="return confirm('Bạn có muốn đăng xuất?')" class="nav-link">
                    -&emsp;Đăng xuất
                </a>
                <div class="dropdown-divider"></div>
            </div>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search"
                            aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>
    </ul>
</nav>
<div class="modal fade" id="modalChangePassword" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Đổi mật khẩu</h4>
                <button type="button" class="closeModalChangePassword close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <input name="tel_or_email" id="tel_or_email" type="text" value="" class="form-control"
                        placeholder="Nhập tên bài">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="old_password" id="old_password" value=""
                        class="form-control" placeholder="Nhập mật khẩu cũ">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" id="password" value="" class="form-control"
                        placeholder="Nhập mật khẩu mới">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <button style="width: 100%" class="btn btn-primary btn-change-password">Đổi mật khẩu</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
