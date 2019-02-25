<?php
/**
 * Created by PhpStorm.
 * User: lx
 * Date: 2017/11
 * Time: 17:31
 */
namespace App\Http\ApiControllers;

use App\Models\Medicine;
use Auth;
use App\Models\Recipe;
use App\ApiTransformers\RecipeTransformer;
use Illuminate\Http\Request;
use DB;

/**
 * Class RecipeController
 * @package App\Http\ApiControllers
 */
class RecipeController extends Controller
{

    protected $recipe;


    public function __construct(Recipe $recipe)
    {
        $this->recipe = $recipe;
    }

    /**
     * 医生处方列表
     * @param Request $request
     */
    public function lists(Request $request)
    {
        $data = $request->all();
        if($request->has('page')) {
            $ret = $this->recipe->where('doctor_id', '=', Auth::id())
                ->title($data['title'] ?? '')
                ->type($data['type'])->status(1)->paginate(10);
            return $this->response()->paginator($ret, new RecipeTransformer());
        }else{
            $ret = $this->recipe->where('doctor_id', '=', Auth::id())
                ->title($data['title'] ?? '')
                ->type($data['type'])->status(1)->get();
            return $this->response()->collection($ret, new RecipeTransformer());
        }




    }

    /**
     * 添加处方
     * @param Request $request
     */
    public function add(Request $request)
    {


        $validator = $this->recipe->Verification_data($request->only(['title','content']));
        if ($validator->fails()) {
            return $this->error(101, $validator->errors()->first());
        }

        $data = array_merge($request->only(['title', 'content','type']), ['doctor_id' => Auth::id()]);
        $recipe = $this->recipe->cresteDate($data);

        if ($recipe)
            return $this->success($recipe, "创建成功");

        return $this->error("101", "创建失败");

    }

    /**
     * 修改处方(需要完善)
     * @param Request $request
     * @param id
     */
    public function edit(Request $request)
    {
        $validator = $this->recipe->Verification_data($request->only(['title','content']));
        if($validator->fails()){
            return $this->error(101,$validator->errors()->first());
        }


        //$edit = $this->recipe->updateDate($id,$request->only(['title','content']));
        $edit = Recipe::where('id',$request['id'])->update(['title'=>$request['title'],'content'=>json_encode($request['content'])]);

        if($edit)
            return $this->success($edit,"修改成功");
        return $this->error('101','修改失败');
    }

    /**
     * 详情
     * @param id
     * @return mixed
     */
    public function details($id){
        if(empty($id)){
            return $this->error(101,'参数缺失');
        }

        $data = $this->recipe->getFind($id);
        if($data)
            return $this->success($data);
        return $this->error(101,'该处方不存在');

    }


    /**
     * 删除处方
     * @param id
     * @return mixed
     */
    public function del($id)
    {
        if(empty($id)){
            return $this->error(101,'参数缺失');
        }

        $ret = $this->recipe->deleteRecipe($id);

        if($ret){
            return $this->success(200, '删除成功');
        }
        return $this->error(101,'删除失败');

    }




    /**
     * 获取药材名
     * $param $request
     * @return mixed
     */
    public function medicine_all(Request $request){
        $all = $request->all();

        $data = Medicine::where('name', 'like', '%' . $all['name'] . '%')->orWhere('spell', 'like', '%' . $all['name'] . '%')->get();

        if($data){
            return $this->success($data);
        }

        return $this->error(101,'获取失败');
    }




}
