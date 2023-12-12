<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LanguageController extends Controller
{


    public function index()
    {
        $data = array();
        $data['page_title'] = 'Language';
        $data['language'] = [[
            'id' => '',
            'name' => '',
            'slug' => '',
            'short_name' => '',
            'code' => '',
            'text_direction' => '',
            'status' => ''
        ]];
        $data['languages'] = get_language();
        $data['main_content'] = view('admin/language/language', $data)->render();
        return view('admin/index', $data);
    }


    // add language
    public function add(Request $request)
    {
        if ($_POST) {

            $id = $request->input('id');
            if ($id == '') {
                $is_unique = '|unique:language,name';
            } else {
                $is_unique = '';
            }
            $validator =  Validator::make(
                $request->all(),
                [
                    'name' => 'required' . $is_unique
                ]
            );

            if ($validator->fails()) {
                session()->flash('error', implode("<br>", $validator->errors()->all()));
                return redirect(url('admin/language'));
            } else {

                $data = array(
                    'name' => $request->input('name'),
                    'slug' => str_slug($request->input('name')),
                    'short_name' => $request->input('short_name'),
                    'text_direction' => $request->input('text_direction'),
                    'status' => 1
                );


                //if id available info will be edited
                if ($id != '') {
                    helper_update_by_id($data, $id, 'language');
                    session()->flash('msg', helper_trans('msg-edited'));

                    // $lang = str_slug($_POST['lang_name']);

                    // //update language folder name
                    // if (is_dir(APPPATH . 'language/' . $lang)) {
                    //     rename(APPPATH . 'language/' . $lang, APPPATH . 'language/' . str_slug($request->input('name')));
                    // }

                    //update database column
                    // $fields = array(
                    //     $lang => array(
                    //         'name' => str_slug($request->input('name')),
                    //         'type' => 'TEXT',
                    //         'constraint' => '255'
                    //     ),
                    // );
                    // $this->dbforge->modify_column('lang_values', $fields);
                } else {
                    helper_insert($data, 'language');
                    session()->flash('msg', helper_trans('msg-inserted'));

                    // insert new column in language table
                    // $fields = array(
                    //     str_slug($_POST['name']) => array('type' => 'TEXT', 'after' => 'english')
                    // );
                    // $this->dbforge->add_column('lang_values', $fields);

                    // create language folder & file
                    // $dir = str_slug($_POST['name']);
                    // if (!is_dir(APPPATH . 'language/' . $dir)) {
                    //     mkdir(APPPATH . './language/' . $dir, 0777, TRUE);
                    //     copy(APPPATH . 'language/english/website_lang.php', APPPATH . 'language/' . $dir . '/website_lang.php');
                    // }
                }

                return redirect(url('admin/language'));
            }
        }
    }


    //add language values
    public function add_value(Request $request)
    {
        if ($_POST) {

            $check = admin_check_keyword(str_slug($request->input('keyword')));
            //print_r($check) ; exit();
            if ($check == 1) {
                session()->flash('error', helper_trans('keyword-exists'));
                return redirect($_SERVER['HTTP_REFERER']);
            } else {

                $data = array(
                    'label' => $request->input('label'),
                    'keyword' => str_slug($request->input('keyword')),
                    'english' => $request->input('label'),
                    'type' => $request->input('type')
                );

                helper_insert($data, 'lang_values');
                session()->flash('msg', helper_trans('msg-inserted'));
                return redirect(url('admin/language/values/' . $request->input('type') . '/' . $request->input('lang')));
            }
        }
    }


    //show language values
    public function values($type, $slug)
    {
        $data = array();
        $data['page_title'] = 'language';
        $data['value'] = $slug;
        $data['type'] = $type;
        $data['language'] = get_lang_values_by_type(0, 1000, 0, $type);
        $data['main_content'] = view('admin/language/language_values', $data)->render();
        return view('admin/index', $data);
    }

    //update language values
    public function update_values($type)
    {
        $data = array();
        $languages = get_lang_values_by_type(0, 1000, 0, $type);

        ini_set('memory_limit', '-1');
        set_time_limit(-1);

        foreach ($languages as $lang) {
            $value = 'value' . $lang->id;

            $data = array(
                $_POST['lang_type'] => $_POST[$value]
            );
            helper_update_by_id($data, $lang->id, 'lang_values');
        }
        session()->flash('msg', helper_trans('msg-updated'));
        return redirect(url('admin/language'));
    }


    //edit language values
    public function edit($id)
    {
        $data = array();
        $data['page_title'] = 'Edit';
        $data['language'] = select_option($id, 'language');
        $data['main_content'] = view('admin/language/language', $data)->render();
        return view('admin/index', $data);
    }

    //active language    
    public function active($id)
    {
        $data = array(
            'status' => 1
        );

        helper_update_by_id($data, $id, 'language');
        session()->flash('msg', helper_trans('msg-activated'));
        return redirect(url('admin/language'));
    }


    //deactive language
    public function deactive($id)
    {
        $language = get_by_id($id, 'language');

        $data = array(
            'status' => 0
        );

        if ($language->name != 'english') {
            helper_update_by_id($data, $id, 'language');
            session()->flash('msg', helper_trans('msg-deactivated'));
        }
        return redirect(url('admin/language'));
    }


    //delete language
    public function delete($id)
    {
        $language = get_by_id($id, 'language');

        $lang = $language->slug;
        if ($lang != 'english') {

            delete_id($id, 'language');
        }
        echo json_encode(array('st' => 1));
    }
}
