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
                ul.img_box(v-if='read_only == 1')
                    li(v-for="(ch,i) in answers")
                        .img(v-bind:style="bg(ch)" v-bind:id="i+'photo'" @click="showImg($event)")
                        .pop.imgScare(v-on:click="close()")
                            .img(v-bind:style="bg(ch)")
                ul.img_box(v-else)
                    li(v-for="(ch,i) in answers" @click="wxUpload(i+'photo')")
                        .img(v-bind:style="bg(ch)" v-bind:id="i+'photo'")
                        i.icon-camera
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
                must: 0,
                info: {},
                answers: [],
                checked: [],
                next_id: 0,
                clinic_id: 0,
                question_id: 0,
                exam_id: 0,
                type: '',
                tag: 0,
                is_over: 0,
                doctorName: '',
                read_only: 0,
                background_img: ''
            }
        },
        methods:{
            appUpload(i){
                var _this = this;
                AppUpload(function (data) {
                    if(data&&data.status){
                        _this.checked[parseInt(i)] = data.data.image_url;
                        console.log(_this.checked);
                        $('#'+i).css("background-image","url("+data.data.image_url+")");
                        $('#'+i).next('.icon-camera').hide();
                    }else{
                        $api.pop('上传失败');
                    }
                });
            },
            wxUpload:function(i){
                if(this.$store.state.tcmuser && true){
                    this.appUpload(i);
                    //$api.pop('微信接口在app上待开发，稍等');
                    return;
                }
                if(!this.$store.state.wxready){
                    $api.pop('微信上传未就绪，稍等');
                    return;
                }
                var self = this;
                wx.chooseImage({
                    count: 1, // 默认9
                    sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
                    sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
                    success: function (res) {
                        var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
                        wx.uploadImage({
                            localId: localIds[0], // 需要上传的图片的本地ID，由chooseImage接口获得
                            isShowProgressTips: 1, // 默认为1，显示进度提示
                            success: function (res) {
                                var serverId = res.serverId; // 返回图片的服务器端ID
                                self.wxUploadId(serverId,i);
                            },
                            fail:function(res){
                                alert(JSON.stringify(res));
                            }
                        });
                    },
                    fail:function(res){
                        alert('选图失败'+res.errMsg+',请重试');
                        setTimeout(function(){
                            location.reload();
                        },200);
                    }
                });
            },
            wxUploadId:function(serverId,i){
                var _this = this;
                $('body').addClass('loading');
                this.$http.post(this.$store.state.apiUrl+'wxupload', {serverId:serverId}).then(function (res){
                    $('body').removeClass('loading');
                    var data = res.data;
                    if(data.status){
                        _this.checked[parseInt(i)] = data.data;
                        console.log(_this.checked);
                        $('#'+i).css("background-image","url("+data.data+")");
                        $('#'+i).next('.icon-camera').hide();
                    }else{
                        $api.pop('上传失败');
                    }
                })
            },
            getQuestion: function (question_id) {
                let obj = {};
                let self = this;
                obj.clinic_id = this.clinic_id;
                obj.exam_id = this.exam_id;
                obj.family_id = this.family_id;
                obj.question_id = question_id;
                this.$http.post(this.$store.state.apiUrl + 'exam/show/', obj).then(function (res) {
                    var data = res.data;
                    var info = data.data;
                    self.info = data.data;
                    self.must = info.must;
                    self.type = info.type;
                    self.answers = info.option;
                    self.doctorName = info.doctorName;
                    self.read_only = info.read_only;
                    if (info.answer && info.answer.length > 0) {
                        self.answers = info.answer;
                        self.checked = info.answer;
                        if(self.answers.length == 1){
                            self.answers.push('');
                            self.answers.push('');
                        }else if(self.answers.length == 2){
                            self.answers.push('');
                        }
                    }else{
                        if(self.answers == null){
                            self.answers = [];
                            self.answers.push('');
                            self.answers.push('');
                            self.answers.push('');
                        }else{
                            let len = Object.keys(self.answers).length;
                            if(len == 1){
                                self.answers.push('');
                                self.answers.push('');
                            }else if(len == 2){
                                self.answers.push('');
                            }
                        }
                    }
                    if(info.is_over == 1){
                        self.is_over = 1;
                        $('.btn-block').html('完成');
                    }else{
                        self.is_over = 0;
                        $('.btn-block').html('下一题');
                    }
                    if (info.type != 'photo') {
                        if (info.id) {
                            self.next_id = info.id;
                        } else {
                            $api.pop(data.msg);
                            self.doctorName = info.doctorName;
                            self.$router.push({path:'/chat',query: { clinicId: self.clinic_id, doctorName: info.doctorName }});
                        }
                    }
                })
            },
            bg: function (url) {
                if (url) return 'background-image:url(' + url + ')'
            },
            next(){
                var obj = {};
                var _this = this;
                obj.question_id = this.info.id;
                obj.checked = this.checked;// 所有上传的图片
                obj.must = this.must;
                obj.exam_id = this.exam_id;
                obj.clinic_id = this.clinic_id;
                if(_this.checked.length==0){
                    if(_this.must>0){
                        $api.pop('题目为必答题'); return;
                    }
                }
                if(_this.tag == 0){
                    _this.$http.post(_this.$store.state.apiUrl+'exam/answer',obj).then(function (res){
                        var data = res.data;
                        _this.tag = 1;
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
                        _this.checked = [];
                    })
                }
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
            close:function(){
                $('.imgScare').hide();
            },
            showImg:function(event){
                $(event.currentTarget).show();
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
                    let url = '/wechat/exam/exam_photo?id=' + that.exam_id + '&familyId=' + that.family_id + '&clinicId=' + that.clinic_id + '&questionId=' + that.question_id;
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
                this.answers = [];
                this.checked = [];
                this.getQuestion(this.question_id);
            },
            next_id(){
                this.dohref();
            }
        }
    };
</script>
