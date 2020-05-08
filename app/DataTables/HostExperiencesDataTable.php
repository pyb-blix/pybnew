<?php

/**
 * Host Experiences DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Host Experiences
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use App\Models\HostExperiences;
use Auth;

class HostExperiencesDataTable extends DataTable
{    
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->base_url = url(ADMIN_URL.'/host_experiences');
        return datatables()
            ->of($query)
            ->addColumn('host_name', function ($host_experiences) {   
                return $host_experiences->user->first_name;   
            })
            ->addColumn('city', function ($host_experiences) {   
                return @$host_experiences->city_details->name;
            })
            ->addColumn('category', function ($host_experiences) {   
                return @$host_experiences->category_details->name;
            })
            ->addColumn('admin_status_options', function ($host_experiences) {
                $admin_status = '<form action="'.url(ADMIN_URL).'/update_hostexperience_status" method="post" name="admin_status_'.$host_experiences->id.'" >
                <input type="hidden" name="_token" value="'.csrf_token().'">
                <input type="hidden" name="id" value="'.$host_experiences->id.'">
                <select class="form-control" name="admin_status" '. (($host_experiences->status == null) ? 'disabled="disabled"' : '') . ' onchange="this.form.submit()">';
                $pending_status=($host_experiences->admin_status=="Pending") ? $pending_status ="selected" : $pending_status ="";
                $admin_status .='<option value="Pending" '.$pending_status.' >Pending</option>';
                $approved_status=($host_experiences->admin_status=="Approved") ? $approved_status ="selected" : $approved_status ="";
                $admin_status .='<option value="Approved" '.$approved_status.' >Approved</option>';
                $rejected_status=($host_experiences->admin_status=="Rejected") ? $rejected_status ="selected" : $rejected_status ="";
                $admin_status .='<option value="Rejected" '.$rejected_status.' >Rejected</option>';
                $admin_status .='</select>
                </form>';

                return $admin_status;
            })
            ->addColumn('featured', function ($host_experiences) {

                $class = ($host_experiences->is_featured == 'No') ? 'danger' : 'success';

                $featured = '<a href="'.url(ADMIN_URL.'/feature_experience/'.$host_experiences->id).'" class="btn btn-xs btn-'.$class.'">'.$host_experiences->is_featured.'</a>';

                return $featured;
            })
            ->addColumn('action', function ($host_experiences) {   
                $edit_link = '<a href="'.$this->base_url.'/edit/'.$host_experiences->id.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;';
                $delete_link  = '<a data-href="'.$this->base_url.'/delete/'.$host_experiences->id.'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';

                $action_link = '';
                if(Auth::guard('admin')->user()->can('edit_host_experiences'))
                {
                    $action_link .= $edit_link;
                }
                if(Auth::guard('admin')->user()->can('delete_host_experiences'))
                {
                    $action_link .= $delete_link;
                }
                return $action_link;
            })
            ->rawColumns(['admin_status_options','featured','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \HostExperiences $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(HostExperiences $model)
    {
        return $model::with(['user', 'city_details', 'category_details'])->get();
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
            ['data' => 'title', 'name' => 'title', 'title' => 'Title'],
            ['data' => 'host_name', 'name' => 'host_name', 'title' => 'Host Name'],
            ['data' => 'city', 'name' => 'city', 'title' => 'City'],
            ['data' => 'category', 'name' => 'category', 'title' => 'Category'],
            ['data' => 'status', 'name' => 'status', 'title' => 'Status'],
            ['data' => 'admin_status_options', 'name' => 'admin_status_options', 'title' => 'Admin Status ', 'searchable' => false],
            ['data' => 'featured', 'name' => 'featured', 'title' => 'Featured'],
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
        return 'host_experiences_' . date('YmdHis');
    }
}