<?php

namespace App\Http\Controllers;
use Auth;
use Validator;
use App\Models\Barang;
use App\Models\Category_barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
  

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function index()
    {
        $getBarang = Barang::getBarang();

        return response()->json([
            'message' => 'Barang Ditemukan',
            'barang' => $getBarang
        ], 201);
    }

 
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_barang' => 'required|string|min:2|max:100',
            'category_id' => 'required|integer|min:1|max:100',
            'nama_barang' => 'required|string|min:2|max:100',
            'stock_awal' => 'required|integer|min:1|max:100',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
       

        $getCategoryId = Category_barang::getCatBarangById($request->category_id);

        $barang = array(
                    'kode_barang'       => $request->kode_barang,
                    'category_id'       => $request->category_id,
                    'nama_barang'       => $request->nama_barang,
                    'stock_awal'        => $request->stock_awal,
                    'created_by'        => Auth::user()->id
        );
        $checkBarangByCode = Barang::checkBarangByCode($request->kode_barang);
         
        if(!empty($checkBarangByCode)){ 
            return response()->json([
                'message' => 'Barang Gagal Ditambahkan Kode Barang Duplicate',
                'Barang' => $barang,
                'Cat_barang' => $getCategoryId
            ], 201);
		}

        $response = Barang::insBarang($barang);
       

        if ($response ==false) {
            return response()->json([
                'message' => 'Barang Gagal Ditambahkan',
                'Barang' => $barang,
                'Cat_barang' => $getCategoryId
            ], 201);
        }else{
            return response()->json([
                'message' => 'Barang Berhasil Ditambahkan',
                'Barang' => $barang,
                'Cat_barang' => $getCategoryId
            ], 201);
        }
    }

 
    public function show($id)
    {
        $getBarangById          = Barang::getBarangById($id);
        $catId                  = $getBarangById[0]->category_id;
        $getCategoryId          = Category_barang::getCatBarangById($catId);

        return response()->json([
            'message' => 'Category Barang Ditemukan',
            'Barang' => $getBarangById,
            'Category_barang' => $getCategoryId
        ], 201);
    }


    public function update(Request $request, $id)
    {

          $messages = [
              
            'category_id.required' => 'Tidak boleh kosong.',
            'nama_barang.required' => 'Tidak boleh kosong.',
            'stock_awal.required' => 'Tidak boleh kosong.'
          ];
  
          $validator = Validator::make($request->all(), [
                  'category_id' => 'required',
                  'nama_barang' => 'required',
                  'stock_awal' => 'required'


              
          ], $messages);
  
          if ($validator->fails()) {
          
            return response()->json($validator->errors(), 400);
          }
  
          $params = array(
            'category_id'        => $request->category_id, 
            'nama_barang'        => $request->nama_barang,
            'stock_awal'         => $request->stock_awal,
            'id'                 => $id, 
            );
            
            $response = Barang::updBarang($params);
            $getBarangById          = Barang::getBarangById($id);
            $catId                  = $getBarangById[0]->category_id;
            $getCategoryId          = Category_barang::getCatBarangById($catId);
            if ($response ==true) {
                return response()->json([
                    'message' => 'Barang Berhasil Diupdate',
                    'Barang' => $params,
                    'Category_barang' => $getCategoryId
                ], 201);
            }else{
                return response()->json([
                    'message' => 'Category Barang Gagal Diupdate',
                    'Barang' => $params,
                    'Category_barang' => $getCategoryId
                ], 201);
            }
    }


    public function destroy($id)
    {
        $response  = Barang::delBarang($id);
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
