<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\GymBooking;
use App\Models\LaporanAktivitasGym;
use Carbon\Carbon;
use PDF;

class LaporanAktivitasGymController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = LaporanAktivitasGym::all();

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

    public function laporan($id_laporan_aktivitas_gym){
        // mendapatkan laporan sesuai id laporan yang diklik kasir di web
        $laporan_aktivitas_gym = LaporanAktivitasGym::find($id_laporan_aktivitas_gym);

        // mendapatkan bulan dan tahun dari laporan
        $month = Carbon::parse($laporan_aktivitas_gym->bulan)->month;
        $year = Carbon::parse($laporan_aktivitas_gym->bulan)->year;
        $resultMonth = "";
        if($month >= 10){
            $resultMonth = $month;
        } else{
            $resultMonth = '0'.$month;
        }

        // mendapatkan class running untuk menampilkan kelas dan instruktur
        // $class_running = ClassRunning::with(['class_details', 'instructor'])->orderBy('class_running.class_details.class_name')->get();
        // $class_running = DB::table('class_runnings')
        //                 ->join('class_details', 'class_runnings.id_class', '=', 'class_details.id_class')
        //                 ->join('instructors', 'class_runnings.id_instructor', '=', 'instructors.id_instructor')
        //                 ->orderBy("class_details.class_name", "ASC")
        //                 ->get();
        // $ordered_class_running = $class_running->orderBy('class_details.class_name', 'ASC');

        // cari class booking berdasarkan bulan dan tahun
        // $gym_booking = GymBooking::with(['class_running_daily.class_running.class_details', 'class_running_daily.class_running.instructor'])
        //                         ->where('datetime', 'LIKE', '%-' . $resultMonth . '-%')
        //                         ->where('datetime', 'LIKE', '%'. $year . '-%')
        //                         ->get();

        // mencari total peserta tiap tiap kelas

        // $decode_class_running = json_decode($class_running, true);

        $gym_booking = GymBooking::all();

        $i = 0;
        // ambil first day dari bulan yang mau dicetak aktivitas nya
        $first_day = Carbon::parse($laporan_aktivitas_gym->bulan)->format('Y-m');

        // setting jadi tanggal 1 nya bulan yang mau dicetak
        $first_of_month = $first_day . '-01';

        // mencari tanggal terakhir bulan dan tahun yang mau dicetak
        $end_of_month = Carbon::parse($first_of_month)->endOfMonth();

        // ini gaperlu keknya
        $parse_for_show = Carbon::parse($first_of_month)->format('d F Y');

        // ini ambil hari dalam integer buat jadi counter di looping for
        $parse_end_of_month = Carbon::parse($end_of_month)->day;

        // variabel untuk menampung passing data berisi 'date' dan 'jumlah_libur'
        $data_passing;
        
        $jumlah_member = 0;
        for($i = 0; $i < $parse_end_of_month; $i++){
            foreach($gym_booking as $data){
                if($data->datetime_presensi != NULL){
                    if(Carbon::parse($data->datetime_presensi)->toDateString() == $first_of_month){
                        $jumlah_member++;
                    }
                }
            }
            $data_passing[$i]['date'] = Carbon::parse($first_of_month)->format('d F Y');
            $data_passing[$i]['jumlah_member'] = $jumlah_member;
            $first_of_month = Carbon::parse($first_of_month)->addDays(1)->format('Y-m-d');
            $jumlah_member = 0;
        }
        $total = 0;
        for($i = 0; $i < $parse_end_of_month; $i++){
            $total += $data_passing[$i]['jumlah_member'];
        }

        $data = [
            'title' => 'GoFit',
            'address_gym' => 'Jl. Centralpark No.10 Yogyakarta',
            'subtitle' => 'LAPORAN AKTIVITAS KELAS',
            'bulan' => $laporan_aktivitas_gym->bulan,
            'tahun' => $laporan_aktivitas_gym->tahun,
            'tanggal_cetak' => Carbon::now()->format('d m Y'),
            'total' => $total,
            'periode' => $laporan_aktivitas_gym->tahun,
            'aktivitas_gym' => $data_passing,
        ];

        return response([
            'data' => $data
        ], 200);

        // $pdf = PDF::loadview('laporan_aktivitas_gym', $data);
	    // return $pdf->download('laporan_aktivitas_gym.pdf');
    }
}
