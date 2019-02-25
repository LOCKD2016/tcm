<template lang='jade'>
.fixbody
    form.search_bar(action="" onsubmit="$api.pop($('#so').val())")
        input#so(type="search" v-model="name" )
        span(onclick="$('.search_bar').hide()") 取消

    header(onclick="$('.calendar,.top_nav li').removeClass('active');")
        .left(@click="back()")
            i.icon-arrow-left
        .center
            span {{title}}
            //sub(@click="city()")
                span {{chooseCity}}
                i.icon-arrow-down
        //.right(@click="linkSearch()")
            i.icon-search
        //.right(@click="city()")
            i.addr
            span {{chooseCity}}
    //选择城市
    .cityBox.none
        header
            .left(@click="backTo()")
                i.icon-arrow-left
            //.center 当前地区－北京
        ul
          li(v-for="(it,ind) in cities" v-bind:class="ind==city_set?'active':''" @click="setCity(ind,it.yid,it.city)") {{it.city}}
            i.icon-check
    form.search_box(action="javascript:return true;")
        input.searchInp(type="search" placeholder="搜索医生 / 疾病" v-model="keyword" @keydown.enter="enter($event)")
        i.icon-search(@click="search()")
    .top_nav(v-if="!disease")
        //-var nv = ['医馆','诊费','专科','时间']
        -var nv = ['门诊部','科室','挂号','日期']
        //-var nm = ['科室','在线咨询','面对面问诊']
        ul(v-if="$route.params.type==1")
            each vm,i in nv
                li
                    .tog_btn(@click="navTog(#{i})")
                        span=vm
                        i.icon-arrow-down
                    .drop
                        .bg(@click="navClose()")
                        -if(i==0)
                            .box
                                .hosp_li(v-for="(it,ind) in hospitals" v-bind:class="ind==hospital_set?'active':''" @click="setClinicid(ind,it.id)")
                                    h3(v-if="it.id==0") {{it.city}}
                                    template(v-else)
                                        h3 {{it.name}}医院
                                        h3 {{it.address}}{{it.content.address}}
                                        h3 {{ it.content.telephone }}
                                          //label(@click="map(it.url)") 进入地图
                                        //p 北京市朝阳区望京博泰国际B座615
                                        i.icon-check-c
                                        span.addr_a
                        -else if(i==1)
                            //.box.box_2
                                .pricesBox.clearfix
                                    .prices(v-for="(it,ind) in prices" @click="setPrice(ind,it.feeGT,it.feeLT)" v-bind:class="{active:ind==0}") {{it.price}}
                                        span
                                        i.icon-check
                            .box.clearfix.box_hg(v-if="department")
                                .department(v-for="(it,ind) in department" @click="setDepartment(ind,it.id)" v-bind:class="{active:section_id==it.id}")  {{it.name}}

                        -else if(i==2)
                            .box.clearfix.box_hg(v-if="titles")
                                //.department.all(v-bind:class="'active'") 不限
                                     //span
                                     //i.icon-check
                                .department(v-for="(t,ind) in titles" @click="setTitle(ind,t.id)" v-bind:class="{active:(t && t.id==titleName)}")  {{t | has_name}}
                                     //span

                                     //i.icon-check
        ul(v-if="$route.params.type!=1" class="col3")
            li(v-for="(nav,index) in navs" v-if="index !=2 || (index ==2 && !$store.state.tcmuser)")
                .tog_btn(@click="navTog(index)")
                    span {{nav}}
                    i.icon-arrow-down
                .drop
                    .bg(@click="navClose()")
                        .box.clearfix.box_hg(v-if="index == 0")
                            .department(v-for="(it,ind) in department" @click="setDepartment(ind,it.id)" v-bind:class="{active:section_id==it.id}")  {{it.name}}
                        .box.clearfix.box_hg(v-else-if="index == 1")
                            .prices(v-for="(it,ind) in prices" @click="setPrice(ind,it.price)" v-bind:class="{active:ind==0}") {{it.price}}
                                //span
                                //i.icon-check
                        .box.clearfix.box_hg(v-else-if="index == 2" @click='ceshi')
                            p.wr 非常抱歉，面对面问诊目前只在APP端进行
                            p.ck 点击前往下载泰和国医APP >
                            p.face
    #wrap
        .swiper-container.swiper-container-list
            .orderBOX.swiper-wrapper
                .swiper-slide
                    .panel
                        h4.tit_illness.no.none 没有找到您要找的医师
                        h4.tit_illness.yes.none 以下是治疗
                            span {{disease}}
                            | 的医师
                        .doctor_list(v-for="list in lists")
                            .link(v-if="list" @click="det(list.id)")
                                .avatar(v-if='list.photoSUrl' v-bind:style="bg(list.photoSUrl)")
                                .avatar(v-else v-bind:style="bg('/img/doctor_default.png')")
                                //- .introduction 专家介绍
                                h3
                                    span {{list.name}}
                                    sub {{list.title | return_title}}
                                    //label 在线
                                    //label.active 在线
                                //h5 {{list.cliniques.data[0].name}}
                                p.labBox 擅长：
                                    //- span(v-for="(e,index) in list.diseases.data" v-if="index<2") {{e.name}}
                                    span {{ list.diseases.data | ceshi }}
                                //.price(v-if="type==0") {{list | netMoney(list)}}
                                //.price(v-else) {{list | clinicMoney(list)}}
                                h6
                                    span 患者推荐指数：
                                    .stars(v-bind:show="list.level")
                                        i.icon-nav1
                                        i.icon-nav1
                                        i.icon-nav1
                                        i.icon-nav1
                                        i.icon-nav1
                            .btn.btn-jv(v-if="type==1" @click="order(list.id,list.isSchedules)") 预约
                            //- .btn.full(v-if="type==1 && !list.isSchedules") 咨询已满
                            //- .btn.full(v-if="type==2 && list.rest==1") 咨询已满
                            .btn.btn-jv(v-if="type!=1" @click="order(list.id,list.rest)") {{type==2?'在线咨询':'视频问医'}}
                    .visible(@click='loadMore')

    .layer_pop.none
      .content
        .txt 请先去完善信息！
        .pop_btn.clearfix
          .p_btn.l(@click="dodel()") 确定
          .p_btn(@click="canceldel()") 取消

