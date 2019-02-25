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
                    label
                        textarea(placeholder='请点此输入' v-model="answer")
                .btn.btn-block(v-if="id==33",@click='next()') 完成
                .btn.btn-block(v-else,@click='next()') 下一题

</template>
<script>
    export default {
        created(){
            this.id = this.$route.query.id; // 题目id
            this.pre_id = this.$route.query.pre_id;
            if(this.id){
                this.getQuestion(this.id);
            }
        },
        data(){
            return {
                info: {},
                id: 0, // 题目id
                pre_id: 0, //上次添加的问题答案id
                order: 0,
                answer: '',
                necessary: '',
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
                this.$http.post(this.$store.state.apiUrl+'question/show',obj).then(function(res){
                    var info = res.data.data;
                    self.info = info;
                    self.order = info.order;
                    self.necessary = info.necessary;
                    if(info.answer){
                        self.answer = info.answer;
                    }
                })
            },
            next(){
                var obj = {};
                var self = this;
                obj.qid = this.id;
                obj.order = this.order;// 为了获取下一题
                obj.answer = this.answer;
                obj.necessary = this.necessary;
                obj.pre_id = this.pre_id;
                if(this.answer=='' && this.necessary>0){
                    $api.pop('题目类型为必填'); return false;
                }
                if(this.tag==0){
                    self.$http.post(self.$store.state.apiUrl+'answer/fill',obj).then(function (res){
                        self.tag=1;
                        if(res.data){
                            self.next_id = res.data.data.next_id;
                            self.type = res.data.data.qtype;
                            if(!self.next_id){
                                self.$router.push({path:'/my_order/my/'});
                            }
                            self.tag = 0;
                        }
                    })
                }
            },
            dohref(){
                var self = this;
                if(self.type==0){
                    self.$router.push({path:'/wzd_radio' ,query: { id: self.next_id, pre_id: self.pre_id }});
                }else if(self.type==1){
                    self.$router.push({path:'/wzd_check' ,query: { id: self.next_id, pre_id: self.pre_id }});
                }else if(self.type==2){
                    self.reload();
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
                self.answer = '';
                this.getQuestion(newValue);
            },
            next_id(value){
                this.dohref();
            }
        }
    };
</script>