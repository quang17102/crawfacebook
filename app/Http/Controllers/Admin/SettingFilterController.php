<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SettingFilter;
use App\Models\UserRole;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Throwable;
use Toastr;
class SettingFilterController extends Controller
{    
    public function index_2()
    {
        return view('admin.setting_admin_2', [
            'title' => 'Cài đặt',
            'settings' => Setting::orderBy('key')->get()
        ]);
    }
    
    public function getAll()
    {
        return response()->json([
            'status' => 0,
            'settings' => SettingFilter::orderBy('id')->get()
        ]);
    }
    public function show(){
        return view('admin.update_setting', [
            'title' => 'Update',
            'settings' => Setting::orderBy('key')->get()
        ]);
    }
    public function store(Request $request){
        try{
            $data = $request->validate([
                'data_cuoi_from' => 'nullable|integer',
                'data_cuoi_to' => 'nullable|integer',
                'reaction_chenh_from' => 'nullable|integer',
                'reaction_chenh_to' => 'nullable|integer',
                'data_reaction_chenh_from' => 'nullable|integer',
                'data_reaction_chenh_to' => 'nullable|integer',
                'comment_chenh_from' => 'nullable|integer',
                'comment_chenh_to' => 'nullable|integer',
                'data_comment_chenh_from' => 'nullable|integer',
                'data_comment_chenh_to' => 'nullable|integer',
                'view_chenh_from' => 'nullable|integer',
                'view_chenh_to' => 'nullable|integer',
                'delay' => 'nullable|integer',
                'status' => 'nullable|string',
            ]);
            DB::beginTransaction();
            SettingFilter::create([
                'data_cuoi_from' => $data['data_cuoi_from'],
                'data_cuoi_to' => $data['data_cuoi_to'],
                'reaction_chenh_from' => $data['reaction_chenh_from'],
                'reaction_chenh_to' => $data['reaction_chenh_to'],
                'data_reaction_chenh_from' => $data['data_reaction_chenh_from'],
                'data_reaction_chenh_to' => $data['data_reaction_chenh_to'],
                'comment_chenh_from' => $data['comment_chenh_from'],
                'comment_chenh_to' => $data['comment_chenh_to'],
                'data_comment_chenh_from' => $data['data_comment_chenh_from'],
                'data_comment_chenh_to' => $data['data_comment_chenh_to'],
                'view_chenh_from' => $data['view_chenh_from'],
                'view_chenh_to' => $data['view_chenh_to'],
                'delay' => $data['delay'],
                'status' => $data['status'],
            ]);
            Toastr::success('Tạo tài khoản thành công', __('title.toastr.success'));
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            Toastr::error($e->getMessage(), __('title.toastr.fail'));
        }
        return redirect()->back();
    }
}
