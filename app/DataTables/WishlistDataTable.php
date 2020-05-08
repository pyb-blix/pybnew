<?php

/**
 * Wishlist DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Wishlist
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use App\Models\Wishlists;
use Auth;


class WishlistDataTable extends DataTable
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
            ->addColumn('all_rooms_count', function ($wishlists) {
                $all_rooms_count = $wishlists->all_rooms_count;
                return $all_rooms_count;
            })
            ->addColumn('all_host_experience_count', function ($wishlists) {
                $all_host_experience_count = $wishlists->all_host_experience_count;
                return $all_host_experience_count;
            })
             ->addColumn('pick', function ($wishlists) {
                $class = ($wishlists->pick == 'No') ? 'danger' : 'success';
                $pick = '<a href="'.url(ADMIN_URL.'/pick_wishlist/'.$wishlists->id).'" class="btn btn-xs btn-'.$class.'">'.$wishlists->pick.'</a>';
               return $pick;
            })
            ->rawColumns(['pick']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Wishlists $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Wishlists $model)
    {
        return $model::join('users', function($join) {
                        $join->on('users.id', '=', 'wishlists.user_id');
                })->select(['wishlists.id','wishlists.user_id','wishlists.name','wishlists.pick','users.first_name'])->get();
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
                    ->minifiedAjax()
                    ->dom('lBfr<"table-responsive"t>ip')
                    ->orderBy(0)
                    ->buttons(
                        ['csv','excel', 'print', 'reset']
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
            ['data' => 'name', 'name' => 'name', 'title' => 'Wish List Name'],
            ['data' => 'all_rooms_count', 'name' => 'all_rooms_count', 'title' => 'Lists Count'],
            ['data' => 'all_host_experience_count', 'name' => 'all_host_experience_count', 'title' => 'Host Experience Count'],
            ['data' => 'pick', 'name' => 'pick', 'title' => 'Pick','orderable' => false],
        );
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'wishlist_' . date('YmdHis');
    }
}