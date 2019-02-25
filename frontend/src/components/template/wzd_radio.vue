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
                    label.radio(v-for="a in answers" @click='choose(a.id)')
                        //input(type="radio" v-model='checked' v-bind:value="a.id" v-bind:id="a.id")
                        .icon-check(v-bind:id="a.id")
                        span {{a.answer}}
                    label(v-if="info.other>0")
                        input(type="text" name="other" placeholder='请点此输入其他' v-model="other")
                .btn.btn-block(@click="nextQuestion()") 下一题

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
                order: 0,
                answers: {},
                checked: 0, //被选中的选项
                other: '',
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
                this.$http.post(this.$store.state.apiUrl+'question/show',obj).then(function(res){
                    var info = res.data.data;
                    self.info = info;
                    self.answers = info.answers;
                    self.order = info.order;
                    self.necessary = info.necessary;
                    if(info.checked){
                        self.checked = info.checked[0];
                        $("#"+info.checked[0]).addClass('active');
                    }
                })
            },
            nextQuestion(){
                console.log(this.checked);
                var obj = {};
                var self = this;
                obj.qid = this.id;
                obj.order = this.order;
                obj.necessary = this.necessary;
                obj.pre_id = this.pre_id;
                if(this.checked>0 && this.other){
                    $api.pop('题目类型为单选'); return false;
                }
                obj.aid = this.checked;
                obj.other = this.other;
                if(!this.checked && !this.other){
                    if(this.necessary>0){
                        $api.pop('题目为必答题'); return false;
                    }
                }
                if(this.tag==0){
                    self.$http.post(this.$store.state.apiUrl+'answer/radio',obj).then(function (res){
                        self.tag = 1;
                        if(res.data){
                            self.type = res.data.data.qtype;
                            self.next_id = res.data.data.next_id;
                            if(!res.data.data.next_id){
                                self.$router.push({path:'/my_order/my/'});
                            }
                        }
                        self.tag = 0;
                        $('.radio .icon-check').removeClass('active');
                    })
                }

            },
            dohref(){
                var self = this;
                if(self.type==0){
                    self.reload();
                    self.$router.push({path:'/wzd_radio' ,query: { id: self.next_id, pre_id: self.pre_id }});
                }else if(self.type==1){
                    self.$router.push({path:'/wzd_check' ,query: { id: self.next_id, pre_id: self.pre_id }});
                }else if(self.type==2){
                    self.$router.push({path:'/wzd_fill' ,query: { id: self.next_id, pre_id: self.pre_id }});
                }else{
                    self.$router.push({path:'/my_order/my/'});
                }
            },
            choose(id){
                this.checked = id;
                $('.radio .icon-check').removeClass('active');
                $('#'+id).addClass('active');
            },
            reload(){
                setTimeout(function(){
                    location.reload();
                },200);
            }
        },
        watch:{
            id(newValue){
                this.checked = 0;
                this.other = '';
                $('input[type="radio"]').prop('checked',false);
                this.getQuestion(newValue);
            },
            other(newValue){
                this.checked = 0;
                $('input[type="radio"]').prop('checked',false)
            },
            checked(){
                this.other = '';
            },
            next_id(value){
                this.dohref();
            }
        }
    };
</script>
