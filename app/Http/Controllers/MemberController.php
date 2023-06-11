<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Member;
use App\Models\DepositClass;
use App\Models\ActivationReport;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use PDF;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $members = Member::all(); // mengambil semua data member

        if(count($members) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $members
            ], 200);
        } //Return data semua Member dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //Return message data Member kosong
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

        return response([
            'data' => $storeData
        ], 400);

        $latest = DB::table('members')->latest()->first();  //23.04.001
        if($latest == null){
            $incrementing = 1;
        } else{
            $incrementing = ((int)Str::substr($latest->id_member, 6, 3)) + 1;
        }

        // $count = DB::table('members')->count() +1;
        if($incrementing < 10){
            $incrementing = '00'.$incrementing;
        } else if($incrementing < 100){
            $incrementing = '0'.$incrementing;
        }

        $curdate = Carbon::now()->format('Y-m-d');
        $month = Str::substr($curdate, 5, 2); // 2023-05-20
        $year = Str::substr($curdate, 2, 2);
        Str::substr($year, -2);
        $expired = Carbon::now()->addYear();
        // MM.YY.001

        $Member = Member::create([
            'id_member'=> $year.'.'.$month.'.'.$incrementing,
            'email'=>$request->email,
            'password'=>Hash::make($request->birth_date), // Password == birth_date -- Jadi gaperlu bikin input password
            'birth_date'=>$request->birth_date,
            'fullname'=>$request->fullname,
            'address'=>$request->address,
            'phone_number' => $request->phone_number,
            'cash_deposit' => 0
        ]);

        $latestActivation = DB::table('activation_reports')->latest()->first();
        if($latestActivation == null){
            $num_report = 1;
        } else{
            $num_report = ((int)Str::substr($latestActivation->report_number_activation, 6, 3)) + 1;
        }

        // $count = DB::table('members')->count() +1;
        if($num_report < 10){
            $num_report = '00'.$num_report;
        } else if($num_report < 100){
            $num_report = '0'.$num_report;
        }
        // $Activation = ActivationReport::create([
        //     'report_number_activation'=>$year.'.'.$month.'.'.$num_report,
        //     'id_member'=>$year.'.'.$month.'.'.$incrementing,
        //     'id_pegawai'=>'',
        //     'datetime'=>Carbon::now(),
        //     'expired_date'=>Carbon::today(),
        // ]);

        $User = User::create([
            'name'=>$request->fullname,
            'id_user'=>$year . '.' . $month . '.' . $incrementing,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role'=>'Member'
        ]);

        return response([
            'message' => 'Add Member Success',
            'data' => $Member
        ], 200); //Return message data Member baru dalam bentuk JSON
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_member)
    {
        $members = Member::find($id_member); //Mencari data Member berdasarkan id

        if(!is_null($members)){
            return response([
                'message' => 'Retrieve Member Success',
                'data' => $members
            ], 200);
        } //Return data semua Member dalam bentuk JSON

        return response([
            'message' => 'Member Not Found',
            'data' => null
        ], 400); //Return message data Member kosong
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
    public function update(Request $request, $id_member)
    {
        $Member = Member::find($id_member); //Mencari data Member berdasarkan id
        $User = User::where('id_user', $id_member)->first();

        if(is_null($Member)){
            return response([
                'message' => 'Member Not Found',
                'data' => null
            ], 404);
        } //Return message saat data Member tidak ditemukan

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

        // $User ga kebaca

        $User->email = $updateData['email'];
        $User->password = Hash::make($updateData['password']);
        $User->name = $updateData['fullname'];
        $User->save();

        $Member->email = $updateData['email'];
        $Member->password = Hash::make($updateData['password']);
        $Member->birth_date = $updateData['birth_date'];
        $Member->fullname = $updateData['fullname'];
        $Member->address = $updateData['address'];
        $Member->phone_number = $updateData['phone_number'];

        if($Member->save()){
            return response([
                'message' => 'Update Member Success',
                'data' => $Member
            ], 200);
        } //Return data Member yang telah di EDIT dalam bentuk JSON

        return response([
            'message' => 'Update Member Failed',
            'data' => null
        ], 400);
    }

    // public function updatePassword(Request $request, $id_member){
    //     $Member = Member::find($id_member);

    //     if(is_null($Member)){
    //         return response([
    //             'message' => 'Member Not Found',
    //             'data' => null
    //         ], 404);
    //     } //Return message saat data Member tidak ditemukan

    //     $updateData = $request->all();
    //     $validate = Validator::make($updateData, [
    //         'password' => 'required'
    //     ]);

    //     if($validate->fails()){
    //         return response(['message' => $validate->errors()], 400); //Return error invalid input
    //     }

    //     $Member->password = $updateData['password'];

    //     if($Member->save()){
    //         return response([
    //             'message' => 'Update Password Member Success',
    //             'data' => $Member
    //         ], 200);
    //     } //Return data Member yang telah di EDIT dalam bentuk JSON

    //     return response([
    //         'message' => 'Update Password Member Failed',
    //         'data' => null
    //     ], 400);
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_member)
    {
        $Member = Member::find($id_member); //Mencari data product berdasarkan id
        $User = User::where('id_user', $id_member)->first();

        if(is_null($Member)){
            return response([
                'message' => 'Member Not Found',
                'date' => null
            ], 404);
        } //Return message saat data Member tidak ditemukan

        if($Member->delete()){
            $User->delete();
            return response([
                'message' => 'Delete Member Success',
                'data' => $Member
            ], 200);
        } //Return message saat berhasil menghapus data Member

        return response([
            'message' => 'Delete Member Failed',
            'data' => null,
        ], 400);
    }

    public function resetPassword($id_member){
        $User = User::where('id_user', $id_member)->first();
        $Member = Member::find($id_member);

        $User->password = Hash::make($Member->birth_date);
        $User->save();

        $Member->password = Hash::make($Member->birth_date);
        $Member->save();

        return response([
            'message' => 'Reset Password Member Success',
            'data' => $Member
        ], 200);
    }

    public function createMembercard(Request $request, $id_member){
        $member = Member::find($id_member);

        $data = [
            'title' => 'GoFit',
            'address_gym' => 'Jl. Centralpark No.10 Yogyakarta',
            'subtitle' => 'MEMBER CARD',
            'member_id' => $member->id_member,
            'nama' => $member->fullname,
            'alamat' => $member->address,
            'telpon' => $member->phone_number
        ];
	    $pdf = PDF::loadview('membercard', $data);
	    return $pdf->download('membercard.pdf');
    }

    public function changeExpired(Request $request, $id_member){
        $member = Member::find($id_member);

        if(is_null($member)){
            return response([
                'message' => 'Member Not Found',
                'data' => null
            ], 404);
        } //Return message saat data Member tidak ditemukan

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'expired_date' => 'required'
        ]);


        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $member->expired_date = $updateData['expired_date'];

        if($member->save()){
            return response([
                'message' => 'Update Member Success',
                'data' => $member
            ], 200);
        } //Return data Member yang telah di EDIT dalam bentuk JSON

        return response([
            'message' => 'Update Member Failed',
            'data' => null
        ], 400);
    }

    public function addDepo(Request $request, $id_member){
        $member = Member::find($id_member);

        if(is_null($member)){
            return response([
                'message' => 'Member Not Found',
                'data' => null
            ], 404);
        } //Return message saat data Member tidak ditemukan

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'cash_deposit' => 'required'
        ]);


        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $member->cash_deposit = $updateData['cash_deposit'];

        if($member->save()){
            return response([
                'message' => 'Update Member Success',
                'data' => $member
            ], 200);
        } //Return data Member yang telah di EDIT dalam bentuk JSON

        return response([
            'message' => 'Update Member Failed',
            'data' => null
        ], 400);
    }

    public function showExpired($id_member){
        $expired_today = DB::select('SELECT * FROM members WHERE expired_date = "' . Carbon::now()->toDateString() . '";');

        if(is_null($expired_today)){
            return response([
                'message' => 'Tidak ada member yang expired hari ini',
                'data' => null,
            ]);
        } else{
            return response([
                'message' => 'Retrieve Data Expired Member Success',
                'data' => $expired_today,
            ]);
        }
    }

    public function deactivated($id_member){
        $expired_today = Member::all();

        if(!is_null($expired_today)){
            foreach($expired_today as $data){
                if($data->expired_date == Carbon::now()->toDateString()){
                    $data->expired_date = null;
                    $data->save();
                }
            }
            return response([
                'message' => 'Success deactivate member',
                'data' => $expired_today,
            ], 200);
        } else{
            return response([
                'message' => 'Tidak ada member yang expired hari ini',
                'data' => null,
            ], 400);
        }
    }

    public function showProfile($id_member){
        $deposit_class = DepositClass::with('member')->where('id_member', $id_member)->get();

        $member = Member::where('id_member', $id_member)->first();
        return response(['data' => $member], 200);
    }

    // histori aktivitas isinya :
    // activation report -> show (masih dalam bentuk query builder) == SELESAI
    // deposit cash report -> show (masih dalam bentuk query builder) == SELESAI
    // deposit class report -> show (masih dalam bentuk query builder) == SELESAI

    // class_booking kalo bayar pake class deposit where datetime_presensi != null (masih dalam bentuk query builder)
    // cek payment_type -> kalo paket keluarin paketnya, kalo cash keluarin harganya == BUAT RV BARU

    // gym_booking where datetime_presensi != null (masih dalam bentuk query builder) == KURANG TAMBAHIN GYM BOOKING
}
