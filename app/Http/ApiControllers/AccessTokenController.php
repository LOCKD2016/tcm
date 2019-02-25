<?php
/**
 * Created by PhpStorm.
 * User: lbbniu
 * Date: 16/10/28
 * Time: 下午2:55
 */

namespace App\Http\ApiControllers;

use Laravel\Passport\Http\Controllers\AccessTokenController as ATController;
use Zend\Diactoros\Response as Psr7Response;
use Psr\Http\Message\ServerRequestInterface;

class AccessTokenController extends ATController
{
    /**
     * Authorize a client to access the user's account.
     *
     * @param  ServerRequestInterface  $request
     * @return Response
     */
    public function issueToken(ServerRequestInterface $request)
    {
        return parent::issueToken($request);
    }


}