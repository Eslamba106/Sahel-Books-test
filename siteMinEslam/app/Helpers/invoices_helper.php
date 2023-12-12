<?php

use Stripe\Invoice;
use App\Models\TaxModel;
use App\Models\InvoiceModel;
use App\Models\Lang_valuesModel;
use App\Models\Free_invoiceModel;
use App\Models\Invoice_taxesModel;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice_items_taxes;


function get_invoice_number($type)
{
    return InvoiceModel::where([['user_id', user()->id], ['business_id', helper_get_business()->uid], ['type', $type]])->count() + 1;
}

function get_taxes($id){
    $query = DB::table('invoice_items' , 'i')
    ->where('i.invoice_id' , $id)
    ->join('invoice_items_taxes as t', 't.invoice_id', '=', 'i.invoice_id', 'left')
    ->join('tax as x' , 'x.id' , '=' , 't.tax_id' , 'left')
    ->select('i.*' , 'x.name as tax_name' , 'x.rate as tax_rate' )
    ->get();
    // dd( $query);
    return $query ;
}
function helper_get_invoice_items($id)
{
        $query = DB::table('invoice_items', 'i')
        ->where('i.invoice_id', $id)
        ->join('invoice_items_taxes as iit', 'iit.invoice_id', '=', 'i.invoice_id', 'left')
        ->join('tax as t', 't.id', '=', 'iit.tax_id', 'left')
        ->join('products as p', 'p.id', '=', 'invoice_item_id', 'left')
        ->select('i.*', 'iit.tax_rate as tax_rate' , 'iit.tax_value as tax_value'  , 't.name as tax_name' , 'p.name as item_name', 'p.details as details') //, '
        ->distinct()
        ->groupBy('i.id')
        ->get();
    return $query;

}
function get_total_invoice_payments($invoice_id, $parent_id = 0)
{

    // $this->db->select('r.*');
    // $this->db->select_sum('r.amount', 'total');
    // $this->db->from('payment_records r');
    // if ($parent_id != 0) {
    //     $payment_query = $payment_query->where("r.invoice_id", $parent_id);
    // } else {
    //     $payment_query = $payment_query->where("r.invoice_id", $invoice_id);
    // }
    // $query = $this->db->get();
    // $query = $query->row();
    // return $query;
    $payment_query = DB::table('payment_records', 'r');

    if ($parent_id != 0) {
        $payment_query = $payment_query->where("r.invoice_id", $parent_id);
    } else {
        $payment_query = $payment_query->where("r.invoice_id", $invoice_id);
    }

    $payment = $payment_query->select('r.*', DB::raw('SUM(r.amount) AS total'))->first();
    if (empty($payment)) {
        return 0;
    } else {
        return $payment->total;
    }
}

function get_invoice_details($id)
{
    return DB::table('invoice', 'i')
        ->join('business as b', 'b.uid', '=', 'i.business_id')
        ->join('country as t', 't.id', '=', 'b.country')
        ->where(DB::raw('md5(i.id)'), $id)
        ->select(
            'i.*',
            'b.uid',
            'b.color',
            'b.name as business_name',
            'b.address as business_address',
            'b.logo',
            'b.biz_number',
            'b.vat_code',
            't.currency_code',
            't.currency_symbol',
            't.name as country'
        )
        ->first();
}

function get_single_business($id)
{
    return [(array) DB::table('business', 'b')
        ->join('country as n', 'n.id', '=', 'b.country', 'left')
        ->join('business_category as c', 'c.id', '=', 'b.category', 'left')
        ->where('b.id', $id)
        ->select(
            'b.*',
            'n.name as country_name',
            'n.currency_name',
            'n.currency_symbol',
            'n.currency_code',
            'c.name as category_name'
        )
        ->first()];
}


