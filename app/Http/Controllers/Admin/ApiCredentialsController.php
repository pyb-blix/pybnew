<?php

/**
 * Api Credentials Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Api Credentials
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApiCredentials;
use Validator;

class ApiCredentialsController extends Controller
{
    public function __construct()
    {
        $this->helper = resolve('App\Http\Start\Helpers');
    }

    /**
     * Load View and Update Api Credentials
     *
     * @return redirect     to api_credentials
     */
    public function index(Request $request)
    {
        if ($request->isMethod('GET')) {
            $data['result'] = ApiCredentials::get();
            return view('admin.api_credentials', $data);
        }

        if($request->submit) {
            $rules = array(
                'facebook_client_id'     => 'required',
                'facebook_client_secret' => 'required',
                'google_client_id'       => 'required',
                'google_client_secret'   => 'required',
                'google_map_key'         => 'required',
                'google_map_server_key'  => 'required',
                'linkedin_client_id'     => 'required',
                'linkedin_client_secret' => 'required',
                'nexmo_api'              => 'required',
                'nexmo_secret'           => 'required',
                'nexmo_from'             => 'required',
                'cloud_name'             => 'required',
                'cloud_key'              => 'required',
                'cloud_secret'           => 'required',
                'cloud_base_url'         => 'required',
                'cloud_secure_url'       => 'required',
                'cloud_api_url'          => 'required',
                'apple_service_id'      => 'required',
                'apple_team_id'         => 'required',
                'apple_key_id'          => 'required',
                'apple_key_file'        => 'valid_extensions:p8',
            );

            $messages = [
                'valid_extensions' => trans('validation.mimes',['values'=>'p8']),
            ];

            // Api Credentials Validation Custom Names
            $attributes = array(
                'facebook_client_id'     => 'Facebook Client ID',
                'facebook_client_secret' => 'Facebook Client Secret',
                'google_client_id'       => 'Google Client ID',
                'google_client_secret'   => 'Google Client Secret',
                'google_map_key'         => 'Google Map Browser Key',
                'google_map_server_key'  => 'Google Map Server Key',
                'linkedin_client_id'     => 'LinkedIn Client ID',
                'linkedin_client_secret' => 'LinkedIn Client Secret',
                'cloud_name'             => 'Cloudinary Name',
                'cloud_key'              => 'Cloudinary Key',
                'cloud_secret'           => 'Cloudinary Secret',
                'cloud_base_url'         => 'Cloudinary BaseUrl',
                'cloud_secure_url'       => 'Cloudinary SecureUrl',
                'cloud_api_url'          => 'Cloudinary ApiUrl',
                'apple_service_id'      => 'Apple Service ID',
                'apple_team_id'         => 'Apple Team ID',
                'apple_key_id'          => 'Apple Key ID',
                'apple_key_file'        => 'Key File',
            );

            $validator = Validator::make($request->all(), $rules, $messages, $attributes);

            $validator->after(function ($validator) use($request) {
                if(!file_exists(public_path('key.txt')) && $request->file('apple_key_file') == '') {
                    $validator->errors()->add('apple_key_file', 'The :attribute is required.');
                }
            });

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            ApiCredentials::where(['name' => 'client_id', 'site' => 'Facebook'])->update(['value' => $request->facebook_client_id]);
            ApiCredentials::where(['name' => 'client_secret', 'site' => 'Facebook'])->update(['value' => $request->facebook_client_secret]);

            ApiCredentials::where(['name' => 'client_id', 'site' => 'Google'])->update(['value' => $request->google_client_id]);
            ApiCredentials::where(['name' => 'client_secret', 'site' => 'Google'])->update(['value' => $request->google_client_secret]);

            ApiCredentials::where(['name' => 'key', 'site' => 'GoogleMap'])->update(['value' => $request->google_map_key]);
            ApiCredentials::where(['name' => 'server_key', 'site' => 'GoogleMap'])->update(['value' => $request->google_map_server_key]);

            ApiCredentials::where(['name' => 'client_id', 'site' => 'LinkedIn'])->update(['value' => $request->linkedin_client_id]);
            ApiCredentials::where(['name' => 'client_secret', 'site' => 'LinkedIn'])->update(['value' => $request->linkedin_client_secret]);

            ApiCredentials::where(['name' => 'key', 'site' => 'Nexmo'])->update(['value' => $request->nexmo_api]);
            ApiCredentials::where(['name' => 'secret', 'site' => 'Nexmo'])->update(['value' => $request->nexmo_secret]);
            ApiCredentials::where(['name' => 'from', 'site' => 'Nexmo'])->update(['value' => $request->nexmo_from]);
            
            ApiCredentials::where(['name' => 'cloudinary_name', 'site' => 'Cloudinary'])->update(['value' => $request->cloud_name]);
            ApiCredentials::where(['name' => 'cloudinary_key', 'site' => 'Cloudinary'])->update(['value' => $request->cloud_key]);
            ApiCredentials::where(['name' => 'cloudinary_secret', 'site' => 'Cloudinary'])->update(['value' => $request->cloud_secret]);
            ApiCredentials::where(['name' => 'cloud_base_url', 'site' => 'Cloudinary'])->update(['value' => $request->cloud_base_url]);
            ApiCredentials::where(['name' => 'cloud_secure_url', 'site' => 'Cloudinary'])->update(['value' => $request->cloud_secure_url]);
            ApiCredentials::where(['name' => 'cloud_api_url', 'site' => 'Cloudinary'])->update(['value' => $request->cloud_api_url]);

            ApiCredentials::where(['name' => 'service_id','site' => 'Apple'])->update(['value' => $request->apple_service_id]); 
            ApiCredentials::where(['name' => 'team_id','site' => 'Apple'])->update(['value' => $request->apple_team_id]);
            ApiCredentials::where(['name' => 'key_id','site' => 'Apple'])->update(['value' => $request->apple_key_id]);

            if ($request->file('apple_key_file')) {
                $key_file = $request->file('apple_key_file');
                $extension = $key_file->getClientOriginalExtension();
                $filename = 'key.txt';

                $success = $key_file->move(public_path(), $filename);

                if(!$success) {
                    return back()->withError('Could not upload Image');
                }
                ApiCredentials::where(['name' => 'key_file','site' => 'Apple'])->update(['value' => $filename]);
            }

            $this->helper->flash_message('success', 'Updated Successfully');
        }

        return redirect()->route('admin.api_credentials');
    }
}