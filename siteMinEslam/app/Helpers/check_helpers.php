<?php

use App\Models\Newsletter_emailsModel;
use App\Models\PackageModel;
use App\Models\Package_featuresModel;
use App\Models\PaymentModel;
use App\Models\Payment_recordsModel;
use App\Models\Users_role_feature_assignModel;
use App\Models\UsersModel;
use App\Models\InvoiceModel;
use Illuminate\Support\Facades\DB;

function check_paid_status($invoice_id)
{
    $count = Payment_recordsModel::where('invoice_id', $invoice_id)
        ->where('type', 'income')
        ->count();
    if ($count > 0) {
        return 1;
    } else {
        return 0;
    }
}

function check_payment_status()
{
    $payment = get_user_payment();
    if (!empty(user()) && user()->user_type == 'trial') {
        return TRUE;
    } else {
        if (isset($payment) && $payment->status == 'verified') {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}


function check_package_limit($slug)
{
    return check_package_limit_by_user($slug, user()->id);
}

function check_permissions($role, $slug)
{
    $response = Users_role_feature_assignModel::where('role', $role)
        ->where('feature_slug', $slug)
        ->where('view_only', 0)
        ->where('business_id', helper_get_business()->uid)
        ->first();
    // var_dump($ci->business);
    if (auth_helper('role') == 'user' || auth_helper('role') == 'viewer') {
        return TRUE;
    } else {
        if (empty($response)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}

function check_package_limit_by_user($slug, $user_id)
{

    $user = PaymentModel::where('user_id', $user_id)->orderBy('id', 'desc')->first();
    if (!empty($user)) {
        $package = PackageModel::find($user->package);

        if ($user->billing_type == 'yearly') {
            $pkslug = 'year_' . $package->slug;
        } else {
            $pkslug = $package->slug;
        }
        $feature = Package_featuresModel::where('slug', $slug)->first();
        // dd($slug);
        return $feature->$pkslug;
    } else {
        return true;
    }
}

// select by function
function check_invoice_payment_records($invoice_id, $parent_id)
{
    $query = DB::table('payment_records', 'r')
        ->select('r.*', DB::raw('SUM(r.amount) as total'));

    if ($parent_id != 0) {
        $query = $query->where("r.invoice_id", $parent_id);
    } else {
        $query = $query->where("r.invoice_id", $invoice_id);
    }
    return $query->first();
}

//get income payments
function check_income_payment_records($invoice_id)
{
    $query = DB::table('payment_records', 'r')
        ->select('r.*', DB::raw('SUM(r.amount) as total'))
        ->where("r.invoice_id", $invoice_id);
    return $query->first();
}

//get income payments
function check_expense_payment_records($invoice_id)
{
    $query = DB::table('payment_records', 'r')
        ->select('r.*', DB::raw('SUM(r.amount) as total'))
        ->where("r.invoice_id", $invoice_id);
    return $query->first();
}


// OK
function helper_check_username($name)
{
    $users = UsersModel::where('user_name', $name)->count();
    if ($users == 1) {
        return UsersModel::where('user_name', $name)->get();
    } else {
        return 0;
    }
}


// get_payment
function check_user_payment($user_id)
{
    return DB::table('payment', 'p')
        ->select('p.*', 'k.name as package_name', 'k.slug')
        ->join('package as k', 'k.id', '=', 'p.package', 'left')
        ->where('p.user_id', $user_id)
        ->orderBy('p.id', 'desc')
        ->first();
}

//check unique language keyword
function admin_check_keyword($keyword)
{
    $query = DB::table('lang_values')
        ->where('keyword', $keyword)
        ->take(1)
        ->count();
    if ($query == 1) {
        return 1;
    } else {
        return 0;
    }
}
