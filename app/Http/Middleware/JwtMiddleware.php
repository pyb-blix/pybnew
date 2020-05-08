<?php

namespace App\Http\Middleware;

use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use JWTAuth;
use App;
use Auth;

class JwtMiddleware extends BaseMiddleware
{
	/**
	* Handle an incoming request.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  \Closure  $next
	* @return mixed
	*/
	public function handle($request, \Closure $next)
	{
		if($request->filled('token')) {
			$validate_token = $this->validateToken($request);
			if($validate_token) {
				return $validate_token;
			}
		}

		if($request->has('language')) {
			\Session::put('language', $request->language);
			App::setLocale($request->language);
		}

		return $next($request);
	}

	protected function validateToken($request)
	{
		try {
			$user_details = JWTAuth::parseToken()->authenticate();
			if($user_details == '') {
				return response()->json(['status' => 'user_not_found'],403);
			}
			Auth::setUser($user_details);

			if($user_details->status=='Inactive') {
	            return response()->json([
	                'status_code'      => "0",
	                'status_message'   => "Inactive User",
	            ], 403);
	        }

	        if($user_details->currency_code) {
                \Session::put('currency', $user_details->currency_code);
	        }

	        if ($user_details->email_language !== null) {
                \Session::put('language', $user_details->email_language);
                App::setLocale($user_details->email_language);
            }
            \Session::save();
		}
		catch (\Exception $e) {
			if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
				return response()->json(['status' => 'invalid_token'],401);
			}
			else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
				return $this->getRefreshToken($request->token);
			}
			else {
				return response()->json(['status' => 'token_not_found'],401);
			}
		}
		return false;
	}

	protected function getRefreshToken($token)
	{
		try {
			$refreshed = JWTAuth::refresh($token);
		}
		catch (\Exception $e) {
			return response()->json(['status' => 'invalid_token'],401);
		}

		return response()->json([
			'status_code' 		=> "0",
			'success_message' 	=> "Token Expired",
			'refresh_token' 	=> $refreshed,
		]);
	}
}