function get_invoices_by_type($total, $limit, $offset, $status, $type)
{
    // 
    if (isset(user()->role) && user()->role == "sub_user")
        $user_id = user()->parent_id;
    else
        $user_id = session('id');

    $data = DB::table('invoice', 'i');


    /*  */
    $data = $data->join('invoice_taxes', 'i.id', '=', 'invoice_id', 'left');
    $data = $data->join('tax', 'tax.id', '=', 'tax_id', 'left');

    $data = $data->where('i.business_id', helper_get_business()->uid);
    $data = $data->where('i.user_id', $user_id);
    if ($status != 0 && $status != 3 && $status != 7 && $status != 8 && $status != 9) {
        $data = $data->where('i.status', $status);
    }
    if (isset($_GET['customer']) && $_GET['customer'] != '') {
        $data = $data->where('i.customer', $_GET['customer']);
    }
    if (isset($_GET['status']) && $_GET['status'] != "" && $_GET['status'] != 3) {
        $data = $data->where('i.status', $_GET['status']);
    }
    if (isset($_GET['status']) && $_GET['status'] != "" && $_GET['status'] == 3) {
        $data = $data->where('i.is_sent', 1);
    }
    if (isset($_GET['recurring']) && $_GET['recurring'] == 1) {
        $data = $data->where('i.recurring', 1);
    }
    if (!empty($_GET['start_date'])) {
        $where[] = [DB::raw("DATE_FORMAT(i.date,'%Y-%m-%d')"), '>=', $_GET['start_date']];
        $data = $data->where($where);
    }
    if (!empty($_GET['end_date'])) {
        $where[] = [DB::raw("DATE_FORMAT(i.date,'%Y-%m-%d')"), '<=', $_GET['end_date']];
        $data = $data->where($where);
    }
    if (isset($_GET['search_number']) && !empty($_GET['search_number'])) {
        if (strpos($_GET['search_number'], 'INV') !== false) {
            $data = $data->where('i.number', $_GET['search_number']);
        } else {
            $data = $data->where('i.model_number', $_GET['search_number']);
            $data = $data->join('invoice_items as it', 'it.invoice_id', '=', 'i.id');
            $data = $data->groupBy('it.invoice_id');
            $data = $data->join('products as p', 'p.id', '=', 'it.item');
            $data = $data->orWhere('p.code', 'like', $_GET['search_number']);
        }
    }

    $data = $data->where('i.type', $type);
    $data = $data->select('i.*', 'tax.rate');
    $data = $data->orderBy('i.id', 'desc');

    if ($total == 1) {
        return $data->count();
    } else {
        return $data->skip($offset)->take($limit)->get();
    }
}

// get invoices
function get_estimates_by_type($total, $limit, $offset, $status, $type)
{
    if (user()->role == "sub_user")
        $user_id = user()->parent_id;
    else
        $user_id = session('id');

    $data = DB::table('invoice', 'i');
    $data = $data->select('i.*', 'tax.rate');

    /*  */
    $data = $data->join('invoice_taxes', 'i.id', '=', 'invoice_id', 'left');
    $data = $data->join('tax', 'tax.id', '=', 'tax_id', 'left');

    $data = $data->where('i.business_id', helper_get_business()->uid);
    $data = $data->where('i.user_id', $user_id);
    if (isset($_GET['customer']) && $_GET['customer'] != '') {
        $data = $data->where('i.customer', $_GET['customer']);
    }
    if (isset($_GET['status']) && $_GET['status'] != "" && $_GET['status'] != 3) {
        $data = $data->where('i.status', $_GET['status']);
    }
    if (isset($_GET['status']) && $_GET['status'] != "" && $_GET['status'] == 3) {
        $data = $data->where('i.is_sent', 1);
    }
    if (isset($_GET['recurring']) && $_GET['recurring'] == 1) {
        $data = $data->where('i.recurring', 1);
    }
    if (!empty($_GET['start_date'])) {
        $where[] = [DB::raw("DATE_FORMAT(i.date,'%Y-%m-%d')"), '>=', $_GET['start_date']];
        $data = $data->where($where);
    }
    if (!empty($_GET['end_date'])) {
        $where[] = [DB::raw("DATE_FORMAT(i.date,'%Y-%m-%d')"), '<=', $_GET['end_date']];
        $data = $data->where($where);
    }

    $data = $data->where('i.type', $type);
    $data = $data->orderBy('i.id', 'desc');

    if (
        $total == 1
    ) {
        return $data->count();
    } else {
        return $data->skip($offset)->take($limit)->get();
    }
}

// get invoices
function get_bills_by_type($total, $limit, $offset, $status, $type)
{
    if (user()->role == "sub_user")
        $user_id = user()->parent_id;
    else
        $user_id = session('id');

    $data = DB::table('invoice', 'i');
    $data = $data->select('i.*', 'tax.rate');


    /*  */
    $data = $data->join('invoice_taxes', 'i.id', '=', 'invoice_id', 'left');
    $data = $data->join('tax', 'tax.id', '=', 'tax_id', 'left');

    $data = $data->where('i.business_id', helper_get_business()->uid);
    $data = $data->where('i.user_id', $user_id);

    if (isset($_GET['customer']) && $_GET['customer'] != '') {
        $data = $data->where('i.customer', $_GET['customer']);
    }
    if (isset($_GET['status']) && $_GET['status'] != "" && $_GET['status'] != 3) {
        $data = $data->where('i.status', $_GET['status']);
    }
    if (isset($_GET['status']) && $_GET['status'] != "" && $_GET['status'] == 3) {
        $data = $data->where('i.is_sent', 1);
    }

    if (!empty($_GET['start_date'])) {
        $where[] = [DB::raw("DATE_FORMAT(i.date,'%Y-%m-%d')"), '>=', $_GET['start_date']];
        $data = $data->where($where);
    }
    if (!empty($_GET['end_date'])) {
        $where[] = [DB::raw("DATE_FORMAT(i.date,'%Y-%m-%d')"), '<=', $_GET['end_date']];
        $data = $data->where($where);
    }

    $data = $data->where('i.type', $type);
    $data = $data->orderBy('i.id', 'desc');

    if (
        $total == 1
    ) {
        return $data->count();
    } else {
        return $data->skip($offset)->take($limit)->get();
    }
}


