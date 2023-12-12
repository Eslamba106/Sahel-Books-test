<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function __construct()
    {

        //check auth
        if (!helper_is_user()) {
            return redirect(url(''));
        }
    }


    public function all($type = '')
    {
        $data = array();
        $data['page_title'] = 'Products';
        $data['page'] = 'Product';
        if ($type == 'buy') {
            $data['main_page'] = 'Purchases';
        } else {
            $data['main_page'] = 'Sales';
        }
        $data['type'] = $type;
        $data['product'] = [[
            'id' => '',
            'name' => '',
            'price' => '',
            'quantity' => '',
            'income_category' => '',
            'expense_category' => '',
            'details' => '',
            // 'price' => '',
        ]];
        $data['products'] = get_by_user_and_type('products', 'is_' . $type);
        $data['taxes'] = get_by_user('tax');
        $data['income_category'] = get_product_categories($type = 1);
        $data['expense_category'] = get_product_categories($type = 2);
        $data['main_content'] = view('admin/user/products', $data)->render();
        return view('admin/index', $data);
    }


    public function add(Request $request)
    {
        if ($_POST) {
            $id = $request->input('id');
            $type = $request->input('type');

            $validator =  Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'price' => 'required'
                ]
            );

            if ($validator->fails()) {
                session()->flash('errors', implode("<br>", $validator->errors()->all()));
                return redirect($_SERVER['HTTP_REFERER']);
            } else {

                if (!empty($request->input('income_category'))) {
                    $income_category = $request->input('income_category');
                } else {
                    $income_category = 0;
                }

                if (!empty($request->input('expense_category'))) {
                    $expense_category = $request->input('expense_category');
                } else {
                    $expense_category = 0;
                }

                if (!empty($request->input('quantity'))) {
                    $quantity = $request->input('quantity');
                } else {
                    $quantity = 0;
                }

                $data = array(
                    'user_id' => user()->id,
                    'business_id' => helper_get_business()->uid,
                    'name' => $request->input('name'),
                    'slug' => str_slug(trim($request->input('name'))),
                    'price' => $request->input('price'),
                    'is_sell' => $request->input('is_sell'),
                    'income_category' => $income_category,
                    'is_buy' => $request->input('is_buy'),
                    'expense_category' => $expense_category,
                    'details' => $request->input('details'),
                    'quantity' => $quantity
                );


                if ($id != '') {
                    //delete_tax($id, 'product_tax');
                    helper_update_by_id($data, $id, 'products');
                    session()->flash('msg', helper_trans('msg-updated'));
                } else {
                    $id = helper_insert($data, 'products');
                    session()->flash('msg', helper_trans('msg-inserted'));
                }

                return redirect(url('admin/product/all/' . $type));
            }
        }
    }

    public function edit($id, $type)
    {
        $data = array();
        $data['page_title'] = 'Edit';
        $data['type'] = $type;
        $data['taxes'] = get_by_user('tax');
        $data['selected_tax'] = get_tax_by_product($id);
        $data['product'] = select_option($id, 'products');
        $data['income_category'] = get_product_categories($type = 1);
        $data['expense_category'] = get_product_categories($type = 2);
        $data['main_content'] = view('admin/user/products', $data)->render();
        return view('admin/index', $data);
    }


    public function delete($id)
    {
        delete_id($id, 'products');
        echo json_encode(array('st' => 1));
    }
}
