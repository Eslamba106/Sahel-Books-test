<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\UsersModel;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function __construct()
    {

        //check auth
        if (!helper_is_user()) {
            return redirect(url(''));
        }
    }


    public function index()
    {
        $data = array();
        $data['page_title'] = 'Personal Information';
        $data['page'] = 'Profile';
        $data['user'] = UsersModel::find(session('id'));
        $data['countries'] = select_asc('country', "", "BINARY(name):asc");
        $data['fonts'] = select_asc('google_fonts');
        $data['main_content'] = view('admin/user/profile', $data)->render();
        return view('admin/index', $data);
    }

    //switch business
    public function switch_business($business = "")
    {
        $business = ($business != "") ? $business : helper_get_business()->uid;
        $active_business = array('active_business' => $business);
        session($active_business);
        return redirect(url('admin/dashboard/business'));
    }


    //update user profile
    public function update(Request $request)
    {

        if ($_POST) {

            $data = array(
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'country' => $request->input('country'),
                'address' => $request->input('address'),
                'city' => $request->input('city'),
                'state' => $request->input('state'),
                'phone' => $request->input('phone'),
                'postcode' => $request->input('postcode')
            );

            // insert photos
            if ($_FILES['photo']['name'] != '') {
                $up_load = upload_image('800');
                $data_img = array(
                    'image' => $up_load['images'],
                    'thumb' => $up_load['thumb']
                );
                helper_update_by_id($data_img, user()->id, 'users');
            }


            helper_update_by_id($data, user()->id, 'users');
            session()->flash('msg', helper_trans('msg-updated'));
            return redirect(url('admin/profile'));
        }
    }


    public function change_password()
    {
        $data = array();
        $data['page_title'] = 'Change Password';
        $data['page'] = 'Password';
        $data['main_content'] = view('admin/user/change_password', $data)->render();
        return view('admin/index', $data);
    }


    //change password
    public function change(Request $request)
    {
        if ($_POST) {

            $id = user()->id;
            $user = get_by_id($id, 'users');

            if (password_verify($request->input('old_pass'), $user->password)) {
                if ($request->input('new_pass') == $request->input('confirm_pass')) {
                    $data = array(
                        'password' => helper_hash_password($request->input('new_pass'))
                    );

                    helper_update_by_id($data, $id, 'users');
                    echo json_encode(array('st' => 1));
                } else {
                    echo json_encode(array('st' => 2));
                }
            } else {
                echo json_encode(array('st' => 0));
            }
        }
    }
}
