@extends('admin.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
@endpush
@push('scripts')
    <script src="/js/admin/settingfilter/index.js?v=111112"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
@endpush
@section('content')
<div class="row">
        <div class="col-lg-12">
            <div class="card direct-chat direct-chat-primary">
                <div class="card-body" style="display: block;padding: 10px !important;">
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
