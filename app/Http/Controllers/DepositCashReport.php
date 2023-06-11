<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Member;
use App\Models\Pegawai;
use Illuminate\Support\Str;
use App\Models\ActivationReport;
use PDF;

class DepositCashReport extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $depocash = DepositCash::with(['member', 'pegawai', 'cash_promo'])->get(); // mengambil semua data member

        if(count($depocash) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $depocash
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
    public function store(Request $request, $id_member)
    {
        $member = Member::find($id_member)->get();
        
        $storeData = $request->all(); // mengambil semua input dari web
        $validate = Validator::make($storeData, [
            'id_member'=>'required',
            'id_pegawai' => 'required',
            'id_cash_promo' => 'required',
            'date_deposit' => 'required',
            'amount_deposit' => 'required',
            'bonus_deposit' => 'required',
            'remaining_deposit' => 'required',
            'total_deposit' => 'required',
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $latest = DB::table('deposit_cash_reports')->latest()->first();  //23.04.001
        if($latest == null){
            $incrementing = 1;
        } else{
            $incrementing = ((int)Str::substr($latest->report_number_activation, 6, 3)) + 1;
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
        $num_activation = $year . '.' . $month . '.' . $incrementing;

        $afterBonus = 0;
        // $multiplier = (int)$request->amount_deposit / 3000000;
        $bonus = 300000;
        return response([
            'data' => $request->amount_deposit,
        ]);
        if($request->amount_deposit >= 500000){
            if($request->amount_deposit >= 3000000){
                $afterBonus = $member->cash_deposit + $request->amount_deposit + $bonus;
            } else{
                $afterBonus = $member->cash_deposit + $request->amount_deposit;
            }
            $depocash = DepositCashReport::create([
                'report_number_deposit_cash'=> $num_activation,
                'id_member'=>$member->id_member,
    
                'id_pegawai'=>localStorage.getItem('id'),
                'id_cash_promo' => 1,
                'date_deposit' => Carbon::now(),
    
                'amount_deposit' => $request->amount_deposit,
    
                'bonus_deposit' => $bonus,
                'remaining_deposit' => $member->cash_deposit,
                'total_deposit' => $afterBonus,
            ]);
            $member->cash_deposit = $afterBonus;
            $member->save();

            return response([
                'message' => 'Add Deposit Cash Success',
                'data' => $depocash,
            ], 200); //Return message data Activation baru dalam bentuk JSON
        } else{
            return response([
                'message' => 'Minimal deposit Rp.500.000,-',
                'data' => $request->amount_deposit,
            ]);
        }

        // $afterBonus = 0;
        // $multiplier = (int)$request->amount_deposit / 3000000;
        // $bonus = $multiplier*300000;
        // if($member->cash_deposit >= 500000){
        //     if($request->amount_deposit >= 3000000){
        //         $afterBonus = $member->cash_deposit + $request->amount_deposit + $bonus;
        //     }
        // } else{
        //     $afterBonus = $member->cash_deposit + $request->amount_deposit;
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($report_number_activation)
    {
        // $activation = ActivationReport::find($report_number_activation); //Mencari data Member berdasarkan id

        // if(!is_null($activation)){
        //     return response([
        //         'message' => 'Retrieve Member Success',
        //         'data' => $activation
        //     ], 200);
        // } //Return data semua Member dalam bentuk JSON

        // return response([
        //     'message' => 'Member Not Found',
        //     'data' => null
        // ], 400); //Return message data Member kosong
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
    public function update(Request $request, $report_number_activation)
    {
        // $activation = ActivationReport::find($report_number_activation); //Mencari data Member berdasarkan id

        // if(is_null($activation)){
        //     return response([
        //         'message' => 'Activation Not Found',
        //         'data' => null
        //     ], 404);
        // } //Return message saat data Activation tidak ditemukan

        // $updateData = $request->all();
        // $validate = Validator::make($updateData, [
        //     'id_member' => 'required',
        //     'id_pegawai' => 'required',
        //     'datetime' => 'required',
        //     'expired_date' => 'required',
        // ]);
        // // Bisa ditambahin rule validasi input
        //     // 'nama_customer.regex' => 'Inputan tidak boleh mengandung angka atau simbol lain',
        //     // 'tgl_lahir.date_format' => 'Seharusnya YYYY-MM-DD',
        //     // 'email_customer.email' => 'Format email salah',
        //     // 'upload_berkas.mimes' => 'Hanya bisa menampung jpg, png, jpeg saja',
        //     // 'upload_berkas.image' => 'Hanya bisa menerima image',
        //     // 'nomor_kartupengenal' => 'Hanya bisa menerima angka',
        //     // 'no_sim.numeric' => 'Hanya bisa menerima angka',
        //     // 'usia_customer.numeric' => 'Hanya bisa menerima angka'


        // if($validate->fails()){
        //     return response(['message' => $validate->errors()], 400); //Return error invalid input
        // }

        // $activation->id_pegawai = Hash::make($updateData['id_pegawai']);
        // $activation->datetime = $updateData['datetime'];
        // $activation->expired_date = $updateData['expired_date'];

        // if($activation->save()){
        //     return response([
        //         'message' => 'Activation Success',
        //         'data' => $activation
        //     ], 200);
        // } //Return data Member yang telah di EDIT dalam bentuk JSON

        // return response([
        //     'message' => 'Activation Failed',
        //     'data' => null
        // ], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($report_number_activation)
    {
        // $activation = ActivationReport::find($report_number_activation); //Mencari data product berdasarkan id

        // if(is_null($activation)){
        //     return response([
        //         'message' => 'Activation Not Found',
        //         'date' => null
        //     ], 404);
        // } //Return message saat data Member tidak ditemukan

        // if($activation->delete()){
        //     return response([
        //         'message' => 'Delete Report Success',
        //         'data' => $activation
        //     ], 200);
        // } //Return message saat berhasil menghapus data Report

        // return response([
        //     'message' => 'Delete Report Failed',
        //     'data' => null,
        // ], 400);
    }

    public function printReport(Request $request, $id_member){
        // $relation = ActivationReport::with(['member', 'pegawai'])->get();
        // $activation = ActivationReport::where('id_member', $id_member)->first();

        $depocash = DB::select('SELECT a.*, b.fullname as fullname_member, c.fullname as fullname_pegawai FROM deposit_cash_reports a
        join members b
        on a.id_member = b.id_member
        join pegawais c
        on a.id_pegawai = c.id_pegawai
        WHERE a.id_member = "' . $id_member . '";');

        $data = [
            'title' => 'GoFit',
            'address_gym' => 'Jl. Centralpark No.10 Yogyakarta',
            'report_number_activation' => $depocash[0]->report_number_deposit_cash,
            'date' => Carbon::now(),
            'id_member' => $depocash[0]->id_member,
            'fullname_member' => $depocash[0]->fullname_member,
            'id_pegawai' => $depocash[0]->id_pegawai,
            'fullname_pegawai' => $depocash[0]->fullname_pegawai,
            'bonus_deposit' => $depocash[0]->bonus_deposit,
            'sisa_deposit' => $depocash[0]->remaining_deposit,
            'deposit' => $depocash[0]->amount_deposit,
            'total_deposit' => $depocash[0]->total_deposit,
        ];
	    $pdf = PDF::loadview('report', $data);
	    return $pdf->download('deposit_cash_report.pdf');
    }
}
