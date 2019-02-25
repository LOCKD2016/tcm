<?php

use Illuminate\Database\Seeder;

class GoodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('goods')->delete();

        //1:普通商品 11:门诊预约 12:网诊预约 13:抓药 14:煎药 15:调剂费 16:自备 17:快递
        \DB::table('goods')->insert([
            [
                'name' => '门诊预约',
                'image' => '',
                'share_image' => '',
                'amount' => '5000',
                'desc' => '门诊预约',
                'real' => '0',
                'type' => '11',
                'status' => '1',
            ],
            [
                'name' => '网诊预约',
                'image' => '',
                'share_image' => '',
                'amount' => '20000',
                'desc' => '网诊预约',
                'real' => '0',
                'type' => '12',
                'status' => '1',
            ],
            [
                'name' => '抓药',
                'image' => '',
                'share_image' => '',
                'amount' => '5000',
                'desc' => '抓药',
                'real' => '0',
                'type' => '13',
                'status' => '1',
            ],
            [
                'name' => '煎药',
                'image' => '',
                'share_image' => '',
                'amount' => '2000',
                'desc' => '煎药',
                'real' => '0',
                'type' => '14',
                'status' => '1',
            ],
            [
                'name' => '调剂费',
                'image' => '',
                'share_image' => '',
                'amount' => '0',
                'desc' => '网诊预约',
                'real' => '0',
                'type' => '15',
                'status' => '1',
            ],
            [
                'name' => '自备',
                'image' => '',
                'share_image' => '',
                'amount' => '0',
                'desc' => '自备',
                'real' => '0',
                'type' => '16',
                'status' => '1',
            ],
            [
                'name' => '快递费',
                'image' => '',
                'share_image' => '',
                'amount' => '0',
                'desc' => '快递费',
                'real' => '0',
                'type' => '17',
                'status' => '1',
            ],
        ]);
    }
}