</template>
<script>
    import {errorMsg} from '../../vuex/store';
    export default {
        name:'doctorlist',
        data(){
            return {
                navs:['科室','在线咨询','面对面问诊'],
                title:this.$route.params.type==1?'门诊预约':(this.$route.params.type==2?'在线咨询':'视频问医'),
                type:this.$route.params.type,
                hospital_set:0,
                city_set:0,
                chooseCity:'北京',
                feeLT:'',
                feeGT:'',
                clinicid:0,
                date:'',
                photoSUrl:'',
                hospitals:[],
                prices_set:0,
                prices:[
                    {
                        id:0,
                        price:'不限',
                        feeGT:'',
                        feeLT:''
                    },
                    {
                        id:1,
                        price:'100-199',
                        feeGT:100,
                        feeLT:199
                    },
                    {
                        id:2,
                        price:'200-299',
                        feeGT:200,
                        feeLT:299
                    },
                    {
                        id:3,
                        price:'300-399',
                        feeGT:300,
                        feeLT:399
                    },
                    {
                        id:4,
                        price:'400+',
                        feeGT:400,
                        feeLT:10000
                    }

                ],
                cities:[
                    {
                        id:1,
                        city:'北京'
                    },
                    {
                        id:3,
                        city:'其他'
                    }
                ],
                price:0,
                recommend:0, //0不推荐 1推荐
                department_set:0,
                section_id: 0,
                cityId:0,
                mark:0,
                perPage:0,
                department:[],
                times:[],
                keyword: '',
                page:1,
                lists:[],
                feeGTall:[],
                feeLTall:[],
                cinnique:[],
                clinicPrice:[],
                dateTime:this.$store.state.searchDate,
                swiper:"",
                name:"",
                statusOrder:true,
                titles: {},
                titleName: '',//职称
                is_search:0
            };

        },//
        filters:{

            ceshi(value){

                console.log(value)

                var str = ''

                if(value.length>2){

                    value.forEach(function(v){

                        str += v.name + '  '

                    })

                    return str.trim()+'...'

                }else if(value.length > 0 && value.length <= 2){

                    value.forEach(function(v){

                        str += v.name + '  '

                    })

                    return str.trim()+'...'

                }

            },

            netMoney(value){
                return parseInt(value.net_money) ? "￥"+parseInt(value.net_money) : '免费';
            },
            clinicMoney(value){
                return parseInt(value.clinic_money) ? "￥" + parseInt(value.clinic_money) : '免费';
            },
            has_name(val){
                if(val){
                    return val.name;
                }
            },
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
        mounted(){
            this.getList();
            var self=this;
            setTimeout(function(){
                self.swiper = new Swiper('.swiper-container-list', {
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
                                self.getList();
                            }
                        }
                    }
                });
            },400);
        },
        created(){
            this.disease = this.$route.query.disease;
            this.getSection();
            this.getClinique();
            this.getTitles();
            // this.$store.state.apiUrl = '/api'
            this.$store.commit('setincludePage','doctorlist')
            if(this.$route.params.type == 2){
                this.navs = ['科室','在线咨询','面对面问诊'];
            }else if(this.$route.params.type ==3){
                this.navs = ['科室','视频问医','面对面问诊'];
            }
        },
        activated() {

            if(this.swiper){
                this.swiper.setWrapperTranslate(this.$store.state.translate)
            }

        },
        beforeRouteLeave(to,from,next){
            console.log(to)
            // if(to.fullPath == '/'){
            //     this.$store.commit('setincludePage','')
            // }
            if(to.fullPath.length < from.fullPath.length){
                this.$store.commit('setincludePage','')
            }
            next();
        },
        events: {
            update(){
                this.page = 1;
                this.lists = [];
                this.getList();
            }
        },
        watch:{
            dateTime(){
                this.page = 1;
                this.recommend = 0;
                this.lists = [];
                this.getList();
            },
            clinicid(val){
                if(val > 0){
                    this.page = 1;
                    this.recommend = 0;
                    this.lists = [];
                    this.getList();
                }
            }
        },
        methods:{

            ceshi(){

              var u = navigator.userAgent;

              var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端

              var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端

              var isWeixinBrowser = (/micromessenger/i).test(navigator.userAgent);

              if(isAndroid){

                if(isWeixinBrowser){

                  this.isWeixin = 1

                  $('body').css('overflow','hidden')

                }else{

                  window.location.href = ''

                }



              }else if(isiOS){
                window.location.href = 'https://itunes.apple.com/cn/app/%E6%B3%B0%E5%92%8C%E5%9B%BD%E5%8C%BB/id1353271770?mt=8'

              }


            },

          //确定  取消

          canceldel(){
            $('.layer_pop').addClass('none');
          },
          dodel(){
            $('.layer_pop').addClass('none');
            this.$router.push({path:'/my_fmld/my'});
          },
            //回车事件
              enter(event){
                if (event.keyCode == "13") {  //js监测到为为回车事件时 触发
                  $('.searchInp').blur()
                  this.search();
                }
              },
            backTo(){
              $('.cityBox').addClass('none');
            },
            city(){
              $('.cityBox').removeClass('none');
            },

            //点击加载更多

            loadMore(){

                if(this.statusOrder){

                    this.getList()

                }

            },

            getList(){
                var self = this;
                var new_type = '';
                var cate = 'lists';
                if(this.$route.params.type == 1){
                    new_type = 'clinic';
                }else if(this.$route.params.type == 3){
                    new_type = 'video';
                }else {
                    new_type = 'web';
                }
                // if(this.recommend==1){
                //     cate = 'recommend';
                // }

                this.$http({url:this.$store.state.apiUrl+'doctor/'+cate+'?include=diseases',method:'GET',params:{
                    page:this.page,type: new_type, sections_id: this.section_id,fees:this.clinicPrice, title: this.titleName,
                    name: this.keyword, clinique: this.clinicid, schedule: this.dateTime
                }}).then(function (res) {
                    this.nextList(res.data.data,self);
                },function (response) {
                    errorMsg(response.data.data.errors);
                });
            },
            nextList(data,self){

                var self = this;

                if(!data.count && this.keyword){

                    $('.no').removeClass('none')

                    $('.visible').html('')

                    return false

                }

                let oLoadTip = $('.orderBOX').find('.visible');
                data.list.forEach(e =>{
                    self.lists.push(e);
                });

                if(data.list.length > 0){
                  self.page ++;
                }
                if(self.page> data.totalPage){

                    oLoadTip.html('没有更多数据了');

                    self.statusOrder = false;
                } else {
                    self.statusOrder = true;
                    oLoadTip.show();
                    oLoadTip.html('点击加载');
                }

                setTimeout(function(){
                    self.swiper.update();
                    self.show();
                },400);
                if(!data.count && self.recommend==0){
                    self.recommend = 1;
                    self.getList();
                }
                self.total = data.perPage;

                // setTimeout(function(){

                //    console.log($('.panel').height())

                //     if($('.orderBOX').height()>$('.panel').height()){

                //         oLoadTip.hide()

                //     }else{

                //         oLoadTip.show()

                //     }

                // },200)
            },
            getSection(){
                this.$http({url:this.$store.state.apiUrl+'section/lists',method:'GET'}).then(function (res) {
                  console.log(res)
                    this.department = res.data.data;
                    this.department.unshift({id:0 ,name:"不限"});
                },function (response) {
                    errorMsg(response.data.data.errors);
                });
            },
            getClinique(){
                this.$http({url:this.$store.state.apiUrl+'clinique/lists',method:'GET',params:{'city':this.chooseCity}})
                    .then(function (res) {
                    this.hospitals = res.data.data;
                },function (response) {
                    errorMsg(response.data.data.errors);
                });
            },
            setClinicid(index,id){
                this.hospital_set = index;
                this.clinicid = id;
                this.navClose();
            },
            show(){
                if(this.disease && this.recommend == 0){
                    $('.yes').removeClass('none');
                }
                if(this.recommend == 0){
                    $('.no').addClass('none');
                }else if(this.recommend == 1){
                    if(this.lists.length > 0){
                      $('.no').removeClass('none');
                      $('.yes').addClass('none');
                    }
                }
            },
            setCity(index,id,city){
                this.city_set = index;
                this.cityId = id;
                this.chooseCity = city;
                $('.cityBox').addClass('none');
                this.getClinique();
            },
            setPrice(index){
                if(index > 0){
                    $('.prices').eq(0).removeClass('active');
                    if($('.prices').eq(index).hasClass('active')){
                        $('.prices').eq(index).removeClass('active');
                        this.clinicPrice.remove(this.prices[index].price);
                    }else{
                        $('.prices').eq(index).addClass('active');
                        this.clinicPrice.push(this.prices[index].price);
                    }
                }else{
                    $('.prices').eq(0).addClass('active');
                    $('.prices').eq(0).siblings().removeClass('active');
                    this.clinicPrice = [];
                }
                console.log(this.clinicPrice);
                this.page = 1;
                this.recommend = 0;
                this.lists = [];
                this.swiper.setWrapperTranslate(0);
                this.getList();
                this.navClose();
            },
            setDepartment(index,section_id){

                //                if(index > 0){
                //                    if($('.department').eq(index).hasClass('active')){
                //                        this.section_id.remove(section_id);
                //                        $('.department').eq(index).removeClass('active');
                //                    }else{
                //                        $('.department').eq(index).addClass('active').siblings().removeClass('active');
                //                        this.section_id.push(section_id);
                //                    }
                //                    $('.department').eq(0).removeClass('active');
                //                }else{
                //                    $('.department').eq(0).addClass('active');
                //                    $('.department').eq(0).siblings().removeClass('active');
                //                    this.section_id = [];
                //                }
                this.section_id = section_id;
                $('.department').eq(index).removeClass('active');
                console.log(this.section_id);
                this.recommend = 0;
                this.page = 1;
                this.lists = [];
                this.swiper.setWrapperTranslate(0);
                this.getList();
                this.navClose();
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
            order:function(id,can){
                //记录当前滚动位置

                this.$store.commit('setTranslate',this.swiper.translate)

                var self = this;
                this.$http.get(this.$store.state.apiUrl+'user/complete').then(function (res) {
                console.log(res)
                     if(res.data.status == 1){
                         if(self.$route.params.type == 1){

                            if(can == 1){

                                self.$router.push({path:'/doctor/outline',query: { id: id,clinicId:self.clinicid , type:self.type}});

                            }else{

                                self.$router.push({path:'/doctor_detail',query: { id: id ,clinicId:self.clinicid, type:self.type}});

                            }

                         }else{

                            if(!can){
                                self.$http.get(self.$store.state.apiUrl+'bespeak/can/'+id).then(function (result) {

                                  if(result.data.status){

                                    self.$router.push({path:'/doctor/preOnline',query: { id: id,type:self.type}});

                                  }else{

                                    $api.pop(result.data.msg);

                                  }

                                });

                            }else{

                                self.$router.push({path:'/doctor_detail',query: { id: id ,clinicId:self.clinicid, type:self.type}});

                            }

                         }
                     }else{

                       $('.layer_pop').removeClass('none');

                     }
                });
            },
            bg:function(url){
                if(url) {
                    return 'background-image:url('+url+')'
                }
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
            map:function(url){
                if(this.$store.state.tcmuser && true){
                    var updateLink = url;
                    api.openApp({
                        iosUrl : updateLink,
                        androidPkg : 'android.intent.action.VIEW',
                        mimeType : 'text/html',
                        uri : updateLink
                    }, function(ret, err) {

                    });
                }else{
                    location.href = url;
                }
            },
            linkSearch:function (){
                this.$router.push({ path: '/search', query:{type: this.type}});
            },
            back(){
                window.history.back(-1);
            },
            getTitles(){
                this.$http.get(this.$store.state.apiUrl+'doctor/title').then(function (res) {
                    this.titles = res.data.data;
                },function (response) {
                    errorMsg(response.data.data.errors);
                })
            },
            setTitle(index,id){
                this.titleName = id;
                this.page = 1;
                this.recommend = 0;
                this.lists = [];
                this.swiper.setWrapperTranslate(0);
                this.getList();
                this.navClose();
            },
            search(){
                if(!this.keyword){
                    $api.pop('请输入 医生 / 症状 / 疾病');
                    return false;
                }
                this.is_search = 1
                this.page = 1;
                this.lists = [];
                this.getList();
                this.navClose();
            },
            det:function(id){
                this.$store.commit('setTranslate',this.swiper.translate)
                this.$router.push({path:'/doctor_detail',query: { id: id ,clinicId:this.clinicid, type:this.type}});
            },
        }
    };
</script>
<style>
    .search_box{
        z-index: 9;
    }
    .top_nav {
        z-index: 5;
    }
    .wrap {
        z-index: 1;
    }
</style>

