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
<form action="{{ route('admin.settingfilters.store') }}" method="POST">
        <div class="row">
            <div class="col-lg-12">
                <div class="card direct-chat direct-chat-primary">
                    <div class="card-header ui-sortable-handle header-color" style="cursor: move;">
                        <h3 class="card-title text-bold">Thêm setting</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body" style="display: block;padding: 10px !important;">
                        <div class="row">
                            <div class="form-group col-6">
                                <label for="menu">Data cuối từ<span class="required">(*)</span></label>
                                <input type="text" class="form-control" name="data_cuoi_from"
                                    value="" placeholder="">
                            </div>
                            <div class="form-group col-6">
                                <label for="menu">Data cuối đến<span class="required">(*)</span></label>
                                <input type="text" class="form-control" name="data_cuoi_to"
                                    value="" placeholder="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group">
                                    <label for="menu">Reaction từ<span class="required">(*)</span></label>
                                    <input type="text" class="form-control" name="reaction_from"
                                        value="" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="menu">Reaction đến<span class="required">(*)</span></label>
                                    <input type="text" class="form-control" name="reaction_to"
                                        value="" placeholder="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-sm-6">
                                <div class="form-group">
                                    <label for="menu">Delay<span class="required">(*)</span></label>
                                    <input type="text" class="form-control" name="delay"
                                        value="" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="menu">Status<span class="required">(*)</span></label>
                                    <input type="text" class="form-control" name="status"
                                        value="" placeholder="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </div>
            </div>
        </div>
        @csrf
    </form>
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
                                <th>Reaction đến</th>
                                <th>Delay</th>
                                <th>Trạng thái</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
