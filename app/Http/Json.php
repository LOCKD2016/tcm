<?php
/**
 * Created by PhpStorm.
 * User: vming
 * Date: 16/10/29
 * Time: 上午2:23
 */

namespace App\Http;
use Dingo\Api\Http\Response\Format\Format;
use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Arrayable;

class Json extends Format
{
    /**
     * Format an Eloquent model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return string
     */
    public function formatEloquentModel($model)
    {
        return $this->encode(['data' => $model->toArray()]);
    }

    /**
     * Format an Eloquent collection.
     * TODO:分页没有处理 要对分页进行处理
     *
     * @param \Illuminate\Database\Eloquent\Collection $collection
     *
     * @return string
     */
    public function formatEloquentCollection($collection)
    {
        if ($collection->isEmpty()) {
            return $this->encode(['data'=>[]]);
        }
        $arr = $collection->toArray();
        if(isset($arr['list'])){
            $data= [
                'list'=>$arr['data']
            ];
        }else{
            $data= $arr;
        }
        return $this->encode(['data'=>$data]);
    }


    /**
     * Format an array or instance implementing Arrayable.
     *
     * @param array|\Illuminate\Contracts\Support\Arrayable $content
     *
     * @return string
     */
    public function formatArray($content)
    {
        $content = $this->morphToArray($content);

        array_walk_recursive($content, function (&$value) {
            $value = $this->morphToArray($value);
        });
        if(!isset($content['data'])) $content['data'] = null;
        //对分页重新处理
        if(isset($content['meta'])){
            $meta = $content['meta'];
            unset($content['meta']);
            if(isset($meta['pagination'])){
                $list = $content['data'];
                $content['data'] = [];
                $content['data']['list']=$list;
                $content['data']['count'] = $meta['pagination']['count'];
                $content['data']['total'] = $meta['pagination']['total'];
                $content['data']['perPage'] = $meta['pagination']['per_page'];
                $content['data']['currentPage'] = $meta['pagination']['current_page'];
                $content['data']['totalPage'] = $meta['pagination']['total_pages'];
                unset($meta['pagination']);
            }
            if(isset($meta['cursor'])){
                $list = $content['data'];
                $content['data'] = [];
                $content['data']['list']=$list;
                $content['data']['current'] = $meta['cursor']['current'];
                $content['data']['prev'] = $meta['cursor']['prev'];
                $content['data']['next'] = $meta['cursor']['next'];
                $content['data']['count'] = $meta['cursor']['count'];
                unset($meta['cursor']);
            }
            foreach($meta as $k=>$v){
                $content['data'][$k] = $v;
            }
        }
        if(isset($content['status'])){
            foreach($content as $k=>$v){
                if(!in_array($k,['status','msg','errcode','data'])){
                    $content['data'][$k] = $v;
                    unset($content[$k]);
                }
            }
            //对异常数据进行格式化
            if(!in_array($content['status'],[0,1])){
                if(!isset($content['errcode']))
                    $content['errcode']= $content['status'];
                else
                    $content['data']['errcode'] = $content['status'];
                $content['status'] = 0;
            }
        }else{
            if(!isset($content['data'])) $content=['data'=>$content];
        }
        return $this->encode($content);
    }

    /**
     * Get the response content type.
     *
     * @return string
     */
    public function getContentType()
    {
        return 'application/json; charset=UTF-8';
    }

    /**
     * Morph a value to an array.
     *
     * @param array|\Illuminate\Contracts\Support\Arrayable $value
     *
     * @return array
     */
    protected function morphToArray($value)
    {
        return $value instanceof Arrayable ? $value->toArray() : $value;
    }

    /**
     * Encode the content to its JSON representation.
     *
     * @param string $content
     *
     * @return string
     */
    protected function encode($content)
    {
        if(isset($content['status'])){
            if(!isset($content['errcode'])) $content['errcode'] = 200;
            if(!isset($content['msg'])) $content['msg'] = "成功";
            if(!isset($content['data'])) $content['data'] = null;
            $data = $content;
        }else{
            $data=[
                'status'=>1,
                'msg'=>"成功",
                'errcode'=>0,
            ];
            $data = array_merge($data,$content);
        }
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
