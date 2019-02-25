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
                    label.inp(v-for="(an,i) in answers")
                        //input(type='checkbox' v-model='checked' v-bind:value="i")
                        .icon-check(v-bind:id="i")
                        span {{an.val}}
                .main(v-else)
                    label.inp(v-for="(an,i) in answers" @click="store(i,an.val)")
                        //input(type='checkbox' v-model='checked' v-bind:value="i")
                        .icon-check(v-bind:id="i")
                        span {{an.val}}
                .btn.btn-block(@click="next()") 下一题

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
                checked: [],//下标
                json_checked: [],//真正传入后台的选项
                clinic_id: 0,
                question_id: 0,
                family_id: 0,
                exam_id: 0,
                next_id: 0,
                type: '',
                tag: 0,
                is_over: 0,
                doctorName: '',
                read_only: 0//是否只读
            }
        },
        methods:{
            getQuestion:function (question_id) {
                let obj = {};
                let self = this;
                obj.clinic_id = this.clinic_id;
                obj.exam_id = this.exam_id;
                obj.family_id = this.family_id;
                obj.question_id = question_id;
                this.$http.post(self.$store.state.apiUrl+'exam/show/',obj).then(function(res){
                    var data = res.data;
                    var info = data.data;
                    self.info = data.data;
                    self.must = info.must;
                    self.type = info.type;
                    self.answers = info.option;
                    self.doctorName = info.doctorName;
                    self.read_only = info.read_only;
                    self.$nextTick(function(){
                        if(info.checked){
                            self.checked = info.checked;
                            for(var i = 0;i<info.checked.length;i++){
                                $("#" + info.checked[i]).addClass('active');
                            }
                        }
                        if(info.is_over == 1){
                            self.is_over = 1;
                            $('.btn-block').html('完成');
                        }else{
                            self.is_over = 0;
                            $('.btn-block').html('下一题');
                        }
                        if(info.answer){
                            self.json_checked = info.answer;
                        }
                        console.log(self.json_checked);
                        if(info.type != 'checkbox'){
                            if(info.id){
                                self.next_id = info.id;
                            }else{
                                $api.pop(data.msg);
                                self.doctorName = info.doctorName;
                                self.$router.push({path:'/chat',query: { clinicId: self.clinic_id, doctorName: info.doctorName }});
                            }
                        }
                    })
                })
            },
            next(){
                var obj = {};
                var _this = this;
                obj.question_id = this.info.id;
                obj.checked = this.json_checked;//被选中的答案
                obj.must = this.must;
                obj.exam_id = this.exam_id;
                obj.family_id = this.family_id;
                obj.clinic_id = this.clinic_id;
                console.log(this.json_checked);
                console.log(this.checked);
                if(_this.checked.length==0){
                    if(_this.must>0){
                        $api.pop('题目为必答题'); return;
                    }
                }
                if(!_this.tag){
                    _this.$http.post(_this.$store.state.apiUrl+'exam/answer',obj).then(function (res){
                        _this.tag = 1;
                        var data = res.data;
                        if(data.status){
                            var info = data.data;
                            _this.type = info.type;
                            if(info.id){
                                _this.next_id = info.id;
                            }else{
                                _this.$router.push({path:'/chat',query: { clinicId: _this.clinic_id, doctorName: _this.doctorName }});
                            }
                        }else{
                            $api.pop(data.msg);
                            if(_this.is_over == 1){
                                _this.$router.push({path:'/chat',query: { clinicId: _this.clinic_id, doctorName: _this.doctorName }});
                            }
                        }
                        _this.tag = 0;
                    })
                }
            },
            store(id,val){
                let _this = this;
                console.log(id)
                console.log(val)
                if (_this.checked.indexOf(id) > -1) {
                    for (var i = 0; i < _this.checked.length; i++) {
                        if (_this.checked[i] == id) {
                            _this.checked.splice(i, 1);
                            $("#" + id).removeClass('active');
                            break;
                        }
                    }
                    for (var j = 0; j < _this.json_checked.length; j++) {
                        if (_this.json_checked[j] == val) {
                            var inx = _this.json_checked.indexOf(val);
                            _this.json_checked.splice(inx, 1);
                            break;
                        }
                    }
                } else {
                    _this.checked.push(id);
                    _this.json_checked.push(val);
                    $("#" + id).addClass('active');
                }
                console.log(this.json_checked);
                console.log(this.checked);
            },
            dohref(){
                var self = this;
                var qid = this.next_id;
                var eid = this.exam_id;
                var cid = this.clinic_id;
                var fid = this.family_id;
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
            shareMessage(){
                if(this.$store.state.tcmuser && true){
                    $api.pop('微信接口在app上待开发，稍等');
                    return;
                }
                let that = this;
                let title = '【泰和国医】 请您填写问诊单';
                if(this.read_only == 1) title = '【泰和国医】 请您查看问诊单';
                wx.ready(function () {
                    let url = '/wechat/exam/exam_check?id=' + that.exam_id + '&familyId=' + that.family_id + '&clinicId=' + that.clinic_id + '&questionId=' + that.question_id;
                    wx.onMenuShareTimeline({
                        title: title, // 分享标题
                        link: 'http://'+window.location.host+url, // 分享链接
                        imgUrl: 'http://'+window.location.host+'/static/img/wxlogo.png', // 分享图标
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
                        imgUrl: 'http://'+window.location.host+'/static/img/wxlogo.png', // 分享图标
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
                $('.icon-check').removeClass('active');
                this.checked = [];
                this.getQuestion(this.question_id);
            },
            next_id(newValue){
                this.dohref();
            }
        }
    };
</script>