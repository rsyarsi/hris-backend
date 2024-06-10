<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckOriginRecruitment
{
    private $allowedDomains;

    public function __construct()
    {
        $this->allowedDomains = explode(',', env('ALLOWED_CAREER_URL'));
    }

    public function handle(Request $request, Closure $next)
    {
        $origin = $request->headers->get('Origin');

        if (in_array($origin, $this->allowedDomains) || empty($origin)) {
            return $next($request);
        }

        abort(403, 'Unauthorized access');
    }
}
