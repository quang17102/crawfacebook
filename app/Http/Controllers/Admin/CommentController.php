<?php

namespace App\Http\Controllers\Admin;

use App\Constant\GlobalConstant;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Throwable;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.comment.list', [
            'title' => 'Danh sách bình luận',
            'users' => User::where('role', GlobalConstant::ROLE_USER)->get(),
        ]);
    }
}
