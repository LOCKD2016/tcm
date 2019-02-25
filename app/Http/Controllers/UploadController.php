<?php

namespace App\Http\Controllers;

use App\Util\QiNiu;
use App\Util\Tools;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

/**
 * Class UploadController
 * @package App\Http\Controllers
 */
class UploadController extends ApiBaseController
{
    /**
     * 图片上传
     * @desc 和 头像上传一样 人为区分下 后期图片如果要分清晰度的 话用 不分的话就算了
     * @auth kingofzihua
     * @param Request $request
     * @return string  上传文件的路径
     */
    public function image(Request $request)
    {
        if ($request->file('image')) {
            $path = $request->file('image')->store('image');

            $url = asset('/static/' . $path);

            return $this->success([
                'image_url' => $url,
                'image_thumb_url' => $url, //缩略图 备用的字段 虽然没有逻辑 但是保留 以后可能用得着
            ], '上传成功');
        }

        return $this->error("error:image");
    }

    /**
     * base64上传文件
     * @param Request $request
     * @return mixed
     */
    public function baseImage(Request $request)
    {
        if (isset($request->image)) {
            //获取base64的内容
            $base64_img = substr($request->image, 22);
            
            //不用 time() . str_random(6) 而用md5 是防止同一张图片上传多次(同一张图片 base64编码一样 md5值也是一样的)
            // 可能有错误，但是 很小的几率 有问题再改回来吧
            $path = '/image/' . md5($base64_img) . '.jpg';//获取文件名字

            //将文件移动到目录下
            $put = \Storage::disk('local')->put($path, base64_decode($base64_img));

            if ($put) { //如果成功
                $url = asset('/static' . $path);

                return $this->success([
                    'image_url' => $url,
                    'image_thumb_url' => $url, //缩略图 备用的字段 虽然没有逻辑 但是保留 以后可能用得着
                ], '上传成功');
            }

            return $this->error(500, '上传失败');
        }

        return $this->error("error:baseImage");
    }

    /**
     * 头像上传
     * @desc 暂时不做处理 后期需要再说
     * @param Request $request
     * @return mixed
     */
    public function avatar(Request $request)
    {
        if ($request->file('avatar')) {
            $path = $request->file('avatar')->store('avatar');

            $url = asset('/static' . $path);

            return $this->success(['avatar_url' => $url,], '上传成功');
        }

        return $this->error("error:avatar");
    }

    /**
     * 上传头像
     * @param Request $request
     * @return mixed
     */
    public function baseAvatar(Request $request)
    {
        if (isset($request->avatar)) {
            //获取base64的内容
            $base64_img = substr($request->avatar, 22);

            //不用 time() . str_random(6) 而用md5 是防止同一张图片上传多次(同一张图片 base64编码一样 md5值也是一样的)
            // 可能有错误，但是 很小的几率 有问题再改回来吧
            $path = '/avatar/' . md5($base64_img) . '.jpg';//获取文件名字

            //将文件移动到目录下
            $put = \Storage::disk('local')->put($path, base64_decode($base64_img));

            if ($put) {
                $url = asset('/static' . $path);

                return $this->success(['avatar_url' => $url,], '上传成功');
            }

            return $this->error(500, '上传失败');
        }

        return $this->error("error:baseAvatar");
    }

    /**
     * 图片上传到七牛
     * @author zhoupeng
     * @dateTime 2017-06-05 12:44
     * @explain 上传图片
     * @param
     */
    public function qiniu(Request $request, $tag='')
    {
        $path = '/img';
        if($tag) $path = '/slider';
        $disk = \Storage::disk('qiniu'); //使用七牛云上传
        $file = $request->file('image');
        $size = filesize($file);
        $filesize = Tools::getsize($size,'mb');

        if(!$file->isValid() || !Tools::isImage($file)){
            return $this->error(0, "请上传正确的图片");
        }
        if($filesize> 3){
            return $this->error(0, "请上传小于3M的图片");
        }

        $filename = $disk->put($path, $file);//上传
        if (!$request->hasFile('image') || !$filename) {
            return $this->error(0, "上传图片失败");
        }

        $pre_img_url = $disk->getDriver()->downloadUrl($filename); //获取下载链接
        $img_url = explode('/img', $pre_img_url)[1];

        if ($img_url) {
            if(!empty($tag)){
                return 'slider' . $img_url;
            }else{
                return $this->success([
                    'image_url' => Tools::thumbnail(QiNiu::url().'img/', substr($filename,4)),
                    'image_thumb_url' => 'img' . $img_url, //缩略图 备用的字段 虽然没有逻辑 但是保留 以后可能用得着
                ], '上传成功');
            }

        }

        return $this->error("error:image");
    }









}