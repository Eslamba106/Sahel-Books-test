<?php

use App\Models\Country;
use App\Models\TaxModel;
use App\Models\PaymentModel;
use App\Models\ProductsModel;
use App\Models\Tax_typeModel;
use Illuminate\Support\Facades\DB;
use App\Models\Users_role_featureModel;

function select_row_where($table, $where = [])
{
    $table_row = DB::table($table);
    foreach (array_keys($where) as $k) {
        $table_row = $table_row->where($k, $where[$k]);
    }
    return $table_row->first();
}
function select_where($table, $where = [])
{
    $table_rows = DB::table($table);
    foreach (array_keys($where) as $k) {
        $table_rows = $table_rows->where($k, $where[$k]);
    }
    $table_rows =  $table_rows->get()->toArray();
    $table_rows = array_map(function ($value) {
        return (array)$value;
    }, $table_rows);
    return $table_rows;
}
function select_last_row_where($table, $where = [])
{
    $table_row = DB::table($table);
    foreach (array_keys($where) as $k) {
        $table_row = $table_row->where($k, $where[$k]);
    }
    return $table_row->orderBy('id', 'desc')->first();
}

// OK
function helper_update_by_id($arr, $id, $table)
{
    return DB::table($table)->where('id', $id)->update((array) helper_xss_clean($arr));
}


// select by function
function get_by_user($table)
{
    // 
    if (user()->role == "sub_user")
        $user_id = user()->parent_id;
    else
        $user_id = session('id');

    return DB::table($table)
        ->where('business_id', helper_get_business()->uid)
        ->where('user_id', $user_id)
        ->orderBy('id', 'desc')
        ->get();
}

// select by function
function get_by_user_and_type($table, $type)
{
    // 
    if (user()->role == "sub_user")
        $user_id = user()->parent_id;
    else
        $user_id = session('id');


    $query = DB::table($table, 't');

    if ($type == 'is_sell')
        $query = $query->join("categories as cat", "cat.id", "=", "t.income_category", "left");
    else
        $query = $query->join("categories as cat", "cat.id", "=", "t.expense_category", "left");

    if (
        $type == 'is_sell'
    ) {
        $query = $query->where('is_sell', 1);
    } else {
        $query = $query->where('is_buy', 1);
    }
    $query = $query->select("t.*", "cat.name as cat_name");
    return $query
        ->where('t.business_id', helper_get_business()->uid)
        ->where('t.user_id', $user_id)
        ->orderBy('t.id', 'desc')
        ->get();
}


// select by function
function get_total_by_user($table, $type)
{
    // 
    if (user()->role == "sub_user")
        $user_id = user()->parent_id;
    else
        $user_id = session('id');

    return DB::table($table)
        ->where('type', $type)
        ->where('business_id', helper_get_business()->uid)
        ->where('user_id', $user_id)
        ->count();
}


// OK
// asc select function
function select_asc($table, $lang = "", $orderBy = "", $groupBy = "") // 
{
    $query = DB::table($table);

    if (!empty($lang))
        $query = $query->where("lang", $lang);

    if ($orderBy != "") {
        $ord_fields = explode(":", $orderBy);
        $query = $query->orderBy(DB::raw($ord_fields[0]), $ord_fields[1]);
    } else
        $query = $query->orderBy('id', 'asc');

    if ($groupBy != "")
        $query = $query->groupBy($groupBy);

    return $query
        ->get();
}

function get_user_taxes_by_gst()
{
    // 
    if (user()->role == "sub_user")
        $user_id = user()->parent_id;
    else
        $user_id = session('id');
    $gsts = Tax_typeModel::where('business_id', helper_get_business()->uid)->where('user_id', $user_id)->get();

    foreach ($gsts as $key => $value) {
        $taxes = TaxModel::where('type', $value->id)->where([
            ['is_invoices', 1]
        ])->orderBy('id', 'desc')->get();
        $gsts[$key]->taxes = $taxes;
    }
    return $gsts;
}

