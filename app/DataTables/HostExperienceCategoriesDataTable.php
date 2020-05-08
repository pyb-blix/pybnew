<?php

/**
 * Host Experience Categories DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Host Experience Categories
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use App\Models\HostExperienceCategories;

class HostExperienceCategoriesDataTable extends DataTable
{    
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->base_url = url(ADMIN_URL.'/host_experience_categories');

        return datatables()
            ->of($query)
            ->addColumn('action', function ($host_experience_category) {
                  $edit = '<a href="'.$this->base_url.'/edit/'.$host_experience_category->id.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>';
                  $delete = '<a data-href="'.$this->base_url.'/delete/'.$host_experience_category->id.'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';

                return $edit.'&nbsp;'.$delete;
            })
            ->rawColumns(['icon','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \HostExperienceCategories $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(HostExperienceCategories $model)
    {
        return $model->select();
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
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'status', 'name' => 'status', 'title' => 'Status'],
        );
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'host_experience_category' . date('YmdHis');
    }
}