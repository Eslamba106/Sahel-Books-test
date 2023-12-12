<?php

use App\Models\PaymentModel;
use App\Models\UsersModel;
use Illuminate\Support\Facades\DB;


// upcomming recurring invoices
function get_upcomming_recurring_payments()
{
    // 
    if (isset(user()->role) && user()->role == "sub_user")
        $user_id = user()->parent_id;
    else
        $user_id = session('id');

    $query =  DB::table('invoice', 'i')
        ->select('i.*', 'c.name as customer_name')
        ->leftJoin("customers as c", "c.id", "=", "i.customer")
        ->where('i.business_id', helper_get_business()->uid)
        ->where('i.user_id', $user_id)
        ->where('i.recurring', 1)
        ->where(function ($q) {
            $q->where('i.next_payment', '>', date('Y-m-d'))
                ->orWhere('i.recurring_end', '<', date('Y-m-d'));
        })
        ->orderBy('i.next_payment', 'asc')
        ->groupBy('i.parent_id')
        ->take(4)
        ->get();

    // echo '<pre>';
    // var_dump($query);
    // echo '</pre>';
    // exit;
    // if (empty($query[0]->customer_name)) {
    //     return [];
    // }
    return $query;
}


// count invoices
function admin_count_invoices($type)
{
    $query = DB::table('invoice', 'i')
        ->join('users as u', "u.id", "=", "i.user_id");
    if (!empty(helper_get_business())) {
        $query = $query->where('business_id', helper_get_business()->uid);
    }
    $query = $query->where('i.type', $type);
    $query = $query->groupBy('i.id');
    return $query->count();
}


//get report
function get_admin_income_by_date($date)
{
    $query = DB::table('payment', 'r')
        ->select('r.*', DB::raw('SUM(r.amount) as total'))
        ->join('users as u', "u.id", "=", "r.user_id")
        ->where("r.status", 'verified')
        ->groupBy('r.created_at')
        ->where(DB::raw("DATE_FORMAT(r.created_at,'%Y-%m')"), $date)
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

//get packages
function get_users_packages()
{
    $query = DB::table('payment', 'p')
        ->select('k.name', DB::raw('SUM(p.id) as total'))
        ->join('package as k', 'k.id', '=', 'p.package', 'left')
        ->where('status', 'verified')
        ->groupBy('p.package');
    return $query->get();
}

// count users
function count_users()
{
    return UsersModel::count();
}

// count users
function count_business()
{
    return DB::table('business', 'b')
        ->join('users as u', "b.user_id", "=", "u.id")
        ->groupBy('b.id')
        ->count();
}

//get latest users
function get_latest_users($lilmit)
{
    return DB::table('users', 'u')
        ->select('u.*', 'p.status as payment_status', 'p.package', 'p.expire_on', 'pk.name as package_name')
        ->join('payment as p', 'p.user_id', '=', 'u.id', 'left')
        ->join('package as pk', 'pk.id', '=', 'p.package', 'left')
        ->where('u.role', 'user')
        ->orderBy('u.id', 'desc')
        ->groupBy('u.id')
        ->take($lilmit)
        ->get();
}

//get admin report
function get_admin_income_by_year()
{
    $query = DB::table('payment', 'r')
        ->select('r.*', DB::raw('SUM(r.amount) as total'))
        ->join('users as u', "u.id", "=", "r.user_id")
        ->where("r.status", 'verified')
        ->groupByRaw("DATE_FORMAT(r.created_at,'%Y')")
        ->get();;
    return $query;
}

// get all users
function get_all_users($total, $limit, $offset, $type, $developer = false)
{

    $sql_query = "SELECT result.* , `p`.`status` as `payment_status`, `p`.`package`, `p`.`created_at` `start_on`, `p`.`expire_on`, `pk`.`name` as `package_name` from( SELECT max(t.id) payment_id, `u`.* FROM `users` `u` LEFT JOIN `payment` `t` ON `t`.`user_id` = `u`.`id` WHERE `u`.`role` = 'user' GROUP BY `u`.`id` ORDER BY u.id) result LEFT JOIN `payment` `p` ON `p`.`user_id` = `result`.`id` and p.id = result.payment_id LEFT JOIN `package` `pk` ON `pk`.`id` = `p`.`package`";
    /*$this->db->select('* from( SELECT max(p.id), u.*, p.status as payment_status, p.package,  p.created_at start_on, p.expire_on, pk.name as package_name');
        $this->db->from('users u');
        $this->db->join('payment p', 'p.user_id = u.id', 'LEFT');
        $this->db->join('package pk', 'pk.id = p.package', 'LEFT');
        $this->db->where('u.role', 'user');*/


    if ($type != 'all') {
        // $this->db->where('u.account_type', $type);
        $sql_query .= " and result.account_type = " . $type;
    }

    if (!empty($_GET['package'])) {
        $sql_query .= " and p.package = " . $_GET['package'];
        $sql_query .= " and result.user_type <> 'trial'";
        // $this->db->where('p.package', $_GET['package']);
        //$this->db->where('u.user_type <>', 'trial');
    }


    if (!empty($_GET['search'])) {
        $sql_query .= " and result.name like '%" . $_GET['search'] . "%'";
        if ($developer == false) {
            $sql_query .= " or result.email like '%" . $_GET['search'] . "%'";
        }
        // $this->db->like('u.name', $_GET['search']);
        // $this->db->or_like('u.email', $_GET['search']);
    }



    //$this->db->order_by('u.id','DESC');
    //$this->db->group_by('u.id');

    // Mohmmed
    // $this->db->query('SET SQL_BIG_SELECTS=1');

    if ($total == 1) {
        //$query = $this->db->get();
        // $query = $this->db->query($sql_query);
        $query = DB::select($sql_query);
        // dd();
        // $query = $query->count();
        return count($query);
    } else {
        //$query = $this->db->get('', $limit, $offset);
        if ($limit != 0)
            $query = DB::select($sql_query . " LIMIT " . $offset . "," . $limit);
        else
            $query = DB::select($sql_query);
        foreach ($query as $key => $value) {
            $query2 = DB::table('business', 'b')
                ->where('b.user_id', $value->id)
                ->get();
            $query[$key]->business = $query2;
        }
        return $query;
    }
}
