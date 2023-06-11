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
use App\Models\InstructorPresensi;
use Validator;
use PDF;

class ClassBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $ClassBooking = ClassBooking::with(['member', 'class_running_daily']);
        $ClassBooking = DB::select('SELECT * FROM class_bookings a
        join class_running_dailies b
        on a.id_class_running_daily = b.id_class_running_daily
        join members c
        on a.id_member = c.id_member
        join class_runnings d
        on b.id_class_running = d.id_class_running
        join class_details e
        on d.id_class = e.id_class;');

        if(count($ClassBooking) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $ClassBooking
            ], 200);
        } //Return data semua ClassBooking dalam bentuk JSON

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); //Return message data ClassBooking kosong
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
        $latest = DB::table('class_bookings')->latest()->first();  //23.04.001
        if($latest == null){
            $incrementing = 1;
        } else{
            $incrementing = ((int)Str::substr($latest->id_class_booking, 6, 3)) + 1;
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

        $storeData = $request->all(); // mengambil semua input dari web
        $validate = Validator::make($storeData, [ 
            // 'id_class_booking' => 'required',
            // 'id_class_running_daily' => 'required',
            // 'id_member' => 'required',
            // 'datetime' => 'required',
            // 'payment_type' => 'required',
            //KURANG GENERATE NOMOR BOOKING PAKE STRING
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 405); //Return error invalid input
        }

        $member = Member::where('id_member', $id_member)->first();
        $depoClass = DB::select('SELECT * FROM deposit_classes a
        join class_details b
        on a.id_class = b.id_class
        WHERE a.id_member = "' . $id_member . '";');

        // $ClassRunningDaily = ClassRunningDaily::with(['members', 'class_runnings'])->get();
        // $ClassRunningDaily = DB::select('SELECT a.*, b.id_class FROM class_running_dailies a
        // join class_runnings b
        // on a.id_class_running = b.id_class_running
        // WHERE a.id_class_running_daily = "' . $request->id_class_running_daily . '";');

        $ClassRunningDaily = ClassRunningDaily::with(['class_running.class_details'])->where('id_class_running_daily', $request->id_class_running_daily)->first();
        // return response([
        //     'data' => $ClassRunningDaily
        // ], 200);

        $ClassBooking = ClassBooking::all();

        foreach($ClassBooking as $data){
            if(($data->id_class_running_daily == $request->id_class_running_daily) && ($data->id_member == $id_member)){
                return response([
                    'message' => 'Anda sudah booking kelas ini',
                    'data' => $data
                ], 403);
            }
        }

        // deposit_class harus berkurang -- slot di classrunningdaily harus berkurang

        if($ClassRunningDaily->class_running->slot > 0){
            foreach($depoClass as $data){
                if($ClassRunningDaily->class_running->id_class == $data->id_class && $data->id_member == $request->id_member){ // 2023-07-01
                    if($data->expired_date >= Str::substr(Carbon::now()->toDateString(), 0, 9) && $data->total_amount > 0){
                        $data->total_amount--; // Decrement nanti di presensi
                        $booking = ClassBooking::create([
                            'id_class_booking' => $year . '.' . $month . '.' . $incrementing,
                            'id_class_running_daily'=>$request->id_class_running_daily,
                            'id_member'=>$request->id_member,
                            'datetime'=>Carbon::now()->toDateString(),
                            'payment_type'=>'Paket'
                        ]);
                        $slotMinus = $ClassRunningDaily->class_running->slot - 1;
                        // DB::table('class_running_dailies')->where('id_class_running_daily', $request->id_class_running_daily)->update(['slot' => $slotMinus]);
                        $ClassRunningDaily->slot -= 1;
                        $ClassRunningDaily->save();
                        // $ClassRunningDaily[0]->slot -= 1;
                        // $ClassRunningDaily[0]->save();
                        return response([
                            'message' => 'Berhasil mengikuti kelas dengan pemotongan deposit kelas',
                            'data' => $ClassRunningDaily
                        ], 200);
                    }
                }
            }
            // Pengurangan deposit cash jika tidak memiliki deposit kelas dengan id_class tersebut
            // foreach($depoClass as $data){
            //     if($ClassRunningDaily->class_running->id_class == $data->id_class){
                    if($member->cash_deposit >= $ClassRunningDaily->class_running->class_details->price){
                        $booking = ClassBooking::create([
                            'id_class_booking' => $year . '.' . $month . '.' . $incrementing,
                            'id_class_running_daily'=>$request->id_class_running_daily,
                            'id_member'=>$request->id_member,
                            'datetime'=>Carbon::now()->toDateString(),
                            'payment_type'=>'Cash',
                        ]);
                        $slotMinus = $ClassRunningDaily->class_running->slot - 1;
                        // DB::table('class_running_dailies')->where('id_class_running_daily', $request->id_class_running_daily)->update(['slot' => $slotMinus]);
                        $ClassRunningDaily->slot -= 1;
                        $ClassRunningDaily->save();
                        return response([
                            'message' => 'Berhasil mengikuti kelas dengan pemotongan deposit cash',
                            'data' => $data
                        ], 200);
                    } else{
                        return response([
                            'message' => 'Deposit Cash member tidak mencukupi untuk booking kelas',
                            'data' => $data
                        ], 401);
                    }
            //     }
            // }
        } else{
            return response([
                'message' => 'Slot sudah habis',
                'data' => $ClassRunningDaily
            ], 402);
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
        $class_booked = DB::select('SELECT * FROM class_bookings a
        join class_running_dailies b
        on a.id_class_running_daily = b.id_class_running_daily
        join class_runnings c
        on b.id_class_running = c.id_class_running
        join instructors d
        on c.id_instructor = d.id_instructor
        join class_details e
        on c.id_class = e.id_class
        WHERE a.id_member = "' . $id_member . '";');

        if(count($class_booked) > 0){
            return response([
                'message' => 'Berhasil mendapatkan data booking',
                'data' => $class_booked
            ], 200);
        } //Return data semua Member dalam bentuk JSON

        return response([
            'message' => 'Tidak ada booking',
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
    public function destroy($id_class_booking)
    {
        $class_booking = ClassBooking::find($id_class_booking);

        if(is_null($class_booking)){
            return response([
                'message' => 'Tidak dapat menemukan booking',
                'date' => null
            ], 404);
        } //Return message saat data Member tidak ditemukan
        
        $id_class_running_daily = $class_booking->id_class_running_daily;
        $class_running_daily = ClassRunningDaily::find($id_class_running_daily);

        if($class_running_daily->date > Carbon::now()->toDateString()){
            if($class_booking->delete()){
                // UPDATE SLOT +1
                $class_running_daily->slot++;
                $class_running_daily->save();
                return response([
                    'message' => 'Delete Class Booking Success',
                    'data' => $class_booking
                ], 200);
            } //Return message saat berhasil menghapus data Member
        } else{
            return response([
                'message' => 'Cannot delete booking H-1',
                'data' => $class_booking,
            ], 400);
        }
    }

    // buat nampilin seluruh booking pada salah satu class_running_daily
    public function showInsideClass($id_class_running_daily){
        $class_inside = DB::select('SELECT a.*, b.*, c.*, d.fullname as fullname_instructor, e.*, f.fullname as fullname_member FROM class_bookings a
        join class_running_dailies b
        on a.id_class_running_daily = b.id_class_running_daily
        join class_runnings c
        on b.id_class_running = c.id_class_running
        join instructors d
        on c.id_instructor = d.id_instructor
        join class_details e
        on c.id_class = e.id_class
        join members f
        on a.id_member = f.id_member
        WHERE a.id_class_running_daily = "' . $id_class_running_daily . '";');

        return response([
            'message' => 'Success retrieve inside class',
            'data' => $class_inside,
        ], 200);
    }

    public function printReportDepositCash($id_class_booking){
        // $class_booking = ClassBooking::find($id_class_booking);

        $class_booking = DB::select('SELECT a.*, b.fullname as fullname_member, b.id_member, b.cash_deposit, c.*, d.*, e.*, f.fullname as fullname_instructor FROM class_bookings a
        join members b
        on a.id_member = b.id_member
        join class_running_dailies c
        on a.id_class_running_daily = c.id_class_running_daily
        join class_runnings d
        on c.id_class_running = d.id_class_running
        join class_details e
        on d.id_class = e.id_class
        join instructors f
        on d.id_instructor = f.id_instructor
        WHERE a.id_class_booking = "' . $id_class_booking . '";');

        if($class_booking[0]->datetime_presensi != NULL){
            $data = [
                'title' => 'GoFit',
                'address_gym' => 'Jl. Centralpark No.10 Yogyakarta',
                'subtitle' => 'STRUK PRESENSI KELAS',
                'no_struk' => $class_booking[0]->id_class_booking,
                'tanggal' => $class_booking[0]->datetime_presensi,
                'member_id' => $class_booking[0]->id_member,
                'fullname_member' => $class_booking[0]->fullname_member,
                'class_name' => $class_booking[0]->class_name,
                'fullname_instructor' => $class_booking[0]->fullname_instructor,
                'price' => $class_booking[0]->price,
                'remaining_deposit' => $class_booking[0]->cash_deposit

            ];
            $pdf = PDF::loadview('report_booking_deposit_cash', $data);
            return $pdf->download('report_booking_deposit_cash.pdf');
        } else{
            return response([
                'message' => 'Member ini belum presensi',
                'data' => $class_booking
            ], 401);
        }
    }

    public function printReportDepositClass($id_class_booking){
        $class_booking = DB::select('SELECT a.*, b.fullname as fullname_member, b.id_member, c.*, d.*, e.*, f.fullname as fullname_instructor FROM class_bookings a
        join members b
        on a.id_member = b.id_member
        join class_running_dailies c
        on a.id_class_running_daily = c.id_class_running_daily
        join class_runnings d
        on c.id_class_running = d.id_class_running
        join class_details e
        on d.id_class = e.id_class
        join instructors f
        on d.id_instructor = f.id_instructor
        WHERE a.id_class_booking = "' . $id_class_booking . '";');

        $deposit_class = DB::select('SELECT * FROM deposit_classes a
        join members b
        on a.id_member = b.id_member
        join class_details c
        on a.id_class = c.id_class
        WHERE a.id_member = "' . $class_booking[0]->id_member . '"
        AND a.id_class = "' . $class_booking[0]->id_class . '";');

        if($class_booking[0]->datetime_presensi != NULL){
            $data = [
                'title' => 'GoFit',
                'address_gym' => 'Jl. Centralpark No.10 Yogyakarta',
                'subtitle' => 'STRUK PRESENSI KELAS PAKET',
                'no_struk' => $class_booking[0]->id_class_booking,
                'tanggal' => $class_booking[0]->datetime_presensi,
                'member_id' => $class_booking[0]->id_member,
                'fullname_member' => $class_booking[0]->fullname_member,
                'class_name' => $class_booking[0]->class_name,
                'fullname_instructor' => $class_booking[0]->fullname_instructor,
                'remaining_deposit' => $deposit_class[0]->total_amount,
                'expired_date' => $deposit_class[0]->expired_date
            ];
            $pdf = PDF::loadview('report_booking_deposit_class', $data);
            return $pdf->download('report_booking_deposit_class.pdf');
        } else{
            return response([
                'message' => 'Member ini belum presensi',
                'data' => $class_booking
            ], 401);
        }
    }

    public function presensi($id_class_booking){
        $class_booking = ClassBooking::find($id_class_booking);
        $presensi_instructor = InstructorPresensi::where('id_class_running_daily', $class_booking->id_class_running_daily)->first();
        if($class_booking->datetime_presensi == null){
            if(!is_null($presensi_instructor)){
                if($presensi_instructor->start_class != "00:00:00"){
                    $class_booking->datetime_presensi = Carbon::now();
                    $class_booking->save();
    
                    return response([
                        'message' => 'Success presensi member',
                        'data' => $class_booking
                    ], 200);
                } else{
                    return response([
                        'message' => 'Instructor belum dipresensi',
                        'data' => $presensi_instructor
                    ], 400);
                }
                
            } else{
                return response([
                    'message' => 'Tidak ada data presensi',
                    'data' => null
                ], 400);
            }
        } else{
            return response([
                'message' => 'Member ini sudah di presensi',
                'data' => $class_booking
            ], 400);
        }
    }

    public function showActivity($id_member){
        $class_booked = DB::select('SELECT * FROM class_bookings a
        join class_running_dailies b
        on a.id_class_running_daily = b.id_class_running_daily
        join class_runnings c
        on b.id_class_running = c.id_class_running
        join instructors d
        on c.id_instructor = d.id_instructor
        join class_details e
        on c.id_class = e.id_class
        WHERE a.id_member = "' . $id_member . '"
        AND a.datetime_presensi IS NOT NULL;');

        if(count($class_booked) > 0){
            return response([
                'message' => 'Berhasil mendapatkan data booking',
                'data' => $class_booked
            ], 200);
        } //Return data semua Member dalam bentuk JSON

        return response([
            'message' => 'Tidak ada booking',
            'data' => null
        ], 400); //Return message data Member kosong
    }
}
