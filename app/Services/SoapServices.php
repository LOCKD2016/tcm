<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Repository\ApiLogRepository;
use App\Soap\Request\SendMessage;

/**
 * Class SoapServices
 * @Auth: kingofzihua
 * @package App\Services
 */
class SoapServices
{
    /**
     * @var \SoapClient
     */
    protected $soapClient;

    /**
     * 表示本地测试环境 不需要直接发送请求
     * @Auth: kingofzihua
     * @var boolen
     */
    public $test = false;


    /**
     * 地址
     * @Auth: kingofzihua
     * @var string
     */
//    protected $url = 'http://www.superedc.com:9090/HIS_APP/AppSystemService.svc/AppSystemService/mex?wsdl';
//    protected $url = 'http://47.95.112.72:9090/CRM_APP/AppSystemService.svc/AppSystemService/mex?wsdl'; //测试环境
    protected $url = 'http://47.95.112.72:9090/dgy_app/AppSystemService.svc/AppSystemService/mex?wsdl'; //线上环境


    /**
     * 参数
     * @Auth: kingofzihua
     * @var
     */
    protected $params;

    /**
     * header
     * @Auth: kingofzihua
     * @var
     */
    protected $header;

    /**
     * SoapController constructor.
     *
     * @param SoapWrapper $soapClient
     */
    public function __construct()
    {
        $this->soapClient = new \SoapClient($this->url, ['soap_version' => SOAP_1_1, 'classmap' => ['SendMessage' => SendMessage::class]]);

    }

    /**
     * 添加参数
     * @Auth: kingofzihua
     * @param $params
     * @return $this
     */
    public function withParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * 添加参数
     * @Auth: kingofzihua
     * @param $params
     * @return $this
     */
    public function withHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * 发送数据
     * @Auth: kingofzihua
     * @return mixed
     */
    public function send()
    {
        //组装数据
        $data = [
            'Header' => $this->header,
            'Params' => $this->params,
        ];

        //模拟 SendMessage
        $request = new SendMessage($data);
        //print_r($request);
        /*try {
            print_r($this->getFuncs());
            print_r($this->getTypes());
            print_r($request);

        } catch (\SoapFault $e) {
            print $e->getCode();
            print $e->getMessage();
            return false;
        }*/
        //$response = $this->soapClient->SendMessage($request);
        $response = $this->soapClient->__call('SendMessage', [$request]);
        //发送数据

        //返回数据
        $res = $response->SendMessageResult;

        //转化为数组
        $return = json_decode($res, true);

        //记录日志
        $this->recordLog($this->header['MsgMethod'], $data, $return);

        return $return;
    }

    /**
     * 返回数据
     * @param $res
     * @return array
     */
    public function returns($res)
    {
        if (isset($res['Header']['ResultState']) && $res['Header']['ResultState'] == 1) { //判断成功
            return [
                'state' => 1,
                'code' => '200',
                'message' => $res['Header']['ResultMsg'] ?: '操作成功',
                'data' => $res['Data'],
            ];
        } else { //接口返回失败
            return [
                'state' => 0,
                'code' => 502,
                'message' => $res['Header']['ResultMsg'] ?: '操作失败',
                'data' => $res['Data'],
            ];
        }

    }

    /**
     * 发送数据 返回数据的记录
     * @param $method
     * @param $send
     * @param $return
     * @return static
     */
    public function recordLog($method, $send, $return)
    {
        $resultState = isset($return['Header']['ResultState']) && $return['Header']['ResultState'] == 1;

        return ApiLogRepository::soapLog($method, $send, $return, $resultState);
    }

