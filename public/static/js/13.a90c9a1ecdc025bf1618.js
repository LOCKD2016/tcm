webpackJsonp([13],{139:function(t,i,a){var s=a(11)(a(192),a(227),null,null);t.exports=s.exports},192:function(t,i,a){"use strict";Object.defineProperty(i,"__esModule",{value:!0});var s=a(32),e=a.n(s);i.default={created:function(){this.getFamily(),this.id=this.$route.params.id},data:function(){return{patient_set:0,patient:[],pic_mb:"",pic_st:"",patient_id:0,id:0,long:0,not_ready:0}},methods:{appUpload:function(t){var i=this;AppUpload(function(a){a&&a.status?"md"==t?i.pic_mb=a.data.image_url:i.pic_st=a.data.image_url:$api.pop("上传失败")})},wxUpload:function(t){if(this.$store.state.tcmuser)return void this.appUpload(t);if(!this.$store.state.wxready)return void $api.pop("微信上传未就绪，稍等");var i=this;wx.chooseImage({count:1,sizeType:["original","compressed"],sourceType:["album","camera"],success:function(a){var s=a.localIds;wx.uploadImage({localId:s[0],isShowProgressTips:1,success:function(a){var s=a.serverId;i.wxUploadId(s,t)},fail:function(t){alert(e()(t))}})},fail:function(t){alert("选图失败"+t.errMsg+",请重试"),setTimeout(function(){location.reload()},200)}})},wxUploadId:function(t,i){$("body").addClass("loading"),this.$http.post(this.$store.state.apiUrl+"wxupload",{serverId:t}).then(function(t){$("body").removeClass("loading");var a=t.data;a.status?"md"==i?this.pic_mb=a.data:this.pic_st=a.data:$api.pop("上传失败")})},bg:function(t){if(t)return"background-image:url("+t+")"},setPat:function(t,i){this.patient_set=t,this.patient_id=i},toOrder:function(t){this.$router.push({path:"/doctor/"+t,query:{id:this.$route.query.id}})},upImg:function(){var t=this;layui.use("upload",function(){layui.upload({url:"/upload/wechat",elem:"#mb",method:"post",before:function(t){$("body").addClass("loading")},success:function(i){$("body").removeClass("loading"),t.pic_mb=i.data}})})},upTp:function(){var t=this;layui.use("upload",function(){layui.upload({url:"/upload/wechat",elem:"#sb",method:"post",before:function(t){$("body").addClass("loading")},success:function(i){$("body").removeClass("loading"),t.pic_st=i.data}})})},submitInfo:function(){if(!this.pic_mb)return $api.pop("面部照片不能为空"),!1;if(!this.pic_st)return $api.pop("舌部照片不能为空"),!1;if(1==this.not_ready)return $api.pop("请选择就诊人"),!1;$("body").addClass("loading");var t=this,i={};i.family_id=this.patient_id,i.face_img=this.pic_mb,i.tongue_img=this.pic_st,i.order_id=this.id,this.$http.post(this.$store.state.apiUrl+"answer/store",i).then(function(i){var a=i.data.data.next_id,s=i.data.data.qtype,e=i.data.data.pre_id;0==s?t.$router.push({path:"/wzd_radio",query:{id:a,pre_id:e}}):1==s?t.$router.push({path:"/wzd_check",query:{id:a,pre_id:e}}):2==s?t.$router.push({path:"/wzd_fill",query:{id:a,pre_id:e}}):t.$router.push({path:"/my_order/my/"}),$("body").removeClass("loading")})},getFamily:function(){this.$http({url:this.$store.state.apiUrl+"familylist",method:"GET"}).then(function(t){this.patient=t.data.data,this.patient_id=this.patient[0].id,this.patient[0].realname?this.long=this.patient.length:this.not_ready=1})},add:function(){1==this.not_ready?this.$router.push({path:"/my_fmld/my"}):this.$router.push({path:"/my_myfml/my"})}}}},227:function(t,i){t.exports={render:function(){var t=this,i=t.$createElement,a=t._self._c||i;return a("div",{staticClass:"fixbody"},[t._m(0),a("div",{staticClass:"pop",attrs:{id:"patSet"}},[a("div",{staticClass:"box"},[a("div",{staticClass:"head dz"},[a("span",[t._v("选择就诊人")]),t.long>0?a("a",{on:{click:function(i){t.add()}}},[t._v("就诊人管理")]):t._e()]),a("div",{staticClass:"main"},[a("ul",[t.long<1?a("li",[a("h3",[a("span",[t._v("尚未添加就诊人，")]),a("a",{on:{click:function(i){t.add()}}},[t._v("现在添加")])])]):t._l(t.patient,function(i,s){return a("li",{class:t.patient_set==s?"active":"",on:{click:function(a){t.setPat(s,i.id)}}},[a("h4",[t._v(t._s(i.realname))]),a("p",[t._v(t._s(i.sex)+"，"+t._s(i.age)+"岁")]),a("i",{staticClass:"icon-check-c"})])})],2)]),a("div",{staticClass:"foot",attrs:{onclick:"$('#patSet').fadeOut()"}},[t._v("确定")])])]),a("div",{attrs:{id:"wrap"}},[a("div",{staticClass:"step1"},[t._m(1),a("ul",{staticClass:"list-group"},[t.long>0?a("li",{attrs:{onclick:"$('#patSet').fadeIn()"}},[a("span",[t._v("就诊人")]),a("div",{staticClass:"val"},[t._v(t._s(t.patient[t.patient_set].realname)+"， "+t._s(t.patient[t.patient_set].sex)+"，"+t._s(t.patient[t.patient_set].age)+"岁")]),a("i",{staticClass:"icon-arrow-right"})]):a("li",{attrs:{onclick:"$('#patSet').fadeIn()"}},[a("span",[t._v("就诊人")]),a("div",{staticClass:"val"}),a("i",{staticClass:"icon-arrow-right"})]),a("li",{on:{click:function(i){t.wxUpload("md")}}},[a("span",[t._v("面部照片")]),a("div",{staticClass:"val img",style:t.bg(t.pic_mb)}),a("i",{staticClass:"icon-arrow-right"}),a("input",{directives:[{name:"model",rawName:"v-model",value:t.pic_mb,expression:"pic_mb"}],attrs:{type:"hidden",name:"pic_mb"},domProps:{value:t.pic_mb},on:{input:function(i){i.target.composing||(t.pic_mb=i.target.value)}}})]),a("li",{on:{click:function(i){t.wxUpload("sb")}}},[a("span",[t._v("舌苔照片")]),a("div",{staticClass:"val img",style:t.bg(t.pic_st)}),a("i",{staticClass:"icon-arrow-right"}),a("input",{directives:[{name:"model",rawName:"v-model",value:t.pic_st,expression:"pic_st"}],attrs:{type:"hidden",name:"pic_st"},domProps:{value:t.pic_st},on:{input:function(i){i.target.composing||(t.pic_st=i.target.value)}}})])]),a("div",{staticClass:"btn btn-block",on:{click:function(i){t.submitInfo()}}},[t._v("下一步")])])])])},staticRenderFns:[function(){var t=this,i=t.$createElement,a=t._self._c||i;return a("header",[a("div",{staticClass:"left",attrs:{onclick:"back()"}},[a("i",{staticClass:"icon-arrow-left"})]),a("div",{staticClass:"center"},[t._v("填写问诊单")])])},function(){var t=this,i=t.$createElement,a=t._self._c||i;return a("div",{staticClass:"panel top_tip"},[a("p",[t._v("为保证您一次填写成功，请提前预备好两张照片：一张清晨起床未化妆自然光线下的面部照片（面部拍照平视，下巴微收，无闪光灯）清晨起床自然光线下未进食时舌的照片（舌部拍照勿用力伸舌，反复伸舌）")]),a("h3",[t._v("以下人群不适合贴“个性化”三伏贴：")]),a("ul",[a("li",[t._v("心脏病、肾病、肝脏疾病者")]),a("li",[t._v("肺结核、支气管扩张患者")]),a("li",[t._v("孕妇")]),a("li",[t._v("有严重药敏史")]),a("li",[t._v("瘢痕体质或穴位处有皮肤溃损")]),a("li",[t._v("对皮肤暂时留下色素沉着、皮肤起泡很介意者")]),a("li",{staticClass:"none"},[t._v("智力高于60的")])])])}]}}});