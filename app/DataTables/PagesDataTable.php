<?php

/**
 * Static Pages DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Static Pages
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use App\Models\Pages;

class PagesDataTable extends DataTable
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
            ->addColumn('action', function ($page) {
                $edit = '<a href="'.url(ADMIN_URL.'/edit_page/'.$page->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>';

                $delete = '<a data-href="'.url(ADMIN_URL.'/delete_page/'.$page->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';

                $view = '<a href="'.url($page->url).'" target="_blank" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-eye-open"></i></a>';


                return $edit.'&nbsp;'.$delete.'&nbsp;'.$view;
            })
            ->rawColumns(['icon','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Pages $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Pages $model)
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
            ['data' => 'url', 'name' => 'url', 'title' => 'Url'],
            ['data' => 'status', 'name' => 'status', 'title' => 'Status'],
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
        return 'pages_' . date('YmdHis');
    }
}