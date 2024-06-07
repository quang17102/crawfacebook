@extends('admin.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.dataTables.css">
@endpush
@push('scripts')
    <script src="/js/admin/reaction/index.js?v=1"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
@endpush
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card direct-chat direct-chat-primary">
                <div class="card-header ui-sortable-handle header-color" style="cursor: move;">
                    <h3 class="card-title text-bold">Danh sách cảm xúc</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;padding: 10px !important;">
                    <div class="form-group col-lg-6">
                        <label class="filtering">Lọc theo: Không</label><br>
                        <label class="count-select">Đã chọn: 0</label><br>
                        <label class="count-reaction">Cảm xúc: 0</label>
                    </div>
                    <div class="form-group col-lg-6">
                        <button disabled class="btn-control btn btn-danger btn-delete-multiple">Xóa</button>
                        <button data-target="#modalFilter" data-toggle="modal"
                            class="btn btn-primary btn-choose-filter">Chọn</button>
                        <button class="btn btn-danger btn-auto-refresh">Auto Refresh: OFF</button>
                        <button data-target="#modalCopyUid" data-toggle="modal" class="btn btn-success">Copy
                            UID</button>
                    </div>
                    <table id="table" class="table display nowrap dataTable dtr-inline collapsed">
                        <thead>
                            <tr>
                                <th><input class="btn-select-all" type="checkbox" /></th>
                                <th>ID</th>
                                <th>Nội dung bài viết</th>
                                <th>UID</th>
                                <th>Thời gian</th>
                                <th>Tài khoản</th>
                                <th>Tên bài</th>
                                <th>Tên Facebook</th>
                                <th>Cảm xúc</th>
                                <th>Số điện thoại</th>
                                <th>Note</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalFilter" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Lọc</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="menu">UID</label>
                                <input type="text" data-name="UID" class="form-control" id="uid" value=""
                                    placeholder="UID">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="menu">Ngày tạo </label>
                                <div class="row">
                                     <div class="col-lg-6">
                                        <input type="date" class="form-control" data-name="Ngày tạo" id="from"
                                            value="{{ date('Y-m-d') }}" placeholder="Từ">
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="date" class="form-control" data-name="Ngày tạo" id="to"
                                            value="{{ date('Y-m-d') }}" placeholder="Đến">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="menu">SĐT</label>
                                <input type="text" data-name="SĐT" class="form-control" id="phone" value=""
                                    placeholder="SĐT">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="menu">Bình luận</label>
                                <input type="text" data-name="Bình luận" class="form-control" id="content"
                                    value="" placeholder="Bình luận">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="menu">Tên bài</label>
                                <input type="text" data-name="Tên bài" class="form-control" id="title"
                                    value="" placeholder="Tên bài">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="menu">ID</label>
                                <input type="text" data-name="ID" class="form-control" id="link_or_post_id"
                                    value="" placeholder="ID">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="menu">Tên Facebook</label>
                                <input type="text" data-name="Tên Facebook" class="form-control" id="name_facebook"
                                    value="" placeholder="Tên Facebook">
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="menu">Ghi chú</label>
                                <input type="text" data-name="Ghi chú" class="form-control" id="note"
                                    value="" placeholder="Ghi chú">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <div class="form-group">
                                <label for="menu">Tên tài khoản</label>
                                <select data-name="Tài khoản" class="form-control" id="user">
                                    <option value="">ALL</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-rounded btn-warning btn-filter">Chọn</button>
                    <button class="btn btn-rounded btn-success btn-refresh">Làm mới</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalCopyUid" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Copy UID</h4>
                    <button type="button" class="closeModalCopyUid close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12">
                            <div class="form-group">
                                <label for="menu">Số lượng</label>
                                <input type="number" class="form-control" id="number" placeholder="Số lượng"
                                    value="100" />
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="id-editting" />
                <div class="modal-footer justify-content-between">
                    <button class="btn btn-rounded btn-success btn-copy-uid">Xác nhận</button>
                    <button class="btn btn-default" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endsection
