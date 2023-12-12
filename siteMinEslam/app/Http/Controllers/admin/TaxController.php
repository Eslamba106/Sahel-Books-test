<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaxController extends Controller
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
        $this->update_tax();
        $data = array();
        $data['main_page'] = 'Accounting';
        $data['page_title'] = 'Tax';
        $data['page'] = 'Tax';
        $data['tax'] = [[
            'id' => '',
            'type' => '',
            'name' => '',
            'rate' => '',
            'number' => '',
            'details' => '',
            'is_invoices' => '',
            'is_recoverable' => '',
            'tax_kind' => '',
            'tax_sub_kind' => '',
            'tax_code' => '',
            'model_name' => '',
            'added_by' => '',
            'old_tax_id' => ''
        ]];
        $data['taxes'] = get_user_dash_taxes();
        $data['type'] = [[
            'id' => '',
            'name' => '',
            'added_by' => '',
            'tax_type_code' => ''
        ]];
        $data['types'] = get_by_user('tax_type');
        $data['main_content'] = view('admin/user/tax', $data)->render();
        return view('admin/index', $data);
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
                return redirect(url('admin/tax'));
            } else {

                if (!empty($request->input('is_invoices'))) {
                    $is_invoices = 1;
                } else {
                    $is_invoices = 0;
                }
                if (!empty($request->input('is_recoverable'))) {
                    $is_recoverable = 1;
                } else {
                    $is_recoverable = 0;
                }

                $data = array(
                    'user_id' => user()->id,
                    'business_id' => helper_get_business()->uid,
                    'type' => $request->input('type'),
                    'name' => $request->input('name'),
                    'rate' => $request->input('rate'),
                    'number' => $request->input('number'),
                    'details' => $request->input('details'),
                    'is_invoices' => $is_invoices
                );


                //if id available info will be edited
                if ($id != '') {
                    helper_update_by_id($data, $id, 'tax');
                    session()->flash('msg', helper_trans('msg-updated'));
                } else {
                    $id = helper_insert($data, 'tax');
                    session()->flash('msg', helper_trans('msg-inserted'));
                }
                return redirect(url('admin/tax'));
            }
        }
    }

    public function edit($id)
    {
        $data = array();
        $data['main_page'] = 'Accounting';
        $data['page_title'] = 'Edit';
        $data['tax'] = select_option($id, 'tax');
        $data['types'] = get_by_user('tax_type');
        $data['main_content'] = view('admin/user/tax', $data)->render();
        return view('admin/index', $data);
    }


    public function add_type(Request $request)
    {
        if ($_POST) {
            $id = $request->input('id');
            $data = array(
                'user_id' => user()->id,
                'business_id' => helper_get_business()->uid,
                'name' => $request->input('name')
            );

            if ($id != '') {
                helper_update_by_id($data, $id, 'tax_type');
                session()->flash('msg', helper_trans('msg-updated'));
            } else {
                $id = helper_insert($data, 'tax_type');
                session()->flash('msg', helper_trans('msg-inserted'));
            }
            return redirect(url('admin/tax'));
        }
    }

    public function edit_type($id)
    {
        $data = array();
        $data['main_page'] = 'Accounting';
        $data['page_title'] = 'Edit Type';
        $data['type'] = select_option($id, 'tax_type');
        $data['main_content'] = view('admin/user/tax', $data)->render();
        return view('admin/index', $data);
    }


    public function delete($id)
    {
        delete_id($id, 'tax');
        echo json_encode(array('st' => 1));
    }

    public function delete_type($id)
    {
        delete_id($id, 'tax_type');
        echo json_encode(array('st' => 1));
    }

    public function update_tax()
    {
        $tax = get_by_id(1, 'tax_type');
        if (isset($tax) && $tax->user_id == 0) {
            $action = array(
                'user_id' => user()->id,
                'business_id' => helper_get_business()->uid
            );
            $this->db->where('id', 1);
            $this->db->update('tax_type', $action);
        }
    }
}
