<?php

namespace App\Http\Middleware;

use Closure;
use App;
use JWTAuth;

class ApiLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = request('token');
        if ($token) {
            $user_details = JWTAuth::parseToken()->authenticate();
            if ($user_details && @$user_details->email_language !== null) {
                App::setLocale($user_details->email_language);
                return $next($request);
            }
        }

        if(isset($request->language)) {
            App::setLocale($request->language);
        }else {
            App::setLocale('en');
        }
        return $next($request);
    }
}
