<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $instructors = Instructor::all(); // mengambil semua data instructor

        if(count($instructors) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $instructors
            ], 200);
        } //Return data semua Instructor dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //Return message data Instructor kosong
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
            'email' => 'required|email',
            'password' => 'required',
            'birth_date' => 'required|date|before:today',
            'fullname' => 'required',
            'address' => 'required',
            'phone_number' => 'required|regex:/^08[0-9]{10}$/',
        ]);

        if ($validate->fails()) {
            $firstError = $validate->errors()->first();
        
            return response(['message' => $firstError], 400);
        }

        $latest = DB::table('instructors')->latest()->first();  //23.04.001
        if($latest == null){
            $incrementing = 1;
        } else{
            $incrementing = ((int)Str::substr($latest->id_instructor, 6, 3)) + 1;
        }

        // $count = DB::table('members')->count() +1;
        if($incrementing < 10){
            $incrementing = '00'.$incrementing;
        } else if($incrementing < 100){
            $incrementing = '0'.$incrementing;
        }

        // $count = DB::table('instructors')->count() +1;
        // if($count < 10){
        //     $count = '00'.$count;
        // } else if($count < 100){
        //     $count = '0'.$count;
        // }

        $curdate = Carbon::now()->format('Y-m-d');
        $month = Str::substr($curdate, 5, 2);
        $year = Str::substr($curdate, 2, 2);
        Str::substr($year, -2);
        $expired = Carbon::now()->addYear();
        $total_late = "00:00";

        $Instructor = Instructor::create([
            'id_instructor'=> $year.'.'.$month.'.'.$incrementing,
            'email'=>$request->email,
            'password'=>$request->password,
            'fullname'=>$request->fullname,
            'birth_date'=>$request->birth_date,
            'address'=>$request->address,
            'phone_number'=>$request->phone_number,
            'total_late'=>$total_late
        ]);

        $User = User::create([
            'name'=>$request->fullname,
            'id_user'=>$year.'.'.$month.'.'.$incrementing,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role'=>'Instructor'
        ]);

        return response([
            'message' => 'Add Instructor Success',
            'data' => $Instructor
        ], 200); //Return message data Instructor baru dalam bentuk JSON
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_instructor)
    {
        $instructors = Instructor::find($id_instructor); //Mencari data Instructor berdasarkan id

        if(!is_null($instructors)){
            return response([
                'message' => 'Retrieve Instructor Success',
                'data' => $instructors
            ], 200);
        } //Return data semua Instructor dalam bentuk JSON

        return response([
            'message' => 'Instructor Not Found',
            'data' => null
        ], 400); //Return message data Instructor kosong
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
    public function update(Request $request, $id_instructor)
    {
        $Instructor = Instructor::find($id_instructor); //Mencari data Member berdasarkan id
        $User = User::where('id_user', $id_instructor)->first();

        if(is_null($Instructor)){
            return response([
                'message' => 'Instructor Not Found',
                'data' => null
            ], 404);
        } //Return message saat data Instructor tidak ditemukan

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'email' => 'required|email',
            'password' => 'required',
            'birth_date' => 'required|date|before:today',
            'fullname' => 'required',
            'address' => 'required',
            'phone_number' => 'required|regex:/^08[0-9]{10}$/',
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

        $User->email = $updateData['email'];
        $User->password = Hash::make($updateData['password']);
        $User->name = $updateData['fullname'];
        $User->save();

        $Instructor->email = $updateData['email'];
        $Instructor->password = $updateData['password'];
        $Instructor->birth_date = $updateData['birth_date'];
        $Instructor->fullname = $updateData['fullname'];
        $Instructor->address = $updateData['address'];
        $Instructor->phone_number = $updateData['phone_number'];


        if($Instructor->save()){
            return response([
                'message' => 'Update Instructor Success',
                'data' => $Instructor
            ], 200);
        } //Return data Instructor yang telah di EDIT dalam bentuk JSON

        return response([
            'message' => 'Update Instructor Failed',
            'data' => null
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_instructor)
    {
        $Instructor = Instructor::find($id_instructor); //Mencari data product berdasarkan id

        if(is_null($Instructor)){
            return response([
                'message' => 'Instructor Not Found',
                'date' => null
            ], 404);
        } //Return message saat data Instructor tidak ditemukan

        if($Instructor->delete()){
            return response([
                'message' => 'Delete Instructor Success',
                'data' => $Instructor
            ], 200);
        } //Return message saat berhasil menghapus data Instructor

        return response([
            'message' => 'Delete Instructor Failed',
            'data' => null,
        ], 400);
    }

    public function resetTotalLate($id_instructor){
        $instructor = Instructor::all();
        foreach($instructor as $data){
            $data->total_late = '00:00:00';
            $data->save();
        }
        return response([
            'message' => 'Reset Total Late Success',
            'data' => $instructor,
        ], 200);
    }

    public function updatePassword(Request $request, $id_instructor){
        $instructor = Instructor::find($id_instructor);
        $User = User::where('id_user', $id_instructor)->first();

        if(is_null($instructor)){
            return response([
                'message' => 'instructor Not Found',
                'data' => null
            ], 404);
        } //Return message saat data Pegawai tidak ditemukan

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'password' => 'required'
        ]);

        if($validate->fails()){
            // return response(['message' => $validate->errors()], 400); //Return error invalid input
            return response([
                'message' => $validate->errors(),
                'data' => $request->password
            ], 400);
        }

        $instructor->password = Hash::make($updateData['password']);
        $User->password = Hash::make($instructor->password);

        if($instructor->save()){
            $User->save();
            return response([
                'message' => 'Update Password instructor Success',
                'data' => $instructor
            ], 200);
        } //Return data Pegawai yang telah di EDIT dalam bentuk JSON

        return response([
            'message' => 'Update Password instructor Failed',
            'data' => null
        ], 400);
    }

    // history instructor isinya :
    // dia ngajar kelas apa aja + statusnya Libur ato masuk -> ClassRunningDaily where id = id AND status != Libur
    // dia pernah izin apa aja -> udah ada fragment izin nya -> InstructorAbsent -> where confirmed != NULL
    // sekalian bikin change password
}
