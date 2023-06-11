<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\ClassRunning;
use App\Models\ClassRunningDaily;
use App\Models\InstructorPresensi;
use App\Models\Member;
use App\Models\Instructor;
use App\Models\DepositClass;
use App\Models\ClassBooking;
use App\Models\LaporanKinerjaInstructor;
use App\Models\LaporanAktivitasKelas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Validator;

class InstructorPresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function showActivity($id_instructor){
        $InstructorPresensi = DB::select('SELECT a.*, b.slot as slot_kelas, b.id_class_running_daily, b.date, b.id_class_running, c.*, d.* FROM instructor_presensis a
        join class_running_dailies b
        on a.id_class_running_daily = b.id_class_running_daily
        join class_runnings c
        on b.id_class_running = c.id_class_running
        join class_details d
        on c.id_class = d.id_class
        WHERE a.id_instructor = "' . $id_instructor . '"
        AND a.is_presensi = "Presensi";');

        // $ClassRunningDaily = DB::select('SELECT * FROM class_running_dailies a
        // join class_runnings b
        // on a.id_class_running = b.id_class_running
        // join instructors c
        // on b.id_instructor = c.id_instructor
        // join class_details d
        // on b.id_class = d.id_class
        // WHERE b.id_instructor = "' . $id_instructor . '"
        // AND a.status != "Libur";');

        if(count($InstructorPresensi) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $InstructorPresensi
            ], 200);
        } //Return data semua ClassRunningDaily dalam bentuk JSON

        return response([
            'message' => 'Tidak ada ClassRunningDaily',
            'data' => null
        ], 400); //Return message data ClassRunningDaily kosong
    }
}
