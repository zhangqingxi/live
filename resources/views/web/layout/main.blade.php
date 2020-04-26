<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{csrf_token()}}">

    <title>聊天室</title>

    <link href="{{asset('static/css/font-awesome.min.css')}}" rel="stylesheet">

    <link href="{{asset('static/css/bootstrap.min.css')}}" rel="stylesheet">

    <link href="{{asset('static/css/templatemo-style.css')}}" rel="stylesheet">

    <link href="{{asset('static/layui/css/layui.css')}}" rel="stylesheet">

    <script src="{{asset('static/layui/layui.js')}}"></script>

    <style>
        .layim-send-set .layui-edge{
            top: 14px !important;
        }
    </style>
</head>

<body class="light-gray-bg" style="height: 96%">

@yield('content')

</body>


</html>
