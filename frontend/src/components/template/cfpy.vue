<template lang='jade'>
.fixbody
  #wrap.cfpy.cfpy_pb
      header
          .left(onclick="back()")
              i.icon-arrow-left
          .center 传方抓药
          .right(v-on:click="history()") 历史纪录
      #patSet.popp
          .box
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
                              h4 {{a.realName}}
                              p {{a.sex}}，{{a.age}}岁
                              i.icon-check-c
              .foot(onclick="$('#patSet').fadeOut()") 确定

      ul.list-group.search_his
          li(onclick="$('#patSet').fadeIn()")
              span 选择就诊人
              .val(v-if="long==1") {{realName}}， {{sex}}，{{age}}岁
              i.icon-arrow-right
      .panel.list.pfxz
          .head   拍方须知
            span(v-on:click="noanymore")  不再提示
          .nr
            p 拍摄时确保处方、医生签名或盖章清晰可见；
            p 接受机打处方或手写处方，手写处方过程中如遇问题，行知助理将 与你再次确认。
            .imgbox
              img(src="/static/img/banner03.jpg" v-on:click="imgScare()")
              span 或
              img(src="/static/img/banner03.jpg" v-on:click="imgScare()")
      .panel.list.cfsc
          .head  处方上传（最多上传1张）
          .img(v-if="pic" v-bind:style="bg(pic)" @click="wxUpload()")
          .sc(v-else @click="wxUpload()")
            i.icon-camera
            span 添加照片
          ul.list-group.search_his
              li
                  span 中药剂型
                  .val {{type}}
                  select(v-model="type")
                      option(value="请选择中药剂型") 请选择中药剂型
                      option(v-for="m in medicinaltype") {{m.name}}
                  i.icon-arrow-right

      .panel.list.jyfs
          .head 煎药方式
          ul.main
              li.zj(v-on:click="jysf($event,0)")
                  i.icon-check
                  span 自煎
              li.active(v-on:click="jysf($event,1)")
                  i.icon-check
                  span 代煎

          .foot.clearfix.jy_zb.none
              .left 自备
              .right
                .items
                    span.zb(v-for="(m,index) in medicine" v-on:click="check($event,m.name,index)") {{m.name}}
      .pop.wx_tit(v-if="point != 1")
        .box
          .nr
            h3 泰和国医温馨提示：
            p 1. 传方抓药只支持本诊所医生开具的处方调剂服务；
            p 2. 按照《处方管理办法》规定，上传的处方需要在3日内，急性病症1日内；
            P 3. 根据中药饮片处方与调剂管理规定，本诊所仅提供完整处方调剂，不提供单味饮片抓药服务；
            P 4. 购药过程中本诊所享有拒绝下单的权利。
            .inp(@click="points()")
              .icon-check
              span 不再提示
          .foot(@click="closeTit()") 我知道了
      .pop.tijiao
          .box
              .main
                  ul
                      li 处方已上传成功请耐心等待划价
              .foot(@click="know()") 我知道了

      .pop.imgScare(v-on:click="close()")
           img(src="/static/img/banner03.jpg")

      .btn.btn-fix(v-on:click="recipe()") 提交

