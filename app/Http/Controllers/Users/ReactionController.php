<?php

namespace App\Http\Controllers\Users;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\LinkReaction;
use App\Models\Reaction;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ReactionController extends Controller
{
    public function getAll(Request $request)
    {
        $user_id = $request->user_id;
        $reaction_id = $request->reaction_id;
        $to = $request->to;
        $from = $request->from;

        return response()->json([
            'status' => 0,
            'reactions' => LinkReaction::with(['reaction', 'link.userLinks.user'])
                ->when($user_id, function ($q) use ($user_id) {
                    return $q->whereHas('link.userLinks', function ($q) use ($user_id) {
                        $q->where('user_id', $user_id);
                    });
                })
                ->when($to, function ($q) use ($to) {
                    return $q->whereHas('reaction', function ($q) use ($to) {
                        $q->where('created_at', '<=', $to);
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
                ->orderByDesc('id')
                ->get()
        ]);
    }

    public function create()
    {
        return view('user.reaction.add', [
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
                'reactions.*.note' => 'nullable|string',
            ]);
            DB::beginTransaction();
            foreach ($data['reactions'] as $key => $value) {
                $link = Link::firstWhere('link_or_post_id', $value['link_or_post_id']);
                if (!$link) {
                    throw new Exception('link_or_post_id không tồn tại');
                }
                unset($value['link_or_post_id']);
                $reaction = Reaction::create($value);
                LinkReaction::create([
                    'link_id' => $link->id,
                    'reaction_id' => $reaction->id,
                ]);
            }
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

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer',
            'title' => 'nullable|string',
            'reaction' => 'nullable|string',
            'data' => 'nullable|numeric',
            'emotion' => 'nullable|numeric',
            'note' => 'nullable|string',
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

            $reactions = Reaction::orderByDesc('id')
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

        return view('user.reaction.list', [
            'title' => 'Danh sách cảm xúc',
        ]);
    }

    public function show($id)
    {
        return view('user.reaction.edit', [
            'title' => 'Chi tiết cảm xúc',
            'reaction' => Reaction::firstWhere('id', $id)
        ]);
    }

    public function destroy($id)
    {
        try {
            $link = Reaction::firstWhere('id', $id);
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
