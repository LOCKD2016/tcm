<?php

namespace App\Http\ApiControllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Alchemy\BinaryDriver\Exception\ExecutionFailureException;




class UploadController extends Controller
{

    /**
     * 文件上传
     * @auth kingofzihua
     * @param Request $request
     * @return imageUrl  上传文件的路径
     */
    public function image(Request $request)
    {
        ini_set('memory_limit','512M');
        $data = $request->all();
        $type = !empty($data['type']) ? $data['type'] : 1;
        $t = md5(time());
        switch ($type) {
            case "1":
                $file = '/avatars/' . $t . '-dst.jpg';
                $small = '/avatars/' . $t . '-small.jpg';
                break;
            case "2":
                $file = '/auth/' . $t . '-dst.jpg';
                break;
            default:
                return $this->error('404', '不识别的type类型');
        }

        Storage::disk('public')->put($file, base64_decode(substr($request->get('img'), 22)));
        $img = Image::make(storage_path('app/public') . $file);
        if ($type == '1') {
            $img->resize('400', null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(storage_path('app/public') . $small);

            $result['big'] = asset('/storage' . $file);
            $result['small'] = asset('/storage' . $small);
        } else {
            $result = asset('/storage' . $file);
        }

        return $this->success($result);
    }

    public function qiniuAuido(Request $request, $tag=''){

        $path = 'avthumb/mp3';


        $disk = \Storage::disk('qiniu'); //使用七牛云上传

        $file = $request->file('image');

        if(!$file->isValid()){
            return $this->error(0, "失败");
        }
        $newName = date('ymdhis').'.amr';
        //$filename = $disk->put($path, $file);//上传
        $filename = $disk->putFileAs($path,$file,$newName);
        if(!$filename) {
            return $this->error(0, "上传失败");
        }
        $pre_img_url = $disk->getDriver()->downloadUrl($filename); //获取下载链接123


        return  $this->QiNiu('avthumb/mp3/'.explode('mp3/', $pre_img_url)[1]);


    }


    public function QiNiu($key)
    {
        $fops = "avthumb/mp3";
        $config = config('filesystems.disks.qiniu');

        //$ret = app('PersistentFop')->execute($key, $fops,'tcm_audio');
        $ret = app('PersistentFop')->execute($config['bucket'],$key, $fops,$config['pipeline']);
        if (isset($ret[0]) && $ret[0]) {
            $key = $this->status($ret[0]);
            if ($key)
                return $this->success($key);
        }

        return $this->error(100, '操作失败');
    }

    public function status($id, $num = 0)
    {
        if ($num >= 3)
            return false;
        sleep(1);
        $return = app('PersistentFop')->status($id);
        if (isset($return[0]) && $return[0]) {
            /**
             * code  [0:转码成功,1:转码进行中]
             */
            if ($return[0]['code'] == 0) {
                return $return[0]['items'][0]['key'];
            } else {
                return $this->status($id, $num + 1);
            }
        }
    }

}
