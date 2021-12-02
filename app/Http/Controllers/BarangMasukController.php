<?php

namespace App\Http\Controllers;
use Auth;
use Validator;
use App\Models\Barang;
use App\Models\Category_barang;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
  

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function index()
    {
        $getBarangMasuk = BarangMasuk::getBarangMasuk();

        return response()->json([
            'message' => 'Barang Masuk Ditemukan',
            'barang' => $getBarangMasuk
        ], 201);
    }

 
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_barang_masuk'     => 'required|string|min:2|max:100',
            'total'                => 'required|integer|min:1|max:100',
            'tanggal'               => 'required',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $data = json_decode($request->getContent(), true);
        $arrayDetail = $data['barang_masuk_detail'];
        // dd($data);
        $checkBarangMasukByCode = BarangMasuk::checkBarangMasukByCode($data['kode_barang_masuk']);
         
        if(!empty($checkBarangMasukByCode)){ 
            return response()->json([
                'message' => 'Barang Gagal Ditambahkan Kode Barang Masuk Duplicate',
  
            ], 201);
		}

        $detailNum =0;
        foreach ($arrayDetail as $i => $q) {
        $getBarangByCode = Barang::getBarangByCode($arrayDetail[$i]['kode_barang'],$arrayDetail[$i]['jumlah']);

            $detailArray = array(
                                 'kode_barang_masuk_header'     => $data['kode_barang_masuk'],
                                 'kode_barang'                  => $arrayDetail[$i]['kode_barang'],
                                 'jumlah'                       => $arrayDetail[$i]['jumlah'],
                                 'stock_akhir'                  => $getBarangByCode[0]->stock_akhir_temp,
                                 'created_by'                   => Auth::user()->id
                                );
            
        Barang::updStockBarang($detailArray);
        $response       = BarangMasuk::insBarangMasukDetail($detailArray);
        $detailNum++;
        }
        
        $headArray = array(
            'kode_barang_masuk'            => $data['kode_barang_masuk'],
            'total'                        => $data['total'],
            'tanggal'                      => $data['tanggal'],
            'created_by'                   => Auth::user()->id
           );

        $response       = BarangMasuk::insBarangMasukHeader($headArray);

    
        if ($response ==false) {
            return response()->json([
                'message' => 'Barang Masuk Gagal Ditambahkan',
                'barang_masuk_head' => $headArray,
                 'barang_masuk_detail' => $detailArray
            ], 201);
        }else{
            return response()->json([
                'message' => 'Barang Masuk Berhasil Ditambahkan',
                'barang_masuk_head' => $headArray,
                'barang_masuk_detail' => $detailArray
            ], 201);
        }
    }

 
    public function show($id)
    {
        $getBarangMasukById             = BarangMasuk::getBarangMasukById($id);
        if(empty($getBarangMasukById)){ 
            return response()->json([
                'message' => 'Barang Masuk Tidak Ditemukan'
            ], 201);
		}

        return response()->json([
            'message' => 'Barang Masuk Ditemukan',
            'barang_masuk' => $getBarangMasukById
        ], 201);
    }


    public function update(Request $request, $id)
    {

         
  
          $validator = Validator::make($request->all(), [
            'kode_barang_masuk'     => 'required|string|min:2|max:100',
            'total'                 => 'required|integer|min:1|max:100',
            'tanggal'               => 'required',
              
          ]);
  
          if ($validator->fails()) {
          
            return response()->json($validator->errors(), 400);
          }
          $data = json_decode($request->getContent(), true);
          $arrayDetail = $data['barang_masuk_detail'];
        

          $detailNum =0;
          foreach ($arrayDetail as $i => $q) {
            $getBarangByCodeEdit    = BarangMasuk::getBarangMasukDetailByCodeEdit($data['kode_barang_masuk'],$arrayDetail[$i]['kode_barang'],$arrayDetail[$i]['jumlah']);
         
  
              $detailArray = array(
                                   'kode_barang_masuk_header'     => $data['kode_barang_masuk'],
                                   'kode_barang'                  => $arrayDetail[$i]['kode_barang'],
                                   'jumlah'                       => $arrayDetail[$i]['jumlah'],
                                   'id'                           => $arrayDetail[$i]['id'],
                                   'stock_akhir'                  => $getBarangByCodeEdit[0]->jumlah_temp,
                                   'updated_by'                   => Auth::user()->id
                                  );
                   
              
          Barang::updStockBarang($detailArray);
          $response       = BarangMasuk::updBarangMasukDetail($detailArray);
    
          $detailNum++;
          }
          
          $headArray = array(
            'kode_barang_masuk'              => $data['kode_barang_masuk'],
              'total'                        => $data['total'],
              'tanggal'                      => $data['tanggal'],
              'updated_by'                   => Auth::user()->id
             );
  
          $response       = BarangMasuk::updBarangMasukHeader($headArray);


            if ($response ==true) {
                return response()->json([
                    'message'               => 'Barang Masuk Berhasil Diupdate',
                    'Barang_masuk_head'     => $headArray,
                    'Barang_masuk_detail'   => $detailArray
                ], 201);
            }else{
                return response()->json([
                    'message'                   => 'Barang Masuk Gagal Diupdate',
                    'Barang_masuk_head'         => $headArray,
                    'Barang_masuk_detail'       => $detailArray
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
