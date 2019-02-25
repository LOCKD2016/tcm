<?php

namespace App\Transformers;


/**
 * Class UserTransformers
 * @Auth: kingofzihua
 * @package App\Transformers
 */
class OperationLogTransformer extends BaseTransformer
{
    /**
     * @Auth: haiming
     * @param $model
     * @return array
     */
    public function transformData($model)
    {
        return [
            'id' => $model->id,
            'i' => $model->i,
            'operation_detail' => $model->operation_detail,
            'read_flag' => $model->read_flag,
            'receive_people' => $model->receive_people,
            'send_people' => $model->send_people,
            'user_id' => $model->user_id,
            'created_at' => $model->created_at->format('Y-m-d H:i:s'),
        ];
    }


}