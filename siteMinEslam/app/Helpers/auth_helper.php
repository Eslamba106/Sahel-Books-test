<?php

use App\Models\BusinessModel;
use App\Models\SettingsModel;
use App\Models\UsersModel;
use App\Models\Users_roleModel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

function auth_helper($role)
{
    return session($role);
}

function user()
{
    $user = helper_get_logged_user();
    if (empty($user)) {
        // logout_fun();
        // return redirect(url('auth/login'));
        return null;
    } else {
        return $user;
    }
}

function helper_get_business()
{
    if (!empty(session('active_business'))) {
        $business_data = get_u_business(session('active_business'));
    } else {
        $business_data = get_u_business(0);
    }
    session(['business_data' => $business_data]);
    session()->save();
    return $business_data;
}

function helper_update_business()
{
    if (!is_admin()) {
        // 
        if (session('role') == 'subadmin' || session('role') == 'editor' || session('role') == 'viewer' || session('role') == 'cashier') {
            $business_data = get_u_business(session('business_id'));
        } else {
            $business_data = get_u_business(helper_get_business()->uid);
        }
        // session(['business_data' => $business_data]);
        session()->put('business_data', $business_data);
        session()->save();
    }
}
function helper_update_user_data()
{
    $id = session('id');
    $user = json_decode(Redis::get('user_'.$id));
    // $user  = DB::table('users', 'u')
    //     ->join('country as c', 'c.id', '=', 'u.country', 'left')
    //     ->select('u.*', 'c.name as country', 'c.currency_name', 'c.currency_code', 'c.currency_symbol')
    //     ->where('u.id', session('id'))
    //     ->first();

    session(['user_data' => $user]);
    session()->save();
    return $user;
}

function load_user_session_data($user, $keep = '', $update_keep = true)
{
    $son_id = 0;
    if ($user->role == 'subadmin' || $user->role == 'editor' || $user->role == 'viewer' || $user->role == 'cashier') {
        $son_id = $user->id;
        $user_id = $user->parent_id;
    } else {
        $user_id = $user->id;
    }

    $package = check_user_payment($user_id);
    $data = array(
        'id' => $user_id,
        'name' => $user->name,
        'slug' => $user->slug,
        'thumb' => $user->thumb,
        'email' => $user->email,
        'role' => $user->role,
        'son_id' => $son_id,
        'expire_on' => !empty($package) ? $package->expire_on : '',
        'logged_in' => TRUE
    );

    if ($update_keep == true) {
        if (!empty($keep)) {
            $data['session_time'] = time() + (60 * 60 * 24 * 14); // 14 day
        } else {
            $data['session_time'] = time() + (60 * 60 * 24 * 1); // 1 day
        }
    }

    if (!empty($user->business_id)) {
        $data['business_id'] = $user->business_id;
    }

    // 
    /*if($user->role == 'sub_user'){
                    $data['parent_id']= $user->parent_id ;
                }*/

    session($data);
    session()->save();
    helper_add_user_data();
    helper_update_business();
}

// get business
// Redis::set("all_business" , json_encode($result));
// $all_business = (array)json_decode(Redis::get("all_business"));
function get_u_business($uid, $user_id = "") // 
{
    $result  = DB::table('business', 'b')
        ->join('country as t', 't.id', '=', 'b.country')
        ->select('b.*', 't.name as country', 't.currency_symbol', 't.currency_name', 't.currency_code');

    if ($uid != 0) {
        $result  = $result->where('b.uid', $uid);
    } else {
        $result  = $result->where('b.is_primary', 1);
    }
    if ($user_id == "") // 
        $result  = $result->where('b.user_id', session('id'));
    else
        $result  = $result->where('b.user_id', $user_id);

    return $result->first();
}

// get business
function get_business($uid)
{
    // 
    if (user()->role == "sub_user")
        $user_id = user()->parent_id;
    else
        $user_id = session('id');

    $query = DB::table('business', 'b')
        ->join('country as t', 't.id', '=', 'b.country')
        ->select('b.*', 'n.name as country_name', 'c.name as category_name', 't.currency_symbol', 't.currency_name', 't.currency_code');
    if ($uid != 0) {
        $query = $query->where('b.uid', $uid);
    }
    $query = $query->where('b.user_id', $user_id);
    $query = $query->join('country as n', 'n.id', '=', 'b.country', 'left');

    $query = $query->join('business_category as c', 'c.id', '=', 'b.category', 'left');
    $query = $query->orderBy('id', 'asc');

    $query = $query->get();
    return $query;
}
function get_my_business()
{
    $business_data = json_decode(Redis::get('business_data'));

    if($business_data == null){
        $result  = DB::table('business', 'b')
        ->join('country as t', 't.id', '=', 'b.country')
        ->select('b.*', 't.name as country')
        ->where('b.user_id', session('id'))
        ->get();
        Redis::set("business_data" , json_encode($result)); 
        return $business_data;
    }
    return $business_data;
}



