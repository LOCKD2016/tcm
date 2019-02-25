<?php

use Illuminate\Database\Seeder;

class ConfigTableSeeder extends Seeder
{
    /**
     * 基本配置表
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('configs')->delete();

        \DB::table('configs')->insert([
            [
                'name' => '医生头衔',
                'key' => 'doctor_title',
                'value' => json_encode(
                    [
                        [
                            'id' => '1',
                            'name' => '主任医师',
                        ],
                        [
                            'id' => '2',
                            'name' => '副主任医师',
                        ],
                        [
                            'id' => '3',
                            'name' => '主治医师',
                        ],
                        [
                            'id' => '4',
                            'name' => '知名专家',
                        ],
                        [
                            'id' => '5',
                            'name' => '特聘专家',
                        ],
                        [
                            'id' => '6',
                            'name' => '名老中医',
                        ],
                    ]
                ),
                'desc' => '医生的头衔',
                'status' => '1',
            ],[
                'name' => 'app端用户协议',
                'key' => 'app_agreement',
                'value' => '',
                'desc' => 'app用户协议',
                'status' => '1',
            ],[
                'name' => '微信端用户协议',
                'key' => 'wechat_agreement',
                'value' => '',
                'desc' => '微信端用户协议',
                'status' => '1',
            ]
        ]);
    }
}
