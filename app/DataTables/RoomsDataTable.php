<?php

/**
 * Rooms DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Rooms
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use App\Models\Rooms;
use App\Models\RoomsStepsStatus;
use Auth;

class RoomsDataTable extends DataTable
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
            ->addColumn('room_name', function ($rooms) {
                return '<span class="notranslate">'.$rooms->room_name.'</span>';
            })
            ->addColumn('host_name', function ($rooms) {
                return '<span class="notranslate">'.$rooms->first_name.'</span>';
            })
            ->addColumn('popular', function ($rooms) {
                $class = ($rooms->popular == 'No') ? 'danger' : 'success';
                $popular = '<a href="'.url(ADMIN_URL.'/popular_room/'.$rooms->room_id).'" class="btn btn-xs btn-'.$class.'">'.$rooms->popular.'</a>';
                return $popular;
            })
            ->addColumn('room_status', function ($rooms) {
                $status = $rooms->room_status_view;
                if ($status == null && $this->get_steps_count($rooms->room_id) <= 0) {
                   $status = 'Pending';
                }
                return $status;
            })
            ->addColumn('verified', function ($rooms) {
                $verified =  '<select class="admin_rooms form-control" data-type="verified" id="'.$rooms->room_id.'" name="'.$rooms->room_id.'" ' . (($this->get_steps_count($rooms->room_id) > 0) ? 'disabled="disabled"' : '') . '>
               <option value="Pending" '.($rooms->verified == 'Pending' ? ' selected="selected"' : '').'>Pending</option>
               <option value="Approved" '.($rooms->verified == 'Approved' ? ' selected="selected"' : '').' >Approved</option>
               <option value="Resubmit" '.($rooms->verified == 'Resubmit' ? ' selected="selected"' : '').' >Resubmit</option>
               </select>';

                return $verified;
            })
            ->addColumn('action', function ($rooms) {

                $edit = (Auth::guard('admin')->user()->can('edit_room')) ? '<a href="'.url(ADMIN_URL.'/edit_room/'.$rooms->room_id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>' : '';
                $delete = (Auth::guard('admin')->user()->can('delete_room')) ? '<a data-href="'.url(ADMIN_URL.'/delete_room/'.$rooms->room_id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>' : '';

                return $edit.'&nbsp;'.$delete;
            })
            ->rawColumns(['room_name','host_name','popular','verified','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Rooms $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Rooms $model)
    {
        return $model->join('users', function($join) {
                    $join->on('users.id', '=', 'rooms.user_id');
                })
                ->join('property_type', function($join) {
                    $join->on('property_type.id', '=', 'rooms.property_type');
                })
                ->join('room_type', function($join) {
                    $join->on('room_type.id', '=', 'rooms.room_type');
                })
                ->select(['rooms.id as room_id', 'rooms.name as room_name', 'rooms.status as room_status_view', 'rooms.created_at as room_created_at', 'rooms.updated_at as room_updated_at', 'rooms.*', 'users.*', 'property_type.*', 'room_type.*']);
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
            ['data' => 'room_id', 'name' => 'rooms.id', 'title' => 'Id'],
            ['data' => 'room_name', 'name' => 'rooms.name', 'title' => 'Name'],
            ['data' => 'host_name', 'name' => 'users.first_name', 'title' => 'Host Name'],
            ['data' => 'property_type_name', 'name' => 'property_type.name', 'title' => 'Property Type'],
            ['data' => 'room_status', 'name' => 'rooms.status', 'title' => 'Status'],
            ['data' => 'room_created_at', 'name' => 'rooms.created_at', 'title' => 'Created At'],
            ['data' => 'room_updated_at', 'name' => 'rooms.updated_at', 'title' => 'Updated At'],
            ['data' => 'views_count', 'name' => 'views_count', 'title' => 'Viewed Count'],
            ['data' => 'popular', 'name' => 'popular', 'title' => 'Popular'],
            ['data' => 'verified', 'name' => 'verified', 'title' => 'Verified', 'exportable' => false, 'printable'=>false],
        );
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'rooms_' . date('YmdHis');
    }

    /**
     * Get Rooms Steps Count.
     *
     * @return int
     */
    protected function get_steps_count($room_id)
    {
        $rs_result = RoomsStepsStatus::find($room_id);
        if($rs_result == '') {
            return 6;
        }
        return 6 - ($rs_result->basics + $rs_result->description + $rs_result->location + $rs_result->photos + $rs_result->pricing + $rs_result->calendar);
    }
}