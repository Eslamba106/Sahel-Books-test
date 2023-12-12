<?php

namespace App\Lib;

// use Mpdf\Mpdf;

class M_pdf
{

    public function get_pdf()
    {
        return new \Mpdf\Mpdf([
            'margin_left' => '5',
            'margin_right' => '5',
            'mode' => 'utf-8',
            'format' => 'A4',
            //A4, Letter, Legal, Executive, Folio, Demy, Royal, etc 'orientation' => 'P' 
            //"L" for Landscape orientation, "P" for Portrait orientation 
        ]);
    }
}
