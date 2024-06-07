<?php

namespace App\Http\Controllers\Users;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Controller;
use App\Models\Link;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;
use Toastr;

class LinkScanController extends Controller
{
    public function create()
    {
        return view('user.linkscan.add', [
            'title' => 'Thêm link quét'
        ]);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'title' => 'nullable|string',
                'content' => 'nullable|string',
                'is_scan' => 'nullable|in:0,1',
                'note' => 'nullable|string',
                'image' => 'nullable|string',
                'link_or_post_id' => 'required|string'
            ]);

            $user = Auth::user();

            $userLinks = Link::with(['user'])
                ->where('user_id', $user->id)
                ->where('type', GlobalConstant::TYPE_SCAN)
                ->get();
            if ($userLinks->count() >= $user->limit) {
                throw new Exception('Đã quá giới hạn link được thêm');
            }

            $userLink = Link::with(['user'])
                ->where('user_id', $user->id)
                ->where('link_or_post_id', $data['link_or_post_id'])
                ->first();

            if ($userLink) {
                if ($userLink->type == GlobalConstant::TYPE_SCAN) {
                    throw new Exception('Đã tồn tại ID bài viết bên bảng '
                        . ($userLink->type == GlobalConstant::TYPE_SCAN ? 'link quét' : 'link theo dõi'));
                }
            }

            $data['is_scan'] = GlobalConstant::IS_ON;
            $data['status'] = GlobalConstant::STATUS_RUNNING;
            $data['type'] = GlobalConstant::TYPE_SCAN;
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

            $data['user_id'] = $user->id;
            $userLink =  Link::withTrashed()
                ->where('link_or_post_id', $data['link_or_post_id'])
                ->where('user_id', $user->id)
                ->first();

            if ($userLink) {
                if ($userLink->trashed()) {
                    $userLink->restore();
                }
                $userLink->update([
                    'title' => $data['title'],
                    'type' => $data['type'],
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
                        'user_id' => $user->id,
                    ]);
                } else {
                    Link::create(
                        [
                            'user_id' => $user->id,
                            'link_or_post_id' => $data['link_or_post_id'],
                            'is_scan' => $data['is_scan'],
                            'title' => $data['title'] ?? '',
                            'note' => $data['note'] ?? '',
                            'type' => $data['type'],
                            'is_on_at' => now(),
                            'created_at' => now(),
                            'updated_at' => now(),
                            'delay' => $user->delay ?? 0,
                        ]
                    );
                }
            }
            Toastr::success('Tạo link quét thành công', __('title.toastr.success'));
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Toastr::error($e->getMessage(), __('title.toastr.fail'));
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
            ]);
            unset($data['id']);
            DB::beginTransaction();
            $link = Link::firstWhere('id', $request->input('id'));
            if ($link) {
                $link->update($data);
            }
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
        return view('user.linkscan.list', [
            'title' => 'Danh sách link quét',
        ]);
    }

    public function show($id)
    {
        return view('user.linkscan.edit', [
            'title' => 'Chi tiết link quét',
            'link' => Link::firstWhere('id', $id)
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
            'linkscans' => Link::where('type', GlobalConstant::TYPE_SCAN)->get()
        ]);
    }

    public function follow(Request $request)
    {
        try {
            $data = $request->validate([
                'id' => 'required|integer',
            ]);
            Link::where('id', $data['id'])->update([
                'type' =>  GlobalConstant::TYPE_FOLLOW
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 1,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 0,
        ]);
    }
}