    /**
     * 添加就诊人
     * "Header": {
     *      "ClientNo":  "A001",
     *      "MsgMethod": "CustomerInfoAdd",
     * },
     * "Params": {
     *      " CUSTOMER_NAME": "李四",
     *      " GENDER": "10007"
     * }
     * @param $data
     * @return array [
     *      'state'=>'1', 1:成功 0:失败
     * ]
     */
    public function addUser($data)
    {
        if ($this->test) {
            return [
                'state' => 1,
                'code' => '200',
                'message' => '添加成功',
                'data' => [
                    'CUSTOMER_CODE' => '00101001',
                ],
            ];
        }

        $header = [
            "ClientNo" => "A001",
            "MsgMethod" => "CustomerInfoAdd",
        ];

        $res = $this->withHeader($header)->withParams($data)->send();

        return $this->returns($res);
    }

    /**
     * 修改就诊人
     * "Header": {
     *      "ClientNo":  "A001",
     *      "MsgMethod": "CustomerInfoEdit",
     * },
     * "Params": {
     *      " CUSTOMER_NAME": "李四",
     *      " GENDER": "10007"
     * }
     * @param $data
     * @return array [
     *      'state'=>'1', 1:成功 0:失败
     * ]
     */
    public function editUser($data)
    {
        if ($this->test) {
            return [
                'state' => 1,
                'code' => '200',
                'message' => '修改成功',
                'data' => [
                    'CUSTOMER_CODE' => '00101001',
                ],
            ];
        }

        $header = [
            "ClientNo" => "A001",
            "MsgMethod" => "CustomerInfoEdit",
        ];

        $res = $this->withHeader($header)->withParams($data)->send();

        return $this->returns($res);
    }

    /**
     * 删除就诊人
     * @desc 应该是没有用 不过后期可能有需求用的到，暂时先写上 没测试，如果用到再测试吧，反正用的地方也很局限
     * "Header": {
     *      "ClientNo":  "A001",
     *      "MsgMethod": "CustomerInfoDel",
     * },
     * "Params": {
     *      " CUSTOMER_NAME": "李四",
     *      " GENDER": "10007"
     * }
     * @param $data
     * @return array [
     *      'state'=>'1', 1:成功 0:失败
     * ]
     */
    public function deleteUser($data)
    {
        $header = [
            "ClientNo" => "A001",
            "MsgMethod" => "CustomerInfoDel",
        ];

        $res = $this->withHeader($header)->withParams($data)->send();

        return $this->returns($res);
    }

