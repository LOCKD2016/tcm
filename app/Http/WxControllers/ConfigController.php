<?php

namespace App\Http\WxControllers;

use App\Http\Controllers\Api\GoodsController;
use App\Models\Goods;
use App\Repository\ConfigRepository;
use App\Transformers\ConfigTransformer;
use App\Util\Tools;

/**
 * Class ConfigController
 * @package App\Http\ApiControllers
 */
class ConfigController extends Controller
{
    /**
     * @var ConfigRepository
     */
    public $model;

    /**
     * ConfigController constructor.
     * @param ConfigRepository $configRepository
     */
    public function __construct(ConfigRepository $configRepository)
    {
        $this->model = $configRepository;
    }

    /**
     * 获取注册协议
     * @return \Dingo\Api\Http\Response
     */
    public function agreement()
    {
        $data = $this->model->get_data_by_key('wechat_agreement');

        if (empty($data)) {
            return $this->error(404, '数据不存在');
        }

        return $this->response()->item($data, new ConfigTransformer());
    }

    /**
     * 通过商品名获取商品价格 (门诊预约/抓药/煎药 商品表固定数据)
     * @desc
     * @author Eric Chow
     * @DateTime 2018/3/15 10:31
     * @param $name
     */
    public function goods($name)
    {
        return Goods::where('name', $name)->first();
    }


    public function nav(){
        if(Tools::isTCMUserApp()){
            $navs = [
                ['name'=>'门诊预约','route'=>true,'path'=>'/doctor/type/1','msg'=>'','icon'=>'ico1'],
                ['name'=>'视频问医','route'=>true,'path'=>'/doctor/type/3','msg'=>'','icon'=>'ico6'],
                ['name'=>'在线咨询','route'=>true,'path'=>'/doctor/type/2','msg'=>'','icon'=>'ico2'],
                ['name'=>'健康数据','route'=>true,'path'=>'/data/1/0','msg'=>'','icon'=>'ico4'],
                //['name'=>'健康商城','route'=>false,'path'=>'shop1','msg'=>'更多精彩，敬请期待！','icon'=>'ico5'],
                ['name'=>'我的医生','route'=>true,'path'=>'/my_doctor','msg'=>'','icon'=>'ico3'],
            ];
        }else{
            $navs = [
                ['name'=>'门诊预约','route'=>true,'path'=>'/doctor/type/1','msg'=>'','icon'=>'ico1'],
                ['name'=>'在线咨询','route'=>true,'path'=>'/doctor/type/2','msg'=>'','icon'=>'ico2'],
                ['name'=>'我的医生','route'=>true,'path'=>'/my_doctor','msg'=>'','icon'=>'ico3'],
                ['name'=>'健康数据','route'=>true,'path'=>'/data/1/0','msg'=>'','icon'=>'ico4'],
                ['name'=>'我的预约','route'=>true,'path'=>'/order','msg'=>'','icon'=>'ico7'],
                //['name'=>'健康商城','route'=>false,'path'=>'','msg'=>'更多精彩，敬请期待！','icon'=>'ico5'],
                ['name'=>'视频看诊','route'=>false,'path'=>'videokz','msg'=>'更多精彩，敬请期待！','icon'=>'ico6','data'=>[
                    'iosUrl'=>'https://itunes.apple.com/cn/app/taiheguoyi/id1353271770?mt=8',
                    'androidUrl'=>'https://itunes.apple.com/cn/app/taiheguoyi/id1353271770?mt=8',
                ]],
            ];
        }
        return $this->success($navs);
    }
}