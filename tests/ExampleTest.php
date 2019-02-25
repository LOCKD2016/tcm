<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Class ExampleTest
 */
class ExampleTest extends TestCase
{
    /**
     * A basic functional inquiry example.
     *
     * @return void
     */
    public function testBasicExample()
    {

    }


    /**
     * 用户关注医生
     */
    public function user_follow_doctor()
    {
        $user = \App\Models\AppUser::first();

        $follow = $user->follow_doctor(3);

        $this->assertTrue($follow);
    }

    /**
     * 医生关联患者
     */
    public function doctor_follow_user()
    {
        $doctor = \App\Models\Doctor::first();

        $follow = $doctor->relevance_user(3);

        $this->assertTrue($follow);
    }

    /**
     * @test
     */
    public function base()
    {
        dump(__CLASS__);
    }
}
