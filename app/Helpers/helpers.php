<?php

use Illuminate\Support\HtmlString;
use App\Models\Currency;
use App\Models\Language;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\KeyManagement\JWKFactory;
use Jose\Component\Signature\Algorithm\ES256;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\Serializer\CompactSerializer;

/**
 * Convert String to htmlable instance
 *
 * @param  string $type      Type of the image
 * @return instance of \Illuminate\Contracts\Support\Htmlable
 */
if (!function_exists('html_string')) {

	function html_string($str)
	{
		return new HtmlString($str);
	}
}

/**
 * Set Flash Message function
 *
 * @param  string $class     Type of the class ['danger','success','warning']
 * @param  string $message   message to be displayed
 */
if (!function_exists('flash_message')) {

	function flash_message($class, $message)
	{
		Session::flash('alert-class', 'alert-'.$class);
        Session::flash('message', $message);
	}
}

/**
 * Currency Convert
 *
 * @param int $from   Currency Code From
 * @param int $to     Currency Code To
 * @param int $price  Price Amount
 * @return int Converted amount
 */
if (!function_exists('currency_convert')) {

	function currency_convert($from = '', $to = '', $price)
	{
		if(session('currency')) {
			$currency_code = session('currency');
		}
		else {
			$currency_code = Currency::where('default_currency', 1)->first()->code;
		}
		if($from == '') {
			$from = $currency_code;
		}
		if($to == '') {
			$to = $currency_code;
		}

		if($from == $to) {
			return ceil($price);
		}

		$rate = Currency::whereCode($from)->first()->rate;
		$usd_amount = $price / $rate;
		$session_rate = Currency::whereCode($to)->first()->rate;

		return ceil($usd_amount * $session_rate);
	}
}

/**
 * Checks if a value exists in an array in a case-insensitive manner
 *
 * @param string $key The searched value
 * 
 * @return if key found, return particular value of key.
 */
if (!function_exists('site_settings')) {
	
	function site_settings($key) {
		$site_settings = resolve('site_settings');
		$site_setting = $site_settings->where('name',$key)->first();

		return optional($site_setting)->value ?? '';
	}
}

/**
 * Checks if a value exists in an array in a case-insensitive manner
 *
 * @param string $key The searched value
 * 
 * @return if key found, return particular value of key.
 */
if (!function_exists('api_credentials')) {
	
	function api_credentials($key, $site) {
		$api_credentials = resolve('api_credentials');
		$credentials = $api_credentials->where('name',$key)->where('site',$site)->first();

		return optional($credentials)->value ?? '';
	}
}

/**
 * File Get Content by using CURL
 *
 * @param  string $url  Url
 * @return string $data Response of URL
 */
if (!function_exists('file_get_contents_curl')) {

	function file_get_contents_curl($url)
	{
	    $ch = curl_init();

	    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       

	    $data = curl_exec($ch);
	    curl_close($ch);

	    return $data;
	}
}

/**
 * Process CURL With POST
 *
 * @param  String $url  Url
 * @param  Array $params  Url Parameters
 * @return string $data Response of URL
 */
if (!function_exists('curlPost')) {

	function curlPost($url,$params)
	{
		$curlObj = curl_init();

		curl_setopt($curlObj,CURLOPT_URL,$url);
		curl_setopt($curlObj,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curlObj,CURLOPT_HEADER, false); 
		curl_setopt($curlObj,CURLOPT_POST, count($params));
		curl_setopt($curlObj,CURLOPT_POSTFIELDS, http_build_query($params));    
		curl_setopt($curlObj, CURLOPT_HTTPHEADER, [
	        'Accept: application/json',
	        'User-Agent: curl',
	    ]);
		$output = curl_exec($curlObj);

		curl_close($curlObj);
		return json_decode($output,true);
	}
}

/**
 * Get Langugage Code
 *
 * @return String $lang_code 
 */
if (!function_exists('getLangCode')) {

	function getLangCode()
	{
		$language = Language::whereValue(session('language'))->first();

		if($language) {
			$lang_code = $language->value;
		}
		else {
			$lang_code = Language::where('default_language',1)->first()->value;
		}
		return $lang_code;
	}
}

