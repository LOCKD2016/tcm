<template lang='jade'>
    .fixbody
        header
            .left(onclick="back()")
                i.icon-arrow-left
            .center {{info.title}}
        #wrap
                        .panel.step2
                            div(v-for='(op,index) in options')
                                span.title(v-if='info.title') {{index+1}}、{{op.title}}
                                .main(v-if='op.type=="text"' v-bind:id="index+1")
                                    label
                                        textarea(placeholder='请点此输入' v-model="op.answers" @keydown="autoHeight($event)")
                                .main(v-if='op.type=="checkbox"' v-bind:id="index+1")
                                    label.inp(v-for="(an,i) in op.option" @click='choose(an.title,index,"checkbox",$event)')
                                        .icon-check(v-bind:class='{active: op.answers.indexOf(an.title) > -1}')
                                        span {{an.title}}
                                .main(v-if='op.type=="radio"' v-bind:id="index+1")
                                    label.radio(v-for="(a,i) in op.option" @click='choose(a.title,index,"radio",$event)')
                                        .icon-check(v-bind:class='{active:a.title==op.answers}')
                                        span.sex {{a.title}}
                                .main.p_exam(v-if='op.type=="photo"' v-bind:id="index+1")
                                    ul.img_box
                                        li(v-if='op.answers[0]||images>=1')
                                            .img(v-bind:id="op.id+'photo'+0" v-bind:style='bg(op.answers[0])' @click="wxUpload(index,0,op.id,$event)")
                                            i.cancel(@click='cancel(index,0,op.id,$event)')
                                            //i.icon-camera
                                        li(v-if='op.answers[1]||images>=2')
                                            .img(v-bind:id="op.id+'photo'+1" v-bind:style='bg(op.answers[1])' @click="wxUpload(index,1,op.id,$event)")
                                            i.cancel(@click='cancel(index,1,op.id,$event)')
                                            //i.icon-camera
                                        li(v-if='op.answers[2]||images>=3')
                                            .img(v-bind:id="op.id+'photo'+2" v-bind:style='bg(op.answers[2])' @click="wxUpload(index,2,op.id,$event)")
                                            i.cancel(@click='cancel(index,2,op.id,$event)')
                                            //i.icon-camera
                                        li(v-if='op.answers[3]||images>=4')
                                            .img(v-bind:id="op.id+'photo'+3" v-bind:style='bg(op.answers[3])' @click="wxUpload(index,3,op.id,$event)")
                                            i.cancel(@click='cancel(index,3,op.id,$event)')
                                            //i.icon-camera
                                        li(v-if='op.answers[4]||images==5')
                                            .img(v-bind:id="op.id+'photo'+4" v-bind:style='bg(op.answers[4])' @click="wxUpload(index,4,op.id,$event)")
                                            i.cancel(@click='cancel(index,4,op.id,$event)')
                                            //i.icon-camera
                                        li.imgbtn
                                            i.icon-camera(@click="wxUpload(index,images,op.id,$event)")
                        .btn.btn-block(@click='submit()') 提交

        //提示信息

        .layer_pop.none
            .content
                .txt 最多只能上传五张照片
                .pop_btn.clearfix
                    .p_btn.l(@click="dodel()" style='width:100%') 确定

