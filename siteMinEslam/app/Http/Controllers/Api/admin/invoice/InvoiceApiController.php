<?php

namespace App\Http\Controllers\Api\admin\invoice;

use App\Models\InvoiceModel;
use Illuminate\Http\Request;
use App\Models\ProductsModel;
use Illuminate\Support\Facades\DB;
use App\Exports\InvoiceModelExport;
use App\Models\Invoice_Items_Taxes;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Services\Invoices\InvoiceServices;
use Predis\Command\Traits\BloomFilters\Items;
use App\Http\Controllers\Api\Base\BaseController;

class InvoiceApiController extends Controller
{

    public $invoice_service;

    public function __construct(InvoiceServices $invoice_service)
    {
        $this->invoice_service = $invoice_service;
    }


    public function type(Request $request)
    {
        // return $request->busness_id ;
        // $data = array();
        // $data['invoices'] = InvoiceModel::where('business_id', $business_id)->where('status' , $status)->get();
        // // dd($data['invoices']);
        // $data['customers'] = DB::table('customers')
        // ->where('business_id', $business_id)
        // ->where('user_id', $data['invoices'][0]['user_id'])
        // ->orderBy('id', 'desc')
        // ->get();
        $data = $this->invoice_service->get_invoices($request->busness_id , $request->status) ;
        // dd($data['invoices']->count());
        $array = [
            'data'    => $data,
            'massege' => 'ok',
            'status'  => 200
        ];
        $arrayerror = [
            'data'    => 'not found',
            'massege' => 'fail',
            'status'  => 401
        ];
        if($data['invoices']){
            return response($array);
        }
        // elseif($data['invoices']->count() > 0){
        //     return response($array);
        // }

    }

    public function create()
    {
        $data = array();
        $data['products'] = ProductsModel::get() ;
        // if ($type == 0) {
        //     $data['page_title'] = 'Create Invoice';
        // } else {
        //     $data['page_title'] = 'Create Recurring Invoice';
        // }

        // $data['page'] = 'Invoice';
        // $data['main_page'] = 'Sales';
        // $data['type'] = $type;
        $data['invoice'] = [[
            'id' => '',
            'title' => '',
            'type' => '',
            'recurring' => '',
            'parent_id' => '',
            'summary' => '',
            'number' => '',
            'poso_number' => '',
            'customer' => '',
            'date' => '',
            'discount' => '',
            'payment_due' => '',
            'expire_on' => '',
            'due_limit' => '',
            'footer_note' => '',
            'sub_total' => '',
            'grand_total' => '',
            'convert_total' => '',
            'status' => '',
            'reject_reason' => '',
            'client_action_date' => '',
            'is_sent' => '',
            'is_completed' => '',
            'sent_date' => '',
            'recurring_start' => '',
            'recurring_end' => '',
            'frequency' => '',
            'next_payment' => '',
            'frequency_count' => '',
            'auto_send' => '',
            'send_myself' => '',
            'created_at' => '',
            'total_taxes' => '',
            'modified_at' => '',
            'send_status' => '',
            'send_id' => '',
            'paymentToken' => '',
            'puid' => '',
            'total_tax_items' => '',
            'to_invoice_id' => '',
            'credits_used' => '',
            'remaining_amount' => '',
            'to_invoice_number' => '',
            'invoice_type' => '',
            'model_id' => '',
            'model_number' => '',
            'total_discounts' => '',
            'convert_sub_total' => '',
            'shipping_cost' => '',
            'shipping_tax' => '',
            'shipping_tax_percent' => '',
            'inv_tax' => '',
            'inv_discount' => '',
            'tax_type' => '',
            'added_by' => '',
            'shipping_company' => '',
            'api_payment_method_name' => '',
            'api_status_name' => '',
            'api_status_code' => '',
            'api_payment_method' => '',
            'change_return' => ''
        ]];
        $data['products'] = get_by_user_and_type('products', 'is_sell');
        // $data['tax'] = 'eslam';
        // $data['total_tax'] = get_invoice_total_taxes(0);
        // $data['asign_taxs'] = get_invoice_taxes(0);
        // $data['gsts'] = get_user_taxes_by_gst();
        // $data['taxes'] = get_by_user('tax');
        // $data['customers'] = get_by_user('customers');
        // $data['total'] = get_total_by_user('invoice', 1);
        // $data['countries'] = select_asc('country');
        dd($data);
        $data['main_content'] = view('admin/user/invoice_create', $data)->render();
        return view('admin/index', $data);
    }

