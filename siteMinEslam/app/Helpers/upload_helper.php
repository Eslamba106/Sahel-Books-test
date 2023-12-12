<?php

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

function upload_helper(Request $request, $filed_name, $save_path = '', $required = 0, $mimes = '', $other_validators = '')
{
    if ($request->has($filed_name)) {
        $validator =  Validator::make(
            $request->all(),
            [
                $filed_name => ($required == 1 ? 'required|' : '') .
                    ($mimes != '' ? 'mimes:' . $mimes : '') .
                    ($other_validators != '' ? '|' . $other_validators : '')
            ],
            [
                'required' => 'file is required',
                'mimes' => 'Allowed only ' . $mimes,
            ]
        );

        if ($validator->fails()) {
            echo json_encode([
                'st' => 0,
                'msg' => $validator->errors()
            ]);
            exit;
            return 0;
        }

        $file = $request->file($filed_name);


        $date = new DateTime();
        $file_name = $date->getTimestamp() . '-' . rand(10,100) . '.' . $file->extension();

        // dd(base_path() . '/uploads');
        $file->storeAs('' . $save_path, $file_name);


        // return the file name and remove duplicate slash characters
        return  $save_path . (empty($save_path) || substr($save_path, -1) == '/' ? '' : '/') . $file_name;
    }
}
