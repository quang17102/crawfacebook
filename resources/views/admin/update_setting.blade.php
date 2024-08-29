@extends('admin.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.dataTables.css">
@endpush
@push('scripts')
    <script src="/js/admin/account/index.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
@endpush
@section('content')
    <form action="{{ route('admin.settingfilters.update') }}" method="POST">
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
                                    value="{{$settingfilter["data_cuoi_from"]}}" placeholder="">
                            </div>
                            <div class="form-group col-2">
                                <label for="menu">Data cuối đến<span class=""></span></label>
                                <input type="text" class="form-control" name="data_cuoi_to"
                                    value="{{$settingfilter->data_cuoi_to}}" placeholder="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-2">
                                <label for="menu">Reaction chênh từ<span class=""></span></label>
                                <input type="text" class="form-control" name="reaction_chenh_from"
                                    value="{{$settingfilter->reaction_chenh_from}}" placeholder="">
                            </div>
                            <div class="form-group col-2">
                                <label for="menu">Reaction chêng đến<span class=""></span></label>
                                <input type="text" class="form-control" name="reaction_chenh_to"
                                    value="{{$settingfilter->reaction_chenh_to}}" placeholder="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-2">
                                <label for="menu">Data Reaction chênh từ<span class=""></span></label>
                                <input type="text" class="form-control" name="data_reaction_chenh_from"
                                    value="{{$settingfilter->data_reaction_chenh_from}}" placeholder="">
                            </div>
                            <div class="form-group col-2">
                                <label for="menu">Data Reaction chêng đến<span class=""></span></label>
                                <input type="text" class="form-control" name="data_reaction_chenh_to"
                                    value="{{$settingfilter->data_reaction_chenh_to}}" placeholder="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-2">
                                <label for="menu">Comment chênh từ<span class=""></span></label>
                                <input type="text" class="form-control" name="comment_chenh_from"
                                    value="{{$settingfilter->comment_chenh_from}}" placeholder="">
                            </div>
                            <div class="form-group col-2">
                                <label for="menu">Comment chênh đến<span class=""></span></label>
                                <input type="text" class="form-control" name="comment_chenh_to"
                                    value="{{$settingfilter->comment_chenh_to}}" placeholder="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-2">
                                <label for="menu">Data Comment chênh từ<span class=""></span></label>
                                <input type="text" class="form-control" name="data_comment_chenh_from"
                                    value="{{$settingfilter->data_comment_chenh_from}}" placeholder="">
                            </div>
                            <div class="form-group col-2">
                                <label for="menu">Data Comment chêng đến<span class=""></span></label>
                                <input type="text" class="form-control" name="data_comment_chenh_to"
                                    value="{{$settingfilter->data_comment_chenh_to}}" placeholder="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-2">
                                <label for="menu">View chênh từ<span class=""></span></label>
                                <input type="text" class="form-control" name="view_chenh_from"
                                    value="{{$settingfilter->view_chenh_from}}" placeholder="">
                            </div>
                            <div class="form-group col-2">
                                <label for="menu">View chêng đến<span class=""></span></label>
                                <input type="text" class="form-control" name="view_chenh_to"
                                    value="{{$settingfilter->view_chenh_to}}" placeholder="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group  col-2">
                                <label for="menu">Delay<span class=""></span></label>
                                <input type="text" class="form-control" name="delay"
                                    value="{{$settingfilter->delay}}" placeholder="">
                            </div>
                            <div class="form-group col-2">
                                    <label for="menu">Người dùng <span class="required">(*)</span></label>
                                    <select name="status" class="form-control">
                                        <option value="ON" {{ $$settingfilter->status == 'ON' ? 'selected' : '' }}>ON</option>
                                        <option value="OFF" {{ $$settingfilter->status == 'OFF' ? 'selected' : '' }}>OFF</option>
                                        <option value="FOLLOWTOSCAN" {{ $$settingfilter->status == 'FOLLOWTOSCAN' ? 'selected' : '' }}>FOLLOW -> SCAN</option>
                                        <option value="SCANTOFOLLOW" {{ $$settingfilter->status == 'SCANTOFOLLOW' ? 'selected' : '' }}>SCAN -> FOLLOW</option>
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
@endsection
