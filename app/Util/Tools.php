<?php
/**
 * Created by PhpStorm.
 * User: vming
 * Date: 16/10/29
 * Time: 上午11:32
 */

namespace App\Util;

use DB;
use QrCode;
use GuzzleHttp\Client;
use Qiniu\Auth as QiniuAuth;
use Qiniu\Storage\UploadManager;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Request;


class Tools
{

    public static  function zp_log($name, $msg, $isEnd = false)
    {
        $str = $isEnd ? "\n\r" : "\n";
        file_put_contents(storage_path() . '/logs/' . $name . '.log', json_encode($msg, JSON_UNESCAPED_UNICODE) . $str, FILE_APPEND);
    }

    /**
     * 不解释
     * @param $weeks
     * @return array
     */
    public static function weeksHandle($weeks){
        $arr = ['1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0,'6'=>0,'0'=>0];
        foreach ($arr as $key=>$value){
            if(in_array($key,$weeks)){
                $arr[$key] = 1;
            }
        }
        return $arr;
    }

    /**
     * 不解释
     * @param $collect
     * @param int $avg
     * @return array
     */
    public static function handleData($collect, $avg = 0)
    {
        $data = collect($collect);
        $one = $data->where('condition', 1)->count();  //痊愈
        $two = $data->where('condition', 2)->count(); //明显好转
        $three = $data->where('condition', 3)->count(); //好转
        $four = $data->where('condition', 4)->count(); //没变化
        if ($avg) {
            $sum = $data->count();
            return collect([$one, $two, $three, $four])->map(function ($value) use ($sum) {
                return ceil(($value / $sum) * 100) . '%';
            })->toArray();
        }
        return [$one, $two, $three, $four];
    }
    /**
     * @param $content 二维码内容
     * @param string $path 文件存放路径 文件路径默认为public下
     * @param int $size 生成二维码尺寸 px
     * @param int $name 生成二维码名称
     * @return string
     */
    public static function qrcode($content , $name = false , $path='img/qrcode/',$size=300){

        if(! file_exists(public_path($path))) mkdir(public_path($path));

        if(!$name){
            $name = time().mt_rand(1000000,getrandmax());
        }
        if(file_exists(public_path($path.$name.'.png')))
            return ['msg'=>'此目录下文件已存在','status'=>0];

        QrCode::format('png')->size($size)->encoding('UTF-8')->generate($content, public_path($path.$name.'.png'));

        return  ['msg'=>config('app.url').'/'.$path.$name.'.png','status'=>1];
    }

    public static function randCode($num=6){
        $code='';
        for ($i=0; $i < $num; $i++) {
            $number = '';
            $rand = 1;
            switch ($rand) {
                case '1':
                    $number = rand(48,57);  //生成48-57之间的一个任意数，其对应的字符为0-9
                    break;
                case '2':
                    $number = rand(65,90);  //生成65-90之间的一个任意数，其对应的字符为A-Z
                    break;
                case '3':
                    $number = rand(97,122); //生成97-122之间的一个任意数，其对应的字符为a-z
                    break;
                default:
                    break;
            }
            $code.= strtolower(chr($number));  //将随机生成的数值（ascii码）转成成对应的字符，并存入到code中
        }
        return $code;
    }
    public static function sendSms($mobile,$msg){
        $http = new Client();
        $data['mobile']=$mobile;
        $data['create_time']= time();
        $msg = mb_convert_encoding($msg, 'gb2312', 'utf-8');   //改变字符串编码    $msg 要转换编码的字符串  gb2312 目标编码   utf-8 源码编码
        $msg = urlencode($msg); //将字符串以URL编码
        $url = 'http://si.800617.com:4400/SendLenSms.aspx?un=bjwmhd-1&pwd=011189&mobile=' . $mobile . '&msg=' . $msg;
        $res = $http->get($url);
        $xml = simplexml_load_string(str_replace('gb2312','utf-8',mb_convert_encoding($res->getBody(),'utf-8','gb2312')));
        if ($xml->Result== 1) {
            return 'ok';
        } else {
            return $xml->Result;
        }
    }

    public static function tcmSendSms($mobile,$msg){
        $SN = 'SDK-BBX-010-27442';
        $pwd = 'a@e57b@424f';
        $pwd = strtoupper(md5($SN.$pwd));
        $url = "http://sdk.entinfo.cn:8061/mdsmssend.ashx?sn={$SN}&pwd={$pwd}&mobile={$mobile}&content={$msg}&ext=&stime=&rrid=&msgfmt=";
        $res = file_get_contents($url);
        return $res;
    }

    public static function removeA($content){
        return preg_replace('/<a[^>]*>(.*)<\/a>/isU','',$content);
    }

    public static function isMobile($content){
        return preg_match('/^1[34578][0-9]{9}$/',$content);
    }



    //生成随机名
    public static  function genUserNumber()
    {
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $username = "";
        for ( $i = 0; $i < 6; $i++ )
        {
            $username .= $chars[mt_rand(0, strlen($chars))];
        }
        return strtoupper(base_convert(time() - 1420070400, 10, 36)).$username;
    }

    public static function getOrderSn($sn=null){
        /* 选择一个随机的方案 */
        mt_srand((double) microtime() * 1000000);
        if($sn)
            return substr($sn,0,14). str_pad(mt_rand(1, 99999), 6, '0', STR_PAD_LEFT);
        return date('YmdHis') . str_pad(mt_rand(1, 99999), 6, '0', STR_PAD_LEFT);
    }

    public static function isWeChatBrowser()
    {
        return strpos(Request::header('user_agent'), 'MicroMessenger') !== false;
    }

    public static function isTCMUserApp()
    {
        return strpos(Request::header('user_agent'), 'TCMUser') !== false;
    }

    public static function getChatToken(){
        return date('YmdHis') . str_pad(mt_rand(1, 99999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * 获取url中的各个参数
     * 类似于 pay_code=alipay&bank_code=ICBC-DEBIT
     * @param //type $str
     * @return //type
     */
    public static  function parse_url_param($str)
    {
        $data = array();
        $parameter = explode('&', end(explode('?', $str)));
        foreach ($parameter as $val) {
            $tmp = explode('=', $val);
            $data[$tmp[0]] = $tmp[1];
        }
        return $data;
    }

    /**
     * 根据类型获取日期时间
     * @desc
     * @author Eric Chow
     * @DateTime 2018/1/11 15:31
     * @param int $type 0今天1今天以前的一周2今天以前的一个月
     * @return bool|false|string
     */
    public static function getDatesByType($type=0, $onlyDay=0){

        if($onlyDay>0){
            // 今天
            if($type==0) return date('d');
            //今天往前一周
            if($type==1){
                return self::getDatesByDays(7,$onlyDay=1);
            }elseif ($type==2){
                return self::getDatesByDays(30,$onlyDay=1); //今天往前30天时间
            }
        }else{
            // 今天
            if($type==0) return date('Y-m-d');
            //今天往前一周
            if($type==1){
                return self::getDatesByDays(7,$onlyDay=0);
            }elseif ($type==2){
                return self::getDatesByDays(30,$onlyDay=0); //今天往前30天时间
            }
        }

        return false;
    }

    /**
     * 根据天数获取日期数据
     * @desc
     * @author Eric Chow
     * @DateTime 2018/1/11 15:40
     * @param int $days
     */
    public static function getDatesByDays($days=7,$onlyDay=0){

        $arr = [];
        if($onlyDay>0){
            for($i=0; $i<$days;$i++){
                array_push($arr,date("d",strtotime("-".$i."day")));
            }
        }else{
            for($i=0; $i<$days;$i++){
                array_push($arr,date("Y-m-d",strtotime("-".$i."day")));
            }
        }

        return $arr;
    }

    /**
     * 获取指定日期段内每一天的日期
     * @param  //Date  $startdate 开始日期
     * @param  //Date  $enddate   结束日期
     * @return //Array
     */
    public static function getDateFromRange($startdate, $enddate){

        $stimestamp = strtotime($startdate);
        $etimestamp = strtotime($enddate);
        $days = ($etimestamp-$stimestamp)/86400+1;     // 计算日期段内有多少天
        // 保存每天日期
        $date = array();
        for($i=0; $i<$days; $i++){
            $date[] = date('Y-m-d', $stimestamp+(86400*$i));
        }
        return $date;
    }

    /**
     * 获取周几
     * @Author zhoupeng
     * @Date&Time 2017-09-08 11:47
     * @param $datetime 日期
     */
    public static function getWeekday($datetime)
    {
        $weekarray = array("Sun", "Mon", "Tues", "Wed", "Thur", "Fri", "Sat");
        return $weekarray[date("w", strtotime($datetime))];
    }

    //生成缩略图
    public static function thumbnail($imgPath,$imgName,$maxwidth=100){
        ini_set('memory_limit','256M');
        $img = Image::make($imgPath.$imgName);
        $width = $img->getWidth();
        $hieht = $img->getHeight();
        $img->resize($maxwidth,($hieht*$maxwidth) / $width)->save(public_path().'/img/'.'small_'.$imgName);
        $img_url = self::move_img_from_wx_to_qiniu('small_'.$imgName, public_path('img/small_'.$imgName));
        if($img_url)
            return $img_url;
        return '';
    }

    /**
     * 将图片从微信上传到七牛
     * @desc
     * @author Eric Chow
     * @DateTime 2018/2/27 14:35
     * @param $img
     */
    public static function move_img_from_wx_to_qiniu($key,$filePath)
    {
        $qiniu = config('filesystems.disks.qiniu');
        $auth = new QiniuAuth($qiniu['access_key'], $qiniu['secret_key']);
        $token = $auth->uploadToken($qiniu['bucket']);
        $uploadMgr = new UploadManager();
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        if(isset($ret['key'])){
            unlink($filePath); //删除本地照片
            return 'http://'.$qiniu['domains']['default'] . '/' . $ret['key'];
        }else{
            DB::table('error_note')->insertGetId(['content'=>json_encode($err).'FAIL'.json_encode($ret).date('Y-m-d H:i:s')]);
            return false;
        }
    }

    //获取文件大小
    public static function getsize($size, $format) {
        $p = 0;
        if ($format == 'kb') {
            $p = 1;
        } elseif ($format == 'mb') {
            $p = 2;
        } elseif ($format == 'gb') {
            $p = 3;
        }
        $size /= pow(1024, $p);
        return number_format($size, 3);
    }

    //是否是图片
    public static function isImage($filename)
    {
        $types = '.gif|.jpeg|.png|.bmp'; //定义检查的图片类型
        if(file_exists($filename)) {
            $info = getimagesize($filename);
            $ext = image_type_to_extension($info['2']);
            return stripos($types,$ext);
        }else{
            return false;
        }
    }

    public static function get_weekday($time)
    {
        $weekarray = array("日", "一", "二", "三", "四", "五", "六");

        $week = "周" . $weekarray[date('w', strtotime($time))];

        return date('Y-m-d ' . $week . ' H:i', strtotime($time));
    }

    public static function is_json($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

}
