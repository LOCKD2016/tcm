<template lang='jade'>
  .fixbody
    header
      .left(onclick="back()")
        i.icon-arrow-left
      .center 支付完成

    #wrap
      .panel.success_box
        .img
        h3 {{user.realname}}已预约成功 {{doctor.name}}医师
        //h3 {{user.realname}}已预约成功，请准时就诊
        p {{bespeak.start_time}}

      .panel.user
        .addr
          i.icon-location
          h3 {{cliniques.name}}
              a(href='//m.amap.com/navi/?start=&dest=116.451507,39.937215&destName=%E6%B3%B0%E5%92%8C%E5%9B%BD%E5%8C%BB&key=f429bc8bfc2f54d86dc399ea513ca3d5') 进入导航
          p {{address}}
        .mapBox
            img(src='../../../static/img/map1.png')
          //- iframe(frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='//f.amap.com/3x1vK_03849LM')
          //- iframe(frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='http://j.map.baidu.com/WMAuP')
          //- iframe(frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='//m.amap.com/navi/?dest=116.451122,39.93728&destName=北京市朝阳区新东路12号院 首开铂郡南区&hideRouteIcon=1&key=1d9644db347ce2e1d157e19ed878932e')
      .myDD
        .btnDD(@click='order()') 查看我的预约

</template>
<script>
    export default{
        data() {
            return{
                user,
                bespeak:[],
                doctor:[],
                store:[],
                cliniques: {},
                address: ''
            }
        },
        created(){
            this.bespeak_id = this.$route.query.bespeak_id;
            this.getSub();
            this.getClinique();
        },
        mounted(){

            if(this.$store.state.tcmuser  && true){
                //$api.pop('微信接口在app上待开发，稍等');
                return;
            }
            let dd = this;
            wx.ready(function () {
                wx.onMenuShareTimeline({
                    title: '您已成功预约 '+dd.doctor.name+'医师', // 分享标题
                    link: protocol+window.location.host+'/wechat/order_details?id='+dd.info.id, // 分享链接
                    imgUrl: protocol+window.location.host+dd.doctor.avatar, // 分享图标
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
                    title: '您已成功预约 '+dd.doctor.name+'医师', // 分享标题
                    desc: '预约时间：'+dd.info.sub_date, // 分享描述
                    link: protocol+window.location.host+'/wechat/order_details?id='+dd.info.id, // 分享链接
                    imgUrl: protocol+window.location.host+dd.doctor.avatar, // 分享图标
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
            getSub(){
                this.$http.get(this.$store.state.apiUrl+'bespeak/detail/'+ this.bespeak_id+'?include=user,doctor').then(function (res) {
                    if(res.data.status){
                        this.bespeak = res.data.data;
                        this.user = res.data.data.user.data;
                        this.doctor = res.data.data.doctor.data;
                    }else{
                        $api.pop(res.data.msg);
                    }

                });
            },
            order(){
                this.$router.push({path:'/order'});
            },
            getClinique(){
              this.$http.get(this.$store.state.apiUrl + 'clinique/lists').then(function (res) {
                this.cliniques = res.data.data;
                this.address = res.data.data.content.address;
              })
            }
        }
    }
</script>