function helper_count_invoices($status = 0, $type = 0)
{
    return get_invoices_by_type(1, 0, 0, $status, $type);
}

function count_recurring_invoices()
{
    // 
    if (user()->role == "sub_user")
        $user_id = user()->parent_id;
    else
        $user_id = session('id');

    $where = [];
    $where[] = ['business_id', helper_get_business()->uid];
    $where[] = ['user_id', $user_id];
    $where[] = ['recurring', 1];

    return InvoiceModel::where($where)->count();
}

function get_invoice_taxes($id, $return = false)
{
    $taxes = Invoice_taxesModel::where('invoice_id', $id)->get();
    // Sahhel
    if ($return)
        return $taxes;
    if (!empty($taxes)) {
        $taxe = [];
        foreach ($taxes as $tax) {
            $taxe[] =  $tax->tax_id;
        }
        //echo "<pre>"; print_r($taxe); exit();
        return $taxe;
    } else {
        return FALSE;
    }
}

function get_invoice_total_taxes($id)
{
    return DB::table('invoice_taxes', 't')
        ->select('t.*', DB::raw('SUM(p.rate) as total'))
        ->join('tax as p', 'p.id', '=', 't.tax_id', 'left')
        ->where('t.invoice_id', $id)
        ->first();
}

function delete_items_by_inv_id($id, $table)
{
    return DB::table($table)->where('invoice_id', $id)->delete();
}


function get_invoice_items_to_create($id, $use_md5 = FALSE)
{
    $query = DB::table('invoice_items', 'i')
        ->join('products as p', 'p.id', '=', 'i.item', 'left')
        ->select('i.*', 'p.name as item_name', 'p.details as details');
    if ($use_md5 == TRUE) {
        $query = $query->where(DB::raw('md5(i.invoice_id)'), $id);
    } else {
        $query = $query->where('i.invoice_id', $id);
    }
    $query = $query->where('i.is_used', 0)->get();
    $i = 0;
    $end_data = array();
    foreach ($query as $value) {
        $end_data[$i] = $value;
        // TODO: fix
        // $tax_query = TaxModel::find($value->tax);
        // $total = $value->qty * $value->price;
        // if ($value->discount_item_type == 1) {
        //     $discount = ($total / 100) * $value->discount;
        // } else {
        //     $discount = $value->qty * $value->discount;
        // }
        // $end_data[$i]->discount_end_val = $discount;
        // $total_after_discount = $total - $discount;
        // $end_data[$i]->tax_end_val = ($total_after_discount / 100) * $tax_query->rate;
        // $i++;
    }
    return $end_data;
}

// get_customer_info
function get_readonly_invoice($id)
{
    return DB::table('invoice', 'i')
        ->select(
            'i.*',
            'b.color',
            'b.name as business_name',
            'b.address as business_address',
            'b.template_style',
            'b.logo',
            'b.biz_number',
            'b.vat_code',
            't.currency_symbol',
            't.name as country'
        )
        ->join('business as b', 'b.uid', '=', 'i.business_id', 'left')
        ->join('country as t', 't.id', '=', 'b.country', 'left')
        ->where(DB::raw('md5(i.id)'), $id)
        ->first();
}

// count invoices
function count_invoices($type)
{
    return DB::table('invoice', 'i')
        ->join('users', 'users.id', '=', 'user_id')
        ->where('business_id', helper_get_business()->uid)
        ->where('type', $type)
        ->groupBy('i.id')
        ->count();
}

//get product
function helper_search_product($value, $type)
{
    $query = DB::table('products');
    if ($value != 'empty') {
        $query = $query->like('name', $value);
    }

    if ($type == 'buy') {
        $query = $query->where('is_buy', 1);
    } else {
        $query = $query->where('is_sell', 1);
    }

    $query = $query->where('business_id', helper_get_business()->uid);
    return $query->get();
}
