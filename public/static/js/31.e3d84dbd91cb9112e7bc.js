webpackJsonp([31],{116:function(t,e,s){var r=s(11)(s(168),s(220),null,null);t.exports=r.exports},168:function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={data:function(){return{id:0,resultcode:0,exp:[],list:[],order:[]}},created:function(){this.id=this.$route.query.id,this.getExp()},filters:{orderInfo:function(t){switch(t.shipping_status){case 0:return"未发货";case 1:return"运输中";case 2:return"已签收";case 4:return"退货"}}},methods:{getExp:function(){this.$http.get(this.$store.state.apiUrl+"orders/exp/"+this.id).then(function(t){t.data.status&&(this.order=t.data.data.order,t.data.data.exp.result&&(this.resultcode=t.data.data.exp.resultcode,this.exp=t.data.data.exp.result,this.list=t.data.data.exp.result.list.reverse()))})}}}},220:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"fixbody"},[t._m(0),s("div",{attrs:{id:"wrap"}},[s("div",{staticClass:"logistics clearfix"},[t._m(1),s("div",{staticClass:"left del"},[s("dl",{staticClass:"stauts"},[s("dt",[t._v("物流状态：")]),s("dd",[t._v(t._s(t._f("orderInfo")(t.order,t.order)))])]),t._m(2),s("dl",[s("dt",[t._v("运单编号：")]),s("dd",[t._v(t._s(t.order.express_number))])]),t._m(3)])]),200==t.resultcode?s("div",{staticClass:"logistics_del"},t._l(t.list,function(e){return s("dl",[s("dt",[t._v(t._s(e.remark))]),s("dd",[t._v(t._s(e.datetime))])])})):s("div",{staticClass:"logistics"},[s("span",{staticClass:"notfind"},[t._v("查不到物流信息")])])])])},staticRenderFns:[function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("header",[s("div",{staticClass:"left",attrs:{onclick:"back()"}},[s("i",{staticClass:"icon-arrow-left"})]),s("div",{staticClass:"center"},[t._v("物流信息")])])},function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"left"},[s("img",{attrs:{src:"/img/banner_gst.jpg"}})])},function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("dl",[s("dt",[t._v("承运来源：")]),s("dd",[t._v("顺丰快递")])])},function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("dl",[s("dt",[t._v("官方电话：")]),s("dd",[t._v("15456")])])}]}}});