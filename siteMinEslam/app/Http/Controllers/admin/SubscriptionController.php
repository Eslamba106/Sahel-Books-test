<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\PackageModel;
use App\Models\PaymentModel;

class SubscriptionController extends Controller
{

    public function index()
    {
        $data = array();
        $data['page_title'] = 'Subscription';
        $data['user'] = get_my_package();
        $data['packages'] = select_asc('package');
        $data['features'] = select_asc('package_features');
        if (user()->user_type == 'trial') {
            $page_load = 'trial_subscription';
        } else {
            $page_load = 'subscription';
        }
        $data['main_content'] = view('admin/user/' . $page_load, $data)->render();
        return view('admin/index', $data);
    }


    public function upgrade($slug = '', $billing_type = '', $status = 0)
    {
        if ($status == 0) {
            $data = array();
            $data['slug'] = $slug;
            $data['billing_type'] = $billing_type;

            $data['main_content'] = view('admin/user/payment_confirm', $data)->render();
            return view('admin/index', $data);
        } else {
            if (user()->user_type == 'trial') {
                $user_data = array(
                    'user_type' => 'registered'
                );
                helper_update_by_id($user_data, user()->id, 'users');
            }

            $data = array();
            $data['page_title'] = 'Upgrade';
            $data['page'] = 'Payment';
            $payment = get_user_payment();
            $uid = random_string('numeric', 5);
            $data['payment_id'] = (user()->user_type == 'trial' ? $uid : $payment->puid);
            $data['billing_type'] = $billing_type;
            $data['package'] = PackageModel::where('slug', $slug)->first();
            $payments = PaymentModel::where('user_id', user()->id)->get();
            $package = $data['package'];
            $uid = random_string('numeric', 5);

            foreach ($payments as $pay) {
                $pays_data = array(
                    'status' => 'expired'
                );
                helper_update_by_id($pays_data, $pay->id, 'payment');
            }

            if ($billing_type == 'monthly') :
                $amount = $package->monthly_price;
                $expire_on = date('Y-m-d', strtotime('+1 month'));
            else :
                $amount = $package->price;
                $expire_on = date('Y-m-d', strtotime('+12 month'));
            endif;

            if (number_format($amount, 0) == 0) :
                $status = 'verified';
            else :
                $status = 'pending';
            endif;

            //create payment
            $pay_data = array(
                'user_id' => user()->id,
                'puid' => $uid,
                'package' => $package->id,
                'amount' => $amount,
                'billing_type' => $billing_type,
                'status' => $status,
                'created_at' => my_date_now(),
                'expire_on' => $expire_on
            );

            helper_insert($pay_data, 'payment');

            if (number_format($amount, 0) == 0) {
                return redirect(url('admin/dashboard/business'));
            } else {
                if (settings()->enable_paypal == 1) {
                    $data['main_content'] = view('admin/user/payment', $data)->render();
                    return view('admin/index', $data);
                } else {
                    return redirect(url('admin/subscription'));
                }
            }
        }
    }


    //payment success
    public function payment_success($billing_type, $package_id, $payment_id)
    {
        $package = PackageModel::find($package_id);
        $get_payment = PaymentModel::where('puid', $payment_id)->first();
        $user_id = $get_payment->user_id;
        $payment = PaymentModel::where('user_id', $user_id)->orderBy('id', 'desc')->first();

        $uid = random_string('numeric', 5);

        if ($billing_type == 'monthly') :
            $amount = $package->monthly_price;
            $expire_on = date('Y-m-d', strtotime('+1 month'));
        else :
            $amount = $package->price;
            $expire_on = date('Y-m-d', strtotime('+12 month'));
        endif;

        $data = array();
        $pay_data = array(
            'user_id' => user()->id,
            'package' => $package->id,
            'puid' => $payment_id,
            'status' => 'verified',
            'billing_type' => $billing_type,
            'amount' => $amount,
            'expire_on' => $expire_on,
            'created_at' => my_date_now()
        );


        if (user()->user_type == 'trial') {

            helper_insert($pay_data, 'payment');
            //update user type
            $user_data = array(
                'user_type' => 'registered',
                'trial_expire' => '0000-00-00'
            );
            helper_update_by_id($user_data, user()->id, 'users');
        } else {
            helper_update_by_id($pay_data, $payment->id, 'payment');
        }

        $data['success_msg'] = 'Success';
        $data['main_content'] = view('admin/user/payment_msg', $data)->render();
        return view('admin/index', $data);
    }


    //payment cancel
    public function payment_cancel($billing_type, $package_id, $payment_id)
    {
        $data = array();
        $data['error_msg'] = 'Error';
        $data['main_content'] = view('admin/user/payment_msg', $data)->render();
        return view('admin/index', $data);
    }
}
