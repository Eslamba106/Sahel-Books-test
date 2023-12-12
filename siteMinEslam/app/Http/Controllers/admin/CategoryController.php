<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CategoriesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
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
        $data['main_page'] = 'Accounting';
        $data['page_title'] = 'Category';
        $data['page'] = 'Category';
        $data['category'] = [[
            'id' => '',
            'name' => '',
            'slug' => '',
            'type' => '',
            'parent_id' => '',
            'model_name' => '',
            'model_number' => '',
            'added_by' => ''
        ]];
        $data['categories'] = CategoriesModel::where([
            ['business_id', helper_get_business()->uid],
            ['user_id', user()->id],
            ['parent_id', 0]
        ])
            ->orderBy('id', 'desc')
            ->get();
        $data['main_content'] = view('admin/user/category', $data)->render();
        return view('admin/index', $data);
    }


    public function add(Request $request)
    {
        if ($_POST) {
            $id = $request->input('id');

            $validator =  Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:100',
                    'type' => 'required'
                ]
            );

            if ($validator->fails()) {
                session()->flash('errors', implode("<br>", $validator->errors()->all()));
                return redirect(url('admin/category'));
            } else {

                $data = array(
                    'user_id' => user()->id,
                    'business_id' => helper_get_business()->uid,
                    'name' => $request->input('name'),
                    'slug' => str_slug(trim($request->input('name'))),
                    'type' => $request->input('type')
                );

                if ($id != '') {
                    helper_update_by_id($data, $id, 'categories');
                    session()->flash('msg', helper_trans('msg-updated'));
                } else {
                    $id = helper_insert($data, 'categories');
                    session()->flash('msg', helper_trans('msg-inserted'));
                }
                return redirect(url('admin/category'));
            }
        }
    }

    public function edit($id)
    {
        $data = array();
        $data['main_page'] = 'Accounting';
        $data['page_title'] = 'Edit';
        $data['category'] = select_option($id, 'categories');
        $data['main_content'] = view('admin/user/category', $data)->render();
        return view('admin/index', $data);
    }


    public function active($id)
    {
        $data = array(
            'status' => 1
        );

        helper_update_by_id($data, $id, 'categories');
        session()->flash('msg', helper_trans('msg-activated'));
        return redirect(url('admin/category'));
    }

    public function deactive($id)
    {
        $data = array(
            'status' => 0
        );

        helper_update_by_id($data, $id, 'categories');
        session()->flash('msg', helper_trans('msg-deactivated'));
        return redirect(url('admin/category'));
    }

    public function delete($id)
    {
        delete_id($id, 'categories');
        echo json_encode(array('st' => 1));
    }
}
