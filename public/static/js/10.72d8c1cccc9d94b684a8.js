webpackJsonp([10],{141:function(t,e,i){var s=i(11)(i(194),i(222),null,null);t.exports=s.exports},194:function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={created:function(){this.id=this.$route.query.id,this.pre_id=this.$route.query.pre_id,this.id&&this.getQuestion(this.id)},data:function(){return{info:{},id:0,necessary:0,order:0,answers:{},checked:0,other:"",pre_id:0,next_id:0,type:0,tag:0}},methods:{getQuestion:function(t){this.id=t;var e={},i=this;e.qid=t,e.pre_id=this.pre_id,this.$http.post(this.$store.state.apiUrl+"question/show",e).then(function(t){var e=t.data.data;i.info=e,i.answers=e.answers,i.order=e.order,i.necessary=e.necessary,e.checked&&(i.checked=e.checked[0],$("#"+e.checked[0]).addClass("active"))})},nextQuestion:function(){console.log(this.checked);var t={},e=this;return t.qid=this.id,t.order=this.order,t.necessary=this.necessary,t.pre_id=this.pre_id,this.checked>0&&this.other?($api.pop("题目类型为单选"),!1):(t.aid=this.checked,t.other=this.other,!this.checked&&!this.other&&this.necessary>0?($api.pop("题目为必答题"),!1):void(0==this.tag&&e.$http.post(this.$store.state.apiUrl+"answer/radio",t).then(function(t){e.tag=1,t.data&&(e.type=t.data.data.qtype,e.next_id=t.data.data.next_id,t.data.data.next_id||e.$router.push({path:"/my_order/my/"})),e.tag=0,$(".radio .icon-check").removeClass("active")})))},dohref:function(){var t=this;0==t.type?(t.reload(),t.$router.push({path:"/wzd_radio",query:{id:t.next_id,pre_id:t.pre_id}})):1==t.type?t.$router.push({path:"/wzd_check",query:{id:t.next_id,pre_id:t.pre_id}}):2==t.type?t.$router.push({path:"/wzd_fill",query:{id:t.next_id,pre_id:t.pre_id}}):t.$router.push({path:"/my_order/my/"})},choose:function(t){this.checked=t,$(".radio .icon-check").removeClass("active"),$("#"+t).addClass("active")},reload:function(){setTimeout(function(){location.reload()},200)}},watch:{id:function(t){this.checked=0,this.other="",$('input[type="radio"]').prop("checked",!1),this.getQuestion(t)},other:function(t){this.checked=0,$('input[type="radio"]').prop("checked",!1)},checked:function(){this.other=""},next_id:function(t){this.dohref()}}}},222:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"fixbody"},[i("header",[i("div",{staticClass:"left",attrs:{onclick:"back()"},on:{click:function(e){t.reload()}}},[i("i",{staticClass:"icon-arrow-left"})]),i("div",{staticClass:"center"},[t._v("填写问诊单")])]),i("div",{attrs:{id:"wrap"}},[i("div",{staticClass:"panel step2"},[t.info.question?i("h3",[t._v(t._s(t.order+1)+"、"+t._s(t.info.question))]):i("h3"),i("div",{staticClass:"main"},[t._l(t.answers,function(e){return i("label",{staticClass:"radio",on:{click:function(i){t.choose(e.id)}}},[i("div",{staticClass:"icon-check",attrs:{id:e.id}}),i("span",[t._v(t._s(e.answer))])])}),t.info.other>0?i("label",[i("input",{directives:[{name:"model",rawName:"v-model",value:t.other,expression:"other"}],attrs:{type:"text",name:"other",placeholder:"请点此输入其他"},domProps:{value:t.other},on:{input:function(e){e.target.composing||(t.other=e.target.value)}}})]):t._e()],2),i("div",{staticClass:"btn btn-block",on:{click:function(e){t.nextQuestion()}}},[t._v("下一题")])])])])},staticRenderFns:[]}}});