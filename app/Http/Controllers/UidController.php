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
            //Uid::upsert($data, ['uid'], ['phone']);
            return response()->json([
                'status' => 0,
                'data' => $data
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 1,
                'message' => $e->getMessage()
            ]);
        }
    }
}
