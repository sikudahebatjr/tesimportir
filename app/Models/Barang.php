<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Barang extends Model
{

    protected $fillable = [
        'kode_barang'
        ,'nama_barang'
        ,'stock_awal'
        ,'stock_akhir'
        ,'created_at'
        ,'created_by'
        ,'updated_at'
        ,'updated_by'
    ];
    use HasFactory;


    public static function getBarang(){
        $result = DB::select("select * from barang");
        return $result;
    }


    public static function getAutoIncrement(){
        $result = DB::select("SELECT `auto_increment` FROM INFORMATION_SCHEMA.TABLES
        WHERE table_name = 'barang'");
        return $result;
    }


    public static function getBarangById($id){
        $result = DB::select("select * from barang
        where 1=1 
        and id ='$id'");
        return $result;
    }

    public static function checkBarangByCode($id){
        $result = DB::select("select * from barang
        where 1=1 
        and kode_barang ='$id'");
        return $result;
    }


    public static function getBarangByCode($id,$jumlah){
        $result = DB::select("SELECT *
        ,case when stock_akhir is null then (stock_awal+ $jumlah)
        when stock_akhir is not null then  (stock_akhir + $jumlah) end 
        stock_akhir_temp from barang
        where 1=1 
        and kode_barang ='$id'");
        return $result;
    }

    public static function getBarangByCodeKeluar($id,$jumlah){
        $result = DB::select("SELECT *
        ,case when stock_akhir is null then (stock_awal- $jumlah)
        when stock_akhir is not null then  (stock_akhir - $jumlah) end 
        stock_akhir_temp from barang
        where 1=1 
        and kode_barang ='$id'");
        return $result;
    }


    public static function getBarangByCodeEdit($id,$jumlah){
        $result = DB::select("SELECT *
        ,case when stock_akhir is null then (stock_awal+ $jumlah)
        when stock_akhir is not null then  (stock_akhir + $jumlah) end 
        stock_akhir_temp from barang
        where 1=1 
        and kode_barang ='$id'");
        return $result;
    }


    public static function getBarangByCodeDelete($id,$jumlah){
        $result = DB::select("SELECT *
        ,case when stock_akhir is null
        then 0 else stock_akhir - $jumlah 
        end stock_akhir_temp from barang
        where 1=1 
        and kode_barang ='$id'");
        return $result;
    }




    public static function updBarang($params){
        try{		
            DB::update("
            UPDATE barang SET 
            category_id=?,
            nama_barang=?,
            stock_awal=?
            WHERE id= ?",
              [
              $params['category_id'],
              $params['nama_barang'],
              $params['stock_awal'],
              $params['id']]  
            );
            return true;
		}catch(\Exception $e){
			
			return false;
		}
    }  


    public static function delBarang($id){
        try{
            DB::delete("
            delete from barang 
            WHERE id= '$id'");
        return true;
        }catch(\Exception $e){
            
            return false;
        }
     }  



         public static function insBarang($params){

            try{
              $respone =   DB::select("insert into barang
                (
                    kode_barang
                    ,category_id
                    ,nama_barang                       
                    ,stock_awal                             
                    ,created_by                         
                    ,created_at                                                                                          
                )
            values
                ( 
                    '" . $params['kode_barang'] . "'
                    ,'" . $params['category_id'] . "'
                    ,'" . $params['nama_barang'] . "'
                    , '" . $params['stock_awal'] . "'
                    ,'" . $params['created_by'] . "'
                    ,now()                              
                )");
              return true;
              
            }catch(\Exception $e){
                
                return false;
            }
         }   




         public static function updStockBarang($params){
            try{		
                DB::update("
                UPDATE barang SET 
                stock_akhir=?
                WHERE kode_barang= ?",
                  [
                  $params['stock_akhir'],
                  $params['kode_barang']]  
                );
                return true;
            }catch(\Exception $e){
                
                return false;
            }
        }  


    
   
}
