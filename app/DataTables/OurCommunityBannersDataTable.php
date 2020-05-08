<?php

/**
 * Our Community Banners DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Our Community Banners
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use App\Models\OurCommunityBanners;

class OurCommunityBannersDataTable extends DataTable
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
            ->addColumn('image', function ($our_community_banners) {   
                return '<img src="'.$our_community_banners->image_url.'" width="200" height="100">';
            })
            ->addColumn('action', function ($our_community_banners) {
                $edit = '<a href="'.url(ADMIN_URL.'/edit_our_community_banners/'.$our_community_banners->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>';

                $delete = '<a data-href="'.url(ADMIN_URL.'/delete_our_community_banners/'.$our_community_banners->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';

                return $edit.'&nbsp;'.$delete;
            })
            ->rawColumns(['image','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \OurCommunityBanners $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(OurCommunityBanners $model)
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
            ['data' => 'title', 'name' => 'title', 'title' => 'Title'],
            ['data' => 'description', 'name' => 'description', 'title' => 'Description'],
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
        return 'our_community_' . date('YmdHis');
    }
}