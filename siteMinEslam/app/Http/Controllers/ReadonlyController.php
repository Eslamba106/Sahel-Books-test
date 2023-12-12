<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Lib\M_pdf;
use Illuminate\Http\Request;

class ReadonlyController extends Controller
{

    public function __construct()
    {
    }

    public function export_pdf($id, M_pdf $mpdf)
    {

        $data = array();
        $data['invoice'] = get_invoice_details($id);

        if ($data['invoice']->type == 1 || $data['invoice']->type == 4) {
            $type = 'Invoice';
        } else if ($data['invoice']->type == 2) {
            $type = 'Estimate';
        } else if ($data['invoice']->type == 5) {
            $type = 'Debit Note';
        } else {
            $type = 'Bill';
        };

        $data['page_title'] = $type . ' Export';
        $data['page'] = $type;

        //load the view and saved it into $html variable
        $html = view('admin/user/export_file', $data)->render();

        // echo ($html);
        // exit;
        //this the the PDF filename that user will get to download
        $pdfFilePath = $type . '-' . $data['invoice']->type . ".pdf";


        // print_r($html);
        // exit;
        //load mPDF library
        $pdf = $mpdf->get_pdf();

        try {

            //generate the PDF from the given html
            $pdf->WriteHTML($html);
            //$this->m_pdf->pdf->Annotation("File annotation", 0, 0, 'Note', '', '', 0, false, '', base_url().'/assets/add-user.png');

            // $pdf->SetAssociatedFiles([[
            //     'name' => 'public_filename.png',
            //     'mime' => 'text/xml',
            //     'description' => 'some description',
            //     'AFRelationship' => 'Alternative',
            //     'path' =>  url('') . '/assets/add-user.png' // __DIR__ . '/../data/xml/test.xml'
            // ]]);
            //download it.
            $pdf->Output($pdfFilePath, "D");
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }



    public function estimate($mode, $id)
    {
        $data = array();
        $data['mode'] = $mode;
        if ($mode == 'preview') {
            $data['link'] = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        }
        $data['invoice'] = get_readonly_invoice($id);
        $data['page_title'] = 'Estimate preview';
        $data['page'] = 'Estimate';
        return view('admin/user/estimate_view', $data);
    }

    public function invoice($mode, $id)
    {
        $data = array();
        $data['invoice'] = get_readonly_invoice($id);
        $data['mode'] = $mode;
        if ($mode == 'preview') {
            $data['link'] = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        }
        $data['page_title'] = 'Invoice preview';
        $data['page'] = 'Invoice';
        return view('admin/user/invoice_view', $data);
    }

    public function bill($mode, $id)
    {
        $data = array();
        $data['invoice'] = get_readonly_invoice($id);
        $data['mode'] = $mode;
        if ($mode == 'preview') {
            $data['link'] = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        }
        $data['page_title'] = 'Invoice preview';
        $data['page'] = 'Bill';
        return view('admin/user/bill_view', $data);
    }

    public function inv(Request $request)
    {

        $invoice = get_by_md5_id(md5(1), 'invoice');
        $data = array();
        if (isset($is_myself)) {
            $data['email_myself'] = $request->input('email_myself');
        } else {
            $data['email_myself'] = '';
        }

        $data['email_to'] = $request->input('email_to');
        $data['message'] = $request->input('message');
        $data['subject'] = $request->input('subject');
        $data['invoice'] = $invoice;
        return view('email_template/invoice', $data);
    }


    public function approve($status, $id, Request $request)
    {
        $invoice = get_by_md5_id($id, 'invoice');
        if ($status == 1) {
            $reject_reason = '';
        } else {
            $reject_reason = $request->input('reject_reason');
        }

        $data = array(
            'status' => $status,
            'reject_reason' => $reject_reason,
            'client_action_date' => my_date_now(),
        );

        helper_update_by_id($data, $invoice->id, 'invoice');
        return redirect($_SERVER['HTTP_REFERER']);
    }
}
