<template lang='jade'>
.fixbody
    header
        .left(onclick="back()")
            i.icon-arrow-left
        .center {{$route.query.type ==3?'视频问医':'在线咨询'}}预约
    .btn.btn-fix(@click="sure") 提交
    #patSet.pop
        .box
            .head.dz
                span 选择就诊人
                a(@click="add()") 就诊人管理
            .main
                ul
                    li(v-for="(a,ind) in patient" v-bind:class="patient_set == ind ? 'active':''" @click="setPat(ind,a.id)")
                        h4 {{a.realName}}
                        p {{a.sex}}，{{a.age}}岁
                        i.icon-check-c
            .foot(onclick="$('#patSet').fadeOut()") 确定

    #wrap.doctor_order
        .yes_no
            .one
                span.t 泰和国医患者
                .box
                    span(@click="fz('patient',1)", v-bind:class="{active:is_patient == 1}") 是
                    span(@click="fz('patient',0)", v-bind:class="{active:is_patient == 0}") 否
            .one
                span.t 诊断类型
                .box
                    span(@click="fz('type',1)", v-bind:class="{active:type == 1}") 咨询
                    span.fz(@click="fz('type',0)", v-bind:class="{active:type == 0}") 复诊
        ul.list-group(v-if='type == 1')
            li
                span.changesize 疾病
                .val(@click="inptfocus(1)")
                    //这里要用autocomplate组件
                    //input.showInput(type="text" v-model="disease" placeholder='请输入疾病名称' @focus='rise' @keyup='search' @blur='resultHide')
                    //input.showInput(type="text" v-model="disease" readonly)
                    input.showInput(type="text" v-model="disease" placeholder='请输入疾病名称' @focus='rise' @blur='resultHide')
                //- .result
                //-     ul
                //-         li(v-for='(item,index) in result' @click='chooseitem(item)')
                //-             span {{ item }}
                //- .disease_choose(@click='chooseDisease(".showInput")')
                //-     i.icon-arrow-down
        .panel
            h3 症状描述（{{symptom.length}}/300）
            .txt(@click="inptfocus(2)")
                textarea.textVal(placeholder="详细描述您的病情，治疗经过，症状以及想要得到的帮助，泰和国医将确保您的隐私安全。" v-model="symptom" @focus='rise')
        .panel(v-if="type==0")
            h3 症例
            .txt
                p 为方便医生更好的诊断，请上传初诊病历，建议上传舌象、面相等与病情相关的图片
                .imgBox
                  .showimage(v-for='(image,index) in photo')
                    i.cancel(@click='cancel(index)')
                    .img(v-bind:id="'photo'+index" @click='wxUpload(index,$event)' v-bind:style="'background-image:url('+image+');background-size:100% 100%;'")
                  //- .showimage
                  //-   i.cancel(@click='cancel(index)')
                  //-   .img(style="background:red")
                    //i.icon-camera
                    //span 添加照片
                  .box.addimage(@click="wxUpload(images,$event)")
                    i.icon-camera
                    span 添加照片

    .popop
        .box
            .main
                ul
                    li.name_tit
                        span.span_c {{user.realname}}
                        span(v-if="user.sex == 2") 女士：
                        span(v-if="user.sex == 1") 先生：
                        //i.icon-close-c(@click="know()")
                    li {{doctor.name}}医生将在30分钟内回复，请耐心等待。如果{{doctor.name}}医生无法接诊，医生助理将为您提供服务。
                    li.ys_nr.clearfix
                        .left
                            img(v-bind:src="doctor.photoSUrl")
                            .dBox
                                span.name {{doctor.name}}
                                span {{doctor.title | return_title}}
                        .right
                          span.zf {{$route.query.type ==3?'视频':'咨询'}}费：
                          span.price ￥{{$route.query.type ==3 ? doctor.video_amount:doctor.web_amount}}
            .foot(@click="know()") 知道了

    //图片上传提示信息

    .layer_pop.none
        .content
            .txt 最多只能上传五张照片
            .pop_btn.clearfix
                .p_btn.l(@click="queding()" style='width:100%') 确定

    //预约提示信息

    //.ordertips.none
        .content
            .txt 距离就诊时间24小时之内不能取消
            .pop_btn.clearfix
                .p_btn.l(@click="dodel()") 确定
                .p_btn(@click="canceldel()") 取消

