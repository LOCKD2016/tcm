<template lang='jade'>
    .fixbody
        header
            .left(onclick="back()")
                i.icon-arrow-left
            .center(v-if='read_only == 1') 个性化问诊单(医生已读)
            .center(v-else) 个性化问诊单
        #wrap
            .panel.step2
                h3(v-if='info.title') {{info.sort}}、{{info.title}}
                h3(v-else)
                .main(v-if='read_only == 1')
                    label.radio(v-for="(a,i) in answers")
                        .icon-check(v-bind:id="'radio'+i")
                        span {{a.val}}
                .main(v-else)
                    label.radio(v-for="(a,i) in answers" @click='choose(a,"radio"+i)')
                        .icon-check(v-bind:id="'radio'+i")
                        span {{a.val}}
                .btn.btn-block(@click="save()") 下一题

</template>
<script>
    export default {
        created(){

        },
        mounted:function(){
            this.clinic_id = this.$route.query.clinicId;
            this.question_id = this.$route.query.questionId;
            this.family_id = this.$route.query.familyId;
            this.exam_id = this.$route.query.id;
            this.getQuestion(this.question_id);
            this.shareMessage();
        },
        data(){
            return {
                info: {},
                must: 0,
                answers: {},
                checked: 0,
                next_id: 0,
                clinic_id: 0,
                quesiton_id: 0,
                exam_id: 0,
                type: '',
                tag: 0,
                is_over: 0,
                doctorName: '',
                read_only: 0
            }
        },

        methods:{
            getQuestion: function (question_id) {
                let obj = {};
                let self = this;
                obj.clinic_id = this.clinic_id;
                obj.exam_id = this.exam_id;
                obj.family_id = this.family_id;
                obj.question_id = question_id;
                this.$http.post(this.$store.state.apiUrl + 'exam/show/', obj).then(function (res) {
                    let data = res.data;
                    let info = data.data;
                    self.info = data.data;
                    self.must = info.must;
                    self.type = info.type;
                    self.answers = info.option;
                    self.doctorName = info.doctorName;
                    self.read_only = info.read_only;
                    self.$nextTick(function(){
                        if (info.checked) {
                            self.checked = info.answer;
                            $("#radio" + info.checked[0]).addClass('active');
                        }
                        if(info.is_over == 1){
                            self.is_over = 1;
                            $('.btn-block').html('完成');
                        }else{
                            self.is_over = 0;
                            $('.btn-block').html('下一题');
                        }
                        if (info.type != 'radio') {
                            if (info.id) {
                                self.next_id = info.id;
                            } else {
                                $api.pop(data.msg);
                                self.doctorName = info.doctorName;
                                self.$router.push({path:'/chat',query: { clinicId: self.clinic_id, doctorName: info.doctorName }});
                            }
                        }
                    })
                })
            },
            save(){
                console.log(this.checked);
                var obj = {};
                var self = this;
                obj.question_id = this.info.id;
                obj.must = this.must;
                obj.clinic_id = this.clinic_id;
                obj.exam_id = this.exam_id;
                obj.checked = this.checked;
                if(!this.checked && this.must){
                    $api.pop('题目为必答题'); return false;
                }
                if(!self.tag){
                    self.$http.post(this.$store.state.apiUrl+'exam/answer',obj).then(function (res){
                        self.tag = 1;
                        var data = res.data;
                        if(data.status){
                            var info = data.data;
                            self.type = info.type;
                            if(info.id){
                                self.next_id = info.id;
                            }else{
                                self.$router.push({path:'/chat',query: { clinicId: self.clinic_id, doctorName: self.doctorName }});
                            }
                        }else{
                            $api.pop(data.msg);
                            if(_this.is_over == 1){
                                self.$router.push({path:'/chat',query: { clinicId: self.clinic_id, doctorName: self.doctorName }});
                            }
                        }
                        self.tag = 0;
                    })
                }

            },
            dohref(){
                let self = this;
                let qid = this.next_id;
                let eid = this.exam_id;
                let cid = this.clinic_id;
                let fid = this.family_id;
                if(self.type == 'radio'){
                    self.$router.push({
                        path: '/exam/exam_radio',
                        query: {id: eid, familyId: fid, clinicId: cid, questionId: qid}
                    });
                }else if(self.type == 'checkbox'){
                    self.$router.push({
                        path: '/exam/exam_check',
                        query: {id: eid, familyId: fid, clinicId: cid, questionId: qid}
                    });
                }else if(self.type == 'text') {
                    self.$router.push({
                        path: '/exam/exam_fill',
                        query: {id: eid, familyId: fid, clinicId: cid, questionId: qid}
                    });
                }else if(self.type == 'photo'){
                    self.$router.push({
                        path: '/exam/exam_photo',
                        query: {id: eid, familyId: fid, clinicId: cid, questionId: qid}
                    });
                }else{
                    self.$router.push({path:'/chat',query: { clinicId: self.clinic_id, doctorName: self.doctorName }});
                }
            },
            choose(val,id){
                this.checked = val;
                $('.icon-check').removeClass('active');
                $('#'+id).addClass('active');
            },
            reload(){
                setTimeout(function(){
                    location.reload();
                },200);
            },
            shareMessage(){
                if(this.$store.state.tcmuser  && true){
                    //$api.pop('微信接口在app上待开发，稍等');
                    return;
                }
                let that = this;
                let title = '【泰和国医】 请您填写问诊单';
                if(this.read_only == 1) title = '【泰和国医】 请您查看问诊单';

                wx.ready(function () {
                    let url = '/wechat/exam/exam_radio?id=' + that.exam_id + '&familyId=' + that.family_id + '&clinicId=' + that.clinic_id + '&questionId=' + that.question_id;
                    wx.onMenuShareTimeline({
                        title: title, // 分享标题
                        link: 'http://'+window.location.host+url, // 分享链接
                        imgUrl: 'http://'+window.location.host+'/static/img/gxh_share.jpg', // 分享图标
                        success: function () {
                            // 用户确认分享后执行的回调函数
                        },
                        cancel: function () {
                            // 用户取消分享后执行的回调函数
                        },fail:function(res){
                            alert(JSON.stringify(res))
                        }
                    });
                    wx.onMenuShareAppMessage({
                        title: title, // 分享标题
                        desc: '这是一款有中医思维的问诊单，诚意推荐给您。【泰和国医】出品', // 分享描述
                        link: 'http://'+window.location.host+url, // 分享链接
                        imgUrl: 'http://'+window.location.host+'/static/img/gxh_share.jpg', // 分享图标
                        success: function () {
                            // 用户确认分享后执行的回调函数
                        },
                        cancel: function () {
                            // 用户取消分享后执行的回调函数
                        },fail:function(res){
                            alert(JSON.stringify(res))
                        }
                    });
                })
            }
        },
        watch:{
            $route(){
                this.question_id = this.$route.query.questionId;
                this.answers = {};
                this.checked = 0;
                $('.icon-check').removeClass('active');
                this.getQuestion(this.question_id);
            },
            next_id(){
                this.dohref();
            }
        }
    };
</script>
