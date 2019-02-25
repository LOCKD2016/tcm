<?php

namespace App\Http\ApiControllers;

use Illuminate\Http\Request;
use App\Repository\GroupRepository;
use App\Transformers\UserTransformer;
use App\Transformers\GroupTransformer;
use App\Http\Requests\GroupSaveRequest;

/**
 * 医生 的分组
 * Class GroupController
 * @package App\Http\ApiControllers
 */
class GroupController extends Controller
{
    /**
     * @var
     */
    protected $model;

    /**
     * GroupController constructor.
     * @param GroupRepository $groupRepository
     */
    public function __construct(GroupRepository $groupRepository)
    {
        $this->model = $groupRepository;
    }

    /**
     * 列表
     * @return \Dingo\Api\Http\Response
     */
    public function lists()
    {
        $groups = \Auth::user()->groups;

        return $this->response()->collection($groups, new GroupTransformer());
    }

    /**
     * 用户的列表
     * @param $group_id
     * @return \Dingo\Api\Http\Response
     */
    public function userLists($group_id)
    {
        $list = $this->model->group_user_list_by_id($group_id);

        return $this->response()->paginator($list, new  UserTransformer());
    }

    /**
     * 通过用户编号获取用户所在当前医生的分组
     * @param $user_id
     * @return mixed
     */
    public function users($user_id)
    {
        $groups = \Auth::user()->selectGroupByUserId($user_id);

        return $this->response()->collection($groups, new GroupTransformer());
    }

    /**
     * 添加分组
     * @param GroupSaveRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function save(GroupSaveRequest $request)
    {
        $group = $this->model->create([
            'name' => $request->name,
            'doctor_id' => \Auth::id(),
        ]);

        if ($group) {
            return $this->response()->item($group, new GroupTransformer());
        }

        return $this->error(502, '添加失败');
    }

    /**
     * 分组内添加成员
     * @desc 一定要注意已存在的成员添加这种情况 num 会有问题 要前台判断
     * @param Request $request [
     *      'user_ids',//array 用户的编号数组
     * ]
     * @param $group_id
     * @return mixed
     */
    public function addUser(Request $request, $group_id)
    {
        try {
            $ids = \GuzzleHttp\json_decode($request->user_ids);
        } catch (\InvalidArgumentException $exception) {
            return $this->error(500, $exception->getMessage());
        }

        $group = $this->model->get_data_by_id($group_id);

        $group->users()->attach($ids);

        $group->increment('num', count($ids));

        return $group ? $this->success([], '添加成功') : $this->error(502, '添加失败');
    }

    /**
     * 分组内删除成员
     * @desc 一定要注意不存在的成员删除这种情况 num 会有问题 要前台判断
     * @param Request $request [
     *      'user_ids',//array 用户的编号数组
     * ]
     * @param $group_id
     * @return mixed
     */
    public function removeUser(Request $request, $group_id)
    {
        try {
            $ids = \GuzzleHttp\json_decode($request->user_ids);
        } catch (\InvalidArgumentException $exception) {
            return $this->error(500, $exception->getMessage());
        }

        $group = $this->model->get_data_by_id($group_id);

        $group->users()->detach($ids);

        $group->decrement('num', count($ids));

        return $group ? $this->success([], '删除成功') : $this->error(502, '删除失败');
    }

    /**
     * 同步分组内的成员
     * @param Request $request [
     *      'user_ids',//array 用户的编号数组
     * ]
     * @param $group_id
     * @return mixed
     */
    public function syncUser(Request $request, $group_id)
    {
        try {
            $ids = \GuzzleHttp\json_decode($request->user_ids);
        } catch (\InvalidArgumentException $exception) {
            return $this->error(500, $exception->getMessage());
        }

        $group = $this->model->get_data_by_id($group_id);

        $sync = $group->users()->sync($ids);

        $update = $group->update(['num' => count($ids)]);

        return $group ? $this->success([], '同步成功') : $this->error(502, '同步失败');
    }

    /**
     * 同步用户分组
     * @param Request $request [
     *      'groups'=>[//array 用户的编号数组
     *          "24",
     *          100,
     *          101,
     *          "感冒"
     *      ],
     * ]
     * @param $user_id
     * @return mixed
     */
    public function syncUserGroup(Request $request, $user_id)
    {
        //验证传过来的数据是不是正确的
        try {
            $groups = \GuzzleHttp\json_decode($request->groups);
        } catch (\InvalidArgumentException $exception) {
            return $this->error(500, $exception->getMessage());
        }

        //通过传过来的 数组获取 已存在的数组
        $already_ids = $this->model->get_group_data_in_id($groups);

        //获取数据库不存在的数据
        $diff = collect($groups)->diff($already_ids)->all();

        //所有的分组编号
        $ids = $already_ids;

        //判断下是否数据库不存在的数据
        if (count($diff)) {
            //有新建的分组
            $new_group_ids = array_map(function ($dif) {
                $new_group = $this->model->get_data_by_name($dif) ?: $this->model->create(['doctor_id' => \Auth::id(), 'name' => $dif, 'num' => '0',]);

                return $new_group ? $new_group->id : null;
            }, $diff);

            //去除null
            $new_group_ids = array_filter($new_group_ids);

            //有新的 ,所有的放到一块
            if ($new_group_ids) {
                $ids = array_merge($already_ids, array_filter($new_group_ids));
            }
        }


        //获取登录医生所有的分组
        $auth_doctor_group = \Auth::user()->groups->each(function ($group) use ($ids, $user_id) {
            //先在当前分组内删除 用户编号 不管有没有
            $group->users()->detach($user_id);

            //如果当前分组存在
            if (in_array($group->id, $ids)) {
                $group->users()->attach($user_id);
            }

            //同步下分组内的成员数量
            $group->update(['num' => count($group->users)]);
        });

        return $this->success([], '同步成功');
    }

}