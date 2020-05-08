<?php

/**
 * Reservation DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Reservation
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use App\Models\Reservation;
use Auth;
use DB;

class ReservationsDataTable extends DataTable
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
            ->addColumn('status', function ($reservations) {
                if($reservations->status == 'Pre-Accepted' || $reservations->status == 'Inquiry'){
                    if($reservations->checkin < date("Y-m-d")){
                        return 'Expired';
                    }else{
                        return $reservations->status;
                    }
                }else{
                    return $reservations->status;
                }
            })
            ->addColumn('total', function ($reservations) {
                return $reservations->currency->symbol.$reservations->total;
            })
            ->addColumn('room_name', function ($reservations) {
                return htmlentities($reservations->room_name);
            })
            ->addColumn('action', function ($reservations) {
                $view = '<a href="'.url(ADMIN_URL.'/reservation/detail/'.$reservations->id).'" class="btn btn-xs btn-primary" title="Detail View"><i class="fa fa-share"></i></a>';

                $convo = '<a href="'.url(ADMIN_URL.'/reservation/conversation/'.$reservations->id).'" class="btn btn-xs btn-primary" title="Conversation"><i class="glyphicon glyphicon-envelope"></i></a>';

                return $view.'&nbsp;'.$convo;
            })
            ->rawColumns(['status','total','room_name','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Reservation $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Reservation $model)
    {
        $reservations = $model::join('rooms', function($join) {
                                $join->on('rooms.id', '=', 'reservation.room_id');
                            })
                        ->join('users', function($join) {
                                $join->on('users.id', '=', 'reservation.user_id');
                            })
                        ->join('currency', function($join) {
                                $join->on('currency.code', '=', 'reservation.currency_code');
                            })
                        ->leftJoin('users as u', function($join) {
                                $join->on('u.id', '=', 'reservation.host_id');
                            })
                        ->select(['reservation.id as id', 'u.first_name as host_name', 'users.first_name as guest_name', 'rooms.name as room_name','reservation.status', 'reservation.created_at as created_at','reservation.code as confirmation_code', 'reservation.updated_at as updated_at', 'reservation.checkin', 'reservation.checkout', 'reservation.number_of_guests', 'reservation.host_id', 'reservation.user_id', 'reservation.total', 'reservation.currency_code', 'reservation.service', 'reservation.host_fee','reservation.coupon_code','reservation.coupon_amount','reservation.room_id'])->where('reservation.list_type','Rooms');

        return $reservations;
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
            ['data' => 'id', 'name' => 'reservation.id', 'title' => 'Id'],
            ['data' => 'host_name', 'name' => 'u.first_name', 'title' => 'Host Name'],
            ['data' => 'guest_name', 'name' => 'users.first_name', 'title' => 'Guest Name'],
            ['data' => 'confirmation_code', 'name' => 'reservation.code', 'title' => 'Confirmation Code'],
            ['data' => 'room_name', 'name' => 'rooms.name', 'title' => 'Room Name'],
            ['data' => 'total', 'name' => 'reservation.total', 'title' => 'Total Amount'],
            ['data' => 'status', 'name' => 'reservation.status', 'title' => 'Status'],
            ['data' => 'created_at', 'name' => 'reservation.created_at', 'title' => 'Created At'],
            ['data' => 'updated_at', 'name' => 'reservation.updated_at', 'title' => 'Updated At'],
        );
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'reservation_' . date('YmdHis');
    }
}