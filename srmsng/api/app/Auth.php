<?php
namespace app;
class Auth
{
    /**
     * It's only a validation example!
     * You should search user (on your database) by authorization token
     */
    public function getUserByToken($token)
    {
        if ($token != 'ad21a50c28789e4d7f9c0165f9e329653e4991fc042d5a369f') {
            /**
             * The throwable class must implement UnauthorizedExceptionInterface
             */
            // throw new UnauthorizedException('Invalid Token');
            return false;
        }
        $user = [
            'name' => 'Dyorg',
            'id' => 1,
            'permisssion' => 'admin'
        ];
        return $user;
    }
}