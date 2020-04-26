<?php

namespace App\Http\Controllers\Web;

use App\Models\UserFriend;
use App\User;
use Illuminate\Http\Request;

class IndexController extends BaseController
{

    //
    public function index()
    {
        return view('web.index');
    }

    /**
     * 主页信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function main(Request $request)
    {

        try{

            $user = $this->getUser($request->input('token'));

            //我的信息
            $mine = ['username' => $user->username, 'id' => $user->id, 'status' => $user->status, 'sign' => $user->sign, 'avatar' => 'http://cdn.firstlinkapp.com/upload/2016_6/1465575923433_33812.jpg'];

            $friend = [];

            //获取我的分组与分组好友
            foreach ($user->groups as $k => $group){

                $friend[$k]['groupname'] = $group['group_name'];

                $friend[$k]['id'] = $group['id'];

                $ids = UserFriend::where('group_id', $group['id'])->where('user_id', $user->id)->pluck('friend_id');

                $friend[$k]['list'] = User::whereIn('id', $ids)->select(['username', 'id', 'sign', 'avatar'])->get();


            }

            return response()->json(['code' => 0, 'msg' => '获取首页数据', 'data' => compact('mine', 'friend')]);

        }catch (\Exception $e){

            return response()->json(['code' => -1, 'message' => '服务器异常', 'data' => [], 'tips' => $e->getMessage()]);

        }

    }
}
