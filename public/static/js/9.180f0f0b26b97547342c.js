webpackJsonp([9],{123:function(t,s,i){var e=i(11)(i(177),i(232),null,null);t.exports=e.exports},147:function(t,s,i){t.exports=i.p+"static/img/map1.ddaed6e.png"},177:function(t,s,i){"use strict";Object.defineProperty(s,"__esModule",{value:!0}),s.default={data:function(){return{list:[],bespeak:[],doctor:[],clinique:[],recipe_order:[],comment:[],subscribe:[],price_a:"",price_b:"",price:"",photoSUrl:"",img:"",userInfo:null}},created:function(){this.id=this.$route.query.id,this.detail(),this.$http.get(this.$store.state.apiUrl+"user/detail").then(function(t){t&&t.status&&(this.userInfo=t.data.data)})},filters:{money:function(t){return isEmpty(t.is_first)?"诊费：¥ "+t.doctor.clinic_money:"复诊费：¥ "+t.doctor.return_money},returnStatus:function(t){switch(t){case 0:return"未支付";case 2:return"正在支付";case 5:return"已支付";case 7:return"已过期";case 9:return"退款中";case 10:return"已退款"}},payType:function(t){switch(t){case 0:return"未支付";case 1:return"微信支付";case 5:return"免费订单"}}},ready:function(){},methods:{toMap:function(){var t="https://m.amap.com/navi/?start=&dest=116.451507,39.937215&destName=%E6%B3%B0%E5%92%8C%E5%9B%BD%E5%8C%BB&key=f429bc8bfc2f54d86dc399ea513ca3d5";this.$store.state.tcmuser?api.openApp({iosUrl:t,androidPkg:"android.intent.action.VIEW",mimeType:"text/html",uri:t},function(t,s){}):window.location.href=t},find:function(){if(this.$store.state.tcmuser){var t="http://www.kuaidi100.com/all/sf.shtml?from=openv";api.openApp({iosUrl:t,androidPkg:"android.intent.action.VIEW",mimeType:"text/html",uri:t},function(t,s){})}else window.location.href="http://www.kuaidi100.com/all/sf.shtml?from=openv"},fz:function(){document.getElementById("biao1").select(),document.execCommand("Copy"),alert("已复制好，可贴粘。")},detail:function(){this.$http.get(this.$store.state.apiUrl+"order/detail/"+this.id+"?include=bespeak.doctor,bespeak.clinique,goods").then(function(t){this.list=t.data.data,this.list.bespeak&&(this.bespeak=this.list.bespeak.data,this.doctor=this.list.bespeak.data.doctor.data),t.data.data.clinique&&(this.clinique=t.data.data.clinique)})},bg:function(t){if(t)return"background-image:url("+t+")"},cT:function(t){switch(t){case 1:return"已完成";case 2:return"不在线";case 3:return"商城";case 4:return"推荐"}},commentClinic:function(t){this.$router.push({path:"/tickling",query:{id:t}})},toRecipe:function(t){this.$router.push({path:"/prescription/my/id",query:{id:t}})}}}},232:function(t,s,i){t.exports={render:function(){var t=this,s=t.$createElement,i=t._self._c||s;return i("div",{staticClass:"fixbody"},[t._m(0),i("div",{staticClass:"order_de",attrs:{id:"wrap"}},[1==t.list.order_type?i("div",{staticClass:"panel fer"},[i("div",{staticClass:"doctors commodity"},[i("div",{staticClass:"avatar",style:t.bg(t.doctor.photoSUrl)}),i("h3",[t._v(t._s(t.doctor.name))]),i("p",[t._v(t._s(t.doctor.titleName))])]),i("div",{staticClass:"contact clearfix"},[i("div",{staticClass:"left"},[t._v("就诊日期：")]),i("div",{staticClass:"right"},[t._v(t._s(t.bespeak.start_time))])]),i("div",{staticClass:"contact clearfix"},[i("div",{staticClass:"left"},[t._v("患者姓名：")]),i("div",{staticClass:"right"},[t._v(t._s(t.userInfo.realname))])]),i("div",{staticClass:"contact clearfix"},[i("div",{staticClass:"left"},[t._v("身份证：")]),i("div",{staticClass:"right"},[t._v(t._s(t.userInfo.idNo))])]),i("div",{staticClass:"contact clearfix"},[i("div",{staticClass:"left"},[t._v("医事服务费：")]),t.list.status>=5?i("div",{staticClass:"right"},[t._v("¥ "+t._s(t.list.amount))]):t._e()]),i("div",{staticClass:"contact clearfix"},[i("div",{staticClass:"left"},[t._v("取号地点：")]),i("div",{staticClass:"right"},[t._v(t._s(t.clinique.content.address))])]),i("div",{staticClass:"contact clearfix"},[i("div",{staticClass:"left"},[t._v("联系方式：")]),i("div",{staticClass:"right"},[t._v(t._s(t.clinique.telephone))])]),i("div",{staticClass:"clinicTips"},[t._v("您的预约时间为："+t._s(t.bespeak.start_time)+"，请您携带有效身份证原件准时就诊，过时预约号作废。")]),i("div",{staticClass:"addr"},[i("i",{staticClass:"icon-location"}),i("h3",[t._v(t._s(t.clinique.name)),i("a",{attrs:{href:"//m.amap.com/navi/?start=&dest=116.451507,39.937215&destName=%E6%B3%B0%E5%92%8C%E5%9B%BD%E5%8C%BB&key=f429bc8bfc2f54d86dc399ea513ca3d5"}},[t._v("进入导航")])]),i("p",[t._v(t._s(t.clinique.content.address))])]),t._m(1)]):t._e(),2==t.list.order_type?i("div",{staticClass:"panel order_sta"},[i("dl",[i("dt",[t._v("订单编号：")]),i("dd",[t._v(t._s(t.list.order_sn))])]),i("dl",[i("dt",[t._v("支付状态：")]),i("dd",[t._v(t._s(t._f("returnStatus")(t.list.status)))])]),t.list.status>=5?i("dl",[i("dt",[t._v("支付方式：")]),i("dd",[t._v("微信")])]):t._e(),t.list.status>=5?i("dl",[i("dt",[t._v("支付时间：")]),i("dd",[t._v(t._s(t.list.pay_time))])]):t._e(),i("div",{staticClass:"foot clearfix"},[i("div",{staticClass:"left"},[t._v("总计：")]),i("div",{staticClass:"right"},[t._v("¥ "+t._s(t.list.amount))])])]):t._e(),3==t.list.order_type?i("div",{staticClass:"panel fer user"},[t.list.prescription?i("h5",{staticClass:"hea",on:{click:function(s){t.toRecipe(t.list.prescription.id)}}},[i("span",[t._v("处方信息")]),i("span",{staticClass:"icon-arrow-right"})]):t._e(),t.list.amount>0?i("div",{staticClass:"foot clearfix"},[i("div",{staticClass:"left"},[t._v("总计：")]),i("div",{staticClass:"right"},[t._v("¥ "+t._s(t.list.amount))])]):t._e()]):t._e(),3==t.list.order_type?i("div",{staticClass:"panel order_sta"},[i("dl",[i("dt",[t._v("订单编号：")]),i("dd",[t._v(t._s(t.list.order_sn))])]),t.list.pay_time?i("dl",[i("dt",[t._v("支付时间：")]),i("dd",[t._v(t._s(t.list.pay_time))])]):i("dl",[i("dt",[t._v("订单状态：")]),i("dd",[t._v("未付款")])]),t.list.pay_time?i("dl",[i("dt",[t._v("支付方式：")]),i("dd",[t._v("微信支付")])]):t._e(),t.list.pay_time&&0==t.list.prescription.express?i("div",{staticClass:"getmedicine"},[t._m(2),i("dl",[i("dt",[t._v("门诊电话：")]),i("dd",[t._v(t._s(t.clinique.telephone))])]),i("dl",[i("dt",[t._v("取药地点：")]),i("dd"),i("div",{staticClass:"addr"},[i("i",{staticClass:"icon-location"}),i("h3",[t._v(t._s(t.clinique.name)),i("a",{on:{click:t.toMap}},[t._v("进入导航")])]),i("p",[t._v(t._s(t.clinique.content.address)+"        ")])])])]):t._e(),t.list.pay_time&&1==t.list.prescription.express?i("div",{staticClass:"expressinfo"},[i("dl",[i("dt",[t._v("门诊电话：")]),i("dd",[t._v(t._s(t.clinique.telephone))])]),t.list.express.express_number?t._e():i("dl",[i("dt",[t._v("发货时间：")]),i("dd",[t._v("24h-48h之内发货")])]),t.list.order_user?i("dl",[i("dt",[t._v("收货地址：")]),i("dd",[t._v(t._s(t.list.order_user.country)+" "+t._s(t.list.order_user.province)+" "+t._s(t.list.order_user.city)+" "+t._s(t.list.order_user.district)+" "+t._s(t.list.order_user.address)+" ")])]):t._e(),t.list.express.express_number?i("dl",[i("dt",[t._v("快递单号：")]),i("dd",[t._v(t._s(t.list.express.express_number))])]):t._e()]):t._e()]):t._e()])])},staticRenderFns:[function(){var t=this,s=t.$createElement,i=t._self._c||s;return i("header",[i("div",{staticClass:"left",attrs:{onclick:"back()"}},[i("i",{staticClass:"icon-arrow-left"})]),i("div",{staticClass:"center"},[t._v("我的订单")])])},function(){var t=this,s=t.$createElement,e=t._self._c||s;return e("div",{staticClass:"mapBox"},[e("img",{attrs:{src:i(147)}})])},function(){var t=this,s=t.$createElement,i=t._self._c||s;return i("dl",[i("dt",[t._v("取药时间：")]),i("dd",[t._v("24h-48h之内到门诊取药")])])}]}}});