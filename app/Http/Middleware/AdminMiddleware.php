<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // เพิ่มตัวนี้เข้ามา

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ใช้ Auth::check() แทน auth()->check() เพื่อลดโอกาสตัวแดงใน IDE
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        return redirect('/')->with('error', 'เฉพาะผู้ดูแลระบบเท่านั้นที่เข้าถึงหน้านี้ได้');
    }
}