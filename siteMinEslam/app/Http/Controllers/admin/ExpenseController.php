<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ExpensesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
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
        $data['page_title'] = 'Expense';
        $data['page'] = 'Expense';
        $data['main_page'] = 'Purchases';
        $data['expense'] = [[
            'id' => '',
            'category' => '',
            'vendor' => '',
            'amount' => '',
            'net_amount' => '',
            'date' => '',
            'notes' => '',
            'tax' => '',
            'file' => '',
            'created_at' => '',
            'payment_method' => '',
            'status' => '',
            'model_name' => '',
            'model_number' => '',
            'number' => '',
            'tax_id' => '',
            'added_by' => ''
        ]];
        $data['vendors'] = get_by_user('vendors');
        $data['expenses'] = get_user_expenses();
        $data['expense_category'] = get_product_categories($type = 2);
        $data['main_content'] = view('admin/user/expenses', $data)->render();
        return view('admin/index', $data);
    }


    public function add(Request $request)
    {
        if ($_POST) {
            $id = $request->input('id');
            $validator =  Validator::make(
                $request->all(),
                [
                    'amount' => 'required|max:100'
                ]
            );
            $upload_path = 'files/'; //file save path
            $file_name  = '';
            $img  = upload_helper($request, 'file', $upload_path, 1, 'gif,jpg,png,jpeg,pdf', 'max:92000');
            if ($img != 0) {
                $file_name  = str_replace('files/', '', $img);
            }


            if ($validator->fails()) {
                session()->flash('errors', implode("<br>", $validator->errors()->all()));
                return redirect(url('admin/expense'));
            } else {

                // $file_name = 'expense_' . random_string('numeric', 4) . '.' . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

                $tax = $request->input('tax');
                if (!empty($tax)) {
                    $tax = $tax;
                } else {
                    $tax = 0;
                };
                if (!empty($request->input('vendor'))) {
                    $vendor = $request->input('vendor');
                } else {
                    $vendor = 0;
                };
                $amount = $request->input('amount');
                $amount = str_replace(',', '', "$amount");
                $amount = (float) $amount;
                $net_amount = $amount + ($amount * $tax / 100);

                if (empty($_FILES['file']['name'])) {
                    $file_name = '';
                } else {
                    $file_name = $file_name;
                }


                $data = array(
                    'user_id' => user()->id,
                    'business_id' => helper_get_business()->uid,
                    'amount' => $request->input('amount'),
                    'net_amount' => $net_amount,
                    'tax' => $tax,
                    'category' => $request->input('category'),
                    'vendor' => $vendor,
                    'notes' => $request->input('notes'),
                    'file' => $file_name,
                    'date' => $request->input('date')
                );

                if (
                    $img != 0
                ) {
                    $data['file'] = str_replace('files/', '', $file_name);
                }

                if ($id != '') {
                    helper_update_by_id($data, $id, 'expenses');
                    session()->flash('msg', helper_trans('msg-updated'));
                } else {
                    $id = helper_insert($data, 'expenses');
                    session()->flash('msg', helper_trans('msg-inserted'));
                }


                return redirect(url('admin/expense'));
            }
        }
    }

    public function edit($id)
    {
        $data = array();
        $data['page_title'] = 'Edit';
        $data['vendors'] = get_by_user('vendors');
        $data['expense'] = select_option($id, 'expenses');
        $data['expense_category'] = get_product_categories($type = 2);
        $data['main_content'] = view('admin/user/expenses', $data)->render();
        return view('admin/index', $data);
    }


    public function download($id)
    {
        $expense = ExpensesModel::find($id);
        $link = url('uploads/files/' . $expense->file);
        $data = file_get_contents($link);
        $name = $expense->file;
        force_download($name, $data);

        session()->flash('msg', $name . 'File Downloaded Successfully');
    }


    public function active($id)
    {
        $data = array(
            'status' => 1
        );

        helper_update_by_id($data, $id, 'Expenses');
        session()->flash('msg', helper_trans('msg-activated'));
        return redirect(url('admin/expense'));
    }

    public function deactive($id)
    {
        $data = array(
            'status' => 0
        );

        helper_update_by_id($data, $id, 'expenses');
        session()->flash('msg', helper_trans('msg-deactivated'));
        return redirect(url('admin/expense'));
    }

    public function delete($id)
    {
        delete_id($id, 'expenses');
        echo json_encode(array('st' => 1));
    }
}