</template>
<script>
    import {errorMsg} from '../../vuex/store';
    export default {

        data(){
            return{
                patient_set:0,
                patient:[
                    {
                        id:'',
                        name:'',
                        sex:'',
                        age:''
                    }
                ],
                prices_set:0,
                prices:['100-199','200-299','300-399','400+'],
                prices_id:0,
                bespeak_id:0, //预约id1111//
                disease:'',
                symptom:'',
                long:0,
                tag:0,
                info:[],
                user: {},
                doctor: {},
                fuzhen:0,
                is_patient: 0,//默认不是本院患者
                type: 1, //默认初诊
                photo: [],
                u:'',
                isAndroid:'',  //判断是否是安卓设备
                oldHeight:document.body.clientHeight,
                screenHeight: document.body.clientHeight,
                images:0,
                result:[],
                nothing:1,
                data:{},     //提交的数据
                ceshi:['肿瘤疾病','外科疾病','肾科疾病','眼科疾病','消化疾病','呼吸疾病','儿科疾病']
            }
        },

        mounted(){

            const that = this
                window.onresize = () => {
                    return (() => {
                        window.screenHeight = document.body.clientHeight
                        that.screenHeight = window.screenHeight
                })()
            }

          this.phone();
          // FastClick.attach(document.body);

        },

        created(){
            this.id = this.$route.query.id;
            // this.$store.state.apiUrl = '/api'
        },

        filters:{
            return_title(val){
                switch (val){
                  case 1:
                    return '主任医师';
                    break;
                  case 2:
                    return '副主任医师';
                    break;
                  case 3:
                    return '主治医师';
                    break;
                  case 4:
                    return '知名专家';
                    break;
                  case 5:
                    return '特聘专家';
                    break;
                  case 6:
                    return '名老中医';
                    break;
                }
            }
        },
        watch: {
            screenHeight (val) {

                if(this.screenHeight==this.oldHeight){

                    $('.yes_no').css({display:'block'})

                }else{

                    $('.yes_no').css({'display':'none'})
                }
            },
            bespeak_id(val){
                if(val>0){
                    this.get_bespeak();
                }
            }

            //疾病实时搜索

            // disease(val){

            //     $('.result').css('display','block')

            //     this.result=[]

            //     for(var i = 0; i < this.ceshi.length;i++){

            //         if(val){

            //             if(this.ceshi[i].indexOf(val)>-1){

            //                 this.result.push(this.ceshi[i])

            //             }

            //         }
            //     }

            //     if(val){

            //         if(this.result.length<1){

            //             this.result.push('未查询到结果')

            //         }
            //     }
            // }
        },

        methods:{
          inptfocus(val){
            if(val==1){
              $('.showInput').focus();
            }else if(val==2){
              $('.textVal').focus();
            }

            // alert('123231')

          },
            //疾病实时搜索

            search(){

                $('.result').css('display','block')

                this.result=[]

                for(var i = 0; i < this.ceshi.length;i++){

                    this.nothing=1

                    if(this.disease){

                        var tem=this.disease.split('')

                        for(var j = 0;j < tem.length;j++){

                           if(this.ceshi[i].indexOf(tem[j])==-1){

                                this.nothing=0

                                break

                            }

                        }

                        if(this.nothing){

                            this.result.push(this.ceshi[i])

                        }

                        //console.log(tem)

                    }
                }

                console.log(this.result)

                if(this.disease){

                    if(this.result.length<1){

                        this.result.push('未查询到结果')

                    }
                }

            },

            //失去焦点隐藏搜索结果

            resultHide(){

                $('.result').css('display','none')

            },

            //点击搜索结果

            chooseitem(item){

                if(item!='未查询到结果'){

                    this.disease=item;

                    setTimeout(function(){

                       $('.result').css('display','none')

                    },1)

                }

            },

            //判断设备

            phone(){

                this.u = navigator.userAgent;

                console.log(this.u)

                this.isAndroid = this.u.indexOf('Android') > -1 || this.u.indexOf('Adr') > -1; //android终端

                //this.isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端

            },

            //解决软键盘挡住输入框问题

            rise(){

                if(this.isAndroid){

                    $('.yes_no').css({'display':'none'})

                }

            },

            drop(){

                if(this.isAndroid){

                    $('.yes_no').css({display:'block'})

                }
            },

            bg: function (url) {

                if (url) return 'background-image:url(' + url + ')'

            },

            queding(){

                $('.layer_pop').addClass('none');

            },

            cancel(index){

                this.photo.splice(index,1)

                this.images--

                if(this.images<5){

                    $('.addimage').css('display','inline-block')

                }

            },
            appUpload(index,e){
                var _this = this;
                AppUpload(function (pic_data) {
                    if(pic_data&&pic_data.status){
                        _this.photo.splice(index,1,pic_data.data.image_url);
                        if(_this.images==4){
                            $('.addimage').css('display','none')
                        }
                        if(e.target.className=='box addimage'||e.target.className=='icon-camera'||e.target.tagName=='SPAN'){
                            _this.images++
                        }
                        //$('#photo'+index).css({"background":"red"});
                    }else{
                        $api.pop('上传失败');
                    }
                });
            },
            //上传图片
            wxUpload:function(index,e){

              if(e.target.className=='box addimage'||e.target.className=='icon-camera'||e.target.tagName=='SPAN'){

                if(this.images>4){

                    $('.layer_pop').removeClass('none');

                    return false
                }

              }
              if(this.$store.state.tcmuser && true){
                  this.appUpload(index,e);
                  //$api.pop('微信接口在app上待开发，稍等');
                  return;
              }

              if(!this.$store.state.wxready){
                $api.pop('微信上传未就绪，稍等');
                return;
              }
              var self = this;
              wx.ready(function () {
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
                        self.wxUploadId(serverId,index,e);
                      },
                      fail:function(res){
                        alert(JSON.stringify(res));
                      }
                    });
                  },
                  fail:function(res){
                    alert('选图失败'+res.errMsg+',请重试');
                    setTimeout(function(){
                      window.location.reload();
                    },200);
                  }
                });
              });
            },

            wxUploadId:function(serverId,index,e){
              var _this = this;
              $('body').addClass('loading');
              this.$http.post(this.$store.state.apiUrl+'wxupload', {serverId:serverId}).then(function (res){

                $('body').removeClass('loading');

                var pic_data = res.data;

                if(pic_data.status){

                  _this.photo.splice(index,1,pic_data.data);

                  if(_this.images==4){

                      $('.addimage').css('display','none')

                  }

                  if(e.target.className=='box addimage'||e.target.className=='icon-camera'||e.target.tagName=='SPAN'){

                      _this.images++

                  }

                  //$('#photo'+index).css({"background":"red"});

                }else{
                  $api.pop('上传失败');
                  setTimeout(function(){
                    window.location.reload();
                  },200);
                }
              })
            },
            fz:function(event,i){
                if(event == 'patient'){
                    this.is_patient = i;
                }else if(event == 'type'){
                    this.type = i;
                }
            },
            setPat:function(id,p_id){
                this.patient_set = id;
                this.patient_id = p_id;
            },

            //点击提交事件

            sure(){

                this.data.redundant_first = this.type; // 1:初诊 0:复诊
                this.data.redundant_in = this.is_patient; //1是 0否
                this.data.doctor_id = this.id;
                this.data.disease = this.disease;
                this.data.desc = this.symptom;
                this.data.local_type = this.$route.query.type;

                // 判断

                if(!this.type){
                  // if(!this.photo.length){
                  //   $api.pop('请上传症例照片');
                  //   return false;
                  // }
                  // if(this.photo.length==1){
                  //   $api.pop('请上传症例照片');
                  //   return false;
                  // }else if(this.photo.length>1){

                    this.data.disease = this.photo;

                  // }

                }else{

                    if(!this.data.disease){

                      $api.pop('疾病不能为空');

                      return false;
                    }

                    //判断所填疾病是否存在

                    // if(this.ceshi.indexOf(this.data.disease)==-1){

                    //     $api.pop('请选择正确的疾病名称');

                    //     return false;

                    // }

                    if(!this.data.desc){

                        $api.pop('疾病描述不能为空');

                        return false;
                    }
                }

                this.submit()

            },

            //确认与取消事件

            dodel(){

                this.submit()

                //$('.ordertips').addClass('none');

            },

            canceldel(){

                //$('.ordertips').addClass('none');

            },

            submit(){

                if(!this.tag){

                    this.$http.post(this.$store.state.apiUrl+'bespeak/web',this.data).then(function (res,error) {
                      this.tag = 1;
                      if(res.data.status==1){
                        this.bespeak_id = res.data.data.bespeak_id;
                        $('.popop').fadeIn();
                      }else{
                        $api.pop(res.data.msg);
                      }
                    },function (res) {
                      errorMsg(res.data.data.errors);
                    });
                }

            },
            // doctor(){
            //     this.$http.get(this.$store.state.apiUrl+'doctors/show/'+ this.id).then(function (res) {
            //         this.doctor = res.data.data;
            //     });
            // },
            know(){
                $('.popop').fadeOut();
                this.$router.push({path:'/order'});
            },
            navTog:function(i){
                if(i==3&&!$('.top_nav li').eq(3).hasClass('active')){
                    $('.calendar').addClass('active')
                }else{
                    $('.calendar').removeClass('active')
                }
                $('.top_nav li').eq(i).toggleClass('active').siblings().removeClass('active');
            },
            navClose:function (){
                $('.calendar').removeClass('active');
                $('.top_nav li').removeClass('active');
            },
            det:function(id){
                this.$router.push({path:'/doctor_det/id',query: { id: id }});
            },
            order:function(){

            },
            bg:function(url){
                if(url) return 'background-image:url('+url+')'
            },
            cT:function(i){
                switch (i){
                    case 1:
                        return '已完成';
                        break;
                    case 2:
                        return '不在线';
                        break;
                    case 3:
                        return '商城';
                        break;
                    case 4:
                        return '推荐';
                        break;
                }
            },
            add(){
                this.$router.push({path:'/my_myfml/my'});
            },
            get_bespeak(){
                this.$http.get(this.$store.state.apiUrl+'bespeak/detail/'+ this.bespeak_id + '?include=doctor,user').then(function (res) {
                    this.info = res.data.data;
                    if(res.data.data.doctor){
                        this.doctor = res.data.data.doctor.data;
                        if(!this.doctor.photoSUrl){
                          this.doctor.photoSUrl = '/img/doctor_default.png';
                        }
                    }
                    if(res.data.data.user){
                        this.user = res.data.data.user.data;
                    }
                    // if(res.data.data.type && res.data.data.time_diff_hours < 24){
                    //     $('.ordertips').removeClass('none');
                    // }
                });
            },

            //疾病选择

            chooseDisease(e){
                var self=this;
                $(e).blur();
                var data1=[{'id': '110000', 'value': '肿瘤科','parentId': '0'},{'id': '120000', 'value': '外科','parentId': '0'},{'id': '130000', 'value': '肾内科','parentId': '0'},{'id': '140000', 'value': '眼科','parentId': '0'},{'id': '150000', 'value': '消化科','parentId': '0'},{'id': '160000', 'value': '呼吸科','parentId': '0'},{'id': '170000', 'value': '儿科','parentId': '0'}]
                var data2=[{'id': '110100', 'value': '肿瘤疾病', 'parentId': '110000'},{'id': '120100', 'value': '外科疾病', 'parentId': '120000'},{'id': '130100', 'value': '肾科疾病', 'parentId': '130000'},{'id': '140100', 'value': '眼科疾病', 'parentId': '140000'},{'id': '150100', 'value': '消化疾病', 'parentId': '150000'},{'id': '160100', 'value': '呼吸疾病', 'parentId': '160000'},{'id': '170100', 'value': '儿科疾病', 'parentId': '170000'}]
                var showDom = document.querySelector(e);// 绑定一个触发元素
                var valDom = document.querySelector(e);  // 绑定一个存储结果的元素
                var oneId = showDom.dataset['one_id'];
                var oneValue = showDom.dataset['one_value'];
                var twoId = showDom.dataset['two_id'];
                var twoValue = showDom.dataset['two_value'];
                //var title = showDom.dataset['value'];        // 获取元素的data-value属性值
                // 实例化组件

                var example = new IosSelect(2,               // 第一个参数为级联层级，演示为1
                    [data1,data2],                             // 演示数据
                    {
                        container: 'body',             // 容器class
                        title: '疾病名',                    // 标题
                        itemHeight: 40,                      // 每个元素的高度
                        itemShowCount: 3,                   // 每一列显示元素个数，超出将隐藏
                        relation: [1, 1],
                        oneLevelId: oneId,                    // 第一级默认值
                        twoLevelId: twoId,
                        callback: function (selectOneObj,selectTwoObj) {  // 用户确认选择后的回调函数

                            self.disease=selectTwoObj.value

                            // setTimeout(function(){

                            //     $('.result').css('display','none')

                            // },1)

                            $('.showInput').val(selectTwoObj.value)

                        }
                    });
            }
        }
    };
</script>
