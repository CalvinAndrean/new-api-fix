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

class ClassRunningDailyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $ClassRunningDaily = DB::table('class_running_dailies')
        //     ->join('class_runnings', 'class_running_dailies.id_class_running', '=', 'class_runnings.id_class_running')
        //     ->join('instructors', 'class_runnings.id_instructor', '=', 'instructors.id_instructor')
        //     // ->join('class_details', 'class_runnings.id_class', '=', 'class_details.id_class')
        //     ->select('class_running_dailies.*', 'class_runnings.*', 'instructors.*')
        //     ->get();
        
        $ClassRunningDaily = DB::select('SELECT a.*, b.start_time, b.day, c.fullname, d.class_name, d.price 
        FROM class_running_dailies a
        JOIN class_runnings b ON a.id_class_running = b.id_class_running
        JOIN instructors c ON b.id_instructor = c.id_instructor
        JOIN class_details d ON b.id_class = d.id_class
        WHERE a.date >= ? AND b.start_time >= ?
        ORDER BY a.id_class_running_daily ASC;', [Carbon::now()->toDateString(), Carbon::now()->toTimeString()]);
        
        // $ClassRunningDaily = ClassRunningDaily::with(['class_running.instructor', 'class_running.class_details'])->get();
        if(count($ClassRunningDaily) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $ClassRunningDaily
            ], 200);
        } //Return data semua ClassRunningDaily dalam bentuk JSON

        return response([
            'message' => 'Tidak ada ClassRunningDaily',
            'data' => null
        ], 400); //Return message data ClassRunningDaily kosong
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
        $checkNull = ClassRunningDaily::all();
        if(count($checkNull) > 0){
            $class_running_daily = ClassRunningDaily::with(['class_running'])->latest()->take(30)->get();
            $dateNextWeek = Carbon::now()->addDays(7)->toDateString();
            foreach($class_running_daily as $data){
                // $dateClassRunning = $data->date->addDays(7);
                foreach($class_running_daily as $check)
                    if($check->date == $dateNextWeek){
                        return response([
                            'message' => 'Jadwal untuk minggu depan sudah ter-generate',
                            'data' => $data
                        ], 400);
                    }
                $date = Carbon::createFromFormat('Y-m-d', $data->date);
                $ClassRunningDaily = ClassRunningDaily::create([
                    'id_class_running' => $data->id_class_running,
                    'slot' => 10,
                    'date' => $date->addDays(7),
                    'start_time' => '00:00:00',
                    'end_time' => '00:00:00',
                    'status' => 'Normal',
                ]);
                $InstructorPresensi = InstructorPresensi::create([
                    'id_instructor' => $data->class_running->id_instructor,
                    'start_class' => '00:00:00',
                    'end_class' => '00:00:00',
                    'id_class_running_daily' => $ClassRunningDaily->id_class_running_daily,
                    'is_presensi' => null
                ]);
                $checkLaporanAktivitasKelas = LaporanAktivitasKelas::all();
                $checked = 0;
                foreach($checkLaporanAktivitasKelas as $laporan){
                    if(($laporan->bulan == Carbon::parse($date->addDays(7))->format('F')) && ($laporan->tahun == Str::substr($date->addDays(7), 0, 4))){
                        $checked++;
                    }
                }

                if($checked == 0){
                    $LaporanAktivitasKelas = LaporanAktivitasKelas::create([
                        'bulan' => Carbon::parse($date->addDays(7))->format('F'),
                        'tahun' => Str::substr($date->addDays(7), 0, 4),
                    ]);
                    $LaporanKinerjaInstructor = LaporanKinerjaInstructor::create([
                        'bulan' => Carbon::parse($date->addDays(7))->format('F'),
                        'tahun' => Str::substr($date->addDays(7), 0, 4),
                    ]);
                }
            }
        } else{
            $nextWeek = Carbon::now()->addDays(7);
            $loopDay = $nextWeek->startOfWeek();
            $class_running = ClassRunning::all();

            foreach($class_running as $data){
                $dayString = ($loopDay->format('l'));
                if($data->day == strtoupper(Str::substr($dayString, 0, 3))){
                    $ClassRunningDaily = ClassRunningDaily::create([
                        'id_class_running' => $data->id_class_running,
                        'slot' => $data->slot,
                        'date' => $loopDay,
                        'start_time' => '00:00:00',
                        'end_time' => '00:00:00',
                        'status' => 'Normal',
                    ]);
                    $InstructorPresensi = InstructorPresensi::create([
                        'id_instructor' => $data->id_instructor,
                        'start_class' => '00:00:00',
                        'end_class' => '00:00:00',
                        'id_class_running_daily' => $ClassRunningDaily->id_class_running_daily,
                        'is_presensi' => "Belum presensi"
                    ]);
                    $checkLaporanAktivitasKelas = LaporanAktivitasKelas::all();
                    $checked = 0;
                    foreach($checkLaporanAktivitasKelas as $check){
                        if(($check->bulan == Carbon::parse($loopDay)->format('F')) && ($check->tahun == Str::substr($date->addDays(7), 0, 4))){
                            $checked++;
                        }
                    }

                    if($checked == 0){
                        $LaporanAktivitasKelas = LaporanAktivitasKelas::create([
                            'bulan' => Carbon::parse($loopDay)->format('F'),
                            'tahun' => Str::substr($loopDay, 0, 4),
                        ]);
                        $LaporanKinerjaInstructor = LaporanKinerjaInstructor::create([
                            'bulan' => Carbon::parse($loopDay)->format('F'),
                            'tahun' => Str::substr($loopDay, 0, 4),
                        ]);
                    }
                } else{
                    if($data->day == 'MON'){
                        $nextWeek = Carbon::now()->addDays(7);
                        $loopDay = $nextWeek->startOfWeek();
                        $ClassRunningDaily = ClassRunningDaily::create([
                            'id_class_running' => $data->id_class_running,
                            'slot' => $data->slot,
                            'date' => $loopDay,
                            'start_time' => '00:00:00',
                            'end_time' => '00:00:00',
                            'status' => 'Normal',
                        ]);
                        $InstructorPresensi = InstructorPresensi::create([
                            'id_instructor' => $data->id_instructor,
                            'start_class' => '00:00:00',
                            'end_class' => '00:00:00',
                            'id_class_running_daily' => $ClassRunningDaily->id_class_running_daily,
                            'is_presensi' => "Belum presensi"
                        ]);
                        $checkLaporanAktivitasKelas = LaporanAktivitasKelas::all();
                        $checked = 0;
                        foreach($checkLaporanAktivitasKelas as $check){
                            if(($check->bulan == Carbon::parse($loopDay)->format('F')) && ($check->tahun == Str::substr($date->addDays(7), 0, 4))){
                                $checked++;
                            }
                        }

                        if($checked == 0){
                            $LaporanAktivitasKelas = LaporanAktivitasKelas::create([
                                'bulan' => Carbon::parse($loopDay)->format('F'),
                                'tahun' => Str::substr($loopDay, 0, 4),
                            ]);
                            $LaporanKinerjaInstructor = LaporanKinerjaInstructor::create([
                                'bulan' => Carbon::parse($loopDay)->format('F'),
                                'tahun' => Str::substr($loopDay, 0, 4),
                            ]);
                        }
                    } else{
                        $loopDay->addDays(1);
                        $ClassRunningDaily = ClassRunningDaily::create([
                            'id_class_running' => $data->id_class_running,
                            'slot' => $data->slot,
                            'date' => $loopDay,
                            'start_time' => '00:00:00',
                            'end_time' => '00:00:00',
                            'status' => 'Normal',
                        ]);
                        $InstructorPresensi = InstructorPresensi::create([
                            'id_instructor' => $data->id_instructor,
                            'start_class' => '00:00:00',
                            'end_class' => '00:00:00',
                            'id_class_running_daily' => $ClassRunningDaily->id_class_running_daily,
                            'is_presensi' => "Belum presensi"
                        ]);
                        $checkLaporanAktivitasKelas = LaporanAktivitasKelas::all();
                        $checked = 0;
                        foreach($checkLaporanAktivitasKelas as $check){
                            if(($check->bulan == Carbon::parse($loopDay)->format('F')) && ($check->tahun == Str::substr($date->addDays(7), 0, 4))){
                                $checked++;
                            }
                        }

                        if($checked == 0){
                            $LaporanAktivitasKelas = LaporanAktivitasKelas::create([
                                'bulan' => Carbon::parse($loopDay)->format('F'),
                                'tahun' => Str::substr($loopDay, 0, 4),
                            ]);
                            $LaporanKinerjaInstructor = LaporanKinerjaInstructor::create([
                                'bulan' => Carbon::parse($loopDay)->format('F'),
                                'tahun' => Str::substr($loopDay, 0, 4),
                            ]);
                        }
                    }
                    
                }
            }
        }
        $class_running_new = ClassRunningDaily::latest()->take(30)->get();

        return response([
            'message' => 'Add ClassRunningDaily Success',
            'data' => $class_running_new
        ], 200); //Return message data ClassRunningDaily baru dalam bentuk JSON
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id_instructor)
    {
        $ClassRunningDaily = DB::select('SELECT * FROM class_running_dailies a
        join class_runnings b
        on a.id_class_running = b.id_class_running
        join instructors c
        on b.id_instructor = c.id_instructor
        join class_details d
        on b.id_class = d.id_class
        WHERE b.id_instructor = "' . $id_instructor . '";');

        // $ClassRunningDaily = DB::select('SELECT * FROM instructors a
        // join class_runnings b
        // on b.id_instructor = a.id_instructor
        // join class_running_dailies c
        // on b.id_class_running = c.id_class_running
        // join class_details d
        // on b.id_class on d.id_class
        // WHERE a.id_instructor = "' . $id_instructor . '";');

        if(count($ClassRunningDaily) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $ClassRunningDaily
            ], 200);
        } //Return data semua ClassRunningDaily dalam bentuk JSON

        return response([
            'message' => 'Tidak ada ClassRunningDaily',
            'data' => null
        ], 400); //Return message data ClassRunningDaily kosong
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id_class_running_daily)
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
    public function update(Request $request, $id_class_running_daily)
    {
        $ClassRunningDaily = ClassRunningDaily::find($id_class_running_daily); //Mencari data Member berdasarkan id

        if(is_null($ClassRunningDaily)){
            return response([
                'message' => 'ClassRunningDaily Not Found',
                'data' => null
            ], 404);
        } //Return message saat data ClassRunningDaily tidak ditemukan

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'status' => 'required'
        ]);
        // Bisa ditambahin rule validasi input
            // 'nama_customer.regex' => 'Inputan tidak boleh mengandung angka atau simbol lain',
            // 'tgl_lahir.date_format' => 'Seharusnya YYYY-MM-DD',
            // 'email_customer.email' => 'Format email salah',
            // 'upload_berkas.mimes' => 'Hanya bisa menampung jpg, png, jpeg saja',
            // 'upload_berkas.image' => 'Hanya bisa menerima image',
            // 'nomor_kartupengenal' => 'Hanya bisa menerima angka',
            // 'no_sim.numeric' => 'Hanya bisa menerima angka',
            // 'usia_customer.numeric' => 'Hanya bisa menerima angka'


        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $ClassRunningDaily->status = $updateData['status'];

        if($ClassRunningDaily->save()){
            return response([
                'message' => 'Update ClassRunningDaily Success',
                'data' => $ClassRunningDaily
            ], 200);
        } //Return data ClassRunningDaily yang telah di EDIT dalam bentuk JSON

        return response([
            'message' => 'Update ClassRunningDaily Failed',
            'data' => null
        ], 400);
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

    // instructor
    // class_running
    // class_details
    public function forAbsent()
    {
        // $ClassRunningDaily = DB::table('class_running_dailies')
        //     ->join('class_runnings', 'class_running_dailies.id_class_running', '=', 'class_runnings.id_class_running')
        //     ->join('instructors', 'class_runnings.id_instructor', '=', 'instructors.id_instructor')
        //     // ->join('class_details', 'class_runnings.id_class', '=', 'class_details.id_class')
        //     ->select('class_running_dailies.*', 'class_runnings.*', 'instructors.*')
        //     ->get();
        $id_instructor = "23.04.001";
        $ClassRunningDaily = DB::select('SELECT * FROM class_runnings a
        join class_running_dailies b
        on a.id_class_running = b.id_class_running
        join instructors c
        on a.id_instructor = c.id_instructor
        join class_details d
        on a.id_class = d.id_class
        WHERE a.id_instructor = "' . $id_instructor . '";');

        if(count($ClassRunningDaily) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $ClassRunningDaily
            ], 200);
        } //Return data semua ClassRunningDaily dalam bentuk JSON

        return response([
            'message' => 'Tidak ada ClassRunningDaily',
            'data' => null
        ], 400); //Return message data ClassRunningDaily kosong
    }

    public function updateStartTime($id_class_running_daily){

        $class_running_daily = ClassRunningDaily::where('id_class_running_daily', $id_class_running_daily)->first();
        if($class_running_daily->start_time != '00:00:00'){
            return response([
                'message' => 'Start time sudah diupdate',
                'data' => $class_running_daily
            ], 400);
        }
        // if($class_running_daily->start_time == "00:00:00"){
            $all_booking = ClassBooking::where('id_class_running_daily', $id_class_running_daily)->get();
            foreach($all_booking as $data){

                // dapet price dari $checkPrice->price
                $checkPrice = ClassRunningDaily::with('class_running.class_details')->where('id_class_running_daily', $id_class_running_daily)->first();

                // dapet id_member buat potong cash_depositnya sama buat cek kepunyaan kelas di deposit_classes
                $member = Member::where('id_member', $data->id_member)->first();

                // cari deposit class dari id_member && id_class
                $deposit_class = DepositClass::where('id_member', $data->id_member)->where('id_class', $checkPrice->class_running->class_details->id_class)->first();
                if($data->payment_type == "Paket"){
                    $deposit_class->total_amount -= 1;
                    $deposit_class->save();
                } else if($data->payment_type == "Cash"){
                    $member->cash_deposit -= $checkPrice->class_running->class_details->price;
                    $member->save();
                }
            }

            $class_running_daily->start_time = Carbon::now()->toTimeString();
            $class_running_daily->save();

            // sekalian presensi instructor
            $presensi_instructor = InstructorPresensi::where('id_class_running_daily', $id_class_running_daily)->first();
            $presensi_instructor->start_class = Carbon::now()->toTimeString();
            $presensi_instructor->is_presensi = "Presensi";
            $presensi_instructor->save();

            // mau ambil instructor dari class_running_daily yang di presensi
            $parent = ClassRunningDaily::with(['class_running'])->where('id_class_running_daily', $id_class_running_daily)->first();
            $instructor = Instructor::where('id_instructor', $parent->class_running->id_instructor)->first();

            $startTime = Carbon::createFromFormat('H:i:s', $parent->start_time);
            $endTime = Carbon::createFromFormat('H:i:s', $parent->class_running->start_time);

            // bandingkan waktu di class_running_daily dengan waktu di class running -> cari different nya
            $total_in_second = $startTime->diffInSeconds($endTime);

            // convert second dalam bentuk 00:00:00
            gmdate('H:i:s', $total_in_second);

            $instructor->total_late = $total_in_second;
            $instructor->save();

            return response([
                'message' => 'Success update start time',
                'data' => $class_running_daily
            ], 200);
        // } else{
        //     return response([
        //         'message' => 'Kelas sudah dimulai',
        //         'data' => $class_running_daily
        //     ], 400);
        // }
    }

    public function updateEndTime($id_class_running_daily){
        $class_running_daily = ClassRunningDaily::find($id_class_running_daily)->first();
        $presensi_instructor = InstructorPresensi::where('id_class_running_daily', $id_class_running_daily)->first();

        if($presensi_instructor->end_class == '00:00:00'){
            $class_running_daily->end_time = Carbon::now()->toTimeString();
            $presensi_instructor->end_class = Carbon::now()->toTimeString();
            $presensi_instructor->save();
            $class_running_daily->save();
        } else{
            return response([
                'message' => 'End time sudah terisi',
                'data' => $presensi_instructor
            ], 400);
        }

        return response([
            'message' => 'Success update end time',
            'data' => $class_running_daily
        ], 200);
    }

    // buat nampilin class_running_daily pas instructor login -> kasih inside class sekalian nanti pas diklik (ClassBookingController)
    public function showClassToday($id_instructor){
        $class_today = DB::select('SELECT * FROM class_running_dailies a
        join class_runnings b
        on a.id_class_running = b.id_class_running
        join class_details c
        on b.id_class = c.id_class
        join instructors d
        on b.id_instructor = d.id_instructor
        WHERE b.id_instructor = "' . $id_instructor . '"
        AND a.date = "' . Carbon::now()->toDateString() . '";');

        return response([
            'message' => 'Success retrieve data',
            'data' => $class_today
        ], 200);
    }

    public function showClassTodayMO($id_instructor){
        $class_today = DB::select('SELECT * FROM class_running_dailies a
        join class_runnings b
        on a.id_class_running = b.id_class_running
        join class_details c
        on b.id_class = c.id_class
        join instructors d
        on b.id_instructor = d.id_instructor
        WHERE a.date = "' . Carbon::now()->toDateString() . '";');

        return response([
            'message' => 'Success retrieve data',
            'data' => $class_today
        ], 200);
    }

    public function showForUmum($id_class_running_daily){
        $class_running_daily = ClassRunningDaily::with(['class_running.class_details', 'instructor'])->get();

        return response(['data' => $class_running_daily], 200);
    }
}
