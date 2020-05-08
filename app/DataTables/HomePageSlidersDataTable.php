<?php

/**
 * HomePage Sliders DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    HomePage Sliders
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use App\Models\HomePageSlider;

class HomePageSlidersDataTable extends DataTable
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
            ->addColumn('image', function ($home_slider) {   
                return '<img src="'.$home_slider->image_url.'" width="200" height="100">';
            })
            ->addColumn('action', function ($home_slider) {
                $edit = '<a href="'.route('homepage_sliders.edit',[$home_slider->id]).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>';
                $delete = '<a data-href="'.route('homepage_sliders.delete',[$home_slider->id]).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';
                return $edit.'&nbsp;'.$delete;
            })
            ->rawColumns(['image','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \HomePageSlider $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(HomePageSlider $model)
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
            ['data' => 'order', 'name' => 'order', 'title' => 'Order'],
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
        return 'home_page_slider_' . date('YmdHis');
    }
}