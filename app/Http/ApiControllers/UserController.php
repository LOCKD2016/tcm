<?php

namespace App\Http\ApiControllers;

use App\Repository\UserRepository;
use App\Transformers\UserTransformer;
use App\Transformers\GroupTransformer;

/**
 * 患者 就诊人
 * Class UsersController
 * @package App\Http\ApiControllers
 */
class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    public $model;

    /**
     * UsersController constructor.
     * @param $model
     */
    public function __construct(UserRepository $model)
    {
        $this->model = $model;
    }

    /**
     * 用户详情
     * @param $user_id
     * @return \Dingo\Api\Http\Response
     */
    public function detail($user_id)
    {
        $user = $this->model->get_data_by_id($user_id);

        return $this->response()->item($user, new UserTransformer());
    }
}
