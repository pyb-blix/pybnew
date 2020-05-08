<?php

/**
 * Site Settings Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Site Settings
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SiteSettings;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Dateformats;
use App\Http\Start\Helpers;
use Validator;
use Image;
use Artisan;
use App;

class SiteSettingsController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->view_data['navigation_menu'] = 'site_settings';
        $this->helper = new Helpers;
    }

    /**
     * Load View and Update Site Settings Data
     *
     * @return redirect     to site_settings
     */
    public function index(Request $request)
    {
        if(!$_POST)
        {
            $this->view_data['dateformats'] = Dateformats::where('status','Active')->pluck('display_format','id');
            $this->view_data['result']   = SiteSettings::get();
            
            $this->view_data['currency'] = Currency::where('status','Active')->pluck('code', 'id');
            $this->view_data['language'] = Language::translatable()->pluck('name', 'id');
            $this->view_data['default_currency'] = Currency::where('default_currency',1)->first()->id;
            $this->view_data['default_upload_driver'] = SiteSettings::where('name','upload_driver')->first()->value;
            $this->view_data['default_language'] = Language::where('default_language',1)->first()->id;
            $this->view_data['maintenance_mode'] = (App::isDownForMaintenance()) ? 'down' : 'up';
            $this->view_data['paypal_currency'] = Currency::where(['status' => 'Active', 'paypal_currency' => 'Yes'])->orderBy('code', 'ASC')->pluck('code', 'code');

            return view('admin.site_settings', $this->view_data);
        }
        else if($request->submit)
        {
            // Site Settings Validation Rules

            $rules = array(
                'site_name' => 'required',
                'minimum_price' =>'required|numeric|min:1|integer|maxminstrict:'.$request->maximum_price,
                'maximum_price' =>'required|numeric|integer', 
                'logo'         => 'image|mimes:jpg,png,jpeg,gif,svg,webp',
                'email_logo'   => 'image|mimes:jpg,png,jpeg,gif,svg,webp',
                'favicon'   => 'image|mimes:jpg,png,jpeg,gif,svg,webp',
                'footer_cover_image'   => 'image|mimes:jpg,png,gif,svg,webp,jpeg',
                'help_page_cover_image'   => 'image|mimes:jpg,png,gif,svg,webp,jpeg',
                'home_page_stay_image'   => 'image|mimes:jpg,png,gif,svg,webp,jpeg',
                'home_page_experience_image'   => 'image|mimes:jpg,png,gif,svg,webp,jpeg',
                'admin_url' => 'required|alpha_dash'
            );

            // Site Settings Validation Custom Names
            $niceNames = array(
                'site_name'   => 'Site Name',
                'minimum_price' => 'Minimum Price',
                'maximum_price' => 'Maximum Price',
                'logo'        => 'logo Image',
                'email_logo'  => 'Email logo',
                'favicon'     => 'favicon logo',
                'footer_cover_image'     => 'Footer Image',
                'help_page_cover_image'  => 'Help Image',
                'home_page_stay_image'  => 'Home Page Stay Image',
                'home_page_experience_image'  => 'Home Page Experience Image',
                'admin_url'=>'Admin PrefiX',
            );

            $messages = array( 
                'maxminstrict' => 'Minimum Price should be lesser than Maximum Price',
                'integer' => 'The :attribute must be numeric.'
            );

            $validator = Validator::make($request->all(), $rules,$messages);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }

            $image          =   $request->file('logo');
            $home_image     =   $request->file('home_logo');
            $email_image    =   $request->file('email_logo');
            $favicon        =   $request->file('favicon');

            if($image) {
                $extension      =   $image->getClientOriginalExtension();
                $filename       =   'logo' . '.' . $extension;
                if(UPLOAD_DRIVER=='cloudinary')
                {
                    $last_src=SiteSettings::where(['name' => 'logo'])->get()->first()->value;
                    $c=$this->helper->cloud_upload($image,$last_src);
                    if($c['status']!="error")
                    {
                        $filename=$c['message']['public_id'];    
                    }
                    else
                    {
                        $this->helper->flash_message('danger', $c['message']); // Call flash message function
                        return redirect($request->admin_url.'/site_settings');
                    }
                }
                else
                {
                    $success = $image->move('images/logos', $filename);
            
                    if(!$success)
                        return back()->withError('Could not upload Image');
                }

                SiteSettings::where(['name' => 'logo'])->update(['value' => $filename]);
            }
            
            if($home_image) {
                $extension      =   $home_image->getClientOriginalExtension();
                $filename       =   'home_logo' . '.' . $extension;
                if(UPLOAD_DRIVER=='cloudinary')
                {
                    $last_src=SiteSettings::where(['name' => 'home_logo'])->get()->first()->value;
                    $c=$this->helper->cloud_upload($home_image,$last_src);
                    if($c['status']!="error")
                    {
                        $filename=$c['message']['public_id'];    
                    }
                    else
                    {
                        $this->helper->flash_message('danger', $c['message']); // Call flash message function
                        return redirect($request->admin_url.'/site_settings');
                    }
                }
                else
                {
                    $success = $home_image->move('images/logos', $filename);
        
                    if(!$success)
                        return back()->withError('Could not upload Image');
                }

                SiteSettings::where(['name' => 'home_logo'])->update(['value' => $filename]);
            }
            
            if($email_image) {
                $extension      =   $email_image->getClientOriginalExtension();
                $filename       =   'email_logo' . '.' . $extension;
                if(UPLOAD_DRIVER=='cloudinary')
                {
                    $last_src=SiteSettings::where(['name' => 'email_logo'])->get()->first()->value;
                    $c=$this->helper->cloud_upload($email_image,$last_src);
                    if($c['status']!="error")
                    {
                        $filename=$c['message']['public_id'];    
                    }
                    else
                    {
                        $this->helper->flash_message('danger', $c['message']); // Call flash message function
                        return redirect($request->admin_url.'/site_settings');
                    }
                }
                else
                {
                    $success = $email_image->move('images/logos', $filename);
        
                    if(!$success)
                        return back()->withError('Could not upload Image');
                }

                SiteSettings::where(['name' => 'email_logo'])->update(['value' => $filename]);
            }

            if($favicon) {
                $extension      =   $favicon->getClientOriginalExtension();
                $filename       =   'favicon' . '.' . $extension;
                if(UPLOAD_DRIVER=='cloudinary')
                {
                    $last_src=SiteSettings::where(['name' => 'favicon'])->get()->first()->value;
                    $c=$this->helper->cloud_upload($favicon,$last_src);
                    if($c['status']!="error")
                    {
                        $filename=$c['message']['public_id'];    
                    }
                    else
                    {
                        $this->helper->flash_message('danger', $c['message']); // Call flash message function
                        return redirect($request->admin_url.'/site_settings');
                    }
                }
                else
                {
                    $success = $favicon->move('images/logos', $filename);
        
                    if(!$success)
                        return back()->withError('Could not upload Video');
                }

                SiteSettings::where(['name' => 'favicon'])->update(['value' => $filename]);
            }

            $footer_cover_image = $request->footer_cover_image;

            if($footer_cover_image){
                $extension = $footer_cover_image->getClientOriginalExtension(); 
                $filename = 'footer_cover_image'.'.'.$extension;
                if(UPLOAD_DRIVER=='cloudinary')
                {
                    $last_src=SiteSettings::where(['name' => 'footer_cover_image'])->get()->first()->value;
                    $c=$this->helper->cloud_upload($request->file('footer_cover_image'),$last_src);
                    if($c['status']!="error")
                    {
                        $filename=$c['message']['public_id'];    
                    }
                    else
                    {
                        $this->helper->flash_message('danger', $c['message']); // Call flash message function
                        return redirect($request->admin_url.'/site_settings');
                    }
                }
                else
                {
                    $success = $footer_cover_image->move('images/logos', $filename); 
                    if(!$success)
                        return back()->withError('Could not upload Image');
                }

                SiteSettings::where(['name' => 'footer_cover_image'])->update(['value' => $filename]);
            }
            $help_page_cover_image = $request->help_page_cover_image;

            if($help_page_cover_image){
                $extension = $help_page_cover_image->getClientOriginalExtension(); 
                $filename = 'help_page_cover_image'.'.'.$extension;
                if(UPLOAD_DRIVER=='cloudinary')
                {
                    $last_src=SiteSettings::where(['name' => 'help_page_cover_image'])->get()->first()->value;
                    $c=$this->helper->cloud_upload($request->file('help_page_cover_image'),$last_src);
                    if($c['status']!="error")
                    {
                        $filename=$c['message']['public_id'];    
                    }
                    else
                    {
                        $this->helper->flash_message('danger', $c['message']); // Call flash message function
                        return redirect($request->admin_url.'/site_settings');
                    }
                }
                else
                {
                    $success = $help_page_cover_image->move('images/logos', $filename); 
                    if(!$success)
                        return back()->withError('Could not upload Image');
                }
                

                SiteSettings::where(['name' => 'help_page_cover_image'])->update(['value' => $filename]);
            }

            $home_page_stay_image = $request->home_page_stay_image;
            if($home_page_stay_image){
                $extension = $home_page_stay_image->getClientOriginalExtension(); 
                $filename = 'home_page_stay_image'.'.'.$extension;
                if(UPLOAD_DRIVER=='cloudinary')
                {
                    $last_src=SiteSettings::where(['name' => 'home_page_stay_image'])->get()->first()->value;
                    $c=$this->helper->cloud_upload($request->file('home_page_stay_image'),$last_src);
                    if($c['status']!="error")
                    {
                        $filename=$c['message']['public_id'];    
                    }
                    else
                    {
                        $this->helper->flash_message('danger', $c['message']); // Call flash message function
                        return redirect($request->admin_url.'/site_settings');
                    }
                }
                else
                {
                    $success = $home_page_stay_image->move('images/logos', $filename); 
                    if(!$success)
                        return back()->withError('Could not upload Image');
                }
                

                SiteSettings::where(['name' => 'home_page_stay_image'])->update(['value' => $filename]);
            }

            $home_page_experience_image = $request->home_page_experience_image;

            if($home_page_experience_image){
                $extension = $home_page_experience_image->getClientOriginalExtension(); 
                $filename = 'home_page_experience_image'.'.'.$extension;
                if(UPLOAD_DRIVER=='cloudinary')
                {
                    $last_src=SiteSettings::where(['name' => 'home_page_experience_image'])->get()->first()->value;
                    $c=$this->helper->cloud_upload($request->file('home_page_experience_image'),$last_src);
                    if($c['status']!="error")
                    {
                        $filename=$c['message']['public_id'];    
                    }
                    else
                    {
                        $this->helper->flash_message('danger', $c['message']); // Call flash message function
                        return redirect($request->admin_url.'/site_settings');
                    }
                }
                else
                {
                    $success = $home_page_experience_image->move('images/logos', $filename); 
                    if(!$success)
                        return back()->withError('Could not upload Image');
                }
                

                SiteSettings::where(['name' => 'home_page_experience_image'])->update(['value' => $filename]);
            }

            SiteSettings::where(['name' => 'site_name'])->update(['value' => $request->site_name]);
             SiteSettings::where(['name' => 'minimum_amount'])->update(['value' => $request->minimum_price]);
              SiteSettings::where(['name' => 'maximum_amount'])->update(['value' => $request->maximum_price]);
            SiteSettings::where(['name' => 'head_code'])->update(['value' => $request->head_code]);
            SiteSettings::where(['name' => 'currency_provider'])->update(['value' => $request->currency_provider]);
            SiteSettings::where(['name' => 'site_date_format'])->update(['value' => $request->site_date_format]);
            SiteSettings::where(['name' => 'paypal_currency'])->update(['value' => $request->paypal_currency]);
            SiteSettings::where(['name' => 'version'])->update(['value' => $request->version]);
            SiteSettings::where(['name' => 'admin_prefix'])->update(['value' => $request->admin_url]);
            SiteSettings::where(['name' => 'upload_driver'])->update(['value' => $request->upload_driver]);
            SiteSettings::where(['name' => 'support_number'])->update(['value' => $request->customer_number]);

            Currency::where('status','Active')->update(['default_currency'=>0]);
            Language::translatable()->update(['default_language'=>0]);

            Currency::where('id', $request->default_currency)->update(['default_currency'=>1]);
            Language::where('id', $request->default_language)->update(['default_language'=>1]);

            Artisan::call($request->maintenance_mode);
            
            $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
            return redirect($request->admin_url.'/site_settings');
        }

        return redirect(ADMIN_URL.'/site_settings');
    }
}