    public function edit($id, $type = 0)
    {
        $data = array();
        $data['page_title'] = 'Edit Invoice';
        $data['page_sub'] = 'Edit';
        $data['page'] = 'Invoice';
        $data['invoice'] = get_by_md5_data($id, 'invoice');
        $data['type'] = $data['invoice'][0]['recurring'];
        $data['products'] = get_by_user_and_type('products', 'is_sell');
        $data['total_tax'] = get_invoice_total_taxes($data['invoice'][0]['id']);
        $data['asign_taxs'] = get_invoice_taxes($data['invoice'][0]['id']);
        $data['gsts'] = get_user_taxes_by_gst();
        $data['taxes'] = get_by_user('tax');
        $data['countries'] = select_asc('country');
        $data['total'] = get_total_by_user('invoice', 1);
        $data['customers'] = get_by_user('customers');
        $data['main_content'] = view('admin/user/invoice_create', $data)->render();
        return view('admin/index', $data);
    }

    public function add_product($product_id, $customer_id)
    {
        $product = get_by_id($product_id, 'products');
        $customer = get_customer_info($customer_id);
        
        $data = array();
        $data['taxes'] = get_by_user('tax');
        $data['taxes_type'] = get_by_user('tax_type');
        $data['product'] = $product;

        if (!empty($product)) {
            if (empty($customer)) {
                $currency = '';
            } else {

                if (empty($customer->currency_symbol)) {
                    $currency = '';
                } else {
                    $currency = $customer->currency_symbol;
                }
            }
            $loaded = view('admin/user/include/product_list', $data)->render();
            echo json_encode(array('st' => 1, 'loaded' => $loaded, 'currency' => $currency));
        } else {
            echo "Data not found";
        }
    }


    public function search_product($value, $type = '')
    {
        $products = helper_search_product($value, $type);
        $data = array();
        // $data['taxes'] = get_taxes();
        $data['products'] = $products;
        if (!empty($products)) {

            $loaded = view('admin/user/include/invoice_product_list', $data)->render();
            echo json_encode(array('st' => 1, 'loaded' => $loaded));
        } else {
            echo json_encode(array('st' => 0));
        }
    }
    
    public function convert_currency($amount = '', $from_currency = '')
    {
        $result = ($amount / get_rate($from_currency)) * get_rate(helper_get_business()->currency_code);
        $rate = (1 / get_rate($from_currency)) * get_rate(helper_get_business()->currency_code);

        if ($from_currency == helper_get_business()->currency_code) {
            $conversion = '';
            $convert_total = str_replace(",", "", $result);
        } else {
            $conversion = helper_trans('currency-conversion') . ': ' . helper_get_business()->currency_symbol . ' ' . number_format($result, 2) . ' (' . helper_get_business()->currency_code . ') at ' . number_format($rate, 4);
            $convert_total = str_replace(",", "", $result);
        }
        echo $convert_total;
        exit();
        echo json_encode(array('st' => 1, 'result' => $conversion, 'convert_total' => $convert_total));
    }



    public function convert_recurring($id)
    {
        $invoice = get_by_md5_id($id, 'invoice');
        $data = array(
            'recurring' => 1
        );
        helper_update_by_id($data, $invoice->id, 'invoice');
        echo json_encode(array('st' => 1));
    }


