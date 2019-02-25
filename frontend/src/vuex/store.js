import Vue from 'vue'
import Vuex from 'vuex'
import mutations from "./mutations"
import actions from "./actions"
import getters from "./getters"
Vue.use(Vuex)
    // 统一管理接口域名
//let apiPublicDomain = 'http://dangwen.vmh5.com/';
let apiPublicDomain = window.location.host;

if(location.hostname == 'localhost'){
    apiPublicDomain = 'http://tcm.dev/';
}else{
    apiPublicDomain = '/';
}
let ua = window.navigator.userAgent.toLowerCase();
if(ua.match(/TCMUser/i) == 'tcmuser'){
    //apiPublicDomain = 'http://tcm.vmh5.com/';
    apiPublicDomain = 'https://app.taiheguoyi.com/';
}
const state = {
    headerStatus:true, //显示（true）/隐藏（false）wx-header组件
    footerStatus:true,
    currentLang: "zh", //当前使用的语言 zh：简体中文 en:英文 后期需要
    newMsgCount: 0, //新消息数量
    currentPageName: "泰和国医", //用于在wx-header组件中显示当前页标题
    //backPageName: "", //用于在返回按钮出 显示前一页名字 已遗弃
    tipsStatus: false, //控制首页右上角菜单的显示(true)/隐藏(false)
    // 所有接口地址 后期需要
    apiUrl: apiPublicDomain + "api/weixin/",
    imgUrl: apiPublicDomain,
    wxready:false,
    tcmuser:false,
    wxInstall:false,
    apiready:false,
    searchDate:searchDate,
    translate:0,
    includePage:[]
};

if(ua.match(/TCMUser/i) == 'tcmuser'){
    state.tcmuser = true;
}

export default new Vuex.Store({
    state,
    mutations,
    actions,
    getters
})

export function errorMsg(errors){
    for(var d in errors){
        var msg = errors[d][0];
        $api.pop(msg);
        break;
    }
}

export function responseErrorMsg(response){
    var data = response.data;
    if(data.errcode == 401){
        if(TCM.$store.state.tcmuser && true){
            api.sendEvent({name:'background',extra: {
                status:'logout'
            }});
            TCM.$router.replace('/sign');
        }else{
            location.href = "/wechat/sign";
        }
    }else if(data.errcode == 403){
        $api.pop("没有权限");
    }else{
        console.log(data);
        $api.pop(response.errcode);
    }
}
