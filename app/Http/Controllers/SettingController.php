<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Toastr;

class SettingController extends Controller
{
    public function uploadmap(Request $request)
    {
        $url = '';
        if ($request->hasFile('file')) {
            try {
                $file_name = date('H-i-s') . $request->file('file')->getClientOriginalName();
                $pathFull = 'uploads/' . date('Y-m-d');
                $request->file('file')->storeAs(
                    'public/' . $pathFull,
                    $file_name
                );
                $url = '/storage/' . $pathFull . '/' . $file_name;

                Setting::updateOrCreate(['key' => 'map'], [
                    'value' => $url
                ]);
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

    public function backup(Request $request)
    {
        $email_or_phone = Auth::user()?->email ?? Auth::user()?->name;
        Log::info('Backup at ' . now()->format('Y-m-d H:i:s') . ' by ' . $email_or_phone);
        $filename = 'backup' . now()->format('Y-m-d') . '.sql';

        $disk = 'local';
        $path = 'public/backup';
        // Check if the folder exists
        if (!Storage::disk($disk)->exists($path)) {
            // Create the folder
            Storage::disk($disk)->makeDirectory($path);
        }
        $filePath = storage_path() . '/app/public/backup/' . $filename;
        $command = 'mysqldump --user=' . env('DB_USERNAME') . ' --password=' . env('DB_PASSWORD')
            . ' --host=' . env('DB_HOST') . ' --port=' . env('DB_PORT') . ' ' . env('DB_DATABASE') . ' > ' . $filePath;
        $returnVar = NULL;
        $output = NULL;
        exec($command, $output, $returnVar);
        Log::info('Command backup: ' . $command);

        return response()->download($filePath);
    }

    public function update(Request $request)
    {
        try {
            foreach ($request->except('_token') as $key => $value) {
                Setting::where('key', $key)->update([
                    'value' => $value
                ]);
            }
        } catch (Throwable $e) {
            Toastr::error(__('message.fail.update'), __('title.toastr.fail'));
        }
        Toastr::success(__('message.success.update'), __('title.toastr.success'));

        return redirect()->back();
    }

    public function index()
    {
        return view('admin.setting', [
            'title' => 'Cài đặt',
            'settings' => Setting::orderBy('key')->get()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'required|string',
            'settings.*.name' => 'nullable|string',
        ]);
        foreach ($data['settings'] as $value) {
            Setting::updateOrCreate([
                'key' => $value['key']
            ], [
                'value' => $value['value'],
                'name' => $value['name'],
            ]);
        }

        return response()->json([
            'status' => 0,
        ]);
    }

    public function getAll()
    {
        return response()->json([
            'status' => 0,
            'settings' => Setting::orderBy('key')->get()
        ]);
    }

    public function delete(Request $request)
    {
        $data = $request->validate([
            'key' => 'required|array',
            'key.*' => 'nullable|string',
        ]);

        try {
            if (!in_array('*', $data['key'])) {
                Setting::whereIn('key', $data['key'] ?? [])->delete();
            } else {
                Setting::truncate();;
            }
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
