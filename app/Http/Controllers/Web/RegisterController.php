<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    //
    public function index()
    {
        return view('web.register.index');
    }

    /**
     * 注册用户
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {

        //验证数据
        try {

            $this->validate($request, [
                'username' => 'required|min:3|max:8',
                'password' => 'required|max:16|min:6|same:re_password',
            ], [
                'username.required' => '用户名不能为空',
                'username.min' => '用户名长度3~8个字符',
                'username.max' => '用户名长度3~8个字符',
                'password.required' => '密码不能为空',
                'password.min' => '密码长度6~16个字符',
                'password.max' => '密码长度6~16个字符',
                'password.same' => '两次输入的密码不匹配',
            ]);

            $user = User::create([

                'username' => $request->input('username'),

                'password' => Hash::make($request->input('password')),

                'token' => Str::random(60),

            ]);

            if (!$user->wasRecentlyCreated) {

                return response()->json(['code' => -1, 'message' => '注册失败']);

            }

            return response()->json(['code' => 0, 'message' => '注册成功', 'data' => ['token' => $user->token]]);

        } catch (ValidationException $e) {

            return response()->json(['code' => -1, 'message' => array_values($e->errors())[0][0]]);

        } catch (\Exception $e){

            return response()->json(['code' => -1, 'message' => '服务器异常', 'data' => [], 'tips' => $e->getMessage()]);

        }

    }

}