// get_payment
function get_user_payment_by_user($user_id)
{
    return PaymentModel::where('user_id', $user_id)->orderBy('id', 'desc')->first();
}


function get_user_payment()
{


        $user_id = session('id');

    return get_user_payment_by_user($user_id);
}
// get expense depend on year
if (!function_exists('get_expense_by_year')) {
    function get_expense_by_year($year)
    {
        // Get a reference to the controller object
        $query = DB::table('expenses', 'e')->select('e.*')->selectRaw('SUM(e.amount) AS total')->whereRaw("DATE_FORMAT(e.date,?)", [$year])->where("e.business_id", helper_get_business()->uid)->groupByRaw("DATE_FORMAT(e.date,?)", [$year])->get()->toArray();
        /*var_dump($query);
        die;*/
        if (empty($query)) {
            $expens1 = 0;
        } else {
            $expens1 = $query[0]->total;
        }


        $query = DB::table('payment_records', 'r')->select('r.*')->selectRaw('SUM(r.convert_amount) AS total')->whereRaw("DATE_FORMAT(r.payment_date,?)", [$year])->where("r.business_id", helper_get_business()->uid)->where("r.type", 'expense')->groupByRaw("DATE_FORMAT(r.payment_date,?)", [$year])->get();
        if (empty($query)) {
            $expens2 = 0;
        } else {
            $sum = 0;
            foreach ($query as $value) {
                $sum += $value->total;
            }
            $expens2 = $sum;
        }


        return $expens1 + $expens2;
    }
}
//get invoice items
function get_invoice_report_types($type, $limit)
{
    // 
    if (user()->role == "sub_user")
        $user_id = user()->parent_id;
    else
        $user_id = session('id');

    $query = DB::table('invoice', 'i')
        ->select('i.*', 'c.name as customer_name')
        ->leftJoin('customers as c', 'c.id', '=', 'i.customer')
        ->where('i.user_id', $user_id)
        ->where('i.type', 1);
    if ($type == 1) {
        $query = $query->where('i.payment_due', '<', date('Y-m-d'));
    }

    $query = $query->where('i.status', $type)
        ->where('i.business_id', helper_get_business()->uid)
        ->orderBy('i.id', 'desc')
        ->take($limit)
        ->get();

    return $query;
}
// select by function
function get_tax_id($id)
{
    return DB::table('tax', 't')
        ->join('tax_type as p', 'p.id', '=', 't.type', 'left')
        ->where('t.is_invoices', 1)
        ->where('t.id', $id)
        ->select('t.*', 'p.name as type_name')
        ->first();
    // $this->db->select('t.*, p.name as type_name');
    // $this->db->from('tax as t');
    // $this->db->join('tax_type p', 'p.id = t.type', 'LEFT');
    // $this->db->where('t.is_invoices', 1);
    // $this->db->where('t.id', $id);
    // $query = $this->db->get();
    // $query = $query->row();
    // return $query;
}

function get_by_md5_id($id, $table)
{
    return DB::table($table)->where(DB::raw('md5(id)'), $id)->first();
}

function get_by_md5_data($id, $table)
{
    $data = DB::table($table)->where(DB::raw('md5(id)'), $id)->get()->toArray();
    $data[0] = (array) $data[0];
    return $data;
}

function helper_insert($data, $table)
{
    return DB::table($table)->insertGetId((array) helper_xss_clean($data));
}

function select_option($id, $table)
{
    $data = DB::table($table)->where('id', $id)->get()->toArray();
    $data[0] = (array) $data[0];
    return $data;
}

function delete_id($id, $table)
{
    return DB::table($table)->where('id', $id)->delete();
}

function get_by_id($id, $table)
{
    return DB::table($table)->where('id', $id)->first();
}

function date_difference($date1)
{
    $date2 = date('Y-m-d');
    $datetime1 = date_create($date1);
    $datetime2 = date_create($date2);
    $interval = date_diff($datetime1, $datetime2);
    return $interval->format('%R%a');
}


function helper_get_product($id)
{
    return ProductsModel::find($id);
}
