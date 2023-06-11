<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Member;
use App\Models\Pegawai;
use App\Models\DepositClass;
use Validator;
use App\Models\ClassPromo;
use Illuminate\Support\Str;

class DepositClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $DepositClass = DepositClass::with(['class_details', 'members'])->get();

        if(count($DepositClass) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $DepositClass
            ], 200);
        } //Return data semua DepositClass dalam bentuk JSON

        return response([
            'message' => 'Tidak ada DepositClass',
            'data' => null
        ], 400); //Return message data DepositClass kosong
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
        // $availDepoClass = DepositClass::where('id_member', $id_member)->first();
        $availDepoClass = DepositClass::where('id_member', $id_member)->get();
        // $availDepoClass = DB::select('SELECT * FROM deposit_classes 
        // WHERE id_member = "' . $id_member . '";');
        // return response([
        //     'data' => $availDepoClass,
        // ]);

        $storeData = $request->all(); // mengambil semua input dari web
        $validate = Validator::make($storeData, [
            'id_member' => 'required',
            'id_class' => 'required',
            'total_amount' => 'required',
            'expired_date' => 'required',
        ]);

        if ($validate->fails()) {
            $firstError = $validate->errors()->first();
        
            return response(['message' => $firstError], 400);
        }

        if(count($availDepoClass) > 0){
            foreach($availDepoClass as $data){
                if($data->id_class == $request->id_class){
                    if(($data->total_amount == 0) || ($data->expired_date < Carbon::now())){
                        $total_amount = $data->total_amount + $request->total_amount;
                        $dateNow = Carbon::now();
                        $diffDay = $dateNow->diffInDays($request->expired_date);
                        $new_expired_date = Carbon::parse($data->expired_date)->addDays($diffDay);
                        $data->total_amount = $total_amount;
                        $data->expired_date = $request->expired_date;
                        $data->save();
                        return response([
                            'message' => 'Update DepositClass Success',
                            'data' => $availDepoClass
                        ], 200); //Return message data DepositClass baru dalam bentuk JSON
                    } else{
                        return response([
                            'data' => 'Deposit kelas masih ada',
                        ], 400);
                    }
                }
            }
            $availDepoClass = DepositClass::create([
                'id_member' => $id_member,
                'id_class' => $request->id_class,
                'total_amount' => $request->total_amount,
                'expired_date' => $request->expired_date,
            ]);
            return response([
                'message' => 'Add New DepositClass Success',
                'data' => $availDepoClass
            ], 200); //Return message data DepositClass baru dalam bentuk JSON
        } else{
            $availDepoClass = DepositClass::create([
                'id_member' => $id_member,
                'id_class' => $request->id_class,
                'total_amount' => $request->total_amount,
                'expired_date' => $request->expired_date,
            ]);
            return response([
                'message' => 'Add New DepositClass Success',
                'data' => $availDepoClass
            ], 200); //Return message data DepositClass baru dalam bentuk JSON
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
        $DepositClass = DB::select('SELECT a.*, b.expired_date as member_expired, b.fullname as fullname, c.* FROM deposit_classes a
        join members b
        on a.id_member = b.id_member
        join class_details c
        on a.id_class = c.id_class
        WHERE a.id_member = "' . $id_member . '";');

        // $DepositClass = DepositClass::with(['class_details'])->get();

        if(!is_null($DepositClass)){
            return response([
                'message' => 'Retrieve DepositClass Success',
                'data' => $DepositClass
            ], 200);
        } //Return data semua DepositClass dalam bentuk JSON

        return response([
            'message' => 'DepositClass Not Found',
            'data' => null
        ], 400); //Return message data DepositClass kosong
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
            'report_number_activation' => $depoclass->report_number_deposit_cash,
            'date' => Carbon::now(),
            'id_member' => $depoclass->id_member,
            'fullname_member' => $depoclass->fullname_member,
            'id_pegawai' => $depoclass->id_pegawai,
            'fullname_pegawai' => $depoclass->fullname_pegawai,

            'deposit' => $depoclass->amount_deposit,
            'price' => $depoclass->price,
            'jenis_kelas' => $depoclass->class_name,
            'bonus' => $depoclass->bonus,
            'total_deposit' => $depoclass->amount_deposit + $depoclass->bonus,
            'expired_date' => $depoclass->expired_date,
        ];
	    $pdf = PDF::loadview('depoclass', $data);
	    return $pdf->download('deposit_class_report.pdf');
    }

    public function showExpired($id_deposit_class){
        $expired_today = DB::select('SELECT * FROM deposit_classes WHERE expired_date = "' . Carbon::now()->toDateString() . '";');

        if(is_null($expired_today)){
            return response([
                'message' => 'Tidak ada deposit kelas yang expired hari ini',
                'data' => null,
            ]);
        } else{
            return response([
                'message' => 'Retrieve Data Expired Deposit Class Success',
                'data' => $expired_today,
            ]);
        }
    }

    public function resetDepositClass($id_deposit_class){
        $expired_today = DepositClass::all();

        if(is_null($expired_today)){
            return response([
                'message' => 'Tidak ada deposit kelas yang expired hari ini',
                'data' => null,
            ], 400);
        } else{
            foreach($expired_today as $data){
                if($data->expired_date == Carbon::now()->toDateString()){
                    $data->total_amount = 0;
                    $data->save();
                }
            }
            return response([
                'message' => 'Reset Deposit Class Success',
                'data' => $expired_today,
            ], 200);
        }
    }
}