/**
 * Get a Facebook Login URL
 *
 * @return URL from Facebook API
 */
if (!function_exists('getAppleLoginUrl')) {
	function getAppleLoginUrl()
	{
		$params = [
			'response_type' 	=> 'code',
			'response_mode' 	=> 'form_post',
			'client_id' 		=> api_credentials('service_id','Apple'),
			'redirect_uri' 		=> url('apple_callback'),
			'state' 			=> bin2hex(random_bytes(5)),
			'scope' 			=> 'name email',
		];
		$authorize_url = 'https://appleid.apple.com/auth/authorize?'.http_build_query($params);

		return $authorize_url;
	}
}

/**
 * Generate Apple Client Secret
 *
 * @return String $token
 */
if (!function_exists('getAppleClientSecret')) {
	function getAppleClientSecret()
    {
        $key_file = public_path(api_credentials('key_file','Apple'));

        $algorithmManager = new AlgorithmManager([new ES256()]);
        $jwsBuilder = new JWSBuilder($algorithmManager);
        $jws = $jwsBuilder
            ->create()
            ->withPayload(json_encode([
                'iat' => time(),
                'exp' => time() + 86400*180,
                'iss' => api_credentials('team_id','Apple'),
                'aud' => 'https://appleid.apple.com',
                'sub' => api_credentials('service_id','Apple'),
            ]))
            ->addSignature(JWKFactory::createFromKeyFile($key_file), [
                'alg' => 'ES256',
                'kid' => api_credentials('key_id','Apple')
            ])
            ->build();

        $serializer = new CompactSerializer();
        $token = $serializer->serialize($jws, 0);
        
        return $token;
    }
}

/**
 * Check if a string is a valid timezone
 *
 * @param string $timezone
 * @return bool
 */
if (!function_exists('isValidTimezone')) {
    function isValidTimezone($timezone)
    {
        return in_array($timezone, timezone_identifiers_list());
    }
}

if (!function_exists('compressImage')) {
	function compressImage($source_url, $destination_url, $quality, $width = 225, $height = 225)
	{
        $info = getimagesize($source_url);
        if(!$info) {
            return false;
        }

        if($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($source_url);
            $exif = @exif_read_data($source_url);
        }
        elseif($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($source_url);
        }
        elseif($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($source_url);
        }
        elseif($info['mime'] == 'image/webp') {
            $image = imagecreatefromwebp($source_url);
        }

        if (isset($exif) && !empty($exif['Orientation'])) {
            $imageResource = imagecreatefromjpeg($source_url);
            switch ($exif['Orientation']) {
                case 3:
                    $image = imagerotate($imageResource, 180, 0);
                    break;
                case 6:
                    $image = imagerotate($imageResource, -90, 0);
                    break;
                case 8:
                    $image = imagerotate($imageResource, 90, 0);
                    break;
                default:
                    $image = $imageResource;
            }
        }

        imagejpeg($image, $destination_url, $quality);
        cropImage($source_url, $width, $height);
        return $destination_url;
    }
}

if (!function_exists('cropImage')) {
    function cropImage($source_url='', $crop_width=225, $crop_height=225, $destination_url = '')
    {
        ini_set('memory_limit', '-1');
        $image = Image::make($source_url);
        $image_width = $image->width();
        $image_height = $image->height();

        if($image_width < $crop_width && $crop_width < $crop_height){
            $image = $image->fit($crop_width, $image_height);
        }if($image_height < $crop_height  && $crop_width > $crop_height){
            $image = $image->fit($crop_width, $crop_height);
        }

  		$primary_cropped_image = $image;

        $croped_image = $primary_cropped_image->fit($crop_width, $crop_height);

		if($destination_url == ''){
			$source_url_details = pathinfo($source_url); 
			$destination_url = @$source_url_details['dirname'].'/'.@$source_url_details['filename'].'_'.$crop_width.'x'.$crop_height.'.'.@$source_url_details['extension']; 
		}

		$croped_image->save($destination_url); 
		return $destination_url; 
    }
}

/**
 * Upload Image function
 *
 * @param  String $image     Image File
 * @param  String $target_dir   Where file to be uploaded
 * @param  String $name_prefix   Prefix of file name
 * @return Array $return_data return status,status_message and file name
 */
