<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\SettingsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index()
    {
        if (!is_admin()) {
            return redirect(url(''));
        }
        $data = array();
        $data['currency'] = currency_to_symbol(settings()->currency);
        for ($i = 1; $i <= 13; $i++) {
            $months[] = date("Y-m", strtotime(date('Y-m-01') . " -$i months"));
        }

        for ($i = 0; $i <= 11; $i++) {
            $income = get_admin_income_by_date(date("Y-m", strtotime(date('Y-m-01') . " -$i months")));
            $months[] = array("date" => month_show(date("Y-m", strtotime(date('Y-m-01') . " -$i months"))));
            $incomes[] = array("total" => $income);
        }

        $data['income_axis'] = json_encode(array_column($months, 'date'), JSON_NUMERIC_CHECK);
        $data['income_data'] = json_encode(array_column($incomes, 'total'), JSON_NUMERIC_CHECK);
        $data['upackages'] = get_users_packages();



        $data['invoices'] = admin_count_invoices($invoice = 1);
        $data['estimates'] = admin_count_invoices($invoice = 2);
        $data['total_users'] = count_users();
        $data['business'] = count_business();

        $data['page_title'] = 'Dashboard';
        $data['settings'] = SettingsModel::first();
        $data['users'] = get_latest_users(6);
        $data['net_income'] = get_admin_income_by_year();
        $data['main_content'] = view('admin/admin_dash', $data)->render();
        return view('admin/index', $data);
    }



    public function business()
    {

        check_recurring_payments();
        $data = array();
        $data['page_title'] = 'User Dashboard';
        if (!empty(helper_get_business()) && !empty(helper_get_business()->currency_symbol)) {
            $data['currency'] = html_escape(helper_get_business()->currency_symbol);
        }
        $data['settings'] = SettingsModel::first();
        $data['users'] = get_latest_users(6);
        $data['user'] = DB::table("users")
            ->select(DB::raw("count(*) as total"), DB::raw("(SELECT count(users.id) FROM users WHERE (users.status = 1) AND (users.account_type = 'pro') ) AS pro_user"), DB::raw("(SELECT count(users.id) FROM users WHERE (users.status = 0) ) AS pending_user"))
            ->get();
        $data['overdues'] = get_invoice_report_types($type = 1, $limit = 5);
        $data['pending'] = get_invoice_report_types($type = 0, $limit = 5);
        $data['paids'] = get_invoice_report_types($type = 2, $limit = 5);
        $data['upcoming_payments'] = get_upcomming_recurring_payments();
        $data['net_income'] = DB::table("payment_records as r")
            ->select('r.*', DB::raw('SUM(r.convert_amount) AS total'))
            ->where('r.business_id', helper_get_business()->uid)
            ->where('r.type', 'income')
            ->groupBy(DB::raw("DATE_FORMAT(r.payment_date,'%Y')"))->get(); //get_income_by_year();
        $data['income_report'] = get_income_report();
        for ($i = 1; $i <= 13; $i++) {
            $months[] = date("Y-m", strtotime(date('Y-m-01') . " -$i months"));
        }

        for ($i = 0; $i <= 11; $i++) {
            $income = get_income_report_by_date(date("Y-m", strtotime(date('Y-m-01') . " -$i months")));
            $expense = get_expense_report_by_date(date("Y-m", strtotime(date('Y-m-01') . " -$i months")));
            $expense2 = get_expense_report_by_date2(date("Y-m", strtotime(date('Y-m-01') . " -$i months")));
            $months[] = array("date" => month_show(date("Y-m", strtotime(date('Y-m-01') . " -$i months"))));
            $incomes[] = array("total" => $income);
            $expenses[] = array("total" => $expense + $expense2);
        }
        $data['income_axis'] = json_encode(array_column($months, 'date'), JSON_NUMERIC_CHECK);
        $data['income_data'] = json_encode(array_column($incomes, 'total'), JSON_NUMERIC_CHECK);
        $data['expense_data'] = json_encode(array_column($expenses, 'total'), JSON_NUMERIC_CHECK);
        $data['main_content'] = view('admin/dash', $data)->render();
        return view('admin/index', $data);
    }


    public function change_password()
    {
        $data = array();
        $data['page_title'] = 'Change Password';
        $data['main_content'] = view('admin/change_password', $data)->render();
        return view('admin/index', $data);
    }


    //change password
    public function change(Request $request)
    {
        $id = user()->id;
        $user = get_by_id($id, 'users');

        if (helper_password_verify($request->input('old_pass'), $user->password)) {
            if ($request->input('new_pass') == $request->input('confirm_pass')) {
                $data = array(
                    'password' => helper_hash_password($request->input('new_pass'))
                );
                helper_update_by_id($data, $id, 'users');
                echo json_encode(array('st' => 1));
            } else {
                echo json_encode(array('st' => 2));
            }
        } else {
            echo json_encode(array('st' => 0));
        }
    }
}
