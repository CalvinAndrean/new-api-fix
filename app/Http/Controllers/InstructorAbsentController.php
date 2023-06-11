<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Member;
use App\Models\Pegawai;
use App\Models\ClassRunningDaily;
use App\Models\InstructorAbsent;
use Illuminate\Support\Str;

class InstructorAbsentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $absent = InstructorAbsent::all(); // mengambil semua data member

        $absent = DB::select('SELECT a.*, b.fullname as fullname_instructor, f.fullname as pengganti_instructor, c.*, d.*, e.* FROM instructor_absents a
        join instructors b
        on a.id_instructor = b.id_instructor
        join class_running_dailies c
        on a.id_class_running_daily = c.id_class_running_daily
        join class_runnings d
        on c.id_class_running = d.id_class_running
        join class_details e
        on d.id_class = e.id_class
        join instructors f
        on a.id_substitute_instructor = f.id_instructor;');

        if(count($absent) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $absent
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
            'id_instructor'=>'required',
            'id_class_running_daily' => 'required',
            'reason' => 'required',
            'id_substitute_instructor' => 'required',
        ]);

        if ($validate->fails()) {
            $firstError = $validate->errors()->first();
        
            return response(['message' => $firstError], 400);
        }

        $absent = InstructorAbsent::create([
            'id_instructor'=> $request->id_instructor,
            'id_class_running_daily'=>$request->id_class_running_daily,
            'reason'=>$request->reason,
            'id_substitute_instructor'=>$request->id_substitute_instructor,
        ]);

        return response([
            'message' => 'Add Absent Success',
            'data' => $absent
        ], 200); //Return message data Member baru dalam bentuk JSON
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_instructor)
    {
        // $absent = InstructorAbsent::where('id_instructor', $id_instructor)->get();

        $absent = DB::select('SELECT a.*, b.fullname as fullname_instructor, e.fullname as pengganti_instructor, c.*, d.* FROM instructor_absents a
        join instructors b
        on a.id_instructor = b.id_instructor
        join class_running_dailies c
        on a.id_class_running_daily = c.id_class_running_daily
        join class_runnings f
        on c.id_class_running = f.id_class_running
        join class_details d
        on f.id_class = d.id_class
        join instructors e
        on a.id_substitute_instructor = e.id_instructor
        WHERE a.id_instructor = "' . $id_instructor . '";');

        if(!is_null($absent)){
            return response([
                'message' => 'Retrieve Absent Success',
                'data' => $absent
            ], 200);
        } //Return data semua Member dalam bentuk JSON

        return response([
            'message' => 'Absent Not Found',
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

    public function confirmAbsent(Request $request, $id_absent){
        $absent = InstructorAbsent::find($id_absent)->first();
        $ClassRunningDaily = ClassRunningDaily::find($absent->id_class_running_daily);

        if(!is_null($absent)){
            $absent->is_confirmed = $request->is_confirmed;
            $absent->save();

            $ClassRunningDaily->status = $request->is_confirmed;
            $ClassRunningDaily->save();
            return response([
                'message' => 'Berhasil confirm absent',
                'data' => $absent
            ], 200);
        } else{
            return response([
                'message' => 'Absent tidak ditemukan',
                'data' => null,
            ], 400);
        }
    }

    public function showActivity($id_instructor){
        $absent = DB::select('SELECT a.*, b.fullname as fullname_instructor, e.fullname as pengganti_instructor, c.*, d.* FROM instructor_absents a
        join instructors b
        on a.id_instructor = b.id_instructor
        join class_running_dailies c
        on a.id_class_running_daily = c.id_class_running_daily
        join class_runnings f
        on c.id_class_running = f.id_class_running
        join class_details d
        on f.id_class = d.id_class
        join instructors e
        on a.id_substitute_instructor = e.id_instructor
        WHERE a.id_instructor = "' . $id_instructor . '"
        AND a.is_confirmed = "Confirmed";');

        if(!is_null($absent)){
            return response([
                'message' => 'Retrieve Absent Success',
                'data' => $absent
            ], 200);
        } //Return data semua Member dalam bentuk JSON

        return response([
            'message' => 'Absent Not Found',
            'data' => null
        ], 400); //Return message data Member kosong
    }
}
