<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    //
    public function index()
    {
        return view('web.login.index');
    }

    /**
     * 登陆
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        //验证数据
        try {

            $this->validate($request, [
                'username' => 'required|min:3|max:8',
                'password' => 'required|max:16|min:6',
            ],[
                'username.required' => '用户名不能为空',
                'username.min' => '用户名长度3~8个字符',
                'username.max' => '用户名长度3~8个字符',
                'password.required' => '密码不能为空',
                'password.min' => '密码长度6~16个字符',
                'password.max' => '密码长度6~16个字符',
            ]);

            $username = $request->input('username');

            $password = $request->input('password');

            if(!$user = User::whereUsername($username)->select(['id', 'username', 'password', 'token'])->first()){

                return response()->json(['code' => -1, 'message' => '账号不存在']);

            }

            if (!Hash::check($password, $user->password)) {

                return response()->json(['code' => -1, 'message' => '密码错误']);

            }

            $user->token = Str::random(60);

            $user->save();

            return response()->json(['code' => 0, 'message' => '登陆成功', 'data' => ['token' => $user->token]]);

        } catch (ValidationException $e) {

            return response()->json(['code' => -1, 'message' => array_values($e->errors())[0][0]]);

        } catch (\Exception $e){

            return response()->json(['code' => -1, 'message' => '服务器异常', 'data' => [], 'tips' => $e->getMessage()]);

        }

    }

}
