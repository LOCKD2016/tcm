<?php
namespace App\Util;

class Exp
{
    private $key;
    private $queryUrl = 'http://v.juhe.cn/exp/index';
    private $comUrl = 'http://v.juhe.cn/exp/com';
    public $param;

    /*
     * @param com 快递公司编码
     * @param number 快递编号
     */
    public function __construct($com, $number)
    {
        $this->key = config('api.EXP_KEY');
        $this->param = array(
            'key' => $this->key,
            'com' => $com,
            'no' => $number
        );
    }

    /**
     * 返回支持的快递公司公司列表
     * @return array
     */
    public function getComs()
    {
        $params = 'key=' . $this->key;
        $content = $this->juhecurl($this->comUrl, $params);
        return $this->_returnArray($content);
    }

    public function query()
    {
        $content = $this->juhecurl($this->queryUrl, $this->param, 1);
        return $this->_returnArray($content);
    }


    /**
     * 将JSON内容转为数据，并返回
     * @param string $content [内容]
     * @return array
     */
    public function _returnArray($content)
    {
        return json_decode($content, true);
    }

    /**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */
    public function juhecurl($url, $params = false, $ispost = 0)
    {
        $httpInfo = array();
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'JuheData');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($ispost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($params) {
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }
        $response = curl_exec($ch);
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }
}