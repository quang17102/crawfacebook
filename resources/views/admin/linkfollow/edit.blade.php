@extends('admin.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.dataTables.css">
@endpush
@push('scripts')
    <script src="/js/admin/linkfollow/index.js?v=133"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
@endpush
@section('content')
    <form action="{{ route('admin.linkfollows.update', ['id' => $link->id]) }}" method="POST">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-sm-12">
                    <div class="form-group">
                        <label for="menu">Tên bài <span class="required">(*)</span></label>
                        <input type="text" class="form-control" name="title" value="{{ old('title') ?? $link?->title }}"
                            placeholder="Nhập tên bài">
                    </div>
                </div>
                <!-- <div class="col-lg-6 col-sm-12">
                    <div class="form-group">
                        <label for="menu">ID bài viết <span class="required">(*)</span></label>
                        <input type="text" class="form-control" name="link_or_post_id"
                            value="{{ old('link_or_post_id') ?? $link->link_or_post_id }}"
                            placeholder="Nhập ID bài viết">
                    </div>
                </div> -->
            </div>
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <div class="form-group">
                        <label for="menu">Note <span class="required">(*)</span></label>
                        <input type="text" class="form-control" name="note" value="{{ old('note') ?? $link?->note }}"
                            placeholder="Nhập ghi chú">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="{{ route('admin.linkfollows.index') }}" class="btn btn-success">Xem danh sách</a>
        </div>
        <input type="hidden" name="user_id" value="{{ request()->user_id }}">
        @csrf
    </form>
@endsection
