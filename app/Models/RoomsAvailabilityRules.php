<?php

/**
 * RoomsAvailabilityRules Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    RoomsAvailabilityRules
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use App\Models\Currency;
use Session;

class RoomsAvailabilityRules extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rooms_availability_rules';

    protected $appends = ['during', 'start_date_formatted', 'end_date_formatted'];

    protected $fillable = ['id'];

    public $timestamps = false;

    public function getStartDateFormattedAttribute() {
        $format = PHP_DATE_FORMAT;
        if(request()->segment(1) == 'api') {
            $format = 'd-m-Y';
        }
        return date($format, strtotime(@$this->attributes['start_date']));
    }
    public function getEndDateFormattedAttribute() {
        $format = PHP_DATE_FORMAT;
        if(request()->segment(1) == 'api') {
            $format = 'd-m-Y';
        }
        return date($format, strtotime(@$this->attributes['end_date']));
    }

    public function getDuringAttribute() {
        $default_lang = Language::where('default_language',1)->first()->value;
        $lang = Language::whereValue((Session::get('language')) ? Session::get('language') : $default_lang)->first()->value;

        if($lang == 'en'){
            $start_date = date('d M Y', strtotime(@$this->attributes['start_date']));
            $end_date = date('d M Y', strtotime(@$this->attributes['end_date']));
        }else{
            $start_date1 = strtotime(@$this->attributes['start_date']);
            $end_date1 = strtotime(@$this->attributes['end_date']);

            $start_month = trans('messages.lys.'.date('F',$start_date1));
            $end_month = trans('messages.lys.'.date('F',$end_date1));

            $start_date = date('d',$start_date1).' '.$start_month.' '.date('Y',$start_date1);
            $end_date = date('d',$end_date1).' '.$end_month.' '.date('Y',$end_date1);

        }

    	$during = $start_date.' - '.$end_date;
    	return $during;
    }

}
