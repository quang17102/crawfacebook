<?php

namespace App\Http\Controllers\Admin;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
use Toastr;

class LinkFollowController extends Controller
{
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'user_id' => 'required|string',
                'title' => 'required|string',
                'time' => 'nullable|string',
                'content' => 'nullable|string',
                'comment' => 'nullable|string',
                'diff_comment' => 'nullable|string',
                'data' => 'nullable|string',
                'diff_data' => 'nullable|string',
                'reaction' => 'nullable|string',
                'diff_reaction' => 'nullable|string',
                'is_scan' => 'nullable|in:0,1,2',
                'note' => 'nullable|string',
                'image' => 'nullable|string',
                'link_or_post_id' => 'required|string',
            ]);
            $user = User::firstWhere('id', $data['user_id']);

            $userLinks = Link::with(['user'])
                ->where('user_id', $user->id)
                ->where('type', GlobalConstant::TYPE_FOLLOW)
                ->get();
            if ($userLinks->count() >= $user->limit_follow) {
                throw new Exception('Đã quá giới hạn link được thêm');
            }

            $userLink = Link::with(['user'])
                ->where('user_id', $user->id)
                ->where('link_or_post_id', $data['link_or_post_id'])
                ->first();

            if ($userLink) {
                throw new Exception('Đã tồn tại ID bài viết bên bảng '
                    . ($userLink->type == GlobalConstant::TYPE_SCAN ? 'link quét' : 'link theo dõi'));
            }

            $data['is_scan'] = GlobalConstant::IS_OFF;
            $data['type'] = GlobalConstant::TYPE_FOLLOW;
            $data['status'] = GlobalConstant::STATUS_RUNNING;
            $data['delay'] = $user->delay;

            // check link_or_post_id
            if (!is_numeric($data['link_or_post_id'])) {
                if (!(str_contains($data['link_or_post_id'], 'videos') || str_contains($data['link_or_post_id'], 'reel'))) {
                    throw new Exception('Link không đúng định dạng');
                }
                $link_or_post_id = explode('/', $data['link_or_post_id']);
                $data['link_or_post_id'] = $link_or_post_id[count($link_or_post_id) - 1];
            }

            DB::beginTransaction();
            $userLink =  Link::withTrashed()
                ->where('link_or_post_id', $data['link_or_post_id'])
                ->where('user_id', $data['user_id'])
                ->first();

            if ($userLink) {
                if ($userLink->trashed()) {
                    $userLink->restore();
                }
                $userLink->update([
                    'type' => $data['type'],
                    'title' => $data['title'],
                    'is_scan' => $data['is_scan'],
                    'created_at' => now(),
                    'is_on_at' => now(),
                    'comment' => 0,
                    'diff_comment' => 0,
                    'data' => 0,
                    'diff_data' => 0,
                    'reaction' => 0,
                    'diff_reaction' => 0,
                    'note' => '',
                    'delay' => $user->delay ?? 0,
                ]);
            } else {
                $newLink =  Link::where('link_or_post_id', $data['link_or_post_id'])
                    ->whereNull('user_id')
                    ->first();
                if ($newLink) {
                    $newLink->update([
                        'title' => $data['title'] ?? '',
                        'type' => $data['type'] ?? '',
                        'is_scan' => $data['is_scan'] ?? '',
                        'is_on_at' => now(),
                        'created_at' => now(),
                        'comment' => 0,
                        'diff_comment' => 0,
                        'data' => 0,
                        'diff_data' => 0,
                        'reaction' => 0,
                        'diff_reaction' => 0,
                        'note' => '',
                        'delay' => $user->delay ?? 0,
                        'user_id' => $data['user_id'],
                    ]);
                } else {
                    Link::create(
                        [
                            'link_or_post_id' => $data['link_or_post_id'],
                            'user_id' => $data['user_id'],
                            'is_scan' => $data['is_scan'],
                            'title' => $data['title'],
                            'type' => $data['type'],
                            'note' => $data['note'] ?? '',
                            'is_on_at' => now()->format('Y-m-d H:i:s'),
                            'created_at' => now(),
                            'updated_at' => now(),
                            'delay' => $user->delay ?? 0,
                        ]
                    );
                }
            }

            Toastr::success('Thêm thành công', 'Thông báo');
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Toastr::error($e->getMessage(), 'Thông báo');
        }

        return redirect()->back();
    }

    public function update(Request $request)
    {
        try {
            $data = $request->validate([
                'id' => 'required|integer',
                'title' => 'nullable|string',
                'content' => 'nullable|string',
                'comment' => 'nullable|string',
                'diff_comment' => 'nullable|string',
                'data' => 'nullable|string',
                'diff_data' => 'nullable|string',
                'reaction' => 'nullable|string',
                'diff_reaction' => 'nullable|string',
                'is_scan' => 'nullable|in:0,1',
                'note' => 'nullable|string',
                'image' => 'nullable|string',
                'link_or_post_id' => 'required|string',
                'user_id' => 'required|string',
            ]);
            unset($data['id']);
            DB::beginTransaction();
            Link::firstWhere('id', $request->input('id'))->update($data);
        } catch (Throwable $e) {
            DB::rollBack();
            Toastr::error($e->getMessage(), __('title.toastr.fail'));
        }
        DB::commit();
        Toastr::success(__('message.success.update'), __('title.toastr.success'));

        return redirect()->back();
    }

    public function index(Request $request)
    {
        return view('admin.linkfollow.list', [
            'title' => 'Danh sách link theo dõi',
            'users' => User::where('role', GlobalConstant::ROLE_USER)->get()
        ]);
    }

    public function show($id, Request $request)
    {
        return view('admin.linkfollow.edit', [
            'title' => 'Chi tiết link theo dõi',
            'link' => Link::with(['user'])->firstWhere('id', $id),
        ]);
    }

    public function destroy($id)
    {
        try {
            $link = Link::firstWhere('id', $id);
            $link->delete();

            return response()->json([
                'status' => 0,
            ]);
        } catch (Throwable $e) {

            return response()->json([
                'status' => 1,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getAll()
    {
        return response()->json([
            'status' => 0,
            'linkfollows' => Link::where('type', GlobalConstant::TYPE_FOLLOW)->get()
        ]);
    }
}
