<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BillsController extends Controller
{

    public function __construct()
    {

        //check auth
        if (!helper_is_user()) {
            return redirect(url(''));
        }
    }


    public function index()
    {

        $data = array();
        $config['base_url'] = url('admin/bill/index');
        $total_row = get_bills_by_type(1, 0, 0, 1, $bills = 3);

        // start pagination
        $config['total_rows'] = $total_row;
        $config['per_page'] = 12;

        $page = 0;
        if (!isset($_GET['page'])) {
            $page = 0;
        } else {
            $page = $_GET['page'];
        }
        if ($page != 0) {
            $page = $page - 1;
        }
        $config['page'] = $page;
        $data['pagination'] = helper_create_pagination($config);
        // end pagination


        $data['page_title'] = 'Bills';
        $data['page'] = 'Bill';
        $data['main_page'] = 'Purchases';
        $data['bills'] = get_bills_by_type(0, $config['per_page'], $page * $config['per_page'], 1, $estimate = 3);
        $data['customers'] = get_by_user('vendors');
        $data['main_content'] = view('admin/user/orders', $data)->render();
        return view('admin/index', $data);
    }

    public function create()
    {
        $data = array();
        $data['page_title'] = 'Create Bill';
        $data['page'] = 'Bill';
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
        $data['products'] = get_by_user_and_type('products', 'is_buy');

        $data['total_tax'] = get_invoice_total_taxes(0);
        $data['asign_taxs'] = get_invoice_taxes(0);
        $data['gsts'] = get_user_taxes_by_gst();

        $data['customers'] = get_by_user('vendors');
        $data['total'] = get_total_by_user('invoice', 3);
        $data['main_content'] = view('admin/user/order_create', $data)->render();
        return view('admin/index', $data);
    }

    public function edit($id)
    {
        $data = array();
        $data['page_title'] = 'Edit Bill';
        $data['page_sub'] = 'Edit Bill';
        $data['page'] = 'Bill';
        $data['invoice'] = get_by_md5_data($id, 'invoice');
        $data['products'] = get_by_user_and_type('products', 'is_buy');

        $data['total_tax'] = get_invoice_total_taxes($data['invoice'][0]['id']);
        $data['asign_taxs'] = get_invoice_taxes($data['invoice'][0]['id']);
        $data['gsts'] = get_user_taxes_by_gst();

        $data['customers'] = get_by_user('vendors');
        $data['total'] = get_total_by_user('invoice', 3);
        $data['main_content'] = view('admin/user/order_create', $data)->render();
        return view('admin/index', $data);
    }

    public function details($id)
    {
        $data = array();
        $data['invoice'] = get_invoice_details($id);
        $data['page_title'] = 'Bill details';
        $data['page'] = 'Bill';
        $data['main_content'] = view('admin/user/order_details', $data)->render();
        return view('admin/index', $data);
    }

    public function add_product($product_id)
    {
        $product = get_by_id($product_id, 'products');
        $data = array();
        $data['taxes'] = get_by_user('tax');
        $data['product'] = $product;
        if (!empty($product)) {
            $loaded = view('admin/user/include/product_list', $data)->render();
            echo json_encode(array('st' => 1, 'loaded' => $loaded));
        }
    }


    public function save()
    {
        $data = array();
        $data['page_title'] = 'Bills';
        $data['page'] = 'Bill';
        $data['products'] = get_by_user('invoice');
        $data['main_content'] = view('admin/user/estimate_save', $data)->render();
        return view('admin/index', $data);
    }


    public function add(Request $request)
    {

        $data = array();
        $id = $request->input('id');

        $validator =  Validator::make(
            $request->all(),
            [
                'customer' => 'required|max:100'
            ],
            [
                'customer.required' => helper_trans('customer')
            ]
        );

        if ($validator->fails()) {
            $data['status'] = 2;
            $data['error'] = implode("<br>", $validator->errors()->all());
            echo json_encode($data);
        } else {

            if (!empty($request->input('discount'))) {
                $discount = $request->input('discount');
            } else {
                $discount = 0;
            }

            if (!empty($id)) {
                $invoice = get_by_id($id, 'invoice');
                $status = $invoice->status;
            } else {
                $status = 0;
            }

            $estimate = array(
                'user_id' => user()->id,
                'business_id' => helper_get_business()->uid,
                'title' => $request->input('title'),
                'type' => 3,
                'status' => $status,
                'summary' => $request->input('summary'),
                'customer' => $request->input('customer'),
                'number' => $request->input('number'),
                'poso_number' => '',
                'date' => $request->input('date'),
                'discount' => $discount,
                'expire_on' => '',
                'footer_note' => $request->input('footer_note'),
                'sub_total' => $request->input('sub_total'),
                'grand_total' => $request->input('grand_total'),
                'convert_total' => $request->input('convert_total')
            );



            if (!empty($id)) {
                delete_items_by_inv_id($id, 'invoice_taxes');
                delete_items_by_inv_id($id, 'invoice_items');
                helper_update_by_id($estimate, $id, 'invoice');
            } else {
                $id = helper_insert($estimate, 'invoice');
            }

            $items = $request->input('items');
            $details = $request->input('details');
            $price = $request->input('price');
            $quantity = $request->input('quantity');
            $total_price = $request->input('total_price');

            if (!empty($items)) {
                for ($i = 0; $i < count($items); $i++) {
                    $item_data = array(
                        'invoice_id' => $id,
                        'item' => $items[$i],
                        'price' => $price[$i],
                        'qty' => $quantity[$i],
                        'total' => $total_price[$i]
                    );
                    helper_insert($item_data, 'invoice_items');

                    if (!empty($details[$i])) {
                        $this->update_product($items[$i], $details[$i]);
                    }
                }
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

            $data['status'] = 1;
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


    public function convert($id)
    {
        $invoice = get_by_md5_id($id, 'invoice');
        $data = array(
            'type' => 1,
            'status' => 0
        );

        helper_update_by_id($data, $invoice->id, 'invoice');
        echo json_encode(array('st' => 1));
    }


    public function export_pdf($id)
    {
        $data = array();
        $data['invoice'] = get_by_md5_id($id, 'invoice');
        $data['page_title'] = 'Estimate Export';
        $data['page'] = 'Order';
        //load library
        $this->load->library('pdf');
        //load view page
        $this->pdf->load_view('admin/user/estimate_view', $data);
        $this->pdf->render();
        $this->pdf->stream("estimate.pdf");
    }


    //add invoice payment record
    public function record_payment(Request $request)
    {
        if ($_POST) {
            $id = $request->input('invoice_id');
            $invoice = get_by_md5_id($id, 'invoice');

            $amount = $request->input('amount');

            $grand_total = $invoice->grand_total - get_total_invoice_payments($invoice->id, $invoice->parent_id);

            if ($amount == $grand_total) {
                $status = 2;
            } else {
                $status = 1;
            }

            if (!empty($request->input('payment_method'))) {
                $payment_method = $request->input('payment_method');
            } else {
                $payment_method = '0';
            }

            $data = array(
                'payment_date' => $request->input('payment_date'),
                'amount' => $amount,
                'customer_id' => $invoice->customer,
                'business_id' => helper_get_business()->uid,
                'convert_amount' => $amount,
                'invoice_id' => $invoice->id,
                'payment_method' => $payment_method,
                'note' => $request->input('note'),
                'type' => 'expense'
            );

            $id = helper_insert($data, 'payment_records');

            $invoice_data = array(
                'status' => $status
            );
            helper_update_by_id($invoice_data, $invoice->id, 'invoice');
            return redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function approve_invoice($id)
    {
        $invoice = get_by_md5_id($id, 'invoice');
        //echo "<pre>"; print_r($invoice); exit();
        $data = array(
            'status' => 1
        );

        helper_update_by_id($data, $invoice->id, 'invoice');
        echo json_encode(array('st' => 1));
    }


    public function deactive($id)
    {
        $data = array(
            'status' => 0
        );

        helper_update_by_id($data, $id, 'invoice');
        session()->flash('msg', helper_trans('msg-deactivated'));
        return redirect(url('admin/services'));
    }

    public function delete($id)
    {
        delete_id($id, 'invoice');
        echo json_encode(array('st' => 1));
    }
}
