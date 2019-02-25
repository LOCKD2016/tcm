<template lang="jade">
    #app
        .outter(:class="{'hideLeft':$route.path.split('/').length>2}")
            footer(v-if="$store.state.footerStatus")
                foot
            router-view(name="default")
        transition(name="custom-classes-transition",:enter-active-class="enterAnimate",:leave-active-class="leaveAnimate")
        //- keep-alive
        //-     router-view.sub-page(v-if='$route.meta.keepAlive' name="subPage")
        keep-alive(:include='includePage')
            router-view.sub-page(name="subPage")
</template>
<script>
    import foot from './components/common/foot.vue'
    import mixin from "./vuex/mixin.js" // 混合被单独放在 mixin.js 中管理
    window.mixin = mixin // 将 混合/mixin 暴露在窗口对象中，某些组件需要时，直接提取 window.mixin
    window.protocol = location.protocol;
    window.shareConfig = function(url){
        if(!url){
            url = '/wechat/payment/2'
        }
        console.log(url);
        wx.onMenuShareTimeline({
            title: '泰和国医欢迎您！', // 分享标题
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
            title: '泰和国医欢迎您！', // 分享标题
            desc: '泰和国医', // 分享描述
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
    };
    window.setWxConfig = function(callback){
        var wxConfigUrl = '/api/weixin/wxconfig';
        if(location.host == 'tcm.vmh5.com'){
            wxConfigUrl = '//auth.vliang.com/wxconfig';
        }
        $.ajax({
            url:wxConfigUrl,
            headers:{Accept:'application/x.daguoyi.wxv1+lbbJson'},
            contentType:'application/json;charset=UTF-8',
            dataType:'json',
            crossDomain:true,
            success:function(res){
                var data = null;
                if(wxConfigUrl == '//auth.vliang.com/wxconfig'){
                    data = res;
                }else{
                    data = res.data;
                }
                wx.config({
                    debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
                    appId: data.appId, // 必填，公众号的唯一标识
                    timestamp: data.timestamp, // 必填，生成签名的时间戳
                    nonceStr: data.nonceStr, // 必填，生成签名的随机串
                    signature: data.signature,// 必填，签名，见附录1
                    jsApiList: [
                        'checkJsApi',
                        'onMenuShareTimeline',
                        'onMenuShareAppMessage',
                        'onMenuShareQQ',
                        'onMenuShareWeibo',
                        'onMenuShareQZone',
                        'hideMenuItems',
                        'showMenuItems',
                        'hideAllNonBaseMenuItem',
                        'showAllNonBaseMenuItem',
                        'translateVoice',
                        'startRecord',
                        'stopRecord',
                        'onVoiceRecordEnd',
                        'playVoice',
                        'onVoicePlayEnd',
                        'pauseVoice',
                        'stopVoice',
                        'uploadVoice',
                        'downloadVoice',
                        'chooseImage',
                        'previewImage',
                        'uploadImage',
                        'downloadImage',
                        'getNetworkType',
                        'openLocation',
                        'getLocation',
                        'hideOptionMenu',
                        'showOptionMenu',
                        'closeWindow',
                        'scanQRCode',
                        //'launchMiniProgram'

                    ]
                });
                if(typeof callback == 'function'){
                    callback();
                }
            }
        });
    };
    export default {
        name: 'app',
        components: {
            foot
        },
        data() {
            return {
                "wxconfig": true,
                "pageName": "",
                "routerAnimate": false,
                "enterAnimate": "", //页面进入动效
                "leaveAnimate": "", //页面离开动效
                "includePage":this.$store.state.includePage
            }
        },
        mounted:function(){
            this.getQm();
        },
        watch: {
            // 监听 $route 为店内页设置不同的过渡效果
            "$route" (to, from) {
                const toDepth = to.path.split('/').length;
                const fromDepth = from.path.split('/').length;
                if(to.path=='/' || to.path=='/index'){
                    $("body").attr('class','p_home');
                }else{
                    $("body").attr('class','p_'+to.path.split('/')[1]);
                }
                if (toDepth === 2) {
                    this.$store.commit("setPageName", to.name)
                }
                //this.getQm();
                //同一级页面无需设置过渡效果
                if (toDepth === fromDepth) {
                    return;
                }
            }
        },
        methods:{
            getQm(){
                if(this.$store.state.tcmuser && true){
                    return;
                }
                var self = this;
                setWxConfig(function(){
                    wx.ready(function(){
                        //shareConfig();
                        self.$store.commit("setWxReady", true);
                        setTimeout(function () {
                           // wx.launchMiniProgram({appId:'wx315e3663bdca8ffa',path:'pages/activitylist/activitylist'});
                        },2000);
                    })
                });
                wx.error(function(res){
                    // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
                    self.getQm();
                });
            }
        }
    }
</script>
