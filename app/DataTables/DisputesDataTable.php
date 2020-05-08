<?php

/**
 * Dispute DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Dispute
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use App\Models\Currency;
use App\Models\Disputes;
use App\Models\DisputeMessages;
use App\Models\DisputeDocuments;
use Auth;
use DB;
use Helpers;

class DisputesDataTable extends DataTable
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
            ->addColumn('reservation_id', function($disputes){
                return '<a target="_blank" href="'.url(ADMIN_URL.'/reservation/detail/'.$disputes->reservation_id).'" class="" title="Reservation details">'.$disputes->reservation_id.'</a>';
            })
            ->addColumn('user_name', function($disputes){
                return $disputes->user_name;
            })
            ->addColumn('amount', function($disputes){
                return $disputes->amount;
            })
            ->addColumn('currency_code', function($disputes){
                return $disputes->currency->session_code;
            })
            ->addColumn('action', function ($disputes) {
                $view = '<a href="'.url(ADMIN_URL.'/dispute/details/'.$disputes->id).'" class="btn btn-xs btn-primary" title="Detail View"><i class="fa fa-share"></i></a>';
                return $view;
            })
            ->rawColumns(['reservation_id','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Disputes $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Disputes $model)
    {
        return $model::with('user','currency','reservation.rooms')->get();
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
            ['data' => 'reservation_id', 'name' => 'disputes.reservation_id', 'title' => 'Reservation'],
            ['data' => 'user_name', 'name' => 'user_name', 'title' => 'User name'],
            ['data' => 'reservation.rooms.name', 'name' => 'created_at', 'title' => 'Listing Name'],
            ['data' => 'reservation.created_at', 'name' => 'created_at', 'title' => 'Reservation Date'],
            ['data' => 'amount', 'name' => 'amount', 'title' => 'Amount'],
            ['data' => 'currency_code', 'name' => 'currency_code', 'title' => 'Currency code'],
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
        return 'Disputes_' . date('YmdHis');
    }
}