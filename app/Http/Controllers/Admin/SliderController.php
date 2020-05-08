<?php

/**
 * Slider Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Slider
 * @author      Trioangle Product Team
 * @version     0.8
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\SliderDataTable;
use App\Models\Slider;
use App\Http\Start\Helpers;
use Validator;

class SliderController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Slider
     *
     * @param array $dataTable  Instance of SliderDataTable
     * @return datatable
     */
    public function index(SliderDataTable $dataTable)
    {
        return $dataTable->render('admin.slider.view');
    }

    /**
     * Add a New Slider
     *
     * @param array $request  Input values
     * @return redirect     to Slider view
     */
    public function add(Request $request)
    {
        if(!$_POST)
        {
            return view('admin.slider.add');
        }
        else if($request->submit)
        {
            
            // Add Slider Validation Rules
            $rules = array(
                'image'   => 'required|mimes:jpg,png,gif,jpeg,svg,webp',
                'order'   => 'required', 
                'status'  => 'required',
            );

            // Add Slider Validation Custom Names
            $attributes = array(
                'image'    => 'Image',
                'order'   => 'Position', 
                'status'  => 'Status',
            );

            // Validate Request
            $request->validate($rules, array(), $attributes);

            if(UPLOAD_DRIVER=='cloudinary') {
                $c=$this->helper->cloud_upload($request->file('image'));
                if($c['status']!="error") {
                    $filename=$c['message']['public_id'];    
                }
                else {
                    $this->helper->flash_message('danger', $c['message']); // Call flash message function
                    return redirect(ADMIN_URL.'/slider');
                }
            }
            else {

                $image = $request->file('image');
                $extension =   $image->getClientOriginalExtension();
                $filename  =   'slider_'.time() . '.' . $extension;

                $success = $image->move('images/slider', $filename);
                $this->helper->compress_image('images/slider/'.$filename, 'images/slider/'.$filename, 80);

                if(!$success)
                    return back()->withError('Could not upload Image');
            }

            $slider = new Slider;

            $slider->image = $filename;
            $slider->order = $request->order; 
            $slider->status = $request->status;

            $slider->save();

            $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function
            return redirect(ADMIN_URL.'/slider');
        }

        return redirect(ADMIN_URL.'/slider');
    }

    /**
     * Update Slider Details
     *
     * @param array $request    Input values
     * @return redirect     to Slider View
     */
    public function update(Request $request)
    {
        if(!$_POST) {
			$data['result'] = Slider::find($request->id);

            return view('admin.slider.edit', $data);
        }
        else if($request->submit) {

            // Update Slider Validation Rules
            $rules = array(
                'image'   => 'mimes:jpg,png,gif,jpeg,svg,webp',
                'order'   => 'required', 
                'status'  => 'required',
            );

            // Update Slider Validation Custom Names
            $attributes = array(
                'order'   => 'Position', 
                'status'  => 'Status',
                'image'    => 'Image',
            );

            // Validate Request
            $request->validate($rules, array(), $attributes);

            $slider = Slider::find($request->id);

            $image     =   $request->file('image');

            if($image) {

                if(UPLOAD_DRIVER=='cloudinary') {
                    $c=$this->helper->cloud_upload($request->file('image'));
                    if($c['status']!="error") {
                        $filename=$c['message']['public_id'];    
                    }
                    else {
                        $this->helper->flash_message('danger', $c['message']); // Call flash message function
                        return redirect(ADMIN_URL.'/slider');
                    }
                }
                else {
                    $extension =   $image->getClientOriginalExtension();
                    $filename  =   'slider_'.time() . '.' . $extension;

                    $success = $image->move('images/slider', $filename);
                    $this->helper->compress_image('images/slider/'.$filename, 'images/slider/'.$filename, 80);

                    if(!$success)
                        return back()->withError('Could not upload Image');
                }
                $slider->image = $filename;
            }

            $slider->order = $request->order;
            $slider->status = $request->status;
            // $slider->front_end = $request->front_end;
            $slider->updated_at = date('Y-m-d H:i:s');

            $slider->save();

            $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function
            return redirect(ADMIN_URL.'/slider');
        }

        return redirect(ADMIN_URL.'/slider');
    }

    /**
     * Delete Slider
     *
     * @param array $request    Input values
     * @return redirect     to Slider View
     */
    public function delete(Request $request)
    {
        $slider = Slider::find($request->id);
        if($slider != ''){
            $slider->delete();
            $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function
        }

        return redirect(ADMIN_URL.'/slider');
    }
}
