@extends('admin.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
@endpush
@push('scripts')
    <script src="/js/admin/settingfilter/index.js?v=11112"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
    <script>
        $("#upload").change(function() {
            const form = new FormData();
            form.append("file", $(this)[0].files[0]);
            console.log(form);
            $.ajax({
                processData: false,
                contentType: false,
                type: "POST",
                data: form,
                url: "/api/uploadUid",
                success: function(response) {
                    if (response.status == 0) {
                        toastr.success('Upload uid thành công', 'Thông báo');
                    } else {
                        toastr.error(response.message, 'Thông báo');
                    }
                },
            });
        });
    </script>
@endpush
@section('content')
<div class="row">
        <div class="col-lg-12">
            <div class="card direct-chat direct-chat-primary">
                <div class="card-header ui-sortable-handle header-color" style="cursor: move;">
                    <h3 class="card-title text-bold">Danh sách bình luậnn</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;padding: 10px !important;">
                    <div class="form-group col-lg-6">
                        <label class="filtering">Lọc theo: Không</label><br>
                        <label class="count-comment">Bình luận: 0</label>
                    </div>
                    <div class="form-group col-lg-6">
                        <button disabled class="btn-control btn btn-danger btn-delete-multiple">Xóa</button>
                        <button data-target="#modalFilter" data-toggle="modal"
                            class="btn btn-primary btn-choose-filter">Chọn</button>
                        <button class="btn btn-danger btn-auto-refresh">Auto Refresh: OFF</button>
                        <button data-target="#modalCopyUid" data-toggle="modal" class="btn btn-success">Copy
                            UID</button>
                        <p> <input class= "showPhone" type="checkbox" id="showPhone" name="anSdt" value="anSdt"> Hiện SDT</p>  
                    </div>
                    <nav id="navigation" aria-label="Page navigation example">
                        <ul id="pagination" class="pagination">
                            <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                            <!-- Pagination links will be dynamically added here -->
                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </nav>
                    <table id="table" class="table display nowrap dataTable dtr-inline collapsed">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Data cuối từ</th>
                                <th>Data cuối đến</th>
                                <th>Reaction từ</th>
                                <th>Tên bài</th>
                                <th>Link bài</th>
                                <th>Nội dung</th>
                                <th>Tên Facebook</th>
                                <th>Link facebook</th>
                                <th>Số điện thoại</th>
                                <th>Reaction đến</th>
                                <th>Delay</th>
                                <th>Trạng thái</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <nav id="navigation" aria-label="Page navigation example">
                        <ul id="pagination" class="pagination">
                            <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                            <!-- Pagination links will be dynamically added here -->
                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection
