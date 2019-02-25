<?php

use Illuminate\Database\Seeder;

class TestTableSeeder extends Seeder
{
    /**
     * 测试数据
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * @desc 删除 中间表
         */
        \DB::table('doctor_disease')->delete();
        \DB::table('doctor_section')->delete();
        \DB::table('doctor_clinique')->delete();
        \DB::table('user_clinique')->delete();
        \DB::table('doctor_users')->delete();
        \DB::table('group_user')->delete();
        \DB::table('group')->delete();

        \DB::table('cliniques')->delete();
        \DB::table('swiper')->delete();
        \DB::table('oauth_clients')->delete();
        \DB::table('user_health')->delete();
        \DB::table('order_goods')->delete();
        \DB::table('orders')->delete();
        \DB::table('inquiry')->delete();
        \DB::table('bespeaks')->delete();
        \DB::table('app_users')->delete();
        \DB::table('doctors')->delete();
        \DB::table('disease')->delete();
        \DB::table('sections')->delete();

        $this->call(UserTableSeeder::class); //管理员
        $this->call(OauthClientsTableSeeder::class); //OauthClients
        $this->call(ConfigTableSeeder::class); //基本设置
        $this->call(AreasTableSeeder::class); //地址
        $this->call(SectionTableSeeder::class); //科室
        $this->call(GoodsTableSeeder::class); //商品
        $this->call(ExamTableSeeder::class); //标准问诊单

        $swiper = factory(\App\Models\Swiper::class, 10)->create(); //轮播图
        $disease = factory(\App\Models\Disease::class, 30)->create(); //疾病

        $clinique = factory(\App\Models\Clinique::class, 5)->create(); //诊所
        $appUser = factory(\App\Models\AppUser::class, 20)->create(); //用户
        $doctor = factory(\App\Models\Doctor::class, 50)->create(); //医生
        $groups = factory(\App\Models\Group::class, 100)->create(); //医生的分组

        //医生关联数据
        $doctor->each(function ($doctor) use ($clinique) {
            //添加科室
            $doctor->sections()->saveMany(
                \App\Models\Section::orderBy(\DB::raw('RAND()'))->take(2)->get()
            );

            //添加疾病
            $doctor->diseases()->saveMany(
                \App\Models\Disease::orderBy(\DB::raw('RAND()'))->take(5)->get()
            );

            //添加诊所 随机读取0-5个
            $clinique->random(rand(1, 5))->each(function ($clinique) use ($doctor) {
                $doctor->cliniques()->save($clinique, ['code' => $clinique->code . str_random(5)]);

                //添加两周的排班 schedules
                for ($i = 0; $i < 15; $i++) {
                    $date = \Carbon\Carbon::now()->addDay($i);

                    $create_data[] = new \App\Models\Schedule([
                        'date' => $date,//排班日期
                        'start_time' => (string)date('Ymd', strtotime($date)) . '080000',//排班开始时间
                        'end_time' => (string)date('Ymd', strtotime($date)) . '203000',//排班结束时间
                        'code' => str_random(15),//排班日期
                        'clinique_id' => $clinique->id, //诊所
                    ]);
                }

                $doctor->schedules()->saveMany($create_data);
            });
        });

        //用户关联数据
        $appUser->random(18)->each(function ($user) use ($clinique) { //有的用户没有同步到他们的诊所
            $clinique->each(function ($clinique) use ($user) { //用户注册全部诊所
                $user->cliniques()->save($clinique, ['code' => $clinique->code . str_random(5)]);
            });
        });

        //分组内添加成员
        $groups->each(function ($group) use ($appUser) {
            $userIds = $appUser->random(5)->pluck('id')->toArray(); //获取5个id

            $group->users()->attach($userIds); //分组内 随机添加5个成员
            $group->increment('num', 5); //增加分组内的成员数量
        });
    }
}
