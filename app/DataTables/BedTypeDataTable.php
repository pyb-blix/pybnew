<?php

/**
 * BedType DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    BedType
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use App\Models\BedType;

class BedTypeDataTable extends DataTable
{    
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query){

        return datatables()
            ->of($query)
            ->addColumn('action', function ($bed_type) {
                $edit = '<a href="'.url(ADMIN_URL.'/edit_bed_type/'.$bed_type->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>';
                $delete = '<a data-href="'.url(ADMIN_URL.'/delete_bed_type/'.$bed_type->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';
                return $edit.'&nbsp;'.$delete;
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \BedType $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(BedType $model){
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
                ->buttons(['csv','excel','print', 'reset']);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(){
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
    protected function filename(){
        return 'bed_type_' . date('YmdHis');
    }
}