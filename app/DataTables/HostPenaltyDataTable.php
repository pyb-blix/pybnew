<?php

/**
 * HostPenalty DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    HostPenalty
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use App\Models\HostPenalty;
use Auth;
use DB;

class HostPenaltyDataTable extends DataTable
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
            ->addColumn('host_penalty_amount', function ($host_penalty) {
                return $host_penalty->currency->symbol.$host_penalty->converted_amount;
            })
            ->addColumn('remain_amount', function ($host_penalty) {
                return $host_penalty->currency->symbol.$host_penalty->converted_remain_amount;
            })
            ->rawColumns(['host_penalty_amount','remain_amount']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \HostPenalty $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(HostPenalty $model)
    {
        $host_penalty = $model::join('rooms', function($join) {
                            $join->on('rooms.id', '=', 'host_penalty.room_id');
                        })
                        ->join('users', function($join) {
                            $join->on('users.id', '=', 'host_penalty.user_id');
                        })
                        ->join('currency', function($join) {
                            $join->on('currency.code', '=', 'host_penalty.currency_code');
                        })
                        ->select(['host_penalty.id as id', 'rooms.name as room_name', 'users.first_name as host_name', 'host_penalty.reservation_id', 'host_penalty.amount','host_penalty.remain_amount', 'host_penalty.status as status', 'host_penalty.created_at', 'host_penalty.updated_at', 'host_penalty.currency_code']);

        return $host_penalty;
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
            ['data' => 'id', 'name' => 'host_penalty.id', 'title' => 'Id'],
            ['data' => 'host_name', 'name' => 'users.first_name', 'title' => 'Host Name'],
            ['data' => 'room_name', 'name' => 'rooms.name', 'title' => 'Room Name'],
            ['data' => 'reservation_id', 'name' => 'host_penalty.reservation_id', 'title' => 'Reservation Id'],
            ['data' => 'host_penalty_amount', 'name' => 'host_penalty.amount', 'title' => 'Total Amount'],
            ['data' => 'remain_amount', 'name' => 'host_penalty.remain_amount', 'title' => 'Remaining Amount'],
            ['data' => 'status', 'name' => 'host_penalty.status', 'title' => 'Status'],
            ['data' => 'created_at', 'name' => 'host_penalty.created_at', 'title' => 'Created At'],
            ['data' => 'updated_at', 'name' => 'host_penalty.updated_at', 'title' => 'Updated At'],

        );
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'host_penalty_' . date('YmdHis');
    }
}