    /**
     * 获取医生的排班
     * @param $code
     * @param $name
     * @param $startDate
     * @param $endDate
     * @return array
     */
    public function getScheduleDate($code, $name, $startDate, $endDate, $clinic_code)
    {
        //获取数据

        if ($this->test) {
            $head = substr($startDate, 0, 8);

            $data = [
                [
                    "RESOURCE_CODE" => $code,
                    "RESOURCE_NAME" => $name,
                    "STR_ARRANGE_STARTTIME" => $head . '083000',
                    "STR_ARRANGE_ENDTIME" => $head . '113000',
                    "ResourceList" => [
                        [
                            //正常数据
                            "CUSTOMER_CODE" => "00101001",
                            "STR_APPOINT_STARTTIME" => $head . "084500",
                            "STR_APPOINT_ENDTIME" => $head . "085900",
                            "SourceState" => 0, //{值为2, 为App端数据，App方预约数据只能更改值为2的数据}
                        ],
                        [
                            //非正常数据
                            "CUSTOMER_CODE" => "00101001",
                            "STR_APPOINT_STARTTIME" => $head . "090100",
                            "STR_APPOINT_ENDTIME" => $head . "091500",
                            "SourceState" => 1,
                        ],
                        [
                            //非正常数据
                            "CUSTOMER_CODE" => "00101001",
                            "STR_APPOINT_STARTTIME" => $head . "091000",
                            "STR_APPOINT_ENDTIME" => $head . "102500",
                            "SourceState" => 2,
                        ],
                    ]

                ],
                [
                    "RESOURCE_CODE" => $code,
                    "RESOURCE_NAME" => $name,
                    "STR_ARRANGE_STARTTIME" => $head . '200000',
                    "STR_ARRANGE_ENDTIME" => $head . '223000',
                    "ResourceList" => [],
                ],
                [
                    "RESOURCE_CODE" => $code,
                    "RESOURCE_NAME" => $name,
                    "STR_ARRANGE_STARTTIME" => $head . '140000',
                    "STR_ARRANGE_ENDTIME" => $head . '183000',
                    "ResourceList" => [
                        [
                            //非正常数据
                            "CUSTOMER_CODE" => "00101001",
                            "STR_APPOINT_STARTTIME" => $head . "140000",
                            "STR_APPOINT_ENDTIME" => $head . "144500",
                            "SourceState" => 3,
                        ],
                        [
                            //非正常数据
                            "CUSTOMER_CODE" => "00101001",
                            "STR_APPOINT_STARTTIME" => $head . "145000",
                            "STR_APPOINT_ENDTIME" => $head . "145500",
                            "SourceState" => 4,
                        ],
                        [
                            //非正常数据
                            "CUSTOMER_CODE" => "00101001",
                            "STR_APPOINT_STARTTIME" => $head . "175000",
                            "STR_APPOINT_ENDTIME" => $head . "183000",
                            "SourceState" => 5,
                        ],
                    ]
                ],
            ];

            //排序
            return array_values(array_sort($data, function ($value) {
                return $value['STR_ARRANGE_STARTTIME'];
            }));
        }

        $header = [
            "ClientNo" => "A001",
            "MsgMethod" => "ResourceArrangeInfo",
        ];

        $data = [
            "RESOURCE_CODE" => $code,
            "COMPANY_CODE" => $clinic_code,
            "STR_ARRANGE_STARTTIME_START" => $startDate,
            "STR_ARRANGE_STARTTIME_END" => $endDate,
        ];

        $res = $this->withHeader($header)->withParams($data)->send();

        if (isset($res['Header']['ResultState']) && $res['Header']['ResultState'] == 1) { //判断成功
            $data = $res['Data'];

            //排序
            return array_values(array_sort($data, function ($value) {
                return $value['STR_ARRANGE_STARTTIME'];
            }));

        } else {
            return [];
        }

    }

    /**
     * 进行预约
     * @param $userCode 用户的code
     * @param $userName 用户的姓名
     * @param $doctorCode 医生的编码
     * @param $doctorName 医生的姓名
     * @param $startDate 开始时间
     * @param $endDate 结束时间
     * @param $cliniqueCode 诊所编码
     * @return string
     */
    public function saveBespeak($userCode, $userName, $doctorCode, $doctorName, $startDate, $endDate, $cliniqueCode)
    {
        if ($this->test) {
            return rand(1, 9) % 2 ?
                ['status' => 0, 'code' => 502, 'msg' => '大国医提示您:随机预约失败', 'data' => ''] :
                ['status' => 1, 'code' => 200, 'msg' => '大国医提示您:预约成功', 'data' => str_random(8)];
        }

        $header = [
            "ClientNo" => "A001",
            "MsgMethod" => "BespeakInfoAdd",
        ];

        $data = [
            "CUSTOMER_CODE" => $userCode,
            "CUSTOMER_NAME" => $userName,
            "RESOURCE_CODE" => $doctorCode,
            "RESOURCE_NAME" => $doctorName,
            "STR_APPOINT_STARTTIME" => $startDate,
            "STR_APPOINT_ENDTIME" => $endDate,
            "OPREATOR" => '移动端预约操作',
            "COMPANY_CODE" => $cliniqueCode,
        ];

        $res = $this->withHeader($header)->withParams($data)->send();

        return $this->returns($res);
    }

