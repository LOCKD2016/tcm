webpackJsonp([12],{140:function(e,t,i){var s=i(11)(i(193),i(246),null,null);e.exports=s.exports},193:function(e,t,i){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={created:function(){this.id=this.$route.query.id,this.pre_id=this.$route.query.pre_id,this.id&&this.getQuestion(this.id)},data:function(){return{info:{},id:0,necessary:0,answers:{},other:"",checked:[],order:0,pre_id:0,next_id:0,type:0,tag:0}},methods:{getQuestion:function(e){this.id=e;var t={},i=this;t.qid=e,t.pre_id=this.pre_id,this.$http.post(i.$store.state.apiUrl+"question/show",t).then(function(e){var t=e.data.data;if(i.info=t,i.order=t.order,i.necessary=t.necessary,i.answers=t.answers,t.checked){i.checked=t.checked;for(var s=0;s<t.checked.length;s++)$("#"+t.checked[s]).addClass("active")}})},next:function(){console.log(this.checked);var e={},t=this;if(e.qid=this.id,e.order=this.order,e.other=this.other,e.checked=this.checked,e.necessary=this.necessary,e.pre_id=this.pre_id,0==t.checked.length&&""==t.other&&t.necessary>0)return $api.pop("题目为必答题"),!1;0==this.tag&&t.$http.post(t.$store.state.apiUrl+"answer/check",e).then(function(e){t.tag=1,e.data&&(t.next_id=e.data.data.next_id,t.type=e.data.data.qtype,t.next_id||t.$router.push({path:"/my_order/my/"}),t.tag=0,$(".icon-check").removeClass("active"))})},store:function(e){var t=this;if(t.checked.indexOf(e)>-1){for(var i=0;i<t.checked.length;i++)if(t.checked[i]==e){t.checked.splice(i,1),$("#"+e).removeClass("active");break}}else t.checked.push(e),$("#"+e).addClass("active")},dohref:function(){var e=this;0==e.type?e.$router.push({path:"/wzd_radio",query:{id:e.next_id,pre_id:e.pre_id}}):1==e.type?(e.reload(),e.$router.push({path:"/wzd_check",query:{id:e.next_id,pre_id:e.pre_id}})):2==e.type?e.$router.push({path:"/wzd_fill",query:{id:e.next_id,pre_id:e.pre_id}}):e.$router.push({path:"/my_order/my/"})},reload:function(){setTimeout(function(){location.reload()},200)}},watch:{id:function(e){$('input[type="checkbox"]').prop("checked",!1),this.checked=[],this.other="",this.getQuestion(e)},next_id:function(e){this.dohref()}}}},246:function(e,t){e.exports={render:function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",{staticClass:"fixbody"},[i("header",[i("div",{staticClass:"left",attrs:{onclick:"back()"},on:{click:function(t){e.reload()}}},[i("i",{staticClass:"icon-arrow-left"})]),i("div",{staticClass:"center"},[e._v("填写问诊单")])]),i("div",{attrs:{id:"wrap"}},[i("div",{staticClass:"panel step2"},[e.info.question?i("h3",[e._v(e._s(e.order+1)+"、"+e._s(e.info.question))]):i("h3"),i("div",{staticClass:"main"},[e._l(e.answers,function(t){return i("label",{staticClass:"inp",on:{click:function(i){e.store(t.id)}}},[i("div",{staticClass:"icon-check",attrs:{id:t.id}}),i("span",[e._v(e._s(t.answer))])])}),e.info.other>0?i("label",[i("input",{directives:[{name:"model",rawName:"v-model",value:e.other,expression:"other"}],attrs:{type:"text",name:"other",placeholder:"请点此输入其他"},domProps:{value:e.other},on:{input:function(t){t.target.composing||(e.other=t.target.value)}}})]):e._e()],2),i("div",{staticClass:"btn btn-block",on:{click:function(t){e.next()}}},[e._v("下一题")])])])])},staticRenderFns:[]}}});