    public function get_due_date($value)
    {
        if ($value == 1) {
            $value = 0;
        } else {
            $value = $value;
        }
        $result = date('Y-m-d', strtotime('+' . $value . ' days'));
        echo json_encode(array('st' => 1, 'result' => $result));
    }


    //send email
    public function send($invoice_id, Request $request)
    {

        $customer_id = $request->input('customer_id');
        $is_myself = $request->input('is_myself');
        $invoice = get_invoice_details(md5($invoice_id));
        $customer = get_customer_info($customer_id);

        $data = array();
        if (isset($is_myself)) {
            $data['email_myself'] = $request->input('email_myself');
        } else {
            $data['email_myself'] = '';
        }

        if ($invoice->type == 1) {
            $type = 'Invoice';
        } else {
            $type = 'Estimate';
        };
        $data['email_to'] = $request->input('email_to');
        $data['message'] = $request->input('message');
        $data['subject'] = $request->input('subject');
        $data['logo'] = url($invoice->logo ?? '');
        $data['currency_code'] = $customer->currency;
        $data['currency_symbol'] = $customer->currency_symbol;
        $data['invoice'] = $invoice;
        $data['type'] = $type;
        $data['html_content'] = view('email_template/invoice', $data)->render();
        $send_data = helper_send_email($data['email_to'], $data['subject'], $data['html_content'], $data['email_myself']);

        if ($send_data == true) {
            $sent_data = array(
                'is_sent' => 1,
                'sent_date' => my_date_now()
            );
            helper_update_by_id($sent_data, $invoice_id, 'invoice');

            $data = array();
            $data['status'] = 1;
            echo json_encode($data);
        } else {
            $data = array();
            $data['status'] = 2;
            echo json_encode($data);
        }
    }


    public function save()
    {
        $data = array();
        $data['page_title'] = 'Invoice';
        $data['page'] = 'Invoice';
        $data['products'] = get_by_user('invoice');
        $data['main_content'] = view('admin/user/invoice_save', $data)->render();
        return view('admin/index', $data);
    }

