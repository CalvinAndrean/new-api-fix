<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassRunning;
use App\Models\Instructor;
use App\Models\ClassDetails;
use Illuminate\Support\Str;
use Validator;
use Illuminate\Support\Facades\DB;

class ClassRunningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $ClassRunning = ClassRunning::all(); // mengambil semua data member
        $ClassRunning = ClassRunning::with(['instructor', 'class_details'])->get();

        if(count($ClassRunning) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $ClassRunning
            ], 200);
        } //Return data semua ClassRunning dalam bentuk JSON

        return response([
            'message' => 'Tidak ada ClassRunning',
            'data' => null
        ], 400); //Return message data ClassRunning kosong
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
            'id_instructor' => 'required',
            'id_class' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'day' => 'required',
            'slot' => 'required',
        ]);

        if ($validate->fails()) {
            $firstError = $validate->errors()->first();
        
            return response(['message' => $firstError], 400);
        }

        $data = ClassRunning::all();
        foreach($data as $check){
            if(($check->id_instructor == $request->id_instructor) && 
               (Str::substr($check->start_time, 0, 5) == $request->start_time) &&
               ($check->id_class == $request->id_class) &&
               ($check->day == $request->day)){
                return response([
                    'message' => 'Jadwal Bertabrakan',
                    'data' => $check
                ], 400);
            }
        }

        $ClassRunning = ClassRunning::create([
            'id_instructor' => $request->id_instructor,
            'id_class' => $request->id_class,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'day' => $request->day,
            'slot' => $request->slot,
        ]);

        return response([
            'message' => 'Add ClassRunning Success',
            'data' => $ClassRunning
        ], 200); //Return message data ClassRunning baru dalam bentuk JSON
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_class_running)
    {
        $ClassRunning = ClassRunning::find($id_class_running); //Mencari data CashPromo berdasarkan id

        if(!is_null($ClassDetails)){
            return response([
                'message' => 'Retrieve ClassRunning Success',
                'data' => $ClassRunning
            ], 200);
        } //Return data semua ClassRunning dalam bentuk JSON

        return response([
            'message' => 'ClassRunning Not Found',
            'data' => null
        ], 400); //Return message data ClassRunning kosong
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
    public function update(Request $request, $id_class_running)
    {
        $ClassRunning = ClassRunning::find($id_class_running); //Mencari data Member berdasarkan id

        if(is_null($ClassRunning)){
            return response([
                'message' => 'ClassRunning Not Found',
                'data' => null
            ], 404);
        } //Return message saat data ClassRunning tidak ditemukan

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_instructor' => 'required',
            'id_class' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'day' => 'required',
            'slot' => 'required',
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

        $data = ClassRunning::all();
        foreach($data as $check){
            if(($check->id_instructor == $request->id_instructor) && 
               (Str::substr($check->start_time, 0, 5) == $request->start_time) &&
               ($check->id_class == $request->id_class) &&
               ($check->day == $request->day)){
                return response([
                    'message' => 'Jadwal Bertabrakan',
                    'data' => $check
                ], 400);
            }
        }

        $ClassRunning->id_instructor = $updateData['id_instructor'];
        $ClassRunning->id_class = $updateData['id_class'];
        $ClassRunning->start_time = $updateData['start_time'];
        $ClassRunning->end_time = $updateData['end_time'];
        $ClassRunning->slot = $updateData['slot'];
        $ClassRunning->day = $updateData['day'];

        if($ClassRunning->save()){
            return response([
                'message' => 'Update ClassRunning Success',
                'data' => $ClassRunning
            ], 200);
        } //Return data ClassRunning yang telah di EDIT dalam bentuk JSON

        return response([
            'message' => 'Update ClassRunning Failed',
            'data' => null
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_class_running)
    {
        $ClassRunning = ClassRunning::find($id_class_running); //Mencari data product berdasarkan id

        if(is_null($ClassRunning)){
            return response([
                'message' => 'ClassRunning Not Found',
                'date' => null
            ], 404);
        } //Return message saat data ClassRunning tidak ditemukan

        if($ClassRunning->delete()){
            return response([
                'message' => 'Delete ClassRunning Success',
                'data' => $ClassRunning
            ], 200);
        } //Return message saat berhasil menghapus data ClassRunning

        return response([
            'message' => 'Delete ClassRunning Failed',
            'data' => null,
        ], 400);
    }
}
