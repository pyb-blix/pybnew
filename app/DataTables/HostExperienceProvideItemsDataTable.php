<?php

/**
 * Host Experience Provide Items DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Host Experience Provide Items
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use App\Models\HostExperienceProvideItems;

class HostExperienceProvideItemsDataTable extends DataTable
{    
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {

        $this->base_url = url(ADMIN_URL.'/host_experience_provide_items');

        return datatables()
            ->of($query)
            ->addColumn('image', function ($host_experience_provide_items) {   
                if($host_experience_provide_items->image_url)
                {
                    return '<img src="'.$host_experience_provide_items->image_url.'" style="width:16px !important; height:19px !important;" />';
                }
            })
            ->addColumn('action', function ($host_experience_provide_items) {
                $edit = '<a href="'.$this->base_url.'/edit/'.$host_experience_provide_items->id.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>';

                $delete = '<a data-href="'.$this->base_url.'/delete/'.$host_experience_provide_items->id.'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';

                return $edit.'&nbsp;'.$delete;
            })
            ->rawColumns(['image','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \HostExperienceProvideItems $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(HostExperienceProvideItems $model)
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
        return 'experience_provide_items_' . date('YmdHis');
    }
}