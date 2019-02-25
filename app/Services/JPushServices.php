<?php
namespace App\Services;

use JPush\Client as JPush;
use JPush\Exceptions\APIRequestException;

/**
 * Class JPushServices
 * @Auth: kingofzihua
 * @package App\Services
 */
class JPushServices
{
    /**
     * @Auth: kingofzihua
     * @var string
     */
    protected $client;

    /**
     * @Auth: kingofzihua
     * JPushServices constructor.
     * @param string $appId
     * @param string $appKey
     */
    public function __construct()
    {
        $this->client = new JPush(
            config('services.ajpush.appKey'),
            config('services.ajpush.secret'),
            storage_path('logs/') . 'push.log');
    }

    /**
     * @Auth: kingofzihua
     * @param $userId array|string 所要接受的用户编号
     * @param $data array [
     *      'title',//标题
     *      'content',//内容
     *      'extras',//扩展字段
     * ]
     * @return array|bool|\Exception|APIRequestException
     */
    public function send($userId, $data)
    {
        try {

            $userIds = $this->getUserIds($userId);

            if (!$userIds) { //
                return false;
            }

            return $this->client->push()
                ->setPlatform(['android', 'ios'])//推送平台
                ->addRegistrationId($userIds)//接受用户的ids
                ->setNotificationAlert($data['content'])// 简单地给所有平台推送相同的 alert 消息
                ->iosNotification( //ios
                    [
                        'title' => $data['title'],
                        'body' => $data['content'],
                    ],//	表示通知内容，会覆盖上级统一指定的 alert 信息；默认内容可以为空字符串，表示不展示到通知栏, 支持字符串和数组两种形式
                    [
                        'sound' => '', //表示通知提示声音，默认填充为空字符串
                        'badge' => '+1', //表示应用角标，把角标数字改为指定的数字；为 0 表示清除，支持 '+1','-1' 这样的字符串，表示在原有的 badge 基础上进行增减，默认填充为 '+1'
                        //'extras' => isset($data['extras']) ? : [],//表示扩展字段，接受一个数组，自定义 Key/value 信息以供业务使用
                        'extras' => isset($data['extras']) ? $data['extras'] : [],//表示扩展字段，接受一个数组，自定义 Key/value 信息以供业务使用
                    ]
                )
                ->androidNotification(//android
                    $data['content'],
                    [
                        'title' => $data['title'],
                        'builder_id' => 2, //表示通知栏样式 ID
                        //'extras' => isset($data['extras']) ? $data['extras'] : [],
                        'extras' => isset($data['extras']) ? $data['extras'] : [],
                    ]
                )
               // ->setMessage($data['content'],$data['title'],null,isset($data['extras']) ? $data['extras'] : [])
                ->message($data['content'],[
                    'title' => $data['title'],
                   'extras' => isset($data['extras']) ? $data['extras'] : [],
                   //'extras' => isset($data['extras']) ? $data['extras'] : [],
                ])
                ->options([
                    'apns_production' => true
                ])
                ->send();

        } catch (APIRequestException $e) {
            return $e;
        }

        return false;
    }


    /**
     * 获取用户的Ids
     * @Auth: kingofzihua
     * @param $userId
     * @return array|bool
     */
    public function getUserIds($userId)
    {
        if (is_array($userId)) {
            return $userId;
        } else if (is_string($userId)) {
            return explode(',', $userId);
        } else {
            return false;
        }
    }

}
