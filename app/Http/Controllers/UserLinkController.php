<?php

namespace App\Http\Controllers;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\LinkHistory;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
use Toastr;
use Carbon\Carbon;

class UserLinkController extends Controller
{
    public function getAll(Request $request)
    {
        $comment_from = $request->comment_from;
        $comment_to = $request->comment_to;
        $delay_from = $request->delay_from;
        $delay_to = $request->delay_to;
        $data_from = $request->data_from;
        $data_to = $request->data_to;
        $reaction_from = $request->reaction_from;
        $reaction_to = $request->reaction_to;
        $time_from = $request->time_from;
        $time_to = $request->time_to;
        $last_data_from = $request->last_data_from;
        $last_data_to = $request->last_data_to;
        $from = $request->from;
        $to = $request->to;
        $user_id = $request->user_id;
        $user = $request->user;
        $note = $request->note;
        $link_id = $request->link_id;
        $is_scan = $request->is_scan;
        $type = (string)$request->type;
        $title = $request->title;
        $content = $request->content;
        $status = $request->status;
        $link_or_post_id = is_numeric($request->link_or_post_id) ? $request->link_or_post_id : $this->getLinkOrPostIdFromUrl($request->link_or_post_id ?? '');

        $query = '(HOUR(CURRENT_TIMESTAMP()) * 60 + MINUTE(CURRENT_TIMESTAMP()) - HOUR(updated_at) * 60 - MINUTE(updated_at))/60 + DATEDIFF(CURRENT_TIMESTAMP(), updated_at) * 24';
        $queryLastData = '(HOUR(CURRENT_TIMESTAMP()) * 60 + MINUTE(CURRENT_TIMESTAMP()) - HOUR(created_at) * 60 - MINUTE(created_at))/60 + DATEDIFF(CURRENT_TIMESTAMP(), created_at) * 24';

        // Initialize variables for start and end datetime strings
        $startDateTimeStr = '';
        $endDateTimeStr = '';

        // Construct the start datetime string if $inputFromHour is not null
        if (strlen($last_data_from)) {

            $startDateTimeStr = Carbon::now()->subHours($last_data_from)->format('Y-m-d H:i:s');
        }

        // Construct the end datetime string if $inputToHour is not null
        if (strlen($last_data_to)) {
            $endDateTimeStr = Carbon::now()->subHours($last_data_to)->format('Y-m-d H:i:s');
        }

        // DB::enableQueryLog();
        $user_role = UserRole::where('user_id', $user_id)->get();
        $userLinks = Link::with(['comments', 'user'])
            // default
            ->whereNotNull('user_id')
            ->where('user_id', '!=', '')
            // title
            ->when(strlen($title), function ($q) use ($title) {
                return $q->where('title', 'like', "%$title%");
            })
            // link_or_post_id
            ->when(strlen($link_or_post_id), function ($q) use ($link_or_post_id) {
                return $q->where('link_or_post_id', 'like', "%$link_or_post_id%");
            })
            // content
            ->when(strlen($content), function ($q) use ($content) {
                return $q->where('content', 'like', "%$content%");
            })
            ->when(strlen($user_id), function ($q) use ($user_id) {
                return $q->where('user_id', $user_id);
            })
            ->when(strlen($link_id), function ($q) use ($link_id) {
                return $q->where('id', $link_id);
            })
            // delay
            ->when(strlen($delay_from), function ($q) use ($delay_from, $delay_to) {
                return $q->when(strlen($delay_to), function ($q) use ($delay_from, $delay_to) {
                    return $q->whereRaw('delay >= ?', $delay_from)
                        ->whereRaw('delay <= ?', $delay_to);
                }, function ($q) use ($delay_from) {
                    return $q->whereRaw('delay >= ?', $delay_from);
                });
            }, function ($q) use ($delay_to) {
                return $q->when(strlen($delay_to), function ($q) use ($delay_to) {
                    return $q->whereRaw('delay <= ?', $delay_to);
                });
            })
            // data
            // ->when(strlen($data_from), function ($q) use ($data_from, $data_to) {
            //     return $q->when(strlen($data_to), function ($q) use ($data_from, $data_to) {
            //         return $q->whereRaw('diff_data >= ?', $data_from)
            //             ->whereRaw('diff_data <= ?', $data_to);
            //     }, function ($q) use ($data_from) {
            //         return $q->whereRaw('diff_data >= ?', $data_from);
            //     });
            // }, function ($q) use ($data_to) {
            //     return $q->when(strlen($data_to), function ($q) use ($data_to) {
            //         return $q->whereRaw('diff_data <= ?', $data_to);
            //     });
            // })
            //Reaction
            ->when(strlen($reaction_from), function ($q) use ($reaction_from) {
                //return $q->where('comment', '>=', $comment_from);
                return $q->whereRaw('CAST(diff_reaction AS UNSIGNED) >= ?', [$reaction_from]); 
            })
            ->when(strlen($reaction_to), function ($q) use ($reaction_to) {
                return $q->whereRaw('CAST(diff_reaction AS UNSIGNED) <= ?', [$reaction_to]);
            })
            //Comment
            ->when(strlen($comment_from), function ($q) use ($comment_from) {
                //return $q->where('comment', '>=', $comment_from);
                return $q->whereRaw('CAST(diff_comment AS UNSIGNED) >= ?', [$comment_from]);
            })
            ->when(strlen($comment_to), function ($q) use ($comment_to) {
                return $q->whereRaw('CAST(diff_comment AS UNSIGNED) <= ?', [$comment_to]);
            })
            //Data cuoi
            ->when(strlen($startDateTimeStr) || strlen($endDateTimeStr), function ($q) use ($startDateTimeStr, $endDateTimeStr) {
                return $q->where(function ($query) use ($startDateTimeStr, $endDateTimeStr) {
                    if (strlen($startDateTimeStr) && strlen($endDateTimeStr)) {
                        $query->where('datacuoi', '<=', $startDateTimeStr)->where('datacuoi', '>=',$endDateTimeStr);
                    }else{
                        if (strlen($startDateTimeStr)) {
                            $query->where('datacuoi', '>=', $startDateTimeStr);
                        }
                        if (strlen($endDateTimeStr)) {
                            $query->orWhere('datacuoi', '>=', $endDateTimeStr);
                        }
                    }
                });
            })
            // // data update count
            // ->when(strlen($time_from), function ($q) use ($time_from, $time_to, $query) {
            //     return $q->when(strlen($time_to), function ($q) use ($time_from, $time_to, $query) {
            //         return $q->whereRaw("$query >= ?", $time_from)
            //             ->whereRaw("$query <= ?", $time_to);
            //     }, function ($q) use ($time_from, $query) {
            //         return $q->whereRaw("$query >= ?", $time_from);
            //     });
            // }, function ($q) use ($time_to, $query) {
            //     return $q->when(strlen($time_to), function ($q) use ($time_to, $query) {
            //         return $q->whereRaw("$query <= ?", $time_to);
            //     });
            // })
            // date
            ->when(strlen($from), function ($q) use ($from, $to) {
                return $q->when($to, function ($q) use ($from, $to) {
                    return $q->whereRaw('created_at >= ?', $from)
                        ->whereRaw('created_at <= ?', $to . ' 23:59:59');
                }, function ($q) use ($from) {
                    return $q->whereRaw('created_at >= ?', $from);
                });
            }, function ($q) use ($to) {
                return $q->when($to, function ($q) use ($to) {
                    return $q->whereRaw('created_at <= ?', $to . ' 23:59:59');
                });
            })
            // is_scan
            ->when(is_numeric($is_scan) || is_array($is_scan), function ($q) use ($is_scan) {
                switch (true) {
                    case is_array($is_scan):
                        $q = $q->whereIn('is_scan', $is_scan);
                        break;
                    default:
                        $q = $q->where('is_scan', $is_scan);
                        break;
                }
                return $q;
            })
            // user
            ->when(strlen($user), function ($q) use ($user) {
                $q->where('user_id', $user);
            })
            // note
            ->when(strlen($note), function ($q) use ($note) {
                return $q->where('note', 'like', "%$note%");
            })
            // type
            ->when(in_array($type, GlobalConstant::LINK_TYPE), function ($q) use ($type) {
                return $q->where('type', $type);
            })
            // status
            ->when(strlen($status), function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->orderByDesc('created_at')
            ->get()?->toArray() ?? [];

        $result = array_map(function ($item) use ($user_role) {
            $item['roles'] = $user_role;
            return $item;
        }, $userLinks);
        return response()->json([
            'status' => 0,
            'links' => $result,
            'user' => User::firstWhere('id', $user_id),
            'total_link' => count($result),
            'test' => 0
        ]);
    }

    public function getAllLinkScan(Request $request)
    {
        // $comment_from = $request->comment_from;
        // $comment_to = $request->comment_to;
        // $delay_from = $request->delay_from;
        // $delay_to = $request->delay_to;
        // $data_from = $request->data_from;
        // $data_to = $request->data_to;
        // $reaction_from = $request->reaction_from;
        // $reaction_to = $request->reaction_to;
        // $time_from = $request->time_from;
        // $time_to = $request->time_to;
        // $last_data_from = $request->last_data_from;
        // $last_data_to = $request->last_data_to;
        // $from = $request->from;
        // $to = $request->to;
           $user_id = $request->user_id;
        // $user = $request->user;
        // $note = $request->note;
        // $link_id = $request->link_id;
        // $is_scan = $request->is_scan;
           $type = (string)$request->type;
        // $title = $request->title;
        // $content = $request->content;
        // $status = $request->status;
        // $link_or_post_id = is_numeric($request->link_or_post_id) ? $request->link_or_post_id : $this->getLinkOrPostIdFromUrl($request->link_or_post_id ?? '');

        // $query = '(HOUR(CURRENT_TIMESTAMP()) * 60 + MINUTE(CURRENT_TIMESTAMP()) - HOUR(updated_at) * 60 - MINUTE(updated_at))/60 + DATEDIFF(CURRENT_TIMESTAMP(), updated_at) * 24';
        // $queryLastData = '(HOUR(CURRENT_TIMESTAMP()) * 60 + MINUTE(CURRENT_TIMESTAMP()) - HOUR(created_at) * 60 - MINUTE(created_at))/60 + DATEDIFF(CURRENT_TIMESTAMP(), created_at) * 24';

        // DB::enableQueryLog();
        $users = User::get()->toArray();
        // Tạo một mảng tương ứng với user_id và tên của user
        $userMap = [];
        foreach ($users as $u) {
            $userMap[$u['id']] = $u['name'];
        }
        $userLinks = [];
        if($user_id != null)
        {
            $userLinks = Link::where('user_id', $user_id)->where('type', $type)->orderByDesc('created_at')-> get()?->toArray() ?? [];
        }
        else
        {
            $userLinks = Link::where('type', $type)->orderByDesc('created_at')->get()?->toArray() ?? [];
        }
        

        foreach ($userLinks as &$post) {
            if (isset($userMap[$post['user_id']])) {
                $post['name'] = $userMap[$post['user_id']];
            } else {
                $post['name'] = '';
            }
        }
        return response()->json([
            'status' => 0,
            'links' => $userLinks,
            'user' => User::firstWhere('id', $user_id),
        ]);
    }

    public function getAllLinkScan_V2(Request $request)
    {
        $to = $request->to;
        $from = $request->from;
        $comment_from = $request->comment_from;
        $comment_to = $request->comment_to;
        $delay_from = $request->delay_from;
        $delay_to = $request->delay_to;
        $reaction_from = $request->reaction_from;
        $reaction_to = $request->reaction_to;
        $last_data_from = $request->last_data_from;
        $last_data_to = $request->last_data_to;
        $title = $request->title;
        $content_i = $request->content;
        $link_or_post_id = $request->link_or_post_id;
        $user_i = $request->user;
        $status_i = $request->status;
        $type = (string)$request->type;

        
        // Initialize variables for start and end datetime strings
        $startDateTimeStr = '';
        $endDateTimeStr = '';

        // Construct the start datetime string if $inputFromHour is not null
        if (strlen($last_data_from)) {

            $startDateTimeStr = Carbon::now()->subHours($last_data_from)->format('Y-m-d H:i:s');
        }

        // Construct the end datetime string if $inputToHour is not null
        if (strlen($last_data_to)) {
            $endDateTimeStr = Carbon::now()->subHours($last_data_to)->format('Y-m-d H:i:s');
        }

        // DB::enableQueryLog();
        $users = User::get()->toArray();
        // Tạo một mảng tương ứng với user_id và tên của user
        $userMap = [];
        foreach ($users as $u) {
            $userMap[$u['id']] = $u['name'];
        }
        $userLinks = [];
        if($user_i != null)
        {
            $userLinks = Link::where('user_id', $user_i)
                                ->where('type', $type)
                                //Comment
                                ->when(strlen($comment_from), function ($q) use ($comment_from) {
                                    //return $q->where('comment', '>=', $comment_from);
                                    return $q->whereRaw('CAST(diff_comment AS UNSIGNED) >= ?', [$comment_from]);
                                })
                                ->when(strlen($comment_to), function ($q) use ($comment_to) {
                                    return $q->whereRaw('CAST(diff_comment AS UNSIGNED) <= ?', [$comment_to]);
                                })
                                //Data cuoi
                                ->when(strlen($startDateTimeStr) || strlen($endDateTimeStr), function ($q) use ($startDateTimeStr, $endDateTimeStr) {
                                    return $q->where(function ($query) use ($startDateTimeStr, $endDateTimeStr) {
                                        if (strlen($startDateTimeStr) && strlen($endDateTimeStr)) {
                                            $query->where('datacuoi', '<=', $startDateTimeStr)->where('datacuoi', '>=',$endDateTimeStr);
                                        }else{
                                            if (strlen($startDateTimeStr)) {
                                                $query->where('datacuoi', '>=', $startDateTimeStr);
                                            }
                                            if (strlen($endDateTimeStr)) {
                                                $query->orWhere('datacuoi', '>=', $endDateTimeStr);
                                            }
                                        }
                                    });
                                })
                                //Reaction
                                ->when(strlen($reaction_from), function ($q) use ($reaction_from) {
                                    //return $q->where('comment', '>=', $comment_from);
                                    return $q->whereRaw('CAST(diff_reaction AS UNSIGNED) >= ?', [$reaction_from]); 
                                })
                                ->when(strlen($reaction_to), function ($q) use ($reaction_to) {
                                    return $q->whereRaw('CAST(diff_reaction AS UNSIGNED) <= ?', [$reaction_to]);
                                })
                                // title
                                ->when(strlen($title), function ($q) use ($title) {
                                    return $q->where('title', 'like', "%$title%");
                                })
                                // content
                                ->when(strlen($content_i), function ($q) use ($content_i) {
                                    return $q->where('content', 'like', "%$content_i%");
                                })
                                // link_or_post_id
                                ->when(strlen($link_or_post_id), function ($q) use ($link_or_post_id) {
                                    return $q->where('link_or_post_id', 'like', "%$link_or_post_id%");
                                })
                                //From-to
                                ->when(strlen($from), function ($q) use ($from) {
                                    return $q->where(
                                        'created_at',
                                        '>=',
                                        $from
                                    );
                                })
                                ->when(strlen($to), function ($q) use ($to) {
                                    return $q->where('created_at', '<=', $to . ' 23:59:59');
                                })
                                ->orderByDesc('created_at')-> get()?->toArray() ?? [];
        }
        else
        {
            $userLinks = Link::where('type', $type)
                                ->when(strlen($comment_from), function ($q) use ($comment_from) {
                                    //return $q->where('comment', '>=', $comment_from);
                                    return $q->whereRaw('CAST(diff_comment AS UNSIGNED) >= ?', [$comment_from]);
                                })
                                ->when(strlen($comment_to), function ($q) use ($comment_to) {
                                    return $q->whereRaw('CAST(diff_comment AS UNSIGNED) <= ?', [$comment_to]);
                                })
                                //Data cuoi
                                ->when(strlen($startDateTimeStr) || strlen($endDateTimeStr), function ($q) use ($startDateTimeStr, $endDateTimeStr) {
                                    return $q->where(function ($query) use ($startDateTimeStr, $endDateTimeStr) {
                                        if (strlen($startDateTimeStr) && strlen($endDateTimeStr)) {
                                            $query->where('datacuoi', '<=', $startDateTimeStr)->where('datacuoi', '>=',$endDateTimeStr);
                                        }else{
                                            if (strlen($startDateTimeStr)) {
                                                $query->where('datacuoi', '>=', $startDateTimeStr);
                                            }
                                            if (strlen($endDateTimeStr)) {
                                                $query->orWhere('datacuoi', '>=', $endDateTimeStr);
                                            }
                                        }
                                    });
                                })
                                //Reaction
                                ->when(strlen($reaction_from), function ($q) use ($reaction_from) {
                                    //return $q->where('comment', '>=', $comment_from);
                                    return $q->whereRaw('CAST(diff_reaction AS UNSIGNED) >= ?', [$reaction_from]); 
                                })
                                ->when(strlen($reaction_to), function ($q) use ($reaction_to) {
                                    return $q->whereRaw('CAST(diff_reaction AS UNSIGNED) <= ?', [$reaction_to]);
                                })
                                // title
                                ->when(strlen($title), function ($q) use ($title) {
                                    return $q->where('title', 'like', "%$title%");
                                })
                                // content
                                ->when(strlen($content_i), function ($q) use ($content_i) {
                                    return $q->where('content', 'like', "%$content_i%");
                                })
                                // link_or_post_id
                                ->when(strlen($link_or_post_id), function ($q) use ($link_or_post_id) {
                                    return $q->where('link_or_post_id', 'like', "%$link_or_post_id%");
                                })
                                //From-to
                                ->when(strlen($from), function ($q) use ($from) {
                                    return $q->where(
                                        'created_at',
                                        '>=',
                                        $from
                                    );
                                })
                                ->when(strlen($to), function ($q) use ($to) {
                                    return $q->where('created_at', '<=', $to . ' 23:59:59');
                                })
                                ->orderByDesc('created_at')->get()?->toArray() ?? [];
        }

        //Get setting
        $dataSetting = Setting::orderBy('key')->get();
        $data_cuoi_from_setting_admin = 0;
        $data_cuoi_to_setting_admin = 0;
        $cam_xuc_from_setting_admin = 0;
        $cam_xuc_to_setting_admin = 0;
        $binh_luan_from_setting_admin = 0;
        $binh_luan_to_setting_admin = 0;
        $data_cmt_from_setting_admin = 0;
        $data_cmt_to_setting_admin = 0;
        $data_reaction_from_setting_admin = 0;
        $data_reaction_to_setting_admin = 0;
        $view_from_setting_admin = 0;
        $view_to_setting_admin = 0;

        foreach ($dataSetting as $setting) {
            $valuexx = $setting["value"];
            switch ($setting['key']) {
                case 'data_cuoi_from_setting_admin':
                    $data_cuoi_from_setting_admin = $valuexx;
                    break;
                case 'data_cuoi_to_setting_admin':
                    $data_cuoi_to_setting_admin = $valuexx;
                    break;
                case 'cam_xuc_from_setting_admin':
                    $cam_xuc_from_setting_admin = $valuexx;
                    break;
                case 'cam_xuc_to_setting_admin':
                    $cam_xuc_to_setting_admin = $valuexx;
                    break;
                case 'binh_luan_from_setting_admin':
                    $binh_luan_from_setting_admin = $valuexx;
                    break;
                case 'binh_luan_to_setting_admin':
                    $binh_luan_to_setting_admin = $valuexx;
                    break;
                case 'data_cmt_from_setting_admin':
                    $data_cmt_from_setting_admin = $valuexx;
                    break;
                case 'data_cmt_to_setting_admin':
                    $data_cmt_to_setting_admin = $valuexx;
                    break;
                case 'data_reaction_from_setting_admin':
                    $data_reaction_from_setting_admin = $valuexx;
                    break;
                case 'data_reaction_to_setting_admin':
                    $data_reaction_to_setting_admin = $valuexx;
                    break;
                case 'view_from_setting_admin':
                    $view_from_setting_admin = $valuexx;
                    break;
                case 'view_to_setting_admin':
                    $view_to_setting_admin = $valuexx;
                    break;
            }
        }
        foreach ($userLinks as &$post) {
            if (isset($userMap[$post['user_id']])) {
                $post['name'] = $userMap[$post['user_id']];
            } else {
                $post['name'] = '';
            }
            $post['is_scan'] = 0;

            //Filter for comment
            if($post['comment'] >= $binh_luan_from_setting_admin && $binh_luan_from_setting_admin != 0 &&
              $post['comment'] <= $binh_luan_to_setting_admin && $binh_luan_to_setting_admin != 0 ){
                $post['is_scan'] = 1;
            }
            if($post['comment'] >= $binh_luan_from_setting_admin && $binh_luan_from_setting_admin != 0 &&
               $binh_luan_to_setting_admin == 0 ){
                $post['is_scan'] = 1;
            }
            if($post['comment'] <= $binh_luan_to_setting_admin && $binh_luan_to_setting_admin != 0 &&
               $binh_luan_from_setting_admin == 0 ){
                $post['is_scan'] = 1;
            }

            //Filter data cuối
            if ($data_cuoi_from_setting_admin >= 0) {
                $startDateTimeStr = Carbon::now()->subHours($data_cuoi_from_setting_admin)->format('Y-m-d H:i:s');
            }
    
            // Construct the end datetime string if $inputToHour is not null
            if ($data_cuoi_to_setting_admin >=0) {
                $endDateTimeStr = Carbon::now()->subHours($data_cuoi_to_setting_admin)->format('Y-m-d H:i:s');
            }
            if($post['datacuoi'] <= $startDateTimeStr && $data_cuoi_from_setting_admin >= 0 &&
              $post['datacuoi'] >= $endDateTimeStr && $data_cuoi_to_setting_admin >= 0 ){
                $post['is_scan'] = 1;
            }
            if($post['datacuoi'] <= $startDateTimeStr && $data_cuoi_from_setting_admin >= 0 &&
              $data_cuoi_to_setting_admin < 0 ){
                $post['is_scan'] = 1;
            }
            if($data_cuoi_from_setting_admin < 0 &&
              $post['datacuoi'] >= $endDateTimeStr && $data_cuoi_to_setting_admin >= 0 ){
                $post['is_scan'] = 1;
            }
            
            //Filter Reaction
            if($post['reaction'] >= $cam_xuc_from_setting_admin && $cam_xuc_from_setting_admin != 0 &&
              $post['reaction'] <= $cam_xuc_to_setting_admin && $cam_xuc_to_setting_admin != 0 ){
                $post['is_scan'] = 1;
            }
            if($post['reaction'] >= $cam_xuc_from_setting_admin && $cam_xuc_from_setting_admin != 0 &&
               $cam_xuc_to_setting_admin == 0 ){
                $post['reaction'] = 1;
            }
            if($post['reaction'] <= $cam_xuc_to_setting_admin && $cam_xuc_to_setting_admin != 0 &&
               $cam_xuc_from_setting_admin == 0 ){
                $post['is_scan'] = 1;
            }

            //Filter Data cmt
            if($post['data'] >= $data_cmt_from_setting_admin && $data_cmt_from_setting_admin != 0 &&
              $post['data'] <= $data_cmt_to_setting_admin && $data_cmt_to_setting_admin != 0 ){
                $post['is_scan'] = 1;
            }
            if($post['data'] >= $data_cmt_from_setting_admin && $data_cmt_from_setting_admin != 0 &&
               $data_cmt_to_setting_admin == 0 ){
                $post['is_scan'] = 1;
            }
            if($post['data'] <= $data_cmt_to_setting_admin && $data_cmt_to_setting_admin != 0 &&
               $data_cmt_from_setting_admin == 0 ){
                $post['is_scan'] = 1;
            }

            //Filter Data reaction
            if($post['reaction_real'] >= $data_reaction_from_setting_admin && $data_reaction_from_setting_admin != 0 &&
              $post['reaction_real'] <= $data_reaction_to_setting_admin && $data_reaction_to_setting_admin != 0 ){
                $post['is_scan'] = 1;
            }
            if($post['reaction_real'] >= $data_reaction_from_setting_admin && $data_reaction_from_setting_admin != 0 &&
               $data_reaction_to_setting_admin == 0 ){
                $post['is_scan'] = 1;
            }
            if($post['reaction_real'] <= $data_reaction_to_setting_admin && $data_reaction_to_setting_admin != 0 &&
               $data_reaction_from_setting_admin == 0 ){
                $post['is_scan'] = 1;
            }

            //Filter View
            if($post['view'] >= $view_from_setting_admin && $view_from_setting_admin != 0 &&
              $post['view'] <= $view_to_setting_admin && $view_to_setting_admin != 0 ){
                $post['is_scan'] = 1;
            }
            if($post['view'] >= $view_from_setting_admin && $view_from_setting_admin != 0 &&
               $view_to_setting_admin == 0 ){
                $post['is_scan'] = 1;
            }
            if($post['view'] <= $view_to_setting_admin && $view_to_setting_admin != 0 &&
               $view_from_setting_admin == 0 ){
                $post['is_scan'] = 1;
            }
        }

        return response()->json([
            'status' => 0,
            'links' => $userLinks,
            'user' => User::firstWhere('id', $status_i),
            'start' => $startDateTimeStr,
            'end' => $endDateTimeStr
        ]);
    }

    public function updateLinkByLinkOrPostId(Request $request)
    {
        try {
            $data = $request->validate([
                'links' => 'required|array',
                'links.*.link_or_post_id' => 'required|string',
                'links.*.parent_link_or_post_id' => 'nullable|string',
                'links.*.title' => 'nullable|string',
                'links.*.time' => 'nullable|string',
                'links.*.content' => 'nullable|string',
                'links.*.comment' => 'nullable|string',
                // 'links.*.diff_comment' => 'nullable|string',
                'links.*.data' => 'nullable|string',
                // 'links.*.diff_data' => 'nullable|string',
                'links.*.reaction' => 'nullable|string',
                // 'links.*.diff_reaction' => 'nullable|string',
                'links.*.is_scan' => 'nullable|in:0,1,2',
                'links.*.status' => 'nullable|in:0,1',
                'links.*.note' => 'nullable|string',
                'links.*.delay' => 'nullable|string',
                'links.*.end_cursor' => 'nullable|string',
                'links.*.type' => 'nullable|in:0,1,2',
            ]);

            DB::beginTransaction();

            foreach ($data['links'] as $key => &$value) {
                $link = Link::with(['parentLink', 'childLinks'])
                    ->firstWhere('link_or_post_id', $value['link_or_post_id']);
                if (!$link) {
                    throw new Exception('link_or_post_id không tồn tại');
                }
                $childLinks = $link?->childLinks;
                // get and set diff
                if (isset($value['comment']) && strlen($value['comment'])) {
                    $lastHistory = LinkHistory::where('link_id', $link->id)
                        ->where('type', GlobalConstant::TYPE_COMMENT)
                        ->orderByDesc('id')
                        ->first();
                    $value['diff_comment'] = $lastHistory?->comment ? ((int)$value['comment'] - (int)$lastHistory->comment) : (int)$value['comment'];
                    $linkHistory = LinkHistory::create([
                        'comment' => $value['comment'],
                        'diff_comment' => $value['diff_comment'],
                        'link_id' => $link->id,
                        'type' => GlobalConstant::TYPE_COMMENT
                    ]);
                    // sync data of count of comment
                    if ($childLinks) {
                        foreach ($childLinks as $childLink) {
                            $newLinkHistory = $linkHistory->replicate()->fill([
                                'link_id' => $childLink->id,
                            ]);
                            $newLinkHistory->save();
                        }
                    }
                }
                if (isset($value['data']) && strlen($value['data'])) {
                    $lastHistory = LinkHistory::where('link_id', $link->id)
                        ->where('type', GlobalConstant::TYPE_DATA)
                        ->orderByDesc('id')
                        ->first();
                    $value['diff_data'] = $lastHistory?->data ? ((int)$value['data'] - (int)$lastHistory->data) : (int)$value['data'];
                    $linkHistory = LinkHistory::create([
                        'data' => $value['data'],
                        'diff_data' => $value['diff_data'],
                        'link_id' => $link->id,
                        'type' => GlobalConstant::TYPE_DATA
                    ]);
                    // sync data of count of data
                    if ($childLinks) {
                        foreach ($childLinks as $childLink) {
                            $newLinkHistory = $linkHistory->replicate()->fill([
                                'link_id' => $childLink->id,
                            ]);
                            $newLinkHistory->save();
                        }
                    }
                }
                if (isset($value['reaction']) && strlen($value['reaction'])) {
                    $lastHistory = LinkHistory::where('link_id', $link->id)
                        ->where('type', GlobalConstant::TYPE_REACTION)
                        ->orderByDesc('id')
                        ->first();
                    $value['diff_reaction'] = $lastHistory?->reaction ? ((int)$value['reaction'] - (int)$lastHistory->reaction) : (int)$value['reaction'];
                    $linkHistory = LinkHistory::create([
                        'reaction' => $value['reaction'],
                        'diff_reaction' => $value['diff_reaction'],
                        'link_id' => $link->id,
                        'type' => GlobalConstant::TYPE_REACTION
                    ]);
                    // sync data of count of reaction
                    if ($childLinks) {
                        foreach ($childLinks as $childLink) {
                            $newLinkHistory = $linkHistory->replicate()->fill([
                                'link_id' => $childLink->id,
                            ]);
                            $newLinkHistory->save();
                        }
                    }
                }
                //
                unset($value['link_or_post_id']);
                $link->update($value);
                $value['link_id'] = $link->id;
                $value['created_at'] = date('Y-m-d H:i:s');
                $value['updated_at'] = date('Y-m-d H:i:s');
                //
                $is_scan = $value['is_scan'] ?? '';
                if (strlen($is_scan)) {
                    Link::where('link_id', $link->id)
                        ->update([
                            'is_scan' => $is_scan,
                            'created_at' => date('Y-m-d H:i:s'),
                        ]);
                }
            }
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => 1,
                'message' => $e->getMessage()
            ]);
        }
        return response()->json([
            'status' => 0,
        ]);
    }

    public function update(Request $request)
    {
        try {
            $data = $request->validate([
                'id' => 'required|integer',
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
                'status' => 'nullable|in:0,1',
                'note' => 'nullable|string',
                'delay' => 'nullable|string',
                'end_cursor' => 'nullable|string',
                // 'link_or_post_id' => 'nullable|string',
                'parent_link_or_post_id' => 'nullable|string',
                'type' => 'nullable|in:0,1,2',
                'user_id' => 'nullable|integer',
            ]);

            $type = $data['type'] ?? '';
            // check limit follow
            if (strlen($type) && (int)$type === GlobalConstant::TYPE_FOLLOW) {
                $user = User::firstWhere('id', $data['user_id']);
                $userLinks = Link::with(['user'])
                    ->where('user_id', $user->id)
                    ->where('type', GlobalConstant::TYPE_FOLLOW)
                    ->get();
                if ($userLinks->count() >= $user->limit_follow) {
                    throw new Exception('Quá giới hạn link theo dõi');
                }
            }

            // check limit scan
            if (strlen($type) && (int)$type === GlobalConstant::TYPE_SCAN) {
                $user = User::firstWhere('id', $data['user_id']);
                $userLinks = Link::with(['user'])
                    ->where('user_id', $user->id)
                    ->where('type', GlobalConstant::TYPE_SCAN)
                    ->get();
                if ($userLinks->count() >= $user->limit) {
                    throw new Exception('Quá giới hạn link quét');
                }
            }

            DB::beginTransaction();
            $is_scan = $data['is_scan'] ?? 0;
            $type = $data['type'] ?? 0;
            $user_id = $data['user_id'] ?? '';
            if (strlen($is_scan) && strlen($user_id)) {
                $check = Link::where('user_id', '!=', $user_id)
                    ->where('id', $data['id'])
                    ->where('is_scan', GlobalConstant::IS_ON)
                    ->get();
                // check any link is on
                if ($check->count()) {
                    $data['is_scan'] = GlobalConstant::IS_ON;
                }
                Link::where('user_id', $user_id)
                    ->where('id', $data['id'])
                    ->update([
                        'is_scan' => $is_scan,
                        'type' => $type,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                unset($data['user_id']);
            }
            unset($data['id']);
            Link::where('id', $request->input('id'))->update($data);
            DB::commit();

            return response()->json([
                'status' => 0,
            ]);
        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => 1,
                'message' => $e->getMessage()
            ]);
        }
    }

    /*
        Using for update link by list ids exclude admin/linkrunning
    **/
    public function updateLinkByListLinkId(Request $request)
    {
        try {
            $data = $request->validate([
                'ids' => 'required|array',
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
                'status' => 'nullable|in:0,1',
                'note' => 'nullable|string',
                'delay' => 'nullable|string',
                'end_cursor' => 'nullable|string',
                'parent_link_or_post_id' => 'nullable|string',
                // 'link_or_post_id' => 'nullable|string',
                'type' => 'nullable|in:0,1,2',
                'user_id' => 'nullable|integer',
            ]);

            $user_id = $data['user_id'] ?? '';
            $type = $data['type'] ?? '';
            // check limit follow
            if (strlen($type) && (int)$type === GlobalConstant::TYPE_FOLLOW && $user_id) {
                $user = User::firstWhere('id', $user_id);

                $userLinks = Link::with(['user'])
                    ->where('user_id', $user->id)
                    ->where('type', GlobalConstant::TYPE_FOLLOW)
                    ->get();
                if ($userLinks->count() >= $user->limit_follow) {
                    throw new Exception('Quá giới hạn link theo dõi');
                }
            }

            // check limit scan
            if (strlen($type) && (int)$type === GlobalConstant::TYPE_SCAN && $user_id) {
                $user = User::firstWhere('id', $user_id);

                $userLinks = Link::with(['user'])
                    ->where('user_id', $user->id)
                    ->where('type', GlobalConstant::TYPE_SCAN)
                    ->get();
                if ($userLinks->count() >= $user->limit) {
                    throw new Exception('Quá giới hạn link quét');
                }
            }

            //
            unset($data['ids']);
            $data['created_at'] = date('Y-m-d H:i:s');
            $keys = [
                'user_id',
                'link_id',
                'is_scan',
                'title',
                'note',
                'type',
            ];
            $dataUpdate = [];
            foreach ($keys as $key) {
                if (isset($data[$key]) && strlen($data[$key])) {
                    $dataUpdate[$key] =  $data[$key];
                }
            }
            // update created_at
            if (strlen($data['type'] ?? '')) {
                $dataUpdate['created_at'] = date('Y-m-d H:i:s');
            }
            if (!empty($data['is_scan'])) {
                $dataUpdate['is_on_at'] = date('Y-m-d H:i:s');
            }
            DB::beginTransaction();
            Link::whereIn('id', $request->ids)->update($dataUpdate);
            DB::commit();

            return response()->json([
                'status' => 0,
            ]);
        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => 1,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateStatusLink(Request $request)
    {
        try {
            $data = $request->validate([
                'ids' => 'required|array',
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
                'status' => 'nullable|in:0,1',
                'note' => 'nullable|string',
                'delay' => 'nullable|string',
                'end_cursor' => 'nullable|string',
                'parent_link_or_post_id' => 'nullable|string',
                // 'link_or_post_id' => 'nullable|string',
                'type' => 'nullable|in:0,1,2',
                'user_id' => 'nullable|integer',
            ]);

            $user_id = $data['user_id'] ?? '';
            $type = $data['type'] ?? '';

            $data['created_at'] = date('Y-m-d H:i:s');
            
            if (!empty($data['is_scan'])) {
                $dataUpdate['is_on_at'] = date('Y-m-d H:i:s');
            }
            DB::beginTransaction();
            Link::whereIn('id', $request->ids)->update($dataUpdate);
            DB::commit();

            return response()->json([
                'status' => 0,
            ]);
        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => 1,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteAll(Request $request)
    {
        try {
            DB::beginTransaction();

            Link::whereIn('id', $request->ids)->delete();

            DB::commit();
            return response()->json([
                'status' => 0,
            ]);
        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => 1,
                'message' => $e->getMessage()
            ]);
        }
    }
}