</template>
<script>
    export default {
        created(){
            this.id = this.$route.query.id; // 问诊单id
            this.clinic_id = this.$route.query.clinic_id; // 问诊单id
            this.message_list_id = this.$route.query.listId; //消息list_id 1个list下面有多条消息
            this.getExam(this.id);
        },
        mounted:function(){
            this.shareMessage();
            var self=this;
            setTimeout(function(){
                self.swiper = new Swiper('.swiper-container-list', {
                    autoHeight:true,
                    direction: 'vertical',
                    slidesPerView: 'auto',
                    observer: true,
                    observeParents: true,
                    mousewheelControl: true,
                    freeMode: true,
                    freeModeMomentumRatio : 0.4,
                    freeModeMomentumVelocityRatio : 0.2,
                    resistanceRatio : 0.7,
                    preventLinksPropagation : true,
                    preventClicksPropagation : true,
                    preventClicks : true,
                    onTouchEnd: function(s){
                        var _viewHeight = s.height;
                        var _contentHeight = s.virtualSize;
                        if(s.translate <= _viewHeight - _contentHeight - 50 && s.translate < 0) {
                            if(self.statusOrder){
                                $(".visible").html('正在加载...');
                                self.shareMessage();
                            }
                        }
                    }
                });
            },400);
        },

        data(){
            return {
                info: {},
                options: {},
                id: 0,
                answer: '',
                type: '',
                must: 0,
                next_id: 0,
                exam_id: 0,
                clinic_id: 0,
                tag: 0,
                is_over: 0,
                doctorName: '',
                read_only: 0,
                message_list_id: 0, //消息list_id
                images:0
            }
        },
        methods:{

            //文本域高度自适应

            autoHeight(e){
                e.target.style.height=e.target.scrollHeight+'px'
            },

            submit(){
                this.info.message_list_id = this.message_list_id;
                this.info.clinic_id = this.clinic_id;
                this.info.exam_id = this.id;
                this.info.option.data = this.options;
                this.$http.post(this.$store.state.apiUrl+'exam/answer',this.info).then(function (res) {
                    $api.pop(res.data.msg);
                    if(res.data.status){
                        this.$router.back();
                    }
                },function (response) {
                    errorMsg(response.data.data.errors);
                });
            },
            getExam:function (id) {
                this.$http.get(this.$store.state.apiUrl+'exam/detail/'+id+'?include=option&clinic_id='+this.clinic_id).then(function(res){
                    if(res.data.status){
                        this.info = res.data.data;
                        this.options = res.data.data.option.data;
                    }else{
                        $api.pop(res.data.msg);
                    }
                },function (response) {
                    errorMsg(response.data.data.errors);
                })
            },
            choose(val,index,type,event){
                if(type == 'radio'){
                    $(event.currentTarget).siblings('.radio').children('.icon-check').removeClass('active');
                    this.options[index].answers = val;
                    //$(event.currentTarget).children('.icon-check').addClass('active');
                }else{
                    if(!this.options[index].answers){
                        this.options[index].answers = [];
                    }
                    if(this.options[index].answers.indexOf(val) > -1){
                        var key = this.options[index].answers.indexOf(val);
                        this.options[index].answers.splice(key,1);
                        //$(event.currentTarget).children('.icon-check').removeClass('active');
                    }else{
                        this.options[index].answers.push(val);
                        //$(event.currentTarget).children('.icon-check').addClass('active');
                    }
                }

            },
            bg: function (url) {

                if (url) return 'background-image:url(' + url + ');background-size:100% 100%;'

            },
            appUpload(index,index2,id,ele){
                var _this = this;
                AppUpload(function (pic_data) {
                    if(pic_data && pic_data.status){
                        if(_this.options[index].answers){
                            _this.options[index].answers[index2] = pic_data.data.image_url;
                        }else {
                            _this.options[index].answers = [];
                            _this.options[index].answers[index2] = pic_data.data.image_url;
                        }
                        $('#'+id+'photo'+index2).css("background-image","url("+pic_data.data.image_url+")");

                        if(_this.options[index].answers.length==5||_this.images==5){
                            $('.imgbtn').css('display','none')
                        }
                        if(ele=='icon-camera'){
                            _this.images++
                        }
                    }else{
                        $api.pop('上传失败');
                    }
                });
            },
            wxUpload:function(index,index2,id,e){

                var ele=e.target.className

                if(ele=='icon-camera'){

                    if(this.images>4){

                        $('.layer_pop').removeClass('none');

                        return false

                    }

                }
                if(this.$store.state.tcmuser && true){
                    this.appUpload(index,index2,id,ele);
                    //$api.pop('微信接口在app上待开发，稍等');
                    return;
                }
                if(!this.$store.state.wxready){
                    $api.pop('微信上传未就绪，稍等');
                    return false;
                }

                var self = this;
                wx.ready(function () {
                  if(!wx.chooseImage){
                      $api.pop('微信上传未就绪，稍等');
                      return false;
                  }
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
                                    self.wxUploadId(serverId,index,index2,id,ele);
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
                });

            },
            wxUploadId:function(serverId,index,index2,id,ele){
                var _this = this;
                $('body').addClass('loading');
                this.$http.post(this.$store.state.apiUrl+'wxupload', {serverId:serverId}).then(function (res){
                    $('body').removeClass('loading');
                    var pic_data = res.data;
                    if(pic_data.status){
                        if(_this.options[index].answers){
                            _this.options[index].answers[index2] = pic_data.data;
                        }else {
                            _this.options[index].answers = [];
                            _this.options[index].answers[index2] = pic_data.data;
                        }

                        $('#'+id+'photo'+index2).css("background-image","url("+pic_data.data+")");

                        if(_this.options[index].answers.length==5||_this.images==5){

                            $('.imgbtn').css('display','none')

                        }

                        if(ele=='icon-camera'){

                            _this.images++

                        }

                    }else{
                        $api.pop(res.data.msg);
                    }
                })
            },

            cancel(index,index2,id,e){

                this.options[index].answers.splice(index2,1);

                //$('#'+id+'photo'+index2).css("background-image","url('')").next('.icon-camera').show();

                this.images--

                $(e.target.parentNode).css('display','none')

                if(this.images<5){

                    $('.imgbtn').css('display','inline-block')

                }

            },

            dodel(){

                $('.layer_pop').addClass('none');

            },

            shareMessage(){
                if(this.$store.state.tcmuser && true){
                    //$api.pop('微信接口在app上待开发，稍等');
                    return;
                }
                let that = this;
                let title = '【泰和国医】 请您填写问诊单';
                if(this.read_only == 1) title = '【泰和国医】 请您查看问诊单';
                wx.ready(function () {
                    let url = '/wechat/exam/exam_fill?id=' + that.exam_id + '&familyId=' + that.family_id + '&clinicId=' + that.clinic_id + '&questionId=' + that.question_id;
                    wx.onMenuShareTimeline({
                        title: title, // 分享标题
                        link: protocol+window.location.host+url, // 分享链接
                        imgUrl: protocol+window.location.host+'/static/img/wxlogo.png', // 分享图标
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
                        link: protocol+window.location.host+url, // 分享链接
                        imgUrl: protocol+window.location.host+'/static/img/wxlogo.png', // 分享图标
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
                this.id = this.$route.query.id;
                this.getQuestion(this.id);
            }
        }
    };
</script>
