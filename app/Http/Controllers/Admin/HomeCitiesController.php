<?php

/**
 * Home cities Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Home Cities
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\HomeCitiesDataTable;
use App\Models\HomeCities;
use App\Models\HomeCitiesLang;
use App\Models\language;
use App\Http\Start\Helpers;
use Validator;

class HomeCitiesController extends Controller
{
    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->helper   = new Helpers;
        $this->view_data['main_title'] = 'Home Cities';
        $this->base_view_path = 'admin.home_cities.';
    }

    /**
     * Display a listing of the resource.
     *
     * @param array $dataTable  Instance of KindOfSpaceDataTable
     * @return \Illuminate\Http\Response
     */
    public function index(HomeCitiesDataTable $dataTable)
    {
        return $dataTable->render($this->base_view_path.'view',$this->view_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->view_data['language'] = Language::get();
        return view($this->base_view_path.'add', $this->view_data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate Data
        $validate_return = $this->validate_request_data($request->all());
        if($validate_return) {
            return $validate_return;
        }

        if(UPLOAD_DRIVER=='cloudinary') {
            $source = 'Cloudinary';
            $c=$this->helper->cloud_upload($request->file('image'));
            if($c['status']!="error") {
                $filename=$c['message']['public_id'];    
            }
            else {
                flash_message('danger', $c['message']);
                return redirect()->route('home_cities');
            }
        }
        else {
            $source    = 'Local';
            $image     =   $request->file('image');
            $extension =   $image->getClientOriginalExtension();
            $filename  =   'home_city_'.time() . '.' . $extension;

            $success = $image->move('images/home_cities', $filename);

            if(!$success) {
                return redirect()->back()->withError('Could not upload Image');
            }
        }

        $home_cities = new HomeCities;

        for($i=0;$i < count($request->lang_code);$i++) {

            if($request->lang_code[$i] == "en") {
                $home_cities->name  = $request->name[$i];
                $home_cities->display_name = $request->display_name;
                $home_cities->latitude = $request->latitude;
                $home_cities->longitude = $request->longitude;
                $home_cities->image = $filename;
                $home_cities->source= $source;
                $home_cities->save();
                $lastInsertedId = $home_cities->id;
            }
            else {
                $home_cities_lang = new HomeCitiesLang;
                $home_cities_lang->home_cities_id   = $lastInsertedId;
                $home_cities_lang->lang_code   = $request->lang_code[$i];
                $home_cities_lang->name        = $request->name[$i];      
                $home_cities_lang->save();
            }

        }
        flash_message('success', 'Added Successfully');
        return redirect()->route('home_cities');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->view_data['result']      = HomeCities::findOrFail($id);
        $this->view_data['language']    = Language::get();
        $all_language                   = Language::select('value')->get();
        $this->view_data['langresult']  = HomeCitiesLang::where('home_cities_id',$id)->whereIn('lang_code',$all_language)->get();

        return view($this->base_view_path.'edit', $this->view_data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate Data
        $validate_return = $this->validate_request_data($request->all(),$id);
        if($validate_return) {
            return $validate_return;
        }
        
        $lang_id_arr = $request->lang_id;
        unset($lang_id_arr[0]); 

        if(empty($lang_id_arr)) {
            $home_cities_lang = HomeCitiesLang::where('home_cities_id',$id); 
            $home_cities_lang->delete();
        }

        $room_del = HomeCitiesLang::select('id')->where('home_cities_id',$id)->get();
        foreach($room_del as $values) {
            if(!in_array($values->id,$lang_id_arr)) {
                $home_cities_lang = HomeCitiesLang::find($values->id); 
                $home_cities_lang->delete();
            }
        }

        $home_cities = HomeCities::find($id);
        $image     =   $request->file('images');

        if($image) {
            if(UPLOAD_DRIVER=='cloudinary') {
                $source = 'Cloudinary';
                $c = $this->helper->cloud_upload($request->file('images'));  
                if($c['status']!="error") {
                    $filename=$c['message']['public_id'];    
                }
                else {                             
                    flash_message('danger', $c['message']);
                    return redirect()->route('home_cities');
                }
            }
            else {
                $source    = 'Local';
                $extension =   $image->getClientOriginalExtension();
                $filename  =   'home_city_'.time() . '.' . $extension;
                $success = $image->move('images/home_cities', $filename);
                $compress_success = $this->helper->compress_image('images/home_cities/'.$filename, 'images/home_cities/'.$filename, 80);
                if(!$success) {
                    return redirect()->back()->withError('Could not upload Image');
                }

                chmod('images/home_cities/'.$filename, 0777);
            }
            $home_cities->image = $filename;
            $home_cities->source    = $source;
        }

        for($i=0;$i < count($request->lang_code);$i++) {

            if($request->lang_code[$i] == "en") {
                $home_cities->name      = $request->name[$i];
                $home_cities->display_name = $request->display_name;
                $home_cities->latitude  = $request->latitude;
                $home_cities->longitude = $request->longitude;
                $home_cities->save();
            }
            else {
                if(isset($request->lang_id[$i])) {
                    $home_cities_lang = HomeCitiesLang::find($request->lang_id[$i]);
                    $home_cities_lang->lang_code   = $request->lang_code[$i];
                    $home_cities_lang->name        = $request->name[$i];            
                    $home_cities_lang->save();            
                } 
                else {
                    $home_cities_lang =  new HomeCitiesLang; 
                    $home_cities_lang->home_cities_id   = $home_cities->id;    
                    $home_cities_lang->lang_code   = $request->lang_code[$i];
                    $home_cities_lang->name        = $request->name[$i];              
                    $home_cities_lang->save();
                }
            }
        }

        flash_message('success', 'Updated Successfully');
        return redirect()->route('home_cities');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $home_cities = HomeCities::find($id);
        if(!$home_cities) {
            return redirect()->route('home_cities');
        }

        HomeCitiesLang::where('home_cities_id',$id)->delete();
        $home_cities = HomeCities::where('id',$id)->delete();
        flash_message('success', 'Deleted Successfully');
        return redirect()->route('home_cities');

    }

    /**
     * Validate Given Request Data.
     *
     * @param  Array  $request_data
     * @param  int  $id
     * @return \Illuminate\Http\Response | void
     */
    protected function validate_request_data($request_data, int $id = 0)
    {
        // Add Home Cities Validation Rules
        $rules = array(
            'image'       => 'required|mimes:jpg,png,gif,jpeg,webp',
            'name'        => 'required',
            'display_name'=> 'required',
            'latitude'    => 'required',
            'longitude'   => 'required',
        );

        if($id != 0) {
            $rules['image'] = 'mimes:jpg,png,gif,jpeg,webp';
        }

        // Add Home Cities Validation Custom Names
        $attributes = array(
            'image'         => 'Image',
            'name'          => 'City Name',
            'display_name'  => 'Display Name',
            'order'         => 'Position',
        );

        // Validate Request
        $validator = \Validator::make($request_data, $rules, $attributes);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }
}