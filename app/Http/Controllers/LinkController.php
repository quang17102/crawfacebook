<?php

namespace App\Http\Controllers;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\LinkHistory;
use App\Models\Comment;
use App\Models\Reaction;
use App\Models\User;
use App\Models\Setting;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
use Carbon\Carbon;

class LinkController extends Controller
{
    /**
     * Only for admin/linkrunning
     */
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

        DB::enableQueryLog();

        $links = Link::with([
            'user',
            'sameLinks.user',
            'parentLink.sameLinks.user',
            'userLinks.user',
            'parentLink.user',
            'childLinks.user',
            'isOnUserLinks.user',
            'childLinks.isOnUserLinks.user',
            'parentLink.isOnUserLinks.user',
            'parentLink.childLinks.isOnUserLinks.user'
        ])
            // default just get all link has at least an userLink record with is_scan = ON
            ->where('is_scan', GlobalConstant::IS_ON)
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
            ->when($user_id, function ($q) use ($user_id) {
                return $q->whereHas('userLinks', function ($q) use ($user_id) {
                    $q->where('user_id', $user_id);
                });
            })
            ->when($link_id, function ($q) use ($link_id) {
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
            ->when(strlen($data_from), function ($q) use ($data_from, $data_to) {
                return $q->when(strlen($data_to), function ($q) use ($data_from, $data_to) {
                    return $q->whereRaw('diff_data >= ?', $data_from)
                        ->whereRaw('diff_data <= ?', $data_to);
                }, function ($q) use ($data_from) {
                    return $q->whereRaw('diff_data >= ?', $data_from);
                });
            }, function ($q) use ($data_to) {
                return $q->when(strlen($data_to), function ($q) use ($data_to) {
                    return $q->whereRaw('diff_data <= ?', $data_to);
                });
            })
            // reaction
            ->when(strlen($reaction_from), function ($q) use ($reaction_from, $reaction_to) {
                return $q->when(strlen($reaction_to), function ($q) use ($reaction_from, $reaction_to) {
                    return $q->whereRaw('diff_reaction >= ?', $reaction_from)
                        ->whereRaw('diff_reaction <= ?', $reaction_to);
                }, function ($q) use ($reaction_from) {
                    return $q->whereRaw('diff_reaction >= ?', $reaction_from);
                });
            }, function ($q) use ($reaction_to) {
                return $q->when(strlen($reaction_to), function ($q) use ($reaction_to) {
                    return $q->whereRaw('diff_reaction <= ?', $reaction_to);
                });
            })
            // comment
            ->when(strlen($comment_from), function ($q) use ($comment_from, $comment_to) {
                return $q->when(strlen($comment_to), function ($q) use ($comment_from, $comment_to) {
                    return $q->whereRaw('diff_comment >= ?', $comment_from)
                        ->whereRaw('diff_comment <= ?', $comment_to);
                }, function ($q) use ($comment_from) {
                    return $q->whereRaw('diff_comment >= ?', $comment_from);
                });
            }, function ($q) use ($comment_to) {
                return $q->when(strlen($comment_to), function ($q) use ($comment_to) {
                    return $q->whereRaw('diff_comment <= ?', $comment_to);
                });
            })
            // last data
            ->when(strlen($last_data_from), function ($q) use ($last_data_from, $last_data_to, $queryLastData) {
                return $q->when(strlen($last_data_to), function ($q) use ($last_data_from, $last_data_to, $queryLastData) {
                    return $q->whereHas('comments', function ($q) use ($last_data_from, $last_data_to, $queryLastData) {
                        $q->whereRaw("$queryLastData >= ?", $last_data_from)
                            ->whereRaw("$queryLastData <= ?", $last_data_to);
                    });
                }, function ($q) use ($last_data_from, $queryLastData) {
                    return $q->whereHas('comments', function ($q) use ($last_data_from, $queryLastData) {
                        $q->whereRaw("$queryLastData >= ?", $last_data_from);
                    });
                });
            }, function ($q) use ($last_data_to, $queryLastData) {
                return $q->when(strlen($last_data_to), function ($q) use ($last_data_to, $queryLastData) {
                    return $q->whereHas('comments', function ($q) use ($last_data_to, $queryLastData) {
                        $q->whereRaw("$queryLastData <= ?", $last_data_to);
                    });
                });
            })
            // data update count
            ->when(strlen($time_from), function ($q) use ($time_from, $time_to, $query) {
                return $q->when(strlen($time_to), function ($q) use ($time_from, $time_to, $query) {
                    return $q->whereRaw("$query >= ?", $time_from)
                        ->whereRaw("$query <= ?", $time_to . ' 23:59:59');
                }, function ($q) use ($time_from, $query) {
                    return $q->whereRaw("$query >= ?", $time_from);
                });
            }, function ($q) use ($time_to, $query) {
                return $q->when(strlen($time_to), function ($q) use ($time_to, $query) {
                    return $q->whereRaw("$query <= ?", $time_to . ' 23:59:59');
                });
            })
            // date
            ->when($from, function ($q) use ($from, $to) {
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
                        return  $q->whereIn('is_scan', $is_scan);
                        break;
                    default:
                        return  $q->where('is_scan', $is_scan);
                        break;
                }
            })
            // user
            ->when(strlen($user), function ($q) use ($user) {
                return $q->where('user_id', $user)
                    ->where('is_scan',  GlobalConstant::IS_ON);
            })
            // note
            ->when(strlen($note), function ($q) use ($note) {
                return $q->where('note', 'like', "%$note%");
            })
            // type
            ->when(in_array($type, GlobalConstant::LINK_STATUS), function ($q) use ($type) {
                return $q->where('type', $type);
            })
            // status
            ->when(strlen($status), function ($q) use ($status) {
                return $q->where('status', $status);
            })
            // default get all link is root
            // ->when(function ($q) {
            //     $q->where('parent_link_or_post_id', '');
            // })
            // default order by created_at descending
            ->orderByDesc('created_at')
            ->get()?->toArray() ?? [];

        // dd(DB::getRawQueryLog());
        $result_links = [];
        foreach ($links as $value) {
            $accounts = [];
            $titles = [];
            if (strlen($value['parent_link_or_post_id'] ?? '')) {
                $value = $value['parent_link'];
            }
            foreach (array_merge($value['is_on_user_links'], $value['same_links']) as $itemLink) {
                if (!empty($itemLink['user'])) {
                    $accounts[$itemLink['id']] = $itemLink['user'];
                }
                $titles[$itemLink['id']] = $itemLink['title'];
            }
            // foreach ($value['child_links'] as $childLink) {
            //     foreach ($childLink['is_on_user_links']  as $is_on_user_link) {
            //         $accounts[$is_on_user_link['id']] = $is_on_user_link;
            //     }
            // }
            $result_links[$value['link_or_post_id']] = [
                ...$value,
                'accounts' => collect($accounts)->values(),
                'titles' => collect($titles)->values(),
            ];
        }

        $result = UserRole::where('user_id', Auth::id())->pluck('role')->toArray() ?? [];
        $permistion_reaction = "NO";
        $permistion_view = "NO";
        if (in_array(7, $result)) {
            $permistion_reaction = "YES";
        } 
        if (in_array(8, $result)) {
            $permistion_view = "YES";
        }
        return response()->json([
            'status' => 0,
            'links' => collect($result_links)->values(),
            'user' => User::firstWhere('id', $user_id),
            'permistion_reaction' => $permistion_reaction,
            'permistion_view' => $permistion_view,
        ]);
    }
    //Quang
    public function checkTimeZone(Request $request){
        $current_time = date('Y-m-d H:i:s');
        return response()->json([
            'status' => 1,
            'timezone' => $current_time,
        ]);
    }
    public function getAllNewForUI(Request $request)
    {
        try{
            $links = Link::where('type', GlobalConstant::TYPE_SCAN)
                            // ->where('status', GlobalConstant::STATUS_RUNNING)
                            // ->where('is_scan', GlobalConstant::STATUS_RUNNING)
                            ->get()->toArray();
            $users = User::get()->toArray();
            // Chuyển danh sách user thành một mảng liên kết để tra cứu nhanh
            $user_lookup = [];
            foreach ($users as $user) {
                $user_lookup[$user['id']] = $user['name'];
            }
            
            // Mảng để lưu kết quả gộp tạm thời và theo dõi trạng thái
            $temp_result = [];
            $status_tracker = [];
            $issan_tracker = [];
            
            // Duyệt qua từng phần tử trong dữ liệu đầu vào
            foreach ($links as $entry) {
                $uid_post = $entry['link_or_post_id'];
                $parentid = $entry['parent_link_or_post_id'];
                $user_id = $entry['user_id'];
                $status = $entry['status'];
                $issan = $entry['is_scan'];
                $delay = $entry['delay'];
                $type = $entry['type'];
                $is_on_at = $entry['is_on_at'];
                $datacuoi = $entry['datacuoi'];
                $comment = $entry['comment'];
                $diff_comment = $entry['diff_comment'];
                $reaction = $entry['reaction'];
                $diff_reaction = $entry['diff_reaction'];
                $content = $entry['content'];
                $data = $entry['data'];
                $diff_data = $entry['diff_data'];

                // Xác định uid_post mục tiêu để gộp
                $target_uid_post = ($parentid === "" || $parentid === null) ? $uid_post : $parentid;
            
                // Nếu uid_post mục tiêu chưa có trong mảng kết quả tạm thời, khởi tạo phần tử mới
                if (!isset($temp_result[$target_uid_post])) {
                    $temp_result[$target_uid_post] = [
                        'link_or_post_id' => $target_uid_post,
                        'user_id' => [],
                        'parent_link_or_post_id' => [],
                        'is_scan' => 0,  // Mặc định là 0 và sẽ cập nhật sau
                        'status' => 0,  // Mặc định là 0 và sẽ cập nhật sau
                        'delay' => 0,
                        'type' => 0,
                    ];
                    $status_tracker[$target_uid_post] = [];
                    $issan_tracker[$target_uid_post] = [];
                }
            
                // Thêm user_id vào mảng user_id của phần tử tương ứng
                if (!in_array($user_id, $temp_result[$target_uid_post]['user_id'])) {
                    $temp_result[$target_uid_post]['user_id'][] = $user_id;
                }
                // Cập nhật trạng thái và theo dõi
                if ($status == 1) {
                    $temp_result[$target_uid_post]['status'] = 1;
                }
                if ($issan == 1) {
                    $temp_result[$target_uid_post]['is_scan'] = 1;
                }
                if ($type == 0) {
                    $temp_result[$target_uid_post]['type'] = 1;
                }
                $temp_result[$target_uid_post]['delay'] = $delay;
                $temp_result[$target_uid_post]['is_on_at'] = $is_on_at;
                $temp_result[$target_uid_post]['datacuoi'] = $datacuoi;

                $temp_result[$target_uid_post]['comment'] = $comment;
                $temp_result[$target_uid_post]['diff_comment'] = $diff_comment;
                $temp_result[$target_uid_post]['reaction'] = $reaction;
                $temp_result[$target_uid_post]['diff_reaction'] = $diff_reaction;

                $temp_result[$target_uid_post]['content'] = $content;

                $temp_result[$target_uid_post]['data'] = $data;
                $temp_result[$target_uid_post]['diff_data'] = $diff_data;
                
                $status_tracker[$target_uid_post][] = $status;
                $issan_tracker[$target_uid_post][] = $issan;
            }
            
            // Mảng để lưu kết quả cuối cùng
            $result = [];
            
            // Duyệt qua mảng tạm thời và loại bỏ các mục có tất cả các trạng thái là 0
            foreach ($temp_result as $uid_post => $entry) {
                if (in_array(1, $issan_tracker[$uid_post])) {
                    // Ghép tên user lại
                    $user_names = [];
                    foreach ($entry['user_id'] as $id) {
                        if (isset($user_lookup[$id])) {
                            $user_names[] = $user_lookup[$id];
                        }
                    }
                    $entry['user_id'] = implode('|', $user_names);
                    $result[] = $entry;
                }
            }
            return response()->json([
                'status' => 1,
                'links' => $result,
                'user' => "Oke",
            ]);

        }catch(Exception $ex){
            return response()->json([
                'status' => 0,
                'links' => var_dump($ex),
                'user' => "Error",
            ]);
        }
    }

