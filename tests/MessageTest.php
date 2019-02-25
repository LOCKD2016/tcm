<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * 聊天测试
 * Class BespeakControllerTest
 * @Auth: kingofzihua
 */
class MessageTest extends TestCase
{
    /**
     * 准备聊天的测试数据
     * @step
     * 1、用户预约医生
     * 2、医生接诊
     * 3、用户点击支付按钮 生成订单
     * 4、订单支付
     * @test
     */
    public function prepare_test_data()
    {
        //网诊预约
        $response = $this->post_data('bespeak/web');

        $content = $this->response->getContent();

        $array = json_decode($content, true);

        if ($array['status']) { //预约成功
            //医生接诊
            $this->bespeak_test_accept($array['data']['bespeak_id']);
        } else {
            $this->dump($response);
        }
    }

    /**
     * @param $bespeak_id 预约编号
     * 医生接诊
     */
    public function bespeak_test_accept($bespeak_id)
    {
        $response = $this->get_url('bespeak/testAccept/' . $bespeak_id);

        $content = $this->response->getContent();

        $array = json_decode($content, true);

        if ($array['status']) {//接诊成功
            //请求生成订单的接口
            dump('生成订单');

            $this->create_order_by_bespeak($bespeak_id);

        } else {
            $this->dump($response);
        }
    }

    /**
     * @param $bespeak_id 预约编号
     * 通过预约创建订单
     */
    public function create_order_by_bespeak($bespeak_id)
    {
        $response = $this->get_url('order/bespeak/' . $bespeak_id);

        $content = $this->response->getContent();

        $array = json_decode($content, true);

        if ($array['status']) {//订单生成成功

            if ($array['data']['toPay']) { //要去支付
                $this->order_pay($array['data']['order_id']);
            } else { //不需要支付 应该是0元的 所以说
                $this->dump("测试数据已经生成了呢！");
            }
        } else {
            $this->dump($response);
        }
    }

    /**
     * 订单支付
     * @param int $order_id 订单编号
     */
    public function order_pay($order_id)
    {
        $response = $this->get_url('testPay/' . $order_id);

        $content = $this->response->getContent();

        $array = json_decode($content, true);

        if ($array['status']) {//订单生成成功
            $this->dump("测试数据已经生成了呢！");
        } else {
            $this->dump($response);
        }
    }

    /**
     * @test
     */
    public function base()
    {
        dump(__CLASS__);
    }
}
