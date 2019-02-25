<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('user')->delete();
        
        \DB::table('user')->insert(array (
            0 => 
            array (
                'user_id' => 1,
                'user_name' => 'admin',
                'user_password' => '$2y$10$2ZmG.dZ/L7ImJV4tLJekN.leiuaHSjU5.WrmI/c/ERXiYvWR4khaO',
                'user_salt' => 'oKO64a',
                'user_realname' => '系统管理员',
                'user_phone' => NULL,
                'user_address' => NULL,
                'user_email' => NULL,
                'user_last_login_time' => '2016-09-21 09:38:50',
                'user_status' => 1,
                'user_create_time' => NULL,
                'fulljob' => 2,
                'sort_num' => 0,
                'invest_field' => NULL,
                'other_field' => NULL,
                'mon_sort' => 0,
            )        
        ));
        
        
    }
}