//is logged in
function helper_is_logged_in()
{

    $id = session('id');
    $user_data = json_decode(Redis::get('user_'.$id));
    if (session('logged_in') == TRUE && !empty($user_data)) {
        if (session('session_time') < time()) {
            // session()->invalidate();
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
    //check if user logged in
    // dd(session('logged_in'), session('id'));
    // if (session('logged_in') == TRUE && !empty(UsersModel::where('id', session('id'))->first())) {
    //     if (session('session_time') < time()) {
    //         // session()->invalidate();
    //         return false;
    //     } else {
    //         return true;
    //     }
    // } else {
    //     return false;
    // }
}

//function get user
function helper_get_logged_user()
{
    $id = session('id');
    $user_data = json_decode(Redis::get('user_'.$id));
    // dd($user_data);
    if(empty($user_data)){
        $user  = DB::table('users', 'u')
        ->join('country as c', 'c.id', '=', 'u.country', 'left')
        ->select('u.*', 'c.name as country', 'c.currency_name', 'c.currency_code', 'c.currency_symbol')
        ->where('u.id', session('id'))
        ->first();
        Redis::set("user_".$id , json_encode($user));
    }
    return $user_data ;
    // dd($user_data);
    // if (helper_is_logged_in()) {
    //     if (empty(session('user_data'))) {
    //         $user  = DB::table('users', 'u')
    //             ->join('country as c', 'c.id', '=', 'u.country', 'left')
    //             ->select('u.*', 'c.name as country', 'c.currency_name', 'c.currency_code', 'c.currency_symbol')
    //             ->where('u.id', session('id'))
    //             ->first();
    //         session(['user_data' => $user]);
    //         session()->save();
    //         return $user;
    //     } else {
    //         return session('user_data');
    //     }
    // }
    // if()


    
}

//is admin
function is_admin()
{
    //check logged in
    if (!helper_is_logged_in()) {
        return false;
    }

    //check role
    if (user()->role == 'admin') {
        return true;
    } else {
        return false;
    }
}

//is user
function helper_is_user()
{
    if (!helper_is_logged_in()) {
        return false;
    }

    //check role
    if (user()->role == 'user' || user()->role == 'sub_user') { // 
        return true;
    } else {
        return false;
    }
}


//logout
function logout_fun()
{
    //unset user data
    session()->forget('logged_in');
    session()->forget('admin_logged_in');
    session()->forget('app_key');
    session()->invalidate();
}


// check post email
function check_email($email)
{
    $users = UsersModel::where('email', $email)->get();
    if (!empty($users)) {
        return $users;
    } else {
        return false;
    }
}


// check valid user by id
function validate_id($id)
{
    $users = UsersModel::where(DB::raw('md5(id)'), $id)->first();
    if ($users) {
        return $users;
    } else {
        return false;
    }
}

// check valid user
function validate_user($request)
{
    $users = UsersModel::where('email', $request->input('user_name'))->orWhere('user_name', $request->input('user_name'))->first();
    if ($users) {
        return $users;
    } else {
        return false;
    }
}

function get_logged_user($id)
{
    return json_decode(Redis::get('user_'.$id)) ;
    // return UsersModel::where('id', $id)->first();
}


function get_active_business()
{
    $server = $_SERVER;
    $http = 'http';
    if (isset($server['HTTPS'])) {
        $http = 'https';
    }
    $host = $server['HTTP_HOST'];
    $requestUri = $server['REQUEST_URI'];
    $page_url = $http . '://' . htmlentities($host) . '/' . htmlentities($requestUri);

    $curr = get_settings();
    if (empty($curr->ind_code) || strlen($curr->ind_code) != 40 || strlen($curr->purchase_code) != 36) {
        $url = "https://www.originlabsoft.com/api/verify?domain=" . $page_url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response);
    }
}

function helper_add_user_data()
{
    $id = session('id');
    $user_data = json_decode(Redis::get('user_'.$id));
    session(['user_data' => $user_data]);
    session()->save();
    // if (session('role') == 'subadmin' || session('role') == 'editor' || session('role') == 'viewer' || session('role') == 'cashier') {
    //     $id = session('parent_id');
    // }

    // $user  = DB::table('users', 'u')
    //     ->join('country as c', 'c.id', '=', 'u.country', 'left')
    //     ->select('u.*', 'c.name as country', 'c.currency_name', 'c.currency_code', 'c.currency_symbol')
    //     ->where('u.id', $id)
    //     ->first();

}