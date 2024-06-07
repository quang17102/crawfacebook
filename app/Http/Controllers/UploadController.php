<?php

namespace App\Http\Controllers;

use App\Constant\GlobalConstant;
use App\Mail\RecoverPasswordMail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class UploadController extends Controller
{
    //
    public function upload(Request $request)
    {
        $url = '';
        if ($request->hasFile('file')) {
            try {
                $request->validate([
                    'file' =>  'max:500000|mimes:jpeg,png,pdf,docx,pptx,cad,xlsx,xls,csv',
                ]);
                $file_name = date('H-i-s') . $request->file('file')->getClientOriginalName();
                $pathFull = 'upload/' . date('Y-m-d');
                $request->file('file')->storeAs(
                    'public/' . $pathFull,
                    $file_name
                );
                $url = '/storage/' . $pathFull . '/' . $file_name;
            } catch (Throwable $e) {
                return response()->json([
                    'status' => 1,
                    'message' => $e->getMessage()
                ]);
            }
        }

        return response()->json([
            'status' => 0,
            'url' => $url
        ]);
    }

    public function restore(Request $request)
    {
        // if ($request->hasFile('file')) {
        //     try {
        //         if ($request->file('file')->getClientOriginalExtension() !== GlobalConstant::TYPE_FILE_BACKUP) {
        //             throw new Exception('File không hợp lệ');
        //         }
        //         $file_name = date('Y-m-d-H-i-s') . $request->file('file')->getClientOriginalName();
        //         $request->file('file')->storeAs('public/restore', $file_name);
        //         $file_location = public_path() . '/storage/restore/' . $file_name;

        //         Log::info('file_location.' . $file_location);
        //         DB::unprepared(file_get_contents($file_location));
        //         Log::info('Database restore completed.');
        //     } catch (Throwable $e) {
        //         return response()->json([
        //             'status' => 1,
        //             'message' => $e->getMessage()
        //         ]);
        //     }
        // }

        return response()->json([
            'status' => 0,
            'message' => 'Phục hồi dữ liệu thành công'
        ]);
    }

    public function recover(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => 'required|string',
                'password' => 'required|string',
            ]);
            Mail::to($data['email'])->send(new RecoverPasswordMail($data['password']));

            return response()->json([
                'status' => 0
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 1,
                'message' => $e->getMessage()
            ]);
        }
    }
}
