<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebConfigsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('webconfigs')->insert([
          'name' => 'API_url',
          'value1' => 'https://8d3d711161a58383f6e96363046d668e:f8255dd7bd547996d2327409022274ad@h987679.myshopify.com/admin/'
        ]);

        DB::table('webconfigs')->insert([
          'name' => 'API_key',
          'value1' => '8d3d711161a58383f6e96363046d668e'
        ]);

        DB::table('webconfigs')->insert([
          'name' => 'API_pass',
          'value1' => 'f8255dd7bd547996d2327409022274ad'
        ]);

        DB::table('webconfigs')->insert([
          'name' => 'shared_secret',
          'value1' => 'a90b00ca22a055eb2a03b012e2126406'
        ]);

        DB::table('webconfigs')->insert([
          'name' => 'total_best',
          'value1' => '20'
        ]);

    }
}
