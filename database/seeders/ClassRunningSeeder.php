<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassRunningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {  
        DB::table('class_runnings')->delete();
        DB::table('class_runnings')->insert([
            'id_class_running' => 1,
            'id_instructor' => '23.04.001',
            'id_class' => '1',
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'slot' => 10,
            'day' => 'MON'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 2,
            'id_instructor' => '23.04.002',
            'id_class' => '2',
            'start_time' => '09:30:00',
            'end_time' => '10:30:00',
            'slot' => 10,
            'day' => 'MON'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 3,
            'id_instructor' => '23.04.003',
            'id_class' => '3',
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'slot' => 10,
            'day' => 'TUE'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 4,
            'id_instructor' => '23.04.004',
            'id_class' => '4',
            'start_time' => '09:30:00',
            'end_time' => '10:30:00',
            'slot' => 10,
            'day' => 'TUE'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 5,
            'id_instructor' => '23.04.005',
            'id_class' => '5',
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'slot' => 10,
            'day' => 'WED'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 6,
            'id_instructor' => '23.04.006',
            'id_class' => '6',
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'slot' => 10,
            'day' => 'WED'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 7,
            'id_instructor' => '23.04.007',
            'id_class' => '7',
            'start_time' => '09:30:00',
            'end_time' => '10:30:00',
            'slot' => 10,
            'day' => 'WED'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 8,
            'id_instructor' => '23.04.008',
            'id_class' => '8',
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'slot' => 10,
            'day' => 'THU'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 9,
            'id_instructor' => '23.04.009',
            'id_class' => '9',
            'start_time' => '09:30:00',
            'end_time' => '10:30:00',
            'slot' => 10,
            'day' => 'THU'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 10,
            'id_instructor' => '23.04.009',
            'id_class' => '7',
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'slot' => 10,
            'day' => 'FRI'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 11,
            'id_instructor' => '23.04.010',
            'id_class' => '10',
            'start_time' => '09:30:00',
            'end_time' => '10:30:00',
            'slot' => 10,
            'day' => 'FRI'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 12,
            'id_instructor' => '23.04.011',
            'id_class' => '11',
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'slot' => 10,
            'day' => 'SAT'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 13,
            'id_instructor' => '23.04.003',
            'id_class' => '12',
            'start_time' => '09:30:00',
            'end_time' => '10:30:00',
            'slot' => 10,
            'day' => 'SAT'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 14,
            'id_instructor' => '23.04.002',
            'id_class' => '13',
            'start_time' => '09:30:00',
            'end_time' => '10:30:00',
            'slot' => 10,
            'day' => 'SAT'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 15,
            'id_instructor' => '23.04.001',
            'id_class' => '14',
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'slot' => 10,
            'day' => 'SUN'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 16,
            'id_instructor' => '23.04.010',
            'id_class' => '10',
            'start_time' => '17:00:00',
            'end_time' => '18:00:00',
            'slot' => 10,
            'day' => 'MON'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 17,
            'id_instructor' => '23.04.004',
            'id_class' => '15',
            'start_time' => '18:30:00',
            'end_time' => '19:30:00',
            'slot' => 10,
            'day' => 'MON'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 18,
            'id_instructor' => '23.04.001',
            'id_class' => '14',
            'start_time' => '17:00:00',
            'end_time' => '18:00:00',
            'slot' => 10,
            'day' => 'TUE'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 19,
            'id_instructor' => '23.04.007',
            'id_class' => '1',
            'start_time' => '17:00:00',
            'end_time' => '18:00:00',
            'slot' => 10,
            'day' => 'TUE'
        ]);
        
        DB::table('class_runnings')->insert([
            'id_class_running' => 20,
            'id_instructor' => '23.04.008',
            'id_class' => '16',
            'start_time' => '18:30:00',
            'end_time' => '19:30:00',
            'slot' => 10,
            'day' => 'TUE'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 21,
            'id_instructor' => '23.04.012',
            'id_class' => '17',
            'start_time' => '17:00:00',
            'end_time' => '18:00:00',
            'slot' => 10,
            'day' => 'WED'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 22,
            'id_instructor' => '23.04.009',
            'id_class' => '9',
            'start_time' => '17:00:00',
            'end_time' => '18:00:00',
            'slot' => 10,
            'day' => 'WED'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 23,
            'id_instructor' => '23.04.011',
            'id_class' => '11',
            'start_time' => '18:30:00',
            'end_time' => '19:30:00',
            'slot' => 10,
            'day' => 'WED'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 24,
            'id_instructor' => '23.04.006',
            'id_class' => '18',
            'start_time' => '17:00:00',
            'end_time' => '18:00:00',
            'slot' => 10,
            'day' => 'THU'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 25,
            'id_instructor' => '23.04.005',
            'id_class' => '5',
            'start_time' => '18:30:00',
            'end_time' => '19:30:00',
            'slot' => 10,
            'day' => 'THU'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 26,
            'id_instructor' => '23.04.006',
            'id_class' => '6',
            'start_time' => '17:00:00',
            'end_time' => '17:00:00',
            'slot' => 10,
            'day' => 'FRI'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 27,
            'id_instructor' => '23.04.002',
            'id_class' => '2',
            'start_time' => '18:30:00',
            'end_time' => '19:30:00',
            'slot' => 10,
            'day' => 'FRI'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 28,
            'id_instructor' => '23.04.012',
            'id_class' => '19',
            'start_time' => '17:00:00',
            'end_time' => '18:00:00',
            'slot' => 10,
            'day' => 'SAT'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 29,
            'id_instructor' => '23.04.011',
            'id_class' => '11',
            'start_time' => '17:00:00',
            'end_time' => '18:00:00',
            'slot' => 10,
            'day' => 'SAT'
        ]);

        DB::table('class_runnings')->insert([
            'id_class_running' => 30,
            'id_instructor' => '23.04.008',
            'id_class' => '16',
            'start_time' => '18:30:00',
            'end_time' => '19:30:00',
            'slot' => 10,
            'day' => 'SAT'
        ]);
    }
}
