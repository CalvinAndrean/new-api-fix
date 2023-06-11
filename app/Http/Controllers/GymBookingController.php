<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Member;
use App\Models\DepositClass;
use App\Models\ClassBooking;
use App\Models\ClassRunningDaily;
use App\Models\GymSession;
use App\Models\GymBooking;
use App\Models\InstructorPresensi;
use Validator;
use PDF;

class GymBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $GymBooking = GymBooking::with(['gym_session', 'member'])->get();
        // $GymBooking = GymBooking::all();

        if(!is_null($GymBooking)){
            return response([
                'message' => 'Success retrieve data',
                'data' => $GymBooking,
            ], 200);
        } else{
            return response([
                'message' => 'No data gym booking',
                'data' => null,
            ], 400);
        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request, $id_gym_session)
    {
        $storeData = $request->all(); // mengambil semua input dari web
        $validate = Validator::make($storeData, [
            'id_member' => 'required',
            'datetime_booking' => 'required|after:today'
        ]);

        if ($validate->fails()) {
            $firstError = $validate->errors()->first();
        
            return response(['message' => $firstError], 400);
        }

        $latest = DB::table('gym_bookings')->latest()->first();  //23.04.001
        if($latest == null){
            $incrementing = 1;
        } else{
            $incrementing = ((int)Str::substr($latest->id_gym_booking, 6, 3)) + 1;
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

        // udah pernah booking di tanggal $request->datetime_booking blom? bandingin datetime booking ada id_member itu
        // substring datetime_booking jadi cuma yyyy-mm-dd trus bandingin sama Carbon::now()->toDateString()
        $gym_booking = GymBooking::where('id_member', $request->id_member)->where('datetime_booking', $request->datetime_booking)->first();
        if(!is_null($gym_booking)){
            return response([
                'message' => 'Member sudah booking gym pada hari tersebut',
                'data' => $gym_booking
            ], 400);
        }

        // membernya aktif ga?
        $member = Member::where('id_member', $request->id_member)->first();
        if($member->expired_date < Carbon::now()->toDateString()){
            return response([
                'message' => 'Membership sudah expired',
                'data' => $member
            ], 400);
        }

        // slot nya masi ada ga?
        $gym_booking = GymBooking::where('datetime_booking', $request->datetime_booking)->where('id_gym_session', $id_gym_session)->get();
        $slot = 0;
        foreach($gym_booking as $data){
            $slot++;
        }
        if($slot == 10){
            return response([
                'message' => 'Slot gym pada sesi ini sudah penuh',
                'data' => $gym_session
            ], 400);
        }

        $GymBooking = GymBooking::create([
            'id_gym_booking' => $year.'.'.$month.'.'.$incrementing,
            'id_gym_session' => $id_gym_session,
            'id_member' => $request->id_member,
            'datetime_booking' => $request->datetime_booking,
            'datetime_presensi' => null,
        ]);
    }

    public function show($id_member)
    {
        // $gym_booking = GymBooking::with(['gym_session', 'member'])->where('id_member', $id_member)->get();

        $gym_booking = DB::select('SELECT * FROM gym_bookings a
        join gym_sessions b
        on a.id_gym_session = b.id_gym_session
        join members c
        on a.id_member = c.id_member
        WHERE a.id_member = "' . $id_member . '";');

        if(!is_null($gym_booking)){
            return response([
                'message' => 'Success retrieve data',
                'data' => $gym_booking
            ], 200);
        } else{
            return response([
                'message' => 'No data found',
                'data' => null,
            ], 400);
        }
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
    public function update(Request $request, $id_gym_booking)
    {
        $gym_booking = GymBooking::where('id_gym_booking', $id_gym_booking)->first();

        $gym_booking->datetime_presensi = Carbon::now();
        $gym_booking->save();
        
        return response([
            'message' => 'Berhasil presensi member gym',
            'data' => $gym_booking
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_gym_booking)
    {
        $gym_booking = GymBooking::with(['gym_session', 'member'])->where('id_gym_booking', $id_gym_booking)->first();
        $temp = $gym_booking;
        if($gym_booking->datetime_booking > Carbon::now()->toDateString()){
            if($gym_booking->delete()){
                return response([
                    'message' => 'Berhasil unbook gym',
                    'data' => $temp,
                ], 200);
            } else{
                return response([
                    'message' => 'Gagal unbook gym',
                    'data' => $temp
                ], 400);
            }
        } else{
            return response([
                'message' => 'Failed unbooking max H-1',
                'data' => $gym_booking
            ], 402);
        }
    }

    public function printReport($id_gym_booking){
        // $class_booking = DB::select('SELECT a.*, b.fullname as fullname_member, b.id_member, c.*, d.*, e.*, f.fullname as fullname_instructor FROM class_bookings a
        // join members b
        // on a.id_member = b.id_member
        // join class_running_dailies c
        // on a.id_class_running_daily = c.id_class_running_daily
        // join class_runnings d
        // on c.id_class_running = d.id_class_running
        // join class_details e
        // on d.id_class = e.id_class
        // join instructors f
        // on d.id_instructor = f.id_instructor
        // WHERE a.id_class_booking = "' . $id_class_booking . '";');

        $gym_booking = GymBooking::with(['gym_session', 'member'])->where('id_gym_booking', $id_gym_booking)->first();

        if($gym_booking->datetime_presensi != NULL){
            $data = [
                'title' => 'GoFit',
                'address_gym' => 'Jl. Centralpark No.10 Yogyakarta',
                'subtitle' => 'STRUK PRESENSI GYM',
                'no_struk' => $gym_booking->id_gym_booking,
                'tanggal' => $gym_booking->datetime_presensi,
                'member_id' => $gym_booking->member->id_member,
                'fullname' => $gym_booking->member->fullname,
                'slot_waktu_start' => $gym_booking->gym_session->start_time,
                'slot_waktu_end' => $gym_booking->gym_session->end_time,
            ];
            $pdf = PDF::loadview('report_booking_gym', $data);
            return $pdf->download('report_booking_gym.pdf');
        } else{
            return response([
                'message' => 'Member ini belum presensi',
                'data' => $gym_booking
            ], 401);
        }
    }

    public function showActivity($id_member){
        $gym_booking = DB::select('SELECT * FROM gym_bookings a
        join gym_sessions b
        on a.id_gym_session = b.id_gym_session
        join members c
        on a.id_member = c.id_member
        WHERE a.id_member = "' . $id_member . '"
        AND a.datetime_presensi IS NOT NULL;');

        if(!is_null($gym_booking)){
            return response([
                'message' => 'Success retrieve data',
                'data' => $gym_booking
            ], 200);
        } else{
            return response([
                'message' => 'No data found',
                'data' => null,
            ], 400);
        }
    }
}
