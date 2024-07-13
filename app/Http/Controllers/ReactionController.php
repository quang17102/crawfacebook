<?php

namespace App\Http\Controllers;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\LinkHistory;
use App\Models\LinkReaction;
use App\Models\Reaction;
use App\Models\Uid;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

use Toastr;

class ReactionController extends Controller
{
    public function getAllReactionUser(Request $request)
    {
        $user_id = $request->user_id;
        $reaction_id = $request->reaction_id;
        $to = $request->to;
        $from = $request->from;
        $reaction = $request->reaction;
        $user = $request->user;
        $uid = $request->uid;
        $note = $request->note;
        $phone = $request->phone;
        $title = $request->title;
        $name_facebook = $request->name_facebook;
        $today = $request->today;
        $limit = $request->limit;
        $ids = $request->ids ?? [];
        $link_or_post_id = is_numeric($request->link_or_post_id) ? $request->link_or_post_id : $this->getLinkOrPostIdFromUrl($request->link_or_post_id ?? '');

        $list_link_ids = Link::all();

        $list_link_of_user = Link::with(['userLinks'])
            ->when($user_id, function ($q) use ($user_id) {
                return $q->whereHas('userLinks', function ($q) use ($user_id) {
                    $q->where('user_id', $user_id);
                });
            })
            ->get()
            ->pluck('link_or_post_id')
            ->toArray();

        foreach ($list_link_ids as $link) {
            if (in_array($link->parent_link_or_post_id, $list_link_of_user)) {
                $list_link_of_user = array_diff($list_link_of_user, [$link->parent_link_or_post_id]);
                $list_link_of_user[] =  $link->link_or_post_id;
            }
        }

        $list_link_of_user = array_unique($list_link_of_user);

        $reactions = LinkReaction::with(['reaction.getUid', 'link.userLinks.user'])
            ->whereHas('link', function ($q) use ($list_link_of_user) {
                $q->whereIn('link_or_post_id', $list_link_of_user);
            })
            ->when($to, function ($q) use ($to) {
                return $q->whereHas('reaction', function ($q) use ($to) {
                    $q->where('created_at', '<=', $to . ' 23:59:59');
                });
            })
            ->when($from, function ($q) use ($from) {
                return $q->whereHas('reaction', function ($q) use ($from) {
                    $q->where(
                        'created_at',
                        '>=',
                        $from
                    );
                });
            })
            ->when($reaction_id, function ($q) use ($reaction_id) {
                return $q->where('reaction_id', $reaction_id);
            })
            // user
            ->when($user, function ($q) use ($user) {
                return $q->whereHas('link.userLinks', function ($q) use ($user) {
                    $q->where('user_id', $user);
                });
            })
            // today
            ->when($today, function ($q) use ($today) {
                return $q->whereHas('reaction', function ($q) use ($today) {
                    $q->where('created_at', 'like', "%$today%");
                });
            })
            // title
            ->when($title, function ($q) use ($title) {
                return $q->whereHas('reaction', function ($q) use ($title) {
                    $q->where('title', 'like', "%$title%");
                });
            })
            // link_or_post_id
            ->when($link_or_post_id, function ($q) use ($link_or_post_id) {
                return $q->whereHas('link', function ($q) use ($link_or_post_id) {
                    $q->where('link_or_post_id', 'like', "%$link_or_post_id%");
                });
            })
            // name_facebook
            ->when($name_facebook, function ($q) use ($name_facebook) {
                return $q->whereHas('reaction', function ($q) use ($name_facebook) {
                    $q->where('name_facebook', 'like', "%$name_facebook%");
                });
            })
            // note
            ->when($note, function ($q) use ($note) {
                return $q->whereHas('reaction', function ($q) use ($note) {
                    $q->where('note', 'like', "%$note%");
                });
            })
            // reaction
            ->when($reaction, function ($q) use ($reaction) {
                return $q->whereHas('reaction', function ($q) use ($reaction) {
                    $q->where('reaction', 'like', "%$reaction%");
                });
            })
            // phone
            ->when($phone, function ($q) use ($phone) {
                return $q->whereHas('reaction.getUid', function ($q) use ($phone) {
                    $q->where('phone', 'like', "%$phone%");
                });
            })
            // uid
            ->when($uid, function ($q) use ($uid) {
                return $q->whereHas('reaction', function ($q) use ($uid) {
                    $q->where('uid', 'like', "%$uid%");
                });
            })
            // ids
            ->when(count($ids), function ($q) use ($ids) {
                $q->whereIn('id', $ids);
            })
            // order
            ->orderByDesc('created_at');

        // limit
        if ($limit) {
            $reactions = $reactions->limit($limit);
        }

        return response()->json([
            'status' => 0,
            'reactions' => $reactions->get()
        ]);
    }

