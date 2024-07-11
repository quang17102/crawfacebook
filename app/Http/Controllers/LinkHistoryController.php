<?php

namespace App\Http\Controllers;

use App\Constant\GlobalConstant;
use App\Models\LinkHistory;
use Illuminate\Http\Request;

class LinkHistoryController extends Controller
{
    public function getAll(Request $request)
    {
        $user_id = $request->user_id;
        $to = $request->to;
        $from = $request->from;
        $link_or_post_id = $request->link_or_post_id;

        return response()->json([
            'status' => 0,
            'histories' => LinkHistory::with(['link.userLinks.user'])
                ->when($user_id, function ($q) use ($user_id) {
                    return $q->whereHas('link.userLinks', function ($q) use ($user_id) {
                        $q->where('user_id', $user_id);
                    });
                })
                ->when($link_or_post_id, function ($q) use ($link_or_post_id) {
                    return $q->whereHas('link', function ($q) use ($link_or_post_id) {
                        $q->where('link_or_post_id', $link_or_post_id);
                    });
                })
                ->when($to, function ($q) use ($to) {
                    return  $q->where('created_at', '<=', $to);
                })
                ->when($from, function ($q) use ($from) {
                    return  $q->where('created_at', '>=',  $from);
                })
                ->orderByDesc('id')
                ->limit(GlobalConstant::LIMIT_LINK_HISTORY)
                ->get()->toArray()
        ]);
    }
    public function getHistoryAll(Request $request)
    {
        $link_or_post_id = $request->link_or_post_id;

        return response()->json([
            'status' => 0,
            'histories' => LinkHistory::where('link_id', 'like' ,"%$link_or_post_id%")
                ->orderByDesc('id')
                ->limit(GlobalConstant::LIMIT_LINK_HISTORY)
                ->get()->toArray()
        ]);
    }
}
