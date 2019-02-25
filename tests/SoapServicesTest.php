<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Soap接口测试
 * Class SoapServicesTest
 */
class SoapServicesTest extends TestCase
{
    /**
     * SoapServices
     * @var \App\Services\SoapServices $soapServices
     */
    public $soapServices;

    /**
     * 在准备完毕后 手下
     */
    protected function setUp()
    {
        parent::setUp();

        $this->soapServices = new \App\Services\SoapServices();

        $this->soapServices->test = false; //测试接口是否可以 先关闭
    }

    /**
     * 添加用户数据
     */
    public function addUserData()
    {
        $addData = [
            'CUSTOMER_CODE' => '',
            'CUSTOMER_NAME' => "王子",//患者姓名
            'GENDER' => "10007",//性别 {男："10007", 女："10008"} database:性别 0未知 1男 2女
            'CONTACT' => "15210236535", //手机号
            'STR_BIRTHDAYDATE' => "19960705", //出生日期（yyyyMMdd）
            'Certification_CODE' => '2800',//证件类型{身份证: "2800",护照: "0000143365",其他类型: "0000143366"}
            'Certification_NUM' => '371202199607054558', //证件号码
            'Address_LINE' => '',//家庭住址
            'MED_RECORD_CODE' => '',// 病历号
            'OPREATOR' => '移动端创建用户', //操作人员姓名
            'CREATE_COMPANY_CODE' => 'GS_O1',//创建公司编号(默认编号GS_O1)
        ];

        $res = $this->soapServices->addUser($addData);

        dump($res);
    }

    /**
     * 修改用户数据
     */
    public function editUserData()
    {
        $editData = [
            'CUSTOMER_CODE' => '0e838c7a-7fbe-4cbe-ac8e-c4148105a7c3',
            'CUSTOMER_NAME' => "小王子",//患者姓名
            'GENDER' => "10008",//性别 {男："10007", 女："10008"} database:性别 0未知 1男 2女
            'CONTACT' => "15210236535", //手机号
            'STR_BIRTHDAYDATE' => "19960705", //出生日期（yyyyMMdd）
            'Certification_CODE' => '2800',//证件类型{身份证: "2800",护照: "0000143365",其他类型: "0000143366"}
            'Certification_NUM' => '371202199607054558', //证件号码
            'Address_LINE' => '',//家庭住址
            'MED_RECORD_CODE' => '',// 病历号
            'OPREATOR' => '移动端修改用户', //操作人员姓名
            'CREATE_COMPANY_CODE' => 'GS_O1',//创建公司编号(默认编号GS_O1)
        ];

        $res = $this->soapServices->editUser($editData);

        dump($res);
    }

    /**
     */
    public function getScheduleDate()
    {
        $res = $this->soapServices->getScheduleDate('ea4779f9-14fb-48d8-a8dc-d502630c92fa',
            '王子华', '20171208000000', '20171208235900', 'GS_01'
        );

//        dump(md5('KINGforeverlove123'));
    }

    /**
     * 预约
     */
    public function saveBespeak()
    {
        $res = $this->soapServices->saveBespeak(
            '0e838c7a-7fbe-4cbe-ac8e-c4148105a7c3',
            '小王',
            'ea4779f9-14fb-48d8-a8dc-d502630c92fa',
            '王子华',
            '20171208151500',
            '20171208153000',
            'GS_01'
        );

        dump($res);
    }

    /**
     * 获取所有方法列表
     * @Auth: kingofzihua
     */
    public function getFuncs()
    {
        $funcs = $this->soapServices->getFuncs();

        dump($funcs);
    }

    /**
     * soap类型数组，详细说明所有结构和类型。
     * @Auth: kingofzihua
     */
    public function getTypes()
    {
        $types = $this->soapServices->getTypes();

        dump($types);
    }

    /**
     * @test
     */
    public function base()
    {
        dump(__CLASS__);
    }

}