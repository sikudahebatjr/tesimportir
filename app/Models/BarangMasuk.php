<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class BarangMasuk extends Model
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


    public static function getBarangMasuk(){
        $result = DB::select("select * from barang_masuk_header bmh
        LEFT JOIN barang_masuk_detail bmd
        ON bmh.kode_barang_masuk = bmd.kode_barang_masuk_header");
        return $result;
        }


        public static function getAutoIncrement(){
            $result = DB::select("SELECT `auto_increment` FROM INFORMATION_SCHEMA.TABLES
            WHERE table_name = 'barang'");
            return $result;
            }


    public static function getBarangMasukById($id){
        $result = DB::select("select * from barang_masuk_header bmh
        LEFT JOIN barang_masuk_detail bmd
        ON bmh.kode_barang_masuk = bmd.kode_barang_masuk_header
        where 1=1 
        and kode_barang_masuk ='$id'");
        return $result;
        }


        public static function checkBarangMasukByCode($id){
            $result = DB::select("select * from barang_masuk_header
            where 1=1 
            and kode_barang_masuk ='$id'");
            return $result;
        }
    




  


    public static function delBarangMasukHeader($id){
        try{
            DB::delete("
            delete from barang_masuk_header 
            WHERE kode_barang_masuk= '$id';
    
            ");
        return true;
        }catch(\Exception $e){
            
            return false;
        }
     }  


     public static function delBarangMasukDetail($id){
        try{
            DB::delete("
            delete from barang_masuk_detail 
            WHERE kode_barang_masuk_header= '$id';
    
            ");
        return true;
        }catch(\Exception $e){
            
            return false;
        }
     }  



         public static function insBarangMasukHeader($params){

            try{
              $respone =   DB::select("insert into barang_masuk_header
                (
                    kode_barang_masuk
                    ,total                   
                    ,tanggal                             
                    ,created_by                         
                    ,created_at                                                                                          
                )
            values
                ( 
                    '" . $params['kode_barang_masuk'] . "'
                    ,'" . $params['total'] . "'
                    , '" . $params['tanggal'] . "'
                    ,'" . $params['created_by'] . "'
                    ,now()                              
                )");
              return true;
              
            }catch(\Exception $e){
                
                return false;
            }
         }   



         public static function insBarangMasukDetail($params){

            try{
              $respone =   DB::select("insert into barang_masuk_detail
                (
                    kode_barang_masuk_header
                    ,kode_barang
                    ,jumlah                                            
                    ,created_by                         
                    ,created_at                                                                                          
                )
            values
                ( 
                    '" . $params['kode_barang_masuk_header'] . "'
                    ,'" . $params['kode_barang'] . "'
                    , '" . $params['jumlah'] . "'
                    ,'" . $params['created_by'] . "'
                    ,now()                              
                )");
              return true;
              
            }catch(\Exception $e){
                
                return false;
            }
         } 


         public static function updBarangMasukDetail($params){
            try{		
               $query= DB::select("
                UPDATE barang_masuk_detail 
                SET 
                jumlah= ".$params['jumlah'].",
                updated_by=".$params['updated_by'].",
                updated_at=now()
                WHERE 1=1
                and kode_barang_masuk_header= '".$params['kode_barang_masuk_header']."'
                and kode_barang= '".$params['kode_barang']."'"
                );

      
                return  true;
            }catch(\Exception $e){
                
                return false;
            }
        }  


        public static function updBarangMasukHeader($params){
            try{		
                DB::update("
                UPDATE barang_masuk_header 
                SET 
                total=?,
                updated_by=?,
                updated_at=?
                WHERE 1=1
                and kode_barang_masuk= '?'",
                  [
                  $params['total'],
                  $params['updated_by'],
                  'now()',
                  $params['kode_barang_masuk']
                  ]  
                );
                return true;
            }catch(\Exception $e){
                
                return false;
            }
        }  
         
         

         public static function getBarangMasukDetailByCodeEdit($kode_barang_masuk,$kode_barang,$jumlah){
            $result = DB::select("SELECT *,($jumlah-jumlah) jumlah_temp FROM barang_masuk_detail
            WHERE 1=1 
            AND kode_barang_masuk_header ='$kode_barang_masuk'
            AND kode_barang ='$kode_barang';");
            return $result;
            }


        public static function getBarangMasukPerhari(){
            $result = DB::select("SELECT kode_barang
            ,SUM(jumlah) jumlah
            ,tanggal
            FROM barang_masuk_header bmh
            LEFT JOIN barang_masuk_detail bmd
            ON bmh.kode_barang_masuk = bmd.kode_barang_masuk_header
            GROUP BY kode_barang_masuk,kode_barang,tanggal");
            return $result;
        }

    public static function getBarangMasukPerminggu(){
        $result = DB::select("SELECT kode_barang
        ,SUM(jumlah) jumlah
        ,YEARWEEK(tanggal) tahun_minggu
        FROM barang_masuk_header bmh
        LEFT JOIN barang_masuk_detail bmd
        ON bmh.kode_barang_masuk = bmd.kode_barang_masuk_header
        GROUP BY kode_barang,YEARWEEK(tanggal) ");
        return $result;
    }


    public static function getBarangMasukPerbulan(){
            $result = DB::select("SELECT kode_barang
            ,SUM(jumlah) jumlah
            ,YEARWEEK(tanggal) tahun_minggu
            FROM barang_masuk_header bmh
            LEFT JOIN barang_masuk_detail bmd
            ON bmh.kode_barang_masuk = bmd.kode_barang_masuk_header
            GROUP BY kode_barang,YEARWEEK(tanggal) ");
            return $result;
    }
        
        public static function getBarangMasukPertahun(){
            $result = DB::select("SELECT kode_barang
            ,SUM(jumlah) jumlah
            ,YEAR(tanggal) tahun
            FROM barang_masuk_header bmh
            LEFT JOIN barang_masuk_detail bmd
            ON bmh.kode_barang_masuk = bmd.kode_barang_masuk_header
            GROUP BY kode_barang,YEAR(tanggal) ");
            return $result;
            }
            


    
   
}
