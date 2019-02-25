<template lang='jade'>
.fixbody
    header
        .center {{$store.state.currentPageName}}
        //.right
          i.addr
          span {{positionCity}}
    #wrap
        .search_box
            input(type="text" placeholder="搜索医生 / 疾病" v-model="keyword" @click="search()" readonly)
            i.icon-search(@click="search()")
        .swiper.banner#banner
            .swiper-container-banner
                .swiper-wrapper
                    a.swiper-slide(v-for="(s,index) in slider" v-if="index<5" v-bind:style="bg(s.image)" v-bind:href="s.url")
                        h4
                          span {{s.title}}
                          .gm {{s.desc}}

                .swiper-pagination
        //-var vl=['门诊预约','在线咨询','我的医生','健康数据','健康商城','视频看诊']
        .home_nav
            .box(@click="toNav(nav)" v-for="nav in navs")
                div.ico(v-bind:class='nav.icon')
                p {{nav.name}}

</template>
<script>
    export default {
        data(){
            return {
                slider:[],
                navs:[],
                keyword: '',
                clinicId:2,
                type: 0,
                positionCity: '北京'
            };
        },
        created(){
            this.type = this.$route.params.type;
            this.$store.commit("toggleHeaderStatus", true);
            this.$store.commit("toggleFooterStatus", true);
            this.getNav();
            //this.getMe();
            this.getSlider();
        },
        mounted:function(){
            setTimeout(function(){
              swpInit();
            },400);

            if(this.$store.state.tcmuser  && true){
                //$api.pop('微信接口在app上待开发，稍等');
                return;
            }
            wx.ready(function () {
                wx.onMenuShareTimeline({
                    title: '诚意为您推荐好中医【泰和国医】', // 分享标题
                    link: 'http://'+window.location.host+'/wechat/index', // 分享链接
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
                    title: '诚意为您推荐好中医【泰和国医】', // 分享标题
                    desc: '您可通过【泰和国医】微信公众账号关注医师，预约Ta的门诊或向Ta发起在线咨询。', // 分享描述
                    link: 'http://'+window.location.host+'/wechat/index', // 分享链接
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
        },
        methods:{
            getMap(){
                var self = this;
                if(this.$store.state.tcmuser && true){
                    // if(this.$store.state.apiready){
                    //     api.getLocation(function(ret, err) {
                    //         if (ret && ret.status) {
                    //             //获取位置信息成功
                    //             self.wxPosition(res);
                    //         } else {
                    //             //alert(JSON.stringify(err));
                    //         }
                    //     });
                    // }else{
                    //     setTimeout(function () {
                    //         self.getMap();
                    //     },1000);
                    // }
                    //$api.pop('微信接口在app上待开发，稍等');
                    return;
                }
                wx.ready(function () {
                    wx.getLocation({
                        type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
                        success: function (res) {
                            self.wxPosition(res);
                        },
                        cancel: function (res) {
                            self.positionCity = '北京';
                        },
                        fail: function (res) {
                            self.positionCity = '北京';
//                            $api.pop(JSON.stringify(res));
                        }
                    });
                })
            },
            wxPosition(res){
                this.$http.post(this.$store.state.apiUrl+'wxposition', {data:res}).then(function (res){
                    if(res.data.status){
                        console.log('城市：-----'+res.data.data.city);
                        if(res.data.data.city == '上海市'){
                            this.positionCity = '上海';
                        }else{
                            this.positionCity = '北京';
                        }
                        console.log('城市22222：-----'+this.positionCity);
                        window.localStorage.setItem("positionCity",this.positionCity);
                    }
                },function (error) {
                    $api.pop('error'+JSON.stringify(error));
                })
            },
            backTo(){
              $('.cityBox').addClass('none');
            },
            city(){
              $('.cityBox').removeClass('none');
            },
            search(){
                this.$router.push({ path: '/search', query:{type: 0}});
            },
            bg:function(url){
                if(url) return 'background-image:url('+url+')'
            },
            togNav:function (i){
              var _this =this;
            	var param='';
                switch (i){
                    case 0:
	                    param={path:'/doctor/type/1'};
                        break;
                    case 1:
                        param={path:'/doctor/type/2'};
                        break;
                    case 2:
                        param='/my_doctor';
                        break;
                    case 3:
	                    param='/data/1/0';
                        break;
                    case 4:
                        // $api.pop('更多精彩，敬请期待！')
                        _this.toDownload;
                        break;
                    case 5:
                        // $api.pop('更多精彩，敬请期待！')
                        _this.toDownload();
                        break;
                }
                this.$router.push(param);

            },
            toNav(nav){
                if(nav.route){
                    this.$router.push(nav.path);
                }else{
                    if(nav.path =='shop'){
						$api.pop(nav.msg)
                    }else if(nav.path='videokz'){
                        //$api.pop(nav.msg)
						this.toDownload(nav.data)
                    }
                }
            },
          //app下载
            toDownload:function(data){

              var u = navigator.userAgent;

              var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端

              var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端

              var isWeixinBrowser = (/micromessenger/i).test(navigator.userAgent);

              if(isAndroid){

                if(isWeixinBrowser){

                  this.isWeixin = 1

                  $('body').css('overflow','hidden')

                }else{

                  window.location.href = data.androidUrl;

                }



              }else if(isiOS){
                window.location.href = data.iosUrl;

              }

            },
            linkSearch:function (){
                var keyword = this.keyword;
                if(!keyword){
                    $api.pop('请输入搜索关键词');return;
                }
                this.$router.push({ path: '/search_result', query:{keyword: keyword}});
            },
            getMe() {
                this.$http({url:this.$store.state.apiUrl+'user/detail', method:'GET'}).then(function(res){
                    if(res.data.status){
                        window.localStorage.setItem("imToken",res.data.data.im_token);
                        window.localStorage.setItem("headimgurl",res.data.data.headimgurl);
                        window.localStorage.setItem("nickname",res.data.data.nickname);
                        window.localStorage.setItem("id",res.data.data.id);
                    }
                })
            },
            getNav(){
                this.$http.get(this.$store.state.apiUrl+'nav').then(function(res){
                    this.navs = res.data.data;
                })
            },
            getSlider() {
                this.$http({url:this.$store.state.apiUrl+'swiper/lists', method:'GET'}).then(function(res){
                    if(res.data.status){
                        this.slider = res.data.data;
                    }
                })
            },
            doctor(){
                this.$http({url:this.$store.state.apiUrl+'user/getLastClinicDoctor', method:'GET'}).then(function(res){
                    if(res.data.status){
                        this.$router.push({path:'/doctor_detail',query: { id: res.data.data.doctor_id}});
                    }else{
                        $api.pop(res.data.msg);
                    }
                });
            }
        }
    }
    function swpInit(){
        var swiper = new Swiper('.swiper-container-banner', {

            autoplay: 3600,
            observer: true,
            observeParents: true,
            pagination: '.swiper-pagination',
            paginationClickable: true,
            initialSlide: 0,
            onSlideChangeEnd: function(swiper){
                //console.log(swiper.activeIndex);
                swiper.update();
            }
        });
    }
</script>
