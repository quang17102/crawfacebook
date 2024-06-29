<?php

namespace App\Http\Controllers;

use App\Models\Uid;
use Illuminate\Http\Request;

class UidController extends Controller
{
    public function uploadUid(Request $request)
    {
        try {
            $data = $request->validate([
                'phone' => 'nullable|array',
                'phone.*.uid' => 'required|string',
                'phone.*.phone' => 'nullable|string',
            ]);
            $dataArray = json_decode($data, true);
            $phoneArray = $dataArray['phone'];
            $result = [];
            foreach ($phoneArray as $item) {
                $data[] = [
                    'uid' => $item['uid'],
                    'phone' => $item['phone']
                ];
            }
            Uid::upsert($result, ['uid'], ['phone']);
            return response()->json([
                'status' => 0,
                'data' => $result
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 1,
                'message' => $e->getMessage()
            ]);
        }
    }
}
