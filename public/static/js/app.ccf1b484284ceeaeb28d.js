webpackJsonp([51],{12:function(n,t,e){"use strict";function a(n){for(var t in n){var e=n[t][0];$api.pop(e);break}}t.b=a;var o=e(3),i=e.n(o),c=e(93),u=e(59),r=e(56),l=e.n(r),s=e(57);i.a.use(c.a);var p=window.location.host;p="localhost"==location.hostname?"http://tcm.dev/":"/";var h=window.navigator.userAgent.toLowerCase();"tcmuser"==h.match(/TCMUser/i)&&(p="https://app.taiheguoyi.com/");var f={headerStatus:!0,footerStatus:!0,currentLang:"zh",newMsgCount:0,currentPageName:"泰和国医",tipsStatus:!1,apiUrl:p+"api/weixin/",imgUrl:p,wxready:!1,tcmuser:!1,wxInstall:!1,apiready:!1,searchDate:searchDate,translate:0,includePage:[]};"tcmuser"==h.match(/TCMUser/i)&&(f.tcmuser=!0),t.a=new c.a.Store({state:f,mutations:u.a,actions:l.a,getters:s.a})},25:function(n,t,e){"use strict";var a=e(60),o=e.n(a),i={fmtDate:function(n,t){var n=new Date(n),e={"M+":n.getMonth()+1,"d+":n.getDate(),"h+":n.getHours(),"m+":n.getMinutes(),"s+":n.getSeconds(),"q+":Math.floor((n.getMonth()+3)/3),S:n.getMilliseconds()};/(y+)/.test(t)&&(t=t.replace(RegExp.$1,(n.getFullYear()+"").substr(4-RegExp.$1.length)));for(var a in e)new RegExp("("+a+")").test(t)&&(t=t.replace(RegExp.$1,1==RegExp.$1.length?e[a]:("00"+e[a]).substr((""+e[a]).length)));return t}};t.a=function(n){o()(i).forEach(function(t){n.filter(t,i[t])})}},26:function(n,t,e){"use strict";var a=e(3),o=e.n(a),i=e(91);o.a.use(i.a);var c=[{path:"/",name:"首页",redirect:"/index"},{path:"/index",name:"泰和国医",component:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)}},{path:"/sign",name:"登录注册",component:function(n){return e.e(6).then(function(){var t=[e(134)];n.apply(null,t)}.bind(this)).catch(e.oe)}},{path:"/doctor/type/:type",name:"doctorlist",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(7).then(function(){var t=[e(105)];n.apply(null,t)}.bind(this)).catch(e.oe)}},meta:{keepAlive:!0}},{path:"/doctor_aLine",name:"doctoraLine",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(40).then(function(){var t=[e(106)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/doctor_illness",name:"全部病情",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(38).then(function(){var t=[e(108)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/doctor/outline",name:"门诊预约",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(37).then(function(){var t=[e(110)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/confirmOrder",name:"确认订单",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(45).then(function(){var t=[e(99)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/doctor/preOnline",name:"在线咨询",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(36).then(function(){var t=[e(111)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/doctor/online",name:"在线咨询",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(4).then(function(){var t=[e(109)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/doctor_detail",name:"医生详情",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(39).then(function(){var t=[e(107)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/doctor/allComment",name:"患者评价",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(35).then(function(){var t=[e(112)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/goods/:id",name:"健康商城",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(32).then(function(){var t=[e(115)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/search",name:"搜索历史",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(18).then(function(){var t=[e(131)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/search_result",name:"搜索结果",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(17).then(function(){var t=[e(132)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/order",name:"我的预约",component:function(n){return e.e(2).then(function(){var t=[e(13)];n.apply(null,t)}.bind(this)).catch(e.oe)}},{path:"/order_details",name:"我的预约详情",components:{default:function(n){return e.e(2).then(function(){var t=[e(13)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(24).then(function(){var t=[e(124)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/myorder_details",name:"我的订单详情",components:{default:function(n){return e.e(2).then(function(){var t=[e(13)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(9).then(function(){var t=[e(123)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/my",name:"个人中心",component:function(n){return e.e(1).then(function(){var t=[e(2)];n.apply(null,t)}.bind(this)).catch(e.oe)}},{path:"/my_order/my",name:"我的订单",components:{default:function(n){return e.e(1).then(function(){var t=[e(2)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(3).then(function(){var t=[e(35)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/my_order_det/my/id",name:"订单详情2",components:{default:function(n){return e.e(1).then(function(){var t=[e(2)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(25).then(function(){var t=[e(122)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/prescription/my/id",name:"我的药方",components:{default:function(n){return e.e(1).then(function(){var t=[e(2)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(20).then(function(){var t=[e(129)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/payment/recipe",name:"药方确认订单",components:{subPage:function(n){return e.e(19).then(function(){var t=[e(130)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/my_fmld/my",name:"个人资料",components:{default:function(n){return e.e(1).then(function(){var t=[e(2)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(26).then(function(){var t=[e(121)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/my_address/my/",name:"收货地址列表",components:{default:function(n){return e.e(1).then(function(){var t=[e(2)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(29).then(function(){var t=[e(118)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/my_address/my/:type/id",name:"收货地址详情",components:{default:function(n){return e.e(1).then(function(){var t=[e(2)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(28).then(function(){var t=[e(119)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/chat",name:"聊天",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(47).then(function(){var t=[e(97)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/questionnaire/:id",name:"填写问诊单",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(13).then(function(){var t=[e(139)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/wzd_fill",name:"填空题",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(11).then(function(){var t=[e(141)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/wzd_radio",name:"单选题",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(10).then(function(){var t=[e(142)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/wzd_check",name:"多选题",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(12).then(function(){var t=[e(140)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/point",name:"穴位列表",components:{default:function(n){return e.e(3).then(function(){var t=[e(35)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(22).then(function(){var t=[e(127)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/tfbd",name:"贴敷必读",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(49).then(function(){var t=[e(136)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/tfbd_new",name:"个性化贴敷必读",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(48).then(function(){var t=[e(137)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/point_img",name:"穴位图片",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(21).then(function(){var t=[e(128)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/payment",name:"选择支付方式",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(23).then(function(){var t=[e(126)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/map",name:"地图",components:{subPage:function(n){return e.e(30).then(function(){var t=[e(117)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/tickling",name:"疗效反馈",components:{subPage:function(n){return e.e(14).then(function(){var t=[e(138)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/exam",name:"个性化问诊单",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(34).then(function(){var t=[e(113)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/standard",name:"查看标准问诊单",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(15).then(function(){var t=[e(135)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/logistics",name:"物流信息",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(31).then(function(){var t=[e(116)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/pay_sucess",name:"支付完成",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(8).then(function(){var t=[e(125)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/data/:titType/:timeType",name:"健康数据",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(5).then(function(){var t=[e(101)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/data_lr",name:"健康数据录入",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(42).then(function(){var t=[e(103)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/data_cl",name:"测量结果",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(43).then(function(){var t=[e(102)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/data_qh/:titType/:timeType",name:"图标切换",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(41).then(function(){var t=[e(104)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/my_doctor",name:"我的医生",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(27).then(function(){var t=[e(120)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/my_clinic_list/my",name:"待评价",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(46).then(function(){var t=[e(98)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/my_contact/my",name:"联系人",components:{default:function(n){return e.e(0).then(function(){var t=[e(0)];n.apply(null,t)}.bind(this)).catch(e.oe)},subPage:function(n){return e.e(44).then(function(){var t=[e(100)];n.apply(null,t)}.bind(this)).catch(e.oe)}}},{path:"/pdfone",name:"pdfone",component:function(n){return e.e(16).then(function(){var t=[e(133)];n.apply(null,t)}.bind(this)).catch(e.oe)}},{path:"/express/:id",name:"express",component:function(n){return e.e(33).then(function(){var t=[e(114)];n.apply(null,t)}.bind(this)).catch(e.oe)}}],u=window.navigator.userAgent.toLowerCase(),r=new i.a({mode:"tcmuser"==u.match(/TCMUser/i)?"hash":"history",base:"/wechat",linkActiveClass:"active",routes:c});t.a=r},27:function(n,t){},30:function(n,t,e){var a=e(11)(e(53),e(89),null,null);n.exports=a.exports},53:function(n,t,e){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var a=e(32),o=e.n(a),i=e(88),c=e.n(i),u=e(58);window.mixin=u.a,window.protocol=location.protocol,window.shareConfig=function(n){n||(n="/wechat/payment/2"),console.log(n),wx.onMenuShareTimeline({title:"泰和国医欢迎您！",link:protocol+window.location.host+n,imgUrl:protocol+window.location.host+"/static/img/wxlogo.png",success:function(){},cancel:function(){},fail:function(n){alert(o()(n))}}),wx.onMenuShareAppMessage({title:"泰和国医欢迎您！",desc:"泰和国医",link:protocol+window.location.host+n,imgUrl:protocol+window.location.host+"/static/img/wxlogo.png",success:function(){},cancel:function(){},fail:function(n){alert(o()(n))}})},window.setWxConfig=function(n){var t="/api/weixin/wxconfig";"tcm.vmh5.com"==location.host&&(t="//auth.vliang.com/wxconfig"),$.ajax({url:t,headers:{Accept:"application/x.daguoyi.wxv1+lbbJson"},contentType:"application/json;charset=UTF-8",dataType:"json",crossDomain:!0,success:function(e){var a=null;a="//auth.vliang.com/wxconfig"==t?e:e.data,wx.config({debug:!1,appId:a.appId,timestamp:a.timestamp,nonceStr:a.nonceStr,signature:a.signature,jsApiList:["checkJsApi","onMenuShareTimeline","onMenuShareAppMessage","onMenuShareQQ","onMenuShareWeibo","onMenuShareQZone","hideMenuItems","showMenuItems","hideAllNonBaseMenuItem","showAllNonBaseMenuItem","translateVoice","startRecord","stopRecord","onVoiceRecordEnd","playVoice","onVoicePlayEnd","pauseVoice","stopVoice","uploadVoice","downloadVoice","chooseImage","previewImage","uploadImage","downloadImage","getNetworkType","openLocation","getLocation","hideOptionMenu","showOptionMenu","closeWindow","scanQRCode"]}),"function"==typeof n&&n()}})},t.default={name:"app",components:{foot:c.a},data:function(){return{wxconfig:!0,pageName:"",routerAnimate:!1,enterAnimate:"",leaveAnimate:"",includePage:this.$store.state.includePage}},mounted:function(){this.getQm()},watch:{$route:function(n,t){var e=n.path.split("/").length;t.path.split("/").length;"/"==n.path||"/index"==n.path?$("body").attr("class","p_home"):$("body").attr("class","p_"+n.path.split("/")[1]),2===e&&this.$store.commit("setPageName",n.name)}},methods:{getQm:function(){if(!this.$store.state.tcmuser){var n=this;setWxConfig(function(){wx.ready(function(){n.$store.commit("setWxReady",!0),setTimeout(function(){},2e3)})}),wx.error(function(t){n.getQm()})}}}}},54:function(n,t,e){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={methods:{jk:function(){$api.pop("更多精彩，敬请期待！")}}}},55:function(n,t,e){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var a=e(3),o=e.n(a),i=e(31),c=e(24),u=e.n(c),r=e(29),l=e.n(r),s=e(30),p=e.n(s),h=e(26),f=e(12),d=e(28),m=e.n(d),b=e(25),y=e(27);e.n(y);o.a.use(i.a,l.a,u.a,o.a),e.i(b.a)(o.a),o.a.http.headers.common.Accept="application/x.daguoyi.wxv1+lbbJson",o.a.http.headers.common["Content-Type"]="application/json;charset=UTF-8",o.a.config.productionTip=!1,o.a.http.interceptors.push(function(n,t){t(function(n){return 500==n.status&&"Unauthenticated."==n.data.msg?(TCM.$store.state.tcmuser&&api.sendEvent({name:"background",extra:{status:"logout"}}),$api.pop("请先登录"),TCM.$router.push({path:"/sign",query:{from:TCM.$router.currentRoute.path}})):n})}),m.a.attach(document.body),window.TCM=new o.a({el:"#app",router:h.a,store:f.a,render:function(n){return n(p.a)}}),window.AppUpload=function(n,t){api.actionSheet({title:"选择照片",cancelTitle:"取消",buttons:["相册","照相机"]},function(e,a){var o=e.buttonIndex;console.log(o);var i="";if(1==o)i="album";else{if(2!=o)return;i="camera"}api.getPicture({sourceType:i,encodingType:"jpg",mediaValue:"pic",destinationType:"url",quality:60,saveToPhotoAlbum:!1},function(e,a){e&&($("body").addClass("loading"),void 0===t&&(t="upload/image"),lbb.ajax(t,"POST",{files:{image:e.data}},function(t,e){$("body").removeClass("loading"),"function"==typeof n&&n(t?t:null)}))})})},window.app={},window.app.chooseWXPay=function(n,t){api.require("wxPay").payOrder({apiKey:n.appid,orderId:n.prepayid,mchId:n.partnerid,nonceStr:n.noncestr,timeStamp:n.timestamp,package:n.package,sign:n.sign},function(n,e){"function"==typeof t&&t(n,e)})}},56:function(n,t){},57:function(n,t,e){"use strict";var a={contactsInitialList:function(n){for(var t=[],e=n.allContacts,a=e.length,o=0;o<a;o++)-1==t.indexOf(e[o].initial.toUpperCase())&&t.push(e[o].initial.toUpperCase());return t.sort()},contactsList:function(n,t){for(var e={},a=n.allContacts,o=a.length,i=0;i<t.contactsInitialList.length;i++){var c=t.contactsInitialList[i];e[c]=[];for(var u=0;u<o;u++)a[u].initial.toUpperCase()===c&&e[c].push(a[u])}return e}};t.a=a},58:function(n,t,e){"use strict";var a={mounted:function(){this.$store.commit("setPageName",this.pageName)},activated:function(){this.$store.commit("setPageName",this.pageName)}};t.a=a},59:function(n,t,e){"use strict";var a={toggleHeaderStatus:function(n,t){n.headerStatus=t},toggleFooterStatus:function(n,t){n.footerStatus=t},setPageName:function(n,t){n.currentPageName=t},setWxReady:function(n,t){n.wxready=t},setWxInstall:function(n,t){n.wxInstall=t,n.apiready=!0},setTranslate:function(n,t){n.translate=t},setincludePage:function(n,t){t?n.includePage.push(t):n.includePage.splice(0)}};t.a=a},88:function(n,t,e){var a=e(11)(e(54),e(90),null,null);n.exports=a.exports},89:function(n,t){n.exports={render:function(){var n=this,t=n.$createElement,e=n._self._c||t;return e("div",{attrs:{id:"app"}},[e("div",{staticClass:"outter",class:{hideLeft:n.$route.path.split("/").length>2}},[n.$store.state.footerStatus?e("footer",[e("foot")],1):n._e(),e("router-view",{attrs:{name:"default"}})],1),e("transition",{attrs:{name:"custom-classes-transition","enter-active-class":n.enterAnimate,"leave-active-class":n.leaveAnimate}}),e("keep-alive",{attrs:{include:n.includePage}},[e("router-view",{staticClass:"sub-page",attrs:{name:"subPage"}})],1)],1)},staticRenderFns:[]}},90:function(n,t){n.exports={render:function(){var n=this,t=n.$createElement,e=n._self._c||t;return e("ul",{staticClass:"nav nav-foot"},[e("router-link",{attrs:{to:"/index",tag:"li"}},[e("a",[e("i",{staticClass:"index"}),e("p",[n._v("首页")])])]),n.$store.state.tcmuser?e("router-link",{attrs:{to:"/order",tag:"li"}},[e("a",[e("i",{staticClass:"my"})]),e("p",[n._v("我的预约")])]):e("li",{on:{click:n.jk}},[e("a",[e("i",{staticClass:"health"}),e("p",[n._v("健康商城")])])]),e("router-link",{attrs:{to:"/my",tag:"li"}},[e("a",[e("i",{staticClass:"mine"}),e("p",[n._v("我")])])])],1)},staticRenderFns:[]}},94:function(n,t){}},[55]);