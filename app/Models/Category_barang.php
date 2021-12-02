<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Category_barang extends Model
{

    protected $fillable = [
        'deskripsi'    
    ];
    use HasFactory;


    public static function getCatBarang(){
        $result = DB::select("select * from category_barangs");
        return $result;
        }


    public static function getCatBarangById($id){
        $result = DB::select("select * from category_barangs
        where 1=1 
        and id ='$id'");
        return $result;
        }




    public static function updCatBarang($params){
        try{		
            DB::update("
            UPDATE category_barangs SET 
            deskripsi=?
            WHERE id= ?",
              [
              $params['deskripsi'],
              $params['id']]  
            );
            return true;
		}catch(\Exception $e){
			
			return false;
		}
    }  


    public static function delCatBarang($id){
        try{
            DB::delete("
            delete from category_barangs 
            WHERE id= '$id'");
        return true;
        }catch(\Exception $e){
            
            return false;
        }
     }  
    
   
}
