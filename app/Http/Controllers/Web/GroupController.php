<?php

namespace App\Http\Controllers\Web;

use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GroupController extends BaseController
{

    /**
     * 添加分组
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {

        //验证数据
        try {

            $this->validate($request, [
                'name' => 'required|min:2|max:8',
            ], [
                'name.required' => '分组名不能为空',
                'name.min' => '分组名长度2~8个字符',
                'name.max' => '分组名长度2~8个字符',
            ]);

            $user = $this->getUser();

            $userGroup = UserGroup::firstOrCreate([

                'user_id' => $user->id,

                'group_name' => $request->input('name'),

            ]);

            if (!$userGroup->wasRecentlyCreated) {

                return response()->json(['code' => -1, 'message' => '添加失败']);

            }

            return response()->json(['code' => 0, 'message' => '添加成功']);

        } catch (ValidationException $e) {

            return response()->json(['code' => -1, 'message' => array_values($e->errors())[0][0]]);

        } catch (\Exception $e){

            return response()->json(['code' => -1, 'message' => '服务器异常', 'data' => [], 'tips' => $e->getMessage()]);

        }

    }

}
