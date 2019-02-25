<?php

use Illuminate\Database\Seeder;

class DiseaseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('disease')->delete();
        \DB::table('disease')->insert([
            [
                'name' => '感冒',
                'section_id' => 1,
                'sort' => 1,
            ],
            [
                'name' => '慢性支气管炎',
                'section_id' => 1,
                'sort' => 2,
            ],
            [
                'name' => '哮喘',
                'section_id' => 2,
                'sort' => 3,
            ],
            [
                'name' => '肺气肿',
                'section_id' => 2,
                'sort' => 4,
            ],
            [
                'name' => '支气管扩张',
                'section_id' => 3,
                'sort' => 5,
            ],
            [
                'name' => '肺炎',
                'section_id' => 10,
                'sort' => 6,
            ],
            [
                'name' => '百日咳',
                'section_id' => 3,
                'sort' => 7,
            ],
            [
                'name' => '支原体肺炎',
                'section_id' => 4,
                'sort' => 8,
            ],
            [
                'name' => '气胸',
                'section_id' => 5,
                'sort' => 9,
            ],
            [
                'name' => '疱疹性咽峡炎',
                'section_id' => 5,
                'sort' => 10,
            ],
            [
                'name' => '成人呼吸窘迫综合征',
                'section_id' => 5,
                'sort' => 11,
            ],
            [
                'name' => '鼾症',
                'section_id' => 6,
                'sort' => 12,
            ],
            [
                'name' => '胃病',
                'section_id' => 7,
                'sort' => 13,
            ],
            [
                'name' => '慢性胃炎',
                'section_id' => 9,
                'sort' => 14,
            ],
            [
                'name' => '胃癌',
                'section_id' => 8,
                'sort' => 15,
            ],
            [
                'name' => '便秘',
                'section_id' => 10,
                'sort' => 16,
            ],
        ]);
    }
}
