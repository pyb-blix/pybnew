<?php

/**
 * Token Auth Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Token Auth
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\EmailController;
use App\Models\Currency;
use App\Models\ProfilePicture;
use App\Models\User;
use App\Models\UsersVerification;
use DB;
use Auth;
use JWTAuth;
use Validator;
use Session;
use App;

class TokenAuthController extends Controller
{
	/**
	 * Get User Details
	 *
	 * @param  App\Models\User $user [user details retrived from User Model]
	 * @return Array $user_data
	 */
	protected function getUserDetails($user)
	{
		if ($user->currency_code == '') {
			$default_currency = Currency::defaultCurrency()->activeOnly()->first();
			$user->currency_code = $default_currency->code;
		}

		$currency_symbol = Currency::original_symbol($user->currency_code);

		$user_data = array(
			'user_id' 		=> $user->id,
			'first_name' 	=> $user->first_name,
			'last_name' 	=> $user->last_name,
			'email_id' 		=> $user->email,
			'user_image' 	=> $user->profile_picture_src,
			'dob' 			=> $user->dob ?? '',
			'currency_code' => $user->currency_code,
			'currency_symbol' => $currency_symbol,
		);

		return $user_data;
	}

	/**
	 * User Login
	 *
	 * @return Response in Json
	 */
	public function login(Request $request)
	{
		$credentials = $request->only('email', 'password');
		if (Auth::attempt($credentials)) {
			try {
				if (!$token = JWTAuth::attempt($credentials)) {
					return response()->json([
						'status_code' => '0',
						'success_message' => trans('messages.api.wrong_credentials'),
					]);
				}
			}
			catch (JWTException $e) {
				return response()->json([
					'status_code' => '0',
					'success_message' => 'could_not_create_token',
				]);
			}

			$user = User::with('profile_picture')->whereEmail($request->email)->first();

			if($request->filled('language')) {
				$user->email_language = $request->language;

				$language = $user->email_language ?? 'en';
			    App::setLocale($language);
			}

			if ($user->status == 'Inactive') {
				return response()->json([
					'status_code' => '0',
					'success_message' => trans('messages.api.account_disabled'),
				]);
			}

			//update currency_code
			if ($user->currency_code == '') {
				$default_currency = Currency::defaultCurrency()->activeOnly()->first();

				$user->currency_code = $default_currency->code;
			}

			$currency_symbol = Currency::original_symbol($user->currency_code);

			if($request->timezone) {
				$user->timezone = $request->timezone;
			}
			
			$user->save();

			$return_data = array(
				'status_code' => '1',
				'success_message' => trans('messages.api.login_success'),
				'access_token' => $token,
			);

			$user_details = $this->getUserDetails($user);

			return response()->json(array_merge($return_data,$user_details));
		}

		return response()->json([
			'status_code' => '0',
			'success_message' => trans('messages.api.wrong_credentials'),
		]);
	}

	/**
	 * Signup via Email, Facebook, Apple and Google.
	 *
	 * @return Response in Json
	 */
	public function signup(Request $request, EmailController $email_controller)
	{


		$acceptable_mimes = view()->shared('acceptable_mimes');        
        $rules = array(
            'auth_type'     => 'required|in:email,facebook,apple,google',
            'auth_id'       => 'required_if:auth_type,facebook,apple,google',
        );
        $attributes = array(
            'auth_type' => 'Auth Type',
            'auth_id' => 'Auth Id',
            'email' => 'Email',
        );
        $messages = array('required' => ':attribute is required.');

        $validator = Validator::make($request->all(), $rules, $messages, $attributes);
        
        if($validator->fails()) {
            return response()->json([
                'status_code' => '0',
                'success_message' => $validator->messages()->first(),
            ]);
        }

        $default_currency = Currency::defaultCurrency()->activeOnly()->first();

        $auth_type 		= $request->auth_type;
        $auth_column 	= $auth_type.'_id';

        try {
	    	$user = User::with('profile_picture')->where($auth_column, $request->auth_id)->orWhere("email",$request->email)->first();
        }
        catch(\Exception $e) {
        	$user = '';
        }

        
        if($auth_type != 'email' && $user != '') {
        
	        if($user->status == 'Inactive') {
				return response()->json([
		        	'status_code'       =>  '0',
		        	'success_message'   =>  'This user in Inactive status please contact admin ',
		        ],401);
			}

			// if(optional($user->profile_picture)->src != '' && $request->filled('profile_pic')) {
			// 	$photo_src = $request->profile_pic;
			// 	if($auth_type == 'google') {
			// 		$photo_src = str_replace('5000', '50',$request->profile_pic);
			// 	}

			// 	DB::table('profile_picture')->whereUserId($user->id)->update([
			// 		'photo_source' 	=> ucfirst($auth_type),
			// 		'src' 		 	=> $photo_src ?? '',
			// 	]);
			// }

			$token = JWTAuth::fromUser($user);

			$return_data = array(
				'status_code' => '1',
				'success_message' => trans('messages.api.login_success'),
				'access_token' => $token,
			);

			$user_details = $this->getUserDetails($user);

			return response()->json(array_merge($return_data,$user_details));
		}

		if($auth_type == 'email') {
			$rules = array(
				'email' => 'required|max:255|email|unique:users',
				'dob' => 'required|date',
				'password' => 'required|min:8',
				'first_name' => 'required',
				'last_name' => 'required',
			);
		}
		else {
			$rules = array(
				'email' 	=> 'required|max:255|email|unique:users',
				'auth_id' 	=> 'required|unique:users,'.$auth_column,
				'first_name'=> 'required',
				'last_name' => 'required',
			);

			if($request->email == '') {
	        	return response()->json([
	                'status_code' => '2',
	                'success_message' => __('validation.required',['attribute' => 'email']),
	            ]);
	        }
	        
		}

        $attributes = array(
            'email' 		=> 'Email',
            'dob' 			=> 'Date of Birth',
            'password' 		=> 'Password',
            'first_name' 	=> 'First name',
            'last_name' 	=> 'Last name',
        );
        $messages = array('required' => ':attribute is required.');

        $validator = Validator::make($request->all(), $rules, $messages, $attributes);

        if ($validator->fails()) {
        	return response()->json([
                'status_code' => '0',
                'success_message' => $validator->messages()->first(),
            ]);
        }

        $default_currency = Currency::defaultCurrency()->activeOnly()->first();
       
        if ($request->filled('dob')) {
			$str_date = $request->dob;
			$date = \Carbon\Carbon::createFromTimestamp(strtotime($request->dob));
			$dob = $date->format('Y-m-d');
		}


		$user = new User;
		$user->first_name 	= $request->first_name;
		$user->last_name 	= $request->last_name;
		$user->email 		= $request->email;

		if ($request->password != '') {
			$user->password = bcrypt($request->password);
		}


		if($auth_type != 'email') {
			$user->status = "Active";
			$user->$auth_column = $request->auth_id;
		}
		
		if(isset($dob)) {
			$user->dob = $dob;
			
		}
		$user->currency_code = $default_currency->code;

		if($request->filled('timezone')) {
			$user->timezone = $request->timezone;
		}

		if($request->filled('language')) {
			$user->email_language = $request->language;
			App::setLocale($user->email_language);
		}

		$user->save();

		$user_pic = new ProfilePicture;
		$user_pic->user_id = $user->id;

		if($request->filled('profile_pic')) {
			$photo_src = $request->profile_pic;
			if($auth_type == 'google') {
				$photo_src = str_replace('5000', '50',$request->profile_pic);
			}

			$user_pic->photo_source = ucfirst($auth_type);
			$user_pic->src = $photo_src;
		}
		$user_pic->save();

		$user_verification = new UsersVerification;
		$user_verification->user_id = $user->id;
		if($auth_type != 'email') {
			$user_verification->$auth_type = 'yes';
		}
		$user_verification->save();
		
		if($auth_type == 'email') {
			$email_controller->welcome_email_confirmation($user);
			$credentials = $request->only('email', 'password');

			try {
				$token = JWTAuth::attempt($credentials);
			}
			catch (JWTException $e) {
				return response()->json([
					'status_code' => '0',
					'success_message' => 'could_not_create_token',
				]);
			}
		}
		else {
			$token = JWTAuth::fromUser($user);
		}

		if(!$token) {
			return response()->json([
				'status_code' 		=> '0',
				'success_message' 	=> trans('messages.api.signup_fail'),
			]);
		}

		$return_data = array(
			'status_code' 		=> '1',
			'success_message' 	=> trans('messages.api.signup_success'),
			'access_token' 		=> $token,
		);

		$user_details = $this->getUserDetails($user);

		return response()->json(array_merge($return_data,$user_details));
	}

	/**
     * User Socail media Resister & Login 
     * @param Get method request inputs
     *
     * @return Response Json 
     */
    public function apple_callback(Request $request) 
    {
        $client_id = api_credentials('service_id','Apple');

        $client_secret = getAppleClientSecret();

        $params = array(
            'grant_type'    => 'authorization_code',
            'code'          => $request->code,
            'redirect_uri'  => url('api/apple_callback'),
            'client_id'     => $client_id,
            'client_secret' => $client_secret,
        );
        
        $curl_result = curlPost("https://appleid.apple.com/auth/token",$params);

        if(!isset($curl_result['id_token'])) {
            $return_data = array(
                'status_code'       => '0',
                'success_message'    => $curl_result['error'],
            );

            return response()->json($return_data);
        }

        $claims = explode('.', $curl_result['id_token'])[1];
        $user_data = json_decode(base64_decode($claims));
        $user_email = optional($user_data)->email ?? '';

        $user = User::where('apple_id', $user_data->sub)->orWhere('email',$user_email)->first();

        if($user == '') {
            $return_data = array(
                'status_code'       => '1',
                'success_message'   => 'New User',
                'email_id'          => $user_email,
                'apple_id'          => $user_data->sub,
                'access_token'      => "",
            );

            return response()->json($return_data);
        }

        $token = JWTAuth::fromUser($user);

        $user_details = $this->getUserDetails($user);

        $return_data = array(
            'status_code'       => '2',
            'success_message'   => 'Login Successfully',
            'email_id'       	=> optional($user_data)->email ?? '',
            'apple_id'          => $user_data->sub,
            'access_token'      => $token,
        );

        return response()->json(array_merge($return_data,$user_details));
    }

	/**
	 * User Email Validation
	 *
	 * @return Response in Json
	 */
	public function emailvalidation(Request $request)
	{
		$rules = array('email' => 'required|max:255|email|unique:users');

		$validator = Validator::make($request->all(), $rules);

		$return_data = array(
			'status_code' => '1',
			'success_message' => trans('messages.api.email_success'),
		);

		if ($validator->fails()) {
			$return_data = array(
				'status_code' => '0',
				'success_message' => trans('messages.api.email_exist'),
			);
		}

		return response()->json($return_data);
	}

	/**
	 * Forgot Password
	 *
	 * @return Response in Json
	 */
	public function forgotpassword(Request $request, EmailController $email_controller)
	{
		$rules = array('email' => 'required|email|exists:users,email');

		$validator = Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			$return_data = array(
				'status_code' => '0',
				'success_message' => trans('messages.api.invalid_mail'),
			);

			return response()->json($return_data);
		}

		$user = User::whereEmail($request->email)->first();

		$email_controller->forgot_password($user);

		$return_data = array(
			'status_code' => '1',
			'success_message' => trans('messages.api.link_send_to_mail'),
		);

		return response()->json($return_data);
	}

    /**
     * User Logout
     *
     * @param  Get method inputs
     * @return Response in Json 
     */
    public function logout(Request $request)
    {  
        JWTAuth::invalidate($request->token);
        Session::flush();

        return response()->json([
			'status_code'     => '1',
			'success_message' => 'Logout Successfully',
		]);
    }

    public function common_data(Request $request)
    {
        $status_code = '1';
        $success_message = 'Listed Successfully';

        $apple_service_id = api_credentials('service_id','Apple');

        return response()->json(compact(
    		'status_code',
    		'success_message',
    		'apple_service_id',
    	));
    }
}