<?php

/**
 * ReferralsDataTable DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    ReferralsDataTable
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use App\Models\Referrals;
use DB;

class ReferralsDataTable extends DataTable
{    
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->of($query)

            ->addColumn('action', function ($referrals) {
                $Details = '<a href="'.url(ADMIN_URL.'/referral_details/'.$referrals->user_id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-eye"></i>Details</a>';
                return $Details;
            })
            ->rawColumns(['icon','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Referrals $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Referrals $model)
    {
        $referrals = $model::join('users', function($join) {
                                $join->on('users.id', '=', 'referrals.user_id');
                            })->selectRaw('referrals.user_id, CONCAT(users.first_name, " ", users.last_name) as full_name, referrals.friend_id, (select count(*) from referrals as r1 where user_id = referrals.user_id) as signup_count, (select count(*) from reservation as re1 join referrals as jr on jr.friend_id = re1.user_id where jr.user_id = referrals.user_id) as booking_count, (select count(*) from rooms as rm1 join referrals as jr on jr.friend_id = rm1.user_id where jr.user_id = referrals.user_id) as listing_count')->groupBy('referrals.user_id');

        return $referrals;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->columns($this->getColumns())
                    ->addAction(["printable" => false])
                    ->minifiedAjax()
                    ->dom('lBfr<"table-responsive"t>ip')
                    ->orderBy(0)
                    ->buttons(
                        ['csv','excel','print', 'reset']
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return array(
            ['data' => 'user_id', 'name' => 'referrals.user_id', 'title' => 'Id'],
            ['data' => 'full_name', 'name' => 'users.first_name', 'title' => 'Referrer Name'],
            ['data' => 'signup_count', 'name' => 'signup_count', 'title' => 'Signup Count', 'searchable' => false],
            ['data' => 'booking_count', 'name' => 'booking_count', 'title' => 'Booking Count', 'searchable' => false],
            ['data' => 'listing_count', 'name' => 'listing_count', 'title' => 'Listing Count', 'searchable' => false],
        );
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'referals_' . date('YmdHis');
    }
}