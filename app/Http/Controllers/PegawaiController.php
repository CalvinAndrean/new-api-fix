<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pegawais = Pegawai::all(); // mengambil semua data pegawai

        if(count($pegawais) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $pegawais
            ], 200);
        } //Return data semua Pegawai dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //Return message data Pegawai kosong
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
            'email'=>'required|email',
            'password' => 'required',
            'fullname' => 'required',
            'address' => 'required',
            'phone_number' => 'required|regex:/^08[0-9]{10}$/',
            'role' => 'required|in:MO,Kasir,Admin',
        ]);

        if ($validate->fails()) {
            $firstError = $validate->errors()->first();
        
            return response(['message' => $firstError], 400);
        }

        $latest = DB::table('pegawais')->latest()->first();  //P01
        if($latest == null){
            $incrementing = 1;
        } else{
            $incrementing = ((int)Str::substr($latest->id_pegawai, 1, 2)) + 1;
        }

        // $count = DB::table('members')->count() +1;
        if($incrementing < 10){
            $incrementing = '0'.$incrementing;
        }

        // $count = DB::table('pegawais')->count() +1;
        // if($count < 10){
        //     $count = '0'.$count;
        // }

        $pegawai = Pegawai::create([
            'id_pegawai'=> 'P'.$incrementing,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'fullname'=>$request->fullname,
            'address'=>$request->address,
            'phone_number'=>$request->phone_number,
            'role'=>$request->role // Cuma bisa Admin, Kasir atau MO
        ]);

        $User = User::create([
            'name'=>$request->fullname,
            'email'=>$request->email,
            'id_user'=>'P'.$incrementing,
            'password'=>Hash::make($request->password),
            'role'=>$request->role
        ]);

        return response([
            'message' => 'Add Pegawai Success',
            'data' => $pegawai
        ], 200); //Return message data Pegawai baru dalam bentuk JSON
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_pegawai)
    {
        $pegawais = Pegawai::find($id_pegawai); //Mencari data Pegawai berdasarkan id

        if(!is_null($id_pegawai)){
            return response([
                'message' => 'Retrieve Pegawai Success',
                'data' => $pegawais
            ], 200);
        } //Return data semua Pegawai dalam bentuk JSON

        return response([
            'message' => 'Pegawai Not Found',
            'data' => null
        ], 400); //Return message data Pegawai kosong
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
    public function update(Request $request, $id_pegawai)
    {
        $Pegawai = Pegawai::find($id_pegawai); //Mencari data Member berdasarkan id
        $User = User::where('id_user', $id_pegawai)->first();
        // $User = User::where('id_user', Auth::user()->id_user)->first(); UNTUK USER YANG MASIH LOGGED IN

        if(is_null($Pegawai)){
            return response([
                'message' => 'Pegawai Not Found',
                'data' => null
            ], 404);
        } else if(is_null($User)){
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        } //Return message saat data Pegawai tidak ditemukan

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'email'=>'required|email',
            'password' => 'required',
            'fullname' => 'required',
            'address' => 'required',
            'phone_number' => 'required|regex:/^08[0-9]{10}$/',
            'role' => 'required|in:MO,Kasir,Admin',
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

        $Pegawai->email = $updateData['email'];
        $Pegawai->password = Hash::make($updateData['password']);
        $Pegawai->address = $updateData['address'];
        $Pegawai->fullname = $updateData['fullname'];
        $Pegawai->phone_number = $updateData['phone_number'];
        $Pegawai->role = $updateData['role'];

        $User->email = $updateData['email'];
        $User->password = Hash::make($updateData['password']);
        $User->name = $updateData['fullname'];
        $User->save();

        if($Pegawai->save()){
            return response([
                'message' => 'Update Pegawai Success',
                'data' => $Pegawai
            ], 200);
        } //Return data Pegawai yang telah di EDIT dalam bentuk JSON

        return response([
            'message' => 'Update Pegawai Failed',
            'data' => null
        ], 400);
    }

    public function updatePassword(Request $request, $id_pegawai){
        $Pegawai = Pegawai::find($id_pegawai);
        $User = User::where('id_user', $id_pegawai)->first();

        if(is_null($Pegawai)){
            return response([
                'message' => 'Pegawai Not Found',
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

        

        $Pegawai->password = $updateData['password'];
        $User->password = Hash::make($Pegawai->password);
        $User->save();

        if($Pegawai->save()){
            return response([
                'message' => 'Update Password Pegawai Success',
                'data' => $Pegawai
            ], 200);
        } //Return data Pegawai yang telah di EDIT dalam bentuk JSON

        return response([
            'message' => 'Update Password Pegawai Failed',
            'data' => null
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_pegawai)
    {
        $Pegawai = Pegawai::find($id_pegawai); //Mencari data product berdasarkan id

        if(is_null($Pegawai)){
            return response([
                'message' => 'Pegawai Not Found',
                'date' => null
            ], 404);
        } //Return message saat data Pegawai tidak ditemukan

        if($Pegawai->delete()){
            return response([
                'message' => 'Delete Pegawai Success',
                'data' => $Pegawai
            ], 200);
        } //Return message saat berhasil menghapus data Pegawai

        return response([
            'message' => 'Delete Pegawai Failed',
            'data' => null,
        ], 400);
    }

    // bikin change password untuk pegawai
}
