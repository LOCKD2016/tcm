webpackJsonp([39],{106:function(t,s,i){var a=i(11)(i(157),i(208),null,null);t.exports=a.exports},157:function(t,s,i){"use strict";Object.defineProperty(s,"__esModule",{value:!0});var a=i(31),e=i.n(a);s.default={data:function(){return{wj:[],hs:[],dashboard:[],doctor:{},comment_num:0,qcode:"",store:"",comment:[],diseases:{},sections:{},clinique:{},schedules:{}}},created:function(){this.id=this.$route.query.id,this.type=this.$route.query.type,this.clinicId=this.$route.query.clinicId?this.$route.query.clinicId:2,this.subscribe_id=this.$route.query.subscribe_id,this.subscribe_type=this.$route.query.subscribe_type,this.getDoctor()},mounted:function(){calen();var t=this;this.$store.state.tcmuser||wx.ready(function(){wx.onMenuShareTimeline({title:"诚意推荐好中医 "+t.doctor.name+"医师 【泰和国医】",link:protocol+window.location.host+"/wechat/doctor/detail/id?id="+t.id+"&clinicId="+t.clinicId,imgUrl:t.doctor.photoSUrl,success:function(){},cancel:function(){},fail:function(t){alert(e()(t))}}),wx.onMenuShareAppMessage({title:"诚意推荐好中医 "+t.doctor.name+"医师 【泰和国医】",desc:"您可通过【泰和国医】微信公众账号直接关注，向Ta发起在线咨询或预约Ta的门诊。",link:protocol+window.location.host+"/wechat/doctor/detail/id?id="+t.id+"&clinicId="+t.clinicId,imgUrl:t.doctor.photoSUrl,success:function(){},cancel:function(){},fail:function(t){alert(e()(t))}})})},methods:{intro:function(t){console.log(t);var s=t.replace(/\n/g,"<br>");return console.log(s),s},hideTip:function(){$(".tipsBg").addClass("none")},showmore:function(){$("#dInfo").toggleClass("open"),$(".icon-triangle-down").toggleClass("up")},canceldel:function(){$(".layer_pop").addClass("none")},dodel:function(){$(".layer_pop").addClass("none"),this.$router.push({path:"/my_fmld/my"})},bg:function(t){if(t)return"background-image:url("+t+")"},imgScare:function(t){this.qcode=t,$(".imgScare").show()},close:function(){$(".imgScare").hide()},getDoctor:function(){this.$http.get(this.$store.state.apiUrl+"doctor/detail/"+this.id+"?include=diseases,sections,comments.user,cliniques.schedules").then(function(t){this.doctor=t.data.data,t.data.data.comments&&(this.comment=t.data.data.comments.data),t.data.data.cliniques&&(this.clinique=t.data.data.cliniques.data),t.data.data.diseases&&(this.diseases=t.data.data.diseases.data),t.data.data.sections&&(this.sections=t.data.data.sections.data),t.data.data.cliniques&&(this.store=this.clinique[0].name,this.wj=isEmpty(this.clinique[0].schedules.data)?[]:this.clinique[0].schedules.data),this.comment_num=this.comment.length,this.$nextTick(function(){var t=this.id;this.dateTable(t)})})},follow:function(){this.$http.get(this.$store.state.apiUrl+"users/follow/"+this.id).then(function(t){t.data.status?this.getDetail():$api.pop(t.data.msg)})},online:function(){var t=this;this.doctor.rest?$(".tipsBg").removeClass("none"):this.$http.get(this.$store.state.apiUrl+"user/complete").then(function(s){1==s.data.status?t.$http.get(t.$store.state.apiUrl+"bespeak/can/"+t.id).then(function(s){s.data.status?t.$router.push({path:"/doctor/preOnline",query:{id:t.id,type:t.$route.query.type}}):$api.pop(s.data.msg)}):$(".layer_pop").removeClass("none")})},offline:function(){this.doctor.cliniques&&1==this.doctor.cliniques.data[0].isSchedules?this.$http.get(this.$store.state.apiUrl+"user/complete").then(function(t){1==t.data.status?this.$router.push({path:"/doctor/outline",query:{id:this.id}}):$(".layer_pop").removeClass("none")}):$(".tipsBg").removeClass("none")},getComment:function(t){this.$router.push({path:"/doctor/allComment",query:{id:t}})},dateTable:function(t){isEmpty(this.wj)||($("#mytable").removeClass("none"),$("#mytable").prev().removeClass("none"),$(".info").removeClass("none"),this.dateData(this.wj,t,this.clinique[0].id))},nocan:function(){$api.pop("医生暂未开通该功能")},dateData:function(t,s,i){for(var a=this,e=0;e<t.length;e++){var n=t[e].date;$("#mytable tbody tr").find("td").each(function(){n==$(this).attr("data-calen")&&($(this).addClass("yy"),$(this).click(function(){a.doctorOut(s,i,$(this).attr("data-calen"))}))})}},doctorOut:function(t,s,i){this.$http.get(this.$store.state.apiUrl+"user/complete").then(function(a){1==a.data.status?this.$router.push({path:"/doctor/outline",query:{id:t,clinicId:s,date:i}}):($api.pop(a.data.msg),this.$router.push({path:"/my_fmld/my"}))})}}}},208:function(t,s){t.exports={render:function(){var t=this,s=t.$createElement,i=t._self._c||s;return i("div",{staticClass:"fixbody"},[t._m(0),[i("div",{staticClass:"btn btn-fix"},[1==t.doctor.web?i("div",{staticClass:"right",class:{buy:1==t.doctor.web&&0==t.doctor.rest},on:{click:function(s){t.online()}}},[t._v(t._s(3==t.$route.query.type?"视频问医":"在线咨询"))]):i("div",{staticClass:"right",on:{click:t.nocan}},[t._v(t._s(3==t.$route.query.type?"视频问医":"在线咨询"))]),1==t.doctor.clinic?i("div",{staticClass:"left",on:{click:function(s){t.offline()}}},[t._v("门诊预约")]):i("div",{staticClass:"left",on:{click:t.nocan}},[t._v("门诊预约")])])],i("div",{attrs:{id:"wrap"}},[i("div",{staticClass:"doc_banner"},[t.doctor.photoSUrl?i("div",{staticClass:"avatar",style:t.bg(t.doctor.photoSUrl)}):i("div",{staticClass:"avatar",style:t.bg("/img/doctor_default.png")}),i("h1",[i("b",[t._v(t._s(t.doctor.name))]),t.doctor.titleName?i("span",{staticClass:"titlename"},[t._v(t._s(t.doctor.titleName))]):t._e()]),i("h1",{staticClass:"store"},[i("span",[t._v(t._s(t.store))])]),i("p",[i("span",[t._v("患者推荐指数：")]),i("span",{staticClass:"stars",attrs:{show:t.doctor.level}},[i("i",{staticClass:"icon-nav1"}),i("i",{staticClass:"icon-nav1"}),i("i",{staticClass:"icon-nav1"}),i("i",{staticClass:"icon-nav1"}),i("i",{staticClass:"icon-nav1"})])])]),i("div",{staticClass:"doc_main"},[i("div",{staticClass:"conts conts_g"},[i("h3",[t._v("擅长")]),i("p",{staticClass:"good"},[t._l(t.diseases,function(s){return[i("span",[t._v(t._s(s.name))])]})],2),[i("h3",{staticClass:"info"},[t._v("门诊信息")]),[i("div",{staticClass:"hos none"},[i("i"),i("span",[t._v(t._s(t.store))])]),t._m(1)]]],2),t.doctor.intro?i("div",{staticClass:"conts"},[i("h3",[t._v("个人介绍")]),i("p",{staticClass:"jieshao open",attrs:{id:"dInfo"},domProps:{innerHTML:t._s(t.intro(t.doctor.intro))}})]):t._e(),t.doctor.intro?i("div",{staticClass:"btn_toggle",on:{click:t.showmore}},[i("span",[t._v("更多")]),i("i",{staticClass:"icon-triangle-down"})]):t._e()]),t.comment?i("div",{staticClass:"doc_main",attrs:{id:"dComment"}},[i("div",{staticClass:"hr"},[i("h3",[t._v("评论-"+t._s(t.comment_num))])]),t._l(t.comment,function(s,a){return[i("div",{staticClass:"comments"},[s.user.data.headimgurl?i("div",{staticClass:"avatar",style:t.bg(s.user.data.headimgurl)}):i("div",{staticClass:"avatar",style:t.bg("/img/doctor_default.png")}),i("h2",[t._v(t._s(s.user.data.nickname.substr(0,1)+"**"))]),i("p",{staticClass:"lab"},[s.disease?i("span",[t._v(t._s(s.disease))]):t._e()]),i("p",{staticClass:"js"},[t._v(t._s(s.content))]),i("div",{staticClass:"right"},[i("span",[t._v(t._s(s.time.substr(0,10)))])])])]})],2):t._e()]),i("div",{staticClass:"layer_pop none"},[i("div",{staticClass:"content"},[i("div",{staticClass:"txt"},[t._v("请先去完善信息！")]),i("div",{staticClass:"pop_btn clearfix"},[i("div",{staticClass:"p_btn l",on:{click:function(s){t.dodel()}}},[t._v("确定")]),i("div",{staticClass:"p_btn",on:{click:function(s){t.canceldel()}}},[t._v("取消")])])])]),i("div",{staticClass:"tipsBg none"},[i("div",{staticClass:"orderFull"},[t._m(2),i("div",{staticClass:"fullbtn",on:{click:t.hideTip}},[t._v("确定")])])])],2)},staticRenderFns:[function(){var t=this,s=t.$createElement,i=t._self._c||s;return i("header",[i("div",{staticClass:"left",attrs:{onclick:"back()"}},[i("i",{staticClass:"icon-arrow-left"})]),i("div",{staticClass:"center"},[t._v("医生主页")])])},function(){var t=this,s=t.$createElement,i=t._self._c||s;return i("table",{staticClass:"none",attrs:{id:"mytable"}},[i("tbody",{staticClass:"ttt"})])},function(){var t=this,s=t.$createElement,i=t._self._c||s;return i("div",{staticClass:"text"},[i("p",[t._v("医生预约已满，")]),i("p",[t._v("如需预约请致电：010-64176667")]),i("p",[t._v("泰和国医预约电话进行预约")])])}]}}});