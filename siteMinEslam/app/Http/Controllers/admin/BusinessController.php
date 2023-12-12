<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusinessController extends Controller
{

    public function __construct()
    {

        //check auth
        if (!helper_is_user() && !is_admin()) {
            return redirect(url(''));
        }
    }

    public function index()
    {
        $data = array();
        $data['page_title'] = 'Business';
        $data['page'] = 'Business';
        $data['busines'] = [[
            'id' => '',
            'address' => '',
            'biz_number' => '',
            'business_tax_type' => '',
            'business_type' => '',
            'category' => '',
            'color' => '',
            'country' => '',
            'created_at' => '',
            'currency' => '',
            'enable_stock' => '',
            'enable_negative_stock' => '',
            'footer_note' => '',
            'is_autoload_amount' => '',
            'is_primary' => '',
            'logo' => '',
            'name' => '',
            'shipping_include_tax' => '',
            'slug' => '',
            'status' => '',
            'template_style' => '',
            'title' => '',
            'type' => '',
            'uid' => '',
            'user_id' => '',
            'vat_code' => '',
            'phone' => '',
            'tax_type' => '',
            'invoice_type' => ''
        ]];
        $data['business'] = get_business(0);
        $data['total'] = count($data['business']);
        $data['active_business'] = get_active_business();
        $data['countries'] = select_asc('country');
        $data['categories'] = select_asc('business_category');
        $data['main_content'] = view('admin/user/business', $data)->render();
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
                ],
                [
                    'name.required' => helper_trans('customer')
                ]
            );

            if ($validator->fails()) {
                session()->flash('errors', implode("<br>", $validator->errors()->all()));
                return redirect(url('admin/business'));
            } else {

                if ($id != '') {
                    $business = get_single_business($id);
                    $uid = $business[0]['uid'];
                    $country = $business[0]['country'];
                } else {
                    $uid = random_string('numeric', 5);
                    $country = $request->input('country');
                }

                $data = array(
                    'user_id' => user()->id,
                    'uid' => $uid,
                    'title' => $request->input('title'),
                    'name' => $request->input('name'),
                    'address' => $request->input('address'),
                    'biz_number' => $request->input('biz_number'),
                    'vat_code' => $request->input('vat_code'),
                    'is_autoload_amount' => 0,
                    'country' => $country,
                    'category' => $request->input('category'),
                    'status' => 1
                );

                if ($id != '') {
                    helper_update_by_id($data, $id, 'business');
                    session()->flash('msg', helper_trans('msg-updated'));
                } else {


                    if (check_package_limit('business') != -2) {
                        $total = get_total_value('business', 0);
                        if ($total >= check_package_limit('business')) :
                            $msg = helper_trans('reached-limit') . ', ' . helper_trans('package-limit-msg');
                            session()->flash('error', $msg);
                            return redirect(url('admin/business'));
                            exit();
                        endif;
                    }


                    $id = helper_insert($data, 'business');
                    session()->flash('msg', helper_trans('msg-inserted'));
                }

                $upload_path = ''; //file save path
                $img  = upload_helper($request, 'photo1', $upload_path, 1, 'gif,jpg,png,jpeg', 'max:92000');
                if ($img != 0) {
                    $data_img = array(
                        'logo' => 'uploads/' . $img
                    );
                    helper_update_by_id($data_img, $id, 'business');
                }

                return redirect(url('admin/business'));
            }
        }
    }


    public function edit($id)
    {
        $data = array();
        $data['page_title'] = 'Edit';
        $data['page'] = 'Business';
        $data['countries'] = select_asc('country');
        $data['categories'] = select_asc('business_category');
        $data['busines'] = get_single_business($id);
        $data['main_content'] = view('admin/user/business', $data)->render();
        return view('admin/index', $data);
    }


    public function set_primary($id)
    {
        BusinessModel::where('user_id', session('id'))->update(['is_primary' => 0]);


        $data = array(
            'is_primary' => 1
        );

        helper_update_by_id($data, $id, 'business');
        echo json_encode(array('st' => 1));
    }




    public function categories()
    {
        $data = array();
        $data['page_title'] = 'Categories';
        $data['page'] = 'Business';
        $data['main_page'] = 'Settings';
        $data['category'] = FALSE;
        $data['categories'] = select_asc('business_category');
        $data['main_content'] = view('admin/user/business_category', $data)->render();
        return view('admin/index', $data);
    }


    public function add_category()
    {
        if ($_POST) {
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
                session()->flash('errors', implode("<br>", $validator->errors()->all()));
                return redirect(url('admin/business/categories'));
            } else {
                $data = array(
                    'name' => $request->input('name')
                );

                if ($id != '') {
                    helper_update_by_id($data, $id, 'business_category');
                    session()->flash('msg', helper_trans('msg-updated'));
                } else {
                    $id = helper_insert($data, 'business_category');
                    session()->flash('msg', helper_trans('msg-inserted'));
                }

                // upload logo
                $data_img = do_upload('photo1');
                if ($data_img) {
                    $data_img = array(
                        'logo' => $data_img['medium']
                    );
                    helper_update_by_id($data_img, $id, 'business');
                }

                return redirect(url('admin/business/categories'));
            }
        }
    }


    public function edit_category($id)
    {
        $data = array();
        $data['page_title'] = 'Edit';
        $data['page'] = 'Business';
        $data['category'] = select_option($id, 'business_category');
        $data['main_content'] = view('admin/user/business_category', $data)->render();
        return view('admin/index', $data);
    }




    public function active($id)
    {
        $data = array(
            'status' => 1
        );

        helper_update_by_id($data, $id, 'business');
        session()->flash('msg', helper_trans('msg-activated'));
        return redirect(url('admin/business'));
    }

    public function deactive($id)
    {
        $data = array(
            'status' => 0
        );

        helper_update_by_id($data, $id, 'business');
        session()->flash('msg', helper_trans('msg-deactivated'));
        return redirect(url('admin/business'));
    }

    public function delete($id)
    {
        delete_id($id, 'business');
        echo json_encode(array('st' => 1));
    }

    public function delete_category($id)
    {
        delete_id($id, 'business_category');
        echo json_encode(array('st' => 1));
    }
}
