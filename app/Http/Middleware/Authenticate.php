<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $cookie = $request->getSession();
        if (!$cookie) {
            return response()->json([
                'code'  => 3000,
                'msg'   => '没有登录'
            ]);
        }

        return $next($request);
    }
}
