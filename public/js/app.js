/**
 * Created by yangshu on 17/2/28.
 */


//var comUrl = location.protocol+'//'+location.host+'/';
var comUrl = 'http://t.ai100.com.cn/';


function getCookie(name)
{
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
    if(arr=document.cookie.match(reg))
        return unescape(arr[2]);
    else
        return null;
}


$(function(){
    $('.nav_tabs li').click(function(){
        $(this).parents('.tabs_box').attr('show',$(this).index()+1)
    });
    $('html').addClass('show');
});

/**
 * 设置本地储存数组, 读取方法JSON.parse(localStorage.name);
 * @param n name
 * @param d data
 */
function setLost(n,d){
    localStorage.setItem(n, JSON.stringify(d));
}

if (localStorage.userinfo) {
    var vb = JSON.parse(localStorage.userinfo);
    //console.log(vb);
    vHead.status=1;
    vHead.info=vb;
}else{

}
function logOut(){
    localStorage.removeItem('userinfo');
    vHead.status = 0;
    vHead.info = {};
    location.href = "/logout";
}
var page =0 ,isOver;
moreLoad = function(options){
    var that = this ,lock, timer;

    options = options || {};

    var elem = $(options.elem); if(!elem[0]) return;
    var scrollElem = $(options.scrollElem || document); //滚动条所在元素
    var mb = options.mb || 50; //与底部的临界距离
    var isAuto = 'isAuto' in options ? options.isAuto : true; //是否自动滚动加载
    //滚动条所在元素是否为document
    var notDocment = options.scrollElem && options.scrollElem !== document;
    //加载下一个元素
    var next = function(over){
        over = over == 0 ? true : null;
        isOver = over;
        lock = null;
    };

    //触发请求
    var done = function(){
        lock = true;
        typeof options.done === 'function' && options.done(++page, next);
    };
    done();
    if(!isAuto) return that;
    scrollElem.on('scroll', function(){
        var othis = $(this), top = othis.scrollTop();
        if(timer) clearTimeout(timer);
        if(isOver) return;
        timer = setTimeout(function(){
            //计算滚动所在容器的可视高度
            var height = notDocment ? othis.height() : $(window).height();

            //计算滚动所在容器的实际高度
            var scrollHeight = notDocment
                ? othis.prop('scrollHeight')
                : document.documentElement.scrollHeight;

            //临界点
            if(scrollHeight - top - height <= mb){
                lock || done();
            }
        }, 100);
    });
};

(function(window){
    var u = {};
    var isAndroid = (/android/gi).test(navigator.appVersion);
    var uzStorage = function(){
        var ls = window.localStorage;
        if(isAndroid){
            ls = os.localStorage();
        }
        return ls;
    };
    u.trim = function(str){
        if(String.prototype.trim){
            return str == null ? "" : String.prototype.trim.call(str);
        }else{
            return str.replace(/(^\s*)|(\s*$)/g, "");
        }
    };
    u.isArray = function(obj){
        if(Array.isArray){
            return Array.isArray(obj);
        }else{
            return obj instanceof Array;
        }
    };
    u.isEmptyObject = function(obj){
        if(JSON.stringify(obj) === '{}'){
            return true;
        }
        return false;
    };

    u.jsonToStr = function(json){
        if(typeof json === 'object'){
            return JSON && JSON.stringify(json);
        }
    };
    u.strToJson = function(str){
        if(typeof str === 'string'){
            return JSON && JSON.parse(str);
        }
    };
    u.setStorage = function(key, value){
        if(arguments.length === 2){
            var v = value;
            if(typeof v == 'object'){
                v = JSON.stringify(v);
                v = 'obj-'+ v;
            }else{
                v = 'str-'+ v;
            }
            var ls = uzStorage();
            if(ls){
                ls.setItem(key, v);
            }
        }
    };
    u.getStorage = function(key){
        var ls = uzStorage();
        if(ls){
            var v = ls.getItem(key);
            if(!v){return;}
            if(v.indexOf('obj-') === 0){
                v = v.slice(4);
                return JSON.parse(v);
            }else if(v.indexOf('str-') === 0){
                return v.slice(4);
            }
        }
    };
    u.rmStorage = function(key){
        var ls = uzStorage();
        if(ls && key){
            ls.removeItem(key);
        }
    };
    u.clearStorage = function(){
        var ls = uzStorage();
        if(ls){
            ls.clear();
        }
    };
    u.getData = function(act,data,type,dataType,callback){
        $.ajax({
            type:type,
            url:u.api_domain+'/'+act,
            data:data,
            dataType:dataType,
            success:function(ret){
                callback(ret);
            }
        });
    };
    //判断是否为空
    u.isEmpty=function(data) {
        if(u.isEmpty1(data) || u.isEmpty2(data)) {
            return true;
        }
        return false;
    };
    u.isEmpty1=function(data) {
        if(data == undefined || data == null || data == "" || data == 'NULL'  || data=='null' || data==null || data == false || data == 'false' || data=='undefined' || data==undefined) {
            return true;
        }
        return false;
    };
    u.isEmpty2=function(v) {
        switch (typeof v) {
            case 'undefined' :
                return true;
            case 'string' :
                if ($api.trim(v).length == 0)
                    return true;
                break;
            case 'boolean' :
                if (!v)
                    return true;
                break;
            case 'number' :
                if (0 === v)
                    return true;
                break;
            case 'object' :
                if (null === v)
                    return true;
                if (undefined !== v.length && v.length == 0)
                    return true;
                for (var k in v) {
                    return false;
                }
                return true;
                break;
        }
        return false;
    };

    //获取url中"?"符后的字串
    u.GetParam=function(name){
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if(r!=null)return  r[2]; return '';
    };
    u.pop=function(t){
        $('#popBox').text(t).addClass('show');
        setTimeout(function(){$('#popBox').removeClass('show')},2500);
    };
    u.api_domain=comUrl;
    window.$api = u;
})(window);

