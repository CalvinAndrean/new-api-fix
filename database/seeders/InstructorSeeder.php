<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('instructors')->delete();

        DB::table('instructors')->insert([
            'id_instructor' => '23.04.001',
            'email' => 'joon@gmail.com',
            'password' => 'joon',
            'fullname' => 'Joon',
            'birth_date' => '2001-07-23',
            'address' => 'Jalan Pemukiman',
            'phone_number' => '082138768113',
            'total_late' => '00:00:00'
        ]);

        DB::table('instructors')->insert([
            'id_instructor' => '23.04.002',
            'email' => 'jk@gmail.com',
            'password' => 'jk',
            'fullname' => 'JK',
            'birth_date' => '2002-01-12',
            'address' => 'Jalan Outer',
            'phone_number' => '082918273817',
            'total_late' => '00:00:00'
        ]);

        DB::table('instructors')->insert([
            'id_instructor' => '23.04.003',
            'email' => 'lisa@gmail.com',
            'password' => 'lisa',
            'fullname' => 'Lisa',
            'birth_date' => '2001-09-22',
            'address' => 'Jalan Layang',
            'phone_number' => '081728371827',
            'total_late' => '00:00:00'
        ]);

        DB::table('instructors')->insert([
            'id_instructor' => '23.04.004',
            'email' => 'hobby@gmail.com',
            'password' => 'hobby',
            'fullname' => 'Hobby',
            'birth_date' => '2005-01-20',
            'address' => 'Perumahan Wayang',
            'phone_number' => '082817283746',
            'total_late' => '00:00:00'
        ]);

        DB::table('instructors')->insert([
            'id_instructor' => '23.04.005',
            'email' => 'v@gmail.com',
            'password' => 'v',
            'fullname' => 'V',
            'birth_date' => '2002-12-09',
            'address' => 'Jalan Sidomukti',
            'phone_number' => '081263712839',
            'total_late' => '00:00:00'
        ]);

        DB::table('instructors')->insert([
            'id_instructor' => '23.04.006',
            'email' => 'jenny@gmail.com',
            'password' => 'jenny',
            'fullname' => 'Jenny',
            'birth_date' => '2002-01-20',
            'address' => 'Perumahan Bappenas',
            'phone_number' => '081928391827',
            'total_late' => '00:00:00'
        ]);

        DB::table('instructors')->insert([
            'id_instructor' => '23.04.007',
            'email' => 'suga@gmail.com',
            'password' => 'suga',
            'fullname' => 'Suga',
            'birth_date' => '2002-02-20',
            'address' => 'Jalan Bantul',
            'phone_number' => '082919283784',
            'total_late' => '00:00:00'
        ]);

        DB::table('instructors')->insert([
            'id_instructor' => '23.04.008',
            'email' => 'rose@gmail.com',
            'password' => 'rose',
            'fullname' => 'Rose',
            'birth_date' => '2003-12-23',
            'address' => 'Jalan Lingkar',
            'phone_number' => '081920391028',
            'total_late' => '00:00:00'
        ]);

        DB::table('instructors')->insert([
            'id_instructor' => '23.04.009',
            'email' => 'jin@gmail.com',
            'password' => 'jin',
            'fullname' => 'Jin',
            'birth_date' => '2003-12-23',
            'address' => 'Jalan Lingkar',
            'phone_number' => '081920391028',
            'total_late' => '00:00:00'
        ]);

        DB::table('instructors')->insert([
            'id_instructor' => '23.04.010',
            'email' => 'jisoo@gmail.com',
            'password' => 'jisoo',
            'fullname' => 'Jisoo',
            'birth_date' => '2003-12-23',
            'address' => 'Jalan Lingkar',
            'phone_number' => '081920391028',
            'total_late' => '00:00:00'
        ]);

        DB::table('instructors')->insert([
            'id_instructor' => '23.04.011',
            'email' => 'jimin@gmail.com',
            'password' => 'jimin',
            'fullname' => 'Jimin',
            'birth_date' => '2003-12-23',
            'address' => 'Jalan Lingkar',
            'phone_number' => '081920391028',
            'total_late' => '00:00:00'
        ]);

        DB::table('instructors')->insert([
            'id_instructor' => '23.04.012',
            'email' => 'jessi@gmail.com',
            'password' => 'jessi',
            'fullname' => 'Jessi',
            'birth_date' => '2003-12-23',
            'address' => 'Jalan Lingkar',
            'phone_number' => '081920391028',
            'total_late' => '00:00:00'
        ]);
    }
}
