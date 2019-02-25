<?php
namespace App\Controller;

use Swoole\Client\CURL;

class Page extends \Swoole\Controller
{
    function index()
    {
        $this->session->start();
        if (!empty($_SESSION['isLogin']))
        {
            chatroom:
            $this->http->redirect('/page/chatroom/');
            return;
        }
        $_SESSION['isLogin'] = 1;
        $user = [
            'nickname'=>mt_rand(10000,99999),
            'avatar'=>'http://qzapp.qlogo.cn/qzapp/221403/12D0B6C91A045A126CA54558E22FFEA5/100',
        ];
        $_SESSION['user'] = $user;
        $this->http->redirect('/page/chatroom/');
    }

    function chatroom()
    {
        $this->session->start();
        if (empty($_SESSION['isLogin']))
        {
            $this->http->redirect('/page/index/');
            return;
        }
        $user = $_SESSION['user'];
        $this->assign('user', $user);
        $this->assign('debug', 'true');
        $this->display('page/chatroom.php');
    }

    /**
     * 用flash添加照片
     */
    function upload()
    {
        if ($_FILES)
        {
            global $php;
            $php->upload->thumb_width = 136;
            $php->upload->thumb_height = 136;
            $php->upload->thumb_qulitity = 100;
            $up_pic = $php->upload->save('Filedata');
            if (empty($up_pic))
            {
                echo '上传失败，请重新上传！ Error:' . $php->upload->error_msg;
            }
            echo json_encode($up_pic);
        }
        else
        {
            echo "Bad Request\n";
        }
    }
}