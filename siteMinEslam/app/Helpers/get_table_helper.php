<?php

use App\Models\Bank_payment_recordsModel;
use App\Models\CountryModel;
use App\Models\CustomersModel;
use App\Models\ExpensesModel;
use App\Models\IncomesModel;
use App\Models\InvoiceModel;
use App\Models\PaymentModel;
use App\Models\Product_taxModel;
use App\Models\RatesModel;
use App\Models\UsersModel;
use App\Models\VendorsModel;
use App\Models\Lang_valuesModel;
use Illuminate\Support\Facades\DB;


// get information about customer by id from customer table
function get_customer_info($id)
{
    return DB::table('customers', 'c')
        ->join('country as t', 't.id', '=', 'c.country', 'left')
        ->select('c.*', 't.name as country', 't.currency_name', 't.currency_symbol')
        ->where('c.id', $id)
        ->first();
        // dd($cust);
}


// take customer id and send it to get customer information
function helper_get_customer($id)
{
    return get_customer_info($id);
}


// 
function get_rate($code)
{
    $rates = RatesModel::where('code', $code)->first();
    // dd($rates);
    if (!empty($rates)) {
        return $rates->rate;
    }
    return 0;
}
// advanced customers record and join with his currency from customer table and the function depend on two parameters  
function get_customer_advanced_record_by_business($customer_id, $business_id)
{
    return DB::table('payment_advance', 'p')
        ->join('customers as c', 'c.id', '=', 'p.customer_id')
        ->where("p.customer_id", $customer_id)
        ->where("p.business_id", $business_id)
        ->select('p.*', 'c.currency')
        ->first();
}
// take customer id and return customer and his currency by used function get customer advanced record by business
function get_customer_advanced_record($customer_id)
{
    return get_customer_advanced_record_by_business($customer_id, helper_get_business()->uid);
}


// select by function
function get_product_categories($type)
{
    // 
    if (user()->role == "sub_user")
        $user_id = user()->parent_id;
    else
        $user_id = session('id');

    return DB::table('categories')
        ->where('type', $type)
        ->where('user_id', $user_id)
        ->where('business_id', helper_get_business()->uid)
        ->get();
}


//get customer
function get_customers()
{
    $customers = DB::table('customers', 'c')->select('c.*', 'n.name as country_name', 'n.currency_name', 'n.currency_symbol', 'n.currency_code');
    $customers = $customers->where('business_id', helper_get_business()->uid);
    $customers = $customers->join('country as n', 'n.id', '=', 'c.country', 'left');
    return  $customers->get();
}

//get tax
// get the tax on product 
function get_tax_by_product($product_id)
{
    return Product_taxModel::where('product_id', $product_id)->get();
}


//get incomes
// get all incomes which user do it 
function get_user_incomes()
{
    $query = DB::table('incomes', 'e')
        ->select('e.*', 'cu.name as customer_name', 'c.name as category_name')
        ->join('customers as cu', 'cu.id', '=', 'e.customer', 'left')
        ->join('categories as c', 'c.id', '=', 'e.category', 'left')
        ->where("e.business_id", helper_get_business()->uid)
        ->where("e.user_id", session('id'))
        ->get();
    if (empty($query)) {
        return 0;
    } else {
        return $query;
    }
}


//get expense_report
function get_user_expenses()
{
    // 
    if (user()->role == "sub_user")
        $user_id = user()->parent_id;
    else
        $user_id = session('id');
    $query = DB::table('expenses', 'e')->select('e.*', 'v.name as vendor_name', 'c.name as category_name')
        ->join('vendors as v', 'v.id', '=', 'e.vendor', 'left')
        ->join('categories as c', 'c.id', '=', 'e.category', 'left')
        ->where("e.business_id", helper_get_business()->uid)
        ->where("e.user_id", $user_id)
        ->orderBy("e.date", "desc")
        ->get();

    if (empty($query)) {
        return 0;
    } else {
        return $query;
    }
}

function helper_get_vendor($id)
{
    return VendorsModel::find($id);
}

// select by function
function get_user_dash_taxes()
{
    // 
    if (user()->role == "sub_user")
        $user_id = user()->parent_id;
    else
        $user_id = session('id');
    return DB::table('tax', 't')->select('t.*', 'p.name as type_name')
        ->join('tax_type as p', 'p.id', '=', 't.type', 'left')
        ->where('t.business_id', helper_get_business()->uid)
        ->where('t.user_id', $user_id)
        ->orderBy('t.id', 'desc')
        ->get();
}




