<template lang='jade'>
#wrap
    .sign_in
        .logo
        .word
        ul
            li
                i.icon-h-nav5
                input(type="tel" maxlength="11" placeholder="输入手机号" v-model="checkmobile")
            li(v-if="ksdl==0")
                i.icon-lock
                input(type="password" maxlength="20" placeholder="输入密码" v-model="checkpass")
            li(v-else)
                i.icon-h-nav6
                input(type="tel" maxlength="6" placeholder="输入验证码" v-model="checkcode")
                .btn(@click="getCode(3)") {{getcodeBtn}}

         .btn.btn-block(@click="login()") 登录
        .clearfix
            .left(v-if="ksdl==0" @click="ksdl=1") {{$store.state.tcmuser?'忘记密码':'快速登录'}}
            .left(v-else @click="ksdl=0") 登录
            .right(@click="registerBtn") 立即注册
        .clearfix(v-if="$store.state.tcmuser && $store.state.wxInstall" @click="wxLogin")
            p.wxlong
              img(src="static/img/wechaticon.png")
    .sign_up.none
        header
            .left(@click="back")
                i.icon-arrow-left
            .center {{regTxt}}
        ul.list-group
            li
                input(type="tel" maxlength="11" placeholder="请输入手机号" v-model="mobile")
            li
                input(type="tel" maxlength="6" placeholder="请输入验证码" v-model="mob_code")
                .btn(@click="getCode(1)") {{getcodeBtnText}}
            li
                input(type="password" maxlength="11" placeholder="请设置六位以上密码" v-model="password")

        .span
            .inp
                .icon-check(:class='agreestatus?"active":""' @click='agree')
            span(onclick="$('.sign_up').hide();$('.sign_tk').fadeIn()") {{regTxt}}即代表同意《泰和国医协议》

        .btn.btn-block(@click="register()") {{regTxt}}

    .sign_tk.none
        header
            .left(onclick="$('.sign_tk').hide();$('.sign_up').fadeIn()")
                i.icon-arrow-left
            .center {{regTxt}}条款
        p.xieyi(v-html='agreement.value')
            .agreexieyi(@click='tongyi') 同意
            .notagree(@click='butongyi') 不同意
    .layer_pop.none
      .content
          .txt 请查看{{regTxt}}协议
          .pop_btn.clearfix
              .p_btn.l(@click="dodel") 确定
              .p_btn(@click="canceldel") 取消
</template>

