@extends('web.layout.main')

@extends('web.layout.token')

@section('content')

    <title>消息盒子</title>

    <style>
        .layim-msgbox {
            margin: 15px;
        }

        .layim-msgbox li {
            position: relative;
            margin-bottom: 10px;
            padding: 0 130px 10px 60px;
            padding-bottom: 10px;
            line-height: 22px;
            border-bottom: 1px dotted #e2e2e2;
        }

        .layim-msgbox .layim-msgbox-tips {
            margin: 0;
            padding: 10px 0;
            border: none;
            text-align: center;
            color: #999;
        }

        .layim-msgbox .layim-msgbox-system {
            padding: 0 10px 10px 10px;
        }

        .layim-msgbox li p span {
            padding-left: 5px;
            color: #999;
        }

        .layim-msgbox li p em {
            font-style: normal;
            color: #FF5722;
        }

        .layim-msgbox-avatar {
            position: absolute;
            left: 0;
            top: 0;
            width: 50px;
            height: 50px;
        }

        .layim-msgbox-user {
            padding-top: 5px;
        }

        .layim-msgbox-content {
            margin-top: 3px;
        }

        .layim-msgbox .layui-btn-small {
            padding: 0 15px;
            margin-left: 5px;
        }

        .layim-msgbox-btn {
            position: absolute;
            right: 0;
            top: 12px;
            color: #999;
        }
    </style>

    <ul class="layim-msgbox" id="LAY_view"></ul>

    <textarea title="消息模版" id="LAY_tpl" style="display:none;">

        @{{# layui.each(d.data, function(index, item){

            if(item.type == 1 || item.type == 2){ }}

                @{{# if(item.from_user_id == d.uid){ }}

                    <li data-uid="@{{ item.to_user_id }}">

                      <a href="javascript:void(0);" target="_blank">

                        <img src="@{{ item.avatar }}"
                             class="layui-circle layim-msgbox-avatar"
                             onerror="javascript:this.src='@{{# if(item.type == 1){ }}{{asset('static/img/empty2.jpg')}}@{{# }else{ }}{{asset('static/img/empty1.jpg')}}@{{# } }} '"
                             alt="">
                      </a>

                      <p class="layim-msgbox-user">

                        <a href="javascript:void(0);" target="_blank"><b>@{{ item.group_name||'' }}</b></a>

                        <span>@{{ item.send_time }}</span>

                      </p>

                      <p class="layim-msgbox-content">

                        @{{# if(item.type == 1){ }}

                        申请添加对方为好友

                        @{{# }else{ }}

                        申请加入该群

                        @{{# } }}

                        <span>@{{ item.remark ? '附言: '+item.remark : '' }}</span>

                      </p>

                      <p class="layim-msgbox-btn">

                        等待验证

                      </p>

                    </li>

                @{{# }else{ }}

                    <li data-uid="@{{ item.from_user_id }}" data-id="@{{ item.id }}" data-type="@{{item.type}}"
                        data-name="@{{ item.username }}"
                        data-avartar="@{{ item.avatar }}"
                        @{{# if(item.group_id){ }}
                        data-groupidx="@{{ item.group_id||'' }}" data-group="@{{ item.group_name||'' }}"
                        @{{#} }}
                        data-signature="@{{ item.sign }}">

                      <a href="javascript:void(0);" target="_blank">

                        <img src="@{{ item.avatar }}"
                             class="layui-circle layim-msgbox-avatar"
                             onerror="javascript:this.src='@{{# if(item.type == 1){ }}{{asset('static/img/empty2.jpg')}}@{{# }else{ }}{{asset('static/img/empty1.jpg')}}@{{# } }} '"
                             alt="">

                      </a>

                      <p class="layim-msgbox-user">

                        <a href="javascript:void(0);" target="_blank"><b>@{{ item.username||'' }}</b></a>

                        <span>@{{ item.send_time }}</span>

                      </p>

                      <p class="layim-msgbox-content">

                        @{{# if(item.type == 1){ }}

                        申请添加你为好友

                        @{{# }else{ }}

                        申请加入 @{{ item.group_name||'' }} 群

                        @{{# } }}

                        <span>@{{ item.remark ? '附言: '+item.remark : '' }}</span>

                      </p>

                      <p class="layim-msgbox-btn">

                        <button class="layui-btn layui-btn-small" data-type="agree">同意</button>

                        <button class="layui-btn layui-btn-small layui-btn-primary" data-type="refuse">拒绝</button>

                      </p>

                    </li>

                @{{# } }}

            @{{# } else if(item.type == 3) { }}

                @{{# if(item.from_user_id == d.uid){ }}

                    <li class="layim-msgbox-system">

                        <p><em>系统：</em><b>@{{ item.username }}</b>

                        @{{# if(item.status == 1){ }}

                        已同意你的好友申请 <button class="layui-btn layui-btn-xs btncolor chat"
                                          data-name="@{{ item.username }}" data-chattype="friend" data-type="chat"
                                          data-uid="@{{item.to}}">发起会话</button>

                        @{{# }else{ }}

                        已拒绝你的好友申请

                        @{{# } }}

                        <span>@{{ item.read_time }}</span></p>

                    </li>

                @{{# }else{ }}

                    <li>

                        <a href="javascript:void(0);" target="_blank">

                            <img src="@{{ item.avatar }}"
                                 class="layui-circle layim-msgbox-avatar"
                                 onerror="javascript:this.src='@{{# if(item.type == 3){ }}{{asset('static/img/empty2.jpg')}}@{{# }else{ }}{{asset('static/img/empty1.jpg')}}@{{# } }} '"
                                 alt="">

                        </a>

                        <p class="layim-msgbox-user">

                            <a href="javascript:void(0);" target="_blank"><b>@{{ item.username||'' }}</b></a>

                            <span>@{{ item.send_time }}</span>

                        </p>

                        <p class="layim-msgbox-content">

                            申请添加你为好友

                            <span>@{{ item.remark ? '附言: '+item.remark : '' }}</span>

                            @{{# if(item.status == 1){ }}

                            <button class="layui-btn layui-btn-xs btncolor chat" data-name="@{{ item.username }}"
                                    data-chattype="friend" data-type="chat" data-uid="@{{item.from_user_id}}">发起会话</button>

                            @{{# } }}

                        </p>

                        <p class="layim-msgbox-btn">

                            @{{# if(item.status == 1){ }}

                            已同意

                            @{{# }else{ }}

                            已拒绝

                            @{{# } }}

                      </p>

                    </li>

                @{{# } }}

            @{{# }else if(item.msgType == 4){ }}
                @{{# if(item.from == d.memberIdx){ }}
                    <li class="layim-msgbox-system">
                        <p><em>系统：</em> 管理员 @{{ item.handle }}
                            @{{# if(item.status == 2 || item.status == 4){ }}
                        已同意你加入群 <b>@{{ item.groupName }}</b> <button class="layui-btn layui-btn-xs btncolor chat"
                                                                     data-name="@{{ item.groupName }}"
                                                                     data-chattype="group" data-type="chat"
                                                                     data-uid="@{{item.to}}">发起会话</button>
                        @{{# }else{ }}
                        已拒绝你加入群 <b>@{{ item.groupName }}</b>
                        @{{# } }}
                        <span>@{{ item.readTime }}</span></p>
                    </li>
                @{{# }else{ }}
                    <li class="layim-msgbox-system">
                        <p><em>系统：</em>
                        管理员@{{ item.handle }}
                            @{{# if(item.status == 2 || item.status == 4){ }}
                        已同意 <b>@{{ item.username }}</b> 加入群 <b>@{{ item.groupName }}</b>
                        @{{# }else{ }}
                        你已拒绝 <b>@{{ item.username }}</b> 加入群 <b>@{{ item.groupName }}</b>
                        @{{# } }}
                        <span>@{{ item.readTime }}</span></p>
                    </li>
                @{{# } }}

            @{{# }
            });
        }}
    </textarea>

    <script src="{{asset('static/js/strophe-1.2.8.min.js')}}"></script>

    <script>

        layui.use(['layer', 'jquery', 'flow', 'layim'], function (layer, $, flow, layim) {

            let laytpl = layui.laytpl;

            //请求消息
            let renderMsg = function (page, callback) {

                $.ajaxSetup({

                    headers: {

                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),

                        'USER-TOKEN': window.localStorage.getItem('user_token')

                    }

                });

                $.ajax({

                    type: "GET",

                    url: '{{url('message/list')}}',

                    data: {page: page || 1},

                    dataType: "json",

                    beforeSend: function () {

                        layer.load(0, {shade: 0.1});

                    },

                    success: function (res) {

                        layer.closeAll('loading');

                        if (res.code !== 0) {

                            return layer.msg(res.msg);

                        }

                        callback && callback(res.data['messages'], res.data['pages'], res.data['uid']);

                    }

                });

            };

            //消息信息流
            flow.load({
                elem: '#LAY_view' //流加载容器
                , isAuto: false
                , end: '<li class="layim-msgbox-tips">暂无更多新消息</li>'
                , done: function (page, next) { //加载下一页
                    renderMsg(page, function (data, pages, uid) {
                        console.log(data);
                        let html = laytpl(LAY_tpl.value).render({
                            data: data
                            , page: page
                            , uid: uid
                        });
                        next(html, page < pages);
                    });
                }
            });

            //操作
            let active = {
                // IsExist: function (avatar){ //判断头像是否存在
                //     var ImgObj=new Image();
                //     ImgObj.src= avatar;
                //     if(ImgObj.fileSize > 0 || (ImgObj.width > 0 && ImgObj.height > 0))
                //     {
                //         return true;
                //     } else {
                //         return false;
                //     }
                // },
                agree: function (othis) {
                    receiveAddFriendGroup(othis, 2);//type 1添加好友 3添加群
                }
                //拒绝
                , refuse: function (othis) {
                    layer.confirm('确定拒绝吗？', function (index) {
                        parent.layui.im.receiveAddFriendGroup(othis, 3);//type 1添加好友 3添加群
                    });
                }, chat: function (othis) {//发起好友聊天
                    var uid = othis.data('uid'), avatar = "http://test.guoshanchina.com/uploads/person/" + uid + '.jpg';
                    parent.layui.layim.chat({
                        name: othis.data('name')
                        , type: othis.data('chattype')
                        , avatar: avatar
                        , id: uid
                    });
                }

            };
            //打开页面即把系统消息标记为已读
            // $(function(){
            //     $.get('../../../../../../class/doAction.php?action=set_allread', {}, function (res) {
            //     });
            // });

            $('body').on('click', '.layui-btn', function () {

                let othis = $(this), type = othis.data('type');

                active[type] ? active[type].call(this, othis) : '';

            });


            let receiveAddFriendGroup = function (othis, act) {//确认添加好友或群
                let li = othis.parents('li')
                    , type = li.data('type')
                    , uid = li.data('uid')
                    , username = li.data('name')
                    , signature = li.data('signature')
                    , msg_id = li.data('id'),
                    msg_type,
                    avatar,
                    group_id,
                    cacheData = parent.layui.layim.cache();

                if (type === 1) {

                    type = 'friend';

                    avatar = li.data('avatar');

                    msg_type = 3;

                } else {

                    type = 'group';

                    group_id = li.data('group_id');

                    msg_type = 4;

                }

                if (act === 2) {//同意

                    if (msg_type === 3) {//系统消息《添加好友》

                        let default_avatar = '{{asset('static/img/empty2.jpg')}}';

                        layim.setFriendGroup({

                            type: type,

                            username: username,//用户名称或群组名称

                            avatar: avatar ? avatar : default_avatar,

                            group: cacheData.friend || [], //获取好友分组数据

                            submit: function (group, index) {

                                layer.close(index);

                                $.ajaxSetup({

                                    headers: {

                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),

                                        'USER-TOKEN': window.localStorage.getItem('user_token')

                                    }

                                });

                                $.ajax({

                                    type: "POST",

                                    url: "{{url('message/update')}}",

                                    data: {
                                        msg_id: msg_id,
                                        msg_type: msg_type,
                                        status: 1,
                                        group_id: group,
                                        friend_uid: uid
                                    },

                                    dataType: "json",

                                    beforeSend: function () {

                                        layer.load(0, {shade: 0.1});

                                    },

                                    success: function (res) {

                                        layer.closeAll('loading');

                                        if (res.code === 0) {

                                            layim.addList({
                                                type: 'friend'
                                                , avatar: avatar ? avatar : default_avatar //好友头像
                                                , username: username //好友昵称
                                                , groupid: group //所在的分组id
                                                , id: uid //好友ID
                                                , sign: signature //好友签名
                                            });
                                            parent.layer.close(index);
                                            othis.parent().html('已同意');
                                        } else {

                                            console.log('添加失败');

                                        }

                                    }

                                });
                            }

                        });

                    } else if (msgType = 4) {
                        var default_avatar = './uploads/person/empty1.jpg';
                        $.get('class/doAction.php?action=modify_msg', {
                            msgIdx: msgIdx,
                            msgType: msgType,
                            status: status
                        }, function (res) {
                            var data = eval('(' + res + ')');
                            if (data.code == 0) {
                                var options = {
                                    applicant: uid,
                                    groupId: groupIdx,
                                    success: function (resp) {
                                        conn.subscribed({//同意添加后通知对方
                                            to: uid,
                                            message: 'addGroupSuccess'
                                        });
                                        im.sendMsg({//系统消息
                                            mine: {
                                                content: username + ' 已加入该群',
                                                timestamp: new Date().getTime()
                                            },
                                            to: {
                                                id: groupIdx,
                                                type: 'group',
                                                cmd: {
                                                    cmdName: 'joinGroup',
                                                    cmdValue: username
                                                }
                                            }
                                        });
                                    },
                                    error: function (e) {
                                    }
                                };
                                conn.agreeJoinGroup(options);
                                othis.parent().html('已同意');
                                // parent.location.reload();
                                im.contextMenu();//更新右键点击事件
                            } else if (data.code == 1) {
                                console.log(data.msg);
                            } else {
                                console.log('添加失败');
                            }
                        });
                    }

                } else {
                    $.get('class/doAction.php?action=modify_msg', {
                        msgIdx: msgIdx,
                        msgType: msgType,
                        status: status
                    }, function (res) {
                        var data = eval('(' + res + ')');
                        if (data.code == 0) {
                            conn.unsubscribed({
                                to: uid,
                                message: 'rejectAddFriend'
                            });
                            othis.parent().html('<em>已拒绝</em>');
                        }
                        layer.close(layer.index);
                    });

                }

            }

        });

    </script>

@endsection
