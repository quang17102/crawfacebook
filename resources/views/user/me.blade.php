@extends('admin.main')
@push('styles')
@endpush
@push('scripts')
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
                url: "/api/upload",
                success: function(response) {
                    if (response.status == 0) {
                        //hiển thị ảnh
                        $("#image_show").attr('src', response.url);
                        $("#avatar").val(response.url);
                    } else {
                        toastr.error(response.message, 'Thông báo');
                    }
                },
            });
        });
    </script>
@endpush
@section('content')
    <form action="{{ route('user.update', ['id' => $staff->id]) }}" method="POST">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Họ tên</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') ?? $staff->name }}"
                            placeholder="Nhập họ tên">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Chức vụ</label>
                        <input type="text" class="form-control" name="position"
                            value="{{ old('position') ?? $staff->position }}" placeholder="Nhập chức vụ">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Căn cước công dân</label>
                        <input type="text" class="form-control" name="identification"
                            value="{{ old('identification') ?? $staff->identification }}"
                            placeholder="Nhập căn cước công dân">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Điện thoại</label>
                        <input type="text" class="form-control" name="tel" value="{{ old('tel') ?? $staff->tel }}"
                            placeholder="Nhập điện thoại">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="file">Chọn ảnh</label><br>
                        <div class="">
                            <img id="image_show" style="width: 100px;height:100px" src="{{ $staff->avatar }}" alt="Avatar" />
                            <input type="file" id="upload" accept=".png,.jpeg"/>
                        </div>
                        <input type="hidden" name="avatar" id="avatar" value="{{ $staff->avatar }}">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="form-group">
                        <label for="menu">Kích hoạt</label>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="active" value="1"
                                {{ $staff->active == 1 ? 'checked' : '' }} name="active">
                            <label for="active" class="custom-control-label">Có</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="unactive" value="0"
                                {{ $staff->active == 0 ? 'checked' : '' }} name="active">
                            <label for="unactive" class="custom-control-label">Không</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Lưu</button>
        </div>
        @csrf
    </form>
@endsection
