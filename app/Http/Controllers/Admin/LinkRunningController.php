<?php

namespace App\Http\Controllers\Admin;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
use Toastr;

class LinkRunningController extends Controller
{
    public function create()
    {
        return view('admin.linkrunning.add', [
            'title' => 'Thêm link đang chạy'
        ]);
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
                'delay' => 'nullable|string',
                'image' => 'nullable|string',
                'link_or_post_id' => 'required|string',
            ]);
            unset($data['id']);
            DB::beginTransaction();
            $link = Link::with(['childLinks.userLinks'])->firstWhere('id', $request->input('id'));
            if ($link) {
                $link->update($data);
                $childLinks = $link?->childLinks ?? [];
                unset($data['link_or_post_id']);
                foreach ($childLinks as $childLink) {
                    $childLink->update($data);
                }
            }

            DB::commit();
            Toastr::success(__('message.success.update'), __('title.toastr.success'));
        } catch (Throwable $e) {
            DB::rollBack();
            Toastr::error($e->getMessage(), __('title.toastr.fail'));
        }

        return redirect()->back();
    }

    public function update_delay(Request $request)
    {
        try {
            //unset($data['id']);
            DB::beginTransaction();
            Link::where('parent_link_or_post_id', $request['id'] )-> orwhere('link_or_post_id', $request['id'])
            ->update(['delay' => $request['delay']]);
            DB::commit();
            Toastr::success(__('message.success.update'), __('title.toastr.success'));
        } catch (Throwable $e) {
            DB::rollBack();
            Toastr::error($e->getMessage(), __('title.toastr.fail'));
        }

        return redirect()->back();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json([
                'status' => 0,
                'linkrunnings' => Link::where('type', GlobalConstant::TYPE_RUNNING)->get()
            ]);
        }

        return view('admin.linkrunning.list', [
            'title' => 'Danh sách link đang chạy',
            'users' => User::where('role', GlobalConstant::ROLE_USER)->get(),
        ]);
    }

    public function show($id)
    {
        return view('admin.linkrunning.edit', [
            'title' => 'Chi tiết link đang chạy',
            'link' => Link::where('link_or_post_id', $id)->orwhere('parent_link_or_post_id', $id)->first()
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
            'linkrunnings' => Link::where('type', GlobalConstant::TYPE_RUNNING)->get()
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
