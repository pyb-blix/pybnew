<?php
 
/**
 * Rooms Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Rooms
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\EmailController;
use App\Models\PropertyType;
use App\Models\PropertyTypeLang;
use App\Models\Language;
use App\Models\RoomType;
use App\Models\Rooms;
use App\Models\RoomsAddress;
use App\Models\BedType;
use App\Models\RoomsStepsStatus;
use App\Models\Country;
use App\Models\Amenities;
use App\Models\AmenitiesType;
use App\Models\RoomsPhotos;
use App\Models\RoomsPrice;
use App\Models\RoomsDescription;
use App\Models\RoomsDescriptionLang;
use App\Models\Calendar;
use App\Models\Currency;
use App\Models\Reservation;
use App\Models\SavedWishlists;
use App\Models\Messages;
use App\Models\SiteSettings;
use App\Models\RoomsPriceRules;
use App\Models\RoomsAvailabilityRules;
use App\Models\RoomsBeds;
use App\Models\User;
use Auth;
use DB;
use DateTime;
use Session;

class RoomsController extends Controller
{
    public function __construct()
    {
        $this->payment_helper = resolve("App\Http\Helper\PaymentHelper");
        $this->helper = resolve("App\Http\Start\Helpers");
    }

    /**
     * Load Your Listings View
     *
     * @return your listings view file
     */
    public function index()
    {
        $data['listed_result'] = Rooms::user()->listed()->verified()->get();

        $data['unlisted_result'] = Rooms::user()->where(function ($query) {
            $query->where('status', 'Unlisted')->orWhere('status', 'Pending')->orWhere('status', 'Resubmit')->orWhere('verified', 'Pending')->orWhere('verified', 'Resubmit')->orWhereNull('status');
        })->get();
        return view('rooms.listings', $data);
    }

    /**
     * Load List Your Space First Page
     *
     * @return list your space first view file
     */
    public function new_room()
    {
        $data['property_type'] = PropertyType::active_all();
        $data['room_type']     = RoomType::active_all();
        $data['country_list']  = Country::all()->pluck('short_name');

        return view('list_your_space.new', $data);
    }

    /**
     * Create a new Room
     *
     * @param array $request    Post values from List Your Space first page
     * @return redirect     to manage listing
     */
    public function create(Request $request)
    {
        $valid_country = $this->validateCountry($request->hosting['country']);

        if (!$valid_country['status']) {
            $this->helper->flash_message('error', $valid_country['status_message']);
            return back();
        }

        $rooms = new Rooms;

        $rooms->user_id       = Auth::user()->id;
        $rooms->sub_name      = RoomType::find($request->hosting['room_type'])->name.' in '.$request->hosting['city'];
        $rooms->property_type = $request->hosting['property_type_id'];
        $rooms->room_type     = $request->hosting['room_type'];
        $rooms->accommodates  = $request->hosting['person_capacity'];
        $rooms->calendar_type = 'Always';
        $rooms->save();

        $rooms_address = new RoomsAddress;

        $rooms_address->room_id        = $rooms->id;
        $rooms_address->address_line_1 = $request->hosting['street_number'] ? $request->hosting['street_number'].', ' : '';
        $rooms_address->address_line_1.= $request->hosting['route'];
        $rooms_address->city           = $request->hosting['city'];
        $rooms_address->state          = $request->hosting['state'];
        $rooms_address->country        = $request->hosting['country'];
        $rooms_address->postal_code    = $request->hosting['postal_code'];
        $rooms_address->latitude       = $request->hosting['latitude'];
        $rooms_address->longitude      = $request->hosting['longitude'];

        $rooms_address->save();

        $rooms_price = new RoomsPrice;

        $rooms_price->room_id       = $rooms->id;
        $rooms_price->currency_code = Session::get('currency');

        $rooms_price->save();

        $rooms_status = new RoomsStepsStatus;
        $rooms_status->calendar = 1;
        $rooms_status->room_id = $rooms->id;

        $rooms_status->save();

        $rooms_description = new RoomsDescription;

        $rooms_description->room_id = $rooms->id;

        $rooms_description->save();

        return redirect('manage-listing/'.$rooms->id.'/basics');
    }

    protected function getManagementData()
    {
        $data['property_type']  = PropertyType::dropdown();
        $data['room_type']      = RoomType::dropdown();
        $data['room_types']     = RoomType::where('status','Active')->limit(3)->get();
        $data['bed_type']       = BedType::active_all();
        $data['amenities']      = Amenities::active_all();
        $data['amenities_type'] = AmenitiesType::active_all();

        $data['length_of_stay_options'] = Rooms::getLenghtOfStayOptions();
        $data['availability_rules_months_options'] = Rooms::getAvailabilityRulesMonthsOptions();

        return $data;
    }

    /**
     * Manage Listing
     *
     * @param array $request    Post values from List Your Space first page
     * @param array $calendar   Instance of CalendarController
     * @return list your space main view file
     */
    public function manage_listing(Request $request, CalendarController $calendar)
    {
        $room_detail = Rooms::user()->findOrFail($request->id);

        $data  = $this->getManagementData();

        $data['result']         = $room_detail;
        $data['rooms_price']    = $data['result']->rooms_price;
        $data['get_single_bed_type'] = $data['result']->get_single_bed_type;
        $data['first_bed_type1'] = $data['result']->get_first_bed_type;
        $data['get_common_bed_type'] = $data['result']->get_common_bed_type;
        $data['first_bed_type'] = BedType::where('status','Active')->limit(4)->get();
        $data['get_bathrooms'] = @$data['result']->get_bathrooms;
        $data['get_common_bathrooms'] = @$data['result']->get_common_bathrooms;
        $data['room_type_is_shared']    = RoomType::where('status','Active')->pluck('is_shared', 'id');

        $data['room_id']        = $request->id;
        // It will get correct view file based on page name
        $data['room_step']      = $request->page;

        $data['rooms_status']   = RoomsStepsStatus::where('room_id',$request->id)->first();

        if($request->page == 'calendar' && $request->ajax()) {
            $data_calendar     = @json_decode($request['data']);
            $year              = @$data_calendar->year;
            $month             = @$data_calendar->month;
            $data['room_step'] = 'edit_calendar';
            $data['calendar']  = $calendar->generate($request->id, $year, $month);
        }
        else {
            $data['calendar']       = $calendar->generate($request->id);
        }

        $data['currency_symbol']= Currency::whereCode($data['rooms_price']->currency_code)->first()->original_symbol;
        $data['minimum_amount'] = currency_convert(DEFAULT_CURRENCY, $data['rooms_price']->currency_code, MINIMUM_AMOUNT);
        $data['prev_amenities'] = explode(',', $data['result']->amenities);
        $data['firstbedtypeid'] = BedType::where('status', 'Active')->first()->id;

        if($request->wantsJson()) {
            return response()
                ->view('list_your_space.'.$data['room_step'], $data, 200)
                ->header('Content-Type', 'text/html');
        }
        session()->forget('ajax_redirect_url');

        return view('list_your_space.main', $data);
    }

    protected function getUpdateInstance($room_id, $current_tab)
    {
        if($current_tab != '' && $current_tab != 'en') {
            $room_instance = RoomsDescriptionLang::where('room_id', $room_id)->where('lang_code', $current_tab)->first();
        }
        else {
            $room_instance = Rooms::find($room_id);
        }
        return $room_instance;
    }

    /**
     * Ajax List Your Space Update Rooms Values
     *
     * @param array $request    Post values from List Your Space first page
     * @return json success, steps_count
     */ 
    public function update_rooms(Request $request, EmailController $email_controller)
    {
        $data  = $request;
        $data  = json_decode($data['data']);
        $current_tab = ($request->current_tab) ? $request->current_tab : '';
        $rooms = $this->getUpdateInstance($request->id, $current_tab);
        $email = '';
        foreach($data as $key=>$value) {
            if($key != 'video') {
                $rooms->$key =$this->helper->phone_email_remove($value);

                $bed_room_count = ($rooms->bedrooms == null)?0:$rooms->bedrooms;
                RoomsBeds::where('room_id',$request->id)->where('bed_room_no','>',$bed_room_count)->where('bed_room_no','!=','common')->delete();
            }
            else {
                $search     = '#(.*?)(?:href="https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch?.*?v=))([\w\-]{10,12}).*#x';
                $count      = preg_match($search, $value);
                if($count == 1) {
                    $replace    = 'https://www.youtube.com/embed/$2';
                    $video      = preg_replace($search,$replace,$value);
                    $rooms->$key = $video;
                }
                else {
                    return json_encode(['success'=>'false', 'steps_count' => $rooms->steps_count]);
                }
            }

            if($key == 'booking_type')
                $rooms->$key = $value ?? NULL;

            if($key == 'status' && $value == 'Listed')
                $email = 'Listed';

            if($key == 'status' && $value == 'Unlisted'){
                $email = 'Unlisted';
                $rooms->recommended='No';
            }

            if($key != 'status') {
                $field = str_replace('_', ' ', $key); 
                $email_controller->room_details_updated($request->id, ucwords($field));
            }
        }

        $rooms->save();

        if($email == 'Listed')
            $email_controller->listed($request->id);

        if($email == 'Unlisted')
            $email_controller->unlisted($request->id);
        elseif($key != 'video' && $key != 'booking_type' && $key != 'cancel_policy' && (!$request->current_tab || $request->current_tab == 'en' && $key != 'status')) {
            $this->update_status($request->id);
        }

        $rooms = Rooms::find($request->id);

        if($key != 'video' && $key != 'status' && $key != 'booking_type' && $key != 'cancel_policy' && $key != 'booking_message' && (!$request->current_tab || $request->current_tab == 'en')) {
            if($rooms->status == 'Listed' && $rooms->verified == 'Approved') {
                $this->resubmit($request->id);
            }
        }

        $rooms_status = RoomsStepsStatus::find($request->id);
        return json_encode(['success'=>'true', 'steps_count' => $rooms->steps_count, 'video' => $rooms->video,'status' => $rooms->status,'basics_status' => $rooms_status->basics]);
    }

    /**
     * Update List Your Space Steps Count, It will calling from ajax update functions
     *
     * @param int $id    Room Id
     * @return true
     */ 
    public function update_status($id)
    {
        $result_rooms = Rooms::whereId($id)->first();

        $rooms_status = RoomsStepsStatus::find($id);
        $rooms_status->description  = 0;
        $rooms_status->basics       = 0;
        $rooms_status->photos       = 0;
        $rooms_status->pricing      = 0;
        $rooms_status->calendar     = 1;

        if($result_rooms->name != '' && $result_rooms->summary != '' ) {
            $rooms_status->description = 1;
        }

        $bed_types       = DB::table('bed_type')->where('status','Active')->select('id')->get()->pluck('id');
        $tot_bed_count = RoomsBeds::where('room_id', $id)->where('count', '>', 0)->whereIn('bed_id',$bed_types)->where('bed_room_no','!=','common')->count();

        if($tot_bed_count > 0) {
            $rooms_status->basics = 1;
        }

        $photos_count = RoomsPhotos::where('room_id', $id)->count();
        if($photos_count != 0) {
            $rooms_status->photos = 1;
        }

        $price = RoomsPrice::find($id);
        if($price != NULL && $price->night != 0 ) {
            $rooms_status->pricing = 1;
        }

        // if($result_rooms->calendar_type != NULL) {
        //     $rooms_status->calendar = 1;
        // }

        $rooms_status->save(); // Update Rooms Steps Count

        if($result_rooms->steps_count > 0 && $result_rooms->status != '') {
            $result_rooms->status = 'Unlisted';
            $result_rooms->verified = 'Pending';
            $result_rooms->save();

            //send awaiting for approval email to admin & host
            $this->sendApprovalMail($id);
        }

        if($result_rooms->steps_count == 0 && $result_rooms->status == 'Unlisted' ){
            $result_rooms->status = 'Pending';
            $result_rooms->verified = 'Pending';
            $result_rooms->save();

            $this->sendApprovalMail($id);
        }
        elseif ($result_rooms->steps_count == 0 && ($result_rooms->status == '' || $result_rooms->status == NULL)) {
            $this->sendApprovalMail($id);
        }

        return true;
    }

    /**
     * Load List Your Space Address Popup
     *
     * @param array $request    Input values
     * @return enter_address view file
     */ 
    public function enter_address(Request $request)
    {
        $data_result['room_id']   = $request->id;
        $data_result['room_step'] = $request->page;
        $data_result['country']   = Country::all()->pluck('long_name','short_name');

        $data  = $request;

        $data  = json_decode($data['data']);
        $country = Country::where('short_name', $data->country)->first();
        $data->country_name = $country ? $country->long_name : "";

        $data_result['result'] = $data;

        return view('list_your_space.enter_address', $data_result);
    }

    /**
     * Load List Your Space Address Location Not Found Popup
     *
     * @param array $request    Input values
     * @return enter_address view file
     */ 
    public function location_not_found(Request $request)
    {
        $data  = $request;

        $data  = json_decode($data['data']);

        $valid_country = $this->validateCountry($data->country);

        if (!$valid_country['status']) {
            return json_encode(['status' => "country_error"]);
        }

        $data->country_name = $valid_country['country_name'];

        $data_result['result'] = $data;

        return view('list_your_space.location_not_found', $data_result);
    }

    /**
     * Load List Your Space Verify Location Popup
     *
     * @param array $request    Input values
     * @return verify_location view file
     */
    public function verify_location(Request $request)
    {
        $data  = $request;
        $data  = json_decode($data['data']);

        $valid_country = $this->validateCountry($data->country);

        if (!$valid_country['status']) {
            return json_encode(['status' => "country_error"]);
        }

        $data->country_name = $valid_country['country_name'];
        $data_result['result'] = $data;

        return view('list_your_space.verify_location', $data_result);
    }

    /**
     * List Your Space Address Data
     *
     * @param array $request    Input values
     * @return json rooms_address result
     */
    public function finish_address(Request $request,EmailController $email_controller)
    {
        $data  = $request;
        $data  = json_decode($data['data']);

        $valid_country = $this->validateCountry($data->country);

        if (!$valid_country['status']) {
            return json_encode(['status' => "country_error"]);
        }

        $rooms = RoomsAddress::find($request->id); // Where condition for Update

        foreach($data as $key=>$value)
        {
            $rooms->$key = $value;          // Dynamic Update
        }

        $rooms->save();

        $rooms = Rooms::find($request->id);
        if(($rooms->status == 'Listed' || $rooms->status == 'Resubmit') && ($rooms->verified == 'Approved' || $rooms->verified == 'Resubmit')){
            $this->resubmit($request->id);
        }

        $rooms_status = RoomsStepsStatus::find($request->id);
        $rooms_status->location = 1;
        $rooms_status->save();

        $data_result = RoomsAddress::find($request->id);

        $email_controller->room_details_updated($request->id, 'Address');

        return json_encode($data_result);
    }

    /**
     * Ajax Update List Your Space Amenities
     *
     * @param array $request    Input values
     * @return json success
     */
    public function update_amenities(Request $request,EmailController $email_controller)
    {
        $rooms = Rooms::find($request->id);

        $rooms->amenities = rtrim($request->data,',');

        $rooms->save();

        $email_controller->room_details_updated($request->id, 'Amenities');

        return json_encode(['success'=>'true']);
    }

    /**
     * Ajax List Your Space Add Photos, it will upload multiple files
     *
     * @param array $request    Input values
     * @return json rooms_photos table result
     */
    public function add_photos(Request $request,EmailController $email_controller)
    {
        $all_photos = $request->photos;
        if(!isset($all_photos) || count($all_photos) == 0) {
            return json_encode(array('error_title' => ' Photo Error', 'error_description' => 'No Photos Selected'));
        }

        $room_id        = $request->id;
        $return_data    = array();
        $upload_errors  = array();
        $last_photo  = RoomsPhotos::whereRoomId($room_id)->latest('order_id')->first();
        $last_order_id  = optional($last_photo)->order_id;

        foreach($all_photos as $key => $image) {

            $target_dir = '/images/rooms/'.$room_id;

            $compress_size = array(
                ['quality' => 80, 'width' => 993, 'height' => 662],
                ['quality' => 80, 'width' => 1440, 'height' => 960],
                ['quality' => 80, 'width' => 450, 'height' => 250],
            );

            $upload_result = uploadImage($image,$target_dir,$key,$compress_size);

            if($upload_result['status'] != 'Success') {
                $error_description = $upload_result['status_message'];
                if(count($all_photos) > 1) {
                    $error_description = trans('messages.lys.invalid_image');
                }
                $upload_errors = array('error_title' => ' Photo Error', 'error_description' => $error_description);
            }
            else {
                $photos             = new RoomsPhotos;
                $photos->room_id   = $room_id;
                $photos->name       = $upload_result['file_name'];
                $photos->source     = $upload_result['upload_src'];
                $photos->order_id   = ++$last_order_id;
                $photos->save();

                $rooms = Rooms::find($request->id);
                if(($rooms->status == 'Listed' || $rooms->status == 'Resubmit') && ($rooms->verified == 'Approved' || $rooms->verified == 'Resubmit')) {
                    $this->resubmit($request->id);
                }
                $this->update_status($request->id);
            }
        }

        $email_controller->room_details_updated($request->id, 'Photos');

        $return_data['photos_list'] = RoomsPhotos::where('room_id',$room_id)->ordered()->get();
        $return_data['error'] = $upload_errors;

        return response()->json($return_data);
    }

    /**
     * Ajax List Your Space Delete Photo
     *
     * @param array $request    Input values
     * @return json success, steps_count
     */
    public function delete_photo(Request $request,EmailController $email_controller)
    {
        $photos          = RoomsPhotos::find($request->photo_id);
        if($photos != NULL){
            $file = $photos->original_name;
            $photos->delete();

            /*delete file from server*/
            $compress_images = ['_450x250.','_1440x960.','_1349x402.'];
            $this->helper->remove_image_file($file,"images/rooms/".$request->id,$compress_images);
            /*delete file from server*/

            $success = 'true';
        }
        else {
            $success = 'false';
        }

        $this->update_status($request->id);
        $email_controller->room_details_updated($request->id, 'Photos');

        $rooms = Rooms::find($request->id);
        $rooms_status = RoomsStepsStatus::find($request->id);
        return json_encode(['success'=>$success, 'steps_count' => $rooms->steps_count,'status' => $rooms->status, 'steps_count' => $rooms->steps_count]);
    }

    /**
     * Ajax List Your Space Photos List
     *
     * @param array $request    Input values
     * @return json rooms_photos table result
     */
    public function photos_list(Request $request)
    {
        $photos = RoomsPhotos::where('room_id', $request->id)->ordered()->get();
        return response()->json($photos);
    }

    /**
     * Ajax List Your Space Photos Highlights
     *
     * @param array $request    Input values
     * @return json success
     */
    public function photo_highlights(Request $request)
    {
        $photos = RoomsPhotos::find($request->photo_id);
        $photos->highlights = $request->data;
        $photos->save();

        return json_encode(['success'=>'true']);
    }

    /**
     * Ajax Update price rules in manage listing
     *
     * @param array $request    Input values
     * @return json success
     */
    public function update_price_rules(Request $request,EmailController $email_controller)
    {
        $rules    = [
            'period'    => 'required|integer|unique:rooms_price_rules,period,'.@$request->data['id'].',id,type,'.$request->type.',room_id,'.$request->id,
            'discount' => 'required|integer|between:1,99',
        ];
        if($request->type == 'early_bird') {
            $rules['period'] .= '|between:30,1080';
        }
        if($request->type == 'last_min') {
            $rules['period'] .= '|between:1,28';
        }

        $messages = [
            'period.integer'  => trans('validation.numeric', ['attribute' => trans('messages.lys.period')]),
            'discount.integer'  => trans('validation.numeric', ['attribute' => trans('messages.lys.discount')]),
        ];
        $attributes = [
            'period'    => trans('messages.lys.period'),
            'discount' => trans('messages.lys.discount'),
        ];

        $validator = \Validator::make($request->data, $rules, $messages, $attributes);

        if($validator->fails()) {
            $errors = @$validator->errors()->getMessages();
            return json_encode(['success' => 'false', 'errors' => $errors]);
        }
        $rule = $request->data;
        if(@$rule['id']) {
            $check = [
                'id' => $rule['id'],
                'room_id' => $request->id,
                'type'    => $request->type,
            ];
        }
        else {
            $check = [
                'room_id' => $request->id,
                'type'    => $request->type,
                'period'  => $rule['period']
            ];
        }

        $price_rule = RoomsPriceRules::firstOrNew($check);
        $price_rule->room_id = $request->id;
        $price_rule->type =  $request->type;
        $price_rule->period = $rule['period'];
        $price_rule->discount = $rule['discount'];

        $price_rule->save();

        return json_encode(['success'=>'true', 'id' => $price_rule->id]);
    }

    /**
     * Ajax Delete Price Rules
     *
     * @param array $request    Input values
     * @return json success
     */
    public function delete_price_rule(Request $request)
    {
        $id = $request->rule_id;
        RoomsPriceRules::where('id', $id)->delete();
        return json_encode(['success' => true]);
    }

    /**
     * Ajax Delete Availability Rules
     *
     * @param array $request    Input values
     * @return json success
     */
    public function delete_availability_rule(Request $request)
    {
        RoomsAvailabilityRules::where('id', $request->rule_id)->delete();
        return json_encode(['success' => true]);
    }

    /**
     * Ajax Update Reservation Settings values
     *
     * @param array $request    Input values
     * @return json success
     */
    public function update_reservation_settings(Request $request,EmailController $email_controller)
    {
        $room = Rooms::find($request->id);
        $rooms_price = $room->rooms_price;

        $rules    = [
            'minimum_stay' => 'integer|min:1|maxmin:'.$request->maximum_stay,
            'maximum_stay'  => 'integer|min:1'
        ];

        $messages = [
            'minimum_stay.maxmin'   => trans('validation.max.numeric', ['attribute' => trans('messages.lys.minimum_stay'), 'max' => trans('messages.lys.maximum_stay')]),
            'minimum_stay.integer'  => trans('validation.numeric', ['attribute' => trans('messages.lys.minimum_stay')]),
            'maximum_stay.integer'  => trans('validation.numeric', ['attribute' => trans('messages.lys.maximum_stay')]),
        ];
        $attributes = [
            'minimum_stay'    => trans('messages.lys.minimum_stay'),
            'maximum_stay' => trans('messages.lys.maximum_stay'),
        ];

        $request_data = $request->all();
        $request_data['minimum_stay'] = is_numeric($request->minimum_stay) ? $request->minimum_stay -0 : $request->minimum_stay;
        $request_data['maximum_stay'] = is_numeric($request->maximum_stay) ? $request->maximum_stay -0 : $request->maximum_stay;
        $validator = \Validator::make($request_data, $rules, $messages, $attributes);

        if($validator->fails()) {
            $errors = @$validator->errors()->getMessages();
            return json_encode(['success' => 'false', 'errors' => $errors]);
        }

        $rooms_price->minimum_stay = $request->minimum_stay ?: null;
        $rooms_price->maximum_stay = $request->maximum_stay ?: null;

        $rooms_price->save();

        return json_encode(['success'=>'true']);
    }

    /**
     * Ajax Update Availability rule values
     *
     * @param array $request    Input values
     * @return json success
     */
    public function update_availability_rule(Request $request,EmailController $email_controller)
    {
        $rules    = [
            'type'         => 'required',
            'start_date'   => 'required',
            'end_date'     => 'required',
            'minimum_stay' => 'required|integer|min:1|maxmin:'.@$request->availability_rule_item['maximum_stay'],
            'maximum_stay' => 'required|integer|min:1'
        ];

        $messages = [
            'minimum_stay.maxmin'   => trans('validation.max.numeric', ['attribute' => trans('messages.lys.minimum_stay'), 'max' => trans('messages.lys.maximum_stay')]),
            'maximum_stay.required_if' => trans('messages.lys.minimum_or_maximum_stay_required'),
            'minimum_stay.integer'  => trans('validation.numeric', ['attribute' => trans('messages.lys.minimum_stay')]),
            'maximum_stay.integer'  => trans('validation.numeric', ['attribute' => trans('messages.lys.maximum_stay')]),
        ];
        $attributes = [
            'type'          => trans('messages.lys.select_dates'),
            'start_date'    => trans('messages.lys.start_date'),
            'end_date'      => trans('messages.lys.end_date'),
            'minimum_stay'  => trans('messages.lys.minimum_stay'),
            'maximum_stay'  => trans('messages.lys.maximum_stay'),
        ];

        $request_data = $request->availability_rule_item;

        $validator = \Validator::make($request_data, $rules, $messages, $attributes);

        if($validator->fails()) {
            $errors = @$validator->errors()->getMessages();
            return json_encode(['success' => 'false', 'errors' => $errors]);
        }

        $rule = $request->availability_rule_item;
        $rooms = Rooms::where('id', $request->id)->first();

        $check = [
            'id' => @$rule['id'] ?: '',
        ];
        $availability_rule = RoomsAvailabilityRules::firstOrNew($check);
        $availability_rule->room_id = $rooms->id;
        $availability_rule->start_date = date('Y-m-d', $this->helper->custom_strtotime(@$rule['start_date'], PHP_DATE_FORMAT));
        $availability_rule->end_date = date('Y-m-d', $this->helper->custom_strtotime(@$rule['end_date'], PHP_DATE_FORMAT));
        $availability_rule->minimum_stay = @$rule['minimum_stay'] ?: null;
        $availability_rule->maximum_stay = @$rule['maximum_stay'] ?: null;
        $availability_rule->type = @$rule['type'] != 'prev' ? @$rule['type']: @$availability_rule->type;
        $availability_rule->save();

        return json_encode(['success'=>'true', 'availability_rules' => $rooms->availability_rules]);
    }

    /**
     * Load Rooms Detail View
     *
     * @param array $request    Input values
     * @return view rooms_detail
     */
    public function rooms_detail(Request $request)
    {
        $data['room_id']          = $request->id;

        $data['result']           = Rooms::with(['rooms_price' => function($query) {
            $query->with('currency');
        },'rooms_address','rooms_photos'])->findOrFail($request->id);

        $data['is_wishlist']      = SavedWishlists::where('user_id',@Auth::user()->id)->where('room_id',$request->id)->where('list_type','Rooms')->count();

        $data['user_details']   =   User::find($data['result']->user_id);

        if($data['result']->user_id != @Auth::user()->id && ($data['user_details']->status != 'Active' || $data['result']->status != 'Listed') ) {
            abort('404');
        }

        if($data['result']->user_id != @Auth::user()->id && $data['result']->status == 'Listed' ) {
            $data['result']->views_count += 1;
            $data['result']->save();
        }

        $data['amenities']        = Amenities::selected($request->id);
        $data['amenities']        = $this->getAmenitiesWithIcon($data['amenities']);

        $data['safety_amenities'] = Amenities::selected_security($request->id); 
        $data['safety_amenities'] = $this->getAmenitiesWithIcon($data['safety_amenities']);

        $data['rooms_photos']     = $data['result']->rooms_photos;
        $data['room_types']       = $data['result']->room_type_name;
        $data['cancellation']     = $data['result']->cancel_policy;

        $rooms_address            = $data['result']->rooms_address;
        $latitude                 = $rooms_address->latitude;
        $longitude                = $rooms_address->longitude;

        $data['checkin'] = '';
        $data['checkout'] = '';
        $data['guests'] = @$request->guests ;
        $data['formatted_checkin'] = '';
        $data['formatted_checkout'] = '';

        if($request->checkin != '' && $request->checkout != '') {
            $data['checkin']         = date('m/d/Y', $this->helper->custom_strtotime($request->checkin));
            $data['checkout']        = date('m/d/Y', $this->helper->custom_strtotime($request->checkout));
            $data['formatted_checkin'] = date('d-m-Y',$this->helper->custom_strtotime($request->checkin));
            $data['formatted_checkout'] = date('d-m-Y',$this->helper->custom_strtotime($request->checkout));
            $data['guests']          = '1';
            if(@$data['result']['accommodates'] >= $request->guests) {
                $data['guests']          = $request->guests;
            }
        }

        $data['similar']          = $this->getSimilarListings($latitude, $longitude, $request->id);

        $data['currency_symbol']  = html_entity_decode($data['result']->rooms_price->currency->symbol);
        $data['title']  =   $data['result']->name.' in '.$data['result']->rooms_address->city;
        return view('rooms.rooms_detail', $data);
    }

    public function remove_video(Request $request)
    {
        $rooms = Rooms::find($request->id);
        $rooms->video = ''; 
        $rooms->save();

        return json_encode(['success' => 'true', 'video' => '']);
    }

    /**
     * Load Rooms Detail Slider View
     *
     * @param array $request    Input values
     * @return view rooms_slider
     */
    public function rooms_slider(Request $request)
    {
        $data['room_id']      = $request->id;

        $data['result']       = Rooms::find($request->id);
        if($request->order != 'id')
            $data['rooms_photos'] = RoomsPhotos::where('room_id', $request->id)->ordered()->get();
        else 
            $data['rooms_photos'] = RoomsPhotos::where('room_id', $request->id)->get();

        $data['version'] = @SiteSettings::where('name', 'version')->first()->value;

        return view('rooms.rooms_slider', $data);
    }

    public function currency_check(Request $request)
    {
        $id             = $request->id;
        $new_price      = $request->n_price;
        $price          = RoomsPrice::find($id);
        $minimum_amount = $this->payment_helper->currency_convert(DEFAULT_CURRENCY, $price->currency_code, MINIMUM_AMOUNT); 
        $currency_symbol = Currency::whereCode($price->currency_code)->first()->original_symbol;
        if($minimum_amount > $new_price) {
            echo "fail";
        }
        else {
            echo "success";
        }
    }

    /**
     * Ajax Update ListFV Your Space Price
     *
     * @param array $request    Input values
     * @return json success, currency_symbol, steps_count
     */
    public function update_price(Request $request,EmailController $email_controller)
    {
        $data           = $request;
        $data           = json_decode($data['data']);

        if(isset($data->night)) {
            $price_data['night'] = $data->night;

            $validatePrice = $this->validateCurrencyData($data->currency_code, $price_data);
            if(!$validatePrice['status']) {
                return json_encode(['success'=>'false','msg' => $validatePrice['status_message'], 'attribute' => $validatePrice['attribute'], 'currency_symbol' => $validatePrice['currency_symbol']]);
            }
        }


        $price          = RoomsPrice::find($request->id);

        if($price->currency_code != $data->currency_code) {
            $this->update_calendar_currency($request->id,$price->currency_code,$data->currency_code);
        }
        foreach ($data as $key => $value) {
            $price->$key = $value;
        }

        $price->save();

        if (array_key_exists("night",$data)) {
            $this->update_status($request->id);
        }

        $email_controller->room_details_updated($request->id, 'Pricing');

        // resubmit list after update
        $rooms = Rooms::find($request->id);

        if (array_key_exists("night",$data)) {
            if(($rooms->status == 'Listed' || $rooms->status == 'Resubmit') && ($rooms->verified == 'Approved' || $rooms->verified == 'Resubmit')){
                $this->resubmit($request->id);
            }
        }
        $rooms1 = Rooms::find($request->id);

        return json_encode(['success'=>'true', 'currency_symbol' => $price->currency->original_symbol, 'steps_count' => $price->steps_count,'night_price'=> $price->getOriginal('night'),'status' => $rooms1->status]);
    }
    
    /**
     * Ajax List Your Space Steps Status
     *
     * @param array $request    Input values
     * @return json rooms_steps_status result
     */
    public function rooms_steps_status(Request $request)
    {
        return RoomsStepsStatus::find($request->id);
    }

    /**
     * Ajax Rooms Related Table Data
     *
     * @param array $request    Input values
     * @return json rooms, rooms_address, rooms_price, currency table results
     */
    public function rooms_data(Request $request)
    {
        $data           = Rooms::find($request->id);

        $rooms_address  = array_merge($data->toArray(),$data->rooms_address->toArray());

        $rooms_price    = array_merge($rooms_address,$data->rooms_price->toArray());
        
        $rooms_currency = array_merge($rooms_price,['symbol' => $data->rooms_price->currency->original_symbol ]);
        
        return json_encode($rooms_currency);
    }

    /**
     * Ajax Rooms Detail Calendar Dates Blocking
     *
     * @param array $request    Input values
     * @return json calendar results
     */
    public function rooms_calendar(Request $request)
    {
        $this->forgetCoupon();

        $id     = $request->data;

        $c_date = date('Y-m-d');
        $result['not_avilable'] = Calendar::where('room_id', $id)->where('date','>=',$c_date)->notAvailable()->get()->pluck('date');
        $result['changed_price'] = Calendar::where('room_id', $id)->where('date','>=',$c_date)->get()->pluck('session_currency_price','date');

        $result['price'] = RoomsPrice::where('room_id', $id)->get()->pluck('night');
        //get weekend price
        $result['weekend'] = RoomsPrice::where('room_id', $id)->get()->pluck('weekend');

        $result['currency_symbol'] = Currency::first()->symbol;

        $result['room_accomodates'] = Rooms::find($id)->accommodates;

        return json_encode($result);
    }

    /**
     * Ajax Rooms Detail Price Calculation while choosing date
     *
     * @param array $request    Input values
     * @return json price list
     */
    public function price_calculation(Request $request)
    {
        $this->forgetCoupon();

        return $this->payment_helper->price_calculation($request->room_id, $request->checkin, $request->checkout, $request->guest_count ,'',$request->change_reservation);
    }

    // Ajax Check Date availability
    public function check_availability(Request $request)
    {
        $room_id = $request->room_id;
        $checkin = $request->checkin;
        $checkout= $request->checkout;
        $date_from = strtotime($checkin);
        $date_to = strtotime($checkout); 
        $date_ar=array();
        for ($i=$date_from; $i<=$date_to - 1; $i+=86400) {  
            $date_ar[]= date("Y-m-d", $i);  
        }  
        $check=array();
        for ($i=0; $i < count($date_ar) ; $i++) { 
            $check[]=DB::table('calendar')->where([ 'room_id' => $room_id, 'date' => $date_ar[$i], 'status' => 'Not available' ])->first();
        }

        return $check;
        exit;
    }

    public function checkin_date_check(Request $request)
    {
        $room_id = $request->room_id;
        $date = $request->date;
        $date = (strtotime($date)) - (24*3600*1);
        $date = date('Y-m-d',$date);
        $result = DB::table('calendar')->where([ 'room_id' => $room_id, 'date' => $date, 'status' => 'Not available' ])->get();
        $checkout = (strtotime($request->date)) + (24*3600*1);
        $checkout =  date('d-m-Y',$checkout);
        $check = array(
            'checkin'=>$request->date,
            'checkout'=>$checkout
        );
        return $check;
    }

    public function current_date_check(Request $request)
    {
        $room_id = $request->room_id;
        $checkin = $request->checkin;
        $check_in=date('Y-m-d', strtotime($checkin));

        $result = DB::table('calendar')->where([ 'room_id' => $room_id, 'date' => $check_in, 'status' => 'Not available' ])->get();
        $check=array();
        if($result->count() >= 1 ) {
            $chck_date=strtotime($checkin);
            $end_date = $chck_date + (24*3600*50);
            for ($i=$chck_date + (24*3600*1) ; $i < $end_date; $i+=86400) {
                $check[] = DB::table('calendar')->where([ 'room_id' => $room_id, 'date' => date("Y-m-d", $i), 'status' => 'Not available' ])->first(); 
                if($check) {
                    $available_date= date('Y-m-d', $i);
                    return $available_date; exit;
                }
            }
        }
        else {
            return $result[0]->date;
            exit;
        }
    }


    /**
     * Get days between two dates
     *
     * @param date $sStartDate  Start Date
     * @param date $sEndDate    End Date
     * @return array $days      Between two dates
     */
    public function get_days($sStartDate, $sEndDate)
    {
        $aDays[]      = $sStartDate;  
        $sCurrentDate = $sStartDate;  

        while($sCurrentDate < $sEndDate) {
            $sCurrentDate = date("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));  
            $aDays[]      = $sCurrentDate;  
        }

        return $aDays;
    }

    /**
     * Ajax Update List Your Space Description
     *
     * @param array $request    Input values
     * @return json success
     */
    public function update_description(Request $request,EmailController $email_controller)
    {
        $data           = @$request;
        $data           = json_decode($data['data']);
        $current_tab = @$request->current_tab  ?: 'en';
        $room_desc      = $this->getDescriptionInstance($request->id, $current_tab);

        foreach ($data as $key => $value) {
            $room_desc->$key =  $value;
        }
        $room_desc->save();

        foreach ($data as $key => $value) {
            if($key == 'space') {
                $field = 'The Space';
            }
            elseif ($key == 'access') {
                $field = 'Guest Access';
            }
            elseif ($key == 'interaction') {
                $field = 'Interaction with Guests';
            }
            elseif ($key == 'notes') {
                $field = 'Other Things to Note';
            }
            elseif ($key == 'house_rules') {
                $field = 'House Rules';
            }
            elseif ($key == 'neighborhood_overview') {
                $field = 'Overview';
            }
            elseif ($key == 'transit') {
                $field = 'Getting Around';
            }
            else {
                $field = ''; 
            }

            if($field != '') {
                $email_controller->room_details_updated($request->id, $field);
            }
        }

        return json_encode(['success'=>'true']);
    }

    /**
     * Ajax Update List Your Space Calendar Dates Price, Status
     *
     * @param array $request    Input values
     * @return empty
     */
    public function calendar_edit(Request $request,EmailController $email_controller)
    {
        $currency_code=RoomsPrice::where('room_id',$request->id)->first()->currency_code;
        $minimum_amount = $this->payment_helper->currency_convert(DEFAULT_CURRENCY,$currency_code, MINIMUM_AMOUNT); 
        $currency_symbol = Currency::whereCode($currency_code)->first()->original_symbol;
        $night_price = $request->price;

        if(is_numeric($night_price) && $night_price < $minimum_amount) {
            return json_encode(['success'=>false,'msg' => trans('validation.min.numeric', ['attribute' => trans('messages.inbox.price'), 'min' => $currency_symbol.$minimum_amount]), 'attribute' => 'price', 'currency_symbol' => $currency_symbol,'min_amt' => $minimum_amount]);
        }

        $start_date = date('Y-m-d', strtotime($request->start_date));
        $start_date = strtotime($start_date);

        $end_date   = date('Y-m-d', strtotime($request->end_date));
        $end_date   = strtotime($end_date);
        $room_price = RoomsPrice::where('room_id',$request->id)->first();

        for($i=$start_date; $i<=$end_date; $i+=86400) {
            $date = date("Y-m-d", $i);
            $roomprice = $room_price->price($date);
            $is_reservation = Reservation::whereRoomId($request->id)->whereRaw('status!="Declined"')->whereRaw('status!="Expired"')->whereRaw('status!="Cancelled"')->whereRaw('(checkin = "'.$date.'" or (checkin < "'.$date.'" and checkout > "'.$date.'")) ')->count(); 
            if($is_reservation == 0) {
                $data = [
                    'room_id' => $request->id,
                    'price'   => ($request->price) ? $request->price : $roomprice,
                    'status'  => $request->status,
                    'notes'   => $request->notes,
                    'source'  => 'Calendar'
                ];
                Calendar::updateOrCreate(['room_id' => $request->id, 'date' => $date], $data);
            }
        }

        $email_controller->room_details_updated($request->id, 'Calendar');
    }

    /**
     * Contact Request send to Host
     *
     * @param array $request Input values
     * @return redirect to Rooms Detail page
     */
    public function contact_request(Request $request, EmailController $email_controller)
    {
        $data['price_list']       = json_decode($this->payment_helper->price_calculation($request->id, $request->message_checkin, $request->message_checkout, $request->message_guests));

        if(@$data['price_list']->status == 'Not available') {
            $this->helper->flash_message('error', @$data['price_list']->error ?: trans('messages.rooms.dates_not_available'));
            return redirect('rooms/'.$request->id);
        }

        $rooms = Rooms::find($request->id);
        $reservation = new Reservation;

        $reservation->room_id          = $request->id;
        $reservation->host_id          = $rooms->user_id;
        $reservation->user_id          = Auth::user()->id;
        $reservation->checkin          = date('Y-m-d', strtotime($request->message_checkin));
        $reservation->checkout         = date('Y-m-d', strtotime($request->message_checkout));
        $reservation->number_of_guests = $request->message_guests;
        $reservation->nights           = $data['price_list']->total_nights;
        $reservation->per_night        = $data['price_list']->per_night;
        $reservation->subtotal         = $data['price_list']->subtotal;
        $reservation->cleaning         = $data['price_list']->cleaning_fee;
        $reservation->additional_guest = $data['price_list']->additional_guest;
        $reservation->security         = $data['price_list']->security_fee;
        $reservation->service          = $data['price_list']->service_fee;
        $reservation->host_fee         = $data['price_list']->host_fee;
        $reservation->total            = $data['price_list']->total;
        $reservation->currency_code    = $data['price_list']->currency;
        $reservation->type             = 'contact';
        $reservation->country          = 'US';

        $reservation->base_per_night                = $data['price_list']->base_rooms_price;
        $reservation->length_of_stay_type           = $data['price_list']->length_of_stay_type;
        $reservation->length_of_stay_discount       = $data['price_list']->length_of_stay_discount;
        $reservation->length_of_stay_discount_price = $data['price_list']->length_of_stay_discount_price;
        $reservation->booked_period_type            = $data['price_list']->booked_period_type;
        $reservation->booked_period_discount        = $data['price_list']->booked_period_discount;
        $reservation->booked_period_discount_price  = $data['price_list']->booked_period_discount_price;
        
        $reservation->save();
        $replacement = "[removed]";

        $email_pattern = "/[^@\s]*@[^@\s]*\.[^@\s]*/";
        $url_pattern = "/[a-zA-Z]*[:\/\/]*[A-Za-z0-9\-_]+\.+[A-Za-z0-9\.\/%&=\?\-_]+/i";
        $phone_pattern = "/\+?[0-9][0-9()\s+]{4,20}[0-9]/";
        $dots=".*\..*\..*";

        $find = array($email_pattern, $phone_pattern);
        $replace = array($replacement, $replacement);

        $question = preg_replace($find, $replace, $request->question);

        if($question == $dots) {
            $question = preg_replace($url_pattern, $replacement, $question);
        }
        else {
            $question = preg_replace($find, $replace, $request->question);
        }

        $message = new Messages;

        $message->room_id        = $request->id;
        $message->reservation_id = $reservation->id;
        $message->user_to        = $rooms->user_id;
        $message->user_from      = Auth::user()->id;
        $message->message        = $question;
        $message->message_type   = 9;
        $message->read           = 0;

        $message->save();

        $email_controller->inquiry($reservation->id, $question);

        $this->helper->flash_message('success', trans('messages.rooms.contact_request_has_sent',['first_name'=>$rooms->users->first_name]));
        return redirect('rooms/'.$request->id);
    }

    public function get_lang_details(Request $request)
    {
        $data = RoomsDescriptionLang::with(['language'])->where('room_id', $request->id)->get();
        return json_encode($data);
    }

    public function get_lang(Request $request)
    {
        $data = Language::translatable()->where('name', '!=', 'English')->get();
        return json_encode($data);
    }

    public function add_description(Request $request)
    {
        $language = new RoomsDescriptionLang;
        $language->room_id        = $request->id;
        $language->lang_code      = $request->lan_code;
        $language->name           = '';
        $language->summary        = '';
        $language->save();

        $result = RoomsDescriptionLang::with(['language'])->where('room_id', $request->id)->where('lang_code', $request->lan_code)->get();
        return json_encode($result);
    }

    public function delete_language(Request $request)
    {
        RoomsDescriptionLang::where('room_id', $request->id)->where('lang_code', $request->current_tab)->delete();
        return json_encode(['success'=>'true']);
    }

    public function lan_description(Request $request)
    {
        $result = RoomsDescriptionLang::with(['language'])->where('room_id', $request->id)->get();
        if($result->count()) {
            foreach($result as $row) {
                $row->lan_id = count($result);
            }
            return json_encode($result);
        }
        else {
            return '[{"name":"", "summary":"","space":"","access":"","interaction":"","notes":"","house_rules":"",
            "neighborhood_overview":"","transit":"","lang_code":""}]';
        }
    }

    public function get_description(Request $request)
    {   
        if($request->lan_code =="en") {
            $result = Rooms::with(['rooms_description'])->where('id', $request->id)->get();
            
        }
        else {
            $result = RoomsDescriptionLang::with(['language'])->where('room_id', $request->id)->where('lang_code', $request->lan_code)->get();
        }
        
        if($result->count()) {
            return json_encode($result);
        }
        else {
            return '[{"name":"", "summary":"","space":"","access":"","interaction":"","notes":"","house_rules":"",
            "neighborhood_overview":"","transit":"","lang_code":""}]';
        }
    }

    public function get_all_language(Request $request)
    {
        $result = DB::select( DB::raw("select * from language where language.value not in (SELECT language.value FROM `language` JOIN rooms_description_lang on (rooms_description_lang.lang_code = language.value AND rooms_description_lang.room_id = '$request->id')) AND  language.status = 'Active' AND language.name != 'English'  ") );
        return json_encode($result);
    } 

    /**
     * Update Calendar Special Price After update Room Currency
     *
     * @param int $id    Room Id
     * @param string $from    From Currency
     * @param string $to    To Currency
     * @return true
     */ 
    public function update_calendar_currency($room_id,$from,$to)
    {
        $calendar_details = Calendar::where('room_id',$room_id)->where('date','>=',date('Y-m-d'))->get();
        foreach ($calendar_details as $calendar) {
            $new_amount = $this->payment_helper->currency_convert($from, $to, $calendar->price);
            $calendar->price = $new_amount;
            $calendar->save();
        }
    }

    /**
     * Resubmit List your sapce steps 
     *  
     * @param int $room_id    Room Id
     * @return true
     */
    public function resubmit($room_id)
    {
        $resubmit_rooms = Rooms::find($room_id);
        $resubmit_rooms->status = 'Pending';
        $resubmit_rooms->verified = 'Pending';
        $resubmit_rooms->save();

        $this->sendApprovalMail($room_id);
    }

    /**
     * Ajax List Your Space Update Rooms Values
     *
     * @param array $request    Post values from List Your Space first page
     * @return json success, steps_count
     */ 
    public function update_bed_rooms(Request $request, EmailController $email_controller)
    {
        $rooms = Rooms::find($request->id);
        $rooms->bedrooms = $request->bed_room;
        $rooms->save();

        foreach ($request->bed_types as $key => $value) {
            if($key != 0 ) {
                foreach ($value as $bed_id => $count) {
                    $bed_id = $count['id'];
                    if (isset($count['count'])) {
                        $room_bed_data = array(
                            'room_id'       => $request->id,
                            'bed_id'        => $bed_id,
                            'bed_room_no'   => $key,
                        );
                        $room_bed =  RoomsBeds::firstOrNew($room_bed_data);
                        $room_bed->count  = $count['count'];
                        $room_bed->save();
                    }
                }
                $bed_ids= collect($value)->where('id','!=',null)->pluck('id');
                $bed_ids= $bed_ids->all();
                RoomsBeds::where('room_id',$request->id)->where('bed_room_no',$key)->whereNotIn('bed_id',$bed_ids)->delete();
            }
        }

        if(($rooms->status == 'Listed' || $rooms->status == 'Resubmit') && ($rooms->verified == 'Approved' || $rooms->verified == 'Resubmit')) {
            $this->resubmit($request->id);
        }

        $this->update_status($request->id);
        $rooms_status = RoomsStepsStatus::find($request->id);
        $rooms = Rooms::find($request->id);
        return json_encode(['success'=>'true', 'steps_count' => $rooms->steps_count, 'basics_status' => $rooms_status->basics,'status'=>$rooms->status]);
    }
    
    /**
     * Ajax List Your Space Update Rooms Values
     *
     * @param array $request    Post values from List Your Space first page
     * @return json success, steps_count
     */ 
    public function update_common_bed_rooms(Request $request, EmailController $email_controller)
    {
        $data  = $request;
        $data  = json_decode($data['data']);

        $rooms = Rooms::find($request->id);

        foreach ($data->bed_types as $count) {
            $common_bed_data = array(
                'room_id'       => $request->id,
                'bed_id'        => $count->id,
                'bed_room_no'   => 'common',
            );

            $room_bed           =  RoomsBeds::firstOrNew($common_bed_data);
            $room_bed->count    = $count->count;
            $room_bed->save();
        }
        if(($rooms->status == 'Listed' || $rooms->status == 'Resubmit') && ($rooms->verified == 'Approved' || $rooms->verified == 'Resubmit')){
            $this->resubmit($request->id);
        }

        $this->update_status($request->id);
        $rooms_status = RoomsStepsStatus::find($request->id);
        $rooms = Rooms::find($request->id);
        return json_encode(['success'=>'true', 'steps_count' => $rooms->steps_count, 'basics_status' => $rooms_status->basics,'status'=>$rooms->status]);
    }

    public function update_bath_rooms(Request $request)
    {
        $rooms = Rooms::find($request->id);
        $rooms->bathrooms       = $request->bathrooms;
        $rooms->bathroom_shared = $request->bathroom_shared;
        $rooms->save();

        $this->update_status($request->id);
        $rooms_status = RoomsStepsStatus::find($request->id);
        $rooms = Rooms::find($request->id);
        return json_encode(['success' => 'true', 'steps_count' => $rooms->steps_count, 'basics_status' => $rooms_status->basics,'status'=>$rooms->status]);
    }

    public function change_photo_order(Request $request)
    {
        $start = 1;
        foreach($request->image_order as $image_id) {
            RoomsPhotos::where('id',$image_id)->update(['order_id' => $start++]);
        }

        $photos = RoomsPhotos::where('room_id', $request->id)->ordered()->get();

        return json_encode($photos);
    }

    /**
     * Duplicate a room
     *
     * @param String $room_id 
     * @return redirect     to Rooms view
     */
    public function duplicate($room_id)
    {
        //rooom dulicate
        $original_room = Rooms::find($room_id);
        $duplicate_room = $original_room->replicate(['popular','recommended','views_count']);
        $duplicate_room->status = 'Pending';
        $duplicate_room->verified = 'Pending';
        $duplicate_room->save();

        //room address duplicate 
        $org_address = RoomsAddress::where('room_id',$room_id)->first();
        $duplicate_address = $org_address->replicate();
        $duplicate_address->room_id = $duplicate_room->id;
        $duplicate_address->save();

        //room discription duplicate
        $org_description = RoomsDescription::where('room_id',$room_id)->first();
        $dup_desc = $org_description->replicate();
        $dup_desc->room_id = $duplicate_room->id;
        $dup_desc->save();

        //room description language duplicate
        $room_desc_lang = RoomsDescriptionLang::where('room_id',$room_id)->get();
        foreach($room_desc_lang as $desc_lang){
            $dup_lang_desc = $desc_lang->replicate();
            $dup_lang_desc->room_id = $duplicate_room->id;
            $dup_lang_desc->save();
        }

        //rooms bed duplicate
        $original_beds = RoomsBeds::where('room_id',$room_id)->get();
        foreach($original_beds as $original_room_bed){
            $duplicate_bed = $original_room_bed->replicate();
            $duplicate_bed->room_id = $duplicate_room->id;
            $duplicate_bed->save();
        }

        //rooms price duplicate
        $org_price = RoomsPrice::find($room_id);
        $dup_room_price = $org_price->replicate();
        $dup_room_price->room_id = $duplicate_room->id;
        $dup_room_price->save();

        //rooms photo duplicate
        $old_path = public_path().'/images/rooms/'.$room_id;
        $new_path = public_path().'/images/rooms/'.$duplicate_room->id;
        if (\File::isDirectory($old_path)) {
            \File::copyDirectory( $old_path, $new_path);
        }
        $original_photos = RoomsPhotos::where('room_id',$room_id)->get();
        foreach($original_photos as $original_room_photo){
            $duplicate_photo = $original_room_photo->replicate();
            $duplicate_photo->room_id = $duplicate_room->id;
            $duplicate_photo->room_id = $duplicate_room->id;
            $duplicate_photo->save();
        }

        //rooms step status duplicate
        $orgstep_status = RoomsStepsStatus::where('room_id',$room_id)->first();
        $dup_step_status = $orgstep_status->replicate();
        $dup_step_status->room_id = $duplicate_room->id;
        $dup_step_status->save();

        //rooms price rule duplicate
        $org_price_rules = RoomsPriceRules::where('room_id',$room_id)->get();
        foreach($org_price_rules as $org_price_rule){
            $duplicate_rule = $org_price_rule->replicate();
            $duplicate_rule->room_id = $duplicate_room->id;
            $duplicate_rule->save();
        }

        //rooms availability rule duplicate
        $avail_rules = RoomsAvailabilityRules::where('room_id',$room_id)->get();
        foreach($avail_rules as $avail_rule){
            $dup_avail_rule = $avail_rule->replicate();
            $dup_avail_rule->room_id = $duplicate_room->id;
            $dup_avail_rule->save();
        } 

        //Rooms calender 
        $original_calendars = Calendar::where('room_id', $room_id)->where('source','Calendar')->get();
        foreach($original_calendars as $original_calendar) {
            $duplicate_calendar = $original_calendar->replicate();
            $duplicate_calendar->room_id = $duplicate_room->id;
            $duplicate_calendar->save();
        } 

        $this->helper->flash_message('success', 'Room Added Successfully');
        return redirect()->back();
    }

    /**
     * End waiting for approval mail to admin and host
     *
     * @param String $room_id
     */
    protected function sendApprovalMail($room_id)
    {
        $email_controller = new EmailController;
        $email_controller->awaiting_approval_admin($room_id);
        $email_controller->awaiting_approval_host($room_id);
    }

    protected function validateCountry($short_name)
    {
        $return_data = array('status' => false, 'status_message' =>  trans('messages.lys.service_not_available_country'));
        $country = Country::where('short_name', $short_name);
        if($country->count() > 0) {
            $return_data = array('status' => true, 'status_message' => 'Country Available', 'country_name' => $country->first()->long_name);
        }
        return $return_data;
    }

    protected function validateCurrencyData($curreny_code,$price_data)
    {
        $currency_symbol = Currency::whereCode($curreny_code)->first()->original_symbol;
        $minimum_amount = $this->payment_helper->currency_convert(DEFAULT_CURRENCY, $curreny_code, MINIMUM_AMOUNT);
        $return_data = array('status' => true, 'status_message' => '', 'attribute' => '', 'currency_symbol' => $currency_symbol, 'min' => $minimum_amount);

        if(is_numeric($price_data['night']) && $price_data['night'] < $minimum_amount) {
            $return_data['status']          = false;
            $return_data['attribute']       = 'price';
            $return_data['status_message']  = trans('validation.min.numeric', ['attribute' => trans('messages.inbox.price'), 'min' => $currency_symbol.$minimum_amount]);
            $return_data['min_amt']         = $minimum_amount;
        }
        return $return_data;
    }

    protected function getSimilarListings($latitude, $longitude, $room_id)
    {
        $similar_listings = Rooms::join('rooms_address', function($join) {
            $join->on('rooms.id', '=', 'rooms_address.room_id');
        })
        ->select(DB::raw('*, ( 3959 * acos( cos( radians('.$latitude.') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('.$longitude.') ) + sin( radians('.$latitude.') ) * sin( radians( latitude ) ) ) ) as distance'))
        ->having('distance', '<=', 30)
        ->where('rooms.id', '!=', $room_id)
        ->where('rooms.status', 'Listed')
        ->where('rooms.verified', 'Approved')
        ->whereHas('users', function($query)  {
            $query->where('users.status','Active');
        })
        ->get();

        return $similar_listings;
    }

    protected function getAmenitiesWithIcon($amenities_data)
    {
        $site_settings_url = @SiteSettings::where('name', 'site_url')->first()->value;
        $url = \App::runningInConsole() ? $site_settings_url : url('/');
        foreach ($amenities_data as $amenities) {
            @$photo_src = explode('.', $amenities->icon);
            if (count($photo_src) > 1) {
                $amenities->image_name= $url . '/images/amenities/' . $amenities->icon;
            }
            else {
                $options['secure'] = TRUE;
                $options['crop'] = 'fill';
                $amenities->image_name= \Cloudder::show($amenities->icon, $options);
            }
        }
        return $amenities_data;
    }

    protected function getDescriptionInstance($room_id, $lang_code)
    {
        if($lang_code == 'en') {
            $instance = RoomsDescription::firstOrCreate(['room_id' => $room_id]);
        }
        else {
            $instance = RoomsDescriptionLang::firstOrCreate(['room_id' => $room_id, 'lang_code' => $lang_code]);
        }
        return $instance;
    }

    // For coupon code destroy
    protected function forgetCoupon()
    {
        session()->forget('coupon_code');
        session()->forget('coupon_amount');
        session()->forget('remove_coupon');
        session()->forget('manual_coupon');
    }
}