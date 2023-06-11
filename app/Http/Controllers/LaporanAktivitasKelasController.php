<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ClassBooking;
use App\Models\ClassRunning;
use App\Models\LaporanAktivitasKelas;
use Carbon\Carbon;
use PDF;

class LaporanAktivitasKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = LaporanAktivitasKelas::all();

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

    public function laporan($id_laporan_aktivitas_kelas){
        // mendapatkan laporan sesuai id laporan yang diklik kasir di web
        $laporan_aktivitas_kelas = LaporanAktivitasKelas::find($id_laporan_aktivitas_kelas);

        // mendapatkan bulan dan tahun dari laporan
        $month = Carbon::parse($laporan_aktivitas_kelas->bulan)->month;
        $year = Carbon::parse($laporan_aktivitas_kelas->bulan)->year;
        $resultMonth = "";
        if($month >= 10){
            $resultMonth = $month;
        } else{
            $resultMonth = '0'.$month;
        }

        // mendapatkan class running untuk menampilkan kelas dan instruktur
        // $class_running = ClassRunning::with(['class_details', 'instructor'])->orderBy('class_running.class_details.class_name')->get();
        $class_running = DB::table('class_runnings')
                        ->join('class_details', 'class_runnings.id_class', '=', 'class_details.id_class')
                        ->join('instructors', 'class_runnings.id_instructor', '=', 'instructors.id_instructor')
                        ->orderBy("class_details.class_name", "ASC")
                        ->get();
        // $ordered_class_running = $class_running->orderBy('class_details.class_name', 'ASC');

        // cari class booking berdasarkan bulan dan tahun
        $class_running_daily = ClassBooking::with(['class_running_daily.class_running.class_details', 'class_running_daily.class_running.instructor'])
                                ->where('datetime', 'LIKE', '%-' . $resultMonth . '-%')
                                ->where('datetime', 'LIKE', '%'. $year . '-%')
                                ->get();

        // mencari total peserta tiap tiap kelas

        $decode_class_running = json_decode($class_running, true);
        $i = 0;
        $total_peserta = 0;
        $jumlah_libur = 0;
        // mencari jumlah libur sebuah kelas -> diliat dari class_running_daily->status
        foreach($class_running as $data1){
            // $decode_class_running[$i]['iniTambahan'] = "Tambahan ke ".$i;
            foreach($class_running_daily as $data2){
                if($data1->id_class_running == $data2->class_running_daily->class_running->id_class_running){
                    $total_peserta += (10-($data2->class_running_daily->slot));   
                }

                if($data2->class_running_daily->status == "Libur"){
                    $jumlah_libur++;
                }
            }
            $decode_class_running[$i]['total_peserta'] = $total_peserta;
            $decode_class_running[$i]['jumlah_libur'] = $jumlah_libur;
            $i++;
            $total_peserta = 0;
            $jumlah_libur = 0;
        };
        // return response([
        //     'data' => $decode_class_running
        // ], 200);

        // ini untuk nambahin total peserta dan jumlah libur -- harus cari total peserta dan jumlah libur dulu
        // $decode_class_running['iniTambahan'] = "tambahan bos";
        // $encode_class_running = json_encode($decode_class_running);

        // return response([
        //     'data' => $decode_class_running
        // ], 200);

        $data = [
            'title' => 'GoFit',
            'address_gym' => 'Jl. Centralpark No.10 Yogyakarta',
            'subtitle' => 'LAPORAN AKTIVITAS KELAS',
            'bulan' => $laporan_aktivitas_kelas->bulan,
            'tahun' => $laporan_aktivitas_kelas->tahun,
            'tanggal_cetak' => Carbon::now()->format('d m Y'),

            'periode' => $laporan_aktivitas_kelas->tahun,
            'class_running' => $decode_class_running,
        ];

        return response([
            'data' => $data
        ], 200);

        $pdf = PDF::loadview('laporan_aktivitas_kelas', $data);
	    return $pdf->download('laporan_aktivitas_kelas.pdf');
    }
}
