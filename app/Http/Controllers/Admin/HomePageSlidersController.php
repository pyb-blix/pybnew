<?php

/**
 * HomePage Sliders Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Sliders
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\HomePageSlidersDataTable;
use App\Models\HomePageSlider;
use App\Http\Start\Helpers;

class HomePageSlidersController extends Controller
{
    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->helper   = new Helpers;
        $this->view_data['main_title'] = 'Home page Slider';
        $this->base_view_path = 'admin.home_page_sliders.';
    }

    /**
     * Display a listing of the resource.
     *
     * @param array $dataTable  Instance of KindOfSpaceDataTable
     * @return \Illuminate\Http\Response
     */
    public function index(HomePageSlidersDataTable $dataTable)
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

        $image     =   $request->file('image');

        if(UPLOAD_DRIVER=='cloudinary') {
            $c=$this->helper->cloud_upload($image);
            if($c['status']!="error") {
                $filename=$c['message']['public_id'];
                $source    = 'Cloudinary';
            }
            else {
                flash_message('danger', $c['message']); // Call flash message function
                return redirect()->route('homepage_sliders');
            }
        }
        else {
            $extension = $image->getClientOriginalExtension();
            $filename  = 'home_page_slider_'.time() . '.' . $extension;
            $source    = 'Local';

            $success = $image->move('images/slider', $filename);

            if(!$success)
                return back()->withError('Could not upload Image');
        }

        $slider = new HomePageSlider;

        $slider->image          = $filename;
        $slider->source         = $source;
        $slider->order          = $request->order; 
        $slider->name           = $request->name; 
        $slider->description    = $request->description; 
        $slider->status         = $request->status;

        $slider->save();

        flash_message('success', 'Added Successfully');
            return redirect()->route('homepage_sliders');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->view_data['result'] = HomePageSlider::findOrFail($id);
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

        $slider = HomePageSlider::findOrFail($id);

        $image  = $request->file('image');

        if($image) {
            if(UPLOAD_DRIVER=='cloudinary') {
                $c=$this->helper->cloud_upload($request->file('image'));
                if($c['status'] != "error") {
                    $filename=$c['message']['public_id'];
                    $source    = 'Cloudinary';
                }
                else {
                    flash_message('danger', $c['message']);
                    return redirect()->route('homepage_sliders');
                }
            }
            else {
                $extension = $image->getClientOriginalExtension();
                $filename  = 'home_page_slider_'.time() . '.' . $extension;
                $source    = 'Local';

                $success = $image->move('images/slider', $filename);
                if(!$success)
                    return redirect()->back()->withError('Could not upload Image');
            }
            $slider->image = $filename;
            $slider->source = $source;
        }

        $slider->order       = $request->order;
        $slider->status      = $request->status;
        $slider->name        = $request->name;
        $slider->description = $request->description;
        $slider->save();

        flash_message('success', 'Updated Successfully');
        return redirect()->route('homepage_sliders');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $slider = HomePageSlider::find($id);
        if($slider != '') {
            $slider->delete();
            flash_message('success', 'Deleted Successfully');
        }

        return redirect()->route('homepage_sliders');
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
        // Add Slider Validation Rules
        $rules = array(
            'image'         => 'required|mimes:jpg,png,gif,jpeg,webp',
            'order'         => 'required',
            'name'          => 'required',
            'description'   => 'required',
            'status'        => 'required',
        );

        if($id != 0) {
            $rules['image'] = 'mimes:jpg,png,gif,jpeg,webp';
        }

        // Add Slider Validation Custom Names
        $attributes = array(
            'image'         => 'Image',
            'order'         => 'Position', 
            'status'        => 'Status',
            'name'          => 'Name',
            'description'   => 'Description Address',
        );

        // Validate Request
        $validator = \Validator::make($request_data, $rules, $attributes);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }
}