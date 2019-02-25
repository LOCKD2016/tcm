<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/js/layui/css/layui.css">
    <link rel="stylesheet" type="text/css" href="/css/index.css?v14">
    <link rel="stylesheet" type="text/css" href="/css/style.min.css?v14">
    <meta name="format-detection" content="telephone=no">
    <title>泰和国医管理平台</title>

    <script type="text/javascript">
        @if(Auth::user())
            const User = {!! $user->toJson(JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!};
            const Menu = {!! json_encode($nav,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!};
        @else
            const User = {isAdmin: false};
        @endif
    </script>
</head>
<body>


<script type="text/javascript" src="{{'//cdn.bootcss.com/jquery/3.1.1/jquery.min.js'}}"></script>
<script type="text/javascript" src="{{'/js/layer/layer.js'}}"></script>
<script type="text/javascript" src="{{'/js/jquery-ui.min.js?v14'}}"></script>
<script type="text/javascript" src="{{'/js/bootstrap.min.js'}}"></script>
<script type="text/javascript" src="{{'/js/echarts.min.js?v14'}}"></script>
<script type="text/javascript" src="{{'/js/load.js?v14'}}"></script>
<script type="text/javascript" src="{{'/js/layui/layui.js'}}"></script>
<script type="text/javascript" src="{{'/js/jstree.min.js?v14'}}"></script>
<script type="text/javascript" src="{{'/js/ichart.1.2.min.js'}}"></script>
<script type="text/javascript" src="{{'/js/area.js'}}"></script>
<script type="text/javascript" src="{{'/js/UEditor/ueditor.config.js'}}"></script>
<!-- 编辑器源码文件 -->
<script type="text/javascript" src="{{'/js/UEditor/ueditor.all.js'}}"></script>
<script src="{{'/js/main.js'}}"></script>
<!-- Live Reload -->
@if ( Config::get('app.env') == 'local' )
    <script type="text/javascript">
        document.write('<script src="//127.0.0.1:35729/livereload.js?snipver=1" type="text/javascript"><\/script>')
    </script>
@endif
</body>
</html>