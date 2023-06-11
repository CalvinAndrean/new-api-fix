<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CashPromo;
use Validator;
use Illuminate\Support\Facades\DB;

class CashPromoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $CashPromo = CashPromo::all(); // mengambil semua data member

        if(count($CashPromo) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $CashPromo
            ], 200);
        } //Return data semua Member dalam bentuk JSON

        return response([
            'message' => 'Tidak ada CashPromo',
            'data' => null
        ], 400); //Return message data CashPromo kosong
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $storeData = $request->all(); // mengambil semua input dari web
        $validate = Validator::make($storeData, [
            'min_topup'=>'required',
            'min_deposit' => 'required',
            'bonus' => 'required'
        ]);

        if ($validate->fails()) {
            $firstError = $validate->errors()->first();
        
            return response(['message' => $firstError], 400);
        }

        $count = DB::table('cash_promos')->count() +1;

        $CashPromo = CashPromo::create([
            'min_topup' => $request->min_topup,
            'min_deposit' => $request->min_deposit,
            'bonus' => $request->bonus
        ]);

        return response([
            'message' => 'Add Cash Promo Success',
            'data' => $CashPromo
        ], 200); //Return message data CashPromo baru dalam bentuk JSON
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_cash_promo)
    {
        $CashPromo = CashPromo::find($id_cash_promo); //Mencari data CashPromo berdasarkan id

        if(!is_null($CashPromo)){
            return response([
                'message' => 'Retrieve CashPromo Success',
                'data' => $CashPromo
            ], 200);
        } //Return data semua Member dalam bentuk JSON

        return response([
            'message' => 'CashPromo Not Found',
            'data' => null
        ], 400); //Return message data CashPromo kosong
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id_cash_promo)
    {
        $CashPromo = CashPromo::find($id_cash_promo); //Mencari data Member berdasarkan id

        if(is_null($CashPromo)){
            return response([
                'message' => 'CashPromo Not Found',
                'data' => null
            ], 404);
        } //Return message saat data Member tidak ditemukan

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'min_topup'=>'required',
            'min_deposit' => 'required',
            'bonus' => 'required'
        ]);
        // Bisa ditambahin rule validasi input
            // 'nama_customer.regex' => 'Inputan tidak boleh mengandung angka atau simbol lain',
            // 'tgl_lahir.date_format' => 'Seharusnya YYYY-MM-DD',
            // 'email_customer.email' => 'Format email salah',
            // 'upload_berkas.mimes' => 'Hanya bisa menampung jpg, png, jpeg saja',
            // 'upload_berkas.image' => 'Hanya bisa menerima image',
            // 'nomor_kartupengenal' => 'Hanya bisa menerima angka',
            // 'no_sim.numeric' => 'Hanya bisa menerima angka',
            // 'usia_customer.numeric' => 'Hanya bisa menerima angka'


        if ($validate->fails()) {
            $firstError = $validate->errors()->first();
        
            return response(['message' => $firstError], 400);
        }

        $CashPromo->min_topup = $updateData['min_topup'];
        $CashPromo->min_deposit = $updateData['min_deposit'];
        $CashPromo->bonus = $updateData['bonus'];

        if($CashPromo->save()){
            return response([
                'message' => 'Update CashPromo Success',
                'data' => $CashPromo
            ], 200);
        } //Return data Member yang telah di EDIT dalam bentuk JSON

        return response([
            'message' => 'Update CashPromo Failed',
            'data' => null
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_cash_promo)
    {
        $CashPromo = CashPromo::find($id_cash_promo); //Mencari data product berdasarkan id

        if(is_null($CashPromo)){
            return response([
                'message' => 'CashPromo Not Found',
                'date' => null
            ], 404);
        } //Return message saat data CashPromo tidak ditemukan

        if($CashPromo->delete()){
            return response([
                'message' => 'Delete CashPromo Success',
                'data' => $CashPromo
            ], 200);
        } //Return message saat berhasil menghapus data CashPromo

        return response([
            'message' => 'Delete CashPromo Failed',
            'data' => null,
        ], 400);
    }
}
