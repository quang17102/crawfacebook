@extends('admin.main')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
@endpush
@push('scripts')
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
    <script>
        let user_id = $('#user_id').val();

        $.ajax({
            type: "GET",
            url: `/api/comments/getAll?user_id=${$('#user_id').val()}`,
            success: function(response) {
                if (response.status == 0) {
                    $('.countComment').text(response.comments.length);
                }
            }
        });

        $.ajax({
            type: "GET",
            url: `/api/reactions/getAll?user_id=${$('#user_id').val()}`,
            success: function(response) {
                if (response.status == 0) {
                    $('.countReaction').text(response.reactions.length);
                }
            }
        });

        $.ajax({
            type: "GET",
            url: `/api/userlinks/getAll?user_id=${user_id}`,
            success: function(response) {
                if (response.status == 0) {
                    let countScan = 0;
                    let countFollow = 0;
                    response.links.forEach((e) => {
                        if (e.type == 0) {
                            countScan++;
                        }
                        if (e.type == 1) {
                            countFollow++;
                        }
                    });
                    $('.countScan').text(countScan);
                    $('.countFollow').text(countFollow);
                }
            }
        });
    </script>
@endpush
@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3 class="countScan">0</h3>
                    <p>Link quét</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="{{ route('user.linkscans.index') }}" class="small-box-footer">Xem thêm <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3 class="countFollow">0</h3>
                    <p>Link theo dõi</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="{{ in_array(App\Constant\GlobalConstant::ROLE_FOLLOW, $userRoles) ? route('user.linkfollows.index') : '#' }} "
                    class="small-box-footer">Xem thêm <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3 class="countComment">0</h3>
                    <p>Bình luận</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="{{ route('user.comments.index') }}" class="small-box-footer">Xem thêm <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3 class="countReaction">0</h3>
                    <p>Cảm xúc</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="{{ in_array(App\Constant\GlobalConstant::ROLE_FOLLOW, $userRoles) ? route('user.reactions.index') : '#' }}"
                    class="small-box-footer">Xem thêm <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card direct-chat direct-chat-primary">
                <div class="card-header ui-sortable-handle header-color" style="cursor: move;">
                    <h3 class="card-title text-bold">Thông báo</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display: block;padding: 10px !important;">
                    {{ \App\Models\Setting::firstWhere('key', 'notification-user-home')?->value ?? '' }}
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" value="{{ Auth::id() }}" id="user_id" />
@endsection
