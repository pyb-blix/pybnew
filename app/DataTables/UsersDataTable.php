<?php

/**
 * Users DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Users
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use App\Models\User;
use Auth;

class UsersDataTable extends DataTable
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
            ->addColumn('phone_numbers', function ($users) {
                $phone_numbers = '';
                foreach($users->users_phone_numbers as $k => $phone_number){
                    if($phone_number->status == 'Confirmed'){
                        $phone_numbers .= '+'.$phone_number->phone_code.' '.$phone_number->phone_number.', ';
                    }
                }
                return trim($phone_numbers, ', ');
            })
            ->addColumn('verification_status', function ($users) {
                $status = $users->original_verification_status;
                $status = $status == 'No'?'--':$status;
                return $status;
            })
            ->addColumn('action', function ($users) {
                $edit = (Auth::guard('admin')->user()->can('edit_user')) ? '<a href="'.url(ADMIN_URL.'/edit_user/'.$users->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;' : '';

                $delete = (Auth::guard('admin')->user()->can('delete_user')) ? '<a data-href="'.url(ADMIN_URL.'/delete_user/'.$users->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>' : '';

                return $edit.$delete;

                return $edit.'&nbsp;'.$delete;
            })
            ->rawColumns(['phone_code','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        return $model->select('users.id as id', 'users.first_name', 'users.last_name', 'users_phone_numbers.phone_code', 'users_phone_numbers.phone_number', 'users.email', 'users.status','users.created_at','users.updated_at', 'users.verification_status','users.languages')
            ->leftJoin('users_phone_numbers', function($join) {
                $join->on('users_phone_numbers.user_id', '=', 'users.id');
            })->groupBy('id');
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
            ['data' => 'id', 'name' => 'users.id', 'title' => 'Id'],
            ['data' => 'first_name', 'name' => 'users.first_name', 'title' => 'First Name'],
            ['data' => 'last_name', 'name' => 'users.last_name', 'title' => 'Last Name'],
            ['data' => 'email', 'name' => 'users.email', 'title' => 'Email'],
            ['data' => 'phone_numbers', 'name' => 'users_phone_numbers.phone_number', 'title' => 'Phone Numbers', 'orderable' => false, 'searchable' => true],
            ['data' => 'status', 'name' => 'users.status', 'title' => 'Status'],
            ['data' => 'created_at', 'name' => 'users.created_at', 'title' => 'Created At'],
            ['data' => 'updated_at', 'name' => 'users.updated_at', 'title' => 'Updated At'],
            ['data' => 'verification_status', 'name' => 'users.verification_status', 'title' => 'ID verification status'],
        );
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'user_' . date('YmdHis');
    }
}