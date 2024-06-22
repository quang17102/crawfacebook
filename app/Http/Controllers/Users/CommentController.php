<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Throwable;
use Toastr;


class CommentController extends Controller
{
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'comments' => 'nullable|array',
                'comments.*.account' => 'nullable|string',
                'comments.*.title' => 'nullable|string',
                'comments.*.uid' => 'nullable|string',
                'comments.*.phone' => 'nullable|string',
                'comments.*.content' => 'nullable|string',
                'comments.*.name_facebook' => 'nullable|string',
                'comments.*.note' => 'nullable|string',
                'comments.*.comment_id' => 'nullable|string',
            ]);
            $data = array_map(function ($item) {
                return [
                    ...$item,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }, $data['comments']);
            Comment::insert($data);

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

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer',
            'title' => 'nullable|string',
            'content' => 'nullable|string',
            'comment' => 'nullable|string',
            'data' => 'nullable|numeric',
            'emotion' => 'nullable|numeric',
            'note' => 'nullable|string',
            'name_facebook' => 'nullable|string',
            'comment_id' => 'nullable|string',
            'link_or_post_id' => 'required|string'
        ]);
        unset($data['id']);
        $update = Comment::where('id', $request->input('id'))->update($data);

        if ($update) {
            Toastr::success(__('message.success.update'), __('title.toastr.success'));
        } else Toastr::error(__('message.fail.update'), __('title.toastr.fail'));

        return redirect()->back();
    }

    public function index(Request $request)
    {
        return view('user.comment.list', [
            'title' => 'Danh sách bình luận',
        ]);
    }

    public function show($id)
    {
        return view('user.comment.edit', [
            'title' => 'Chi tiết bình luận',
            'comment' => Comment::firstWhere('id', $id)
        ]);
    }

    public function destroy($id)
    {
        try {
            $link = Comment::firstWhere('id', $id);
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
}
