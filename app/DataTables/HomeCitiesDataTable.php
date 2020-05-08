<?php

/**
 * Home Cities DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Home Cities
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use App\Models\HomeCities;

class HomeCitiesDataTable extends DataTable
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
            ->addColumn('image', function ($home_cities) {
                return '<img src="'.$home_cities->image_url.'" width="200" height="100">';
            })
            ->addColumn('action', function ($home_cities) {
                $edit = '<a href="'.route('home_cities.edit',[$home_cities->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>';

                $delete = '<a data-href="'.route('home_cities.delete',[$home_cities->id]).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';

                return $edit.'&nbsp;'.$delete;
            })
            ->rawColumns(['image','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \HomeCities $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(HomeCities $model)
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
            ['data' => 'image', 'name' => 'image', 'title' => 'Image'],
        );
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'home_cities_' . date('YmdHis');
    }
}