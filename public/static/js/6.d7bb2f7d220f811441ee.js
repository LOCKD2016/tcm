webpackJsonp([6],{133:function(t,e,s){s(197);var i=s(11)(s(188),s(213),null,null);t.exports=i.exports},188:function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var i=s(12);window.num=null,window.sec=null,e.default={data:function(){return{checkmobile:"",checkpass:"",checkcode:"",ksdl:0,mobile:"",mob_code:"",nickname:"",password:"",send:!1,sendfast:!1,getcodeBtnText:"获取验证码",getcodeBtn:"获取验证码",agreement:"",agreestatus:0,regTxt:"注册"}},created:function(){this.$store.commit("toggleHeaderStatus",!1),this.$store.commit("toggleFooterStatus",!1),this.getAgreement()},methods:{back:function(){"绑定"==this.regTxt&&this.$http.get(this.$store.state.apiUrl+"logout").then(function(t){}),$(".sign_up").hide(),$(".sign_in").fadeIn()},registerBtn:function(){this.regTxt="注册",$(".sign_in").hide(),$(".sign_up").fadeIn()},bindBtn:function(){this.regTxt="绑定",$(".sign_in").hide(),$(".sign_up").fadeIn()},wxLogin:function(){var t=this;wx.auth(function(e,s){e.status?t.$http.get(t.$store.state.apiUrl+"wxlogin?code="+e.code).then(function(e){e.data.status?(t.$store.state.tcmuser&&api.sendEvent({name:"background",extra:{status:"login"}}),t.getMe()):404==e.data.errcode?t.bindBtn():$api.pop(e.data.msg)}):$api.pop("微信登录失败")})},dodel:function(){$(".sign_up").hide(),$(".sign_tk").fadeIn(),$(".layer_pop").addClass("none")},canceldel:function(){$(".layer_pop").addClass("none"),$(".sign_up").show(),$(".sign_tk").hide()},agree:function(){this.agreestatus=!this.agreestatus},tongyi:function(){this.agreestatus=1,$(".sign_up").show(),$(".sign_tk").hide()},butongyi:function(){this.agreestatus=0,$(".sign_up").show(),$(".sign_tk").hide()},getAgreement:function(){this.$http.get(this.$store.state.apiUrl+"agreement").then(function(t){this.agreement=t.data.data})},getCode:function(t){var e={},a=this;if(1==t){if(a.send)return;if(e.type=1,!a.mobile)return $api.pop("手机号不能为空"),!1;e.mobile=a.mobile,this.$http.post(a.$store.state.apiUrl+"send/sms",e).then(function(t){t.data.status?(a.send=!0,a.countDown()):$api.pop(t.data.msg)},function(t){s.i(i.b)(t.data.data.errors)})}else{if(a.sendfast)return;if(e.type=3,!a.checkmobile)return $api.pop("手机号不能为空"),!1;e.mobile=a.checkmobile,this.$http.post(a.$store.state.apiUrl+"send/sms",e).then(function(t){t.data.status?(a.sendfast=!0,a.count()):$api.pop(t.data.msg)},function(t){s.i(i.b)(t.data.data.errors)})}},login:function(){if(1==this.ksdl){var t={};if(t.mobile=this.checkmobile,t.code=this.checkcode,t.type="login_quick",!this.checkmobile)return $api.pop("手机号不能为空"),!1;if(!this.checkcode)return $api.pop("验证码不能为空"),!1;this.$http.post(this.$store.state.apiUrl+"login",t).then(function(t){1==t.data.status?("111"==t.data.data?$api.pop("快速登陆成功"):$api.pop("快速登陆成功,密码初始为111111"),this.$store.state.tcmuser&&api.sendEvent({name:"background",extra:{status:"login"}}),this.getMe()):$api.pop(t.data.msg)},function(t){s.i(i.b)(t.data.data.errors)})}else{var e={};if(e.mobile=this.checkmobile,e.password=this.checkpass,e.type="login_plain",!this.checkpass)return $api.pop("密码不能为空"),!1;this.$http.post(this.$store.state.apiUrl+"login",e).then(function(t){0==t.data.status?$api.pop(t.data.msg):(this.$store.state.tcmuser&&api.sendEvent({name:"background",extra:{status:"login"}}),this.getMe())},function(t){s.i(i.b)(t.data.data.errors)})}},code_init:function(){clearInterval(num),this.getcodeBtnText="获取验证码",this.send=!1},countDown:function(){var t=60,e=this;num=setInterval(function(){if(0==t)return clearInterval(num),e.getcodeBtnText="重发验证码",void(e.send=!1);e.getcodeBtnText=t+"秒后重发",t--},1e3)},code:function(){clearInterval(sec),this.getcodeBtn="获取验证码",this.sendfast=!1},count:function(){var t=60,e=this;sec=setInterval(function(){if(0==t)return clearInterval(sec),e.getcodeBtn="重发验证码",void(e.sendfast=!1);e.getcodeBtn=t+"秒后重发",t--},1e3)},register:function(){if(!this.mobile)return $api.pop("手机号不能为空"),!1;if(!this.password)return $api.pop("密码不能为空"),!1;if(!this.mob_code)return $api.pop("验证码不能为空"),!1;this.agreestatus||$(".layer_pop").removeClass("none");var t={};t.code=this.mob_code,t.mobile=this.mobile,t.password=this.password,t.type="reg_plain",this.$http.post(this.$store.state.apiUrl+"register",t).then(function(t){1==t.data.status?($api.pop("恭喜您注册成功"),this.mob_code="",this.mobile="",this.password="",this.$store.state.tcmuser&&api.sendEvent({name:"background",extra:{status:"login"}}),this.getMe()):$api.pop(t.data.errors.code[0])},function(t){s.i(i.b)(t.data.data.errors)})},getMe:function(){this.$http({url:this.$store.state.apiUrl+"user/detail",method:"GET"}).then(function(t){t.data.status&&(window.localStorage.setItem("imToken",t.data.data.im_token),window.localStorage.setItem("headimgurl",t.data.data.headimgurl),window.localStorage.setItem("nickname",t.data.data.nickname),window.localStorage.setItem("id",t.data.data.id)),this.$router.replace("/index")})}}}},195:function(t,e,s){e=t.exports=s(94)(),e.push([t.i,"input{width:100%;height:.74rem;display:inline-block}",""])},197:function(t,e,s){var i=s(195);"string"==typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);s(95)("2910125a",i,!0)},213:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{attrs:{id:"wrap"}},[s("div",{staticClass:"sign_in"},[s("div",{staticClass:"logo"}),s("div",{staticClass:"word"}),s("ul",[s("li",[s("i",{staticClass:"icon-h-nav5"}),s("input",{directives:[{name:"model",rawName:"v-model",value:t.checkmobile,expression:"checkmobile"}],attrs:{type:"tel",maxlength:"11",placeholder:"输入手机号"},domProps:{value:t.checkmobile},on:{input:function(e){e.target.composing||(t.checkmobile=e.target.value)}}})]),0==t.ksdl?s("li",[s("i",{staticClass:"icon-lock"}),s("input",{directives:[{name:"model",rawName:"v-model",value:t.checkpass,expression:"checkpass"}],attrs:{type:"password",maxlength:"20",placeholder:"输入密码"},domProps:{value:t.checkpass},on:{input:function(e){e.target.composing||(t.checkpass=e.target.value)}}})]):s("li",[s("i",{staticClass:"icon-h-nav6"}),s("input",{directives:[{name:"model",rawName:"v-model",value:t.checkcode,expression:"checkcode"}],attrs:{type:"tel",maxlength:"6",placeholder:"输入验证码"},domProps:{value:t.checkcode},on:{input:function(e){e.target.composing||(t.checkcode=e.target.value)}}}),s("div",{staticClass:"btn",on:{click:function(e){t.getCode(3)}}},[t._v(t._s(t.getcodeBtn))])])]),s("div",{staticClass:"btn btn-block",on:{click:function(e){t.login()}}},[t._v("登录")]),s("div",{staticClass:"clearfix"},[0==t.ksdl?s("div",{staticClass:"left",on:{click:function(e){t.ksdl=1}}},[t._v(t._s(t.$store.state.tcmuser?"忘记密码":"快速登录"))]):s("div",{staticClass:"left",on:{click:function(e){t.ksdl=0}}},[t._v("登录")]),s("div",{staticClass:"right",on:{click:t.registerBtn}},[t._v("立即注册")])]),t.$store.state.tcmuser&&t.$store.state.wxInstall?s("div",{staticClass:"clearfix",on:{click:t.wxLogin}},[t._m(0)]):t._e()]),s("div",{staticClass:"sign_up none"},[s("header",[s("div",{staticClass:"left",on:{click:t.back}},[s("i",{staticClass:"icon-arrow-left"})]),s("div",{staticClass:"center"},[t._v(t._s(t.regTxt))])]),s("ul",{staticClass:"list-group"},[s("li",[s("input",{directives:[{name:"model",rawName:"v-model",value:t.mobile,expression:"mobile"}],attrs:{type:"tel",maxlength:"11",placeholder:"请输入手机号"},domProps:{value:t.mobile},on:{input:function(e){e.target.composing||(t.mobile=e.target.value)}}})]),s("li",[s("input",{directives:[{name:"model",rawName:"v-model",value:t.mob_code,expression:"mob_code"}],attrs:{type:"tel",maxlength:"6",placeholder:"请输入验证码"},domProps:{value:t.mob_code},on:{input:function(e){e.target.composing||(t.mob_code=e.target.value)}}}),s("div",{staticClass:"btn",on:{click:function(e){t.getCode(1)}}},[t._v(t._s(t.getcodeBtnText))])]),s("li",[s("input",{directives:[{name:"model",rawName:"v-model",value:t.password,expression:"password"}],attrs:{type:"password",maxlength:"11",placeholder:"请设置六位以上密码"},domProps:{value:t.password},on:{input:function(e){e.target.composing||(t.password=e.target.value)}}})])]),s("div",{staticClass:"span"},[s("div",{staticClass:"inp"},[s("div",{staticClass:"icon-check",class:t.agreestatus?"active":"",on:{click:t.agree}})]),s("span",{attrs:{onclick:"$('.sign_up').hide();$('.sign_tk').fadeIn()"}},[t._v(t._s(t.regTxt)+"即代表同意《泰和国医协议》")])]),s("div",{staticClass:"btn btn-block",on:{click:function(e){t.register()}}},[t._v(t._s(t.regTxt))])]),s("div",{staticClass:"sign_tk none"},[s("header",[t._m(1),s("div",{staticClass:"center"},[t._v(t._s(t.regTxt)+"条款")])]),s("p",{staticClass:"xieyi",domProps:{innerHTML:t._s(t.agreement.value)}}),s("div",{staticClass:"agreexieyi",on:{click:t.tongyi}},[t._v("同意")]),s("div",{staticClass:"notagree",on:{click:t.butongyi}},[t._v("不同意")]),s("p")]),s("div",{staticClass:"layer_pop none"},[s("div",{staticClass:"content"},[s("div",{staticClass:"txt"},[t._v("请查看"+t._s(t.regTxt)+"协议")]),s("div",{staticClass:"pop_btn clearfix"},[s("div",{staticClass:"p_btn l",on:{click:t.dodel}},[t._v("确定")]),s("div",{staticClass:"p_btn",on:{click:t.canceldel}},[t._v("取消")])])])])])},staticRenderFns:[function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("p",{staticClass:"wxlong"},[s("img",{attrs:{src:"static/img/wechaticon.png"}})])},function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"left",attrs:{onclick:"$('.sign_tk').hide();$('.sign_up').fadeIn()"}},[s("i",{staticClass:"icon-arrow-left"})])}]}}});