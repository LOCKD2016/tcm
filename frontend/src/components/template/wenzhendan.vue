<template lang='jade'>
    .fixbody
        header
            .left(onclick="back()")
                i.icon-arrow-left
            .center 填写问诊单
        #patSet.pop
            .box
                //.head 选择就诊人
                .head.dz
                    span 选择就诊人
                    a(v-if="long>0" @click="add()") 就诊人管理
                .main
                    ul
                        li(v-if="long<1")
                            h3
                                span 尚未添加就诊人，
                                a(@click="add()") 现在添加
                        li(v-else v-for="(a,ind) in patient" v-bind:class="patient_set == ind ? 'active':''" @click="setPat(ind,a.id)")
                            h4 {{a.realname}}
                            p {{a.sex}}，{{a.age}}岁
                            i.icon-check-c
                .foot(onclick="$('#patSet').fadeOut()") 确定
        //#cropbox
            header
                .left(onclick="$('#cropbox').fadeOut()")
                    i.icon-arrow-left
                .center 上传照片
            .cropbox
                .addimg
                    i(style="font-size:12px") 上传
                    input#uploadImage(type="file" accept="image/*")
                //#image-editor
        #wrap
            .step1
                .panel.top_tip
                    p 为保证您一次填写成功，请提前预备好两张照片：一张清晨起床未化妆自然光线下的面部照片（面部拍照平视，下巴微收，无闪光灯）清晨起床自然光线下未进食时舌的照片（舌部拍照勿用力伸舌，反复伸舌）
                    h3 以下人群不适合贴“个性化”三伏贴：
                    ul
                        li 心脏病、肾病、肝脏疾病者
                        li 肺结核、支气管扩张患者
                        li 孕妇
                        li 有严重药敏史
                        li 瘢痕体质或穴位处有皮肤溃损
                        li 对皮肤暂时留下色素沉着、皮肤起泡很介意者
                        li.none 智力高于60的
                ul.list-group
                    li(v-if='long>0', onclick="$('#patSet').fadeIn()")
                        span 就诊人
                        .val {{patient[patient_set].realname}}， {{patient[patient_set].sex}}，{{patient[patient_set].age}}岁
                        i.icon-arrow-right
                    li(v-else,onclick="$('#patSet').fadeIn()")
                        span 就诊人
                        .val
                        i.icon-arrow-right
                    li(@click="wxUpload('md')")
                        span 面部照片
                        .val.img(v-bind:style="bg(pic_mb)")
                        i.icon-arrow-right
                        //input#mb(type="file" name="file")
                        input(type="hidden" name="pic_mb",v-model="pic_mb")

                    li(@click="wxUpload('sb')")
                        span 舌苔照片
                        .val.img(v-bind:style="bg(pic_st)")
                        i.icon-arrow-right
                        //input#sb(type="file" name="file")
                        input(type="hidden" name="pic_st",v-model="pic_st")


                .btn.btn-block(@click="submitInfo()") 下一步

</template>
<script>
    export default {
        created(){
            this.getFamily();
            this.id = this.$route.params.id;
            //this.upImg();
            //this.upTp();
        },
        data(){
            return {
                patient_set:0,
                patient: [],
                pic_mb:'',
                pic_st:'',
                patient_id: 0,
                id: 0, // 订单id,
                long:0,
                not_ready: 0
            }
        },
        methods:{
            appUpload(id){
                var _this = this;
                AppUpload(function (data) {
                    if(data&&data.status){
                        if(id == 'md'){
                            _this.pic_mb = data.data.image_url
                        }else{
                            _this.pic_st = data.data.image_url
                        }
                    }else{
                        $api.pop('上传失败');
                    }
                });
            },
            wxUpload:function(id){

                if(this.$store.state.tcmuser && true){
                    this.appUpload(id)
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
                                self.wxUploadId(serverId,id);
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
            wxUploadId:function(serverId,id){
                $('body').addClass('loading');
                this.$http.post(this.$store.state.apiUrl+'wxupload', {serverId:serverId}).then(function (res){
                    $('body').removeClass('loading');
                    var data = res.data
                    if(data.status){
                        if(id == 'md'){
                            this.pic_mb = data.data
                        }else{
                            this.pic_st = data.data
                        }
                    }else{
                        $api.pop('上传失败');
                    }
                })
            },
            bg: function (url) {
                if (url) return 'background-image:url(' + url + ')'
            },
            setPat:function(id,p_id){
                this.patient_set = id;
                this.patient_id = p_id;
            },
            toOrder:function (t) {
                this.$router.push({path:'/doctor/'+t,query: { id: this.$route.query.id }});
            },
            upImg() {
                var self = this;
                layui.use('upload', function () {
                    layui.upload({
                        url: '/upload/wechat',
                        elem: '#mb',
                        method: 'post',
                        before:function(input){
                            $('body').addClass('loading');
                        },
                        success: function (res) {
                            $('body').removeClass('loading');
                            self.pic_mb = res.data;
                        }
                    });
                });
            },
            upTp() {
                var self = this;
                layui.use('upload', function () {
                    layui.upload({
                        url: '/upload/wechat',
                        elem: '#sb',
                        method: 'post',
                        before:function(input){
                            $('body').addClass('loading');
                        },
                        success: function (res) {
                            $('body').removeClass('loading');
                            self.pic_st = res.data;
                        }
                    });
                });
            },
            submitInfo:function () {
                if(!this.pic_mb){
                    $api.pop('面部照片不能为空'); return false;
                }
                if(!this.pic_st){
                    $api.pop('舌部照片不能为空'); return false;
                }
                if(this.not_ready == 1){
                    $api.pop('请选择就诊人'); return false;
                }
                $('body').addClass('loading');
                var self = this;
                var obj = {};
                obj.family_id = this.patient_id;
                obj.face_img = this.pic_mb;
                obj.tongue_img = this.pic_st;
                obj.order_id = this.id;
                this.$http.post(this.$store.state.apiUrl+'answer/store', obj).then(function (res){
                    var next_id = res.data.data.next_id;
                    var type = res.data.data.qtype;
                    var pre_id = res.data.data.pre_id;
                    if(type==0){
                        self.$router.push({path:'/wzd_radio' ,query: { id: next_id, pre_id: pre_id}});
                    }else if(type==1){
                        self.$router.push({path:'/wzd_check' ,query: { id: next_id, pre_id: pre_id}});
                    }else if(type==2){
                        self.$router.push({path:'/wzd_fill' ,query: { id: next_id, pre_id: pre_id}});
                    }else{
                        self.$router.push({path:'/my_order/my/'});
                    }
                    $('body').removeClass('loading');
                })
            },
            getFamily:function () {
                this.$http({url:this.$store.state.apiUrl+'familylist', method:'GET'}).then(function(res){
                    this.patient = res.data.data;
                    this.patient_id = this.patient[0].id;
                    if(!this.patient[0].realname){
                        this.not_ready = 1;
                    }else{
                        this.long = this.patient.length;
                    }
                })
            },
            add(){
                if(this.not_ready==1){
                    this.$router.push({path:'/my_fmld/my'});
                }else{
                    this.$router.push({path:'/my_myfml/my'});
                }
            }
        }
    };
</script>
