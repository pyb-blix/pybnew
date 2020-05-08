<?php

/**
 * StartService Provider
 *
 * @package     Makent
 * @subpackage  Provider
 * @category    Service
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Currency;
use App\Models\Language;
use App\Models\ApiCredentials;
use App\Models\SiteSettings;
use App\Models\Dateformats;
use View;
use Config;
use Schema;
use Auth;
use App;
use Session;
use Request;
use App\Models\Messages;
use App\Models\Pages;
use App\Models\JoinUs;
use App\Models\RoomType;
use App\Models\HostExperienceCategories;
use App\Models\Admin;
use App\Models\PaymentGateway;

class StartServiceProvider extends ServiceProvider
{

	/**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
    
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    	if(env('DB_DATABASE') != '') {
    		$this->shareCommonData();
    		$this->bindModels();
	    	if(Schema::hasTable('currency')) {
	        	$this->currency();
	    	}
			
			if(Schema::hasTable('site_settings')) {
				$this->site_settings();
			}

			if(Schema::hasTable('language')) {
				$this->language();
			}
			
			if(Schema::hasTable('pages')) {
				$this->pages();
			}

			if(Schema::hasTable('join_us')) {
				$this->join_us();
			}

			if(Schema::hasTable('room_type')) {
				$this->room_type();
			}

	        /*HostExperiencePHPCommentStart*/
			if(Schema::hasTable('host_experience_categories')) {
				$this->host_experience_categories();
			}
	        /*HostExperiencePHPCommentEnd*/

			if(Schema::hasTable('dateformats')) {
				$this->date_format();
			}

			if(Schema::hasTable('payment_gateway')) {
				$this->payment_gateway();
			}
		}
    }
	
	// Share Currency Details to whole software
	public function currency()
	{
		// Currency code lists for footer
        $currency = Currency::where('status', '=', 'Active')->pluck('code', 'code');
        View::share('currency', $currency);
		
		// IP based user details
        $ip = getenv("REMOTE_ADDR");

        $valid =  preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/', $ip);

        $default_country = 'India';
        $default_country_code = 'IN';

        $default_currency = Currency::where('status', '=', 'Active')->where('default_currency', '1');

        if($valid) {
	        $result = unserialize(@file_get_contents('http://www.geoplugin.net/php.gp?ip='.$ip));

	        // Default Currency code for footer
	        if($result['geoplugin_currencyCode']) {
	        	$default_currency 		= Currency::where('status', '=', 'Active')->where('code', $result['geoplugin_currencyCode']);
            	$default_country 		= $result['geoplugin_countryName'];
            	$default_country_code 	= $result['geoplugin_countryCode'];
	        }
        }

		if($default_currency->count()) {
			$default_currency 		= $default_currency->first();
		}
		else {
			$default_currency 		= Currency::whereStatus('Active')->first();
		}

		Session::put('currency', $default_currency->code);
		$symbol = Currency::original_symbol($default_currency->code);
		Session::put('symbol', $symbol);
        $currency = Currency::where('status', '=', 'Active')->where('default_currency', '=', '1')->first();
		define('DEFAULT_CURRENCY', $currency->code);
		View::share('default_currency', $default_currency);
		View::share('default_country', $default_country);
		View::share('default_country_code', $default_country_code);
	}
	
	// Share Language Details to whole software
	public function language()
	{
		// Language lists for footer
        $language = Language::translatable()->pluck('name', 'value');
        View::share('language', $language);  
        $language = Language::translatable()->get();
        View::share('lang', $language);
		
		// Default Language for footer
		$default_language = Language::translatable()->where('default_language', '=', '1')->limit(1)->get();
        View::share('default_language', $default_language);
        
        if(Request::segment(1) == ADMIN_URL){
		$default_language = Language::translatable()->where('value', 'en')->get();
		//View::share('default_language', $default_language);
		}

        if($default_language->count() > 0) {
			Session::put('language', $default_language[0]->value);
			App::setLocale($default_language[0]->value);
		}
	}
	
	// Share Static Pages data to whole software
	public function pages()
	{
		// Pages lists for footer
        $company_pages = Pages::select('id', 'url', 'name')->where('footer','yes')->where('under', 'company')->where('status', '=', 'Active')->get();
        $discover_pages = Pages::select('id', 'url', 'name')->where('footer','yes')->where('under', 'discover')->where('status', '=', 'Active')->get();
        $hosting_pages = Pages::select('id', 'url', 'name')->where('footer','yes')->where('under', 'hosting')->where('status', '=', 'Active')->get();

        View::share('company_pages', $company_pages);
        View::share('discover_pages', $discover_pages);
        View::share('hosting_pages', $hosting_pages);
	}
	
	// Share Join Us data to whole software
	public function join_us()
	{
		$join_us = JoinUs::get();
		// Share App Links to view files
        View::share('play_store_link', $join_us[6]->value);
        View::share('app_store_link', $join_us[7]->value);

        // Remove App links from join us
        $join_us->forget([6,7]);
		View::share('join_us', $join_us);
	}
	
	// Share Room Type data to whole software
	public function room_type()
	{
		$room_type = RoomType::active_all();
		View::share('header_room_type', $room_type);
	}
	// Share category data to whole software
	public function host_experience_categories()
	{
		$host_experience_category = HostExperienceCategories::where('status','Active')->get();
		View::share('host_experience_category', $host_experience_category);
	}
	
	public function get_image_url($src,$url)
	{

		$photo_src=explode('.',$src);

        if(count($photo_src)>1)
        {
        	$rand=str_random(6);
        	return $url.'images/logos/'.$src.'?v='.$rand;
        }
        else
        {
        	$options['secure']=TRUE;
        	// $options['height']=100;
         	// $options['width']=150;
        	$options['crop']	= 'fill';
        	Config::set('cloudder.scaling', array());
            return $src=\Cloudder::show($src,$options);
        }
	}
	public function get_footer_image_url($src,$url)
	{

		$photo_src=explode('.',$src);

        if(count($photo_src)>1)
        {
        	return $url.'images/logos/'.$src;
        }
        else
        {
        	$options['secure']=TRUE;
        	// $options['height']=600;
            // $options['width']=600;
            $options['crop']	= 'fill';
        	Config::set('cloudder.scaling', array());
            return $src=\Cloudder::show($src,$options);
        }
	}
	public function get_help_image_url($src,$url)
	{

		$photo_src=explode('.',$src);

        if(count($photo_src)>1)
        {
        	$rand=str_random(6);
        	return $url.'images/logos/'.$src.'?v='.$rand;
        }
        else
        {
        	$options['secure']=TRUE;
        	// $options['height']=647;
         //    $options['width']=1002;
        	$options['crop']	= 'fill';
        	Config::set('cloudder.scaling', array());
            return $src=\Cloudder::show($src,$options);
        }
	}
	public function get_favicon_url($src)
    {
        $photo_src=explode('.',$src);

        if(count($photo_src)>1)
        {
        	$rand=str_random(6);
            return url('images/logos/'.$src.'?v='.$rand);
        }
        else
        {
            $options['secure']=TRUE;
            $options['height']=16;
            $options['width']=16;
            Config::set('cloudder.scaling', array());
            return $src=\Cloudder::show($src,$options);
        }
    }
	public function get_video_url($src,$url)
	{
		$photo_src=explode('.',$src);

        if(count($photo_src)>1)
        {
        	$rand=str_random(6);
        	return $url.'uploads/video/'.$src.'?v='.$rand;
        }
        else
        {
        	$options['secure']=TRUE;
        	// $options['height']=16;
         	// $options['width']=16;
        	$options['resource_type']="video";
        	Config::set('cloudder.scaling', array());
            return $src=\Cloudder::show($src,$options);
        }
	}

	// Share Site Settings data to whole software
	public function site_settings()
	{
        $site_settings = SiteSettings::all();

        View::share('site_settings', $site_settings);

        if(env('DB_DATABASE') != '') {
    		if(Schema::hasTable('admin')) {
    			$admin_email = @Admin::first()->email;
    			View::share('admin_email', $admin_email);
    		}
    	}
		
		define('SITE_NAME', $site_settings[0]->value);
		define('LOGO_URL', $this->get_image_url($site_settings[2]->value,$site_settings[14]->value));
		define('EMAIL_LOGO_URL', $this->get_image_url($site_settings[7]->value,$site_settings[14]->value));
		define('SITE_DATE_FORMAT', $site_settings[11]->value);
		define('PAYPAL_CURRENCY_CODE', $site_settings[12]->value);
		define('ADMIN_URL', $site_settings[17]->value);
		define('PAYPAL_CURRENCY_SYMBOL', Currency::original_symbol($site_settings[12]->value));
		define('UPLOAD_DRIVER', $site_settings[18]->value);
		define('MINIMUM_AMOUNT', $site_settings[19]->value);
		define('MAXIMUM_AMOUNT', $site_settings[20]->value);

		View::share('site_name', $site_settings[0]->value);
		View::share('head_code', $site_settings[1]->value);
		View::share('logo', $this->get_image_url($site_settings[2]->value,$site_settings[14]->value));
		View::share('home_logo', $this->get_image_url($site_settings[3]->value,$site_settings[14]->value));
		View::share('email_logo', $this->get_image_url($site_settings[7]->value,$site_settings[14]->value));
		View::share('favicon', $this->get_favicon_url($site_settings[5]->value,$site_settings[14]->value));
		View::share('logo_style', 'background:rgba(0, 0, 0, 0) url('.$this->get_image_url($site_settings[2]->value,$site_settings[14]->value).') no-repeat scroll 0 0;');
		View::share('home_logo_style', 'background:rgba(0, 0, 0, 0) url('.$this->get_image_url($site_settings[3]->value,$site_settings[14]->value).') no-repeat scroll 0 0;');

		View::share('footer_cover_image', $this->get_footer_image_url($site_settings[9]->value,$site_settings[14]->value));
		View::share('help_page_cover_image',$this->get_help_image_url($site_settings[10]->value,$site_settings[14]->value));
		View::share('home_page_stay_image',$this->get_help_image_url($site_settings[22]->value,$site_settings[14]->value));
		View::share('home_page_experience_image',$this->get_help_image_url($site_settings[23]->value,$site_settings[14]->value));

		View::share('site_date_format', $site_settings[11]->value);
		View::share('version', $site_settings[16]->value);
		// View::share('version', str_random(5)); // For checking purpose All JS & CSS loaded without cache
		View::share('support_number', $site_settings[21]->value);
		Config::set('site_name', $site_settings[0]->value);

		if($site_settings[14]->value == '' && @$_SERVER['HTTP_HOST'] && !\App::runningInConsole()){
			$url = "http://".$_SERVER['HTTP_HOST'];
			$url .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);

			SiteSettings::where('name','site_url')->update(['value' =>	$url]);
		}
	}

	public function date_format(){
		$site_date_format = SiteSettings::where('name','site_date_format')->first();
		$dateformat = Dateformats::where('id',$site_date_format['value'])->first();

		View::share('daterangepicker_format', $dateformat['daterangepicker_format']);
		View::share('datepicker_format', $dateformat['uidatepicker_format']);
		View::share('php_format_date', $dateformat['php_format']);

		define('PHP_DATE_FORMAT', $dateformat['php_format']);
		define('DISPLAY_DATE_FORMAT', $dateformat['display_format']);
	}

	public function payment_gateway()
	{
		$stripe_credentials = PaymentGateway::where('site', 'Stripe')->pluck('value', 'name');
        View::share('stripe_publish_key',$stripe_credentials["publish"]);
        View::share('stripe_secret_key',$stripe_credentials["secret"]);
	}

	protected function shareCommonData()
    {
        $acceptable_mimes = collect(['jpeg','jpg','png','gif']);

        view()->share('acceptable_mimes',$acceptable_mimes);
    }

    protected function bindModels()
    {
        if (Schema::hasTable('site_settings')) {
            $this->app->singleton('site_settings', function ($app) {
                $site_settings = SiteSettings::get();
                return $site_settings;
            });
        }

        if (Schema::hasTable('api_credentials')) {
            $this->app->singleton('api_credentials', function ($app) {
                $api_credentials = ApiCredentials::get();
                return $api_credentials;
            });
        }

        if (Schema::hasTable('currency')) {
            $this->app->singleton('currency', function ($app) {
                $currency = Currency::get();
                return $currency;
            });
        }
    }
}