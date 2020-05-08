<?php

/**
 * Metas DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Metas
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use App\Models\Metas;

class MetasDataTable extends DataTable
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
            ->addColumn('action', function ($meta) {
                $edit = '<a href="'.url(ADMIN_URL.'/edit_meta/'.$meta->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>';
                
                return $edit;
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Metas $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Metas $model)
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
            ['data' => 'url', 'name' => 'url', 'title' => 'Url'],
            ['data' => 'title', 'name' => 'title', 'title' => 'Title'],
            ['data' => 'description', 'name' => 'description', 'title' => 'Description'],
            ['data' => 'keywords', 'name' => 'keywords', 'title' => 'Keywords'],
        );
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'metas_' . date('YmdHis');
    }
}