    public function getAll(Request $request)
    {
        $user_id = $request->user_id;
        $reaction_id = $request->reaction_id;
        $to = $request->to;
        $from = $request->from;
        $reaction = $request->reaction;
        $user = $request->user;
        $uid = $request->uid;
        $note = $request->note;
        $phone = $request->phone;
        $link_or_post_id = $request->link_or_post_id;
        $title = $request->title;
        $name_facebook = $request->name_facebook;
        $today = $request->today;
        $limit = $request->limit;
        $ids = $request->ids ?? [];

        $links = Link::with(['userLinks', 'parentLink'])
            ->when($user_id, function ($q) use ($user_id) {
                return $q->where('user_id', $user_id);
            })
            ->when($user, function ($q) use ($user) {
                return $q->where('user_id', $user);
            })
            ->get();

        $list_link_of_user = [];
        foreach ($links as $key => $link) {
            $tmp_link_or_post_id = $link?->parentLink ? $link->parentLink->link_or_post_id : $link->link_or_post_id;
            if (!in_array($tmp_link_or_post_id, $list_link_of_user)) {
                $list_link_of_user[] = $tmp_link_or_post_id;
            }
        }

        $reactions = Reaction::with([
            'link',
            'link.user',
            'link.userLinks.user',
            'link.userLinks.user',
            'link.childLinks.user',
            'link.parentLink.user',
            'link.childLinks.userLinks.user',
            'link.parentLink.userLinks.user',
            'link.parentLink.childLinks.user'
        ])
            // default
            // ->whereHas('link', function ($q) use ($list_link_of_user) {
            //     $q->whereIn('link_or_post_id', $list_link_of_user);
            // })
            // to
            ->when(strlen($to), function ($q) use ($to) {
                return $q->where('created_at', '<=', $to . ' 23:59:59');
            })
            // from
            ->when(strlen($from), function ($q) use ($from) {
                return $q->where(
                    'created_at',
                    '>=',
                    $from
                );
            })
            // reaction_id
            ->when(strlen($reaction_id), function ($q) use ($reaction_id) {
                return $q->where('id', $reaction_id);
            })
            // today
            ->when(strlen($today), function ($q) use ($today) {
                return $q->where('created_at', 'like', "%$today%");
            })
            // title
            ->when(strlen($title), function ($q) use ($title) {
                return $q->whereHas('link', function ($q) use ($title) {
                    $q->where('title', 'like', "%$title%");
                });
            })
            // link_or_post_id
            ->when(strlen($link_or_post_id), function ($q) use ($link_or_post_id) {
                return $q->whereHas('link', function ($q) use ($link_or_post_id) {
                    $q->where('link_or_post_id', 'like', "%$link_or_post_id%");
                });
            })
            // name_facebook
            ->when(strlen($name_facebook), function ($q) use ($name_facebook) {
                return $q->where('name_facebook', 'like', "%$name_facebook%");
            })
            // note
            ->when(strlen($note), function ($q) use ($note) {
                return $q->where('note', 'like', "%$note%");
            })
            // reaction
            ->when(strlen($reaction), function ($q) use ($reaction) {
                return $q->where('reaction', 'like', "%$reaction%");
            })
            // phone
            ->when(strlen($phone), function ($q) use ($phone) {
                return $q->whereHas('getUid', function ($q) use ($phone) {
                    $q->where('phone', 'like', "%$phone%");
                });
            })
            // uid
            ->when(strlen($uid), function ($q) use ($uid) {
                return $q->where('uid', 'like', "%$uid%");
            })
            // ids
            ->when(count($ids), function ($q) use ($ids) {
                $q->whereIn('id', $ids);
            })
            // order
            ->orderByDesc('created_at');

        // limit
        // if ($limit) {
            
        // }
        $reactions = $reactions->limit(1000);
        $reactions = $reactions->get()?->toArray() ?? [];;
        $result_reactions = [];
        foreach ($reactions as $value) {
            $link = $value['link'];
            if (strlen($value['link']['parent_link_or_post_id'] ?? '')) {
                $link = $value['link']['parent_link'];
            }
            $account = [];
            if (!empty($link['user']['name'])) {
                $account[] = $link['user']['name'];
            }
            // foreach ($link['user_links'] as $is_on_user_link) {
            //     $account[$is_on_user_link['id']] = $is_on_user_link;
            // }
            foreach ($link['child_links'] ?? [] as $childLink) {
                if (!empty($childLink['user']['name']) && !in_array($childLink['user']['name'], $account)) {
                    $account[] = $childLink['user']['name'];
                }
            }
            $result_reactions[] = [
                ...$value,
                'accounts' => collect($account)->values()
            ];
        }

        return response()->json([
            'status' => 0,
            'reactions' => $result_reactions
        ]);
    }

