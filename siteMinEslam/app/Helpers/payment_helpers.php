<?php
// get_payment

use App\Models\PaymentModel;
use Illuminate\Support\Facades\DB;

function get_my_package()
{
    // dd(session('id'));
    $query = DB::table('payment', 'p')
        ->select('p.*', 'k.name as package_name', 'k.slug')
        ->join('package as k', 'k.id', '=', 'p.package', 'left')
        ->where('p.user_id', session('id'))
        ->orderBy('p.id', 'desc');
    $query = $query->first();
    return $query;
}
