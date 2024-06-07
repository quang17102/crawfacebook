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
    <link rel="stylesheet" href="/js/plugins/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="/js/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/js/dist/css/adminlte.min.css">
    <!-- ajax -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <!-- select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    {{--  --}}
    {{-- <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css"> --}}
    <style>
        span.required {
            color: red;
        }

        span.select2-dropdown {
            top: -25px;
        }

        #table_filter {
            text-align: right;
        }

        .hidden {
            display: none;
        }

        .option-open {
            background-color: rgba(255, 255, 255, .1);
        }

        .open-block {
            display: block;
        }

        .open-none {
            display: none;
        }

        .table.dataTable.nowrap th,
        .table.dataTable.nowrap td {
            white-space: normal !important;
        }

        .dataTables_paginate {
            float: right;
        }

        .pagination li {
            margin-left: 10px;
        }

        .select2-container,
        .form-inline,
        .form-inline label {
            display: inline !important;
        }

        .select2-search__field {
            border: none !important;
        }

        .select2-selection__choice__display {
            color: black;
        }

        .icon {
            padding: 3px 4px;
            border-radius: 10px;
        }

        .table {
            width: 100% !important;
        }

        @media (max-width: 600px) {
            .hide-max-600 {
                display: none !important;
                color: white !important;
            }
        }

        .header-color {
            background-color: #28a745;
            color: white;
        }

        .tool-tip {
            position: relative;
            display: inline-block;
            /* border-bottom: 1px dotted black; */
        }

        .tool-tip:hover {
            cursor: pointer;
        }

        .card-body {
            overflow-x: clip !important;
        }
    </style>
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Preloader -->
        {{-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="/images/gg.png" alt="Áo đá bóng" height="60"
                width="120">
        </div> --}}
        @include('admin.menu')
        @include('admin.sidebar')

        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary mt-3">
                                <div class="card-header">
                                    <h3 class="card-title">{{ $title }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    @yield('content')
                </div>
            </section>
        </div>
    </div>
    <button style="display:none" class="btn-history" data-target="#modalHistory" data-toggle="modal"></button>
    <input type="file" style="opacity: 0" id="file-restore-db" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/js/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/js/dist/js/adminlte.min.js"></script>
    <!-- main.js-->
    {{-- <script src="/js/main.js"></script> --}}
    <div class="Toastify"></div>
    <script src="https://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
    {!! Toastr::message() !!}
    {{-- select2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- common --}}
    <script src="/js/common/index.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $(function() {
            // $(document).tooltip();
            $(' .card-body').css('overflow-x', '');
        });
        // show name_facebook
        $(document).on('mouseenter', '.show-name_facebook', async function() {
            let uid = $(this).data('uid');
            let id = $(this).data('id');
            $('.tooltip-name_facebook-' + id).css('display', 'block');
            $('.tooltip-name_facebook-' + id).html(`UID: ${uid || 'Trống'}`);
        });

        $(document).on('click', '.show-name_facebook', function() {
            let uid = $(this).data('uid');
            // navigator.clipboard.writeText(uid);
            window.open(`https://www.facebook.com/${uid}`, '_blank').focus();
        });

        $(document).on('mouseleave', '.show-name_facebook', function() {
            $('.tooltip-name_facebook').html('');
            $('.tooltip-name_facebook').css('display', 'none');
        })
        // show uid
        $(document).on('mouseenter', '.show-uid', async function() {
            let uid = $(this).data('uid');
            let id = $(this).data('id');
            $('.tooltip-uid-' + id).css('display', 'block');
            $('.tooltip-uid-' + id).html(`UID: ${uid || 'Trống'}`);
        });

        $(document).on('click', '.show-uid', function() {
            let uid = $(this).data('uid');
            // navigator.clipboard.writeText(uid);
            window.open(`https://www.facebook.com/${uid}`, '_blank').focus();
        });

        $(document).on('mouseleave', '.show-uid', function() {
            $('.tooltip-uid').html('');
            $('.tooltip-uid').css('display', 'none');
        })
        // show content
        $(document).on('mouseenter', '.show-content', async function() {
            let content = $(this).data('content');
            if (content) {
                let link_or_post_id = $(this).data('link_or_post_id');
                let id = $(this).data('id');
                $('.tooltip-content-' + id).css('display', 'block');
                $('.tooltip-content-' + id).html(`${content || 'Trống'}`);
            }
        });

        $(document).on('mouseleave', '.show-content', function() {
            $('.tooltip-content').html('');
            $('.tooltip-content').css('display', 'none');
        })

        $(document).on('click', '.show-content', function() {
            let content = $(this).data('content');
            // let link_or_post_id = $(this).data('link_or_post_id');
            // navigator.clipboard.writeText(content);
        });
        // show title
        $(document).on('click', '.show-title', function() {
            let link_or_post_id = $(this).data('link_or_post_id');
            // navigator.clipboard.writeText(link_or_post_id);
            let id = $(this).data('id');
            window.open(!isNumeric(link_or_post_id) ? link_or_post_id :
                `https://www.facebook.com/${link_or_post_id}`, '_blank').focus();
        });
        $(document).on('mouseenter', '.show-title', function() {
            let content = $(this).data('content');
            let link_or_post_id = $(this).data('link_or_post_id');
            let id = $(this).data('id');
            let type = $(this).data('type');
            if (type == 'content') {
                $('.tooltip-title-' + id).css('display', content ? 'block' : 'none');
                $('.tooltip-title-' + id).html(`Nội dung: ${content || ''}`);
            } else {
                $('.tooltip-title-' + id).css('display', 'block');
                $('.tooltip-title-' + id).html(`ID: ${link_or_post_id || ''}`);
            }

        });

        $(document).on('mouseleave', '.show-title', function() {
            $('.tooltip-title').html('');
            $('.tooltip-title').css('display', 'none');
        })
        // show
        $(document).on('click', '.show-history', async function() {
            let link_or_post_id = $(this).data('link_or_post_id');
            let id = $(this).data('id');
            let type = $(this).data('type');
            const allType = [
                'comment',
                'data',
                'emotion'
            ];
            var html = '';
            await $.ajax({
                type: "GET",
                url: `/api/linkHistories/getAll?link_or_post_id=${link_or_post_id}`,
                success: function(response) {
                    if (response.status == 0) {
                        response.histories.forEach(e => {
                            console.log(e);
                            switch (true) {
                                case type == "comment" && e.type == 0:
                                    html += `<tr>
                                                <td>${e.diff_comment}</td>
                                                <td>${e.created_at}</td>
                                            </tr>`;
                                    break;
                                case type == "data" && e.type == 1:
                                    html += `<tr>
                                                <td>${e.diff_data}</td>
                                                <td>${e.created_at}</td>
                                            </tr>`;
                                    break;
                                case type == "emotion" && e.type == 2:
                                    html += `<tr>
                                                <td>${e.diff_reaction}</td>
                                                <td>${e.created_at}</td>
                                            </tr>`;
                                    break;
                            }
                        });
                    } else {
                        toastr.error(response.message, "Thông báo");
                    }
                },
            });
            $(`.tooltiptext-${type}-${id}`).css('display', 'block');
            $(`.tooltiptext-${type}-${id}`).html(`
                    <table style="width: 100%">
                        <thead>
                            <th style="" class="">Chênh</th>
                            <th>Thời gian</th>
                        </thead>
                        <tbody class="table-content">
                            ${html}
                        </tbody>
                    </table>`);

        });

        $(document).on('mouseleave', '.show-history', function() {
            let type = $(this).data('type');
            $('.tooltiptext-' + type).html('');
            $('.tooltiptext-' + type).css('display', 'none');
        })

        function closeModalChangePassword() {
            $("#modalChangePassword").css("display", "none");
            $("body").removeClass("modal-open");
            $(".modal-backdrop").remove();
        }
        $(document).on('click', '.btn-change-password', function() {
            $.ajax({
                type: "POST",
                data: {
                    tel_or_email: $('#tel_or_email').val(),
                    password: $('#password').val(),
                    old_password: $('#old_password').val(),
                },
                url: "/api/user/change_password",
                success: function(response) {
                    if (response.status == 0) {
                        toastr.success(response.message, "Thông báo");
                        closeModalChangePassword();
                    } else {
                        toastr.error(response.message, "Thông báo");
                    }
                },
            });
        })
    </script>
    <script>
        function closeModal(id) {
            $("#" + id).css("display", "none");
            $("body").removeClass("modal-open");
            $(".modal-backdrop").remove();
        }
    </script>
    @stack('scripts')
</body>

</html>