function get_total_value_by_user($table, $type, $user_id, $check_date = true)
{
    // echo $user_id;
    // exit;
    $payment = PaymentModel::where('user_id', $user_id)->orderBy('id', 'desc')->first();
    $user = UsersModel::where('id', $user_id)->first();
    // 
    $user_role = $user->role;
    $table = DB::table($table);
    // 
    if ($user_role == 'sub_user')
        $table->where('user_id', $user->parent_id);
    else
        $table->where('user_id', $user->id);
    if (!empty($payment->created_at) && $check_date == true) {
        $table->where(DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d")'), '>=', $payment->created_at);
    }
    if ($type != 0) {
        $table->where('type', $type);
    }
    $table->orderBy('id', 'DESC');
    $value = $table->count();
    return $value;
}

function get_total_value($table, $type, $check_date = true)
{
    return get_total_value_by_user($table, $type, user()->id, $check_date);
}

//get language values
function check_recurring_payments()
{
    $invoices = get_recurring_payments();
    foreach ($invoices as $invoice) {
        add_recurring_payments($table = 'invoice', $primary_field = 'id', $invoice->id);

        $affected = DB::table('invoice')->where('id',  $invoice->id)->update(['frequency_count' => $invoice->frequency_count + 1]);
    }
    return;
}

// recurring invoices
function get_recurring_payments()
{
    if (isset(user()->role) && user()->role == "sub_user")
        $user_id = user()->parent_id;
    else
        $user_id = session('id');
    // 
    if (isset(helper_get_business()->uid)) {
        $query = DB::table('invoice')
            ->where('business_id', '=', helper_get_business()->uid)
            ->where('user_id', '=', $user_id)
            ->where('recurring', '=', 1)
            ->where('is_completed', '=', 0)
            ->where('frequency_count', '=', 0)
            ->where('next_payment', '=', date('Y-m-d'))
            //->groupBy('status')
            ->get();
        return $query;
    } else
        return [];
}


function add_recurring_payments($table, $primary_field, $primary_val)
{
    /* generate the select query */
    $result = DB::table($table)
        ->where($primary_field, '=', $primary_val)
        //->groupBy('status')
        ->get();

    foreach ($result as $row) {
        foreach ($row as $key => $val) {
            if ($key != $primary_field) {
                /* ->set can be used instead of passing a data array directly to the insert or update functions */
                $insertData[] = [
                    $key => $val,
                ];
            } //endif
        } //endforeach
    } //endforeach

    /* insert the new record into table*/
    $insert_id = DB::table($table)->insertGetId($insertData);

    $invoice = get_by_id($insert_id, 'invoice');

    $recurring_data = array(
        'title' => 'invoice ' . $invoice->id,
        'number' => date('Y') . '-' . str_pad(get_invoice_number(1), 2, '0', STR_PAD_LEFT),
        'parent_id' => $primary_val,
        'is_sent' => 0,
        'status' => 1,
        'is_completed' => 0,
        'date' => date('Y-m-d'),
        'recurring_start' => date('Y-m-d'),
        'next_payment' => date_count($invoice->next_payment, $invoice->frequency)
    );
    DB::table('invoice')
        ->where('id', $invoice->id)
        ->update($recurring_data);
}


// currency to symbol
function currency_to_symbol($currency = '')
{
    $data = DB::table('country')->where('currency_code', $currency)->first();
    if (!empty($data)) {
        return $data->currency_symbol;
    } else {
        return '';
    }
}


// get income reports by customer
function get_incomes_by_customer($customer, $type)
{
    $query = DB::table('invoice')
        ->where('customer', $customer)
        ->where('type', $type)
        ->get();

    if (!empty($_GET['start']) || !empty($_GET['end'])) {
        $query = $query->where("date", '>=', $_GET['start']);
        $query = $query->where("date", '<=', $_GET['end']);
    }
    if (empty($query)) {
        return 0.00;
    } else {
        $sum = 0;
        foreach ($query as $value) {
            $sum += $value->convert_total;
            // next
            // if ($value->type == 3 || $value->type == 5) {
            //     $sum += $value->convert_total - $value->total_taxes;
            // } else {
            //     $customer_data = CustomersModel::find($value->customer);
            //     $sum += helper_convert_payment($value->grand_total - $value->total_taxes, $customer_data->currency);
            // }
        }
        return $sum;
    }
}


// get all users
function get_users()
{
    return DB::table('users', 'u')
        ->join('payment as p', 'p.user_id', '=', 'u.id', 'left')
        ->join('package as pk', 'pk.id', '=', 'p.package')
        ->where('u.role', 'user')
        ->orderBy("u.id", 'desc')
        ->groupBy('u.id')
        ->select('u.id', 'u.name', 'u.email', 'p.status as payment_status', 'p.package', 'p.expire_on')
        ->get();
}


//get report
function get_income_report()
{
    $query = DB::table('payment_records', 'r')
        ->select('r.*', DB::raw('SUM(r.convert_amount) as total'))
        ->where("r.business_id", helper_get_business()->uid)
        ->where("r.type", 'income')
        ->orderBy('r.payment_date', 'desc')
        ->groupBy('r.payment_date');
    return $query->get();
}

//get report
function get_income_report_by_date($date)
{
    // $query = DB::table('payment_records', 'r')
    $query = DB::table('invoice', 'r')
        ->select('r.*', DB::raw('SUM(r.convert_total) as total'))
        ->where("r.business_id", helper_get_business()->uid)
        ->where("r.type", 1)
        ->where("r.status", '!=', 0)
        ->where(DB::raw('DATE_FORMAT(r.date,"%Y-%m")'), $date)
        ->groupBy('r.date')
        ->get();

    if (empty($query)) {
        return 0;
    } else {
        $sum = 0;
        foreach ($query as $value) {
            $sum += $value->total;
        }
        return $sum;
    }
}

//get expense_report
function get_expense_report_by_date($date)
{
    $query = DB::table('expenses', 'e')
        ->select('e.*', DB::raw('SUM(e.amount) as total'))
        ->where("e.business_id", helper_get_business()->uid)
        ->where(DB::raw('DATE_FORMAT(e.date,"%Y-%m")'), $date)
        ->get();
    if (empty($query)) {
        return 0;
    } else {
        return $query[0]->total;
    }
}


function get_expense_report_by_date2($date)
{
    $query = DB::table('payment_records', 'r')
        ->select('r.*', DB::raw('SUM(r.convert_amount) as total'))
        ->where("r.business_id", helper_get_business()->uid)
        ->where("r.type", 'expense')
        ->where(DB::raw('DATE_FORMAT(r.payment_date,"%Y-%m")'), $date)
        ->groupBy('r.payment_date')
        ->get();

    if (empty($query)) {
        return 0;
    } else {
        $sum = 0;
        foreach ($query as $value) {
            $sum += $value->total;
        }
        return $sum;
    }
}
