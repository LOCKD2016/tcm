<template lang='jade'>
.fixbody
    header
        .left(onclick="back()")
            i.icon-arrow-left
        .center 医生主页
    template
        .btn.btn-fix
            .right(v-if='doctor.web==1' v-bind:class="{buy:doctor.web==1&&doctor.rest==0}" @click="online()") {{$route.query.type == 3?'视频问医':'在线咨询'}}
            .right(v-else @click='nocan') {{$route.query.type == 3?'视频问医':'在线咨询'}}
            .left(v-if='doctor.clinic==1' @click="offline()") 门诊预约
            .left(v-else @click='nocan') 门诊预约

    #wrap
        .doc_banner
            .avatar(v-if="doctor.photoSUrl" v-bind:style="bg(doctor.photoSUrl)")
            .avatar(v-else v-bind:style="bg('/img/doctor_default.png')")
            h1
                b {{doctor.name}}
                span.titlename(v-if="doctor.titleName") {{doctor.titleName}}
            h1.store
                span {{store}}
            p
                span 患者推荐指数：
                span.stars(v-bind:show="doctor.level")
                    i.icon-nav1
                    i.icon-nav1
                    i.icon-nav1
                    i.icon-nav1
                    i.icon-nav1

        .doc_main
            .conts.conts_g
                h3 擅长
                p.good
                  template(v-for="e in diseases")
                    span {{e.name}}
                template
                    h3.info 门诊信息
                    //.titNrBox
                        i.yy
                        span 可预约
                    template(v-show="!isEmpty(wj)")
                        .hos.none
                            i
                            span {{store}}
                        table.none#mytable
                            tbody.ttt

            .conts(v-if='doctor.intro')
                h3 个人介绍
                //- p.jieshao.open#dInfo {{ doctor.intro | intro }}
                p.jieshao.open#dInfo(v-html='intro(doctor.intro)')
            //.btn_toggle(v-if='doctor.intro' onclick="$('#dInfo').toggleClass('open');$('.icon-triangle-down').toggleClass('up')")
            .btn_toggle(v-if='doctor.intro' @click='showmore')
                span 更多
                i.icon-triangle-down

        .doc_main#dComment(v-if="comment")
            .hr
                h3 评论-{{comment_num}}
            template(v-for="(c,ind) in comment")
              .comments
                  .avatar(v-if="c.user.data.headimgurl" v-bind:style="bg(c.user.data.headimgurl)")
                  .avatar(v-else v-bind:style="bg('/img/doctor_default.png')")
                  h2 {{c.user.data.nickname.substr(0,1)+'**'}}
                  p.lab
                    span(v-if="c.disease") {{c.disease}}
                  p.js {{c.content}}
                  .right
                      span {{c.time.substr(0,10)}}
    .layer_pop.none
      .content
        .txt 请先去完善信息！
        .pop_btn.clearfix
          .p_btn.l(@click="dodel()") 确定
          .p_btn(@click="canceldel()") 取消

    .tipsBg.none
        .orderFull
            .text
                p 医生预约已满，
                p 如需预约请致电：010-64176667
                p 泰和国医预约电话进行预约
            .fullbtn(@click='hideTip') 确定

