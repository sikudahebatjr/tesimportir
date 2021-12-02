<?php

namespace App\Http\Controllers;
use Auth;
use Validator;
use App\Models\Barang;
use App\Models\Category_barang;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;

class BarangKeluarController extends Controller
{
  

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function index()
    {
        $getBarangKeluar = BarangKeluar::getBarangKeluar();

        return response()->json([
            'message' => 'Barang Keluar Ditemukan',
            'barang' => $getBarangKeluar
        ], 201);
    }

 
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_barang_keluar'     => 'required|string|min:2|max:100',
            'total'                => 'required|integer|min:1|max:100',
            'tanggal'               => 'required',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $data = json_decode($request->getContent(), true);
        $arrayDetail = $data['barang_keluar_detail'];
        // dd($data);
        $checkBarangMasukByCode = BarangKeluar::checkBarangMasukByCode($data['kode_barang_keluar']);
         
        if(!empty($checkBarangMasukByCode)){ 
            return response()->json([
                'message' => 'Barang Gagal Ditambahkan Kode Barang Keluar Duplicate',
  
            ], 201);
		}

        $detailNum =0;
        foreach ($arrayDetail as $i => $q) {
        $getBarangByCodeKeluar = Barang::getBarangByCodeKeluar($arrayDetail[$i]['kode_barang'],$arrayDetail[$i]['jumlah']);

            $detailArray = array(
                                 'kode_barang_keluar_header'     => $data['kode_barang_keluar'],
                                 'kode_barang'                  => $arrayDetail[$i]['kode_barang'],
                                 'jumlah'                       => $arrayDetail[$i]['jumlah'],
                                 'stock_akhir'                  => $getBarangByCodeKeluar[0]->stock_akhir_temp,
                                 'created_by'                   => Auth::user()->id
                                );
            
        Barang::updStockBarang($detailArray);
        $response       = BarangKeluar::insBarangKeluarDetail($detailArray);
        $detailNum++;
        }
        
        $headArray = array(
            'kode_barang_keluar'            => $data['kode_barang_keluar'],
            'total'                        => $data['total'],
            'tanggal'                      => $data['tanggal'],
            'created_by'                   => Auth::user()->id
           );

        $response       = BarangKeluar::insBarangKeluarHeader($headArray);

    
        if ($response ==false) {
            return response()->json([
                'message' => 'Barang Keluar Gagal Ditambahkan',
                'barang_keluar_head' => $headArray,
                 'barang_keluar_detail' => $detailArray
            ], 201);
        }else{
            return response()->json([
                'message' => 'Barang Keluar Berhasil Ditambahkan',
                'barang_keluar_head' => $headArray,
                'barang_keluar_detail' => $detailArray
            ], 201);
        }
    }

 
    public function show($id)
    {
        $getBarangKeluarById             = BarangKeluar::getBarangKeluarById($id);
        if(empty($getBarangKeluarById)){ 
            return response()->json([
                'message' => 'Barang Keluar Tidak Ditemukan'
            ], 201);
		}else{
            return response()->json([
                'message' => 'Barang Keluar Ditemukan',
                'barang_masuk' => $getBarangKeluarById
            ], 201);

        }

        
    }


    public function update(Request $request, $id)
    {

         
  
          $validator = Validator::make($request->all(), [
            'kode_barang_keluar'     => 'required|string|min:2|max:100',
            'total'                 => 'required|integer|min:1|max:100',
            'tanggal'               => 'required',
              
          ]);
  
          if ($validator->fails()) {
          
            return response()->json($validator->errors(), 400);
          }
          $data = json_decode($request->getContent(), true);
          $arrayDetail = $data['barang_keluar_detail'];
        

          $detailNum =0;
          foreach ($arrayDetail as $i => $q) {
            $getBarangByCodeEdit    = BarangKeluar::getBarangKeluarDetailByCodeEdit($data['kode_barang_keluar'],$arrayDetail[$i]['kode_barang'],$arrayDetail[$i]['jumlah']);
         
  
              $detailArray = array(
                                   'kode_barang_keluar_header'     => $data['kode_barang_keluar'],
                                   'kode_barang'                  => $arrayDetail[$i]['kode_barang'],
                                   'jumlah'                       => $arrayDetail[$i]['jumlah'],
                                   'id'                           => $arrayDetail[$i]['id'],
                                   'stock_akhir'                  => $getBarangByCodeEdit[0]->jumlah_temp,
                                   'updated_by'                   => Auth::user()->id
                                  );
                   
              
          Barang::updStockBarang($detailArray);
          $response       = BarangKeluar::updBarangMasukDetail($detailArray);
    
          $detailNum++;
          }
          
          $headArray = array(
            'kode_barang_keluar'              => $data['kode_barang_keluar'],
              'total'                        => $data['total'],
              'tanggal'                      => $data['tanggal'],
              'updated_by'                   => Auth::user()->id
             );
  
          $response       = BarangKeluar::updBarangKeluarHeader($headArray);


            if ($response ==true) {
                return response()->json([
                    'message'               => 'Barang Keluar Berhasil Diupdate',
                    'Barang_keluar_head'     => $headArray,
                    'Barang_keluar_detail'   => $detailArray
                ], 201);
            }else{
                return response()->json([
                    'message'                   => 'Barang Keluar Gagal Diupdate',
                    'Barang_keluar_head'         => $headArray,
                    'Barang_keluar_detail'       => $detailArray
                ], 201);
            }
    }


    public function destroy($id)
    {
        $getBarangMasukDetail   = BarangMasuk::getBarangMasukById($id);
        for ($x=0; $x < count($getBarangMasukDetail); $x++) {
            $kode_barang                        = $getBarangMasukDetail[$x]->kode_barang;
            $jumlah                             = $getBarangMasukDetail[$x]->jumlah;
            $getBarangByCodeDelete              = Barang::getBarangByCodeDelete($kode_barang,$jumlah);
            $detailArray = array(
                'stock_akhir'                  => $getBarangByCodeDelete[0]->stock_akhir_temp,
                'created_by'                   => Auth::user()->id
               );
      
            Barang::updStockBarang($detailArray);
           
        }

        $response               = BarangMasuk::delBarangMasukHeader($id);
        $response               = BarangMasuk::delBarangMasukDetail($id);
  
        if ($response ==true) {
            return response()->json([
                'message' => 'Barang Berhasil Dihapus'
            ], 201);
        }else{
            return response()->json([
                'message' => 'Barang Gagal Dihapus'
            ], 201);
        }
    }
}
