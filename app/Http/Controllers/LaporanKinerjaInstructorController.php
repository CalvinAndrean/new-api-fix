<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ActivationReport;
use App\Models\InstructorPresensi;
use App\Models\Instructor;
use App\Models\ClassRunningDaily;
use App\Models\LaporanPendapatanTahunan;
use App\Models\LaporanKinerjaInstructor;
use PDF;
use Carbon\Carbon;

class LaporanKinerjaInstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = LaporanKinerjaInstructor::all();

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

    public function laporan($id_laporan_kinerja_instructor){
        // mendapatkan tahun
        $laporan_kinerja_instructor = LaporanKinerjaInstructor::find($id_laporan_kinerja_instructor);

        // ambil semua nama instructor
        $instructor = Instructor::all();

        // mengambil seluruh data presensi instructor
        $presensi_instructor = InstructorPresensi::all();

        // mengambil seluruh data jadwal harian untuk dilihat statusnya
        $class_running_daily = ClassRunningDaily::with('class_running')->get();

        // variabel penampung untuk passing data
        $data_passing;
        $i = 0;
        foreach($instructor as $data1){
            $jumlah_hadir = 0;
            $waktu_terlambat = Carbon::parse($data1->total_late)->diffInSeconds(Carbon::parse('00:00:00'));

            // mencari jumlah hadir dari tiap tiap instructor dengan pembanding is_presensi pada tabel presensi_instructor
            foreach($presensi_instructor as $data2){
                if(($data1->id_instructor == $data2->id_instructor) && ($data2->is_presensi == "Presensi")){
                    $jumlah_hadir++;
                }
            }
            $jumlah_libur = 0;

            // mencari jumlah libur dari tiap tiap instructor dengan pembanding status pada tabel class_running_dailies
            foreach($class_running_daily as $data3){
                if(($data1->id_instructor == $data3->class_running->id_instructor) && ($data3->status == "Libur")){
                    $jumlah_libur++;
                }
            }

            $data_passing[$i]['instructor'] = $data1->fullname;
            $data_passing[$i]['jumlah_hadir'] = $jumlah_hadir;
            $data_passing[$i]['jumlah_libur'] = $jumlah_libur;
            $data_passing[$i]['waktu_terlambat'] = $waktu_terlambat;
            $i++;
        }

        for($j = 0; $j < $i; $j++){
            for($k = $j+1; $k < $i; $k++){
                if($data_passing[$j]['waktu_terlambat'] > $data_passing[$k]['waktu_terlambat']){
                    $temp = $data_passing[$j];
                    $data_passing[$j] = $data_passing[$k];
                    $data_passing[$k] = $temp;
                }
            }
        }

        $result = collect($data_passing)->sortBy('waktu_terlambat');

        // return response([
        //     'data' => $result
        // ], 200);
        // masukin data kedalam loadview
        $data = [
            'title' => 'GoFit',
            'address_gym' => 'Jl. Centralpark No.10 Yogyakarta',
            'subtitle' => 'LAPORAN PENDAPATAN BULANAN',

            'bulan' => $laporan_kinerja_instructor->bulan,
            'tahun' => $laporan_kinerja_instructor->tahun,
            'tanggal_cetak' => Carbon::now()->format('d-m-Y'),
            'data_passing' => $data_passing,
        ];

        // return response([
        //     'data' => $data
        // ], 200);
	    $pdf = PDF::loadview('laporan_kinerja_instructor', $data);
	    return $pdf->stream("laporan_kinerja_instructor.pdf", array("Attachment" => false));
    }
}
