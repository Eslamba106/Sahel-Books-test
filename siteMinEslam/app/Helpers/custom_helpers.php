<?php
//  Get Location Info

use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

function getLocationInfoByIp()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = @$_SERVER['REMOTE_ADDR'];
    $result  = array('country' => '', 'city' => '');
    if (filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    } else {
        $ip = $remote;
    }
    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
    if ($ip_data && $ip_data->geoplugin_countryName != null) {
        $result['country'] = $ip_data->geoplugin_countryCode;
        $result['city'] = $ip_data->geoplugin_city;
    }
    return $result;
}

function my_date_show($date)
{

    if ($date != '') {
        $date2 = date_create($date);
        $date_new = date_format($date2, "Y-m-d");
        return $date_new;
    } else {
        return '';
    }
}
function my_date_show_time($date)
{
    if ($date != '') {
        $date2 = date_create($date);
        $date_new = date_format($date2, "Y-m-d h:i A");
        return $date_new;
    } else {
        return '';
    }
}

function helper_convert_payment_between($amount = '', $from_currency = '', $to_currency = '')
{
    $amount = (float) $amount;
    $from_currency_rate = get_rate($from_currency);

    if (empty($from_currency) || empty($to_currency)) {
        return $amount;
    } else {
        if ($from_currency_rate != 0) {
            $result = ($amount / $from_currency_rate) * get_rate($to_currency);
        } else { // TODO: Laravel Erorr: Division by zero
            $result = ($amount / 1) * get_rate($to_currency);
        }

        $convert_total = str_replace(",", "", $result);
        return $convert_total;
    }
}

function helper_convert_payment($amount = '', $from_currency = '')
{
    return helper_convert_payment_between($amount, $from_currency, helper_get_business()->currency_code);
}


function get_discount($total, $discount_amount)
{
    $total = $total - ($total * ($discount_amount / 100));
    return round($total);
}

function get_payment_methods()
{
    $days = array(
        '1' => 'Bank payment',
        '2' => 'Cash',
        '3' => 'Cheque',
        '4' => 'Credit card',
        '5' => 'Paypal',
        '6' => 'Others'
    );
    return $days;
}


function helper_pagination($pagination)
{
    return view('layouts/pagination', ['pagination' => $pagination])->render();
}

function helper_create_pagination($config)
{

    return [
        'currentPage' => $config['page'] < 1 ? 1 : $config['page'] + 1,
        'totalPages' => (int) ceil($config['total_rows'] / $config['per_page'])
    ];
}


function date_count($date, $day)
{
    $result = date('Y-m-d', strtotime($date . ' +' . $day . ' days'));
    if (empty($date) || empty($day)) {
        return FALSE;
    } else {
        return $result;
    }
}
function date_dif($date1, $date2)
{
    $date1 = date_create($date1);
    $date2 = date_create($date2);
    //difference between two dates
    $diff = date_diff($date1, $date2);
    //count days
    return $diff->format("%R%a") - 1;
}

// show current date with custom format
function show_year($date)
{

    if ($date != '') {
        $date2 = date_create($date);
        $date_new = date_format($date2, "Y");
        return $date_new;
    } else {
        return '';
    }
}


// format numbers 
function money_formats($num)
{
    $explrestunits = "";
    if (strlen($num) > 3) {
        $lastthree = substr($num, strlen($num) - 3, strlen($num));
        $restunits = substr($num, 0, strlen($num) - 3); // extracts the last three digits
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        $expunit = str_split($restunits, 2);
        for ($i = 0; $i < sizeof($expunit); $i++) {
            // creates each of the 2's group and adds a comma to the end
            if ($i == 0) {
                $explrestunits .= (int)$expunit[$i] . ","; // if is first value , convert into integer
            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash; // final format
}


//get invoice types
if (!function_exists('helper_get_invoice_types')) {
    function helper_get_invoice_types()
    {
        $types = array(
            '1' => helper_trans('invoice'),
            '2' => helper_trans('estimate'),
            '3' => helper_trans('bill'),
            '6' => helper_trans('expense')
        );
        return $types;
    }
}

function month_show($date)
{

    if ($date != '') {
        $date2 = date_create($date);
        $date_new = date_format($date2, "M Y");
        return $date_new;
    } else {
        return '';
    }
}

function get_business_position($id = '')
{
    $positions = array(
        '1' => helper_trans('business-partner'),
        '2' => helper_trans('accountant'),
        '3' => helper_trans('‏family-member'),
        '4' => helper_trans('salesperson'),
        '5' => helper_trans('‏assistant'),
        '6' => helper_trans('block-advisor-tax-pro'),
        '8' => helper_trans('cashier'),
        '7' => helper_trans('other'),
        // start from 9
    );
    if (is_numeric($id)) {
        return $positions[$id];
    } else {
        return $positions;
    }
}

function get_role_name($value)
{
    if ($value == 'subadmin') {
        return 'Admin';
    } else {
        return ucfirst($value);
    }
}

function convert_currency($amount, $from, $to)
{
    //$string = $from . "_" . $to;
    $string = "conversion_rate";
    //$url = 'http://free.currencyconverterapi.com/api/v5/convert?q=' . $from .'_' . $to . '&compact=ultra&apiKey=fd3e4ac06ed79c18aab8';
    $url = 'https://v6.exchangerate-api.com/v6/54a8bcf32b122caa0cf00142/pair/' . $from . '/' . $to;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17');
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    $response = curl_exec($ch);
    $response = json_decode($response, true);
    curl_close($ch);
    $rate = $response[$string];
    if ($amount == "")
        return $rate;
    else
        return $rate * $amount;
}

function helper_get_product_quantity_msg($is_update = false)
{
    $msg = '';
    $msg_add = 'اضافة كمية من: ';
    $msg_edit = 'تعديل كمية من: ';
    $req = Request();
    $segments = $req->segments();


    // $segments = [
    //     'admin',
    //     'page203',
    //     'bills',
    //     'add',
    //     '2051'
    // ];

    $msgs = [
        [
            'page' => 'bills',
            'method' => 'add',
            'msg' => helper_trans('bills'),
        ],
        [
            'page' => 'credits',
            'method' => 'add',
            'msg' => helper_trans('credit-notes'),
        ],
        [
            'page' => 'debit',
            'method' => 'add',
            'msg' => helper_trans('debit-notes'),
        ],
        [
            'page' => 'invoice',
            'method' => 'add',
            'msg' => helper_trans('invoices'),
        ],
        // [
        //     'page' => 'credits',
        //     'method' => 'add',
        //     'msg' => helper_trans('credit-note'),
        // ],
        // [
        //     'page' => 'credits',
        //     'method' => 'add',
        //     'msg' => helper_trans('credit-note'),
        // ]
    ];
    $add_msg = '';
    foreach ($msgs as $m) {
        if (in_array($m['page'], $segments) && in_array($m['method'], $segments)) {
            $add_msg = $m['msg'];
            if ($is_update) {
                $msg = $msg_edit . $m['msg'];
            } else {
                $msg = $msg_add . $m['msg'];
            }
        }
    }
    if (empty($add_msg)) {
        $msg = 'error';
    }
    return $msg;
}
