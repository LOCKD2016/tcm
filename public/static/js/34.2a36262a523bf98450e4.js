webpackJsonp([34],{112:function(t,i,s){var a=s(11)(s(163),s(244),null,null);t.exports=a.exports},163:function(t,i,s){"use strict";Object.defineProperty(i,"__esModule",{value:!0});var a=s(31),e=s.n(a);i.default={created:function(){this.id=this.$route.query.id,this.clinic_id=this.$route.query.clinic_id,this.message_list_id=this.$route.query.listId,this.getExam(this.id)},mounted:function(){this.shareMessage();var t=this;setTimeout(function(){t.swiper=new Swiper(".swiper-container-list",{autoHeight:!0,direction:"vertical",slidesPerView:"auto",observer:!0,observeParents:!0,mousewheelControl:!0,freeMode:!0,freeModeMomentumRatio:.4,freeModeMomentumVelocityRatio:.2,resistanceRatio:.7,preventLinksPropagation:!0,preventClicksPropagation:!0,preventClicks:!0,onTouchEnd:function(i){var s=i.height,a=i.virtualSize;i.translate<=s-a-50&&i.translate<0&&t.statusOrder&&($(".visible").html("正在加载..."),t.shareMessage())}})},400)},data:function(){return{info:{},options:{},id:0,answer:"",type:"",must:0,next_id:0,exam_id:0,clinic_id:0,tag:0,is_over:0,doctorName:"",read_only:0,message_list_id:0,images:0}},methods:{autoHeight:function(t){t.target.style.height=t.target.scrollHeight+"px"},submit:function(){this.info.message_list_id=this.message_list_id,this.info.clinic_id=this.clinic_id,this.info.exam_id=this.id,this.info.option.data=this.options,this.$http.post(this.$store.state.apiUrl+"exam/answer",this.info).then(function(t){$api.pop(t.data.msg),t.data.status&&this.$router.back()},function(t){errorMsg(t.data.data.errors)})},getExam:function(t){this.$http.get(this.$store.state.apiUrl+"exam/detail/"+t+"?include=option&clinic_id="+this.clinic_id).then(function(t){t.data.status?(this.info=t.data.data,this.options=t.data.data.option.data):$api.pop(t.data.msg)},function(t){errorMsg(t.data.data.errors)})},choose:function(t,i,s,a){if("radio"==s)$(a.currentTarget).siblings(".radio").children(".icon-check").removeClass("active"),this.options[i].answers=t;else if(this.options[i].answers||(this.options[i].answers=[]),this.options[i].answers.indexOf(t)>-1){var e=this.options[i].answers.indexOf(t);this.options[i].answers.splice(e,1)}else this.options[i].answers.push(t)},bg:function(t){if(t)return"background-image:url("+t+");background-size:100% 100%;"},appUpload:function(t,i,s,a){var e=this;AppUpload(function(n){n&&n.status?(e.options[t].answers?e.options[t].answers[i]=n.data.image_url:(e.options[t].answers=[],e.options[t].answers[i]=n.data.image_url),$("#"+s+"photo"+i).css("background-image","url("+n.data.image_url+")"),5!=e.options[t].answers.length&&5!=e.images||$(".imgbtn").css("display","none"),"icon-camera"==a&&e.images++):$api.pop("上传失败")})},wxUpload:function(t,i,s,a){var n=a.target.className;if("icon-camera"==n&&this.images>4)return $(".layer_pop").removeClass("none"),!1;if(this.$store.state.tcmuser)return void this.appUpload(t,i,s,n);if(!this.$store.state.wxready)return $api.pop("微信上传未就绪，稍等"),!1;var o=this;wx.ready(function(){if(!wx.chooseImage)return $api.pop("微信上传未就绪，稍等"),!1;wx.chooseImage({count:1,sizeType:["original","compressed"],sourceType:["album","camera"],success:function(a){var c=a.localIds;wx.uploadImage({localId:c[0],isShowProgressTips:1,success:function(a){var e=a.serverId;o.wxUploadId(e,t,i,s,n)},fail:function(t){alert(e()(t))}})},fail:function(t){alert("选图失败"+t.errMsg+",请重试"),setTimeout(function(){location.reload()},200)}})})},wxUploadId:function(t,i,s,a,e){var n=this;$("body").addClass("loading"),this.$http.post(this.$store.state.apiUrl+"wxupload",{serverId:t}).then(function(t){$("body").removeClass("loading");var o=t.data;o.status?(n.options[i].answers?n.options[i].answers[s]=o.data:(n.options[i].answers=[],n.options[i].answers[s]=o.data),$("#"+a+"photo"+s).css("background-image","url("+o.data+")"),5!=n.options[i].answers.length&&5!=n.images||$(".imgbtn").css("display","none"),"icon-camera"==e&&n.images++):$api.pop(t.data.msg)})},cancel:function(t,i,s,a){this.options[t].answers.splice(i,1),this.images--,$(a.target.parentNode).css("display","none"),this.images<5&&$(".imgbtn").css("display","inline-block")},dodel:function(){$(".layer_pop").addClass("none")},shareMessage:function(){if(!this.$store.state.tcmuser){var t=this,i="【泰和国医】 请您填写问诊单";1==this.read_only&&(i="【泰和国医】 请您查看问诊单"),wx.ready(function(){var s="/wechat/exam/exam_fill?id="+t.exam_id+"&familyId="+t.family_id+"&clinicId="+t.clinic_id+"&questionId="+t.question_id;wx.onMenuShareTimeline({title:i,link:protocol+window.location.host+s,imgUrl:protocol+window.location.host+"/static/img/wxlogo.png",success:function(){},cancel:function(){},fail:function(t){alert(e()(t))}}),wx.onMenuShareAppMessage({title:i,desc:"这是一款有中医思维的问诊单，诚意推荐给您。【泰和国医】出品",link:protocol+window.location.host+s,imgUrl:protocol+window.location.host+"/static/img/wxlogo.png",success:function(){},cancel:function(){},fail:function(t){alert(e()(t))}})})}}},watch:{$route:function(){this.id=this.$route.query.id,this.getQuestion(this.id)}}}},244:function(t,i){t.exports={render:function(){var t=this,i=t.$createElement,s=t._self._c||i;return s("div",{staticClass:"fixbody"},[s("header",[t._m(0),s("div",{staticClass:"center"},[t._v(t._s(t.info.title))])]),s("div",{attrs:{id:"wrap"}},[s("div",{staticClass:"panel step2"},t._l(t.options,function(i,a){return s("div",[t.info.title?s("span",{staticClass:"title"},[t._v(t._s(a+1)+"、"+t._s(i.title))]):t._e(),"text"==i.type?s("div",{staticClass:"main",attrs:{id:a+1}},[s("label",[s("textarea",{directives:[{name:"model",rawName:"v-model",value:i.answers,expression:"op.answers"}],attrs:{placeholder:"请点此输入"},domProps:{value:i.answers},on:{keydown:function(i){t.autoHeight(i)},input:function(s){s.target.composing||t.$set(i,"answers",s.target.value)}}})])]):t._e(),"checkbox"==i.type?s("div",{staticClass:"main",attrs:{id:a+1}},t._l(i.option,function(e,n){return s("label",{staticClass:"inp",on:{click:function(i){t.choose(e.title,a,"checkbox",i)}}},[s("div",{staticClass:"icon-check",class:{active:i.answers.indexOf(e.title)>-1}}),s("span",[t._v(t._s(e.title))])])})):t._e(),"radio"==i.type?s("div",{staticClass:"main",attrs:{id:a+1}},t._l(i.option,function(e,n){return s("label",{staticClass:"radio",on:{click:function(i){t.choose(e.title,a,"radio",i)}}},[s("div",{staticClass:"icon-check",class:{active:e.title==i.answers}}),s("span",{staticClass:"sex"},[t._v(t._s(e.title))])])})):t._e(),"photo"==i.type?s("div",{staticClass:"main p_exam",attrs:{id:a+1}},[s("ul",{staticClass:"img_box"},[i.answers[0]||t.images>=1?s("li",[s("div",{staticClass:"img",style:t.bg(i.answers[0]),attrs:{id:i.id+"photo0"},on:{click:function(s){t.wxUpload(a,0,i.id,s)}}}),s("i",{staticClass:"cancel",on:{click:function(s){t.cancel(a,0,i.id,s)}}})]):t._e(),i.answers[1]||t.images>=2?s("li",[s("div",{staticClass:"img",style:t.bg(i.answers[1]),attrs:{id:i.id+"photo1"},on:{click:function(s){t.wxUpload(a,1,i.id,s)}}}),s("i",{staticClass:"cancel",on:{click:function(s){t.cancel(a,1,i.id,s)}}})]):t._e(),i.answers[2]||t.images>=3?s("li",[s("div",{staticClass:"img",style:t.bg(i.answers[2]),attrs:{id:i.id+"photo2"},on:{click:function(s){t.wxUpload(a,2,i.id,s)}}}),s("i",{staticClass:"cancel",on:{click:function(s){t.cancel(a,2,i.id,s)}}})]):t._e(),i.answers[3]||t.images>=4?s("li",[s("div",{staticClass:"img",style:t.bg(i.answers[3]),attrs:{id:i.id+"photo3"},on:{click:function(s){t.wxUpload(a,3,i.id,s)}}}),s("i",{staticClass:"cancel",on:{click:function(s){t.cancel(a,3,i.id,s)}}})]):t._e(),i.answers[4]||5==t.images?s("li",[s("div",{staticClass:"img",style:t.bg(i.answers[4]),attrs:{id:i.id+"photo4"},on:{click:function(s){t.wxUpload(a,4,i.id,s)}}}),s("i",{staticClass:"cancel",on:{click:function(s){t.cancel(a,4,i.id,s)}}})]):t._e(),s("li",{staticClass:"imgbtn"},[s("i",{staticClass:"icon-camera",on:{click:function(s){t.wxUpload(a,t.images,i.id,s)}}})])])]):t._e()])})),s("div",{staticClass:"btn btn-block",on:{click:function(i){t.submit()}}},[t._v("提交")])]),s("div",{staticClass:"layer_pop none"},[s("div",{staticClass:"content"},[s("div",{staticClass:"txt"},[t._v("最多只能上传五张照片")]),s("div",{staticClass:"pop_btn clearfix"},[s("div",{staticClass:"p_btn l",staticStyle:{width:"100%"},on:{click:function(i){t.dodel()}}},[t._v("确定")])])])])])},staticRenderFns:[function(){var t=this,i=t.$createElement,s=t._self._c||i;return s("div",{staticClass:"left",attrs:{onclick:"back()"}},[s("i",{staticClass:"icon-arrow-left"})])}]}}});