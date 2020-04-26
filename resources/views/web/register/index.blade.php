@extends('web.layout.main')

@section('content')

    <title>注册</title>

    <div class="templatemo-content-widget templatemo-login-widget white-bg tp-8">

        <header class="text-center">

            <h1>注册</h1>

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

            <div class="input-group">

                <div class="input-group-addon"><i class="fa fa-key fa-fw"></i></div>

                <label for="re_password"></label><input type="password" id="re_password" class="form-control" placeholder="请再次确认密码">

            </div>

        </div>

        <div class="form-group">

            <a href="{{url('login')}}" class="layui-btn" style="width: 47.5%">登陆</a>

            <button  class="layui-btn layui-btn-danger register" style="width: 47.5%">注册</button>

        </div>

    </div>

    <script>

        layui.use(['layer', 'jquery'], function (layer, $) {

            $('.register').click(function () {

                let username = $('#username').val(),
                    password = $('#password').val(),
                    re_password = $('#re_password').val();

                if (username === '' || $.trim(username).length === 0) {

                    layer.tips('请输入用户名称', '#username', {tips: [3, '#3595cc'], anim: 4});

                    return false;

                }

                if (password === '' || $.trim(password).length === 0) {

                    layer.tips('请输入用户密码', '#password', {tips: [3, '#3595cc'], anim: 4});

                    return false;

                }

                if (re_password === '' || $.trim(re_password).length === 0) {

                    layer.tips('请输入确认密码', '#re_password', {tips: [3, '#3595cc'], anim: 4});

                    return false;

                }

                if (password !== re_password) {

                    layer.tips('两次输入的密码不匹配', '#re_password', {tips: [3, '#3595cc'], anim: 4});

                    return false;

                }

                $.ajaxSetup({

                    headers:{

                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                    }

                });

                $.ajax({

                    type: "PUT",

                    url: "{{url('register')}}",

                    data: {username: username, password: password,re_password:re_password},

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