<script>
    window.num=null;
    window.sec=null;
    import {errorMsg} from '../../vuex/store';
    export default {
        data(){
            return  {
                checkmobile: '',
                checkpass: '',
                checkcode: '',
                ksdl: 0,//快速登陆标记1是0否,
                mobile: '',
                mob_code: '',
                nickname: '',
                password: '',
                send: false,
                sendfast: false,
                getcodeBtnText:'获取验证码',
                getcodeBtn:'获取验证码',
                agreement: '',
                agreestatus:0,
                regTxt:'注册',
            };
        },
        created(){
            this.$store.commit("toggleHeaderStatus", false);
            this.$store.commit("toggleFooterStatus", false);
            this.getAgreement()
        },
        methods:{
            back(){
                if(this.regTxt == '绑定'){
                    this.$http.get(this.$store.state.apiUrl+'logout').then(function (res){
                    })
                }
                $('.sign_up').hide();$('.sign_in').fadeIn()
            },
            registerBtn(){
                this.regTxt = '注册';
                $('.sign_in').hide();$('.sign_up').fadeIn()
            },
            bindBtn(){
                this.regTxt = '绑定';
                $('.sign_in').hide();$('.sign_up').fadeIn()
            },
            wxLogin(){
                var self = this;
                wx.auth(function(ret, err) {
                    if (ret.status) {
                        self.$http.get(self.$store.state.apiUrl+'wxlogin?code='+ret.code).then(function (res){
                            if(res.data.status){
                                if(self.$store.state.tcmuser){
                                    api.sendEvent({name:'background',extra: {
                                        status:'login'
                                    }});
                                }
                                //self.$router.replace('/index');
                                self.getMe();
                                /*if(self.$route.query.from){
                                    self.$router.push({path: self.$route.query.from || '/index'});
                                }else{
                                    self.$router.replace('/index');
                                }*/
                            }else{
                                if(res.data.errcode == 404){
                                    self.bindBtn();
                                }else{
                                    $api.pop(res.data.msg);
                                }
                            }
                        })
                    } else {
                        $api.pop('微信登录失败');
                    }
                });
            },
            //确认与取消事件
            dodel(){

                $('.sign_up').hide()

                $('.sign_tk').fadeIn()

                $('.layer_pop').addClass('none');

            },

            canceldel(){

                $('.layer_pop').addClass('none');

                $('.sign_up').show()

                $('.sign_tk').hide()

            },

            //注册协议按钮

            agree(){

                this.agreestatus = !this.agreestatus

            },

            //同意协议按钮

            tongyi(){

                this.agreestatus = 1

                $('.sign_up').show()

                $('.sign_tk').hide()

            },

            //不同意协议按钮

            butongyi(){

                this.agreestatus = 0

                $('.sign_up').show()

                $('.sign_tk').hide()

            },

            getAgreement(){
                this.$http.get(this.$store.state.apiUrl+'agreement').then(function (res){
                    this.agreement = res.data.data;
                })
            },
            getCode(val){
                var obj = {};
                var _this = this;
                if(val==1){
                    if(_this.send) return;
                    obj.type = 1;
                    if(!_this.mobile){
                        $api.pop('手机号不能为空');return false;
                    }
                    obj.mobile = _this.mobile;
                    this.$http.post(_this.$store.state.apiUrl+'send/sms',obj).then(function (res){
                        if(res.data.status){
                            _this.send = true;
                            _this.countDown();
                        }else{
                            $api.pop(res.data.msg);
                        }
                    },function (response) {
                        errorMsg(response.data.data.errors);
                    })
                }else{
                    if(_this.sendfast) return;
                    obj.type = 3;
                    if(!_this.checkmobile){
                        $api.pop('手机号不能为空');return false;
                    }
                    obj.mobile = _this.checkmobile;
                    this.$http.post(_this.$store.state.apiUrl+'send/sms',obj).then(function (res){
                        if(res.data.status){
                            _this.sendfast = true;
                            _this.count();
                        }else{
                            $api.pop(res.data.msg);
                        }
                    },function (response) {
                        errorMsg(response.data.data.errors);
                    })
                }

            },
            login(){
                //登陆
                if(this.ksdl==1){
                    //快速登陆
                    var fastLogin = {};
                    fastLogin.mobile = this.checkmobile;
                    fastLogin.code = this.checkcode;
                    fastLogin.type = 'login_quick';
                    if(!this.checkmobile){
                        $api.pop('手机号不能为空');return false;
                    }
                    if(!this.checkcode){
                        $api.pop('验证码不能为空');return false;
                    }
                    this.$http.post(this.$store.state.apiUrl+'login',fastLogin).then(function (res){
                        if(res.data.status==1){
                            if(res.data.data=='111'){
                                $api.pop('快速登陆成功');
                            }else{
                                $api.pop('快速登陆成功,密码初始为111111');
                            }
                            if(this.$store.state.tcmuser){
                                api.sendEvent({name:'background',extra: {
                                    status:'login'
                                }});
                            }
                            //this.$router.replace('/index');
                            this.getMe();
                            /*if(this.$route.query.from){
                                this.$router.push({path: this.$route.query.from || '/index'});
                            }else{
                                this.$router.replace('/index');
                            }*/
                        }else{
                            $api.pop(res.data.msg);
                        }
                    },function (response) {
                        errorMsg(response.data.data.errors);
                    })
                }else{
                    var login = {};
                    login.mobile = this.checkmobile;
                    login.password = this.checkpass;
                    login.type = 'login_plain';
                    if(!this.checkpass){
                        $api.pop('密码不能为空');
                        return false;
                    }
                    this.$http.post(this.$store.state.apiUrl+'login',login).then(function (res){
                        if(res.data.status == 0){
                            $api.pop(res.data.msg);
                        }else{
                            if(this.$store.state.tcmuser){
                                api.sendEvent({name:'background',extra: {
                                    status:'login'
                                }});
                            }
                            //this.$router.replace('/index');
                            this.getMe();
                            /*if(this.$route.query.from){
                                this.$router.push({path: this.$route.query.from || '/index'});
                            }else{
                                this.$router.replace('/index');
                            }*/
                        }
                    }, function(response) {
                        errorMsg(response.data.data.errors);
                    })
                }

            },//初始化发送验证码的定时器和倒计时状态.
            code_init(){
                clearInterval(num);
                this.getcodeBtnText= "获取验证码";
                this.send = false;
            },
            countDown(){
                var t= 60;
                var self = this;
                num= setInterval(function(){
                    if(t==0) {
                        clearInterval(num);
                        self.getcodeBtnText = "重发验证码";
                        self.send = false;
                        return;
                    }
                    self.getcodeBtnText = t+"秒后重发";
                    t--;
                },1000);
            },
            code(){
                clearInterval(sec);
                this.getcodeBtn= "获取验证码";
                this.sendfast = false;
            },
            count(){
                var t= 60;
                var self = this;
                sec= setInterval(function(){
                    if(t==0) {
                        clearInterval(sec);
                        self.getcodeBtn = "重发验证码";
                        self.sendfast = false;
                        return;
                    }
                    self.getcodeBtn = t+"秒后重发";
                    t--;
                },1000);
            },
            //注册
            register(){

                if(!this.mobile){
                    $api.pop('手机号不能为空');return false;
                }
                if(!this.password){
                    $api.pop('密码不能为空');return false;
                }
                if(!this.mob_code){
                    $api.pop('验证码不能为空');return false;
                }

                if(!this.agreestatus){

                    // $api.pop('请同意注册协议');return false;

                    $('.layer_pop').removeClass('none');

                }

                var regist = {};
                regist.code = this.mob_code;
                regist.mobile = this.mobile;
                regist.password = this.password;
                regist.type = 'reg_plain';
                this.$http.post(this.$store.state.apiUrl+'register',regist).then(function (res){
                    if(res.data.status==1){
                        $api.pop('恭喜您注册成功');
                        this.mob_code = '';
                        this.mobile = '';
                        this.password = '';
                        if(this.$store.state.tcmuser){
                            api.sendEvent({name:'background',extra: {
                                status:'login'
                            }});
                        }
                        //this.$router.replace('/index');
                        this.getMe();
                        /*if(this.$route.query.from){
                            this.$router.push({path: this.$route.query.from || '/index'});
                        }else{
                            this.$router.replace('/index');
                        }*/
                    }else{
                        $api.pop(res.data.errors.code[0])
                    }
                },function (response) {
                    errorMsg(response.data.data.errors);
                })
            },
            getMe() {
                this.$http({url:this.$store.state.apiUrl+'user/detail', method:'GET'}).then(function(res){
                    if(res.data.status){
                        window.localStorage.setItem("imToken",res.data.data.im_token);
                        window.localStorage.setItem("headimgurl",res.data.data.headimgurl);
                        window.localStorage.setItem("nickname",res.data.data.nickname);
                        window.localStorage.setItem("id",res.data.data.id);
                    }
                    this.$router.replace('/index');
                })
            },
        }
    }
</script>
<style>
    input{
        width: 100%;
        height: .74rem;
        display: inline-block;
    }
</style>