</template>
<script>
    export default {
        data(){
            return {
                wj:[],
                hs:[],
                dashboard:[],
                doctor:{},
                comment_num: 0,
                qcode: '',
                store: '',
                comment:[],
                diseases: {},
                sections: {},
                clinique: {},
                schedules: {},

            }
        },
        created(){
            this.id = this.$route.query.id;
            this.type = this.$route.query.type;
            this.clinicId = this.$route.query.clinicId ? this.$route.query.clinicId : 2;
            this.subscribe_id = this.$route.query.subscribe_id;
            this.subscribe_type = this.$route.query.subscribe_type;
            this.getDoctor();
        },
        mounted() {
            calen();
            let dd = this;
            if(this.$store.state.tcmuser && true){
                //$api.pop('微信接口在app上待开发，稍等');
                return;
            }
            wx.ready(function () {
                wx.onMenuShareTimeline({
                    title: '诚意推荐好中医 '+dd.doctor.name+'医师 【泰和国医】', // 分享标题
                    link: protocol+window.location.host+'/wechat/doctor/detail/id?id='+dd.id+'&clinicId='+dd.clinicId, // 分享链接
                    imgUrl: dd.doctor.photoSUrl, // 分享图标
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
                    title: '诚意推荐好中医 '+dd.doctor.name+'医师 【泰和国医】', // 分享标题
                    desc: '您可通过【泰和国医】微信公众账号直接关注，向Ta发起在线咨询或预约Ta的门诊。', // 分享描述
                    link: protocol+window.location.host+'/wechat/doctor/detail/id?id='+dd.id+'&clinicId='+dd.clinicId, // 分享链接
                    imgUrl: dd.doctor.photoSUrl, // 分享图标
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
        },
        // filters:{

        //     intro(str){

        //         console.log(str)

        //         var s = str.replace(/\n/g,'<br>')

        //         console.log(s)

        //         return s

        //     },

        // },
        methods:{

            //医生介绍换行

            intro(str){

                console.log(str)

                var s = str.replace(/\n/g,'<br>')

                console.log(s)

                return s

            },

            //隐藏门诊预约已满提示

          hideTip(){

            $('.tipsBg').addClass('none');

          },

          //展示更多

          showmore(){

            $('#dInfo').toggleClass('open')

            $('.icon-triangle-down').toggleClass('up')

          },

          //确定  取消

          canceldel(){
            $('.layer_pop').addClass('none');
          },
          dodel(){
            $('.layer_pop').addClass('none');
            this.$router.push({path:'/my_fmld/my'});
          },

            bg: function (url) {
                if (url) return 'background-image:url(' + url + ')'
            },
            imgScare:function(qcode){
                this.qcode = qcode;
              $('.imgScare').show();
            },
            close:function(){
              $('.imgScare').hide();
            },
            getDoctor() {
                this.$http.get(this.$store.state.apiUrl+'doctor/detail/'+ this.id+'?include=diseases,sections,comments.user,cliniques.schedules').then(function (res) {
                    this.doctor = res.data.data;
                    if(res.data.data.comments){
                      this.comment = res.data.data.comments.data;
                    }
                    if(res.data.data.cliniques){
                      this.clinique = res.data.data.cliniques.data;
                    }
                    if(res.data.data.diseases){
                      this.diseases = res.data.data.diseases.data;
                    }
                    if(res.data.data.sections){
                      this.sections = res.data.data.sections.data;
                    }
                    if(res.data.data.cliniques){
                      this.store = this.clinique[0].name;
                      this.wj = isEmpty(this.clinique[0].schedules.data)?[]:this.clinique[0].schedules.data;
                    }
                    this.comment_num = this.comment.length;
                    this.$nextTick(function(){
                        var id = this.id;
                        this.dateTable(id);
                    });
                });
            },
            follow() {
                this.$http.get(this.$store.state.apiUrl+'users/follow/'+ this.id).then(function (res) {
                    if(res.data.status){
                        this.getDetail();
                    }else{
                        $api.pop(res.data.msg);
                    }
                });
            },
            online:function(){

                var self = this

                if(!this.doctor.rest){

                    this.$http.get(this.$store.state.apiUrl+'user/complete').then(function (res) {

                        if(res.data.status == 1){

                            self.$http.get(self.$store.state.apiUrl+'bespeak/can/'+self.id).then(function (result) {

                                  if(result.data.status){

                                    self.$router.push({path:'/doctor/preOnline',query: { id: self.id,type:self.$route.query.type}});

                                  }else{

                                    $api.pop(result.data.msg);

                                  }

                            });

                        }else{
                            $('.layer_pop').removeClass('none');
                        }
                    });

                }else{

                    //$api.pop('医生预约已满')

                    $('.tipsBg').removeClass('none');

                }

            },
            offline(){

                if(this.doctor.cliniques && this.doctor.cliniques.data[0].isSchedules == 1){

                    this.$http.get(this.$store.state.apiUrl+'user/complete').then(function (res) {
                        if(res.data.status == 1){
                            this.$router.push({path:'/doctor/outline',query: { id: this.id}});
                        }else{
                            // $api.pop(res.data.msg);
                            // this.$router.push({path:'/my_fmld/my'});
                            $('.layer_pop').removeClass('none');
                        }
                    });

                }else{

                    $('.tipsBg').removeClass('none');

                    //$api.pop('医生预约已满，如需预约请致电：010-64166667泰和国医预约电话进行预约')

                }

            },
            getComment:function(id){
                this.$router.push({path:'/doctor/allComment',query: { id: id}});
            },
            dateTable(id){
                if(!isEmpty(this.wj)){
                    $("#mytable").removeClass('none');
                    $("#mytable").prev().removeClass('none');
                    $(".info").removeClass('none');
                    this.dateData(this.wj,id,this.clinique[0].id);
                }
            },
            nocan(){
              $api.pop('医生暂未开通该功能')
            },
            dateData(data,id,clinicId){
                var _this = this;
                var table = '#mytable';
                for(var i=0;i<data.length;i++){
                    var ds= data[i].date;
                    $(table + ' tbody tr').find('td').each(function(){
                        //预约
                        if(ds==$(this).attr('data-calen')){
                            $(this).addClass("yy");
                            $(this).click(function(){
                                _this.doctorOut(id,clinicId,$(this).attr('data-calen'));
                            });
                        }

                    });
                }
            },
            doctorOut(id,clinicId,date){
                this.$http.get(this.$store.state.apiUrl+'user/complete').then(function (res) {
                    if(res.data.status == 1){
                        this.$router.push({path:'/doctor/outline', query: { id: id, clinicId:clinicId,date:date }});
                    }else{
                        $api.pop(res.data.msg);
                        this.$router.push({path:'/my_fmld/my'});
                    }

                });
            },
        }
    };
    </script>
