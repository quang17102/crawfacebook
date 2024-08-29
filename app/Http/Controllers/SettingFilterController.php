<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SettingFilter;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Toastr;
class SettingFilterController extends Controller
{
    public function getAll()
    {
        return response()->json([
            'status' => 0,
            'settings' => SettingFilter::orderBy('id')->get()
        ]);
    }
}