if (!function_exists('uploadImage')) {

	function uploadImage($image, $target_dir, $name_prefix ='', $compress_size = array())
	{
		$return_data = array('status' => 'Success','status_message' => 'Uploaded Successfully','upload_src' => 'Local');
		if(isset($image)) {
			$tmp_name = $image->getPathName();

			if(UPLOAD_DRIVER == 'cloudinary') {
				$return_data['upload_src'] = 'Cloudinary';
				$c = cloudUpload($tmp_name);
				if ($c['status'] != "error") {
					$return_data['file_name'] = $c['message']['public_id'];
				}
				else {
					$return_data['status'] = 'Failed';
					$return_data['status_message'] = $c['message'];
				}
			}
			else {				
				$ext = strtolower($image->getClientOriginalExtension());
				$name = $name_prefix.time().'.'.$ext;

				$filename = dirname($_SERVER['SCRIPT_FILENAME']).$target_dir;

				if (!file_exists($filename)) {
					mkdir(dirname($_SERVER['SCRIPT_FILENAME']).$target_dir, 0777, true);
				}

				if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'svg' || $ext == 'webp') {
					if(!$image->move($filename, $name)) {
						$return_data['status'] = 'Failed';
						$return_data['status_message'] = 'Failed To Upload Image';
					}
					if($ext != 'gif' && $ext != 'webp' && count($compress_size) > 0) {
						foreach ($compress_size as $size) {
							compressImage($filename."/".$name, $filename."/".$name, $size["quality"], $size["width"], $size["height"]);
						}
					}
				}
				else {
					$return_data['status'] = 'Failed';
					$return_data['status_message'] =  trans('validation.mimes',['attribute' => 'Image','values'=>'Jpg,Jpeg,Png,Gif']);
				}

				$return_data['file_name'] = $name;
			}
		}
		return $return_data;
	}
}

/**
 * Upload Image to Cloudinary
 *
 * @return Array $return_data
 */
if (!function_exists('cloudUpload')) {
 	function cloudUpload($file,$last_src="",$resouce_type="image")
    {
        $site_name = str_replace(".","",SITE_NAME);
        try {
            $options = [ 'folder' => $site_name.'/'];
            if($resouce_type=="video") {
                \Cloudder::uploadVideo($file, null, $options);
            }
            else {
                \Cloudder::upload($file, null, $options);
            }
            $c=\Cloudder::getResult();
            $data['status']="success";
            $data['message']=$c;
        }
        catch (\Exception $e) {
            $data['status'] = "error";
            if($e->getCode() == '400') {
                $data['message'] = trans('messages.profile.image_size_exceeds_10mb');
            }
            else {
                $data['message']= $e->getMessage();
            }
        }
        return $data;
    }
}

/**
 * Convert Given Float To Nearest Half Integer
 * @return Int
 */
if (!function_exists('roundHalfInteger')) {
	function roundHalfInteger($value)
	{
		return floor($value * 2) / 2;
	}
}

/**
 * Check Current Environment
 *
 * @return Boolean true or false
 */
if (!function_exists('isLiveEnv')) {
	function isLiveEnv($environments = [])
	{
		if(count($environments) > 0) {
			array_push($environments, 'live');
			return in_array(env('APP_ENV'),$environments);
		}
		return env('APP_ENV') == 'live';
	}
}

/**
 * get protected String or normal based on env
 *
 * @param {string} $str
 *
 * @return {string}
 */
if (!function_exists('protectedString')) {
    
    function protectedString($str) {
        if(isLiveEnv()) {
            return substr($str, 0, 1) . '****' . substr($str,  -4);
        }
        return $str;
    }
}

if ( ! function_exists('updateEnvConfig'))
{
    function updateEnvConfig($key, $value)
    {
        $path = app()->environmentFilePath();

        $escaped = preg_quote('='.env($key), '/');
        try {
	        file_put_contents($path, preg_replace(
	            "/^{$key}{$escaped}/m",
	           "{$key}={$value}",
	           file_get_contents($path)
	        ));        	
        }
        catch (\Exception $e) {
        	dd($e->getMessage());
        }
    }
}
