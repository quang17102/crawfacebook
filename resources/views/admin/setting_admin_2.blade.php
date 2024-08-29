@extends('admin.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
@endpush
@push('scripts')
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
                    <h3 class="card-title text-bold">Cài đặt</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    <div class="card-body" style="display: block;padding: 10px !important;">
                        <div class="row">
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="menu">Data cuối từ</label>
                                    <input type="text" class="form-control" name="data_cuoi_filter"
                                        value="" placeholder="">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="menu">đến</label>
                                    <input type="text" class="form-control" name="data_cuoi_filter"
                                        value="" placeholder="">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="menu">Cảm xúc từ</label>
                                    <input type="text" class="form-control" name="cam_xuc_filter"
                                        value="" placeholder="">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="menu">đến</label>
                                    <input type="text" class="form-control" name="cam_xuc_filter"
                                        value="" placeholder="">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="menu">Bình luận từ</label>
                                    <input type="text" class="form-control" name="binh_luan_filter"
                                        value="" placeholder="">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="menu">đến</label>
                                    <input type="text" class="form-control" name="binh_luan_filter"
                                        value="" placeholder="">
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="menu">Delay</label>
                                    <input type="text" class="form-control" name="delay_filter"
                                        value="" placeholder="">
                                </div>
                            </div>
                            <div class="col-2">
                            <div class="form-group">
                                <label for="menu">Trạng thái</label>
                                <select data-name="Tài khoản" class="form-control" id="status_filter">
                                    <option value="1">On</option>
                                    <option value="0">OFF</option>
                                </select>
                            </div>
                        </div>
                        </div>
                        <button class="btn btn-success">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- <div class="row">
        <div class="col-lg-12">
            <div class="card direct-chat direct-chat-primary">
                <div class="card-header ui-sortable-handle header-color" style="cursor: move;">
                    <h3 class="card-title text-bold">Upload Uid</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;padding: 10px !important;">
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="form-group">
                                <label for="file">Chọn file</label><br>
                                <div class="">
                                    <input type="file" id="upload" accept=".json" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
