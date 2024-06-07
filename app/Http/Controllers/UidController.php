<?php

namespace App\Http\Controllers;

use App\Models\Uid;
use Illuminate\Http\Request;

class UidController extends Controller
{
    public function uploadUid(Request $request)
    {
        try {
            $request->validate([
                'file' =>  'required|mimes:json',
            ]);
            $file_name = $request->file('file')->getClientOriginalName();
            $pathFull = 'upload/';
            $request->file('file')->storeAs(
                'public/' . $pathFull,
                $file_name
            );
            $content = json_decode(file_get_contents(storage_path('app/public/upload/' . $file_name)), true);
            $data = array_map(function ($item) {
                return [
                    'uid' => $item['uid'],
                    'phone' => !empty($item['newphone']) ?
                        $item['phone'] . ',' . $item['newphone'] : $item['phone'],
                ];
            }, $content);
            Uid::upsert($data, [
                'uid'
            ], ['phone']);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 1,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 0,
        ]);
    }
}
