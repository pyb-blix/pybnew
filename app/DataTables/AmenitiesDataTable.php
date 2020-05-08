<?php

/**
 * Amenities DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Amenities
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use App\Models\Amenities;

class AmenitiesDataTable extends DataTable
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
            ->addColumn('icon', function ($amenities) {
                return '<img src="'.$amenities->image_name.'" width="200" height="100">';
            })
            ->addColumn('action', function ($amenities) {
                $edit = '<a href="'.url(ADMIN_URL.'/edit_amenity/'.$amenities->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>';

                $delete = '<a data-href="'.url(ADMIN_URL.'/delete_amenity/'.$amenities->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';

                return $edit.'&nbsp;'.$delete;
            })
            ->rawColumns(['icon','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Amenities $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Amenities $model)
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
            ['data' => 'type_id', 'name' => 'type_id', 'title' => 'Type'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'description', 'name' => 'description', 'title' => 'Description'],
            ['data' => 'icon', 'name' => 'icon', 'title' => 'Icon'],
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
        return 'amenities_' . date('YmdHis');
    }
}