<?php

namespace App\Http\Controllers;
use Auth;
use Validator;
use App\Models\Category_barang;
use Illuminate\Http\Request;

class CategoryBarangController extends Controller
{
  

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function index()
    {
        $getCatBarang = Category_barang::getCatBarang();

        return response()->json([
            'message' => 'Category Barang Ditemukan',
            'Category_barang' => $getCatBarang
        ], 201);
    }

 
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'deskripsi' => 'required|string|min:2|max:100',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $Category_barang = Category_barang::create([
                'deskripsi' => $request->deskripsi
            ]);

        return response()->json([
            'message' => 'Category Barang successfully registered',
            'Category_barang' => $Category_barang
        ], 201);
    }

 
    public function show($id)
    {
        $Category_barang = Category_barang::getCatBarangById($id);

        return response()->json([
            'message' => 'Category Barang Ditemukan',
            'Category_barang' => $Category_barang
        ], 201);
    }


    public function update(Request $request, $id)
    {

          $messages = [
            'deskripsi.required' => 'Tidak boleh kosong.'
          ];
  
          $validator = Validator::make($request->all(), [
                  'deskripsi' => 'required'
              
          ], $messages);
  
          if ($validator->fails()) {
          
            return response()->json($validator->errors(), 400);
          }
  
          $params = array(
            'deskripsi'                       => $request->deskripsi, 
            'id'                              => $request->id
            );
            
            $response = Category_barang::updCatBarang($params);
            if ($response ==true) {
                return response()->json([
                    'message' => 'Category Barang Berhasil Diupdate',
                    'CatBarang' => $params
                ], 201);
            }else{
                return response()->json([
                    'message' => 'Category Barang Gagal Diupdate',
                    'CatBarang' => $params
                ], 201);
            }
    }


    public function destroy($id)
    {
        $response  = Category_barang::delCatBarang($id);
        if ($response ==true) {
            return response()->json([
                'message' => 'Category Barang Berhasil Dihapus'
            ], 201);
        }else{
            return response()->json([
                'message' => 'Category Barang Gagal Dihapus'
            ], 201);
        }
    }
}
