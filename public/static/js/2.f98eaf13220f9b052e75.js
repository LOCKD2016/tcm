webpackJsonp([2],{13:function(t,e,a){var s=a(11)(a(178),a(243),null,null);t.exports=s.exports},178:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var s=a(32),i=a.n(s);a(12);e.default={data:function(){return{lists:[],tag:0,delid:0,pay_status:0,indextmp:0,type:0,subscribe_type:0,status:"",logistic:[],name:"",page:1,again_type:"subscribe",method:[{id:2,name:"微信"}],reminds:"",msg:"会员用户可免挂号费，更多福利",swiper:"",statusOrder:!0}},created:function(){this.getMe()},mounted:function(){var t=this;this.getList(),setTimeout(function(){t.swiper=new Swiper(".swiper-container-order",{direction:"vertical",slidesPerView:"auto",observer:!0,observeParents:!0,mousewheelControl:!0,freeMode:!0,resistanceRatio:.7,preventLinksPropagation:!0,preventClicks:!0,onTouchEnd:function(e){var a=e.height,s=e.virtualSize;e.translate<=a-s-50&&e.translate<0&&t.statusOrder&&($(".visible").html("正在加载..."),t.getList()),e.translate>50&&window.location.reload(),e.translate<0&&setTimeout(function(){},500)}})},400),this.$store.state.tcmuser||wx.ready(function(){wx.onMenuShareTimeline({title:"诚意为您推荐好中医【泰和国医】",link:protocol+window.location.host+"/wechat/index",imgUrl:protocol+window.location.host+"/static/img/wxlogo.png",success:function(){},cancel:function(){},fail:function(t){alert(i()(t))}}),wx.onMenuShareAppMessage({title:"诚意为您推荐好中医【泰和国医】",desc:"您可通过【泰和国医】微信公众账号关注医师，预约Ta的门诊或向Ta发起在线咨询。",link:protocol+window.location.host+"/wechat/index",imgUrl:protocol+window.location.host+"/static/img/wxlogo.png",success:function(){},cancel:function(){},fail:function(t){alert(i()(t))}})})},events:{update:function(){this.getList(),self.swiper.update()}},filters:{price:function(t){return isEmpty(t.order)||isEmpty(t.order.data.order_sn)?0:t.order.data.pay_amount?t.order.data.pay_amount:t.order.data.payable_amount},status_value:function(t){switch(t){case 5:return"待接诊";case 10:return"待支付";case 15:return"已支付";case 20:return"诊疗中";case 25:return"诊疗结束";case 30:return"医生拒绝接诊";case 35:return"诊疗已取消";case 38:return"已过期"}},time_format:function(t){return t.substring(0,16)}},methods:{confirm_order:function(t){var e=this;this.$http.get(this.$store.state.apiUrl+"order/bespeak/"+t).then(function(a){a.data.status?e.$store.state.tcmuser?e.$router.push({path:"/payment",query:{bespeak_id:t}}):window.location.href="/wechat/payment/?bespeak_id="+t:$api.pop(a.data.msg)})},payment:function(t){this.$store.state.tcmuser?this.$router.push({path:"/payment",query:{bespeak_id:t}}):window.location.href="/wechat/payment/?bespeak_id="+t},find:function(t){this.$router.push({path:"/point",query:{id:t}})},ask:function(t,e){this.$router.push({path:"/chat",query:{clinicId:t,doctorName:e}})},loadMore:function(){this.statusOrder&&this.getList()},getList:function(){var t=this,e=$(".orderBOX").find(".visible");this.$http({url:this.$store.state.apiUrl+"bespeak/lists?include=doctor,clinique,order,clinic",method:"GET",params:{page:this.page}}).then(function(a){a.data.data.list.forEach(function(e){t.lists.push(e)}),t.page++,t.page>a.data.data.totalPage?(e.html("没有更多数据了"),t.statusOrder=!1):(e.show(),e.html("点击加载")),setTimeout(function(){t.swiper.update()},400)})},list:function(){this.page=this.page-1,console.log(this.page),this.$http({url:this.$store.state.apiUrl+"bespeak/lists",method:"GET",params:{page:this.page}}).then(function(t){})},clinicCancel:function(t){this.$http.get(this.$store.state.apiUrl+"orders/getrefund/"+t).then(function(t){t.data.status?(this.reminds=t.data.msg,$(".layer_pop").removeClass("none")):$api.pop(t.data.msg),this.status=t.data.status})},delDeal:function(t){var e=this.lists[t];$(".layer_pop").removeClass("none"),this.reminds="您确定将该预约取消？",this.delid=e.id,this.indextmp=t},canceldel:function(){$(".layer_pop").addClass("none")},dodel:function(){$(".layer_pop").addClass("none");var t=this;this.delid&&t.$http.get(t.$store.state.apiUrl+"bespeak/close/"+this.delid).then(function(t){$api.pop(t.data.msg),t.data.status&&(this.getList(),window.location.reload())})},number:function(t){this.$http.get(this.$store.state.apiUrl+"order/"+t).then(function(t){1==t.data.status&&(this.logistic=t.data.data,$(".pop").fadeIn())})},again:function(t,e,a){this.$http({url:this.$store.state.apiUrl+"clinic/again",method:"GET",params:{id:t,type:this.again_type}}).then(function(t){t.data.status?this.$router.push({path:"/payment",query:{id:t.data.data.order_sn,type:e,clinic_money:a}}):$api.pop(t.data.msg)})},det:function(t){this.$router.push({path:"/order_details",query:{id:t}})},bg:function(t){if(t)return"background-image:url("+t+")"},cT:function(t){switch(t){case 1:return"已完成";case 2:return"不在线";case 3:return"商城";case 4:return"推荐"}},chat:function(t){this.$http.get(this.$store.state.apiUrl+"message/getMessageListData/bespeak/"+t+"?include=doctor").then(function(e){1==e.data.status&&(e.data.data.id&&this.$router.push({path:"/chat",query:{listId:e.data.data.id,doctorName:e.data.data.doctor.data.name,bespeakId:t}}),$(".pop").fadeIn())})},bind:function(){$(".layer_pop").removeClass("none")},setMethod:function(t,e){this.type=t,this.mid=e,console.log(this.type),console.log(this.mid)},wait:function(){this.$router.push({path:"/waitline_record"})},getMe:function(){this.$http({url:this.$store.state.apiUrl+"user/detail",method:"GET"}).then(function(t){t.data.status&&(window.localStorage.setItem("imToken",t.data.data.im_token),window.localStorage.setItem("headimgurl",t.data.data.headimgurl),window.localStorage.setItem("nickname",t.data.data.nickname),window.localStorage.setItem("id",t.data.data.id))})}}}},243:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"fixbody"},[a("header",[t.$store.state.tcmuser?t._e():a("router-link",{staticClass:"left",attrs:{to:"/",tag:"a"}},[a("i",{staticClass:"icon-arrow-left"})]),a("div",{staticClass:"center"},[t._v("我的预约")])],1),a("div",{attrs:{id:"wrap"}},[a("div",{staticClass:"swiper-container-order swiper-container-list"},[a("div",{staticClass:"orderBOX swiper-wrapper"},[a("div",{staticClass:"shuju"},[t._v("下拉刷新")]),a("div",{staticClass:"swiper-slide swiper-slide-order"},[t._l(t.lists,function(e,s){return a("div",{staticClass:"order_list"},[1!=e.type?a("div",{staticClass:"mark",attrs:{type:2}}):t._e(),1==e.type?a("div",{staticClass:"mark",attrs:{type:1}}):t._e(),[a("div",{staticClass:"head"},[t._v(t._s(t._f("status_value")(e.status)))])],1==e.type?[1!=e.status_code?[a("div",{staticClass:"head"},[t._v("预约时间："+t._s(t._f("time_format")(e.start_time)))])]:t._e()]:t._e(),a("div",{staticClass:"main",on:{click:function(a){t.det(e.id)}}},[a("div",{staticClass:"doctors"},[e.doctor.data.photoSUrl?a("div",{staticClass:"avatar",style:t.bg(e.doctor.data.photoSUrl)}):a("div",{staticClass:"avatar",style:t.bg("/img/doctor_default.png")}),a("h3",[t._v(t._s(e.doctor.data.name))]),a("p",[t._v(t._s(e.doctor.data.titleName))])])]),1!=e.type&&10==e.status?a("div",{staticClass:"orderTips"},[t._v("温馨提示：您还未支付咨询费，医生将等待您三分钟，还请您及时支付咨询费，三分钟内未支付咨询费，需要重新选择医生咨询。")]):t._e(),1!=e.type&&5==e.status?a("div",{staticClass:"orderTips"},[t._v("温馨提示：请耐心等待医生接诊，医生接诊后泰和国医公众号将发送服务信息给您（请确保您已关注泰和国医公众号，并确保文章推送已开启）。")]):t._e(),a("div",{staticClass:"foot"},[e.order&&e.order.data.pay_amount>0?a("div",{staticClass:"price"},[a("span",[t._v("总价：￥")]),a("b",[t._v(t._s(t._f("price")(e,e)))])]):t._e(),1!=e.type?[5==e.status?a("div",{staticClass:"btn btn-o btn-jv",on:{click:function(e){t.delDeal(s)}}},[t._v("取消预约")]):t._e(),e.status<15?a("div",{staticClass:"btn btn-o btn-jv",on:{click:function(a){t.confirm_order(e.id)}}},[t._v("立即支付")]):t._e(),1==e.type||15!=e.status&&20!=e.status&&25!=e.status?t._e():a("div",{staticClass:"btn btn-o btn-jv",on:{click:function(a){t.chat(e.id)}}},[t._v("开始咨询")])]:[e.order&&2==e.order.data.status?a("div",{staticClass:"btn btn-o disabled"},[t._v("退款中")]):t._e(),10==e.status?a("div",{staticClass:"btn btn-o btn-jv",on:{click:function(a){t.confirm_order(e.id)}}},[t._v("立即支付")]):t._e()]],2)],2)}),a("div",{staticClass:"visible",on:{click:t.loadMore}})],2)])])]),a("div",{staticClass:"layer_pop none"},[a("div",{staticClass:"content"},[a("div",{staticClass:"txt"},[t._v(t._s(t.reminds))]),a("div",{staticClass:"pop_btn clearfix"},[a("div",{staticClass:"p_btn l",on:{click:function(e){t.dodel()}}},[t._v("确定")]),a("div",{staticClass:"p_btn",on:{click:function(e){t.canceldel()}}},[t._v("取消")])])])])])},staticRenderFns:[]}}});