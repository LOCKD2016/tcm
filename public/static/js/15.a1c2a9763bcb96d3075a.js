webpackJsonp([15],{134:function(t,i,s){var e=s(11)(s(189),s(217),null,null);t.exports=e.exports},189:function(t,i,s){"use strict";Object.defineProperty(i,"__esModule",{value:!0}),i.default={data:function(){return{id:0,info:{}}},created:function(){this.id=this.$route.query.id},methods:{getStandard:function(){this.$http({url:this.$store.state.apiUrl+"inquiry/detail/"+this.id,method:"GET"}).then(function(t){this.info=t.data.data})}},watch:{id:function(t){t>0&&this.getStandard()}}}},217:function(t,i){t.exports={render:function(){var t=this,i=t.$createElement,s=t._self._c||i;return s("div",{staticClass:"fixbody"},[t._m(0),s("div",{staticClass:"doctor_order",attrs:{id:"wrap"}},[1==t.info.type?s("div",{staticClass:"panel"},[s("h3",[t._v("疾病")]),s("div",{staticClass:"txt"},[s("p",[t._v(t._s(t.info.disease))])])]):t._e(),s("div",{staticClass:"panel"},[s("h3",[t._v("症状描述")]),s("div",{staticClass:"txt"},[s("p",[t._v(t._s(t.info.desc))])])]),0==t.info.type?s("div",{staticClass:"panel"},[s("h3",[t._v("症例")]),s("div",{staticClass:"txt"},[s("ul",t._l(t.info.disease,function(t){return s("li",[s("img",{staticClass:"diseasepic",attrs:{src:t}})])}))])]):t._e()])])},staticRenderFns:[function(){var t=this,i=t.$createElement,s=t._self._c||i;return s("header",[s("div",{staticClass:"left",attrs:{onclick:"back()"}},[s("i",{staticClass:"icon-arrow-left"})]),s("div",{staticClass:"center"},[t._v("查看标准问诊单")])])}]}}});