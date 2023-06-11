<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassPromo;
use Validator;
use Illuminate\Support\Facades\DB;

class ClassPromoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ClassPromo = ClassPromo::with(['class_details'])->get(); // mengambil semua data member

        if(count($ClassPromo) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $ClassPromo
            ], 200);
        } //Return data semua Member dalam bentuk JSON

        return response([
            'message' => 'Tidak ada ClassPromo',
            'data' => null
        ], 400); //Return message data ClassPromo kosong
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
            'id_class'=>'required',
            'total_price' => 'required',
            'amount_deposit' => 'required',
            'duration' => 'required',
            'bonus' => 'required'
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $ClassPromo = ClassPromo::create([
            'id_class' => $request->id_class,
            'total_price' => $request->total_price,
            'amount_deposit' => $request->amount_deposit,
            'duration' => $request->duration,
            'bonus' => $request->bonus
        ]);

        return response([
            'message' => 'Add ClassPromo Success',
            'data' => $ClassPromo
        ], 200); //Return message data ClassPromo baru dalam bentuk JSON
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_class_promo)
    {
        $ClassPromo = ClassPromo::find($id_class_promo); //Mencari data CashPromo berdasarkan id

        if(!is_null($ClassPromo)){
            return response([
                'message' => 'Retrieve ClassPromo Success',
                'data' => $ClassPromo
            ], 200);
        } //Return data semua ClassPromo dalam bentuk JSON

        return response([
            'message' => 'ClassPromo Not Found',
            'data' => null
        ], 400); //Return message data ClassPromo kosong
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
    public function update(Request $request, $id_class_promo)
    {
        $ClassPromo = ClassPromo::find($id_class_promo); //Mencari data Member berdasarkan id

        if(is_null($ClassPromo)){
            return response([
                'message' => 'ClassPromo Not Found',
                'data' => null
            ], 404);
        } //Return message saat data Member tidak ditemukan

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'total_price' => 'required',
            'amount_deposit' => 'required',
            'duration' => 'required',
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


        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $ClassPromo->total_price = $updateData['total_price'];
        $ClassPromo->amount_deposit = $updateData['amount_deposit'];
        $ClassPromo->duration = $updateData['duration'];
        $ClassPromo->bonus = $updateData['bonus'];

        if($ClassPromo->save()){
            return response([
                'message' => 'Update ClassPromo Success',
                'data' => $ClassPromo
            ], 200);
        } //Return data ClassPromo yang telah di EDIT dalam bentuk JSON

        return response([
            'message' => 'Update ClassPromo Failed',
            'data' => null
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_class_promo)
    {
        $ClassPromo = ClassPromo::find($id_class_promo); //Mencari data product berdasarkan id

        if(is_null($ClassPromo)){
            return response([
                'message' => 'ClassPromo Not Found',
                'date' => null
            ], 404);
        } //Return message saat data ClassPromo tidak ditemukan

        if($ClassPromo->delete()){
            return response([
                'message' => 'Delete ClassPromo Success',
                'data' => $ClassPromo
            ], 200);
        } //Return message saat berhasil menghapus data ClassPromo

        return response([
            'message' => 'Delete ClassPromo Failed',
            'data' => null,
        ], 400);
    }
}
