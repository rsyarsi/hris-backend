<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyCandidate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('candidate')->user() && Auth::guard('candidate')->user()->email_verified_at !== null) {
            return $next($request);
        }
        return response()->json([
            'message' => 'Your email address is not verified!',
            'success' => false,
            'code' => 422,
            'data' => ['email' => ['Your email address is not verified!']]
        ], 422);
    }
}