    public function details($id)
    {

        $data = array();
        $data['invoice'] = get_invoice_details($id);
        $data['payment'] = check_invoice_payment_records($data['invoice']->id, $data['invoice']->parent_id);
        $data['page_title'] = 'Invoice details';
        $data['invoice_items_data'] =  DB::table('invoice_items as ii')
        ->where('ii.invoice_id', $data['invoice']->id)
        ->join('products as p' , 'p.id' , '=' ,'ii.item')
        ->join('invoice_items_taxes as iit' , 'iit.invoice_item_id' , '=' ,'p.id')
        ->select('ii.*' , 'p.name as name' , 'p.details as details' , 'iit.tax_rate as tax_rate' , 'iit.tax_value as tax_value')
        ->orderBy('id', 'desc')
        ->get();
        $data['page'] = 'Invoice';
        $data['main_content'] = view('admin/user/invoice_save', $data)->render();
        return view('admin/index', $data);
    }
    public function add(Request $request)
    {
        $data = array();
        $id = $request->input('id');

        if (settings()->site_info == 1) {
            $total = count_invoices(1);
            if ($total > 20000) {
                $data['status'] = 4;
                echo json_encode($data);
                exit();
            }
        }

        $validator =  Validator::make(
            $request->all(),
            [
                'customer' => 'required|max:100'
            ],
            [
                'customer.required' => helper_trans('add-customer-error-msg')
            ]
        );

        if ($validator->fails()) {
            $data['status'] = 3;
            $data['error'] = implode("<br>", $validator->errors()->all());
            echo json_encode($data);
        } else {

            if (empty($request->input('tax'))) {
                $tax = 0;
            } else {
                $tax = get_by_id($request->input('tax'), 'tax');
                $tax = $tax->rate;
            }

            if (!empty($request->input('discount'))) {
                $discount = $request->input('discount');
            } else {
                $discount = 0;
            }

            $invoice = array(
                'user_id' => user()->id,
                'business_id' => helper_get_business()->uid,
                'title' => $request->input('title'),
                'summary' => $request->input('summary'),
                'customer' => $request->input('customer'),
                'number' => $request->input('number'),
                'poso_number' => $request->input('poso_number'),
                'recurring' => $request->input('is_recurring'),
                'date' => $request->input('date'),
                'discount' => $discount,
                'payment_due' => $request->input('payment_due'),
                'due_limit' => $request->input('due_limit'),
                'footer_note' => $request->input('footer_note'),
                'sub_total' => $request->input('sub_total'),
                'grand_total' => $request->input('grand_total'),
                'convert_total' => $request->input('convert_total')
            );


            if (!empty($id)) {
                delete_items_by_inv_id($id, 'invoice_taxes');
                delete_items_by_inv_id($id, 'invoice_items');
                delete_items_by_inv_id($id, 'invoice_items_taxes');

                helper_update_by_id($invoice, $id, 'invoice');

                // update recurring data
                if ($request->input('is_recurring') == 1) {
                    $recurring_data = array(
                        'recurring_start' => $request->input('recurring_start'),
                        'recurring_end' => $request->input('recurring_end'),
                        'frequency' => $request->input('frequency')
                    );
                    helper_update_by_id($recurring_data, $id, 'invoice');
                }
            } else {
                // if (check_package_limit('invoice') != -2) {
                //     $total = get_total_value('invoice', 1);
                //     if ($total >= check_package_limit('invoice')) :
                //         $msg = helper_trans('reached-limit') . ', ' . helper_trans('package-limit-msg');
                //         $data['status'] = 5;
                //         $data['error'] = $msg;
                //         echo json_encode($data);
                //         exit();m7mdali01@gmail.com
                //     endif;
                // }
                $id = helper_insert($invoice, 'invoice');
            }

            $taxes = $request->input('taxes');
            if (!empty($taxes)) {
                foreach ($taxes as $tax) {
                    if ($tax != 0) {
                        $tax_data = array(
                            'invoice_id' => $id,
                            'tax_id' => $tax,
                        );
                        helper_insert($tax_data, 'invoice_taxes');
                    }
                }
            }


            $product_id = $request->input('product_ids');
            $items = $request->input('items');
            $details = $request->input('details');
            $price = $request->input('price');
            $quantity = $request->input('quantity');
            $tax_rate = $request->input('taxes');
            $total_taxes = $request->input('total_taxes');
            $discount_item = $request->input('Discount');
            $discount = $request->input('discount');
            $total_price = $request->input('total_price');
            // if($request->has('form_flag')){

            // }
            if (!empty($items)) {
                $taxes_total_data = get_by_user('tax')->where('rate' , $total_taxes)->first();
                if($taxes_total_data){
                    $item_data = array(
                        'invoice_id' => $id,
                        'tax_id' => $taxes_total_data->id ?? null,
                    );
                    helper_insert($item_data, 'invoice_taxes');
                }
            }
            if (!empty($items) && $tax_rate != 0) {
                for($j = 0 ; $j< count($items) ; $j++){
                    if($tax_rate[$j] != 0){
                        
                        $taxes_item_data = get_by_user('tax')->where('rate' , $tax_rate[$j])->first();
                        // dd($taxes_item_data->id);
                        $invoice_item_taxes_data = array(
                            'tax_id' => $taxes_item_data->id ,
                            'invoice_id' => $id,
                            'invoice_item_id' => $items[$j],
                            'tax_rate' => $tax_rate[$j]*100,
                            'tax_type' => $taxes_item_data->type,
                            'tax_value' => $tax_rate[$j] * $price[$j],
                        );
                        helper_insert($invoice_item_taxes_data , 'invoice_items_taxes');
                    }
                };
                
            }
            if (!empty($items)) {
                // dd($items);
                for ($i = 0; $i < count($items); $i++) {
                    $item_data = array(
                        'invoice_id' => $id,
                        'item' => $items[$i],
                        'price' => $price[$i],
                        'qty' => $quantity[$i],
                        'discount' => $discount_item[$i] ?? null,
                        'total' => $total_price[$i]
                    );
                    helper_insert($item_data, 'invoice_items');

                    if (!empty($details[$i])) {
                        $this->update_product($items[$i], $details[$i]);
                    }

                    if (helper_get_business()->enable_stock == 1 && !empty($product_id[$i]) && !empty($quantity[$i])) :
                        $this->update_quantity($product_id[$i], $quantity[$i]);
                    endif;
                }
            }

            $data['status'] = 2;
            $data['invoice_id'] = md5($id);
            echo json_encode($data);
        }
    }


