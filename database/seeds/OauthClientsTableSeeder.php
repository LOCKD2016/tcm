<?php

use Illuminate\Database\Seeder;

class OauthClientsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('oauth_clients')->delete();
        
        \DB::table('oauth_clients')->insert(array (
            array (
                'id' => 2,
                'user_id' => NULL,
                'name' => 'Password Grant Client',
                'secret' => '9y0xi0o8QylmGb4st8d4evX15DjBEqZg0hhJ5vpj',
                'redirect' => 'http://localhost',
                'personal_access_client' => 0,
                'password_client' => 1,
                'revoked' => 0,
                'created_at' => '2016-10-29 00:14:46',
                'updated_at' => '2016-10-29 00:14:46',
            ),
        ));
        
        
    }
}