    /**
     * 取消预约
     * @param $userCode //用户的code
     * @param $userName //用户的姓名
     * @param $doctorCode //医生的编码
     * @param $doctorName //医生的姓名
     * @param $startDate //开始时间
     * @param $endDate //结束时间
     * @param $cliniqueCode //诊所编码
     * @return string
     */
    public function cancelBespeak($bespeakCode, $userCode, $userName, $doctorCode, $doctorName, $startDate, $endDate, $cliniqueCode)
    {

        $header = [
            "ClientNo" => "A001",
            "MsgMethod" => "BespeakInfoDel",
        ];

        $data = [
            "Bespeak_CODE" => $bespeakCode,
            "CUSTOMER_CODE" => $userCode,
            "CUSTOMER_NAME" => $userName,
            "RESOURCE_CODE" => $doctorCode,
            "RESOURCE_NAME" => $doctorName,
            "STR_APPOINT_STARTTIME" => $startDate,
            "STR_APPOINT_ENDTIME" => $endDate,
            "OPREATOR" => '移动端取消预约操作',
            "COMPANY_CODE" => $cliniqueCode,
        ];

        $res = $this->withHeader($header)->withParams($data)->send();

        return $this->returns($res);
    }

    // 发送健康数据
    public function postHealthData($customer_code, $userName, $data)
    {

        $header = [
            "ClientNo" => "A001",
            "MsgMethod" => "VitalSignsInfoAdd",
        ];

        $data = [
            "CUSTOMER_CODE" => $customer_code,
            "CUSTOMER_NAME" => $userName,
            "HEIGHT" => isset($data['height']) ? $data['height'] : '',
            "WEIGHT" => isset($data['weight']) ? $data['weight'] : '',
            "BMI" => isset($data['BMI']) ? $data['BMI'] : '',
            "SYSTOLIC" => isset($data['systolic']) ? $data['systolic'] : '',
            "DIASTOLIC" => isset($data['diastolic']) ? $data['diastolic'] : '',
            "CREATE_COMPANY_CODE" => 'GS_01',
        ];

        $res = $this->withHeader($header)->withParams($data)->send();

        return $this->returns($res);
    }

    public function dictionaryInfo(){
        $header = [
            "ClientNo" => "A001",
            "MsgMethod" => "DictionaryInfo",
        ];

        $data = [
            "D_TYPE_CODE" => 'DTY101001',
        ];
        $res = $this->withHeader($header)->withParams($data);

        $data = [
            'Header' => $this->header,
            'Params' => $this->params,
        ];

        //dump($this->getTypes());
        //dump($this->getFuncs());
        //模拟 SendMessage
        $request = new SendMessage($data);
        $response = $this->soapClient->SendMessage($request);
        //var_dump($res);
        return $this->returns($res);
    }

    /**
     * 获取所有方法列表
     * @Auth: kingofzihua
     */
    public function getFuncs()
    {
        return $this->soapClient->__getFunctions();
    }

    /**
     * soap类型数组，详细说明所有结构和类型。
     * @Auth: kingofzihua
     */
    public function getTypes()
    {
        return $this->soapClient->__getTypes();
    }

    public function aaa()
    {
        $header = [
            "ClientNo" => "A001",
            "MsgMethod" => "DictionaryInfo",
        ];

        $data = [
            "D_TYPE_CODE" => 'DTY101001',
        ];

        $res = $this->withHeader($header)->withParams($data)->send();

        return $this->returns($res);
    }


    /**
     *  获取药方
     * @desc
     * @author Eric
     * @DateTime 2018/3/15 16:19
     * @return array
     */
    public function getRecipe($params)
    {
        $header = [
            "ClientNo" => "A001",
            "MsgMethod" => "RcpDetailInfo",
        ];

        $data = [
            "CUSTOMER_CODE" => $params['customer_code'],
        ];

        $res = $this->withHeader($header)->withParams($data)->send();

        return $this->returns($res);
    }

    /**
     *  获取病历(问诊单)
     * @desc
     * @author Eric
     * @DateTime 2018/3/15 16:19
     * @return array
     */
    public function getExam($params)
    {
        $header = [
            "ClientNo" => "A001",
            "MsgMethod" => "CustomerNoteInfo",
        ];

        $data = [
            "CUSTOMER_CODE" => $params['customer_code']
        ];

        $res = $this->withHeader($header)->withParams($data)->send();

        return $this->returns($res);
    }
}