webpackJsonp([20],{128:function(s,t,i){var e=i(11)(i(183),i(205),null,null);s.exports=e.exports},183:function(s,t,i){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var e=i(12);t.default={data:function(){return{vip:localStorage.getItem("vip"),is_tisane:2,is_express:2,weight:0,long:0,tag:0,total:0,markone:0,is_order:0,marktwo:0,medicine:0,country:"",province:"",city:"",district:"",address:"",initiate_price:0,before:0,address_set:"",address_id:0,recipe_self_price:0,recipe_self:[],addresses:[],currentaddress:{},recipe_head:[],medicines:[],clinique:[],goods:[],clinique_set:0,clinique_id:"北京泰和国医店",list:{},order:{status:0},self:[],method:[{id:0,name:"自煎",val:""},{id:1,name:"代煎（ ¥ 10/剂 ）",val:7}],express:[{id:0,name:"自取"},{id:1,name:"快递(邮费到付)"}],user:{},doctor:{},decoction_fee:0}},created:function(){if(this.id=this.$route.query.id,this.getAdd(),this.comment=this.$route.query.comment,this.getRecipe(),this.$route.query.express){var s=this;this.$http.get(this.$store.state.apiUrl+"address/lists").then(function(t){this.currentaddress=t.data.data[t.data.data.length-1],s.address_id=this.currentaddress.id,s.country=this.currentaddress.country,s.province=this.currentaddress.province,s.city=this.currentaddress.city,s.district=this.currentaddress.district,s.address=this.currentaddress.address})}},filters:{recipeStatus:function(s){switch(s.is_price){case 0:case 1:return"未支付";case 2:return"无效";case 3:return"已支付";case 4:return"已发货";case 5:return"已经到货";case 6:return"药方过期";case 7:return"退款中";case 8:return"已退款"}}},methods:{getRecipe:function(){var s=this;this.$http.get(this.$store.state.apiUrl+"prescription/detail/"+this.id+"?include=user,doctor,order").then(function(t){s.getValue(t)})},getValue:function(s){this.list=s.data.data,s.data.data.recipe_head&&(this.recipe_head=s.data.data.recipe_head,1==s.data.data.tisane&&(this.decoction_fee=10*this.recipe_head.sum)),s.data.data.user&&(this.user=s.data.data.user.data,this.doctor=s.data.data.doctor.data),s.data.data.order&&(this.order=s.data.data.order.data,this.order&&(0==this.list.express?this.is_express=0:this.is_express=1,0==this.list.tisane?this.is_tisane=0:this.is_tisane=1,this.order.order_user&&(this.address_id=this.order.address_id,this.country=this.order.order_user.country,this.province=this.order.order_user.province,this.city=this.order.order_user.city,this.district=this.order.order_user.district,this.address=this.order.order_user.address))),this.$route.query.express&&(this.is_express=1),this.$route.query.tisane&&(this.is_tisane=this.$route.query.tisane),s.data.data.medicine_price&&(this.order.pay_time?this.total=(s.data.data.medicine_price+s.data.data.dispensing_price+s.data.data.tisane_price).toFixed(2):this.total=(s.data.data.medicine_price+s.data.data.dispensing_price+this.decoction_fee).toFixed(2))},getClinique:function(){this.$http.get(this.$store.state.apiUrl+"clinique/lists").then(function(s){this.clinique=s.data.data})},buy:function(){if(2==this.is_tisane)return $api.pop("请选择煎药方式"),!1;if(2==this.is_express)return $api.pop("请选择快递方式"),!1;if(1==this.is_express&&!this.address_id)return $api.pop("请选择地址"),!1;0==this.is_express&&(this.address_id=0);var s=this;0==this.tag&&(this.tag=1,this.$http({url:this.$store.state.apiUrl+"prescription/order/"+this.id,method:"GET",params:{tisane:s.is_tisane,express:s.is_express,address_id:s.address_id}}).then(function(t){s.tag=0,t.data.status?s.$store.state.tcmuser?s.$router.push({path:"/payment/recipe",query:{order_id:t.data.data.order_id}}):window.location.href="/wechat/payment/recipe?order_id="+t.data.data.order_id:$api.pop(t.data.msg)},function(s){i.i(e.b)(s.data.data.errors)}))},getAdd:function(){var s=this;this.$http.get(this.$store.state.apiUrl+"address/lists").then(function(t){this.addresses=t.data.data,this.long=this.addresses.length,this.addresses.forEach(function(t){1==t.is_default&&(s.address_id=t.id,s.country=t.country,s.province=t.province,s.city=t.city,s.district=t.district,s.address=t.address)})})},addressPop:function(){this.order.status||(this.is_express=1,this.before=this.initiate_price,$("#addressSet").show())},comment:function(s){this.$router.push({path:"/tickling",query:{id:s}})},setClinique:function(s,t,i){this.clinique_set=s,this.clinique_id=t+i,console.log(this.clinique_id)},add:function(){if(0==this.user_id)return this.$router.push({path:"/sign"}),!1;this.$router.push({path:"/my_address/my/medicine/id?medicineid="+this.id+"&tisane="+this.is_tisane})},setAddr:function(s,t){this.before=this.initiate_price,this.address_set=s,this.address_id=t,this.country=this.addresses[s].country,this.province=this.addresses[s].province,this.city=this.addresses[s].city,this.district=this.addresses[s].district,this.address=this.addresses[s].address},setMethod:function(s){this.is_tisane=s,this.is_tisane?($(".jy_fy").removeClass("none"),this.weight=this.list.recipe_head.sum*this.list.recipe_head.takingNum*200):($(".jy_fy").addClass("none"),this.weight=this.list.recipe_head.sum*this.list.recipe_head.sumWeight),this.is_tisane?this.decoction_fee=10*this.recipe_head.sum:this.is_tisane||(this.decoction_fee=0),this.total=(this.list.medicine_price+this.list.dispensing_price+this.decoction_fee).toFixed(2),console.log(this.weight)},logisticChoose:function(){if(2==this.is_tisane)return $api.pop("请选择煎药方式"),!1;this.order.status||$("#addressSet").fadeIn()},expressMethod:function(s){if(2==this.is_tisane)return $api.pop("请选择煎药方式"),!1;this.is_express=s,1==this.is_express?($(".mendian").addClass("none"),0==this.marktwo&&(this.marktwo=1)):0==this.is_express&&($(".mendian").removeClass("none"),1==this.marktwo&&(this.total=(this.total-this.initiate_price).toFixed(2),this.marktwo=0))},bg:function(s){if(s)return"background-image:url("+s+")"},check:function(s,t,i){$(s.currentTarget).toggleClass("active"),$(".zb").eq(i).hasClass("active")?(this.recipe_self.push(t.name),this.total=(this.total-t.price*t.weight).toFixed(2),this.recipe_self_price=this.recipe_self_price+t.price*t.weight):(this.recipe_self.remove(t.name),this.total=this.total+t.price*t.weight.toFixed(2),this.recipe_self_price=this.recipe_self_price-t.price*t.weight),console.log(this.recipe_self),console.log(this.recipe_self_price)},showpdf:function(){this.$router.push({path:"/pdfone",query:{total:this.recipe_head.sum,day:this.recipe_head.dayNum,time:this.recipe_head.takingNum}})}}},$(".list .main li").click(function(){$(this).addClass("active").siblings().removeClass()})},205:function(s,t){s.exports={render:function(){var s=this,t=s.$createElement,i=s._self._c||t;return i("div",{staticClass:"cfpy_noprice",attrs:{id:"wrap"}},[s._m(0),i("div",{staticClass:"panel"},[i("div",{staticClass:"p"},[i("span",[s._v("［西医诊断］"+s._s(s.list.disease_en))])]),i("div",{staticClass:"p"},[i("span",[s._v("［中医辨证］")]),s._v(s._s(s.list.disease))])]),i("div",{staticClass:"panel prescription"},[i("div",{staticClass:"item"},[i("h3",[s._v("处方")]),i("p"),s._l(s.list.recipe,function(t){return i("div",{staticClass:"yaofang"},[i("span",[s._v(s._s(t.name))]),5==s.order.status?i("span",[s._v("("+s._s(t.dosage)+s._s(t.unit)+")")]):s._e()])}),i("p"),i("div",{staticClass:"name"},[i("span",[s._v(s._s(s.doctor.name))])])],2),i("div",{staticClass:"item"},[i("h3",[s._v("剂数及用药方法")]),i("p",[s._v("共"+s._s(s.recipe_head.sum)+"剂，一日"+s._s(s.recipe_head.dayNum)+"剂，一剂"+s._s(s.recipe_head.takingNum)+"次")])]),i("div",{staticClass:"item"},[i("h3",[s._v("医嘱")]),i("p",[s._v(s._s(s.list.recipe_remark))])]),i("div",{staticClass:"foot clearfix"},[i("div",{staticClass:"left"},[s._v("药费：")]),i("div",{staticClass:"right"},[s._v("¥ "+s._s(s.list.medicine_price))])]),s.list.dispensing_price>0?i("div",{staticClass:"foot clearfix"},[i("div",{staticClass:"left"},[s._v("调剂费：")]),i("div",{staticClass:"right"},[s._v("¥ "+s._s(s.list.dispensing_price))])]):s._e()]),[i("div",{staticClass:"panel list jyfs",attrs:{id:"tisane"}},[i("div",{staticClass:"head"},[s._v("煎药方式")]),5==s.order.status?i("ul",{staticClass:"main"},s._l(s.method,function(t,e){return i("li",{class:s.is_tisane==t.id?"active":""},[i("i",{staticClass:"icon-check"}),i("span",[s._v(s._s(t.name))]),1==t.id?i("div",{staticClass:"val"},[s._v("X "+s._s(s.recipe_head.sum))]):s._e()])})):i("ul",{staticClass:"main"},s._l(s.method,function(t,e){return i("li",{class:s.is_tisane==t.id?"active":"",on:{click:function(i){s.setMethod(t.id)}}},[i("i",{staticClass:"icon-check"}),i("span",[s._v(s._s(t.name))]),1==t.id?i("div",{staticClass:"val"},[s._v("X "+s._s(s.recipe_head.sum))]):s._e()])})),1==s.is_tisane?i("div",{staticClass:"foot clearfix jy_fy"},[i("div",{staticClass:"left"},[s._v("煎药费用：")]),i("div",{staticClass:"right"},[s._v("¥ "+s._s(10*s.recipe_head.sum))])]):s._e(),i("ul",{staticClass:"main"},[i("li",{staticClass:"jianyaotips",on:{click:function(t){s.showpdf()}}},[s._v("汤药煎法指南")])])]),i("div",{staticClass:"pop",attrs:{id:"addressSet"}},[i("div",{staticClass:"box"},[i("div",{staticClass:"head dz"},[i("span",[s._v("选择地址")]),i("a",{on:{click:function(t){s.add()}}},[s._v("添加地址")])]),i("div",{staticClass:"main"},[s.long>0?i("ul",s._l(s.addresses,function(t,e){return i("li",{class:{active:s.address_id==t.id},on:{click:function(i){s.setAddr(e,t.id)}}},[i("h4",[s._v(s._s(t.country)+"  "+s._s(t.mobile))]),i("p",[s._v(s._s(t.province)+s._s(t.city)+s._s(t.district)+s._s(t.address))]),i("i",{staticClass:"icon-check-c"})])})):i("ul",[i("li",[i("h3",[i("span",[s._v("尚未添加收货地址，")]),i("a",{on:{click:function(t){s.add()}}},[s._v("现在添加")])])])])]),i("div",{staticClass:"foot",attrs:{onclick:"$('#addressSet').fadeOut()"}},[s._v("确定")])])]),i("div",{staticClass:"panel list"},[i("div",{staticClass:"head",attrs:{id:"express"}},[s._v("取药方式")]),5==s.order.status?i("ul",{staticClass:"main"},s._l(s.express,function(t,e){return i("li",{class:s.is_express==t.id?"active":""},[i("i",{staticClass:"icon-check"}),i("span",[s._v(s._s(t.name))])])})):i("ul",{staticClass:"main"},s._l(s.express,function(t,e){return i("li",{class:s.is_express==t.id?"active":"",on:{click:function(i){s.expressMethod(t.id)}}},[i("i",{staticClass:"icon-check"}),i("span",[s._v(s._s(t.name))])])})),1==s.is_express?i("div",{staticClass:"foot clearfix",on:{click:s.logisticChoose}},[i("div",{staticClass:"left"},[s._v("送至：")]),i("div",{staticClass:"right",on:{click:s.addressPop}},[s.country?i("span",[s._v(s._s(s.country)+s._s(s.province)+s._s(s.city)+s._s(s.district)+s._s(s.address))]):i("span",[s._v("添加地址")]),i("i",{staticClass:"icon-arrow-right"})])]):s._e(),1==s.is_express?i("div",{staticClass:"adresstips"},[s._v("请确认收货地址，支付后地址不能更改！")]):s._e()])],1!=s.list.is_send||0!=s.is_order&&0!=s.order.pay_status||1!=s.list.is_price?s._e():i("div",{staticClass:"panel list"}),s.order.pay_status>2?i("div",{staticClass:"panel order_sta"},[i("dl",[i("dt",[s._v("订单编号：")]),i("dd",[s._v(s._s(s.order.order_sn))])]),s.order.pay_status>2?i("dl",[i("dt",[s._v("支付时间：")]),i("dd",[s._v(s._s(s.order.pay_time))])]):s._e(),i("dl",[i("dt",[s._v("订单状态：")]),i("dd",[s._v(s._s(s._f("recipeStatus")(s.list,s.list)))])])]):s._e(),6==s.list.is_price?i("div",{staticClass:"tips"},[s._m(1),i("span",[s._v(" 药方订单已过期")])]):s._e(),i("div",{staticClass:"btn btn-fix nofixed"},[s.order.pay_time?i("div",{staticClass:"left"},[i("span",[s._v("总计：￥")]),i("div",{staticClass:"comfirm_order"},[s._v(s._s(s.order.amount))])]):i("div",{staticClass:"left"},[i("span",[s._v("总计：")]),i("b",[s._v("￥"+s._s(s.total))]),i("div",{staticClass:"comfirm_order",on:{click:s.buy}},[s._v("确认订单")])])])],2)},staticRenderFns:[function(){var s=this,t=s.$createElement,i=s._self._c||t;return i("header",[i("div",{staticClass:"left",attrs:{onclick:"back()"}},[i("i",{staticClass:"icon-arrow-left"})]),i("div",{staticClass:"center"},[s._v("我的药方")])])},function(){var s=this,t=s.$createElement,i=s._self._c||t;return i("div",{staticClass:"icon-tit"},[i("i")])}]}}});