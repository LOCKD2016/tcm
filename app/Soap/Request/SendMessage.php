<?php

namespace App\Soap\Request;

/**
 * Class SendMessageRequest
 * @Auth: kingofzihua
 * @package App\Soap\Request
 */
class SendMessage
{
    /**
     * @Auth: kingofzihua
     * @var null
     */
    public $Josn = null;

    /**
     * @Auth: kingofzihua
     * SendMessageRequest constructor.
     * @param null $Josn
     */
    public function __construct(array $Josn)
    {
        $this->Josn = json_encode($Josn, JSON_UNESCAPED_UNICODE);
    }
}