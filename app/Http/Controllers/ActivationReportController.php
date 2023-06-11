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

class ActivationReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activation = ActivationReport::with(['member', 'pegawai'])->get(); // mengambil semua data member

        if(count($activation) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $activation
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
        ]);

        if ($validate->fails()) {
            $firstError = $validate->errors()->first();
        
            return response(['message' => $firstError], 400);
        }

        $latest = DB::table('activation_reports')->latest()->first();  //23.04.001
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

        $activation = ActivationReport::create([
            'report_number_activation'=> $num_activation,
            'id_member'=>$request->id_member,
            'id_pegawai'=>$request->id_pegawai,
            'datetime'=>Carbon::now(),
            'expired_date'=>$expired,
        ]);
        $checkLaporan = LaporanPendapatanTahunan::all();
        $check = 0;
        if(!is_null($checkLaporan)){
            foreach($checkLaporan as $data){
                if($data->tahun == Carbon::now()->format('Y')){
                    $check = 1;
                }
            }
        }

        if($check != 1){
            $laporan = LaporanPendapatanTahunan::create([
                'tahun' => Carbon::now()->format('Y'),
            ]);
        }
    
        // $member->expired_date = $expired;

        return response([
            'message' => 'Add Activation Success',
            'data' => $activation
        ], 200); //Return message data Activation baru dalam bentuk JSON
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_member)
    {
        // $activation = ActivationReport::where('id_member', $id_member);
        $activation = DB::select('SELECT a.*, b.fullname as fullname_member, c.fullname as fullname_pegawai, b.cash_deposit as cash_deposit FROM activation_reports a
        join members b
        on a.id_member = b.id_member
        join pegawais c
        on a.id_pegawai = c.id_pegawai
        WHERE a.id_member = "' . $id_member . '";');

        if(!is_null($activation)){
            return response([
                'message' => 'Retrieve Activation Report Success',
                'data' => $activation
            ], 200);
        } //Return data semua Member dalam bentuk JSON

        return response([
            'message' => 'Activation Report Not Found',
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
    public function update(Request $request, $report_number_activation)
    {
        $activation = ActivationReport::find($report_number_activation); //Mencari data Member berdasarkan id

        if(is_null($activation)){
            return response([
                'message' => 'Activation Not Found',
                'data' => null
            ], 404);
        } //Return message saat data Activation tidak ditemukan

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_member' => 'required',
            'id_pegawai' => 'required',
            'datetime' => 'required',
            'expired_date' => 'required',
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

        $activation->id_pegawai = Hash::make($updateData['id_pegawai']);
        $activation->datetime = $updateData['datetime'];
        $activation->expired_date = $updateData['expired_date'];

        if($activation->save()){
            return response([
                'message' => 'Activation Success',
                'data' => $activation
            ], 200);
        } //Return data Member yang telah di EDIT dalam bentuk JSON

        return response([
            'message' => 'Activation Failed',
            'data' => null
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($report_number_activation)
    {
        $activation = ActivationReport::find($report_number_activation); //Mencari data product berdasarkan id

        if(is_null($activation)){
            return response([
                'message' => 'Activation Not Found',
                'date' => null
            ], 404);
        } //Return message saat data Member tidak ditemukan

        if($activation->delete()){
            return response([
                'message' => 'Delete Report Success',
                'data' => $activation
            ], 200);
        } //Return message saat berhasil menghapus data Report

        return response([
            'message' => 'Delete Report Failed',
            'data' => null,
        ], 400);
    }

    public function printReport(Request $request, $id_member){
        // $relation = ActivationReport::with(['member', 'pegawai'])->get();
        // $activation = ActivationReport::where('id_member', $id_member)->first();

        $activation = DB::select('SELECT a.*, b.fullname as fullname_member, c.fullname as fullname_pegawai FROM activation_reports a
        join members b
        on a.id_member = b.id_member
        join pegawais c
        on a.id_pegawai = c.id_pegawai
        WHERE a.id_member = "' . $id_member . '";');

        $data = [
            'title' => 'GoFit',
            'address_gym' => 'Jl. Centralpark No.10 Yogyakarta',
            'report_number_activation' => $activation[0]->report_number_activation,
            'date' => $activation[0]->datetime,
            'id_member' => $activation[0]->id_member,
            'fullname_member' => $activation[0]->fullname_member,
            'expired_date' => $activation[0]->expired_date,
            'id_pegawai' => $activation[0]->id_pegawai,
            'fullname_pegawai' => $activation[0]->fullname_pegawai,
        ];
	    $pdf = PDF::loadview('report', $data);
	    return $pdf->download('activation_report.pdf');
    }

    public function showActivity($id_member){
        $activation_report = ActivationReport::with(['member', 'pegawai'])->where('id_member', $id_member)->get();

        return response(['data' => $activation_report], 200);
    }
}
