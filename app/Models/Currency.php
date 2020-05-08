<?php

/**
 * Currency Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Currency
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use JWTAuth;
use DB;

class Currency extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'currency';

    public $timestamps = false;

    protected $appends = ['original_symbol'];

    public function scopeActiveOnly($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeDefaultCurrency($query)
    {
        return $query->where('default_currency', '1');
    }

    // Get default currency symbol if session is not set
    public function getSymbolAttribute()
    {
        $default_currency = DB::table('currency')->where('default_currency', 1)->first()->symbol;

        if(request()->segment(1) == 'api' || strlen(request()->token) > 25) {
            $currency_symbol = $default_currency;
            if (request('token')) {
                $user_details = JWTAuth::parseToken()->authenticate();
                if($user_details->currency_code) {
                    $currency_symbol = DB::table('currency')->where('code', $user_details->currency_code)->first()->symbol;
                }
            } 

            return $currency_symbol;
        }

        if(session('symbol') && request()->segment(1) != ADMIN_URL) {
           return session('symbol');
        }
        return $default_currency;
    }

    // Get default currency symbol if session is not set
    public function getSessionCodeAttribute()
    {
        $currency_code  = session('currency');
        $default_currency_code = DB::table('currency')->where('default_currency', 1)->first()->code;
        if(request()->segment(1) == 'api' || strlen(request()->token) > 25 ) {
            $currency_code = $default_currency_code;
            if (request('token')) {
                $user_details = JWTAuth::parseToken()->authenticate();
                if($user_details->currency_code) {
                    $currency_code = $user_details->currency_code;
                }
            }
            return $currency_code;
        }

        if(!isset($currency_code) || $currency_code == '' || request()->segment(1) == ADMIN_URL) {
            $currency_code = $default_currency_code;
        }

        return $currency_code;
    }

    // Get symbol by where given code
    public static function original_symbol($code)
    {
    	$currency = DB::table('currency')->where('code', $code)->first();
        $symbol = optional($currency)->symbol ?? '$';
    	return html_entity_decode($symbol);
    }

    // Get currenct record symbol
    public function getOriginalSymbolAttribute()
    {
        $symbol = $this->attributes['symbol'];
        return html_entity_decode($symbol);
    }
}
