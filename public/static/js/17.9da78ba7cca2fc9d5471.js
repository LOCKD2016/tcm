webpackJsonp([17],{131:function(t,e,s){var i=s(11)(s(186),s(201),null,null);t.exports=i.exports},186:function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={created:function(){this.keyword=this.$route.query.keyword,$(".shuju").css({display:"none","z-index":100})},data:function(){return{lists:[],page:1,total:0,keyword:"",type:0,result:"",recommend:0,swiper:"",statusOrder:!0}},filters:{netMoney:function(t){return parseInt(t.net_money)?"￥"+parseInt(t.net_money):"免费"},clinicMoney:function(t){return parseInt(t.clinic_money)?"￥"+parseInt(t.clinic_money):"免费"}},mounted:function(){var t=this;this.getList(),setTimeout(function(){t.swiper=new Swiper(".swiper-container-list",{direction:"vertical",slidesPerView:"auto",observer:!0,observeParents:!0,mousewheelControl:!0,freeMode:!0,freeModeMomentumRatio:.4,freeModeMomentumVelocityRatio:.2,resistanceRatio:.7,preventLinksPropagation:!0,preventClicksPropagation:!0,preventClicks:!0,onTouchEnd:function(e){var s=e.height,i=e.virtualSize;e.translate<=s-i-50&&e.translate<0&&t.statusOrder&&t.$nextTick(function(){$(".visible").html("正在加载..."),t.getList()})}})},400)},methods:{getList:function(){var t=this,e="search";if(this.recommend>0&&(e="recommend"),!this.keyword)return this.page=1,this.recommend=0,this.lists=[],$api.pop("关键词不能为空"),!1;console.log(this.keyword);var s=$(".orderBOX").find(".visible");this.$http({url:this.$store.state.apiUrl+"doctor/"+e+"?include=diseases",method:"GET",params:{page:this.page,name:this.keyword}}).then(function(e){if(!e.data.data.count)return $(".no").removeClass("none"),$(".yes").addClass("none"),!1;e.data.data.list.forEach(function(e){t.lists.push(e)}),e.data.data.list.length>0&&t.page++,t.page>e.data.data.totalPage?(s.html("没有更多数据了"),t.statusOrder=!1):(s.show(),t.statusOrder=!0,s.html("上拉加载")),setTimeout(function(){t.swiper.update()},400),e.data.data.totalPage||$(".shuju").css({display:"none"}),this.total=e.data.data.total,$(".yes").removeClass("none"),$(".no").addClass("none")})},order:function(t){this.$http.get(this.$store.state.apiUrl+"user/complete").then(function(e){e.data.status?this.$router.push({path:"/doctor_detail",query:{id:t}}):$api.pop(e.data.msg)})},bg:function(t){if(t)return"background-image:url("+t+")"},search:function(){this.recommend=0,this.getList()},det:function(t){this.$router.push({path:"/doctor_detail",query:{id:t,clinicId:this.clinicid,type:this.type}})}},events:{update:function(){this.getList()}},watch:{keyword:function(){this.recommend=0,this.page=1,this.lists=[]}}}},201:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"fixbody"},[s("header",{staticClass:"search_header"},[t._m(0),s("div",{staticClass:"center"},[s("form",{staticClass:"input-kw-form",attrs:{action:""}},[s("input",{directives:[{name:"model",rawName:"v-model",value:t.keyword,expression:"keyword"}],attrs:{type:"search",autocomplete:"off",name:"keyword"},domProps:{value:t.keyword},on:{input:function(e){e.target.composing||(t.keyword=e.target.value)}}})]),s("i",{staticClass:"icon-search",on:{click:function(e){t.search()}}})])]),s("div",{attrs:{id:"wrap"}},[s("div",{staticClass:"shuju"},[t._v("正在刷新...")]),s("div",{staticClass:"swiper-container swiper-container-list"},[s("div",{staticClass:"orderBOX swiper-wrapper"},[s("div",{staticClass:"swiper-slide"},[s("div",{staticClass:"panel",staticStyle:{background:"none"}},[s("h4",{staticClass:"tit_illness no none"},[t._v("没有找到您要找的医师")]),s("h4",{staticClass:"tit_illness yes none"},[t._v("我们为您精心查找了"),s("span",[t._v(t._s(t.keyword))]),t._v("的"+t._s(t.total)+"位医师")]),t._l(t.lists,function(e){return s("div",{staticClass:"doctor_list"},[s("div",{staticClass:"link",on:{click:function(s){t.det(e.id)}}},[s("div",{staticClass:"avatar",style:t.bg(e.photoSUrl)}),s("h3",[s("span",[t._v(t._s(e.name))]),s("sub",[t._v(t._s(e.titleName))]),1==e.web?s("label",{class:"active"},[t._v("在线")]):t._e(),1==e.clinic?s("label",{class:"active"},[t._v("门诊")]):t._e()]),s("p",{staticClass:"labBox"},[t._v("擅长："),t._l(e.diseases.data,function(e,i){return i<3?s("span",[t._v(t._s(e.name))]):t._e()})],2),s("h6",[s("span",[t._v("患者推荐指数")]),s("div",{staticClass:"stars",attrs:{show:e.level}},[s("i",{staticClass:"icon-nav1"}),s("i",{staticClass:"icon-nav1"}),s("i",{staticClass:"icon-nav1"}),s("i",{staticClass:"icon-nav1"}),s("i",{staticClass:"icon-nav1"})])])]),2==t.type?s("div",{staticClass:"btn btn-jv",on:{click:function(s){t.order(e.id)}}},[t._v("在线咨询")]):s("div",{staticClass:"btn btn-jv",on:{click:function(s){t.order(e.id)}}},[t._v("预约")])])})],2),s("div",{staticClass:"visible"})])])])])])},staticRenderFns:[function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"left",attrs:{onclick:"back()"}},[s("i",{staticClass:"icon-arrow-left"})])}]}}});