    // update product
    public function update_product($product_id, $details)
    {
        $data = array(
            'details' => $details
        );
        helper_update_by_id($data, $product_id, 'products');
    }

    // update quantity
    public function update_quantity($product_id, $quantity)
    {
        $product = get_by_id($product_id, 'products');
        if (!empty($product) && $product->quantity != 0) {
            $data = array(
                'quantity' => $product->quantity - $quantity
            );
            helper_update_by_id($data, $product->id, 'products');
        }
    }


    // load tax
    public function load_tax($id)
    {
        $tax = get_by_id($id, 'tax');
        if (empty($tax)) {
            echo 0;
        } else {
            echo $tax->rate;
        }
    }


    public function preview(Request $request)
    {
        $data = array();

        if (empty($request->input('taxes'))) {
            $taxes = 0;
        } else {
            $taxes = $request->input('taxes');
        }

        $invoice = array(
            'title' => $request->input('title'),
            'summary' => $request->input('summary'),
            'customer' => $request->input('customer'),
            'number' => $request->input('number'),
            'poso_number' => $request->input('poso_number'),
            'date' => $request->input('date'),
            'taxes' => $taxes,
            'discount' => $request->input('discount'),
            'payment_due' => $request->input('payment_due'),
            'due_limit' => $request->input('due_limit'),
            'footer_note' => $request->input('footer_note'),
            'sub_total' => $request->input('sub_total'),
            'grand_total' => $request->input('grand_total'),
            'convert_total' => $request->input('convert_total')
        );
        session($invoice);


        $items = $request->input('items');
        $details = $request->input('details');
        if (!empty($items)) {
            for ($i = 0; $i < count($items); $i++) {
                if (!empty($details[$i])) {
                    $this->update_product($items[$i], $details[$i]);
                }
            }
        }


        $products = array(
            'name' => $request->input('items'),
            'price' => $request->input('price'),
            'details' => $request->input('details'),
            'quantity' => $request->input('quantity'),
            'discount_product' => $request->input('Discount'),
            'tax_rate' => $request->input('taxes'),
            'total_price' => $request->input('total_price'),
            'product_ids' => $request->input('product_ids')
        );
        session($products);

        $data['page_title'] = 'Invoice Preview';
        $data['page'] = 'Invoice';
        $load_data = view('admin/user/invoice_preview', $data)->render();
        $data['status'] = 1;
        $data['load_data'] = $load_data;
        echo json_encode($data);
    }



    public function set_recurring($id, Request $request)
    {
        if ($_POST) {
            $invoice = get_by_id($id, 'invoice');

            if ($request->input('recurring_start') < date('Y-m-d')) {
                echo json_encode(array('st' => 2));
                exit();
            }

            $data = array(
                'recurring_start' => $request->input('recurring_start'),
                'recurring_end' => $request->input('recurring_end'),
                'frequency' => $request->input('frequency'),
                'next_payment' => date_count($request->input('recurring_start'), $request->input('frequency')),
                'auto_send' => 0,
                'send_myself' => 0,
                'status' => 1
            );

            helper_update_by_id($data, $id, 'invoice');
            echo json_encode(array('st' => 1));
        }
    }


