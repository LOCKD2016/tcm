import Vue from 'vue';
import VueResource from 'vue-resource';
import axios from 'axios';
import VueAxios from 'vue-axios';
import vuex from 'vue';
import App from './App';
import router from './router';
import store from './vuex/store';
import FastClick from 'fastclick'; //使用 fastclick 解决移动端 300ms 点击延迟
import filters from './filters'; //将全部过滤器放在 filters/index.js 中便于管理
import './stylus/app.styl';



//技巧 同时 use 多个插件 被依赖的插件应放在偏后方
Vue.use(VueResource,VueAxios, axios, vuex);
    // 注册全局过滤器
filters(Vue);
Vue.http.headers.common['Accept'] = 'application/x.daguoyi.wxv1+lbbJson';
//Vue.http.headers.common['Content-Type'] = 'application/x-www-form-urlencoded';
Vue.http.headers.common['Content-Type'] = 'application/json;charset=UTF-8';
Vue.config.productionTip = false ;//将此值设置为 false ,会关闭 Vue 启动时的提示信息，推荐

Vue.http.interceptors.push(function(request, next) {
    // ...
    // 请求发送前的处理逻辑
    // ...
    next(function(response) {
        //console.log(response.url);
        //console.log(response.status);
        //console.log(response.statusText);
        //console.log(response.ok);
        //console.log(JSON.stringify(response.data));
        //console.log(JSON.stringify(TCM.$router.currentRoute.path));
        // ...
        // 请求发送后的处理逻辑
        // ...
        // 根据请求的状态，response参数会返回给successCallback或errorCallback
        if(response.status == 500 && response.data.msg == 'Unauthenticated.'){
            if(TCM.$store.state.tcmuser){
                api.sendEvent({name:'background',extra: {
                    status:'logout'
                }});
            }
            $api.pop('请先登录');
            return TCM.$router.push({path: '/sign', query: {from: TCM.$router.currentRoute.path}});
        }else{
            return response
        }
    })
})

FastClick.attach(document.body);

window.TCM = new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App)
});

window.AppUpload = function (callback,apiUrl) {
    api.actionSheet({
        title: '选择照片',
        cancelTitle: '取消',
        buttons: ['相册', '照相机']
    }, function(ret, err) {
        var index = ret.buttonIndex;
        console.log(index);
        var sourceType = '';
        if(index == 1){
            sourceType = 'album';
        }else if(index == 2){
            sourceType = 'camera';
        }else {
            return;
        }
        api.getPicture({
            sourceType: sourceType,
            encodingType: 'jpg',
            mediaValue: 'pic',
            destinationType: 'url',
            quality: 60,
            saveToPhotoAlbum: false
        }, function(ret, err) {
            if (ret) {
                $('body').addClass('loading');
                if(typeof apiUrl == "undefined"){
                    apiUrl = 'upload/image'
                }
                lbb.ajax(apiUrl,'POST',{files:{image:ret.data}},function (ret,err) {
                    $('body').removeClass('loading');
                    if(typeof  callback == "function"){
                        if(ret){
                            callback(ret)
                        }else{
                            callback(null)
                        }
                    }
                })
            } else {
                //alert(JSON.stringify(err));
            }
        });
    });
};
// 运行 vue init webpack命令新建项目时 可以选择关闭 ESLint
// 若新建项目时开启了 ESLint .eslintignore 文件，告诉 ESLint 去忽略特定的文件和目录。
// .eslintignore 文件是一个纯文本文件，其中的每一行都是一个 glob 模式表明哪些路径应该忽略检测
window.app = {};
window.app.chooseWXPay = function (data,callback) {
    var wxPay = api.require('wxPay');
    wxPay.payOrder({
        apiKey:data.appid,
        orderId: data.prepayid,
        mchId: data.partnerid,
        nonceStr: data.noncestr,
        timeStamp: data.timestamp,
        package: data.package,
        sign: data.sign
    }, function(ret, err) {
        if(typeof callback == "function"){
            callback(ret,err);
        }
        /*if (ret.status) {
            //支付成功
        } else {
            alert(err.code);
            callback(err);
        }*/
    });
};