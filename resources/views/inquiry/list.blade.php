<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>问诊单编辑</title>

    <!-- Fonts -->

    <!-- Styles -->
</head>
<body>
<div class="flex-center position-ref full-height">

    <div class="content">
        <li onclick="routeGo(1)">成人男</li>
        <li onclick="routeGo(2)">成人女</li>
        <li onclick="routeGo(3)">儿童</li>
    </div>
</div>
</body>
<script src="/js/app_admin.js"></script>
<script type="text/javascript">
    var url = location.href;
    function routeGo(type) {
        location.href=url + '/type/'+ type;
    }
</script>
</html>
