<?php

use Illuminate\Database\Seeder;

class SectionTableSeeder extends Seeder
{
    /**
     * 科室
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('sections')->delete();
        \DB::table('sections')->insert([
            [
                'name' => '肿瘤科',
                'sort' => '1',
                'status' => '1',
            ],
            [
                'name' => '心内科',
                'sort' => '1',
                'status' => '1',
            ],
            [
                'name' => '妇科',
                'sort' => '1',
                'status' => '1',
            ],
            [
                'name' => '儿科',
                'sort' => '1',
                'status' => '1',
            ],
            [
                'name' => '呼吸科',
                'sort' => '1',
                'status' => '0',
            ],
            [
                'name' => '消化科',
                'sort' => '1',
                'status' => '0',
            ],
            [
                'name' => '眼科',
                'sort' => '1',
                'status' => '0',
            ],
            [
                'name' => '肾内科',
                'sort' => '1',
                'status' => '0',
            ],
            [
                'name' => '外科',
                'sort' => '1',
                'status' => '0',
            ],
            [
                'name' => '针灸推拿',
                'sort' => '1',
                'status' => '0',
            ],
        ]);
    }
}
