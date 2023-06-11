<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ClassRunningDaily;

class ClassDailySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('class_running_dailies')->delete();
        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '1',
            'id_class_running' => '1',
            'date' => '2023-05-01',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '2',
            'id_class_running' => '2',
            'date' => '2023-05-01',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '3',
            'id_class_running' => '3',
            'date' => '2023-05-02',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '4',
            'id_class_running' => '4',
            'date' => '2023-05-02',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '5',
            'id_class_running' => '5',
            'date' => '2023-05-03',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '6',
            'id_class_running' => '6',
            'date' => '2023-05-03',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '7',
            'id_class_running' => '7',
            'date' => '2023-05-03',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '8',
            'id_class_running' => '8',
            'date' => '2023-05-04',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '9',
            'id_class_running' => '9',
            'date' => '2023-05-04',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '10',
            'id_class_running' => '10',
            'date' => '2023-05-05',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '11',
            'id_class_running' => '11',
            'date' => '2023-05-05',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '12',
            'id_class_running' => '12',
            'date' => '2023-05-06',
            'status' => 'Normal'
        ]);
        
        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '13',
            'id_class_running' => '13',
            'date' => '2023-05-06',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '14',
            'id_class_running' => '14',
            'date' => '2023-05-06',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '15',
            'id_class_running' => '15',
            'date' => '2023-05-07',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '16',
            'id_class_running' => '16',
            'date' => '2023-05-01',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '17',
            'id_class_running' => '17',
            'date' => '2023-05-01',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '18',
            'id_class_running' => '18',
            'date' => '2023-05-02',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '19',
            'id_class_running' => '19',
            'date' => '2023-05-02',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '20',
            'id_class_running' => '20',
            'date' => '2023-05-02',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '21',
            'id_class_running' => '21',
            'date' => '2023-05-03',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '22',
            'id_class_running' => '22',
            'date' => '2023-05-03',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '23',
            'id_class_running' => '23',
            'date' => '2023-05-03',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '24',
            'id_class_running' => '24',
            'date' => '2023-05-04',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '25',
            'id_class_running' => '25',
            'date' => '2023-05-04',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '26',
            'id_class_running' => '26',
            'date' => '2023-05-05',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '27',
            'id_class_running' => '27',
            'date' => '2023-05-05',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '28',
            'id_class_running' => '28',
            'date' => '2023-05-06',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '29',
            'id_class_running' => '29',
            'date' => '2023-05-06',
            'status' => 'Normal'
        ]);

        DB::table('class_running_dailies')->insert([
            'id_class_running_daily' => '30',
            'id_class_running' => '30',
            'date' => '2023-05-06',
            'status' => 'Normal'
        ]);
    }
}
