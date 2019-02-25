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
        {!! $qrcode !!}
    </div>
</div>
</body>
<script src="/js/app_admin.js"></script>
<script type="text/javascript">
    window.onload=function(){
        function inquiry() {
            $.ajax({
                url:'/inquiry',
                type:'post',
                data:{code:"{{$code}}"},
                dataType:'json',
                headers: {
                    'X-CSRF-TOKEN': "{{csrf_token()}}"
                },
                success:function(data){
                    if(data.status){
                        clearInterval(flag);
                        var inquiry_cookie = Math.floor(Math.random()*10000);
                        document.cookie="{{$code}}={{$code}}";
                        location.href='/inquiry/{{$code}}';
                    }
                },
            });
        }

        var flag = setInterval(inquiry,2000);

    }
</script>
</html>
