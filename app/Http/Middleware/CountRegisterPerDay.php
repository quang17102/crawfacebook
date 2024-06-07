<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use Toastr;

class CountRegisterPerDay
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $info_ip = Cache::get($ip);
        if (!$info_ip) {
            return $next($request);
        }
        Toastr::error('Mỗi ngày chỉ được đăng ký một tài khoản một lần duy nhất!', __('title.toastr.fail'));

        return redirect()->back();
    }
}
