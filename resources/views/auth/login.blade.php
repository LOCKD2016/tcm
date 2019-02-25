
@extends('layouts.app')
@section('content')
<form role="form" method="POST" action="{{ url('/login') }}" onsubmit="login(this);return false;" class="form-horizontal">{{ csrf_field() }}
  <div class="logo"></div>
  <div class="msg">
    <div class="form-group">
      <label for="user_name" class="control-label icon-user"></label>
      <div class="col-md-8">
        <input id="user_name" type="text" placeholder="用户名" required="" name="user_name" value="{{ old('user_name') }}" autofocus="" class="form-control"/>
      </div>
    </div>
    <div class="form-group">
      <label for="password" class="control-label icon-lock"></label>
      <div class="col-md-8">
        <input id="password" type="password" required="" name="password" placeholder="请输入密码" class="form-control"/>
      </div>
    </div>
  </div>
  <div class="verify">
    <input type="text" placeholder="请输入验证码" required="" name="captcha" maxlength="4" class="form-control"/>
    <div class="get_verify"><img id="verify" src="{{captcha_src('k2vc')}}"/></div>
  </div>
  <button type="submit" class="login_btn">登录</button>
  <!--.form-group-->
  <!--    .col-md-6.col-md-offset-4-->
  <!--        .checkbox-->
  <!--            label-->
  <!--                input(type='checkbox', name='remember')-->
  <!--                |  记住我-->
  <script>
    function login(obj){
        var data = $(obj).serialize();
        var url = $(obj).attr("action");
        $.ajax({
            url:url,
            type:'post',
            data:data,
            dataType:'json',
            success:function(data){
                if(data.status){
                    location.href=data.url;
                }else{
                    layer.msg(data.msg);
                    $("#verify").click();
                }
            },
            error:function(data){
                var json = JSON.parse(data.responseText);
                for(var d in json){
                    var msg = json[d][0];
                    layer.msg(msg);
                    break;
                }
                $("#verify").click();
            }
        });
    }
    window.onload =function(){
        function getcookie(name, nounescape) {
            var cookie_start = document.cookie.indexOf(name);
            var cookie_end = document.cookie.indexOf(";", cookie_start);
            if (cookie_start == -1) {
                return '';
            } else {
                var v = document.cookie.substring(cookie_start + name.length + 1, (cookie_end > cookie_start ? cookie_end : document.cookie.length));
                return !nounescape ? unescape(v) : v;
            }
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': getcookie("XSRF-TOKEN")
            }
        });
        $("#verify").click(function(){
            var src = $(this).attr("src").split("?")[0];
            $(this).attr('src',src+"?time="+new Date().getTime())
        });
    }
  </script>
</form>@endsection