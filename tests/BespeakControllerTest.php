<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * 预约测试
 * Class BespeakControllerTest
 * @Auth: kingofzihua
 */
class BespeakControllerTest extends TestCase
{
    /**
     * 网诊预约
     * @Auth:kingofzihua
     */
    public function web()
    {

        $response = $this->post_data('bespeak/web');

        $this->dump($response->code);
    }

    /**
     * @test
     */
    public function base()
    {
        dump(__CLASS__);
    }
}
