webpackJsonp([25],{121:function(t,s,i){var a=i(11)(i(175),i(214),null,null);t.exports=a.exports},175:function(t,s,i){"use strict";Object.defineProperty(s,"__esModule",{value:!0}),s.default={data:function(){return{list:[],goods:[],price_a:"",price_b:"",price:"",photoSUrl:"",img:"",commodity:"三伏贴"}},created:function(){this.id=this.$route.query.id,this.detail()},methods:{find:function(){if(this.$store.state.tcmuser){var t="http://www.kuaidi100.com/all/sf.shtml?from=openv";api.openApp({iosUrl:t,androidPkg:"android.intent.action.VIEW",mimeType:"text/html",uri:t},function(t,s){})}else window.location.href="http://www.kuaidi100.com/all/sf.shtml?from=openv"},fz:function(){document.getElementById("biao1").select(),document.execCommand("Copy"),alert("已复制好，可贴粘。")},detail:function(){this.$http.get(this.$store.state.apiUrl+"order/"+this.id).then(function(t){this.list=t.data.data,this.goods=t.data.data.goods})},bg:function(t){if(t)return"background-image:url("+t+")"},cT:function(t){switch(t){case 1:return"已完成";case 2:return"不在线";case 3:return"商城";case 4:return"推荐"}}}}},214:function(t,s){t.exports={render:function(){var t=this,s=t.$createElement,i=t._self._c||s;return i("div",{staticClass:"fixbody"},[t._m(0),i("div",{attrs:{id:"wrap"}},[0==t.list.order_type?i("div",{staticClass:"panel user"},[i("i",{staticClass:"icon-location"}),i("div",{staticClass:"phone"},[t._v(t._s(t.list.mobile))]),i("h3",[t._v(t._s(t.list.consignee))]),i("p",[t._v(t._s(t.list.province)+t._s(t.list.city)+t._s(t.list.district)+t._s(t.list.address))])]):t._e(),0==t.list.order_type?i("div",{staticClass:"panel fer"},[t.list.discount>0?i("div",{staticClass:"doctors commodity"},[i("div",{staticClass:"avatar",style:{backgroundImage:"url("+t.list.goods[0].share_img+")"}}),i("h3",[t._v(t._s(t.list.goods[0].goods_name))]),i("div",{staticClass:"price_a"},[t._v("¥ "+t._s(t.list.money_paid))]),i("div",{staticClass:"price_b"},[t._v("¥ "+t._s(t.list.goods_amount))])]):i("div",{staticClass:"doctors commodity"},[i("div",{staticClass:"avatar",style:{backgroundImage:"url("+t.list.goods[0].share_img+")"}}),i("h3",[t._v(t._s(t.list.goods[0].goods_name))]),i("div",{staticClass:"price_a"},[t._v("¥ "+t._s(t.list.goods_amount))])]),i("div",{staticClass:"yhm clearfix"},[i("div",{staticClass:"left"},[t._v("优惠码")]),0==t.list.discount?i("div",{staticClass:"right"},[t._v("未使用")]):i("div",{staticClass:"right"},[t._v("使用")])]),i("div",{staticClass:"foot clearfix"},[i("div",{staticClass:"left"},[t._v("总计：")]),i("div",{staticClass:"right"},[t._v("¥ "+t._s(t.list.money_paid))])])]):t._e(),0==t.list.order_type?i("div",{staticClass:"panel order_sta"},[i("dl",[i("dt",[t._v("订单编号：")]),i("dd",[t._v(t._s(t.list.order_sn))])]),i("dl",[i("dt",[t._v("订单状态：")]),i("dd",[t._v(t._s(t.list.new_status))])]),i("dl",[i("dt",[t._v("订单类型")]),i("dd",[t._v(t._s(t.list.goods_type))])]),i("dl",[i("dt",[t._v("创建时间：")]),i("dd",[t._v(t._s(t.list.created_at))])]),"已付款"==t.list.pay_status?i("dl",[i("dt",[t._v("支付时间：")]),i("dd",[t._v(t._s(t.list.pay_time))])]):t._e(),"已付款"==t.list.pay_status?i("dl",[i("dt",[t._v("支付方式：")]),i("dd",[t._v("微信支付")])]):t._e(),1==t.list.shopping_status?i("dl",[i("dt",[t._v("物流单号：")]),i("dd",[t._v(t._s(t.list.express_number))]),i("div",{staticClass:"btn_box"},[1==t.list.shopping_status?i("div",{staticClass:"btn btn-o btn-jv",on:{click:function(s){t.find()}}},[t._v("查询")]):t._e()])]):t._e()]):t._e()])])},staticRenderFns:[function(){var t=this,s=t.$createElement,i=t._self._c||s;return i("header",[i("div",{staticClass:"left",attrs:{onclick:"back()"}},[i("i",{staticClass:"icon-arrow-left"})]),i("div",{staticClass:"center"},[t._v("订单详情")])])}]}}});