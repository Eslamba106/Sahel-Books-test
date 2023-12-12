<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
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
        $data['page_title'] = 'Vendor';
        $data['page'] = 'Vendor';
        $data['main_page'] = 'Purchases';
        $data['vendor'] = [[
            'id' => '',
            'name' => '',
            'phone' => '',
            'email' => '',
            'tax_number' => '',
            'address' => ''
        ]];
        $data['vendors'] = get_by_user('vendors');
        $data['main_content'] = view('admin/user/vendors', $data)->render();
        return view('admin/index', $data);
    }

    // load currency by ajax
    public function load_customer_info($id)
    {
        $vendor = get_vendor_info($id);
        if (empty($vendor)) {
            $value = '<h5> Nothing found ! </h5>';
            echo json_encode(array('st' => 0, 'value' => $value, 'currency' => ''));
        } else {
            $currency = helper_get_business()->currency_symbol;
            $value = '<h5>' . $vendor->phone . '</h5> <h6>' . $vendor->email . '</h6>';
            echo json_encode(array('st' => 1, 'value' => $value, 'currency' => $currency, 'code' => helper_get_business()->currency_code));
        }
    }

    public function add(Request $request)
    {
        if ($_POST) {
            $id = $request->input('id');

            $validator =  Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:100'
                ]
            );

            if ($validator->fails()) {
                session()->flash('errors', implode("<br>", $validator->errors()->all()));
                return redirect(url('admin/vendor'));
            } else {

                $data = array(
                    'user_id' => user()->id,
                    'business_id' => helper_get_business()->uid,
                    'name' => $request->input('name'),
                    'phone' => $request->input('phone'),
                    'email' => $request->input('email'),
                    'address' => $request->input('address'),
                    'created_at' => my_date_now()
                );


                //if id available info will be edited
                if ($id != '') {
                    helper_update_by_id($data, $id, 'vendors');
                    session()->flash('msg', helper_trans('msg-updated'));
                } else {
                    $id = helper_insert($data, 'vendors');
                    session()->flash('msg', helper_trans('msg-inserted'));
                }
                return redirect(url('admin/vendor'));
            }
        }
    }

    public function edit($id)
    {
        $data = array();
        $data['page_title'] = 'Edit';
        $data['vendor'] = select_option($id, 'vendors');
        $data['main_content'] = view('admin/user/vendors', $data)->render();
        return view('admin/index', $data);
    }


    public function active($id)
    {
        $data = array(
            'status' => 1
        );

        helper_update_by_id($data, $id, 'vendors');
        session()->flash('msg', helper_trans('msg-activated'));
        return redirect(url('admin/vendor'));
    }

    public function deactive($id)
    {
        $data = array(
            'status' => 0
        );

        helper_update_by_id($data, $id, 'vendors');
        session()->flash('msg', helper_trans('msg-deactivated'));
        return redirect(url('admin/Vendor'));
    }

    public function delete($id)
    {
        delete_id($id, 'vendors');
        echo json_encode(array('st' => 1));
    }
}
