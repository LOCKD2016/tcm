/**
 * Created by yangshu on 17/2/28.
 */
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
    //u.api_domain=comUrl;
    window.$api = u;

})(window);


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
}

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
        history.go(-1);
    }
}

var comCountry = ['中国','韩国','美国','日本','缅甸','越南','加拿大','法国','印度','德国','澳大利亚','新加坡','英国','意大利','西班牙','泰国','马来西亚','菲律宾','印度尼西亚','俄罗斯','以色列','巴基斯坦','新西兰','土耳其','伊拉克','伊朗','沙特阿拉伯'];

function cropInit(call){
    $('#cropbox').fadeIn();
    //--获取窗口高度及宽度，尽量别超出窗口。
    var _e_width = $('#cropbox').width();
    var _e_height = $('#cropbox').height();
    //--这是控件的课配置参数。
    var opts = {
        cutWidth: _e_width*1.8 //裁剪框的宽度。
        , cutHeight: _e_width*1.8 //裁剪框的高度
        , containerWidth: _e_width*2 //容器宽度
        , containerHeight: _e_height*2 //容器高度
        , imageShowWidth: 1600 //图片默认显示的宽度【会按照要求的宽度及高度等比缩放】
        , imageShowHeight: 1600 //图片默认显示的高度【会按照要求的宽度及高度等比缩放】
        ,imageEditorElement:$("#image-editor")
        ,onSave:function(imgData){
            call(imgData)
        }//当用户点击保存按钮要保存图片数据时候的回调函数。
        ,onBtnResetClick:function(){
            if(_imageHandler){
                _imageHandler.hideEditor();
            }
            $("#panel-form").show();
        }//reset按钮点击以后。
        ,tpl: "/static/js/power-img-cut.ejs"

    };

    var _imageHandler=ImageEditorHandler(opts);

    //--逻辑方法定义
    $("#uploadImage").change(function(){

        if (document.getElementById("uploadImage").files.length === 0) {
            alert("请选择图片！");
            return; }
        var oFile = document.getElementById("uploadImage").files[0];
        //if (!rFilter.inquiry(oFile.type)) { alert("You must select a valid image file!"); return; }

        /*  if(oFile.size>5*1024*1024){
         message(myCache.par.lang,{cn:"照片上传：文件不能超过5MB!请使用容量更小的照片。",en:"证书上传：文件不能超过100K!"})
         changePanel("result");
         return;
         }*/
        if(!new RegExp("(jpg|jpeg|gif|png)+","gi").test(oFile.type)){
            alert("照片上传：文件类型必须是JPG、JPEG、PNG或GIF!");
            return;
        }

        var reader = new FileReader();

        reader.onload =function(e){
            var _img_str=e.target.result;
            // img 元素
            $("#panel-form").hide();
            _imageHandler.init(_img_str,oFile);

        };
        reader.readAsDataURL(oFile);
        return;
    });
};



  var currDT;
  var aryDay = new Array("日","一","二","三","四","五","六");//显示星期
  var lastDay;//页面显示的最后一天
  var firstDay;//页面显示的第一天

  initDate();
//初始化日期加载
  function initDate() {

    currDT = new Date();
    var dw = currDT.getDay();//从Date对象返回一周中的某一天(0~6)
    var tdDT;//日期
    //在表格中显示一周的日期
    var objTB = document.getElementById("mytable");//取得表格对象
    var htmls = "";
    for(var i=0;i<2;i++){
      htmls += "<tr>";
      for(var c = 0; c < 7; c++) {
        htmls += "<td><span></span><p></p></td>";
      }
      htmls += "</tr>";
    }

    $("tbody").append( "<td><span></span><p></p></td>");

    for(var i=0;i<7;i++) {
      tdDT = getDays()[i];
      if(tdDT.toLocaleDateString() == currDT.toLocaleDateString()) {
        //objTB.rows[0].cells[i].childNodes[1].style.border = "1px solid #b7e055";//currDT突出显示
        //objTB.rows[0].cells[i].childNodes[1].style.borderRadius = "50%";//currDT突出显示
        $("#mytable tr:eq(0) td").eq(i).addClass("active");
      }
      if(tdDT.toLocaleDateString() < currDT.toLocaleDateString()) {
        //$("#mytable tr:eq(0) td").eq(i).style.color = "#e6e6e6";//currDT突出显示
      }

      dw = tdDT.getDay();//星期几
      //objTB.rows[0].cells[i].childNodes[0].innerHTML = aryDay[dw];//显示
      //objTB.rows[0].cells[i].childNodes[1].innerHTML =  tdDT.getDate();//显示
      $("#mytable tr:eq(0) td").eq(i).innerHTML = aryDay[dw];
      //$("#mytable tr:eq(0) td").eq(i).innerHTML = tdDT.getDate();
    }
    //重新赋值
    lastDay = getDays()[6];//本周的最后一天
    firstDay = getDays()[0];//本周的第一天
    nextWeek();

  }
  function getCountDays() {
    var curDate = new Date();
    /* 获取当前月份 */
    var curMonth = curDate.getMonth();
    /*  生成实际的月份: 由于curMonth会比实际月份小1, 故需加1 */
    curDate.setMonth(curMonth + 1);
    /* 将日期设置为0, 这里为什么要这样设置, 我不知道原因, 这是从网上学来的 */
    curDate.setDate(0);
    /* 返回当月的天数 */
    return curDate.getDate();
  }


