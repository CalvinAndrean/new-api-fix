<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassDetails;
use Validator;
use Illuminate\Support\Facades\DB;

class ClassDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ClassDetails = ClassDetails::all(); // mengambil semua data member

        if(count($ClassDetails) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $ClassDetails
            ], 200);
        } //Return data semua ClassDetails dalam bentuk JSON

        return response([
            'message' => 'Tidak ada ClassDetails',
            'data' => null
        ], 400); //Return message data ClassDetails kosong
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
            'class_name'=>'required',
            'price' => 'required'
        ]);

        if ($validate->fails()) {
            $firstError = $validate->errors()->first();
        
            return response(['message' => $firstError], 400);
        }

        $count = DB::table('class_details')->count() +1;

        $ClassDetails = ClassDetails::create([
            'class_name' => $request->class_name,
            'price' => $request->price
        ]);

        return response([
            'message' => 'Add ClassDetails Success',
            'data' => $ClassDetails
        ], 200); //Return message data ClassDetails baru dalam bentuk JSON
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_class)
    {
        $ClassDetails = ClassDetails::find($id_class); //Mencari data CashPromo berdasarkan id

        if(!is_null($ClassDetails)){
            return response([
                'message' => 'Retrieve ClassDetails Success',
                'data' => $ClassDetails
            ], 200);
        } //Return data semua ClassDetails dalam bentuk JSON

        return response([
            'message' => 'ClassDetails Not Found',
            'data' => null
        ], 400); //Return message data ClassDetails kosong
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
    public function update(Request $request, $id_class)
    {
        $ClassDetails = ClassDetails::find($id_class); //Mencari data Member berdasarkan id

        if(is_null($ClassDetails)){
            return response([
                'message' => 'ClassDetails Not Found',
                'data' => null
            ], 404);
        } //Return message saat data ClassDetails tidak ditemukan

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'class_name'=>'required',
            'price' => 'required'
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

        $ClassDetails->min_topup = $updateData['class_name'];
        $ClassDetails->min_deposit = $updateData['price'];

        if($ClassDetails->save()){
            return response([
                'message' => 'Update ClassDetails Success',
                'data' => $ClassDetails
            ], 200);
        } //Return data ClassDetails yang telah di EDIT dalam bentuk JSON

        return response([
            'message' => 'Update ClassDetails Failed',
            'data' => null
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_class)
    {
        $ClassDetails = ClassDetails::find($id_class); //Mencari data product berdasarkan id

        if(is_null($ClassDetails)){
            return response([
                'message' => 'ClassDetails Not Found',
                'date' => null
            ], 404);
        } //Return message saat data ClassDetails tidak ditemukan

        if($ClassDetails->delete()){
            return response([
                'message' => 'Delete ClassDetails Success',
                'data' => $ClassDetails
            ], 200);
        } //Return message saat berhasil menghapus data ClassDetails

        return response([
            'message' => 'Delete ClassDetails Failed',
            'data' => null,
        ], 400);
    }
}
