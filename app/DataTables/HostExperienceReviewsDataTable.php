<?php

/**
 * Reviews DataTable
 *
 * @package     Makent
 * @subpackage  DataTable
 * @category    Reviews
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;
use App\Models\Reviews;

class HostExperienceReviewsDataTable extends DataTable
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
            ->addColumn('action', function ($reviews) {
                $edit = '<a href="'.url(ADMIN_URL.'/exp_edit_review/'.$reviews->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>';
                return $edit;
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Reviews $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Reviews $model)
    {
        $reviews = $model::where('list_type', 'Experiences')->join('host_experiences', function($join) {
                                $join->on('host_experiences.id', '=', 'reviews.room_id');
                            })
                        ->join('users', function($join) {
                                $join->on('users.id', '=', 'reviews.user_from');
                            })
                        ->join('users as users_to', function($join) {
                                $join->on('users_to.id', '=', 'reviews.user_to');
                            })
                        ->select(['reviews.id as id', 'reservation_id', 'host_experiences.title as room_name', 'users.first_name as user_from', 'users_to.first_name as user_to', 'review_by', 'comments', 'reviews.created_at as created_at', 'reviews.updated_at as updated_at']);
        return $reviews;
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
            ['data' => 'id', 'name' => 'reviews.id', 'title' => 'Id'],
            ['data' => 'reservation_id', 'name' => 'reservation_id', 'title' => 'Reservation Id'],
            ['data' => 'room_name', 'name' => 'host_experiences.title', 'title' => 'Experience title'],
            ['data' => 'user_from', 'name' => 'users.first_name', 'title' => 'User From'],
            ['data' => 'user_to', 'name' => 'users_to.first_name', 'title' => 'User To'],
            ['data' => 'review_by', 'name' => 'review_by', 'title' => 'Review By'],
            ['data' => 'comments', 'name' => 'comments', 'title' => 'Comments'],
            ['data' => 'created_at', 'name' => 'reviews.created_at', 'title' => 'Created At'],
            ['data' => 'updated_at', 'name' => 'reviews.updated_at', 'title' => 'Updated At'],

        );
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'experience_reviews' . date('YmdHis');
    }
}