    public function getAllNewForUI_V2(Request $request)
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
        $view_from = $request->view_from ?? '';
        $view_to = $request->view_to ?? '';
        $data_reaction_from = $request->data_reaction_from ?? '';
        $data_reaction_to = $request->data_reaction_to ?? '';
        $linktn = $request->linktn ?? '';

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

        try{
            $links = Link::where('type', GlobalConstant::TYPE_SCAN)
                        // link_or_post_id
                        ->when(strlen($link_or_post_id), function ($q) use ($link_or_post_id) {
                            return $q->where('link_or_post_id', 'like', "%$link_or_post_id%");
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
                        ->when(strlen($startDateTimeStr) || strlen($endDateTimeStr), function ($q) use ($startDateTimeStr, $endDateTimeStr, $last_data_to) {
                            return $q->where(function ($query) use ($startDateTimeStr, $endDateTimeStr, $last_data_to) {
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
                                if ((int)$last_data_to >= 999) {
                                    $query->orWhereNull('datacuoi');
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
                        //Delay
                        ->when(strlen($delay_from), function ($q) use ($delay_from) {
                            //return $q->where('comment', '>=', $comment_from);
                            return $q->whereRaw('CAST(delay AS UNSIGNED) >= ?', [$delay_from]); 
                        })
                        ->when(strlen($delay_to), function ($q) use ($delay_to) {
                            return $q->whereRaw('CAST(delay AS UNSIGNED) <= ?', [$delay_to]);
                        })
                        // title
                        ->when(strlen($title), function ($q) use ($title) {
                            return $q->where('title', 'like', "%$title%");
                        })
                        // status
                        ->when(strlen($status_i), function ($q) use ($status_i) {
                            return $q->where('status', $status_i);
                        })
                        // user
                        ->when(strlen($user_i), function ($q) use ($user_i) {
                            return $q->where('user_id', $user_i) ;
                        })
                        // content
                        ->when(strlen($content_i), function ($q) use ($content_i) {
                            return $q->where('content', 'like', "%$content_i%");
                        })
                        //From-to
                        ->when(strlen($from), function ($q) use ($from) {
                            return $q->where(
                                'created_at',
                                '>=',
                                $from
                            );
                        })
                        //reaction real
                        ->when(strlen($data_reaction_from), function ($q) use ($data_reaction_from) {
                            //return $q->where('reaction_real', '>=' ,$data_reaction_from);
                            return $q->whereRaw('CAST(reaction_real AS UNSIGNED) >= ?', [$data_reaction_from]);
                        })
                        ->when(strlen($data_reaction_to), function ($q) use ($data_reaction_to) {
                            //return $q->where('reaction_real','<=' ,$data_reaction_to);
                            return $q->whereRaw('CAST(reaction_real AS UNSIGNED) <= ?', [$data_reaction_to]);
                        })
                        //view
                        ->when(strlen($view_from), function ($q) use ($view_from) {
                            return $q->whereRaw('CAST(view AS UNSIGNED) >= ?', [$view_from]);
                        })
                        ->when(strlen($view_to), function ($q) use ($view_to) {
                            return $q->whereRaw('CAST(view AS UNSIGNED) <= ?', [$view_to]);
                        })
                        ->when(strlen($to), function ($q) use ($to) {
                            return $q->where('created_at', '<=', $to . ' 23:59:59');
                        })
                        // link tn
                        ->when(strlen($linktn), function ($q) use ($linktn) {
                            return $q->where('linktn', '=', $linktn);
                        })
                            ->get()->toArray();
            $users = User::get()->toArray();
            // Chuyển danh sách user thành một mảng liên kết để tra cứu nhanh
            $user_lookup = [];
            foreach ($users as $user) {
                $user_lookup[$user['id']] = $user['name'];
            }
            
            // Mảng để lưu kết quả gộp tạm thời và theo dõi trạng thái
            $temp_result = [];
            $status_tracker = [];
            $issan_tracker = [];
            
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

            // Duyệt qua từng phần tử trong dữ liệu đầu vào
            foreach ($links as $entry) {
                $uid_post = $entry['link_or_post_id'];
                $parentid = $entry['parent_link_or_post_id'];
                $user_id = $entry['user_id'];
                $status = $entry['status'];
                $issan = $entry['is_scan'];
                $delay = $entry['delay'];
                $type = $entry['type'];
                $is_on_at = $entry['is_on_at'];
                $datacuoi = $entry['datacuoi'];
                $comment = $entry['comment'];
                $diff_comment = $entry['diff_comment'];
                $reaction = $entry['reaction'];
                $diff_reaction = $entry['diff_reaction'];
                $content = $entry['content'];
                $data = $entry['data'];
                $diff_data = $entry['diff_data'];
                $data_reaction = $entry['reaction_real'];
                $diff_data_reaction = $entry['diff_data_reaction'];
                $view = $entry['view'];
                $diff_view = $entry['diff_view'];
                $linktn = $entry['linktn'];

                // Xác định uid_post mục tiêu để gộp
                $target_uid_post = ($parentid === "" || $parentid === null) ? $uid_post : $parentid;
            
                // Nếu uid_post mục tiêu chưa có trong mảng kết quả tạm thời, khởi tạo phần tử mới
                if (!isset($temp_result[$target_uid_post])) {
                    $temp_result[$target_uid_post] = [
                        'link_or_post_id' => $target_uid_post,
                        'user_id' => [],
                        'parent_link_or_post_id' => [],
                        'is_scan' => 0,  // Mặc định là 0 và sẽ cập nhật sau
                        'status' => 0,  // Mặc định là 0 và sẽ cập nhật sau
                        'delay' => 0,
                        'type' => 0,
                    ];
                    $status_tracker[$target_uid_post] = [];
                    $issan_tracker[$target_uid_post] = [];
                }
            
                // Thêm user_id vào mảng user_id của phần tử tương ứng
                if (!in_array($user_id, $temp_result[$target_uid_post]['user_id'])) {
                    $temp_result[$target_uid_post]['user_id'][] = $user_id;
                }
                // Cập nhật trạng thái và theo dõi
                if ($status == 1) {
                    $temp_result[$target_uid_post]['status'] = 1;
                }
                if ($issan == 1) {
                    $temp_result[$target_uid_post]['is_scan'] = 1;
                }
                if ($type == 0) {
                    $temp_result[$target_uid_post]['type'] = 1;
                }
                $temp_result[$target_uid_post]['delay'] = $delay;
                $temp_result[$target_uid_post]['is_on_at'] = $is_on_at;
                $temp_result[$target_uid_post]['datacuoi'] = $datacuoi;

                $temp_result[$target_uid_post]['comment'] = $comment;
                $temp_result[$target_uid_post]['diff_comment'] = $diff_comment;
                $temp_result[$target_uid_post]['reaction'] = $reaction;
                $temp_result[$target_uid_post]['diff_reaction'] = $diff_reaction;

                $temp_result[$target_uid_post]['content'] = $content;

                $temp_result[$target_uid_post]['data'] = $data;
                $temp_result[$target_uid_post]['diff_data'] = $diff_data;

                $temp_result[$target_uid_post]['reaction_real'] = $data_reaction;
                $temp_result[$target_uid_post]['diff_data_reaction'] = $diff_data_reaction;

                $temp_result[$target_uid_post]['view'] = $view;
                $temp_result[$target_uid_post]['diff_view'] = $diff_view;

                $temp_result[$target_uid_post]['linktn'] = $linktn;
                
                $status_tracker[$target_uid_post][] = $status;
                $issan_tracker[$target_uid_post][] = $issan;
            }
            
            // Mảng để lưu kết quả cuối cùng
            $result = [];
            
            // Duyệt qua mảng tạm thời và loại bỏ các mục có tất cả các trạng thái là 0
            foreach ($temp_result as $uid_post => $entry) {
                if (in_array(1, $issan_tracker[$uid_post])) {
                    // Ghép tên user lại
                    $user_names = [];
                    foreach ($entry['user_id'] as $id) {
                        if (isset($user_lookup[$id])) {
                            $user_names[] = $user_lookup[$id];
                        }
                    }
                    $entry['user_id'] = implode('|', $user_names);
                    $result[] = $entry;
                }
            }
            return response()->json([
                'status' => 1,
                'links' => $result,
                'user' => "Oke",
            ]);

        }catch(Exception $ex){
            return response()->json([
                'status' => 0,
                'links' => var_dump($ex),
                'user' => "Error",
            ]);
        }
    }

    //Quang
    public function getAllLinkScanNewForUI(Request $request)
    {
        try{
            $links = Link::where('type', GlobalConstant::TYPE_SCAN)->get()->toArray();
            return response()->json([
                'status' => 1,
                'links' => $links,
                'user' => "Oke",
            ]);

        }catch(Exception $ex){
            return response()->json([
                'status' => 0,
                'links' => var_dump($ex),
                'user' => "Error",
            ]);
        }
    }
    //Quang
    public function getAllNewAPI(Request $request)
    {
        try{
            $links = Link::where('is_scan', 0)
            ->orWhere('is_scan', 1)->get()?->toArray();

            return response()->json([
                'status' => 1,
                'links' => $links,
                'user' => "Oke",
            ]);

        }catch(Exception $ex){
            return response()->json([
                'status' => 0,
                'links' => var_dump($ex),
                'user' => "Error",
            ]);
        }
    }
    public function updateParentID(Request $request){
        try{
            $link_or_post_id = $request->input('links.0.link_or_post_id');
            $parent_link_or_post_id = $request->input('links.0.parent_link_or_post_id');
    
            Link::where('link_or_post_id', $link_or_post_id)
                ->where(function ($query) {
                    $query->whereNull('parent_link_or_post_id')
                        ->orWhere('parent_link_or_post_id', '');
                })
                ->update(['parent_link_or_post_id' => $parent_link_or_post_id]);

            return response()->json([
                'status' => 0,
                'data' => $link_or_post_id . "|" .$parent_link_or_post_id
            ]);
        }catch(Exception $ex){
            return response()->json([
                'status' => -1,
                'data' => $link_or_post_id . "|" .$parent_link_or_post_id
            ]);
        }
    }
    public function updateLinkDie(Request $request){
        $result = "Update all";
        try{
            $parent_link_or_post_id = $request->input('links.0.parent_link_or_post_id');
            $user_id = $request->input('links.0.user_id');
            $is_scan = $request->input('links.0.is_scan');
            
            //= null sẽ update tất cả
            if(is_null($user_id) || $user_id == ''){
                Link::where('parent_link_or_post_id', $parent_link_or_post_id )->orwhere('link_or_post_id', $parent_link_or_post_id)
                ->update(['is_scan' => $is_scan]);
            }else{
            // != null sẽ update theo user => Dùng cho trường hợp add 2 link trùng nhau
            Link::where(function($query) use ($parent_link_or_post_id) {
                $query->where('parent_link_or_post_id', $parent_link_or_post_id)
                      ->orWhere('link_or_post_id', $parent_link_or_post_id);
            })
            ->where('user_id', $user_id)
            ->update(['is_scan' => $is_scan]);
            
                $result = "Update follow user";
            }
            

            return response()->json([
                'status' => 0,
                'data' => $parent_link_or_post_id  . "|" .$user_id. "|". $result
            ]);
        }catch(Exception $ex){
            return response()->json([
                'status' => -1,
                'data' => $parent_link_or_post_id  . "|" .$user_id. "|". $result
            ]);
        }
    }
    //Quang
    public function updateStatusLink(Request $request){
        $data = array_chunk($request['ids'], 200);
        $typeStatus = $request['status'];
        
        foreach ($data as $links) {
            Link::whereIn('link_or_post_id', $links)
                ->orWhereIn('parent_link_or_post_id', $links)
                ->update(['status' => $typeStatus]);
        }
        return response()->json([
            'status' => 0,
        ]);
    }

    public function updateLinkTN(Request $request){
        $status = $request['linktn'];
        $content = $request['content'];
        $link_or_post_id = $request['link_or_post_id'];
        
        $record = Link::where('link_or_post_id', $link_or_post_id)
            ->orWhere('parent_link_or_post_id', $link_or_post_id)->first();
        if(!is_null($record)){
            $record->linktn = $status;
            if(!is_null($content) && $content !== ''){
                $record->content = $content;
            }
            $record->save();
        }
        return response()->json([
            'status' => 0,
            'data'=> $link_or_post_id,
        ]);
    }

    public function updateDataLinkFilter(Request $request){

        $status = $request['status'];
        $type = $request['type'];
        $is_scan = $request['is_scan'];
        $delay = $request['delay'];
        $adsstatus = $request['adsstatus'];
        $link_or_post_id = $request['link_or_post_id'];
        
        Link::where('link_or_post_id', $link_or_post_id)
            ->orWhere('parent_link_or_post_id', $link_or_post_id)
            ->update(
                [
                    'status' => $status,
                    'type' => $type,
                    'is_scan' => $is_scan,
                    'delay' => $delay,
                    'adsstatus' => $adsstatus
                ]
            );

        return response()->json([
            'status' => 0,
            'data'=> $link_or_post_id,
        ]);
    }

    public function updateDataCuoiLink(Request $request){
        $data = $request->validate([
            'links' => 'nullable|array',
            'links.*.parent_link_or_post_id' => 'nullable|string',
            'links.*.data_cuoi' => 'nullable|string',
        ]);
        $count = 0;
        foreach ($data['links'] as $key => $value) {
            try{
                $parent_link_or_post_id = $value['parent_link_or_post_id'];
                $data_cuoi = $value['data_cuoi'];

                Link::where(function($query) use ($parent_link_or_post_id) {
                    $query->where('link_or_post_id', $parent_link_or_post_id)
                          ->orWhere('parent_link_or_post_id', $parent_link_or_post_id);
                })
                ->where(function($query) use ($data_cuoi) {
                    $query->where('datacuoi', '<', $data_cuoi)
                          ->orWhereNull('datacuoi')
                          ->orWhere('datacuoi', '');
                })
                ->update(['datacuoi' => $data_cuoi]);
                $count++;
            }catch(Exception $ex){}
        }
        return response()->json([
            'status' => 0,
            'message' => $count.'/'.count($data['links'])
        ]);
    }
    public function apiCheckData(Request $request){
        return response()->json([
            'status' => 0,
            'message' => $request->uid
        ]);
    }
    // public function getAllUsersByLinkOrPostId(string $link_or_post_id)
    // {
    //     $links = Link::with(['user', 'childLinks.user', 'isOnUserLinks.user'])
    //     ->where('link_or_post_id', $link_or_post_id)
    //     ->get();
    //     foreach ($links as $value) {
    //         if (strlen($value['parent_link_or_post_id'] ?? '')) {
    //             $value = $value['parent_link'];
    //         }
    //         $account = [];
    //         foreach ($value['is_on_user_links'] as $is_on_user_link) {
    //             $account[] = $is_on_user_link['user'];
    //         }
    //         // foreach ($value['child_links'] as $childLink) {
    //         //     foreach ($childLink['is_on_user_links']  as $is_on_user_link) {
    //         //         $account[$is_on_user_link['id']] = $is_on_user_link;
    //         //     }
    //         // }
    //         $result_links[$value['link_or_post_id']] = [
    //             ...$value,
    //             'accounts' => collect($account)->values()
    //         ];
    //     }

    //     return $links;
    // }

    public function getByType(Request $request)
    {
        return response()->json([
            'status' => 0,
            'links' => Link::where('type', $request->type)->get()
        ]);
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'links' => 'nullable|array',
            'links.*.title' => 'nullable|string',
            'links.*.time' => 'nullable|string',
            'links.*.content' => 'nullable|string',
            'links.*.comment' => 'nullable|string',
            'links.*.diff_comment' => 'nullable|string',
            'links.*.data' => 'nullable|string',
            'links.*.diff_data' => 'nullable|string',
            'links.*.reaction' => 'nullable|string',
            'links.*.diff_reaction' => 'nullable|string',
            'links.*.is_scan' => 'nullable|in:0,1,2',
            'links.*.note' => 'nullable|string',
            'links.*.image' => 'nullable|string',
            'links.*.delay' => 'nullable|string',
            'links.*.link_or_post_id' => 'required|string',
            'links.*.parent_link_or_post_id' => 'nullable|string',
            'links.*.end_cursor' => 'nullable|string',
            'links.*.type' => 'required|in:0,1,2',
        ]);

        $count = 0;
        $error = [
            'link_or_post_id' => [],
        ];
        $dataInsert = [];
        foreach ($data['links'] as $value) {
            $link = Link::with(['childLinks'])->firstWhere('link_or_post_id', $value['link_or_post_id']);
            if ($link) {
                if (!in_array($value['link_or_post_id'], $error['link_or_post_id'])) {
                    $error['link_or_post_id'][] = $value['link_or_post_id'];
                }
                continue;
            }
            $value['created_at'] = date('Y-m-d H:i:s');
            $value['updated_at'] = date('Y-m-d H:i:s');
            $dataInsert[] = $value;
            $count++;
        }

        Link::insert($dataInsert);
        $all = count($data['links']);

        return response()->json([
            'status' => 0,
            'rate' => "$count/$all",
            'error' => $error
        ]);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'links' => 'required|array',
                'links.*.title' => 'nullable|string',
                'links.*.time' => 'nullable|string',
                'links.*.content' => 'nullable|string',
                'links.*.comment' => 'nullable|string',
                'links.*.diff_comment' => 'nullable|string',
                'links.*.data' => 'nullable|string',
                'links.*.diff_data' => 'nullable|string',
                'links.*.reaction' => 'nullable|string',
                'links.*.diff_reaction' => 'nullable|string',
                'links.*.is_scan' => 'nullable|in:0,1,2',
                'links.*.note' => 'nullable|string',
                'links.*.image' => 'nullable|string',
                'links.*.delay' => 'nullable|string',
                'links.*.link_or_post_id' => 'required|string',
                'links.*.parent_link_or_post_id' => 'nullable|string',
                'links.*.end_cursor' => 'nullable|string',
                'links.*.type' => 'required|in:0,1,2',
            ]);

            $count = 0;
            $error = [
                'link_or_post_id' => [],
            ];
            DB::beginTransaction();
            foreach ($data['links'] as $value) {
                $link = Link::with(['childLinks'])->firstWhere('link_or_post_id', $value['link_or_post_id']);
                if ($link) {
                    if (!in_array($value['link_or_post_id'], $error['link_or_post_id'])) {
                        $error['link_or_post_id'][] = $value['link_or_post_id'];
                    }
                    continue;
                }
                $value['created_at'] = date('Y-m-d H:i:s');
                $value['updated_at'] = date('Y-m-d H:i:s');
                Link::create($value);
                $count++;
            }
            $all = count($data['links']);
            DB::commit();

            return response()->json([
                'status' => 0,
                'rate' => "$count/$all",
                'error' => $error
            ]);
        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => 1,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateLinkByLinkOrPostIdAndUserId(Request $request)
    {
        try {
            $data = $request->validate([
                'links' => 'required|array',
                'links.*.link_or_post_id' => 'required|string',
                'links.*.user_id' => 'required|string',
                'links.*.parent_link_or_post_id' => 'nullable|string',
                'links.*.title' => 'nullable|string',
                'links.*.time' => 'nullable|string',
                'links.*.content' => 'nullable|string',
                'links.*.comment' => 'nullable|string',
                'links.*.data' => 'nullable|string',
                'links.*.reaction' => 'nullable|string',
                'links.*.is_scan' => 'nullable|in:0,1,2',
                'links.*.status' => 'nullable|in:0,1',
                'links.*.note' => 'nullable|string',
                'links.*.image' => 'nullable|string',
                'links.*.delay' => 'nullable|string',
                'links.*.end_cursor' => 'nullable|string',
                'links.*.type' => 'nullable|in:0,1,2',
            ]);

            DB::beginTransaction();

            $count = 0;
            $error = [
                'link_or_post_id' => [],
            ];
            foreach ($data['links'] as $key => &$value) {
                $link = Link::with(['childLinks'])
                    ->where('link_or_post_id', $value['link_or_post_id'])
                    ->where('user_id', $value['user_id'])
                    ->first();
                if (!$link) {
                    if (!in_array($value['link_or_post_id'], $error['link_or_post_id'])) {
                        $error['link_or_post_id'][] = $value['link_or_post_id'];
                    }
                    continue;
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
                unset($value['user_id']);
                // update link
                $link->update($value);
                //
                $count++;
            }

            DB::commit();
            $all = count($data['links']);

            return response()->json([
                'status' => 0,
                'rate' => "$count/$all",
                'error' => $error
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
        Just for external calling
    **/
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
                'links.*.image' => 'nullable|string',
                'links.*.delay' => 'nullable|string',
                'links.*.end_cursor' => 'nullable|string',
                'links.*.type' => 'nullable|in:0,1,2',
            ]);

            DB::beginTransaction();

            $count = 0;
            $error = [
                'link_or_post_id' => [],
            ];
            foreach ($data['links'] as $key => &$value) {
                $link = Link::with(['childLinks'])->firstWhere('link_or_post_id', $value['link_or_post_id']);
                if (!$link) {
                    if (!in_array($value['link_or_post_id'], $error['link_or_post_id'])) {
                        $error['link_or_post_id'][] = $value['link_or_post_id'];
                    }
                    continue;
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
                $link_or_post_id = $value['parent_link_or_post_id'] ?? '';
                $link->update($value);
                // update other links which is same link_or_post_id
                Link::where('link_or_post_id', $link->link_or_post_id)
                    ->update($value);
                if (strlen($link_or_post_id)) {
                    $value['parent_link_or_post_id'] = '';
                    Link::updateOrCreate(['link_or_post_id' => $link_or_post_id], $value);
                }
                $value['created_at'] = date('Y-m-d H:i:s');
                $value['updated_at'] = date('Y-m-d H:i:s');
                unset($value['parent_link_or_post_id']);
                if ($childLinks) {
                    foreach ($childLinks as $childLink) {
                        $childLink->update($value);
                    }
                }
                //
                $count++;
            }

            DB::commit();
            $all = count($data['links']);

            return response()->json([
                'status' => 0,
                'rate' => "$count/$all",
                'error' => $error
            ]);
        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => 1,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateCount(Request $request)
    {
        try {
            $data = $request->validate([
                'links' => 'required|array',
                'links.*.link_or_post_id' => 'required|string',
                'links.*.comment' => 'nullable|string',
                'links.*.reaction' => 'nullable|string',
                'links.*.content' => 'nullable|string',
                'links.*.image' => 'nullable|string',
                'links.*.view' => 'nullable|string',
            ]);

            //DB::beginTransaction();

            $count = 0;
            $error = [
                'link_or_post_id' => [],
            ];
            foreach ($data['links'] as $key => &$value) 
            {
                try{
                    $countReaction = $value['reaction'];
                    $countComment = $value['comment'];
                    $image = $value['image'] ?? '';
                    $countView = $value['view'] ?? 0;
                    $link_uid_or_post = $value['link_or_post_id'];
                    $error['link_or_post_id'][] = $link_uid_or_post;

                    //Update history
                    $lastHistory = LinkHistory::where('link_id','like', "%$link_uid_or_post%")
                        ->where('type', GlobalConstant::TYPE_COMMENT)
                        ->orderByDesc('id')
                        ->first();

                    $count_comment_data = Comment::where('link_or_post_id', $link_uid_or_post)->get()->count();
                    $count_reaction_data = Reaction::where('link_or_post_id', $link_uid_or_post)->get()->count();

                    $diff_reaction = $lastHistory?->reaction ? ((int)$countReaction - (int)$lastHistory->reaction) : (int)$countReaction;
                    $diff_comment = $lastHistory?->comment ? ((int)$countComment - (int)$lastHistory->comment) : (int)$countComment;
                    $diff_view = $lastHistory?->view ? ((int)$countView - (int)$lastHistory->view) : (int)$countView;
                    
                    $diff_data_comment = $lastHistory?->data ? $count_comment_data - (int)$lastHistory->data : $count_comment_data;
                    $diff_data_reaction = $lastHistory?->data_reaction ? $count_reaction_data - (int)$lastHistory->data_reaction : $count_reaction_data;

                    LinkHistory::create([
                        'reaction' => $countReaction,
                        'diff_reaction' => $diff_reaction,
                        'link_id' => $link_uid_or_post,
                        'type' => GlobalConstant::TYPE_COMMENT,
                        'comment' => $countComment,
                        'diff_comment' => $diff_comment,
                        'view' => $countView,
                        'diff_view' => $diff_view,
                        'data' => $count_comment_data,
                        'diff_data' => $diff_data_comment,
                        'reaction_real' => $count_reaction_data,
                        'diff_data_reaction' => $diff_data_reaction,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                    $records = Link::where('link_or_post_id','like', "%$link_uid_or_post%")
                    ->orWhere('parent_link_or_post_id','like', "%$link_uid_or_post%")
                    ->get();
                    
                    //Update title and content of link
                    foreach ($records as $record) {
                        $diffcmt = (int)$countComment - (int)$record->comment;
                        $diffreac = (int)$countReaction - (int)$record->reaction;
                        $diffview = (int)$countView - (int)$record->view;
                        
                        if((int)$countComment != 0){
                            $record->comment = $countComment;
                            $record->diff_comment = $diffcmt;
                        }
                        if((int)$countReaction != 0){
                            $record->reaction = $countReaction;
                            $record->diff_reaction = $diffreac;
                        }
                        if((int)$countView != 0){
                            $record->diff_view = $diffview;
                            $record->view = $countView;
                        }

                        $record->data = $count_comment_data;
                        $record->diff_data = $diff_data_comment;

                        $record->reaction_real = $count_reaction_data;
                        $record->diff_data_reaction = $diff_data_reaction;

                        if (is_null($record->image) || $record->image === '') {
                            $record->image = $image;
                        }
                        $record->save();
                    }
                    $count++;
                }catch(Exception $ex){
                    $error['link_or_post_id'][] = $ex->getMessage();
                }
            }

            //DB::commit();
            $all = count($data['links']);

            return response()->json([
                'status' => 0,
                'rate' => "$count/$all",
                'error' => $error
            ]);
        } catch (Throwable $e) {
            //DB::rollBack();

            return response()->json([
                'status' => 1,
                'message' => $e->getMessage()
            ]);
        }
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
                'image' => 'nullable|string',
                'end_cursor' => 'nullable|string',
                'delay' => 'nullable|string',
                // 'link_or_post_id' => 'nullable|string',
                'parent_link_or_post_id' => 'nullable|string',
                'type' => 'nullable|in:0,1,2',
                'user_id' => 'nullable|integer',
            ]);

            unset($data['id']);
            $link = Link::with(['userLinks.user'])->firstWhere('id', $request->input('id'));
            $link->update($data);
            // $link->userLinks()->update([
            //     'is_scan' => $data['is_scan'],
            //     'title' => $data['title'],
            //     'type' => $data['type'],
            //     'note' => $data['note'],
            // ]);

            // sync point to link before update link
            // if (!empty($data['parent_link_or_post_id'])) {
            //     $this->syncPointToLinkBeforeUpdateLink($link->id, $data['parent_link_or_post_id']);
            // }

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
        Using for update link by list ids in admin/linkrunning
    **/
    public function updateLinkByListLinkId(Request $request)
    {
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
            'image' => 'nullable|string',
            'delay' => 'nullable|string',
            'end_cursor' => 'nullable|string',
            'parent_link_or_post_id' => 'nullable|string',
            // 'link_or_post_id' => 'nullable|string',
            'type' => 'nullable|in:0,1,2',
            'user_id' => 'nullable|integer',
        ]);

        $links = Link::with(['childLinks'])
            ->whereIn('id', $data['ids']);
        if ($links->get()->count() === 0) {
            throw new Exception('Link không tồn tại');
        }
        unset($data['ids'], $data['user_id']);
        $links->update($data);
        unset($data['parent_link_or_post_id']);
        foreach ($links->get() as $link) {
            $childLinks = $link?->childLinks;
            if ($childLinks) {
                foreach ($childLinks as $childLink) {
                    $childLink->update($data);
                }
            }
        }

        // sync point to link before update link
        // if (!empty($data['parent_link_or_post_id'])) {
        //     foreach ($links as $link) {
        //         $this->syncPointToLinkBeforeUpdateLink($link->id, $data['parent_link_or_post_id']);
        //     }
        // }

        return response()->json([
            'status' => 0,
        ]);
    }

    public function updateDelayLink(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array',
            'delay' => 'nullable|string',
        ]);
        try{
            Link::with(['childLinks'])
            ->whereIn('parent_link_or_post_id', $data['ids'])
            ->orWhereIn('link_or_post_id', $data['ids'])
            ->update(['delay' => $data['delay']]);
            
        }catch(Exception $ex){

        }
        
        return response()->json([
            'status' => 0,
        ]);
    }

    //Quang
    public function updateStatusByParentID(Request $request)
    {
        $result = "Update all";
        try{
            $parent_link_or_post_id = $request['ids'];
            $status = $request['status'];
            
            Link::where('parent_link_or_post_id', $parent_link_or_post_id )-> orwhere('link_or_post_id', $parent_link_or_post_id)
            ->update(
                [
                    'status' => $status,
                    'is_on_at' => date('Y-m-d H:i:s')
                ]);

            return response()->json([
            'status' => 0,
            'change stt' => $status
            ]);

        }catch(Exception $ex){
            return response()->json([
                'status' => -1,
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $link = Link::with(['childLinks'])->firstWhere('id', $id);
            if ($link->childLinks()->count()) {
                $newParentLink = $link->childLinks()->first();
                foreach ($link->childLinks() as $childLink) {
                    $childLink->update([
                        'parent_link_or_post_id' => $newParentLink->link_or_post_id ?? '',
                    ]);
                }
                $newParentLink->update([
                    'parent_link_or_post_id' => '',
                ]);
            }
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

    public function deleteAll(Request $request)
    {
        try {
            DB::beginTransaction();
            $links =  Link::with(['childLinks'])->whereIn('id', $request->ids)->orderBy('id');
            foreach ($links->get() as $link) {
                if ($link->childLinks()->count()) {
                    $newParentLink = $link->childLinks()->first();
                    foreach ($link->childLinks() as $childLink) {
                        $childLink->update([
                            'parent_link_or_post_id' => $newParentLink->link_or_post_id ?? '',
                        ]);
                    }
                    $newParentLink->update([
                        'parent_link_or_post_id' => '',
                    ]);
                }
            }
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

    public function deleteAllUserLink(Request $request)
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

    public function index()
    {
        $links = Link::get()?->toArray() ?? [];

        // $result_links = [];
        // foreach ($links as $value) {
        //     if (strlen($value['parent_link_or_post_id'] ?? '')) {
        //         $value = $value['parent_link'];
        //     }
        //     $result_links[$value['link_or_post_id']] = $value;
        // }

        return response()->json([
            'status' => 0,
            'links' => $links,
        ]);
    }

    public function getAllLink()
    {
        return response()->json([
            'status' => 0,
            'links' => Link::with([
                'parentLink.childLinks',
                'parentLink.userLinks',
                'parentLink.isFollowTypeUserLinks',
                'childLinks',
                'userLinks',
                'isOnUserLinks',
                'isFollowTypeUserLinks',
            ])
                ->get(),
        ]);
    }

    public function deleteAllByListLinkOrPostId(Request $request)
    {
        try {
            DB::beginTransaction();
            $links =  Link::with(['childLinks'])->whereIn('link_or_post_id', $request->link_or_post_id)->orderBy('id');
            foreach ($links->get() as $link) {
                if ($link->childLinks()->count()) {
                    $newParentLink = $link->childLinks()->first();
                    foreach ($link->childLinks() as $childLink) {
                        $childLink->update([
                            'parent_link_or_post_id' => $newParentLink->link_or_post_id ?? '',
                        ]);
                    }
                    $newParentLink->update([
                        'parent_link_or_post_id' => '',
                    ]);
                }
            }
            Link::whereIn('link_or_post_id', $request->link_or_post_id)->delete();

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
