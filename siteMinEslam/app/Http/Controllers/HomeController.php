<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PackageModel;
use App\Models\PagesModel;
use App\Models\PaymentModel;
use App\Models\SettingsModel;
use App\Models\TestimonialsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{


    public function index()
    {
        $data = array();
        $data['page_title'] = 'Home';
        $data['testimonials'] = TestimonialsModel::orderBy('id', 'asc')->get();
        $data['main_content'] = view('home', $data)->render();
        return view('index', $data);
    }

    //switch language
    public function switch_lang($language = "")
    {
        session()->put('my_site_lang', $language);
        session()->save();
        if (!empty($_SERVER['HTTP_REFERER'])) {
            return redirect($_SERVER['HTTP_REFERER']);
        } else {
            return redirect('/admin/dashboard/business');
        }
    }


    //payment success
    public function payment_success($payment_id)
    {
        $payment = PaymentModel::where('puid', $payment_id)->first();
        $data = array(
            'status' => 'verified'
        );


        $user_data = array(
            'status' => 1
        );

        if (!empty($payment)) {
            helper_update_by_id($user_data, $payment->user_id, 'users');
            helper_update_by_id($data, $payment->id, 'payment');
        }
        $data['success_msg'] = 'Success';
        return view('purchase', $data);
    }

    //payment cancel
    public function payment_cancel($payment_id)
    {
        $payment = PaymentModel::where('puid', $payment_id)->first();
        $data = array(
            'status' => 'pending'
        );

        helper_update_by_id($data, $payment->id, 'payment');
        $data['error_msg'] = 'Error';
        return view('purchase', $data);
    }


    //check username using ajax
    public function check_username($value)
    {
        $result = helper_check_username($value);
        if (!empty($result)) {
            echo json_encode(array('st' => 2));
        } else {
            echo json_encode(array('st' => 1));
        }
    }


    //send contact message
    public function send_message(Request $request)
    {
        if ($_POST) {
            $data = array(
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'message' => $request->input('message'),
                'created_at' => my_date_now()
            );

            //check reCAPTCHA status
            if (!$this->recaptcha_verify_request()) {
                session()->flash('error', helper_helper_trans('recaptcha-is-required'));
            } else {
                helper_insert($data, 'site_contacts');
                session()->flash('msg', helper_helper_trans('message-send-successfully'));
            }

            return redirect($_SERVER['HTTP_REFERER']);
        }
    }
}
