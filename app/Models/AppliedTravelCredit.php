<?php

/**
 * Applied Travel Credit Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Applied Travel Credit
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;

class AppliedTravelCredit extends Model
{
    use CurrencyConversion;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'applied_travel_credit';

    public $timestamps = false;

    // Get Amount
    public function getAmountAttribute()
    {
        return $this->currency_calc('amount');
    }

    // Get Original Amount
    public function getOriginalAmountAttribute()
    {
        return $this->attributes['amount'];
    }
}
