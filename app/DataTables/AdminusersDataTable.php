<?php

/**
 * Admin Users DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Admin Users
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use App\Models\Admin;

class AdminusersDataTable extends DataTable
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
            ->addColumn('action', function ($admin) {
                $edit = '<a href="'.url(ADMIN_URL.'/edit_admin_user/'.$admin->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>';

                $delete = '<a data-href="'.url(ADMIN_URL.'/delete_admin_user/'.$admin->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';

                return $edit.'&nbsp;'.$delete;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Admin $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Admin $model)
    {
        return $model->join('role_user', function($join) {
                $join->on('role_user.user_id', '=', 'admin.id');
            })
            ->join('roles', function($join) {
                $join->on('roles.id', '=', 'role_user.role_id');
            })
            ->select(['admin.id as id', 'username', 'email', 'roles.display_name as role_name', 'status']);
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
                    ->addAction()
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
            ['data' => 'id', 'name' => 'admin.id', 'title' => 'Id'],
            ['data' => 'username', 'name' => 'admin.username', 'title' => 'Username'],
            ['data' => 'email', 'name' => 'admin.email', 'title' => 'Email'],
            ['data' => 'role_name', 'name' => 'roles.display_name', 'title' => 'Role Name'],
            ['data' => 'status', 'name' => 'admin.status', 'title' => 'Status'],
        );
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'admin_users_' . date('YmdHis');
    }
}