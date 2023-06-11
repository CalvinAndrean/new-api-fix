<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Member;
use App\Models\Pegawai;
use App\Models\DepositClassReport;
use App\Models\DepositClass;
use App\Models\ClassPromo;
use Illuminate\Support\Str;
use App\Models\ActivationReport;
use PDF;

class DepositClassReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $depoclass = DepositClassReport::with(['member', 'pegawai', 'class_promo'])->get(); // mengambil semua data member

        if(count($depoclass) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $depoclass
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
        $member = Member::find($id_member);
        $availDepoClass = DepositClass::where('id_member', $id_member)->get();
        
        $storeData = $request->all(); // mengambil semua input dari web
        $validate = Validator::make($storeData, [
            'id_class_promo' => 'required',
        ]);

        if ($validate->fails()) {
            $firstError = $validate->errors()->first();
        
            return response(['message' => $firstError], 400);
        }

        $latest = DB::table('deposit_class_reports')->latest()->first();  //23.04.001
        if($latest == null){
            $incrementing = 1;
        } else{
            $incrementing = ((int)Str::substr($latest->report_number_class_deposit, 6, 3)) + 1;
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

        $class_promo = ClassPromo::find($request->id_class_promo);

        if(count($availDepoClass) > 0){
            foreach($availDepoClass as $data){
                if($data->id_class == $request->id_class){
                    if(($data->total_amount == 0) || ($data->expired_date < Carbon::now())){
                        if($member->cash_deposit < $class_promo->price){
                            return response([
                                'message' => 'Cash Deposit tidak mencukupi',
                                'data' => $member
                            ], 400);
                        } else{
                            $depoclass = DepositClassReport::create([
                                'report_number_class_deposit'=> $num_activation,
                                'id_member'=>$member->id_member,
                                'id_pegawai'=> $request->id_pegawai,
                                'id_class_promo' => $request->id_class_promo,
                                'expired_date' => $request->expired_date,
                                'datetime' => Carbon::now(),
                            ]);
                            $member->cash_deposit -= $class_promo->price;
                            $member->save();
                            return response([
                                'message' => 'Add Report Success',
                                'data' => $depoclass
                            ], 200); //Return message data DepositClass baru dalam bentuk JSON
                        }
                    } else{
                        return response([
                            'message' => 'Tidak dapat generate report karena deposit masih ada dan valid',
                        ], 400);
                    }
                }
            }
            if($member->cash_deposit < $class_promo->price){
                return response([
                    'message' => 'Cash Deposit tidak mencukupi',
                    'data' => $member
                ], 400);
            } else{
                $depoclass = DepositClassReport::create([
                    'report_number_class_deposit'=> $num_activation,
                    'id_member'=>$member->id_member,
                    'id_pegawai'=> $request->id_pegawai,
                    'id_class_promo' => $request->id_class_promo,
                    'expired_date' => $request->expired_date,
                    'datetime' => Carbon::now(),
                ]);
                $member->cash_deposit -= $class_promo->price;
                $member->save();
                return response([
                    'message' => 'Add New Report1 Success',
                    'data' => $depoclass
                ], 200); //Return message data DepositClass baru dalam bentuk JSON
            }
        } else{
            if($member->cash_deposit < $class_promo->price){
                return response([
                    'message' => 'Cash Deposit tidak mencukupi',
                    'data' => $member
                ], 400);
            } else{
                $depoclass = DepositClassReport::create([
                    'report_number_class_deposit'=> $num_activation,
                    'id_member'=>$member->id_member,
                    'id_pegawai'=> $request->id_pegawai,
                    'id_class_promo' => $request->id_class_promo,
                    'expired_date' => $request->expired_date,
                    'datetime' => Carbon::now(),
                ]);
                $member->cash_deposit -= $class_promo->price;
                $member->save();
                return response([
                    'message' => 'Add New Report2 Success',
                    'data' => $depoclass
                ], 200); //Return message data DepositClass baru dalam bentuk JSON
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_member)
    {
        // $depocash = DepositCashReport::where('id_member', $id_member)->first(); //Mencari data Member berdasarkan id

        $depoclass = DB::select('SELECT a.*, b.fullname as fullname_member, c.fullname as fullname_pegawai, d.*, e.* FROM deposit_class_reports a
        join members b
        on a.id_member = b.id_member
        join pegawais c
        on a.id_pegawai = c.id_pegawai
        join class_promos d
        on a.id_class_promo = d.id_class_promo
        join class_details e
        on d.id_class = e.id_class
        WHERE a.id_member = "' . $id_member . '";');

        if(count($depoclass) > 0){
            return response([
                'message' => 'Retrieve Deposit Class Success',
                'data' => $depoclass
            ], 200);
        } //Return data semua Member dalam bentuk JSON

        return response([
            'message' => 'Deposit Class Not Found',
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
        $depositClass = DepositClassReport::where('id_member', $id_member)->first();

        $depositClass->expired_date = $request->expired_date;
        $depositClass->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($report_number_class_deposit)
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

    public function printReport(Request $request, $report_number_class_deposit){
        // $relation = ActivationReport::with(['member', 'pegawai'])->get();
        // $activation = ActivationReport::where('id_member', $id_member)->first();

        $depoclass = DB::select('SELECT a.*, b.fullname as fullname_member, c.fullname as fullname_pegawai, d.*, e.* FROM deposit_class_reports a
        join members b
        on a.id_member = b.id_member
        join pegawais c
        on a.id_pegawai = c.id_pegawai
        join class_promos d
        on a.id_class_promo = d.id_class_promo
        join class_details e
        on d.id_class = e.id_class
        WHERE a.report_number_class_deposit = "' . $report_number_class_deposit . '";');

        $data = [
            'title' => 'GoFit',
            'address_gym' => 'Jl. Centralpark No.10 Yogyakarta',
            'report_number_activation' => $depoclass[0]->report_number_class_deposit,
            'date' => Carbon::now(),
            'id_member' => $depoclass[0]->id_member,
            'fullname_member' => $depoclass[0]->fullname_member,
            'id_pegawai' => $depoclass[0]->id_pegawai,
            'fullname_pegawai' => $depoclass[0]->fullname_pegawai,

            'deposit' => $depoclass[0]->amount_deposit,
            'price' => $depoclass[0]->price,
            'total_price' => $depoclass[0]->total_price,
            'jenis_kelas' => $depoclass[0]->class_name,
            'bonus' => $depoclass[0]->bonus,
            'total_deposit' => $depoclass[0]->amount_deposit + $depoclass[0]->bonus,
            'expired_date' => $depoclass[0]->expired_date,
        ];
	    $pdf = PDF::loadview('depoclass', $data);
	    return $pdf->download('deposit_class_report.pdf');
    }

    public function showActivity($id_member){
        $deposit_class_report = DepositClassReport::with(['class_promo', 'pegawai', 'member'])->where('id_member', $id_member)->get();

        return response(['data' => $deposit_class_report], 200);
    }
}