    public function stop_recurring($id)
    {
        $data = array(
            'is_completed' => 1
        );

        helper_update_by_id($data, $id, 'invoice');
        echo json_encode(array('st' => 1));
    }


    public function ajax_add_product($type = '')

    {
        $request = Request();
        if ($_POST) {

            if ($type == 'buy') {
                $is_buy = 1;
                $is_sell = 0;
                $product_type = 'is_buy';
            } else {
                $is_buy = 0;
                $is_sell = 1;
                $product_type = 'is_sell';
            }

            $data = array(
                'user_id' => user()->id,
                'business_id' => helper_get_business()->uid,
                'name' => $request->input('name'),
                'price' => $request->input('price'),
                'quantity' => $request->input('quantity'),
                'is_sell' => $is_sell,
                'income_category' => 0,
                'is_buy' => $is_buy,
                'expense_category' => 0,
                'details' => $request->input('details')
            );

            $id = helper_insert($data, 'products');
            $data['products'] = get_by_user_and_type('products', $product_type);
            $load_product = view('admin/user/include/invoice_product_list', $data)->render();
            echo json_encode(array('st' => 1, 'load_product' => $load_product));
        }
    }


    public function ajax_add_customer(Request $request)
    {
        if ($_POST) {
            $data = array();
            $data = array(
                'user_id' => user()->id,
                'business_id' => helper_get_business()->uid,
                'name' => $request->input('name'),
                'country' => $request->input('country'),
                'currency' => $request->input('currency'),
                'status' => 1
            );

            helper_insert($data, 'customers');
            $data['invoice'] = FALSE;
            $data['customers'] = get_by_user('customers');
            $load_customers = view('admin/user/include/invoice_load_customers', $data)->render();
            echo json_encode(array('st' => 1, 'load_customers' => $load_customers));
        }
    }


    public function ajax_add_vendor(Request $request)
    {
        $data = array();
        if ($_POST) {
            $data = array(
                'user_id' => user()->id,
                'business_id' => helper_get_business()->uid,
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'address' => $request->input('address')
            );

            $id = helper_insert($data, 'vendors');
            $data['invoice'] = FALSE;
            $data['customers'] = get_by_user('vendors');
            $load_customers = view('admin/user/include/load_vendors', $data)->render();
            echo json_encode(array('st' => 1, 'load_customers' => $load_customers));
        }
    }


    public function convert_payment($amount = '', $from_currency = '')
    {
        if (empty($from_currency)) {
            return $amount;
        } else {
            $result = ($amount / get_rate($from_currency)) * get_rate(helper_get_business()->currency_code);
            $rate = (1 / get_rate($from_currency)) * get_rate(helper_get_business()->currency_code);
            $convert_total = str_replace(",", "", $result);
            return $convert_total;
        }
    }


