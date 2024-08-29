@extends('admin.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
@endpush
@push('scripts')
    <script src="/js/admin/settingfilter/index.js?v=11111111211"></script>
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
                            <div class="form-group col-2">
                                <label for="menu">Data cuối từ<span class=""></span></label>
                                <input type="text" class="form-control" name="data_cuoi_from"
                                    value="" placeholder="">
                            </div>
                            <div class="form-group col-2">
                                <label for="menu">Data cuối đến<span class=""></span></label>
                                <input type="text" class="form-control" name="data_cuoi_to"
                                    value="" placeholder="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-2">
                                <label for="menu">Reaction chênh từ<span class=""></span></label>
                                <input type="text" class="form-control" name="reaction_chenh_from"
                                    value="" placeholder="">
                            </div>
                            <div class="form-group col-2">
                                <label for="menu">Reaction chêng đến<span class=""></span></label>
                                <input type="text" class="form-control" name="reaction_chenh_to"
                                    value="" placeholder="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-2">
                                <label for="menu">Data Reaction chênh từ<span class=""></span></label>
                                <input type="text" class="form-control" name="data_reaction_chenh_from"
                                    value="" placeholder="">
                            </div>
                            <div class="form-group col-2">
                                <label for="menu">Data Reaction chêng đến<span class=""></span></label>
                                <input type="text" class="form-control" name="data_reaction_chenh_to"
                                    value="" placeholder="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-2">
                                <label for="menu">Comment chênh từ<span class=""></span></label>
                                <input type="text" class="form-control" name="comment_chenh_from"
                                    value="" placeholder="">
                            </div>
                            <div class="form-group col-2">
                                <label for="menu">Comment chênh đến<span class=""></span></label>
                                <input type="text" class="form-control" name="comment_chenh_to"
                                    value="" placeholder="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-2">
                                <label for="menu">Data Comment chênh từ<span class=""></span></label>
                                <input type="text" class="form-control" name="data_comment_chenh_from"
                                    value="" placeholder="">
                            </div>
                            <div class="form-group col-2">
                                <label for="menu">Data Comment chêng đến<span class=""></span></label>
                                <input type="text" class="form-control" name="data_comment_chenh_to"
                                    value="" placeholder="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-2">
                                <label for="menu">View chênh từ<span class=""></span></label>
                                <input type="text" class="form-control" name="view_chenh_from"
                                    value="" placeholder="">
                            </div>
                            <div class="form-group col-2">
                                <label for="menu">View chêng đến<span class=""></span></label>
                                <input type="text" class="form-control" name="view_chenh_to"
                                    value="" placeholder="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group  col-2">
                                <label for="menu">Delay<span class=""></span></label>
                                <input type="text" class="form-control" name="delay"
                                    value="" placeholder="">
                            </div>
                            <div class="form-group col-2">
                                    <label for="menu">Người dùng <span class="required">(*)</span></label>
                                    <select name="status" class="form-control">
                                        <option value="ON">ON</option>
                                        <option value="OFF">OFF</option>
                                        <option value="FOLLOWTOSCAN">FOLLOW -> SCAN</option>
                                        <option value="SCANTOFOLLOW">SCAN -> FOLLOW</option>
                                    </select>
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
                                <th>Reaction Chênh từ</th>
                                <th>Reaction Chênh đến</th>
                                <th>Data Reaction Chênh từ</th>
                                <th>Data Reaction Chênh đến</th>
                                <th>Comment Chênh từ</th>
                                <th>Comment Chênh đến</th>
                                <th>Data Comment Chênh từ</th>
                                <th>Data Comment Chênh đến</th>
                                <th>View Chênh từ</th>
                                <th>View Chênh đến</th>
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
