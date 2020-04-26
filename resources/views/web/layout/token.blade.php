<script src="{{asset('static/layui/layui.js')}}"></script>

<script>

    if (!window.localStorage.getItem('user_token')) {

        window.location.href = '/login';

    }

</script>
