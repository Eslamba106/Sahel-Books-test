<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessModel;
use App\Models\Payment_recordsModel;
use App\Models\PaymentModel;

class UsersController extends Controller
{

    public function __construct()
    {
        //check auth
        if (!is_admin()) {
            return redirect(url(''));
        }
    }

    public function index()
    {
        return $this->all_users('all');
    }


    //all users list
    public function all_users($type)
    {
        $data = array();
        $total_row = get_all_users(1, 0, 0, $type);


        // start pagination
        $config['total_rows'] = $total_row;
        $config['per_page'] = 12;

        $page = 0;
        if (!isset($_GET['page'])) {
            $page = 0;
        } else {
            $page = $_GET['page'];
        }
        if ($page != 0) {
            $page = $page - 1;
        }
        $config['page'] = $page;
        $data['pagination'] = helper_create_pagination($config);
        // end pagination

        $data['page_title'] = 'Users';
        $data['packages'] = select_asc('package');
        $data['users'] = get_all_users(0, $config['per_page'], $page * $config['per_page'], $type);
        // dd($data['users']);
        $data['main_content'] = view('admin/users', $data)->render();
        // echo  $data['main_content'];
        return view('admin/index', $data);
    }

    //all pro users list
    public function pro($type = 'pro')
    {
        $data = array();
        $data['page_title'] = 'Users';
        $data['users'] = get_all_users($type);
        $data['main_content'] = view('admin/users', $data)->render();
        return view('admin/index', $data);
    }

    //active or deactive post
    public function status_action($type, $id)
    {
        $data = array(
            'status' => $type
        );

        helper_update_by_id($data, $id, 'users');

        if ($type == 1) :
            session()->flash('msg', helper_trans('msg-activated'));
        else :
            session()->flash('msg', helper_trans('msg-deactivated'));
        endif;
        return redirect(url('admin/users'));
    }

    //change user role
    public function change_account($id)
    {
        $data = array(
            'account_type' => $request->input('type', false)
        );

        helper_update_by_id($data, $id, 'users');
        session()->flash('msg', helper_trans('msg-updated'));
        return redirect(url('admin/users'));
    }

    public function edit($id)
    {
        if ($_POST) {
            $id = $request->input('id');
            $data = array(
                'name' => $request->input('name'),
                'slug' => str_slug(trim($request->input('name'))),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'designation' => $request->input('designation'),
                'address' => $request->input('address'),
                'account_type' => $request->input('type', false)
            );

            helper_update_by_id($data, $id, 'users');
            session()->flash('msg', helper_trans('msg-updated'));
            return redirect(url('admin/users'));
        }

        $data = array();
        $data['page_title'] = 'Edit';
        $data['user'] = get_by_id($id, 'users');
        $data['main_content'] = view('admin/user_edit', $data)->render();
        return view('admin/index', $data);
    }


    public function active($id)
    {
        $data = array(
            'status' => 1
        );

        helper_update_by_id($data, $id, 'users');
        session()->flash('msg', helper_trans('msg-activated'));
        return redirect(url('admin/users'));
    }


    public function delete($user_id)
    {
        $business = BusinessModel::where('user_id', $user_id)->get();
        foreach ($business as $biz) {
            Payment_recordsModel::where('business_id', $biz->uid)->delete();
        }
        PaymentModel::where('user_id', $user_id)->delete();
        BusinessModel::where('user_id', $user_id)->delete();
        delete_id($user_id, 'users');
        echo json_encode(array('st' => 1));
    }
}
