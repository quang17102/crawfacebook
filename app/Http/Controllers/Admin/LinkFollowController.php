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
                'title' => 'nullable|string',
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
                'link_or_post_id' => 'nullable|string',
                'title_second' => 'nullable|string',
                'link_or_post_id_second' => 'nullable|string',
            ]);
            $linksss = '';
            $status = '';
            $count = 0;

            if(strlen($data['link_or_post_id_second'])){
                //convert to title
                $data['title'] = $data['title_second']."|".$data['link_or_post_id_second'];
            }

            $pieces = explode("\r\n", $data['title']);
            for($i =0; $i< count($pieces); $i++)
            {
                try{
                    $needAddLink = true;
                    $data_link = explode("|", $pieces[$i]);
                    if(count($data_link) > 1){
                        $link_id = $data_link[1];
                        $title_id =  $data_link[0];
                    }else{
                        $data_link = explode("\t", $pieces[$i]);
                        if(count($data_link) > 1){
                            $link_id = $data_link[1];
                            $title_id =  $data_link[0];
                        }
                    }
                    $user = User::firstWhere('id', $data['user_id']);
    
                    $userLinks = Link::with(['user'])
                        ->where('user_id', $user->id)
                        ->where('type', GlobalConstant::TYPE_FOLLOW)
                        ->get();
    
                    if ($userLinks->count() >= $user->limit_follow) {
                        throw new Exception('Đã quá giới hạn link được thêm');
                    }
    
                    // check exist link
                    $userLink = Link::with(['user'])
                        ->where('user_id', $user->id)
                        ->where('link_or_post_id', $link_id)
                        ->first();
    
                    if ($userLink) {
                        if ($userLink->type == GlobalConstant::TYPE_FOLLOW) {
                            throw new Exception('Đã tồn tại ID bài viết bên bảng '
                                . ($userLink->type == GlobalConstant::TYPE_SCAN ? 'link quét' : 'link theo dõi'));
                        }
                    }
    
                    $data['is_scan'] = GlobalConstant::IS_OFF;
                    $data['type'] = GlobalConstant::TYPE_FOLLOW;
                    $data['status'] = GlobalConstant::IS_OFF;
                    $data['delay'] = $user->delay;
                    
    
                    // check link_or_post_id
                    // if (!is_numeric($link_id)){
                    //     if(str_contains($link_id, 'watch?v=')){
                    //         $result_video = explode("watch?v=", $link_id);
                    //         $result_video_1 = explode("/", $result_video[1]);
                    //         $link_id = str_replace('/', '', $result_video_1[0]);
                    //     }else
                    //     {
                    //         if(str_contains($link_id, 'videos/')){
                    //             $result_video = explode("videos/", $link_id);
                    //             $result_video_1 = explode("/", $result_video[1]);
                    //             $link_id = str_replace('/', '', $result_video_1[0]);
                    //         }else
                    //         {
                    //             if(str_contains($link_id, 'reel/')){
                    //                 $result_video = explode("reel/", $link_id);
                    //                 $result_video_1 = explode("/", $result_video[1]);
                    //                 $link_id = str_replace('/', '', $result_video_1[0]);
                    //             }else
                    //             {
                    //                 if(str_contains($link_id, 'posts/')){
                    //                     $result_video = explode("posts/", $link_id);
                    //                     $result_video_1 = explode("/", $result_video[1]);
                    //                     $link_id = str_replace('/', '', $result_video_1[0]);
                    //                 }else
                    //                 {
                    //                     if(str_contains($link_id, 'story_fbid=')){
                    //                         $result_video = explode("story_fbid=", $link_id);
                    //                         $result_story = explode("&", $result_video[1]);
                    //                         $link_id = str_replace('/', '', $result_story[0]);
                    //                     }else
                    //                     {
                    //                         $status = $status.'Lỗi link '.$link_id.'|';
                    //                         $needAddLink = false;
                    //                     }
                    //                 }
                    //             }
                    //         }
                    //     }
                    // }
                    if($needAddLink)
                    {
                        $link_id = str_replace(' ', '', $link_id);
                        $data['parent_link_or_post_id'] = $link_id;
                        // Kiểm tra xem đã tồn tại ở parent id nòa chưa
                        $countLink = Link::where('parent_link_or_post_id', $link_id)->count();
                        if($countLink > 0){

                            $data['parent_link_or_post_id'] = $link_id;
                        }

                        DB::beginTransaction();
                        $userLink =  Link::withTrashed()
                            ->where('link_or_post_id', $link_id)
                            ->where('user_id', $data['user_id'])
                            ->first();

                        if ($userLink) {
                            if ($userLink->trashed()) {
                                $userLink->restore();
                            }
                            // Update khi tồn tại link
                            $userLink->update([
                                'title' => $title_id ?? '',
                                'type' => $data['type'] ?? '',
                                'is_scan' => $data['is_scan'] ?? '',
                                'link_or_post_id' => $link_id,
                                'is_on_at' => date('Y-m-d H:i:s'),
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                                'comment' => 0,
                                'diff_comment' => 0,
                                'data' => 0,
                                'diff_data' => 0,
                                'reaction' => 0,
                                'diff_reaction' => 0,
                                'note' => '',
                                'delay' => $user->delay ?? 1000,
                                'parent_link_or_post_id' => $data['parent_link_or_post_id'],
                                'user_id' => $data['user_id'],
                            ]);
                            $status = 'Link có sắn';
                        } else {
                            // Tạo mới link
                            Link::create([
                                'link_or_post_id' => $link_id,
                                'title' => $title_id ?? '',
                                'type' => $data['type'] ?? '',
                                'is_scan' => $data['is_scan'] ?? '',
                                'is_on_at' => date('Y-m-d H:i:s'),
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                                'comment' => 0,
                                'diff_comment' => 0,
                                'data' => 0,
                                'diff_data' => 0,
                                'reaction' => 0,
                                'diff_reaction' => 0,
                                'note' => '',
                                'delay' => $user->delay ?? 1000,
                                'user_id' => $data['user_id'],
                                'parent_link_or_post_id' => $data['parent_link_or_post_id']
                            ]);
                            $status = 'Link mới';
                        }
                        $count++;
                        $linksss = $linksss.'|'.$link_id;
                        DB::commit();
                    }
                }catch(Exception $ex){
                    $status = $ex->getMessage();
                }
            }
            Toastr::success('Thêm thành công'. $count.'/'.count($pieces).'|'.$status, 'Thông báo');
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