</template>
<script>
    export default {
        data() {
            return{
                patient_set:0,
                patient: [],
                medicine: [],
                medicinaltype: [],
                patient_id: 0,
                long: 0,
                point: window.localStorage.getItem("point"),
                is_tisane: 1,
                recipe_self: [],
                realName:'',
                age:'',
                sex:'',
                pic:'',
                recipe_pic:[],
                recipe_head:'',
                type:''
            }
        },
        created:function () {
            this.getFamily();
            this.getZb();
            this.getType();
        },
        mounted(){
          $(".wx_tit").show();
        },
        methods:{
            getFamily:function () {
                this.$http({url:this.$store.state.apiUrl+'family/lists', method:'GET'}).then(function(res){
                    if(res.data.status){
                        this.patient = res.data.data;
                        this.patient_id = this.patient[0].id;
                        this.realName = this.patient[0].realName;
                        this.age = this.patient[0].age;
                        this.sex = this.patient[0].sex;
                        this.long = res.data.status;
                    }else{
                        this.long = res.data.status;
                    }
                })
            },
            getZb:function () {
                this.$http({url:this.$store.state.apiUrl+'recipe/medicineself', method:'GET'}).then(function(res){
                    if(res.data.status){
                        this.medicine = res.data.data;
                    }
                })
            },
            getType:function () {
                this.$http({url:this.$store.state.apiUrl+'recipe/medicinaltype', method:'GET'}).then(function(res){
                    if(res.data.status){
                        this.medicinaltype = res.data.data;
                    }
                })
            },
            points(){
                $(".icon-check").toggleClass("active");
                if($(".icon-check").hasClass("active")){
                    window.localStorage.setItem("point",1);
                }else{
                    window.localStorage.setItem("point",0);
                }

            },
            recipe:function () {
                /**
                 * 传方抓药
                 * @Auth: kingofzihua
                 * @param Request $request [
                 *      'family_id',    // 就诊人ID
                 *      'recipe',//药方
                 *      'type',   //中药剂型
                 *      'recipe_head',    //付数
                 *      'is_tisane',   //代煎
                 *      'recipe_self',  //自备
                 * ]
                 * @return mixed
                 */
                if(!this.pic){
                    $api.pop('请上传药方');
                    return false;
                }
                if(!this.patient_id){
                    $api.pop('请选择就诊人');
                    return false;
                }
                if(!this.type || this.type=="请选择中药剂型"){
                    $api.pop('请选择中药剂型');
                    return false;
                }
                var data = {};
                data.family_id = this.patient_id;
                data.recipe_photo = this.pic;
                data.type = this.type;
                data.recipe_head = {"sum":this.recipe_head};
                data.is_tisane = this.is_tisane;
                data.recipe_self = this.recipe_self;
                this.$http.post(this.$store.state.apiUrl+'recipe/save', data).then(function (res) {
                    if(res.data.status){
                        this.pop();
                    }
                })
            },
            wxUpload:function(){
                if(this.$store.state.tcmuser){
                    $api.pop('微信接口在app上待开发，稍等');
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
                                self.wxUploadId(serverId);
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
            wxUploadId:function(serverId){
                $('body').addClass('loading');
                this.$http.post(this.$store.state.apiUrl+'wxupload', {serverId:serverId}).then(function (res){
                    $('body').removeClass('loading');
                    var data = res.data;
                    if(data.status){
                        this.pic = data.data;
                        console.log(this.pic);
                    }else{
                        $api.pop('上传失败');
                    }
                })
            },
            setPat:function(id,p_id){
                this.patient_set = id;
                this.patient_id = p_id;
                this.realName = this.patient[id].realName;
                this.age = this.patient[id].age;
                this.sex = this.patient[id].sex;
            },
            add(){
                this.$router.push({path:'/my_myfml/my'});
            },
            know(){
                this.$router.push({path:'/cfpy_history'});
            },
            noanymore:function () {
                $('.pfxz').addClass('none');
            },
            items:function(){
              $(".switch_btn").toggleClass("active");
              $(".items").toggleClass("none");
            },
            check:function(event,name,index){
                $(event.currentTarget).toggleClass("active");
                if($('.zb').eq(index).hasClass("active")){
                    this.recipe_self.push(name);
                }else{
                    this.recipe_self.remove(name);
                }
                console.log(this.recipe_self);
            },
            jysf:function(event,index){
              $(event.currentTarget).addClass("active").siblings().removeClass("active");
              if($('.zj').hasClass("active")){
                $('.jy_zb').removeClass('none');
              }else{
                $('.jy_zb').addClass('none');
              }
              this.is_tisane = index;
            },
            pop:function(){
              $('.tijiao').show();
            },
            history:function(){
              this.$router.push({path:'/cfpy_history'});
            },
            imgScare:function(){
              $('.imgScare').show();
            },
            close:function(){
              $('.imgScare').hide();
            },
            bg: function (url) {
                if (url) return 'background-image:url(' + url + ')'
            },
            closeTit:function(){
              $(".wx_tit").hide();
            }
        }
    };
</script>
