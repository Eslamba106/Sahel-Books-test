<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EstimateController extends Controller
{

    public function index()
    {
        $data = array();
        $config['base_url'] = url('admin/estimate/index');
        $total_row = get_estimates_by_type(1, 0, 0, 1, $estimate = 2);


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

        $data['page_title'] = 'Estimate';
        $data['page'] = 'Estimate';
        $data['main_page'] = 'Sales';
        $data['estimates'] = get_estimates_by_type(0, $config['per_page'], $page * $config['per_page'], 1, $estimate = 2);
        $data['customers'] = get_by_user('customers');
        $data['main_content'] = view('admin/user/estimates', $data)->render();
        return view('admin/index', $data);
    }

    public function create()
    {
        $data = array();
        $data['page_title'] = 'Create Estimate';
        $data['page'] = 'Estimate';
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

        $data['total_tax'] = get_invoice_total_taxes(0);
        $data['asign_taxs'] = get_invoice_taxes(0);
        $data['gsts'] = get_user_taxes_by_gst();

        $data['customers'] = get_by_user('customers');
        $data['total'] = get_total_by_user('invoice', 2);
        $data['main_content'] = view('admin/user/estimate_create', $data)->render();
        return view('admin/index', $data);
    }

    public function edit($id)
    {
        $data = array();
        $data['page_title'] = 'Edit Estimate';
        $data['page_sub'] = 'Edit';
        $data['page'] = 'Estimate';
        $data['invoice'] = get_by_md5_data($id, 'invoice');
        $data['products'] = get_by_user_and_type('products', 'is_sell');

        $data['total_tax'] = get_invoice_total_taxes($data['invoice'][0]['id']);
        $data['asign_taxs'] = get_invoice_taxes($data['invoice'][0]['id']);
        $data['gsts'] = get_user_taxes_by_gst();

        $data['customers'] = get_by_user('customers');
        $data['total'] = get_total_by_user('invoice', 2);
        $data['main_content'] = view('admin/user/estimate_create', $data)->render();
        return view('admin/index', $data);
    }

    public function details($id)
    {
        $data = array();
        $data['invoice'] = get_invoice_details($id);
        $data['page_title'] = 'Estimate details';
        $data['page'] = 'Estimate';
        $data['main_content'] = view('admin/user/estimate_save', $data)->render();
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
        $data['page_title'] = 'Invoice';
        $data['page'] = 'Invoice';
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

            $estimate = array(
                'user_id' => user()->id,
                'business_id' => helper_get_business()->uid,
                'title' => $request->input('title'),
                'type' => 2,
                'status' => 0,
                'summary' => $request->input('summary'),
                'customer' => $request->input('customer'),
                'number' => $request->input('number'),
                'poso_number' => $request->input('poso_number'),
                'date' => $request->input('date'),
                'discount' => $discount,
                'expire_on' => $request->input('expire_on'),
                'footer_note' => $request->input('footer_note'),
                'sub_total' => $request->input('sub_total'),
                'grand_total' => $request->input('grand_total'),
                'convert_total' => '0.00'
            );



            if (!empty($id)) {
                delete_items_by_inv_id($id, 'invoice_taxes');
                delete_items_by_inv_id($id, 'invoice_items');
                helper_update_by_id($estimate, $id, 'invoice');
            } else {

                if (check_package_limit('estimate') != -2) {
                    $total = get_total_value('invoice', 2);
                    if ($total >= check_package_limit('estimate')) :
                        $msg = helper_trans('reached-limit') . ', ' . helper_trans('package-limit-msg');
                        $data['status'] = 4;
                        $data['error'] = $msg;
                        echo json_encode($data);
                        exit();
                    endif;
                }

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
        $data['page'] = 'Estimate';
        //load library
        $this->load->library('pdf');
        //load view page
        $this->pdf->load_view('admin/user/estimate_view', $data);
        $this->pdf->render();
        $this->pdf->stream("estimate.pdf");
    }


    public function send($id, Request $request)
    {
        if ($_POST) {
            $customer_id = $request->input('customer_id');
            $is_myself = $request->input('is_myself');
            $estimate = get_invoice_details($id);
            $customer = get_customer_info($customer_id);
            $data = array();
            if (isset($is_myself)) {
                $data['email_myself'] = $request->input('email_myself');
            } else {
                $data['email_myself'] = '';
            }

            $data['email_to'] = $request->input('email_to');
            $data['message'] = $request->input('message');
            $data['subject'] = 'Estimate #' . $estimate->id . ' from ' . user()->name;
            $data['invoice'] = $estimate;
            $data['logo'] = !empty($estimate->logo) ? url($estimate->logo) : '';
            if ($estimate->type == 3) {
                $data['currency_code'] = helper_get_business()->currency_code;
                $data['currency_symbol'] = helper_get_business()->currency_symbol;
                $data['type'] = 'Bill';
            } else {
                $data['currency_code'] = $customer->currency;
                $data['currency_symbol'] = $customer->currency_symbol;
                $data['type'] = 'Estimate';
            }
            $data['html_content'] = view('email_template/invoice', $data)->render();
            $send_data = helper_send_email($data['email_to'], $data['subject'], $data['html_content'], $data['email_myself']);

            if ($send_data == true) {
                $sent_data = array(
                    'is_sent' => 1,
                    'sent_date' => my_date_now()
                );
                helper_update_by_id($sent_data, $id, 'invoice');

                $data = array();
                $data['status'] = 1;
                echo json_encode($data);
            } else {
                $data = array();
                $data['status'] = 2;
                echo json_encode($data);
            }
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
