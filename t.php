<?php
/**
 * Created by PhpStorm.
 * User: lbbniu
 * Date: 2018/1/31
 * Time: 16:33
 */
class SendMessage
{
    public $Josn = null;
    public function __construct($Josn)
    {
        $this->Josn = '{"Header":{"ClientNo":"A001","MsgMethod":"ResourceArrangeInfo"},"Params":{"RESOURCE_CODE":"c47e174f-8e75-4bd0-bde1-3402733ce257","COMPANY_CODE":"GS_01","STR_ARRANGE_STARTTIME_START":"20180202000000","STR_ARRANGE_STARTTIME_END":"20180203000000"}}';//json_encode($Josn);
    }
}
$url = 'http://47.95.112.72:9090/CRM_APP/AppSystemService.svc/AppSystemService/mex?wsdl';
//$soapClient = new SoapClient($url,['soap_version'=>SOAP_1_1,'classmap' => ['SendMessage' => 'SendMessage']]);
$soapClient = new SoapClient($url,[
    //'soap_version'=>SOAP_1_1,
    'proxy_host'     => "10.0.0.10",
    'proxy_port'     => 2214,
    //'classmap' => ['SendMessage' => 'SendMessage']
]);

$header = [
    "ClientNo" => "A001",
    "MsgMethod" => "ResourceArrangeInfo",
];
$params = [
    "D_TYPE_CODE" => 'DTY101001',
];
$data = [
    'Header' => $header,
    'Params' => $params,
];

var_dump($soapClient->__getFunctions());
var_dump($soapClient->__getTypes());
//模拟 SendMessage
$request = new SendMessage($data);
///var_dump($soapClient->__getLastRequest());


try{
    $std = new stdClass();
    $std->Josn = json_encode($data);
    //$parameters = new SoapVar($request, SOAP_ENC_OBJECT,'SendMessage','http://47.95.112.72:9090/CRM_APP/AppSystemService.svc/AppSystemService/mex?xsd=xsd0');
    //$response = $soapClient->SendMessage(new SoapParam($parameters, "parameters"));
    $response = $soapClient->SendMessage($std);
    var_dump($response);
}catch (SoapFault $e){
    var_dump($e);
    //var_dump($soapClient->__getLastRequest());
}finally{
    //var_dump($response);
}

