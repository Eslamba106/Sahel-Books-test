<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class CronController extends Controller
{

    public function __construct()
    {
    }

    //expire payments
    public function expire_payments()
    {
        check_recurring_payments();
        check_expire_recurring_invoices();
        $payments = get_expire_payments();
        $trial_users = get_trial_users();

        foreach ($payments as $payment) {
            $data = array(
                'status' => 'expire'
            );

            update($data, $payment->id, 'payment');
        }

        //check trial expire users
        foreach ($trial_users as $user) {
            $user_data = array(
                'status' => 1,
                'user_type' => 'registered',
                'trial_expire' => '0000-00-00'
            );

            update($user_data, $user->id, 'users');
        }
    }
}
