@extends('web.layout.main')

@section('content')

    <title>登陆</title>

    <div class="templatemo-content-widget templatemo-login-widget white-bg tp-8">

        <header class="text-center">

            <h1>登陆</h1>

        </header>

        <div class="form-group">

            <div class="input-group">

                <div class="input-group-addon"><i class="fa fa-user fa-fw"></i></div>

                <label for="username"></label><input type="text" id="username" class="form-control" placeholder="请输入用户名">

            </div>

        </div>

        <div class="form-group">

            <div class="input-group">

                <div class="input-group-addon"><i class="fa fa-key fa-fw"></i></div>

                <label for="password"></label><input type="password" id="password" class="form-control" placeholder="请输入用户密码">

            </div>

        </div>

        <div class="form-group">

            <button class="layui-btn login" style="width: 47.5%">登陆</button>

            <a href="{{url('register')}}" class="layui-btn layui-btn-danger" style="width: 47.5%">注册</a>

        </div>

    </div>

    <script>

        layui.use(['layer', 'jquery'], function (layer, $) {

            $('.login').click(function () {

                let username = $('#username').val(),
                    password = $('#password').val();

                if (username === '' || $.trim(username).length === 0) {

                    layer.tips('请输入用户名', '#username', {tips: [3, '#3595cc'], anim: 4});

                    return false;

                }

                if (password === '' || $.trim(password).length === 0) {

                    layer.tips('请输入用户密码', '#password', {tips: [3, '#3595cc'], anim: 4});

                    return false;

                }

                $.ajaxSetup({

                    headers:{

                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                    }

                });

                $.ajax({

                    type: "POST",

                    url: "{{url('login')}}",

                    data: {username: username, password: password},

                    dataType: "json",

                    beforeSend: function () {

                        layer.load(0, {shade: 0.1});

                    },

                    success: function (res) {

                        layer.closeAll('loading');

                        if (res.code === 0) {

                            layer.msg(res.message, {icon: 1});

                            window.localStorage.setItem('user_token', res.data.token);

                            window.location.href = '/';

                        } else {

                            layer.alert(res.message, {icon: 2});

                        }

                    }

                });

            });

        });

    </script>

@endsection
