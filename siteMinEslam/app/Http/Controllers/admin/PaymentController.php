<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentModel;
use App\Models\SettingsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{

    public function __construct()
    {
    }


    public function index()
    {
        //check auth
        if (!is_admin()) {
            return redirect(url(''));
        }

        $data = array();
        $data['page_title'] = 'Payment Settings';
        $data['page'] = 'Payment';
        $data['main_page'] = 'Settings';
        $data['settings'] = SettingsModel::find(1);
        $data['currencies'] = select_asc('country');
        $data['packages'] = select_asc('package');
        $data['users'] = get_users();
        $data['main_content'] = view('admin/payment_settings', $data)->render();
        return view('admin/index', $data);
    }


    public function user()
    {
        //check auth
        if (!helper_is_user()) {
            return redirect(url(''));
        }

        $data = array();
        $data['page_title'] = 'Payment Settings';
        $data['page'] = 'Payment';
        $data['settings'] = SettingsModel::find(1);
        $data['currencies'] = select_asc('country');
        $data['packages'] = select_asc('package');
        $data['users'] = get_users();
        $data['main_content'] = view('admin/user_payment_settings', $data)->render();
        return view('admin/index', $data);
    }


    public function convert_currency($amount = '', $from_currency = '', $invoice_id)
    {

        $invoice = get_invoice_details($invoice_id);

        if ($from_currency == $invoice->currency_code) {
            $conversion = '';
            $convert_total = $amount;
        } else {
            $amount = str_replace(",", "", $amount);
            $result = ($amount / get_rate($from_currency)) * get_rate($invoice->currency_code);
            $convert_total = number_format($result, 2);
        }

        return $convert_total;
    }



    public function online($type, $invoice_id)
    {
        $request = Request();
        $data = array();
        if ($_POST) {
            $convert_amount = $this->convert_currency($request->input('amount'), $request->input('currency_code'), $invoice_id);
            $data['final_amount'] = $convert_amount;
            $data['amount'] = $request->input('amount');
        }

        $data['page_title'] = 'Online Payment';
        $data['type'] = $type;
        $data['page'] = 'Invoice';
        $data['invoice'] = get_invoice_details($invoice_id);
        $data['user'] = get_by_id($data['invoice']->user_id, 'users');
        $data['main_content'] = view('admin/user/online_payment', $data)->render();
        return view('admin/index', $data);
    }


    //update settings
    public function update(Request $request)
    {
        //check auth
        if (!is_admin()) {
            return redirect(url(''));
        }


        if ($_POST) {

            if (!empty($request->input('paypal_payment'))) {
                $paypal_payment = $request->input('paypal_payment');
            } else {
                $paypal_payment = 0;
            }

            if (!empty($request->input('stripe_payment'))) {
                $stripe_payment = $request->input('stripe_payment');
            } else {
                $stripe_payment = 0;
            }

            $country = get_by_id($request->input('currency'), 'country');
            $data = array(
                'country' => $request->input('currency'),
                'currency' => $country->currency_code,
                'paypal_mode' => $request->input('paypal_mode'),
                'paypal_email' => $request->input('paypal_email'),
                'publish_key' => $request->input('publish_key'),
                'secret_key' => $request->input('secret_key'),
                'paypal_payment' => $paypal_payment,
                'stripe_payment' => $stripe_payment
            );

            helper_update_by_id($data, 1, 'settings');
            helper_update_settings();
            return redirect($_SERVER['HTTP_REFERER']);
        }
    }



    //update settings
    public function user_update(Request $request)
    {
        //check auth
        if (!helper_is_user()) {
            return redirect(url(''));
        }

        if ($_POST) {

            if (!empty($request->input('paypal_payment'))) {
                $paypal_payment = $request->input('paypal_payment');
            } else {
                $paypal_payment = 0;
            }

            if (!empty($request->input('stripe_payment'))) {
                $stripe_payment = $request->input('stripe_payment');
            } else {
                $stripe_payment = 0;
            }

            $data = array(
                'paypal_email' => $request->input('paypal_email'),
                'publish_key' => $request->input('publish_key'),
                'secret_key' => $request->input('secret_key'),
                'paypal_payment' => $paypal_payment,
                'stripe_payment' => $stripe_payment
            );

            helper_update_by_id($data, user()->id, 'users');
            helper_update_user_data();
            return redirect($_SERVER['HTTP_REFERER']);
        }
    }


    public function offline_payment(Request $request)
    {
        //check auth
        if (!is_admin()) {
            return redirect(url(''));
        }

        if ($_POST) {
            $package = get_by_id($request->input('package'), 'package');
            $payment = check_user_payment($request->input('user'));


            if ($request->input('billing_type') == 'monthly') :
                if (settings()->enable_discount == 1) {
                    $amount = get_discount($package->monthly_price, $package->dis_month);
                } else {
                    $amount = $package->monthly_price;
                }
                $expire_on = date('Y-m-d', strtotime('+1 month'));
            else :
                if (settings()->enable_discount == 1) {
                    $amount = get_discount($package->price, $package->dis_year);
                } else {
                    $amount = $package->price;
                }
                $expire_on = date('Y-m-d', strtotime('+12 month'));
            endif;

            $validator =  Validator::make(
                $request->all(),
                [
                    'user' => 'required',
                    'package' => 'required',
                    'status' => 'required'
                ]
            );

            if ($validator->fails()) {
                session()->flash('errors', implode("<br>", $validator->errors()->all()));
                return redirect(url('admin/payment'));
            } else {

                $data = array(
                    'user_id' => $request->input('user'),
                    'puid' => random_string('numeric', 5),
                    'package' => $request->input('package'),
                    'billing_type' => $request->input('billing_type'),
                    'amount' => $amount,
                    'status' => $request->input('status'),
                    'created_at' => my_date_now(),
                    'expire_on' => $expire_on
                );


                if (empty($payment)) {
                    helper_insert($data, 'payment');

                    $user_data = array(
                        'user_type' => 'registered'
                    );

                    helper_update_by_id($user_data, $request->input('user'), 'users');
                } else {
                    PaymentModel::where('user_id', $request->input('user'))->update((array) helper_xss_clean($data));
                }

                session()->flash('msg', helper_trans('payment-added-successfully'));
                return redirect(url('admin/payment'));
            }
        }
    }


    public function convert_payment($amount = '', $from_currency = '', $biz_curr = '')
    {
        if (empty($from_currency)) {
            return $amount;
        } else {
            $result = ($amount / get_rate($from_currency)) * get_rate($biz_curr);
            $rate = (1 / get_rate($from_currency)) * get_rate($biz_curr);
            $convert_total = str_replace(",", "", $result);
            return $convert_total;
        }
    }


    //payment success
    public function payment_success($invoice_id, $amount = 0)
    {
        $request = Request();

        $invoice = get_invoice_details(md5($invoice_id));
        $customer = get_customer_info($invoice->customer, 'customers');

        if ($invoice->parent_id == 0) {
            $invoice_id = $invoice->id;
        } else {
            $invoice_id = $invoice->parent_id;
        }


        if (get_total_invoice_payments($invoice->id, $invoice->parent_id) == $invoice->grand_total) {
            $status = 2;
        } else {
            $status = 1;
        }

        if (!empty($request->input('payment_method'))) {
            $payment_method = $request->input('payment_method');
        } else {
            $payment_method = '0';
        }

        $paydata = array(
            'amount' => $amount,
            'convert_amount' => $this->convert_payment($amount, $customer->currency, $invoice->currency_code),
            'customer_id' => $invoice->customer,
            'invoice_id' => $invoice_id,
            'business_id' => $invoice->uid,
            'payment_date' => date('Y-m-d'),
            'payment_method' => $payment_method,
            'type' => 'income'
        );

        helper_insert($paydata, 'payment_records');


        $total_payment = get_total_invoice_payments($invoice->id, $invoice->parent_id);

        if ($amount == $invoice->grand_total) {
            $status = 2;
        } else {
            if ($total_payment == $invoice->grand_total) {
                $status = 2;
            } else {
                $status = 1;
            }
        }

        $invoice_data = array(
            'status' => $status
        );
        helper_update_by_id($invoice_data, $invoice->id, 'invoice');

        return redirect(url('admin/payment/success'));
    }

    public function success()
    {
        $data = array();
        $data['success_msg'] = 'Success';
        $data['main_content'] = view('admin/user/online_payment_msg', $data)->render();
        return view('admin/index', $data);
    }

    //payment cancel
    public function payment_cancel($invoice_id)
    {
        $data = array();
        $data['error_msg'] = 'Error';
        $data['main_content'] = view('admin/user/online_payment_msg', $data)->render();
        return view('admin/index', $data);
    }

    //stripe payment
    public function stripe_payment($invoice_id, $amount, $cus_amount = '')
    {

        $request = Request();

        $invoice = get_invoice_details(md5($invoice_id));

        $user = get_by_id($invoice->user_id, 'users');
        $amount = $amount;

        \Stripe\Stripe::setApiKey($user->secret_key);

        try {
            $charge = \Stripe\Charge::create([
                "amount" => $amount * 100,
                "currency" => $invoice->currency_code,
                "source" => $request->input('stripeToken'),
                "description" => "Payment from " . get_settings()->site_name
            ]);
            $chargeJson = $charge->jsonSerialize();

            $amount                  = $chargeJson['amount'] / 100;
            $balance_transaction     = $chargeJson['balance_transaction'];
            $currency                = $chargeJson['currency'];
            $status                  = $chargeJson['status'];
            $payment = 'success';
        } catch (\Exception $e) {
            $error = $e->getMessage();
            session()->flash('error', $error);
            $payment = 'failed';
        }

        if ($payment == 'success') :
            return redirect(url('admin/payment/payment_success/' . $invoice_id . '/' . $cus_amount));
        else :
            return redirect(url('admin/payment/payment_cancel/' . $invoice_id));
        endif;
    }
}
