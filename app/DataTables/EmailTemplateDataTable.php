<?php

/**
 * EmailTemplate DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    EmailTemplate
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use App\Models\EmailTemplate;

class EmailTemplateDataTable extends DataTable
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
            ->addColumn('action', function ($email_template) {
                $edit = '<a href="'.url(ADMIN_URL.'/edit_template/'.$email_template->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>';
                return $edit;
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \EmailTemplate $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(EmailTemplate $model){
        return $model->select()->where('language','en')->groupBy('name');
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
                ->buttons( ['csv','excel','print', 'reset'] );
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
            ['data' => 'type', 'name' => 'type', 'title' => 'Type'],
        );
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'email_template_' . date('YmdHis');
    }
}