function errorMsg(errors){
    for(var d in errors){
        var msg = errors[d][0];
        $api.pop(msg);
        break;
    }
}

function extra(x) {
    //如果传入数字小于10，数字前补一位0。///
    if (parseInt(x) < 10) {
        return "0" + parseInt(x);
    } else {
        return x;
    }
}

/**
 * 日期相差天数
 * @param x 前一天
 * @param y 后一天
 */
function dateDiff(x,y) {
    var xc = new Date(x).getTime();
    var xd = new Date(y).getTime();
    var xs = (xd - xc)/86400000;
    return xs;
}
//console.log(dateDiff('2016-08-12','2016-08-30'));


function time2str(time){
    var d = new Date();
    var t = parseInt(d.getTime()/1000);
    time = parseInt(time/1000);
    var range = t-time;
    if(range<0){
        return "未知";
    }else if(range < 60){
        return range+"秒前";
    }else if(range < 3600 ){
        return parseInt(range/60)+"分钟前";
    }else if(range < 86400){
        return parseInt(range/3600)+"小时前";
    }else if(range < 864000){
        return parseInt(range/86400)+"天前";
    }else{
        return "10天前";
    }
}
// 秒数转成时分秒
function formatSec(value) {
    var theTime = parseInt(value);
    // 秒
    var theTime1 = 0;
    // 分
    var theTime2 = 0;
    // 小时
    if (theTime >= 60) {
        theTime1 = parseInt(theTime / 60);
        theTime = parseInt(theTime % 60);
        if (theTime1 >= 60) {
            theTime2 = parseInt(theTime1 / 60);
            theTime1 = parseInt(theTime1 % 60);
        }
    }
    var i, s, h;
    if (theTime2 >= 10) {
        h = theTime2;
    } else {
        h = '0' + theTime2;
    }
    if (theTime >= 10) {
        s = theTime;
        theTime1=theTime1+1;
    }
    if (theTime1 >= 10) {
        i = theTime1;
    } else {
        i = '0' + theTime1;
    }
    if(h>0){
        return h + '小时' + i + '分钟';
    }else{
        return i + '分钟';
    }
}
// (new Date()).Format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423
// (new Date()).Format("yyyy-M-d h:m:s.S")      ==> 2006-7-2 8:9:4.18
Date.prototype.Format = function (fmt) { //author: meizz
    var o = {
        "M+": this.getMonth() + 1, //月份
        "d+": this.getDate(), //日
        "h+": this.getHours(), //小时
        "m+": this.getMinutes(), //分
        "s+": this.getSeconds(), //秒
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度
        "S": this.getMilliseconds() //毫秒
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
        if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
};


//日期加减
function datePlus(dd,dadd){
    var a = new Date(dd.replace(/-/g, '/')).getTime();
    var b = a + (dadd * 24 * 60 * 60 * 1000);
    return (new Date(b)).Format("yyyy-MM-dd hh:mm:ss");
}


function dateFormat(dd){
    return (new Date(dd.replace(/-/g, '/'))).Format("yyyy-MM-dd hh:mm:ss");
}

//页面返回
function back(){
    if($api.GetParam('back')){
        location.href= $api.GetParam('back');
    }else{
        history.go(-1)
    }
}


var comCountry = ['中国','韩国','美国','日本','缅甸','越南','加拿大','法国','印度','德国','澳大利亚','新加坡','英国','意大利','西班牙','泰国','马来西亚','菲律宾','印度尼西亚','俄罗斯','以色列','巴基斯坦','新西兰','土耳其','伊拉克','伊朗','沙特阿拉伯'];