    //record invoice payment 
    public function record_payment(Request $request)
    {
        if ($_POST) {
            $id = $request->input('invoice_id');
            $invoice = get_by_md5_id($id, 'invoice');
            $customer = get_customer_info($invoice->customer, 'customers');
            $customer_record = get_customer_advanced_record($invoice->customer);

            if (!empty($request->input('is_autoload_amount'))) {
                $auto_load = $request->input('is_autoload_amount');
            } else {
                $auto_load = 0;
            }

            $amount_data = array(
                'is_autoload_amount' => $auto_load
            );

            helper_update_by_id($amount_data, helper_get_business()->id, 'business');
            $business = get_by_id(helper_get_business()->id, 'business');


            if (!empty($customer_record) && $customer_record->customer_id == $invoice->customer) {
                if ($business->is_autoload_amount == 1) {
                    $amount = $request->input('amount');
                    $amount = $customer_record->amount + $amount;
                } else {
                    $amount = $request->input('amount');
                }
            } else {
                $amount = $request->input('amount');
            }

            if ($invoice->parent_id == 0) {
                $invoice_id = $invoice->id;
            } else {
                $invoice_id = $invoice->parent_id;
            }


            if (!empty($request->input('payment_method'))) {
                $payment_method = $request->input('payment_method');
            } else {
                $payment_method = '0';
            }

            $data = array(
                'payment_date' => $request->input('payment_date'),
                'amount' => $amount,
                'convert_amount' => !empty($customer) ? $this->convert_payment($amount, $customer->currency) : '',
                'customer_id' => $invoice->customer,
                'invoice_id' => $invoice_id,
                'business_id' => helper_get_business()->uid,
                'payment_method' => $payment_method,
                'note' => $request->input('note'),
                'type' => 'income'
            );

            $id = helper_insert($data, 'payment_records');

            $total_payment = get_total_invoice_payments($invoice->id, $invoice->parent_id);


            if ($amount == $invoice->grand_total) {
                $status = 2;

                if ($business->is_autoload_amount == 1) {
                    $adv_data = array(
                        'amount' => abs($invoice->grand_total - $amount)
                    );
                    if (!empty($customer_record) && $customer_record->customer_id == $invoice->customer) {
                        helper_update_by_id($adv_data, $customer_record->id, 'payment_advance');
                    }
                }
            } else if ($amount > $invoice->grand_total) {
                $status = 2;

                if ($business->is_autoload_amount == 1) {
                    $adv_data = array(
                        'amount' => abs($invoice->grand_total - $amount),
                        'customer_id' => $invoice->customer,
                        'business_id' => helper_get_business()->uid,
                        'created_at' => my_date_now()
                    );

                    if (empty($customer_record)) {
                        helper_insert($adv_data, 'payment_advance');
                    } else {
                        helper_update_by_id($adv_data, $customer_record->id, 'payment_advance');
                    }
                }
            } else if ($amount < $invoice->grand_total) {

                if ($total_payment == $invoice->grand_total) {
                    $status = 2;
                } else {
                    $status = 1;
                }

                if ($business->is_autoload_amount == 1) {
                    $adv_data = array(
                        'amount' => abs($invoice->grand_total - $amount)
                    );
                    if (!empty($customer_record) && $customer_record->customer_id == $invoice->customer) {
                        helper_update_by_id($adv_data, $customer_record->id, 'payment_advance');
                    }
                }
            }

            if (empty($request->input('due_date'))) {
                $due_date = date('Y-m-d');
            } else {
                $due_date = $request->input('due_date');
            }

            if ($invoice->recurring == 1 && $status == 2) {
                $is_completed = 1;
            } else {
                $is_completed = 0;
            }

            $invoice_data = array(
                'status' => $status,
                'payment_due' => $due_date,
                'is_completed' => $is_completed
            );
            helper_update_by_id($invoice_data, $invoice->id, 'invoice');

            return redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function approve_invoice($id)
    {
        $invoice = get_by_md5_id($id, 'invoice');
        $data = array(
            'status' => 1
        );

        helper_update_by_id($data, $invoice->id, 'invoice');
        echo json_encode(array('st' => 1));
    }


    public function delete($id)
    {
        delete_id($id, 'invoice');
        delete_items_by_inv_id($id, 'payment_records');
        echo json_encode(array('st' => 1));
    }
    public function export() 
    {
        return Excel::download(new InvoiceModelExport, 'invoices.xlsx');
    }
    public function import_page() 
    {
        $data['page'] = 'Invoice';
        $data['main_page'] = 'Sales';
        $data['page_title'] = "import_excel_sheet";
        $data['main_content'] = view('admin/user/include/import_invoice', $data)->render();
        return view('admin.user.include.import_excel_invoice' , $data);

    }
    public function import() 
    {

    }
}
