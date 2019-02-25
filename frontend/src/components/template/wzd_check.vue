<template lang='jade'>
    .fixbody
        header
            .left(onclick="back()" @click="reload()")
                i.icon-arrow-left
            .center 填写问诊单
        #wrap
            .panel.step2
                h3(v-if='info.question') {{order+1}}、{{info.question}}
                h3(v-else)
                .main
                    label.inp(v-for="an in answers" @click="store(an.id)")
                        //input(type='checkbox' v-model='checked' v-bind:value="an.id" v-bind:id="an.id")
                        .icon-check(v-bind:id="an.id")
                        span {{an.answer}}
                    label(v-if="info.other>0")
                        input(type="text" name="other" placeholder='请点此输入其他' v-model="other")
                .btn.btn-block(@click="next()") 下一题

</template>
<script>
    export default {
        created(){
            this.id = this.$route.query.id;
            this.pre_id = this.$route.query.pre_id;
            if(this.id){
                this.getQuestion(this.id);
            }
        },
        data(){
            return {
                info: {},
                id: 0, // 问题id
                necessary: 0,
                answers: {},
                other: '',
                checked: [],
                order: 0,
                pre_id: 0, //上次添加的问题答案id
                next_id: 0,
                type: 0,
                tag: 0
            }
        },
        methods:{
            getQuestion:function (id) {
                this.id = id;
                var obj = {};
                var self = this;
                obj.qid = id;
                obj.pre_id = this.pre_id;
                this.$http.post(self.$store.state.apiUrl+'question/show',obj).then(function(res){
                    var info = res.data.data;
                    self.info = info;
                    self.order = info.order;
                    self.necessary = info.necessary;
                    self.answers = info.answers;
                    if(info.checked){
                        self.checked = info.checked;
                        for(var i=0; i<info.checked.length;i++){
                            $("#"+info.checked[i]).addClass('active');
                        }
                    }
                })
            },
            next(){
                console.log(this.checked);
                var obj = {};
                var _this = this;
                obj.qid = this.id;
                obj.order = this.order;//找下一题
                obj.other = this.other;
                obj.checked = this.checked;
                obj.necessary = this.necessary;
                obj.pre_id = this.pre_id;
                if(_this.checked.length==0 && _this.other==''){
                    if(_this.necessary>0){
                        $api.pop('题目为必答题'); return false;
                    }
                }
                if(this.tag==0){
                    _this.$http.post(_this.$store.state.apiUrl+'answer/check',obj).then(function (res){
                        _this.tag = 1;
                        if(res.data){
                            _this.next_id = res.data.data.next_id;
                            _this.type = res.data.data.qtype;
                            if(!_this.next_id){
                                _this.$router.push({path:'/my_order/my/'});
                            }
                            _this.tag = 0;
                            $('.icon-check').removeClass('active');
                        }

                    })
                }
            },
            store(id){
                var _this = this;
                if (_this.checked.indexOf(id) > -1) {
                    for (var i = 0; i < _this.checked.length; i++) {
                        if (_this.checked[i] == id) {
                            _this.checked.splice(i, 1);
                            $("#"+id).removeClass('active');
                            break;
                        }
                    }
                } else {
                    _this.checked.push(id);
                    $("#"+id).addClass('active');
                }
            },
            dohref(){
                var self = this;
                if(self.type==0){
                    self.$router.push({path:'/wzd_radio' ,query: { id: self.next_id, pre_id: self.pre_id }});
                }else if(self.type==1){
                    self.reload();
                    self.$router.push({path:'/wzd_check' ,query: { id: self.next_id, pre_id: self.pre_id }});
                }else if(self.type==2){
                    self.$router.push({path:'/wzd_fill' ,query: { id: self.next_id, pre_id: self.pre_id }});
                }else{
                    self.$router.push({path:'/my_order/my/'});
                }
            },
            reload(){
                setTimeout(function(){
                    location.reload();
                },200);
            }
        },
        watch:{
            id(newValue){
                $('input[type="checkbox"]').prop('checked',false);
                this.checked = [];
                this.other = '';
                this.getQuestion(newValue);
            },
            next_id(value){
                this.dohref();
            }
        }
    };
</script>