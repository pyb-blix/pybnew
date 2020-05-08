<?php

/**
 * Admin Users Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    Admin Users
 * @author      Trioangle Product Team
 * @version     2.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\AdminusersDataTable;
use App\Models\Admin;
use App\Models\Role;
use App\Http\Start\Helpers;

class AdminusersController extends Controller
{
    protected $helper;  // Global variable for instance of Helpers

    public function __construct()
    {
        $this->helper = new Helpers;
    }

    /**
     * Load Datatable for Admin Users
     *
     * @param array $dataTable  Instance of AdminuserDataTable
     * @return datatable
     */
    public function index(AdminusersDataTable $dataTable)
    {
        return $dataTable->render('admin.admin_users.view');
    }

    /**
     * Add a New Admin User
     *
     * @param array $request  Input values
     * @return redirect     to Admin Users view
     */
    public function add(Request $request)
    {
        if(!$_POST) {
            // Get Roles for Dropdown
            $data['roles'] = Role::all()->pluck('name','id');

            return view('admin.admin_users.add', $data);
        }
        else if($request->submit) {

            $rules = array(
                'username'   => 'required|unique:admin',
                'email'      => 'required|email|unique:admin',
                'password'   => 'required',
                'role'       => 'required',
                'status'     => 'required'
            );

            $messages = array();

            $attributes = array(
                'username'   => 'Username',
                'email'      => 'Email',
                'password'   => 'Password',
                'role'       => 'Role',
                'status'     => 'Status'
            );

            $request->validate($rules,$messages,$attributes);

            $admin = new Admin;

            $admin->username = $request->username;
            $admin->email    = $request->email;
            $admin->password = bcrypt($request->password);
            $admin->status   = $request->status;

            $admin->save();

            $admin->attachRole($request->role); // Insert Role Id to role_user table

            $this->helper->flash_message('success', 'Added Successfully'); // Call flash message function

            return redirect(ADMIN_URL.'/admin_users');
        }

        return redirect(ADMIN_URL.'/admin_users');
    }

    /**
     * Update Admin User Details
     *
     * @param array $request    Input values
     * @return redirect     to Admin Users View
     */
    public function update(Request $request)
    {
        if(!$_POST) {
            $data['result']  = Admin::find($request->id);

            $data['roles']   = Role::all()->pluck('name', 'id');

            $data['role_id'] = Role::role_user($request->id)->role_id;

            return view('admin.admin_users.edit', $data);
        }
        else if($request->submit) {
            // Edit Admin User Validation Rules
            $rules = array(
                'username'   => 'required|unique:admin,username,'.$request->id,
                'email'      => 'required|email|unique:admin,email,'.$request->id,
                'role'       => 'required',
                'status'     => 'required'
            );

            $messages = array();

            $attributes = array(
                'username'   => 'Username',
                'email'      => 'Email',
                'role'       => 'Role',
                'status'     => 'Status'
            );

            $request->validate($rules,$messages,$attributes);

            if($request->status == 'Inactive') {
                $activeAdminUsers = Admin::where('status' , 'Active')->where('id' , '!=', $request->id)->get();
                if($activeAdminUsers->count() < 1){
                    $this->helper->flash_message('danger', 'Status Cannot be Updated. Because it is the only one admin account'); // Call flash message function
                    return redirect(ADMIN_URL.'/edit_admin_user/'.$request->id);
                }
            }
            $admin = Admin::find($request->id);

            $admin->username = $request->username;
            $admin->email    = $request->email;
            $admin->status   = $request->status;

            if($request->password != '')
                $admin->password = bcrypt($request->password);

            $admin->save();

            Admin::update_role($request->id, $request->role);   // Update Role Id to role_user table

            $this->helper->flash_message('success', 'Updated Successfully'); // Call flash message function

            return redirect(ADMIN_URL.'/admin_users');
        }

        return redirect(ADMIN_URL.'/admin_users');
    }

    /**
     * Delete Admin User
     *
     * @param array $request    Input values
     * @return redirect     to Admin Users View
     */
    public function delete(Request $request)
    {
        $activeAdminUsers = Admin::where(['status' => 'Active'])->where('id' , '!=', $request->id)->get();
        if($activeAdminUsers->count() < 1) {
            $this->helper->flash_message('danger', 'User cannot be deleted. Because it is the only one admin account'); // Call flash message function
            return redirect(ADMIN_URL.'/admin_users');
        }
        Admin::find($request->id)->delete();

        $this->helper->flash_message('success', 'Deleted Successfully'); // Call flash message function

        return redirect(ADMIN_URL.'/admin_users');
    }
}
