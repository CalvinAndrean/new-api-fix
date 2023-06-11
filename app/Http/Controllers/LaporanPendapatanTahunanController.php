<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ActivationReport;
use App\Models\DepositClassReport;
use App\Models\DepositCashReport;
use App\Models\ClassBooking;
use App\Models\LaporanPendapatanTahunan;
use PDF;
use Carbon\Carbon;

class LaporanPendapatanTahunanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = LaporanPendapatanTahunan::all();

        if(!is_null($data)){
            return response([
                'message' => 'Success Retrieve data',
                'data' => $data
            ], 200);
        } else{
            return response([
                'message' => 'Failed retrieve data',
                'data' => null
            ], 400);
        }
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    public function laporan($tahun){
        $deposit_cash_report = DepositCashReport::all();
        $deposit_class_report = DepositClassReport::with(['class_promo'])->get();
        $data_passing;
        $data_total_perbulan;
        $activation = ActivationReport::all();
        $total = 0;
        for($i = 1; $i <= 12; $i++){
            $total_activation = 0;
            if($i < 10){
                $month = '0'.$i;
            } else{
                $month = $i;
            }
            // $getMonth = Str::substr($activation[$i]->datetime, 5, 2);
            foreach($activation as $data){
                if(Str::substr($data->datetime, 5, 2) == $month){
                    $total_activation += 3000000;
                }
            }

            $total_depo = 0;
            foreach($deposit_cash_report as $cash){
                if(Str::substr($cash->date_deposit, 5, 2) == $month){
                    $total_depo += $cash->amount_deposit;
                }
            }

            foreach($deposit_class_report as $class){
                if(Str::substr($class->datetime, 5, 2) == $month){
                    $total_depo += $class->class_promo->total_price;
                }
            }
            $total += $total_activation + $total_depo;
            if($i == 2){
                $data_passing[$i-1]['month'] = 'February';
            } else{
                $data_passing[$i-1]['month'] = Carbon::createFromFormat('m', $month)->monthName;
            }
            $data_passing[$i-1]['aktivasi'] = $total_activation;
            $data_passing[$i-1]['deposit'] = $total_depo;
            $data_passing[$i-1]['total_perbulan'] = $total_activation + $total_depo;
            $data_total_perbulan[$i-1] = $total_activation + $total_depo;
        }
        // $data_passing[12]['total'] = $total;
        // $data_passing[12]['periode'] = $tahun;
        // $data_passing[12]['tanggal_cetak'] = Carbon::now()->format('d-m-Y');

        // masukin data kedalam loadview
        $data = [
            'title' => 'GoFit',
            'address_gym' => 'Jl. Centralpark No.10 Yogyakarta',
            'subtitle' => 'LAPORAN PENDAPATAN BULANAN',

            'periode' => $tahun,
            'tanggal_cetak' => Carbon::now()->format('d-m-Y'),
            'data_passing' => $data_passing,
            'total_perbulan_1' => $data_total_perbulan,
            'total' => $total,
        ];
	    $pdf = PDF::loadview('laporan_pendapatan_tahunan', $data);
	    return $pdf->stream("laporan_pendapatan_tahunan.pdf", array("Attachment" => false));

        return response([
            'data' => $data,
        ], 200);
    }
}
