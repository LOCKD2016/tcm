webpackJsonp([23],{125:function(a,t,e){var s=e(11)(e(180),e(243),null,null);a.exports=s.exports},180:function(a,t,e){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var s=e(12);t.default={data:function(){return{method:[{id:2,name:"微信"}],tag:0,order:{},bespeak:{},bespeak_id:0,subscirbe_type:0,mid:1,method_set:0,total:0,order_id:0,error_status:0,password:"",status:0}},created:function(){this.bespeak_id=this.$route.query.bespeak_id,this.order_id=this.$route.query.order_id},watch:{bespeak_id:function(a){a>0&&this.get_bespeak()},order_id:function(a){a>0&&this.getOrder(a)}},methods:{get_bespeak:function(){this.$http.get(this.$store.state.apiUrl+"bespeak/detail/"+this.bespeak_id+"?include=order").then(function(a){if(this.bespeak=a.data.data,!a.data.data.order)return $api.pop("订单信息缺失"),!1;this.order=a.data.data.order.data},function(a){e.i(s.b)(a.data.data.errors)})},getOrder:function(a){this.$http.get(this.$store.state.apiUrl+"order/detail/"+a).then(function(a){if(!a.data.data)return $api.pop("订单信息缺失"),!1;this.order=a.data.data},function(a){e.i(s.b)(a.data.data.errors)})},repay:function(){if(!this.$store.state.wxready&&!this.$store.state.tcmuser)return $api.pop("微信API准备未就绪，请稍后再试"),!1;var a=this;0==this.tag&&($(".topay").html("订单处理中..."),a.tag=1,a.$http.get(a.$store.state.apiUrl+"pay/wechat/"+a.order.id).then(function(t){t.data.status?t.data.data.free_order&&1==t.data.data.free_order?($api.pop("支付成功"),a.$http.get(a.$store.state.apiUrl+"order/detail/"+a.order.id+"?include=bespeak.message.doctor").then(function(t){5==t.data.data.status&&2==t.data.data.order_type?a.$router.push({path:"/chat",query:{listId:t.data.data.bespeak.data.message.data.id,doctorName:t.data.data.bespeak.data.message.data.doctor.data.name}}):1==t.data.data.order_type?a.$router.push({path:"/pay_sucess",query:{bespeak_id:t.data.data.bespeak.data.id}}):a.$store.state.tcmuser?a.$router.push({path:"/order"}):window.location.href="/wechat/order"})):a.$store.state.tcmuser?app.chooseWXPay(t.data.data,function(t,e){e?-2==e.code?$api.pop("支付取消"):$api.pop("支付失败"):t&&t.status?($api.pop("支付成功"),a.$http.get(a.$store.state.apiUrl+"order/detail/"+a.order.id+"?include=bespeak.message.doctor").then(function(t){5==t.data.data.status&&2==t.data.data.order_type?a.$router.push({path:"/chat",query:{bespeakId:t.data.data.bespeak.data.id,listId:t.data.data.bespeak.data.message.data.id,doctorName:t.data.data.bespeak.data.message.data.doctor.data.name}}):1==t.data.data.order_type?a.$router.push({path:"/pay_sucess",query:{bespeak_id:t.data.data.bespeak.data.id}}):a.$router.push({path:"/order"})})):$api.pop("支付失败")}):wx.chooseWXPay({timestamp:t.data.data.timestamp,nonceStr:t.data.data.nonceStr,package:t.data.data.package,signType:t.data.data.signType,paySign:t.data.data.paySign,success:function(t){"chooseWXPay:ok"==t.errMsg&&($api.pop("支付成功"),a.$http.get(a.$store.state.apiUrl+"order/detail/"+a.order.id+"?include=bespeak.message.doctor").then(function(t){5==t.data.data.status&&2==t.data.data.order_type?a.$router.push({path:"/chat",query:{listId:t.data.data.bespeak.data.message.data.id,doctorName:t.data.data.bespeak.data.message.data.doctor.data.name}}):1==t.data.data.order_type?a.$router.push({path:"/pay_sucess",query:{bespeak_id:t.data.data.bespeak.data.id}}):window.location.href="/wechat/order"}))},fail:function(a){$api.pop("无法调起微信支付"+a.errMsg),setTimeout(function(){location.reload()},400)},cancel:function(a){$api.pop("支付取消")}}):$api.pop(t.data.msg),setTimeout(function(){a.tag=0},200),$(".topay").html("支付")}))},payment:function(){$(".payment").removeClass("none")},backTo:function(){$(".payment").addClass("none")},bind:function(){$(".layer_pop").removeClass("none")},canceldel:function(){$(".layer_pop").addClass("none"),this.tag=0},dodel:function(){$(".layer_pop").addClass("none"),this.$router.push({path:"/my_card"})}}}},243:function(a,t){a.exports={render:function(){var a=this,t=a.$createElement,e=a._self._c||t;return e("div",{staticClass:"fixbody"},[e("div",{attrs:{id:"wrap"}},[a._m(0),e("div",{staticClass:"payment pay_fs"},[e("ul",{staticClass:"payX"},a._l(a.method,function(t,s){return e("li",{class:a.method_set==s?"active":"",on:{click:function(e){a.setMethod(s,t.id)}}},[e("span",{class:1==t.id?"icon-bank-card":"icon-weixin"}),e("a",[a._v(a._s(t.name))]),e("i",{staticClass:"icon-check-c"})])}))])]),e("div",{staticClass:"btn btn-fix"},[e("div",{staticClass:"left"},[e("span",[a._v("总计：")]),e("b",[a._v("￥"+a._s(a.order.payable_amount))])]),e("div",{staticClass:"right buy topay",class:{btn_cor:!a.$store.state.wxready&&!a.$store.state.tcmuser},on:{click:function(t){a.repay()}}},[a._v("支付")])])])},staticRenderFns:[function(){var a=this,t=a.$createElement,e=a._self._c||t;return e("header",[e("div",{staticClass:"left",attrs:{onclick:"back()"}},[e("i",{staticClass:"icon-arrow-left"})]),e("div",{staticClass:"center"},[a._v("确认支付")])])}]}}});