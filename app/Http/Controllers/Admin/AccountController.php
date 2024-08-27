<?php

namespace App\Http\Controllers\Admin;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserRole;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;
use Toastr;

class AccountController extends Controller
{
    public function create()
    {
        return view('admin.account.add', [
            'title' => 'Thêm tài khoản'
        ]);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'password' => 'required|string',
                'delay' => 'required|integer',
                'limit' => 'required|integer',
                'limit_follow' => 'required|integer',
                'expire' => 'required|integer',
                'role' => 'required|in:0,1',
                'roles' => 'nullable|array',
                'roles.*' => 'nullable|integer|in:0,1,2,3,4,5,6,7,8',
            ]);
            $check = User::firstWhere('name', $data['name']);
            if ($check) {
                throw new Exception('Tài khoản đã có người đăng ký!');
            }
            DB::beginTransaction();
            $data['expire'] = now()->addDays($data['expire'])->format('Y-m-d');
            $user = User::create([
                'name' => $data['name'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
                'delay' => $data['delay'],
                'limit' => $data['limit'],
                'limit_follow' => $data['limit_follow'],
                'expire' => $data['expire'],
            ]);
            if (!empty($data['roles'])) {
                $data['roles'] = array_map(function ($item) use ($user) {
                    return [
                        'user_id' => $user->id,
                        'role' => $item,
                        'name' => GlobalConstant::ROLE_ALL[$item],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }, $data['roles']);
                UserRole::insert($data['roles']);
            }
            Toastr::success('Tạo tài khoản thành công', __('title.toastr.success'));
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Toastr::error($e->getMessage(), __('title.toastr.fail'));
        }

        return redirect()->back();
    }

    public function update(Request $request)
    {
        try {
            $data = $request->validate([
                'user_id' => 'required|string',
                'password' => 'nullable|string',
                'delay' => 'required|integer',
                'limit' => 'required|integer',
                'limit_follow' => 'required|integer',
                'expire' => 'required|integer',
                'roles' => 'nullable|array',
                'roles.*' => 'nullable|in:0,1,2,3,4,5,6,7',
            ]);
            $user_id = $data['user_id'];
            $data['expire'] = now()->addDays($data['expire'])->format('Y-m-d');
            $dataUpdate = [
                'delay' => $data['delay'],
                'limit' => $data['limit'],
                'limit_follow' => $data['limit_follow'],
                'expire' => $data['expire'],
            ];
            if (strlen($data['password'])) {
                $dataUpdate = array_merge($dataUpdate, [
                    'password' => Hash::make($data['password']),
                ]);
            }
            DB::beginTransaction();

            $user = User::firstWhere('id', $data['user_id']);

            // check limit scan
            $userLinks = Link::with(['user'])
                ->where('user_id', $user->id)
                ->where('type', GlobalConstant::TYPE_SCAN)
                ->orderBy('created_at');
            if ($userLinks->get()->count() >= $data['limit']) {
                $userLinks->take($userLinks->get()->count() - $data['limit'])
                    ->delete();
            }

            // check limit follow
            $userLinks = Link::with(['user'])
                ->where('user_id', $user->id)
                ->where('type', GlobalConstant::TYPE_FOLLOW)
                ->orderBy('created_at');
            if ($userLinks->get()->count() >= $data['limit_follow']) {
                $userLinks->take($userLinks->get()->count() - $data['limit_follow'])
                    ->delete();
            }

            // setting role
            User::where('id', $user_id)->update($dataUpdate);
            UserRole::where('user_id', $user_id)->whereNotIn('role', $data['roles'] ?? [])->delete();
            if (!empty($data['roles'])) {
                $data['roles'] = array_map(function ($item) use ($user_id) {
                    return [
                        'user_id' => $user_id,
                        'role' => $item,
                    ];
                }, $data['roles']);
                UserRole::upsert(
                    $data['roles'],
                    ['user_id', 'role'],
                    ['role']
                );
            }
            DB::commit();

            Toastr::success(__('message.success.update'), __('title.toastr.success'));
        } catch (Throwable $e) {
            DB::rollBack();
            Toastr::error($e->getMessage(), __('title.toastr.fail'. $e->getMessage()));
        }

        return redirect()->back();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json([
                'status' => 0,
                'accounts' => User::all()
            ]);
        }

        return view('admin.account.list', [
            'title' => 'Danh sách tài khoản',
            'roles' => GlobalConstant::ROLE_ALL,
            'setting' => Setting::all()->pluck('value', 'key')->toArray(),
        ]);
    }

    public function show($id)
    {
        $user = User::with(['userRoles'])->firstWhere('id', $id);
        $myRoles = $user->userRoles->pluck('role')->toArray() ?? [];

        return view('admin.account.edit', [
            'title' => 'Chi tiết tài khoản',
            'roles' => GlobalConstant::ROLE_ALL,
            'user' => $user,
            'myRoles' => $myRoles,
        ]);
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $user = User::firstWhere('id', $id);
            $user->delete();
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
