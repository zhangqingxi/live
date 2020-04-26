<?php

namespace App\Http\Controllers\Web;

use App\User;
use Illuminate\Http\Request;

class FindController extends BaseController
{

    public function index()
    {
        return view('web.find.index');
    }

    /**
     * 推荐好友
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recommend(Request $request)
    {

        try {

            $user = $this->getUser();

            $users = User::where('id', '<>', $user->id)->select(['id', 'username', 'sex', 'sign', 'avatar'])->inRandomOrder()->paginate(16);

            $users = $users->items() ?? [];

            return response()->json(['code' => 0, 'message' => '推荐用户', 'data' => compact('users')]);

        }catch (\Exception $e){

            return response()->json(['code' => -1, 'message' => '服务器异常', 'data' => [], 'tips' => $e->getMessage()]);

        }

    }

}
