<?php

namespace App\Http\Controllers\admin;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{

    public function index()
    {
        $data = array();
        $data['page_title'] = 'Customers';
        $data['page'] = 'Customers';
        $data['main_page'] = 'Sales';
        $data['customer'] = [[
            'id' => '',
            'name' => '',
            'email' => '',
            'phone' => '',
            'address' => '',
            'cus_number' => '',
            'vat_code' => '',
            'country' => '',
            'currency_code' => '',
            'city' => '',
            'currency' => '',
            'postal_code' => '',
            'address1' => '',
            'address2' => ''
        ]];
        $data['customers'] = get_customers();
        $data['countries'] = select_asc('country');
        $data['main_content'] = view('admin/user/customers', $data)->render();
        // $customer = get_customer_info(65);
        // dd($customer->currency);
        // load_currency(65);
        return view('admin/index', $data);
    }

    // load currency by ajax
    function load_currency($currency_id)
{
    $country = Country::where('id',$currency_id)->get();
    $currency = $country->pluck('currency_code' , 'currency_name');
    // dd($currency->currency_code);
    // if (empty($currency)) {
    //     return '<option value="0">' . 'Nothing found !' . '</option>';
    // } else {
        // return'<option value="' . $currency->currency_code . '">' . $currency->currency_code . ' - ' . $currency->currency_name . '</option>';
    // }
    
    return json_encode($currency);
}
// get_payment
    // public function load_currency($currency_id)
    // {
    //     $currency = Country::where('id',$currency_id)->first();
    //     dd($currency);
    //     // if (empty($currency)) {
    //     //     return '<option value="0">' . 'Nothing found !' . '</option>';
    //     // } else {
    //         return'<option value="' . $currency->currency_code . '">' . $currency->currency_code . ' - ' . $currency->currency_name . '</option>';
    //     // }
    // }
    // public function load_currency($currency_id)
    // {
    //     $currency = get_by_id($currency_id, 'country');
    //     // dd($currency);
    //     if (empty($currency)) {
    //         return '<option value="0">' . 'Nothing found !' . '</option>';
    //     } else {
    //         return'<option value="' . $currency->currency_code . '">' . $currency->currency_code . ' - ' . $currency->currency_name . '</option>';
    //     }
    // }

    // load currency by ajax
    public function load_customer_info($id)
    {
        $customer = get_customer_info($id);
        // $customer = get_customer_info($id, 'customers');

        if (empty($customer)) {
            $value = '<h5> Nothing found ! </h5>';
            echo json_encode(array('st' => 0, 'value' => $value, 'currency' => ''));
        } else {

            if (empty($customer->currency_symbol)) {
                $currency = helper_get_business()->currency_symbol;
            } else {
                $currency = $customer->currency_symbol;
            }

            $value = '<h5>' . $customer->country . '</h5> <h6>' . $customer->currency . ' - ' . $customer->currency_name . '</h6>';
            $currency_name = $customer->currency . ' (' . $currency . ') - ' . $customer->currency_name;
            $code = $customer->currency;
            echo json_encode(array('st' => 1, 'value' => $value, 'currency' => $currency, 'currency_name' => $currency_name, 'code' => $code));
        }
    }


    public function add(Request $request)
    {
        $id = $request->input('id');

        $validator =  Validator::make(
            $request->all(),
            [
                'name' => 'required|max:100'
            ],
            [
                'name.required' => helper_trans('customer')
            ]
        );

        if ($validator->fails()) {
            echo json_encode([
                'st' => 2,
                'errors' => implode("\n", $validator->errors()->all())
            ]);
            exit;
        }

        $data = array(
            'user_id' => user()->id,
            'business_id' => helper_get_business()->uid,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'cus_number' => $request->input('cus_number'),
            'vat_code' => $request->input('vat_code'),
            'country' => $request->input('country'),
            'currency' => $request->input('currency'),
            'address1' => $request->input('address1'),
            'address2' => $request->input('address2'),
            'city' => $request->input('city'),
            'postal_code' => $request->input('postal_code'),
            'status' => 1
        );
        // dd($data);


        //if id available info will be edited
        if ($id != '') {
            helper_update_by_id($data, $id, 'customers');
            session()->flash('msg', helper_trans('msg-updated'));
        } else {

            // if (check_package_limit('customer') != -2) {
            //     $total = get_total_value('customers', 0);
            //     if ($total >= check_package_limit('customer')) :
            //         $msg = helper_trans('reached-limit') . ', ' . helper_trans('package-limit-msg');
            //         session()->flash('error', $msg);
            //         return redirect(url('admin/customer'));
            //         exit();
            //     endif;
            // }

            $id = helper_insert($data, 'customers');
            session()->flash('msg', helper_trans('msg-inserted'));
        }

        return redirect(url('admin/customer'));
    }

    public function edit($id)
    {
        $data = array();
        $data['page_title'] = 'Edit';
        $data['page'] = 'Customers';
        $data['countries'] = select_asc('country', "", "BINARY(name):asc");
        $data['customer'] = select_option($id, 'customers');
        $data['main_content'] = view('admin/user/customers', $data)->render();
        return view('admin/index', $data);
    }


    public function active($id)
    {
        $data = array(
            'status' => 1
        );

        helper_update_by_id($data, $id, 'customers');
        session()->flash('msg', helper_trans('msg-activated'));
        return redirect(url('admin/customers'));
    }

    public function deactive($id)
    {
        $data = array(
            'status' => 0
        );

        helper_update_by_id($data, $id, 'customers');
        session()->flash('msg', helper_trans('msg-deactivated'));
        return redirect(url('admin/customers'));
    }

    public function delete($id)
    {
        delete_id($id, 'customers');
        echo json_encode(array('st' => 1));
    }
}
