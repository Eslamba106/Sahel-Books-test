<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Cache;

class Cacher{
    public function __construct( public string $store = 'file'){}
   //file //redis
    
  

    public static function setCached($key,$value){

        $cachedData = Cache::store($store = 'file')->put($key,$value);
  
    }

  public static function getCached($key){

    $cachedData =   Cache::store($store = 'file')->get($key);
        if($cachedData){
            return json_decode($cachedData);
        }
        
    }

    public function removeCached($key){

        Cache::store($this->store)->forget($key);
  
        
    }
}