<?php

use App\Helpers\Cacher;
use App\Models\LanguageModel;
use App\Models\SettingsModel;
use App\Models\Time_zoneModel;
use App\Models\Lang_valuesModel;
use App\Models\Product_taxModel;
use App\Models\Settings_langModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

function put_settings()
{
    $settings  = DB::table('settings', 's')
        ->join('language as l', 'l.id', '=', 's.lang')
        ->select('s.*', 'l.short_name', 'l.name as language_name', 'l.slug as lang_slug', 'l.text_direction as dir')
        ->first();
    $site_lang = $settings->lang_slug;
    if (!session()->has('my_site_lang')) {
        session([
            'my_site_lang' => $site_lang
        ]);
    }
    Redis::set('settings_'.$settings->id, json_encode($settings));
    return response()->json($settings, 200);
}
function get_settings($id = 1){
    $settings = json_decode(Redis::get('settings_'.$id));
    if(empty($settings)){
        put_settings();
        return $settings;
    }
    return $settings ;
}

function settings()
{
    put_settings();
    return get_settings();
}

function text_dir()
{
    if (session('my_site_lang') == '') {
        $lang = get_settings();
        return $lang->dir;
    } else {
        $name = session('my_site_lang');
        $lang = LanguageModel::where('slug', $name)->first();
        return $lang->text_direction;
    }
}

function lang_short_form()
{
    if (session('my_site_lang') == '') {
        $lang = Get_Settings();
        return $lang->short_name;
    } else {
        $name = session('my_site_lang');
        $lang = LanguageModel::where('slug', $name)->first();
        return $lang->short_name;
    }
}

function get_language()
{
    return LanguageModel::all();
}

function get_time_zone()
{
    $time_zone = Time_zoneModel::find(settings()->time_zone);
    return $time_zone->name;
}

function my_date_now()
{
    $dt = new DateTime('now', new DateTimezone(get_time_zone()));
    $date_time = $dt->format('Y-m-d H:i:s');
    return $date_time;
}
function get_time_ago($time_ago)
{

    $dt = new DateTime('now', new DateTimezone(get_time_zone()));
    $date_time = strtotime($dt->format('Y-m-d H:i:s'));

    $time_ago = strtotime($time_ago);
    $cur_time   = $date_time;
    $time_elapsed   = $cur_time - $time_ago;
    $seconds    = $time_elapsed;
    $minutes    = round($time_elapsed / 60);
    $hours      = round($time_elapsed / 3600);
    $days       = round($time_elapsed / 86400);
    $weeks      = round($time_elapsed / 604800);
    $months     = round($time_elapsed / 2600640);
    $years      = round($time_elapsed / 31207680);
    // Seconds

    //return $seconds;

    if ($seconds <= 60) {
        return "just now";
    }
    //Minutes
    else if ($minutes <= 60) {
        if ($minutes == 1) {
            return "one minute ago";
        } else {
            return "$minutes minutes ago";
        }
    }
    //Hours
    else if ($hours <= 24) {
        if ($hours == 1) {
            return "an hour ago";
        } else {
            return "$hours hrs ago";
        }
    }
    //Days
    else if ($days <= 7) {
        if ($days == 1) {
            return "yesterday";
        } else {
            return "$days days ago";
        }
    }
    //Weeks
    else if ($weeks <= 4.3) {
        if ($weeks == 1) {
            return "a week ago";
        } else {
            return "$weeks weeks ago";
        }
    }
    //Months
    else if ($months <= 12) {
        if ($months == 1) {
            return "a month ago";
        } else {
            return "$months months ago";
        }
    }
    //Years
    else {
        if ($years == 1) {
            return "one year ago";
        } else {
            return "$years years ago";
        }
    }
}


function helper_trans($key = '')
{
    $line = Lang_valuesModel::where('keyword', $key)->first();
    // dd($line) ;

    if (empty($line)) {
        return $key;
    }
    // echo session('system.site_lang');
    $line = $line->toArray();
    // dd($line) ;
    if (empty(session('my_site_lang'))) {
        get_settings();
        return  $line[session('my_site_lang')];
    }
    // dd($line);
    return  $line[session('my_site_lang')];
}

function helper_update_settings()
{
    $settings  = DB::table('settings', 's')
        ->join('language as l', 'l.id', '=', 's.lang')
        ->select('s.*', 'l.short_name', 'l.name as language_name', 'l.slug as lang_slug', 'l.text_direction as dir')
        ->first();
    $site_lang = $settings->lang_slug;

    // for user
    if (!session()->has('my_site_lang')) {
        session([
            'my_site_lang' => $site_lang
        ]);
    }
    session([
        'settings' => $settings
    ]);

    return session('settings');
}
