<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(OauthClientsTableSeeder::class);
        $this->call(CliniqueTableSeeder::class);//医馆
        $this->call(ConfigTableSeeder::class); //基本设置
        $this->call(AreasTableSeeder::class); //地址
        $this->call(SectionTableSeeder::class); //科室
        $this->call(GoodsTableSeeder::class); //商品
        $this->call(ExamTableSeeder::class); //标准问诊单
        $this->call(PermissionsTableSeeder::class); //权限
    }

}
