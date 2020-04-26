<?php

namespace App\Services;

use App\User;
use Hhxsv5\LaravelS\Swoole\WebSocketHandlerInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

class WebSocketService implements WebSocketHandlerInterface
{
    /**@var \Swoole\Table $wsTable */
    private $wsTable;
    public function __construct()
    {
        $this->wsTable = app('swoole')->wsTable;
    }

    public function onOpen(Server $server, \Swoole\Http\Request $request)
    {
        // TODO: Implement onOpen() method.

//        $server->push($request->fd, json_encode(['type' => 'init', 'data' => ['message' => "Welcome to LaravelS #{$request->fd}"]]));
        Log::info('WebSocket 连接建立');
    }

    public function onMessage(Server $server, Frame $frame)
    {

        // TODO: Implement onMessage() method.
        $data = json_decode($frame->data, true);
        
        if(is_array($data)){

            if($data['type'] === 'init'){

                $user = User::whereToken($data['data']['user_token'])->first();

                $userId = $user ? $user->id : 0; // 0 表示未登录的访客用户

                $this->wsTable->set('uid:' . $userId, ['value' => $frame->fd]);// 绑定uid到fd的映射

                $this->wsTable->set('fd:' . $frame->fd, ['value' => $userId]);// 绑定fd到uid的映射

            }

        }

        //获取所有客户端
        // 广播
//        foreach ($this->wsTable as $key => $row) {
//            if (strpos($key, 'uid:') === 0 && $server->isEstablished($row['value'])) {
//                $content = sprintf('Broadcast: new message "%s" from #%d', $frame->data, $frame->fd);
//                $server->push($row['value'], $content);
//            }
//        }
    }

    public function onClose(Server $server, $fd, $reactorId)
    {
        // TODO: Implement onClose() method.
        $uid = $this->wsTable->get('fd:' . $fd);
        if ($uid !== false) {
            $this->wsTable->del('uid:' . $uid['value']); // 解绑uid映射
        }
        $this->wsTable->del('fd:' . $fd);// 解绑fd映射
        $server->push($fd, "Goodbye #{$fd}");
        Log::info('WebSocket 连接关闭');
    }
}
