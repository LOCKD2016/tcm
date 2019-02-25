<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TryController extends Controller
{
    public function index()
    {
    	$url = 'http://47.95.112.72:9090/dgy_app/AppSystemService.svc/AppSystemService/mex?wsdl';

    	$soapClient = new \soapClient($url, ['soap_version' => SOAP_1_1, 'classmap' => ['SendMessage' => SendMessage::class]]);
    	return "隐藏信息";
    	echo "<pre>";
    	var_dump($soapClient->__getFunctions());echo "<hr>";
    	print_r($soapClient->__getTypes());
    }

    public function delDoctors()
    {
        $name = ['凌云', '高珊', '马文珠', '秦丽娜', '赵百孝', '赵瑞珍', '刘娟', '石效平', '王波', '王素梅', '姜坤', '姜羡华', '刘昭阳', '王天芳', '许昕', '赵红', '范士生', '方建国', '王庆甫', '王征美', '周骥', '郝万山', '贺琳', '黄力', '黄柳华', '贾文华', '林谦', '刘红旭', '刘宗莲', '娄锡恩', '吕湘宾', '马丽红', '史华新', '史载祥', '唐启盛', '王阶', '王小沙', '王亚红', '吴丹明', '徐浩', '杨晋翔', '尤欣', '朱跃兰', '祝捷', '宋立', '金明', '贾立群', '陈长怀', '李仝', '林洪生', '王笑民', '王泽民'];
//        select id,name,web,clinic,is_del from doctors where name in
//        $ids = DB::table('doctors')->whereNotIn('name', $name)->pluck('id');
        $ids = Doctor::whereNotIn('name', $name)->pluck('id');

        $re = Doctor::whereIn('id', $ids)->update(['is_del'=>1]);

        return $re;
    }

    public function jiami()
    {
//        $data = Doctor::find(121)->toArray();
//        $data['salt'] = Str::random(6);
//        $data['password'] = '111111';
//        $data['password'] = bcrypt($data['password'] . $data['salt']);
//        $doctor = Doctor::find(121);
//        $re = $doctor->update($data);
//        if ($re){
//            return '成功';
//        }else {
//            return '失败';
//        }
    }
}
