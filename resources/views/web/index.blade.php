@extends('web.layout.main')

@extends('web.layout.token')

@section('content')

    <title>聊天室</title>

    <link rel="stylesheet" href="{{asset('static/css/menu.css')}}">

    <script src="{{asset('static/js/strophe-1.2.8.min.js')}}"></script>

    <script src="{{asset('static/layui/layui.js')}}"></script>

    <script>

        layui.use(['layim', 'jquery'], function(layim, $){

            let msgBox = 0,cacheData;

            const ws = 'wss://live.zhangqingxi.cn/ws';

            let webSocket = new WebSocket(ws);

            webSocket.onopen = function () {

                console.log("连接成功");

                webSocket.send(JSON.stringify({type:'init', 'data':{'user_token': window.localStorage.getItem('user_token')}}))

            };

            //服务端发送消息的触发事件
            webSocket.onmessage = function (event) {

                let data = $.parseJSON(event.data);

                console.log(data);

                if(data.type === 'message'){

                    layim.msgbox(++msgBox);

                }else if(data.type === 'chat'){

                    layim.getMessage({
                        username: data.data.message.username,
                        avatar: data.data.message.avatar || '{{asset('static/img/empty2.jpg')}}',
                        id: data.data.message.id,
                        type: data.data.message.type,
                        content: data.data.message.content
                    });

                }

            };

            //服务端关闭的触发事件
            webSocket.onclose = function (event) {

                console.log("服务器已关闭");

            };

            layim.config({
                //初始化接口
                init: {
                    url: '{{url('init')}}',data:{'token': window.localStorage.getItem('user_token')}
                },
                isAudio: true,//开启聊天工具栏音频
                isVideo: true,//开启聊天工具栏视频
                groupMembers: true,
                //扩展工具栏
                //tool: [{
                //         alias: 'code'
                //         , title: '代码'
                //         , icon: '&#xe64e;'
                //     }],
                title: 'layim',
                copyright:true,
                initSkin: '1.jpg',//1-5 设置初始背景
                notice: true,//是否开启桌面消息提醒，默认false
                systemNotice: false,//是否开启系统消息提醒，默认false
                msgbox: '{{url('message')}}',
                find: '{{url('find')}}', //发现页面地址，若不开启，剔除该项即可
                addGroup:'{{url('group/add')}}'
            });

            layim.on('ready', function(res){

                cacheData = layim.cache();

            });

            layim.on('sendMessage', function (data) { //监听发送消息

                if (data.to.id === data.mine.id) {

                    layer.msg('不能给自己发送消息');

                    return;

                }

                if (data.to.type === 'friend') {

                    data.to.gid = 0;

                    sendMsg(data);

                }else{

                    let _time = (new Date()).valueOf();//当前时间

                    let gagTime = parseInt(layui.layim.thisChat().data.gagTime);

                    if (gagTime < _time) {

                        sendMsg(data);

                    }else{

                        layer.alert('当前为禁言状态，消息未发送成功！', {icon: 2});

                    }
                }
            });

            let sendMsg = function (data) {  //根据layim提供的data数据，进行解析

                $.ajaxSetup({

                    headers:{

                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),

                        'USER-TOKEN': window.localStorage.getItem('user_token')

                    }

                });

                $.ajax({

                    type: "PUT",

                    url: "{{url('chat/send')}}",

                    data: {to_user_id: data.to.id, content: data.mine.content,type:data.to.type,group_id: data.to.gid},

                    dataType: "json",

                    beforeSend: function () {

                        layer.load(0, {shade: 0.1});

                    },

                    success: function (res) {

                        layer.closeAll('loading');

                        if (res.code === 0) {


                        } else {

                            layer.alert(res.message, {icon: 2});

                        }

                    }

                });

            }

        });

    </script>

{{--    <script>--}}