//取得当前日期一周内的某一天
  function getWeek(i) {
    var now = new Date();
    var n = now.getDay();
    var start = new Date();
    start.setDate(now.getDate() - n + i);//取得一周内的第一天、第二天、第三天...
    return start;
  }

//取得当前日期一周内的七天
  function getDays() {
    var days = new Array();
    for(var i=1;i<=7;i++) {
      days[i-1] = getWeek(i);
    }
    return days;


  }

//取得下一周的日期数(共七天)
  function getNextWeekDatas(ndt) {
    var days = new Array();
    for(var i=1;i<=7;i++) {
      var dt = new Date(ndt);
      days[i-1] = getNextWeek(dt,i);
    }
    return days;
  }

//指定日期的下一周(后七天)
  function getNextWeek(dt,i) {
    var today = dt;
    today.setDate(today.getDate()+i);
    return today;
  }


//下一周
  function nextWeek() {
    for(var e=1;e<2;e++){
      setCurrDTAfter();//重设时间
      //showdate.innerHTML = currDT.toLocaleDateString(); //显示日期

      //在表格中显示一周的日期
      var objTB = document.getElementById("mytable");//取得表格对象
      var dw = currDT.getDay();//从Date对象返回一周中的某一天(0~6)
      var tdDT;//日期

      for(var i=0;i<7;i++) {
        tdDT = getNextWeekDatas(lastDay)[i];
        dw = tdDT.getDay();//星期几
        //objTB.rows[e].cells[i].childNodes[0].innerHTML = aryDay[dw];//显示
        //objTB.rows[e].cells[i].childNodes[1].innerHTML =  tdDT.getDate();//显示
        $("#mytable tr:eq(0) td").eq(0).innerHTML = aryDay[dw];
        $("#mytable tr:eq(0) td").eq(1).innerHTML = tdDT.getDate();
      }
      //重新赋值
      firstDay = getNextWeekDatas(lastDay)[0];//注意赋值顺序1
      lastDay = getNextWeekDatas(lastDay)[6];//注意赋值顺序2

    }
  }

//当前日期后第七天
  function setCurrDTAfter() {
    currDT.setDate(currDT.getDate()+7);
  }

function isEmpty(data) {
    if (isEmpty1(data) || isEmpty2(data)) {
        return true;
    }
    return false;
}

function isEmpty1(data) {
    if (data == undefined || data == null || data == "" || data == 'NULL' || data == false || data == 'false') {
        return true;
    }
    return false;
}

function isEmpty2(v) {
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
}
function log(){
      console.log('dasfdasfasfasdff');
}

function wordStatic(input) {

  // 获取要显示已经输入字数文本框对象
  var content = document.getElementById('num');
  if (content && input) {
    // 获取输入框输入内容长度并更新到界面
    var value = input.value;
    // 将换行符不计算为单词数
    value = value.replace(/\n|\r/gi,"");
    // 更新计数
    content .innerText = value.length;
  }
}

//数据图
function hChart(e,opt){
  var _dom = document.getElementById(e);
  if(!_dom){
    return false
  }
  var myChart = echarts.init(document.getElementById(e));
  // 使用刚指定的配置项和数据显示图表。
  myChart.setOption(opt);
}

