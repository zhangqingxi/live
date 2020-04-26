<?php

namespace App\Http\Controllers\Web;

use App\Models\ChatLog;
use App\Models\Message;
use App\Models\UserFriend;
use App\User;
use Illuminate\Http\Request;

class ChatController extends BaseController
{

    /**
     * 发送消息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Request $request)
    {

        //验证数据
        try{

            $user = $this->getUser();

            $chat = ChatLog::create([

                'type' =>  $request->input('type') !== 'friend' ? 1 : 0,

                'from_user_id' => $user->id,

                'to_user_id' => $request->input('to_user_id'),

                'content' => $request->input('content'),

                'group_id' => $request->input('group_id'),

            ]);

            if (!$chat->wasRecentlyCreated) {

                return response()->json(['code' => -1, 'message' => '发送消息失败']);

            }

            $message['username'] = $user->username;

            $message['id'] = $user->id;

            $message['avatar'] = $user->avatar;

            $message['type'] = $request->input('type');

            $message['content'] = $chat->content;

            //发送消息 通知用户
            $server = app('swoole');

            $table = $server->wsTable->get('uid:' . $chat->to_user_id);

            if($table && isset($table['value']) && $server->isEstablished($fd = $table['value'])) {

                $server->push($fd, json_encode(['type' => 'chat', 'data' => compact('message')]));

            }

            return response()->json(['code' => 0, 'message' => '发送消息成功', 'data' => []]);

        }catch (\Exception $e){

            return response()->json(['code' => -1, 'message' => '服务器异常', 'tips' => $e->getMessage()]);

        }

    }


    /**
     * 发送消息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists(Request $request)
    {

        //验证数据
        try{

            $user = $this->getUser();

            $messages = Message::where('to_user_id', $user->id)->whereOr('from_user_id', $user->id)->paginate(10);

            $messages = $messages->items() ?? [];

            foreach ($messages as $key => $value){

                $messages[$key]['send_time'] = $value['created_at']->format('m月d日');

                $messages[$key]['read_time'] = $value['updated_at']->format('m月d日');

                $userId = '';

                if ($value['to_user_id'] == $user->id) {

                    $userId = $value['from_user_id'];//收到加好友消息（被添加者接收消息）

                }elseif($value['from_user_id'] == $user->id ){

                    $userId = $value['to_user_id'];//收到系统消息(申请是否通过) 加好友消息（添加者接收消息）
                }

                $info = User::where('id', $userId)->select('username', 'avatar', 'sign')->first();

                $messages[$key]['username'] = $info['username'];

                $messages[$key]['sign'] = $info['sign'];

                $messages[$key]['avatar'] = $info['avatar'];

            }

            $uid = $user->id;

            $count = Message::where('to_user_id', $user->id)->count();

            $pages = ceil($count / count($messages));

            return response()->json(['code' => 0, 'message' => '获取消息', 'data' => compact('messages', 'uid', 'pages')]);

        }catch (\Exception $e){

            return response()->json(['code' => -1, 'message' => '服务器异常', 'tips' => $e->getMessage()]);

        }

    }

    /**
     * 更新消息状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {

        //验证数据
        try{

            //获取信息
            $message = Message::whereId($request->input('msg_id'))->first();

            if (!$message){

                return response()->json(['code' => -1, 'message' => '此消息不存在', 'data' => []]);

            }

            //获取用户信息
            $user = $this->getUser();

            if ($message->to_user_id !== $user->id || $message->from_user_id !== $friend_id = (int)$request->input('friend_uid')){

                return response()->json(['code' => -1, 'message' => '消息来源错误', 'data' => []]);

            }

            //好友信息
            $friend_info = User::whereId($friend_id)->first();

            if (!$friend_info){

                return response()->json(['code' => -1, 'message' => '要添加的好友不存在', 'data' => []]);

            }

            //更新消息
            $message->type = $type = $request->input('msg_type');

            $message->status = $request->input('status');

            $message->save();

            if($type == 3){//添加好友

                //我 ==> 好友
                UserFriend::firstOrCreate([

                    'user_id' => $user->id,

                    'friend_id' => $friend_id,

                    'nickname' => $friend_info->username,

                    'group_id' => $request->input('group_id'),

                ]);

                //好友 ==> 我
                UserFriend::firstOrCreate([

                    'user_id' => $friend_id,

                    'friend_id' => $user->id,

                    'nickname' => $user->username,

                    'group_id' => $message->group_id,

                ]);

            }else{

                return response()->json(['code' => -1, 'message' => $type, 'data' => []]);

            }

            return response()->json(['code' => 0, 'message' => '', 'data' => []]);

        }catch (\Exception $e){

            return response()->json(['code' => -1, 'message' => '服务器异常', 'tips' => $e->getMessage()]);

        }

    }

}
