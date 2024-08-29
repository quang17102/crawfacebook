<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->

    <!-- Sidebar -->
    <div class="sidebar">
        @switch(Auth::user()?->role)
            @case(0)
                <a href="{{ route('user.home') }}" class="brand-link text-center">
                    <span class="brand-text font-weight-light">Người dùng</span>
                </a>
            @break

            @case(1)
                <a href="{{ route('admin.index') }}" class="brand-link text-center">
                    <span class="brand-text font-weight-light">Quản lý</span>
                </a>
            @break

            @case(2)
                <a href="{{ route('customers.me') }}" class="brand-link text-center">
                    <span class="brand-text font-weight-light">Khách hàng</span>
                </a>
            @break
        @endswitch
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 d-flex">
            @switch(Auth::user()?->role)
                @case(0)
                    <div class="image">
                        <img src="{{ Auth::user()?->staff->avatar ?? '/images/default.jpg' }}" class="img-circle elevation-2"
                            alt="User Image">
                    </div>
                @break
            @endswitch
            <div class="info" style="text-align: center">
                @switch(Auth::user()?->role)
                    @case(0)
                        <a href="{{ route('user.home') }}" class="d-block">{{ Auth::user()->name ?? Auth::user()->email }}</a>
                    @break

                    @case(1)
                        <a href="{{ route('admin.index') }}" class="d-block">{{ Auth::user()->name ?? Auth::user()->email }}</a>
                    @break
                @endswitch
                <p style="color: white">Số ngày hết hạn: {{ Auth::user()?->time_to_expire }}</p>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                @switch(Auth::user()?->role)
                    {{-- Staff --}}
                    @case(0)
                        <li
                            class="nav-item {{ in_array(request()->route()->getName(), ['user.linkscans.index', 'user.linkscans.create'])
                                ? 'menu-is-opening menu-open'
                                : '' }}">
                            <a href="{{ route('user.linkscans.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-barcode"></i>
                                <p>
                                    Link quét
                                </p>
                            </a>
                        </li>
                        @if (in_array(App\Constant\GlobalConstant::ROLE_FOLLOW, $userRoles))
                            <li
                                class="nav-item {{ in_array(request()->route()->getName(), ['user.linkfollows.index', 'user.linkfollows.create'])
                                    ? 'menu-is-opening menu-open'
                                    : '' }}">
                                <a href="{{ route('user.linkfollows.index') }}" class="nav-link">
                                    <i class="nav-icon fa-solid fa-user-plus"></i>
                                    <p>
                                        Link theo dõi
                                    </p>
                                </a>
                            </li>
                        @endif
                        <li
                            class="nav-item {{ in_array(request()->route()->getName(), ['user.comments.index', 'user.comments.create'])
                                ? 'menu-is-opening menu-open'
                                : '' }}">
                            <a href="{{ route('user.comments.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-comment"></i>
                                <p>
                                    Bình luận
                                </p>
                            </a>
                        </li>
                        @if (in_array(App\Constant\GlobalConstant::ROLE_REACTION, $userRoles))
                            <li
                                class="nav-item {{ in_array(request()->route()->getName(), ['user.reactions.index', 'user.reactions.create'])
                                    ? 'menu-is-opening menu-open'
                                    : '' }}">
                                <a href="{{ route('user.reactions.index') }}" class="nav-link">
                                    <i class="nav-icon fa-solid fa-face-smile"></i>
                                    <p>
                                        Cảm xúc
                                    </p>
                                </a>
                            </li>
                        @endif
                    @break

                    {{-- Admin --}}
                    @case(1)
                        <li
                            class="nav-item {{ in_array(request()->route()->getName(), ['admin.accounts.index', 'admin.accounts.create'])
                                ? 'menu-is-opening menu-open'
                                : '' }}">
                            <a href="{{ route('admin.accounts.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-user"></i>
                                <p>
                                    Tài khoản
                                </p>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ in_array(request()->route()->getName(), ['admin.linkscans.index', 'admin.linkscans.create'])
                                ? 'menu-is-opening menu-open'
                                : '' }}">
                            <a href="{{ route('admin.linkscans.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-barcode"></i>
                                <p>
                                    Link quét
                                </p>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ in_array(request()->route()->getName(), ['admin.linkfollows.index', 'admin.linkfollows.create'])
                                ? 'menu-is-opening menu-open'
                                : '' }}">
                            <a href="{{ route('admin.linkfollows.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-user-plus"></i>
                                <p>
                                    Link theo dõi
                                </p>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ in_array(request()->route()->getName(), ['admin.linkrunnings.index', 'admin.linkrunnings.create'])
                                ? 'menu-is-opening menu-open'
                                : '' }}">
                            <a href="{{ route('admin.linkrunnings.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-check"></i>
                                <p>
                                    Link đang chạy
                                </p>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ in_array(request()->route()->getName(), ['admin.comments.index', 'admin.comments.create'])
                                ? 'menu-is-opening menu-open'
                                : '' }}">
                            <a href="{{ route('admin.comments.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-comment"></i>
                                <p>
                                    Bình luận
                                </p>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ in_array(request()->route()->getName(), ['admin.reactions.index', 'admin.reactions.create'])
                                ? 'menu-is-opening menu-open'
                                : '' }}">
                            <a href="{{ route('admin.reactions.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-face-smile"></i>
                                <p>
                                    Cảm xúc
                                </p>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ in_array(request()->route()->getName(), ['settings.index']) ? 'menu-is-opening menu-open' : '' }}">
                            <a href="{{ route('settings.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-gear"></i>
                                <p>
                                    Cài đặt
                                </p>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ in_array(request()->route()->getName(), ['settings_admin_1.index']) ? 'menu-is-opening menu-open' : '' }}">
                            <a href="{{ route('settings_admin_1.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-gear"></i>
                                <p>
                                    Cài đặt Ads
                                </p>
                            </a>
                        </li>
                        <!-- <li
                            class="nav-item {{ in_array(request()->route()->getName(), ['settings_admin_2.index']) ? 'menu-is-opening menu-open' : '' }}">
                            <a href="{{ route('settings_admin_2.index') }}" class="nav-link">
                                <i class="nav-icon fa-solid fa-gear"></i>
                                <p>
                                    Cài đặt Filter
                                </p>
                            </a>
                        </li> -->
                    @break

                @endswitch
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
