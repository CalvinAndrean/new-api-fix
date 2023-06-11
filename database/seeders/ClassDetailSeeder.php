<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('class_details')->insert([
            'id_class' => '1',
            'class_name' => 'Spine Corrector',
            'price' => 150000
        ]);

        DB::table('class_details')->insert([
            'id_class' => '2',
            'class_name' => 'Muay Thai',
            'price' => 150000
        ]);

        DB::table('class_details')->insert([
            'id_class' => '3',
            'class_name' => 'Pilates',
            'price' => 150000
        ]);

        DB::table('class_details')->insert([
            'id_class' => '4',
            'class_name' => 'Asthanga',
            'price' => 150000
        ]);

        DB::table('class_details')->insert([
            'id_class' => '5',
            'class_name' => 'Body Combat',
            'price' => 150000
        ]);

        DB::table('class_details')->insert([
            'id_class' => '6',
            'class_name' => 'Zumba',
            'price' => 150000
        ]);

        DB::table('class_details')->insert([
            'id_class' => '7',
            'class_name' => 'Hatha',
            'price' => 150000
        ]);

        DB::table('class_details')->insert([
            'id_class' => '8',
            'class_name' => 'Wall Swing',
            'price' => 150000
        ]);

        DB::table('class_details')->insert([
            'id_class' => '9',
            'class_name' => 'Basic Swing',
            'price' => 150000
        ]);

        DB::table('class_details')->insert([
            'id_class' => '10',
            'class_name' => 'Bellydance',
            'price' => 150000
        ]);

        DB::table('class_details')->insert([
            'id_class' => '11',
            'class_name' => 'Bungee',
            'price' => 200000
        ]);

        DB::table('class_details')->insert([
            'id_class' => '12',
            'class_name' => 'Yogalates',
            'price' => 150000
        ]);

        DB::table('class_details')->insert([
            'id_class' => '13',
            'class_name' => 'Boxing',
            'price' => 150000
        ]);

        DB::table('class_details')->insert([
            'id_class' => '14',
            'class_name' => 'Calisthenics',
            'price' => 150000
        ]);

        DB::table('class_details')->insert([
            'id_class' => '15',
            'class_name' => 'Pound Fit',
            'price' => 150000
        ]);

        DB::table('class_details')->insert([
            'id_class' => '16',
            'class_name' => 'Trampoline Workout',
            'price' => 200000
        ]);

        DB::table('class_details')->insert([
            'id_class' => '17',
            'class_name' => 'Yoga For Kids',
            'price' => 150000
        ]);

        DB::table('class_details')->insert([
            'id_class' => '18',
            'class_name' => 'Abs Pilates',
            'price' => 150000
        ]);

        DB::table('class_details')->insert([
            'id_class' => '19',
            'class_name' => 'Swing For Kids',
            'price' => 150000
        ]);
        
    }
}
