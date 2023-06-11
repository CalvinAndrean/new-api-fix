<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GymPresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $GymPresensi = DB::select('SELECT * FROM gym_presensis a
        join gym_bookings b
        on a.id_gym_booking = b.id_gym_booking
        join gym_sessions c
        on b.id_gym_session = c.id_gym_session');

        if(!is_null($GymPresensi)){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $GymPresensi
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
    public function store(Request $request, $id_gym_booking)
    {
        $storeData = $request->all(); // mengambil semua input dari web
        $validate = Validator::make($storeData, [
            'id_gym_booking'=>'required',
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $latest = DB::table('gym_presensis')->latest()->first();  //23.04.001
        if($latest == null){
            $incrementing = 1;
        } else{
            $incrementing = ((int)Str::substr($latest->id_gym_presensi, 6, 3)) + 1;
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

        $Presensi = GymPresensi::create([
            'id_gym_presensi'=> $year.'.'.$month.'.'.$incrementing,
            'id_gym_booking'=>$id_gym_booking,
            'datetime_presensi'=>Carbon::now().toDateString(),
        ]);

        return response([
            'message' => 'Add Presensi Success',
            'data' => $Presensi
        ], 200); //Return message data Member baru dalam bentuk JSON
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $GymPresensi = DB::select('SELECT * FROM gym_presensis a
        join gym_bookings b
        on a.id_gym_booking = b.id_gym_booking
        join gym_sessions c
        on b.id_gym_session = c.id_gym_session
        WHERE c.date = "' . Carbon::now().toDateString() . '";');

        if(!is_null($GymPresensi)){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $GymPresensi
            ], 200);
        } //Return data semua Member dalam bentuk JSON

        return response([
            'message' => 'Empty',
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

    public function createReport(Request $request, $id_gym_presensi){
        $GymPresensi = DB::select('SELECT * FROM gym_presensis a
        join gym_bookings b
        on a.id_gym_booking = b.id_gym_booking
        join members c
        on b.id_member = c.id_member
        WHERE a.id_gym_presensi = "' . $id_gym_presensi . '";');

        $data = [
            'title' => 'GoFit',
            'address_gym' => 'Jl. Centralpark No.10 Yogyakarta',
            'subtitle' => 'MEMBER CARD',
            'gym_presensi_id' => $GymPresensi->id_gym_presensi,
            'nama' => $GymPresensi->fullname,
            'alamat' => $GymPresensi->address,
            'telpon' => $GymPresensi->phone_number
        ];
	    $pdf = PDF::loadview('membercard', $data);
	    return $pdf->download('membercard.pdf');
    }
}
