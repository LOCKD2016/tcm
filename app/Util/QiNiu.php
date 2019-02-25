<?php
namespace App\Util;

use Qiniu\Auth;
use Qiniu\Processing\PersistentFop;

class QiNiu
{

    /**
     * @var
     */
    private $access_key;
    /**
     * @var
     */
    private $secret_key;
    /**
     * @var
     */
    protected $bucket;


    public function __construct()
    {
        $config = config('filesystems.disks.qiniu');

        $this->access_key = $config['access_key'];
        $this->secret_key = $config['secret_key'];
        $this->bucket = $config['bucket'];
        if (!$this->access_key || !$this->secret_key || !$this->bucket) {
            throw new \Exception('缺少接口必须参数', 100);
        }

    }

    public function test()
    {
        $disk = \Storage::disk('qiniu');
        return $disk->exists('voice_1_1_1502940983559');
    }

    public static function url()
    {
        return 'http://static.taiheguoyi.com/';
    }


}