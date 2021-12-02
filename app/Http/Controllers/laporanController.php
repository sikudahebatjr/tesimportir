<?php

namespace App\Http\Controllers;
use Auth;
use Validator;
use App\Models\Barang;
use App\Models\Category_barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;

class laporanController extends Controller
{
  

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function laporanStock()
    {
        $getBarang = Barang::getBarang();

        return response()->json([
            'message' => 'Barang Ditemukan',
            'barang' => $getBarang
        ], 201);
    }


    public function laporanMasukPerhari()
    {
        $getBarangMasukPerhari = BarangMasuk::getBarangMasukPerhari();

        return response()->json([
            'message' => 'Barang Masuk Ditemukan',
            'barang' => $getBarangMasukPerhari
        ], 201);
    }

    public function laporanMasukPerminggu()
    {
        $getBarangMasukPerminggu = BarangMasuk::getBarangMasukPerminggu();

        return response()->json([
            'message' => 'Barang Masuk Ditemukan',
            'barang' => $getBarangMasukPerminggu
        ], 201);
    }



    public function laporanMasukPerbulan()
    {
        $getBarangMasukPerbulan = BarangMasuk::getBarangMasukPerbulan();

        return response()->json([
            'message' => 'Barang Masuk Ditemukan',
            'barang' => $getBarangMasukPerbulan
        ], 201);
    }


    public function laporanMasukPertahun()
    {
        $getBarangMasukPertahun = BarangMasuk::getBarangMasukPertahun();

        return response()->json([
            'message' => 'Barang Masuk Ditemukan',
            'barang' => $getBarangMasukPertahun
        ], 201);
    }





    

    public function laporanKeluarPerhari()
    {
        $getBarangKeluarPerhari = BarangKeluar::getBarangKeluarPerhari();

        return response()->json([
            'message' => 'Barang Keluar Ditemukan',
            'barang' => $getBarangKeluarPerhari
        ], 201);
    }

    public function laporanKeluarPerminggu()
    {
        $getBarangKeluarPerminggu = BarangKeluar::getBarangKeluarPerminggu();

        return response()->json([
            'message' => 'Barang Keluar Ditemukan',
            'barang' => $getBarangKeluarPerminggu
        ], 201);
    }



    public function laporanKeluarPerbulan()
    {
        $getBarangKeluarPerbulan = BarangKeluar::getBarangKeluarPerbulan();

        return response()->json([
            'message' => 'Barang Keluar Ditemukan',
            'barang' => $getBarangKeluarPerbulan
        ], 201);
    }


    public function laporanKeluarPertahun()
    {
        $getBarangKeluarPertahun = BarangKeluar::getBarangKeluarPertahun();

        return response()->json([
            'message' => 'Barang Keluar Ditemukan',
            'barang' => $getBarangKeluarPertahun
        ], 201);
    }

}
