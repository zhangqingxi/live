<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\User;

class BaseController extends Controller
{

    /**
     * 获取用户信息
     * @return mixed
     * @throws \Exception
     */
    public function getUser(string $token = '')
    {

        $token = $token ? $token : request()->header('user-token');

        if($token){

            $user = User::whereToken($token)->first();

            if(!$user){

                throw new \Exception('没有获取到用户信息');

            }

            return $user;

        }

        throw new \Exception('没有获取到用户token');

    }

}
