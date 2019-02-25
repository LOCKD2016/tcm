<?php

use Illuminate\Database\Seeder;

class CliniqueTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('cliniques')->delete();
        \DB::table('cliniques')->insert([
            [
                'name' => '泰和国医',
                'content' => json_encode(
                    [
                        'address' => '北京市朝阳区新东路12号院 首开铂郡南区4号楼B1',
                        "longitude" => "116.458104",
	                    "latitude" => "39.943186"
                    ]
                ),
                'code' => 'GS_O1',
            ]
        ]);
    }
}
