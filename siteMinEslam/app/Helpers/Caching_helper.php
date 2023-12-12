<?php

namespace App\Helpers;
//
use App\Helpers\Cacher;
use App\Models\SettingsModel;
use Illuminate\Support\Facades\DB;

class Caching_helper
{
 
//we call the cacher with the store name file or redis
    private  $cacher;
    public function __construct(){
     $this->cacher =  new Cacher('file');
    }

    function put_settings()
{
    $settings  = DB::table('settings', 's')
        ->join('language as l', 'l.id', '=', 's.lang')
        ->select('s.*', 'l.short_name', 'l.name as language_name', 'l.slug as lang_slug', 'l.text_direction as dir')
        ->first();
    $site_lang = $settings->lang_slug;

    $settings = new SettingsModel(SettingsModel::get($settings));
    $this->cacher->setCached('settings_'.$settings->id,  $settings->toJson());
    return response()->json($settings, 200);
}
public function Get_Settings($id = 1){
    $cachedData = $this->cacher->getCached('settings'.$id);
    $settings = $cachedData ;
    return response()->json($settings, 200);
}



}