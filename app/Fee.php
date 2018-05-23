<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;
use Request;
use Input;
use DB;

class Fee extends Model {
    static public function getRegions(&$data){
        $data['regions'] = DB::table('regions')->get();
    }
    static public function getCountries(&$data){
        $data['countries'] = DB::table('countries')->get();
    }
    static public function getRegionsIds(){
        return DB::table('regions')->select('id')->get();
    }
    
    static public function getRegionIds($id){
        return DB::table('region_fee')
                ->where('merch_id','=',$id)
                ->get();
    }
    static public function getCountriesIds($id){
        return DB::table('countries_fee')
                ->join('countries','countries_fee.country_id','=','countries.id')
                ->select('countries_fee.*','countries.region')
                ->where('merch_id','=',$id)
                ->get();
    }
    static public function getCountriesByRegionId($id){
        return DB::table('countries')->where('region', '=', $id)->get();
    }
    
    static public function displayFeesOfMerchant(&$data, $id){
        $data['region_fee'] = DB::table('region_fee')
                ->join('regions','region_fee.region_id','=','regions.id')
                ->where('region_fee.merch_id','=',$id)
                ->select('region_fee.*','regions.name as r_name')
                ->get();
        
        foreach($data['region_fee'] as $r_fee){
            $country_fee[] = DB::table('countries_fee')
                ->join('countries','countries_fee.country_id','=','countries.id')
                ->select('countries_fee.*','countries.country as country_name','countries.region as region')
                ->where('countries.region','=',$r_fee->region_id)
                ->where('countries_fee.merch_id','=',$id)
                ->get();
        }
        if(!empty($country_fee)){
        $data['country_fee'] = $country_fee;
        }
    }
}
