<?php

/**
 * Payouts DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Payouts
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use App\Models\Payouts;
use Auth;
use DB;

class PayoutsDataTable extends DataTable
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
            ->addColumn('listing_name', function ($payouts) {
                return $payouts->reservation->rooms->name;
            })
            ->addColumn('action', function ($payouts) {
                $view = '<a href="'.url(ADMIN_URL.'/payouts/detail/'.$payouts->id).'" class="btn btn-xs btn-primary" title="Detail View"><i class="fa fa-share"></i></a>';

                return $view;
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Payouts $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Payouts $model)
    {
        return $model::where('status','future')->with(['users','reservation' => function($query) {
                        $query->with('currency','rooms');
                }])->get();
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
            ['data' => 'id', 'name' => 'id', 'title' => 'Id'],
            ['data' => 'reservation.id', 'name' => 'id', 'title' => 'Reservation Id'],
            ['data' => 'listing_name', 'name' => 'listing_name', 'title' => 'Listing Name'],
            ['data' => 'list_type', 'name' => 'list_type', 'title' => 'Type'],
            ['data' => 'users.first_name', 'name' => 'users.first_name', 'title' => 'User Name'],
            ['data' => 'currency_code', 'name' => 'currency_code', 'title' => 'Currency Code'],
            ['data' => 'amount', 'name' => 'amount', 'title' => 'Payout Amount'],
            ['data' => 'status', 'name' => 'status', 'title' => 'Status'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At'],
            ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Updated At'],
        );
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'payout_' . date('YmdHis');
    }
}