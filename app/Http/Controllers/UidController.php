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
                'phone.*.phone' => 'required|string',
                'phone.*.newphone' => 'nullable|string',
            ]);
            // Initialize an empty array to hold the transformed data
            $upsertData = [];
            if (isset($data['phone']) && is_array($data['phone'])) {
                // Loop through the 'phone' array and transform each item
                foreach ($data['phone'] as $item) {
                    $upsertData[] = [
                        'uid' => $item['uid'],
                        'phone' => $item['phone']
                    ];
                    if(!is_null($item['newphone'])){
                        $upsertData[] = [
                            'uid' => $item['uid'],
                            'phone' => $item['newphone']
                        ];
                    }
                }
            }
            Uid::insert($upsertData, ['uid'], ['phone']);
            return response()->json([
                'status' => 0,
                'data' => $upsertData
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 1,
                'message' => $e->getMessage()
            ]);
        }
    }
}