    public function create()
    {
        return view('admin.reaction.add', [
            'title' => 'Thêm cảm xúc'
        ]);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'reactions' => 'nullable|array',
                'reactions.*.link_or_post_id' => 'required|string',
                'reactions.*.title' => 'nullable|string',
                'reactions.*.uid' => 'nullable|string',
                'reactions.*.phone' => 'nullable|string',
                'reactions.*.reaction' => 'nullable|string',
                'reactions.*.name_facebook' => 'nullable|string',
                'reactions.*.note' => 'nullable|string',
            ]);
            DB::beginTransaction();
            $count = 0;
            $unique_link_ids = [];
            $uids = [];
            $error = [
                'uid' => [],
                'link_or_post_id' => [],
            ];
            foreach ($data['reactions'] as $key => $value) {
                $link = Link::firstWhere('link_or_post_id', $value['link_or_post_id']);
                if (!$link) {
                    if (!in_array($value['link_or_post_id'], $error['link_or_post_id'])) {
                        $error['link_or_post_id'][] = $value['link_or_post_id'];
                    }
                    // throw new Exception('Không tồn tại link_or_post_id');
                    continue;
                }
                $count_uid = Reaction::with(['link'])
                    ->where('link_or_post_id', $link->link_or_post_id)
                    ->when(strlen($value['uid'] ?? ''), function ($q) use ($value) {
                        return $q->where('uid', $value['uid']);
                    })
                    ->get()
                    ->count();
                if ($count_uid) {
                    if (!in_array($value['uid'], $error['uid'])) {
                        $error['uid'][] = $value['uid'];
                    }
                    continue;
                }

                $unique_link_ids[$link->id] = $link;
                $reaction = Reaction::create($value);
                // get data phone
                $pattern = '/\d{10,11}/';
                preg_match_all($pattern, $reaction->phone, $matches);
                $uids[$reaction->uid][] = implode(',', $matches[0]);
                $count++;
            }

            if ($count) {
                // insert uids
                foreach ($uids as $key => $value_uid) {
                    $value_uid = array_filter($value_uid);
                    $uid = Uid::firstWhere('uid', $key);
                    if (!$uid) {
                        Uid::create([
                            'uid' => $key,
                            'phone' => implode(',', $value_uid),
                        ]);
                    } else {
                        DB::table('uids')
                            ->where('uid', (string)$key)
                            ->update([
                                'phone' => count($value_uid) ? $uid->phone . ',' . implode(',', $value_uid) : $uid->phone,
                            ]);
                    }
                }
                // update column reaction of link
                $dataLinks = [];
                foreach ($unique_link_ids as $link) {
                    $count_reaction = Reaction::where('link_or_post_id', $link->link_or_post_id)
                        ->get()
                        ->count();
                    // get history
                    $lastHistory = LinkHistory::with(['link'])
                        ->where('type', GlobalConstant::TYPE_REACTION)
                        ->where('link_id', $link->id)
                        ->orderByDesc('id')
                        ->first();
                    //
                    $diff_reaction = $lastHistory?->reaction ? $count_reaction - (int)$lastHistory->reaction : $count_reaction;
                    //
                    Link::firstWhere('id', $link->id)
                        ->update([
                            'reaction' => $count_reaction,
                            'diff_reaction' => $diff_reaction,
                        ]);
                    //
                    $dataLinks[] = [
                        'reaction' => $count_reaction,
                        'diff_reaction' => $diff_reaction,
                        'link_id' => $link->id,
                        'type' => GlobalConstant::TYPE_REACTION,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                }
                LinkHistory::insert($dataLinks);
            }
            DB::commit();
            $all = count($data['reactions']);

            return response()->json([
                'status' => 0,
                'rate' => "$count/$all",
                'error' => $error
            ]);
        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => 1,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer',
            'title' => 'nullable|string',
            'reaction' => 'nullable|string',
            'data' => 'nullable|numeric',
            'emotion' => 'nullable|numeric',
            'note' => 'nullable|string',
            'name_facebook' => 'nullable|string',
            'link_or_post_id' => 'required|string'
        ]);
        unset($data['id']);
        $update = Reaction::where('id', $request->input('id'))->update($data);

        if ($update) {
            Toastr::success(__('message.success.update'), __('title.toastr.success'));
        } else Toastr::error(__('message.fail.update'), __('title.toastr.fail'));

        return redirect()->back();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $from = $request->from ?? '';
            $to = $request->to ?? '';

            $reactions = Reaction::with(['link.userLinks.user'])
                ->orderByDesc('id')
                ->when($from, function ($q) use ($from) {
                    return $q->where('created_at', '>=', $from);
                })
                ->when($to, function ($q) use ($to) {
                    return $q->where('created_at', '<=', $to);
                })
                ->get();

            return response()->json([
                'status' => 0,
                'reactions' => $reactions
            ]);
        }

        return view('admin.reaction.list', [
            'title' => 'Danh sách cảm xúc',
        ]);
    }

    public function show($id)
    {
        return view('admin.reaction.edit', [
            'title' => 'Chi tiết cảm xúc',
            'reaction' => Reaction::firstWhere('id', $id)
        ]);
    }

    public function destroy($id)
    {
        $reaction = Reaction::firstWhere('id', $id);
        if (!$reaction) {
            throw new Exception('Cảm xúc không tồn tại');
        }
        $reaction->delete();

        return response()->json([
            'status' => 0,
        ]);
    }

    public function deleteAll(Request $request)
    {
        try {
            DB::beginTransaction();
            Reaction::whereIn('id', $request->ids)->delete();

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