{{--        //layui绑定扩展--}}
{{--        // layui.config({--}}
{{--        //--}}
{{--        //     base: 'static/js/'--}}
{{--        //--}}
{{--        // }).extend({--}}
{{--        //--}}
{{--        //     socket: 'socket',--}}
{{--        //--}}
{{--        // });--}}

{{--        layui.use(['layim', 'jquery'], function (layim, $) {--}}
{{--            // var $ = layui.jquery;--}}
{{--            // var socket = layui.socket;--}}
{{--            // var token = $('body').data('token');--}}
{{--            // var rykey = $('body').data('rykey');--}}
{{--            // socket.config({--}}
{{--            //     // user: token,--}}
{{--            //     // pwd: rykey ,--}}
{{--            //     layim: layim,--}}
{{--            // });--}}
{{--            layim.config({--}}
{{--                init: {--}}
{{--                    url: '{{url('init')}}'--}}
{{--                },--}}
{{--                // init: {--}}
{{--                //     url: 'class/doAction.php?action=get_user_data', data: {}--}}
{{--                // },--}}
{{--                // //获取群成员--}}
{{--                // members: {--}}
{{--                //     url: 'class/doAction.php?action=groupMembers', data: {}--}}
{{--                // }--}}
{{--                // //上传图片接口--}}
{{--                // , uploadImage: {--}}
{{--                //     url: 'class/doAction.php?action=uploadImage' //（返回的数据格式见下文）--}}
{{--                //     , type: 'post' //默认post--}}
{{--                // }--}}
{{--                // //上传文件接口--}}
{{--                // , uploadFile: {--}}
{{--                //     url: 'class/doAction.php?action=uploadFile' //--}}
{{--                //     , type: 'post' //默认post--}}
{{--                // }--}}
{{--                // //自定义皮肤--}}
{{--                // ,uploadSkin: {--}}
{{--                //     url: 'class/doAction.php?action=uploadSkin'--}}
{{--                //     , type: 'post' //默认post--}}
{{--                // }--}}
{{--                // //选择系统皮肤--}}
{{--                // ,systemSkin: {--}}
{{--                //     url: 'class/doAction.php?action=systemSkin'--}}
{{--                //     , type: 'post' //默认post--}}
{{--                // }--}}
{{--                // //获取推荐好友--}}
{{--                // ,getRecommend:{--}}
{{--                //     url: 'class/doAction.php?action=getRecommend'--}}
{{--                //     , type: 'get' //默认--}}
{{--                // }--}}
{{--                // //查找好友总数--}}
{{--                // ,findFriendTotal:{--}}
{{--                //     url: 'class/doAction.php?action=findFriendTotal'--}}
{{--                //     , type: 'get' //默认--}}
{{--                // }--}}
{{--                // //查找好友--}}
{{--                // ,findFriend:{--}}
{{--                //     url: 'class/doAction.php?action=findFriend'--}}
{{--                //     , type: 'get' //默认--}}
{{--                // }--}}
{{--                // //获取好友资料--}}
{{--                // ,getInformation:{--}}
{{--                //     url: 'class/doAction.php?action=getInformation'--}}
{{--                //     , type: 'get' //默认--}}
{{--                // }--}}
{{--                // //保存我的资料--}}
{{--                // ,saveMyInformation:{--}}
{{--                //     url: 'class/doAction.php?action=saveMyInformation'--}}
{{--                //     , type: 'post' //默认--}}
{{--                // }--}}
{{--                // //提交建群信息--}}
{{--                // ,commitGroupInfo:{--}}
{{--                //     url: 'class/doAction.php?action=commitGroupInfo'--}}
{{--                //     , type: 'get' //默认--}}
{{--                // }--}}
{{--                // //获取系统消息--}}
{{--                // ,getMsgBox:{--}}
{{--                //     url: 'class/doAction.php?action=getMsgBox'--}}
{{--                //     , type: 'get' //默认post--}}
{{--                // }--}}
{{--                // //获取总的记录数--}}
{{--                // ,getChatLogTotal:{--}}
{{--                //     url: 'class/doAction.php?action=getChatLogTotal'--}}
{{--                //     , type: 'get' //默认post--}}
{{--                // }--}}
{{--                // //获取历史记录--}}
{{--                // ,getChatLog:{--}}
{{--                //     url: 'class/doAction.php?action=getChatLog'--}}
{{--                //     , type: 'get' //默认post--}}
{{--                // }--}}
{{--                isAudio: true //开启聊天工具栏音频--}}
{{--                , isVideo: true //开启聊天工具栏视频--}}
{{--                , groupMembers: true--}}
{{--                //扩展工具栏--}}
{{--                // , tool: [{--}}
{{--                //         alias: 'code'--}}
{{--                //         , title: '代码'--}}
{{--                //         , icon: '&#xe64e;'--}}
{{--                //     }]--}}
{{--                ,title: 'layim'--}}
{{--                ,copyright:true--}}
{{--                , initSkin: '1.jpg' //1-5 设置初始背景--}}
{{--                , notice: true //是否开启桌面消息提醒，默认false--}}
{{--                , systemNotice: false //是否开启系统消息提醒，默认false--}}
{{--                , msgbox: layui.cache.dir + 'css/modules/layim/html/msgbox.html' //消息盒子页面地址，若不开启，剔除该项即可--}}
{{--                , find: '111' //发现页面地址，若不开启，剔除该项即可--}}
{{--                , chatLog: layui.cache.dir + 'css/modules/layim/html/chatlog.html' //聊天记录页面地址，若不开启，剔除该项即可--}}
{{--                , createGroup: layui.cache.dir + '@extends('web.layout.main')' //创建群页面地址，若不开启，剔除该项即可--}}
{{--                , Information: layui.cache.dir + 'css/modules/layim/html/getInformation.html' //好友群资料页面--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}

@endsection
