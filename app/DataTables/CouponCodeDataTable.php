<?php

/**
 * Coupon Code DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Coupon Code
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use App\Models\CouponCode;

class CouponCodeDataTable extends DataTable
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
            ->addColumn('action', function ($coupon_code) {
                $edit = '<a href="'.url(ADMIN_URL.'/edit_coupon_code/'.$coupon_code->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>';
                $delete = '<a data-href="'.url(ADMIN_URL.'/delete_coupon_code/'.$coupon_code->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';

                return $edit.'&nbsp;'.$delete;
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \CouponCode $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CouponCode $model)
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
            ['data' => 'coupon_code', 'name' => 'coupon_code', 'title' => 'Coupon Code'],
            ['data' => 'amount', 'name' => 'amount', 'title' => 'Amount'],
            ['data' => 'currency_code', 'name' => 'currency_code', 'title' => 'Currency Code'],
            ['data' => 'expired_at', 'name' => 'expired_at', 'title' => 'Expired At'],
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
        return 'coupen_code_' . date('YmdHis');
    }
}