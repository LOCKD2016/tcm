<?php

namespace App\Http\Controllers\Api;

use App\Models\AppUser;
use App\Models\Question;
use App\Models\User;
use App\Models\UserWeixin;
use App\Models\Orders;
use App\Util\Tools;
use Hamcrest\Util;
use Illuminate\Http\Request;
use App\Models\UserLnquiry;
use App\Transformers\UserLnquiryTransformer;
use App\Transformers\ProposedTransformer;
use DB;
use Intervention\Image\Point;
use App\Auth\LBWechat;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserLnquiryController extends ApiController
{
    protected $userLnquiry;

    public function __construct(UserLnquiry $userLnquiry)
    {
        $this->userLnquiry = $userLnquiry;
    }

    /**
     * 方案处理列表
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        return $this->response()->paginator($this->userLnquiry->index($request->all()), new UserLnquiryTransformer());
    }

    /**
     * 获取问诊单的穴位选择状态1
     * @return mixed
     */
    public function relate()
    {
        $res = DB::table('relate_point')->select('name', 'child_id')->get()->toArray();
        foreach ($res as $k => &$v) {
            $child = explode(',', $v->child_id);
            $point = DB::table('point')->select('id', 'name', 'img')->whereIn('id', $child)->get();
            foreach ($point as &$value) {
                $value->img = config('app.url') . '/img/point/' . $value->img;
            }
            $v->point = $point;
        }
        return $this->response->array($res);
    }

    //限制医生开方案
    public function forbid($id)
    {
        $info = UserLnquiry::find($id);
        if (!$info) return $this->error(101, '问诊单不存在');
        if ($info->doctor_id != Auth::id() || $info->doctor_id == 0) {
            return $this->error(101, '您没有权限对该问诊单进行操作');
        } else {
            return $this->success();
        }
    }

    /**
     * 添加问诊单的穴位
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function addLnquiryRelate($id, Request $request)
    {
        $row = $this->userLnquiry->lnquiryRelate($id, $request->all());
        return $this->response->array($row);
    }

    /**
     * @param $id
     * @param $family_id
     * @return mixed
     */
    public function detail($id)
    {
        $question = new Question();
        return $this->response->array($question->detail($id));
    }

    /**
     * 获取问诊单详情 -> 当前选择的穴位展示1
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $res = UserLnquiry::where('id', $id)->first();
        if (isset($res->point_id{2})) {
            $arr = json_decode($res->point_id);
            $point_arr = $arr->point;
            switch ($arr->relate) {
                case 1:
                    $res->relate = '呼吸系统';
                    break;
                case 2:
                    $res->relate = '消化系统';
                    break;
                case 3:
                    $res->relate = '颈肩腰腿痛主穴';
                    break;
                case 4:
                    $res->relate = '脏腑痛';
                    break;
                case 5:
                    $res->relate = '杂症';
                    break;
                default:
                    $res->relate = '';
            }
            $res->point_id = collect($point_arr)->implode(',');
        } else {
            $res->point_id = '';
        }
        if ($res) {
            $arr = array('data' => $res, 'status' => 200);
        } else {
            $arr = array('data' => '参数错误', 'status' => 404);
        }
        return $this->response->array($arr);
    }

    //穴位
    public function point()
    {
        return $this->response()->array($this->userLnquiry->point());
    }

    /**
     * @param $id
     * @param Request $request
     * @param $system 执行的操作类型
     * @param 修改的字段组成的关联数组
     * @return mixed
     */
    public function update($id, Request $request)
    {
        $res = UserLnquiry::where('id', $id)->first();
        if (isset($request->get('param')['status']) && $request->get('param')['status'] == 3) {
            if ($res->type == 0) {
                return $this->response->array(array('msg' => '请选择敷贴类型', 'status' => 200));
            }
        }
        if ($res->doctor_id != Auth::id() || $res->doctor_id == 0) {
            return $this->response->array(array('msg' => '您没有权限对该问诊单进行操作', 'status' => 200));
        }
        switch ($request->get('system')) {
            case 'type' :
            case 'send' :
            case 'relate':
                //验证当前问诊单是否已发送
                if ($res->status == 3) {
                    $arr = array('msg' => '问诊单已发送,不可操作', 'status' => 100);
                    return $this->response->array($arr);
                }
                $params = $request->get('param');
                if ($request->get('system') == 'relate') {
                    $params = [];
                    if (isset($request->get('param')['point'])) {
                        if (isset($request->get('param')['relate'])) {
                            $point['relate'] = $request->get('param')['relate'];
                        }
                        $point['point'] = collect($request->get('param')['point'])->pluck('name', 'id');
                        $params['point_id'] = json_encode($point);
                    } else {
                        $params['point_id'] = '';
                    }
                    $params['status'] = 2;
                    $params['doctor_id'] = \Auth::id();
                } else if ($request->get('system') == 'send') {


                }
                break;
            default :
                $arr = array('msg' => '不识别的类型', 'status' => 404);
                return $this->response->array($arr);
        }
        $row = UserLnquiry::where('id', $id)->update($params);
        if ($row) {
            return $this->response->array(array('msg' => '操作成功', 'status' => 200));
        }
        return $this->response->array(array('msg' => '操作失败', 'status' => 101));
    }

    // 微信模板
    private function pushTemplate($openid, $url, $data)
    {
        $wechat = new LBWechat([
            'appid' => config("wechat.app_id"), //填写高级调用功能的app id
            'appsecret' => config("wechat.secret") //填写高级调用功能的密钥
        ]);
        $ret = $wechat->pushMsgTemplate($openid, '08oDkEE3bIphcrd1fypnhDEB-qktUcTXj4tfwz62QMM', $url, $data);
        if (!$ret) {
            Log::info('pushMsgTemplate', ['code' => $wechat->errCode, 'msg' => $wechat->errMsg]);
        }
    }

    public function deal($id)
    {
        $info = UserLnquiry::find($id);
        if (!$info) return $this->error(101, '系统错误,查找问诊单失败');
        if ($info->status == 0) {
            if (Auth::id() > 1) {
                $info->status = 2;
                $info->doctor_id = Auth::id();
                if ($info->save()) {
                    return $this->success();
                } else {
                    return $this->error(101, '系统错误,修改问诊单状态失败');
                }
            } else {
                return $this->success();
            }
        } else {
            return $this->success();
        }
    }

    //获取问诊单详情
    public function note($id)
    {
        $res = UserLnquiry::find($id);
        if (!$res) {
            return $this->error(101, '问诊单不存在');
        }
        return $this->response()->item($res, new UserLnquiryTransformer());
    }


    //添加问诊单备注
    public function add(Request $request)
    {
        $data = $request->all();
        $res = UserLnquiry::where('id', $data['id'])->update(['note' => $data['note']]);
        if ($res) {
            return $this->success(200, '操作成功');
        } else {
            return $this->error(101, '添加失败');
        }
    }
}
