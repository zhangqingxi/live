@extends('web.layout.main')

@extends('web.layout.token')

@section('content')

    <title>发现</title>

    <link rel="stylesheet" href="{{asset('static/layui/css/layui.demo.css')}}">

    <style type="text/css">
        .layui-find-list li img {
            position: absolute;
            left: 15px;
            top: 8px;
            width: 36px;
            height: 36px;
            border-radius: 100%;
        }
        .layui-find-list li {
            position: relative;
            height: 90px;;
            padding: 5px 15px 5px 60px;
            font-size: 0;
            cursor: pointer;
        }
        .layui-find-list li * {
            display: inline-block;
            vertical-align: top;
            font-size: 14px;
            overflow: hidden;
            text-overflow:ellipsis;
            white-space: nowrap;
        }
        .layui-find-list li span {
            margin-top: 4px;
            max-width: 155px;
        }

        .layui-find-list li p {
            display: block;
            line-height: 18px;
            font-size: 12px;
            color: #999;
            overflow: hidden;
            text-overflow:ellipsis;
            white-space: nowrap;
        }
        .back{
            cursor:pointer;
        }
        .lay_page{position: fixed;bottom: 0;margin-left: -15px;margin-bottom: 20px;background: #fff;width: 100%;}
        .layui-laypage{width: 105px;margin:0 auto;display: block}
    </style>

    <script src="{{asset('static/js/strophe-1.2.8.min.js')}}"></script>

    <div class="layui-form" style="width: 99%">

        <div class="layui-container">

            <div class="layui-row layui-col-space3">

                <div class="layui-col-xs7 mt15">

                    <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入用户名" class="layui-input">

                </div>

                <div class="layui-col-xs1 mt15" >

                    <button class="layui-btn btncolor find">查找</button>

                </div>

                <div class="layui-col-xs3 mt15">

                    <input type="radio" name="add" value="friend" title="找人" checked="">

                    <input type="radio" name="add" value="group" title="找群">

                </div>

            </div>

            <div id="list"></div>

            <textarea title="消息模版" id="LAY_tpl" style="display:none;">

                <fieldset class="layui-elem-field layui-field-title">

                  <legend>@{{ d.legend}}</legend>

                </fieldset>

                <div class="layui-row">

                    @{{# if(d.type == 'friend'){ }}

                    @{{#  layui.each(d.data, function(index, item){ }}

                        <div class="layui-col-xs3 layui-find-list">

                            <li layim-event="add">

                                <img src="@{{ item.avatar }}" onerror="javascript:this.src='{{asset('static/img/empty2.jpg')}}'"  alt="">

                                <span>@{{item.username}}(@{{item.id}})</span>

                                <p>@{{item.sign}}  @{{#  if(item.sign == ''){ }}我很懒，懒得写签名@{{#  } }} </p>

                                <button class="layui-btn layui-btn-xs btncolor add" data-type="friend" data-avatar="@{{ item.avatar }}" data-index="0" data-id="@{{ item.id }}" data-name="@{{item.username}}"><i class="layui-icon">&#xe654;</i>加好友</button>

                            </li>

                        </div>

                        @{{#  }); }}

                    @{{# }else{ }}
                    @{{#  layui.each(d.data, function(index, item){ }}
                        <div class="layui-col-xs3 layui-find-list">
                            <li layim-event="add" data-type="group" data-approval="@{{ item.approval }}" data-index="0" data-uid="@{{ item.groupIdx }}" data-name="@{{item.groupName}}">
                                <img src="../../../../../../uploads/person/@{{item.groupIdx}}.jpg " onerror="javascript:this.src='../../../../../../uploads/person/empty1.jpg'" >
                                <span>@{{item.groupName}}(@{{item.groupIdx}})</span>
                                <p>@{{item.des}}  @{{#  if(item.des == ''){ }}无@{{#  } }} </p>
                                <button class="layui-btn layui-btn-xs btncolor add" data-type="group"><i class="layui-icon">&#xe654;</i>加群</button>
                            </li>
                        </div>
                        @{{#  }); }}
                    @{{# } }}
                </div>
            </textarea>

            <div class="lay_page" id="LAY_page" ></div>
        </div>
    </div>

    <script>

        layui.use(['jquery', 'layer', 'layim','laypage','form'], function($, layer){
            let laytpl = layui.laytpl;
            // var layim = layui.layim
            //     , layer = layui.layer
            //     ,laytpl = layui.laytpl
            //     ,form = layui.form
            //     ,$ = layui.jquery
            //     ,laypage = layui.laypage;
            // var cache = parent.layui.layim.cache();
            // var url = '../../../../../../'+cache.base.getRecommend.url || {};  //获得URL参数。
            //
            // $(function(){getRecommend(); });
            // function getRecommend(){

            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),

                    'USER-TOKEN':  window.localStorage.getItem('user_token')

                }

            });

            $.ajax({

                type: "GET",

                url: "{{url('find/recommend')}}",

                data: {},

                dataType: "json",

                beforeSend: function () {

                    layer.load(0, {shade: 0.1});

                },

                success: function (res) {

                    layer.closeAll('loading');

                    if (res.code === 0) {

                        let html = laytpl(LAY_tpl.value).render({
                            data: res.data.users,
                            legend:'推荐好友',
                            type:'friend'
                        });

                        $('#list').html(html);

                    }

                }

            });

            //添加好友
            $('body').on('click', '.add', function (event){

                find($(this).data('type'), $(this).data('name'), $(this).data('avatar'), $(this).data('id'));

            }).on('click', '.createGroup', function (){

                console.log(1111)

            });

            function find(type, username, avatar, uid) {
                let cacheData = parent.layui.layim.cache();
                layui.layim.add({//弹出添加好友对话框
                    username: username || []
                    ,uid:uid
                    ,avatar: avatar || ''
                    ,group:  cacheData.friend || []
                    ,type: type
                    ,submit: function(group,remark,index){//确认发送添加请求
                        if (type === 'friend') {
                            $.ajax({
                                type: "PUT",
                                url: "{{url('message/send')}}",
                                data: {'to_user_id':uid, type: 1, remark: remark, group_id:group},
                                dataType: "json",
                                beforeSend: function () {
                                    layer.load(0, {shade: 0.1});
                                },
                                success: function (res) {
                                    layer.closeAll('loading');
                                    if (res.code === 0) {
                                        layer.msg('你申请添加'+name+'为好友的消息已发送。请等待对方确认');
                                    }else{
                                        layer.msg('你申请添加'+name+'为好友的消息发送失败。请刷新浏览器后重试');
                                    }
                                }
                            });
                        }else{
                            var options = {
                                groupId: uid,
                                success: function(resp) {
                                    if (approval == '1') {
                                        $.get('class/doAction.php?action=add_msg', {to: uid,msgType:3,remark:remark}, function (res) {
                                            var data = eval('(' + res + ')');
                                            if (data.code == 0) {
                                                layer.msg('你申请加入'+name+'的消息已发送。请等待管理员确认');
                                            }else{
                                                layer.msg('你申请加入'+name+'的消息发送失败。请刷新浏览器后重试');
                                            }
                                        });

                                    }else{
                                        layer.msg('你已加入 '+name+' 群');
                                    }
                                },
                                error: function(e) {
                                    if(e.type == 17){
                                        layer.msg('您已经在这个群组里了');
                                    }
                                }
                            };
                            conn.joinGroup(options);
                        }
                    },function(){
                        layer.close(index);
                    }
                });
            }
            // $('body').on('click', '.add', function () {//添加好友
            //     var othis = $(this), type = othis.data('type');
            //     parent.layui.im.addFriendGroup(othis,type);
            //     // type == 'friend' ? parent.layui.im.addFriend(othis,type) : parent.layui.im.addGroup(othis);
            // });
            // $('body').on('click', '.createGroup', function () {//创建群
            //     var othis = $(this);
            //     parent.layui.im.createGroup(othis);
            // });
            // $('body').on('click', '.back', function () {//返回推荐好友
            //     getRecommend();
            //     $("#LAY_page").css("display","none");
            // });
            //
            // $("body").keydown(function(event){
            //     if(event.keyCode==13){
            //         $(".find").click();
            //     }
            // });
            // $('body').on('click', '.find', function () {
            //     $("#LAY_page").css("display","block");
            //     var othis = $(this),input = othis.parents('.layui-col-space3').find('input').val();
            //     var addType = $('input:radio:checked').val();
            //     if (input) {
            //         var url = '../../../../../../'+cache.base.findFriendTotal.url || {};
            //         $.get(url,{type:addType,value:input}, function(data){
            //             var res = eval('(' + data + ')');
            //             if(res.code != 0){
            //                 return layer.msg(res.msg);
            //             }
            //             laypage.render({
            //                 elem: 'LAY_page'
            //                 ,count: res.data.count
            //                 ,limit: res.data.limit
            //                 ,prev: '<i class="layui-icon">&#58970;</i>'
            //                 ,next: '<i class="layui-icon">&#xe65b;</i>'
            //                 ,layout: ['prev', 'next']
            //                 ,curr: res.data.limit
            //                 ,jump: function(obj, first){
            //                     //obj包含了当前分页的所有参数，比如：
            //                     var url = '../../../../../../'+cache.base.findFriend.url || {};
            //                     //首次不执行
            //                     if(first){
            //                         var page = res.data.limit;
            //                     }else{
            //                         var page = obj.curr;
            //                     }
            //                     $.get(url,{type:addType,value:input,page: obj.curr || 1},function(res){
            //                         var data = eval('(' + res + ')');
            //                         var html = laytpl(LAY_tpl.value).render({
            //                             data: data.data,
            //                             legend:'<a class="back"><i class="layui-icon">&#xe65c;</>返回</a> 查找结果',
            //                             type:addType
            //                         });
            //                         $('#LAY_view').html(html);
            //                     });
            //                 }
            //             });
            //         });
            //     }
            // });
        });
    </script>

@endsection
