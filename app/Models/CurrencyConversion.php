<?php

/**
 * CurrencyConversion
 *
 * @package     Makent
 * @subpackage  CurrencyConversion
 * @category    Repository
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\Models;
use JWTAuth;
trait CurrencyConversion
{
    // Calculation for current currency conversion of given price field
    public function currency_calc($field)
    {
        $rate = Currency::whereCode($this->attributes['currency_code'])->first()->rate;
        $amount = @(float)$this->attributes[$field] / $rate;

        $session_currency = session('currency');

        if (request()->segment(1) == 'api' || strlen(request()->token) > 25 ) {
            if (request('token')) {
                $session_currency = JWTAuth::parseToken()->authenticate()->currency_code;
            }else{
                $session_currency = Currency::where('default_currency', 1)->first()->code;
            }
        }
        if(!$session_currency || request()->segment(1) == ADMIN_URL) {
            $session_currency = Currency::where('default_currency', 1)->first()->code;
        }

        $session_rate = Currency::whereCode($session_currency)->first()->rate;

        return round($amount * $session_rate);
    }
}