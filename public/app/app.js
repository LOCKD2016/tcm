//控制页面字体
(function (doc, win) {
    var docEl = doc.documentElement,
        resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
        recalc    = function () {
            var clientWidth = docEl.clientWidth;
            if (clientWidth>=750) {
                clientWidth = 750;
            };
            if (!clientWidth) return;
            docEl.style.fontSize = 100 * (clientWidth / 750) + 'px';
            window.localStorage.expSize = docEl.style.fontSize
        };
    if (!doc.addEventListener) return;
    win.addEventListener(resizeEvt, recalc, false);
    doc.addEventListener('DOMContentLoaded', recalc, false);
})(document, window);

//统一窗口尺寸变量,在登录后 赋值(66,129,80,280|54,127,94,329)
var headerH,filterH,footerH;
if(window.localStorage.tpad_headerH){
    headerH = parseInt(window.localStorage.tpad_headerH);
    filterH = parseInt(window.localStorage.tpad_filterH);
    footerH = parseInt(window.localStorage.tpad_footerH);
    $('html').addClass(localStorage.systemType)
}
// 复选框
function checkMe(o){
    if($(o).attr('type')==0){
        $(o).attr('type',1)
    }else{
        $(o).attr('type',0)
    }
}

//tab切换
function toggleShow(o,i){
    $(o).attr('show',i)
}


//左滑动删除
function fnSwipeDel(){
    $("ul li").on("swipeleft", function () {
        if ($(this).attr("no")==0){
            return false;
        } else {
            $("ul li").animate({marginLeft: '0'}, 300);
            $(this).animate({marginLeft: '-' + $(this).find('.del').width() + 'px'}, 300);
            $("ul li").attr("no","1");
            $(this).attr("no","0");
        }
    }).on("swiperight", function () {
        $(this).animate({marginLeft: '0'}, 300);
        $(this).attr("no","1");
    });
}

//打开共通head页面
function comHead(tit,page,par,boun){
    var param={};
    param.tit=tit;
    param.page=page;
    param.data=par;
    param.bounces=boun;

    api.openWin({
        name: 'win_'+page,
        url: 'com_head.html',
        pageParam:param,
        background:"#f1f1f1",
        slidBackEnabled:true
    });
}

function comHeadR2(url){
    api.openWin({
        name: 'win_'+url,
        url: url+'.html',
        background:"#f1f1f1",
        slidBackEnabled:true
    });
}

/*guhao
 *  @param page 进来时的页面
 *  @param id 页面的文章id
 */
function toComment(par){
    $('.n-pop').removeClass('open');
    $('.show_btn').removeClass('show_btn');
    comHead('添加评论','comment',par);
}

/**
 * 富文本链接处理
 */
function linkInit(){
    setTimeout(function(){
        $('a').each(function(){
            var link = $(this).attr('href');
            if(isURL(link)){
                $(this).attr('onclick','comHead("外部链接","'+link+'",{link:true})');
            }
            $(this).removeAttr('href');
        })
    },300)
}

function isURL(str){
    var checkfiles=new RegExp("((^http)|(^https)|(^ftp)):\/\/(\\w)+\.(\\w)+");
    if(checkfiles.test(str)){
        console.log("是链接");
        return true
    }else{
        console.log("不是链接");
        return false
    }
}

/**
 * 打开页面
 * @param page 要打开的页面
 * @param prm 参数
 * @param isBack 是否可以返回,默认可以返回
 * @returns {boolean}
 */
function toWin(page,prm,isBoun,isBack){

    if($('.chat-body').hasClass('hasmore') || $('.data_li.active').length > 0){
        return false
    }

    if (isEmpty(page)) {
        api.alert({msg: 'No page!'});
        return false;
    } else {
        if(typeof isBack == "undefined"){
            isBack = true;
        }
        api.openWin({
            name: page,
            url: page+'.html',
            slidBackEnabled:isBack,
            bounces:isBoun,
            pageParam: prm

            //,animation:{
            //    type:"ripple"
            //    //duration:800
            //}
        });
    }
}

/**
 * 打开页面
 * @param page 要打开的页面
 * @param prm 参数
 * @param isBack 是否可以返回,默认可以返回
 * @returns {boolean}
 */
function toFrame(page,prm){
    if (isEmpty(page)) {
        api.alert({msg: 'No page!'});
        return false;
    } else {

        api.openFrame({
            name: page,
            url: page+'.html',
            rect: {
                x: 0,
                y: 0
            },
            pageParam: prm,
            bounces: false
        });
    }
}



function popNav(obj){
    $('.share_box').removeClass('open');
    $(obj).parents('.share_box').addClass('open');
}

/**
 * 上传图片
 * @param i 0=用户注册头像  1=用户修改头像 , 2=提交体验
 */
function upAvatar(i){
    $('input,textarea').blur();
    var w = 120,h = 120,at=true,qua=100;
    if(i==2){
        w=1200;
        h=null;
        at=false;
        qua=75
    }
    api.actionSheet({
        title: '图片来源',
        cancelTitle: '取消',
        buttons: ['相机', '图片库']
    }, function(ret, err) {
        var index = ret.buttonIndex;
        if(index==1){
            sourceType = "camera";
        }else if(index ==2){
            sourceType = "library";
        }else{
            return;
        }
        api.getPicture({
            sourceType: sourceType,
            encodingType: 'png',
            mediaValue: 'pic',
            destinationType: 'base64',
            allowEdit: at,
            quality: qua,
            targetWidth: w,
            targetHeight: h,
            saveToPhotoAlbum: false
        }, function(ret, err) {
            if(!isEmpty(ret)){
                switch (i){
                    case 0:
                        lbb.Headimgurl("headimgurl",ret.base64Data,function(rets){
                            if(rets){
                                regDate.headimgurl = rets.data;
                                $('#headimgurl .avatar').css('backgroundImage',"url("+ret.base64Data+")");
                            }
                        });
                        break;
                    case 1:
                        lbb.updateUser("headimgurl",ret.base64Data,function(rets){
                            if(rets){
                                vCont.info.headimgurl = rets.data;
                            }
                        });
                        break;
                    case 2:
                        lbb.Headimgurl("comment",ret.base64Data,function(rets){
                            if(rets){
                                inviteEnd.info.push(rets.data);
                            }
                        });
                        break;
                }
            }

        });
    });
}

//图片浏览器


function openImageBrowser(arr, i) {
    if(isEmpty(arr)||!arr){
        return false;
    }
    var imgs = [];
    for(m in arr){
        imgs.push(lbb.imgUrl+arr[m])
    }
    var photoBrowser = api.require('photoBrowser');
    photoBrowser.open({
        images: imgs,
        bgColor: '#000',
        activeIndex:parseInt(i)
    }, function (ret, err) {
        api.openFrame({
            name: 'photoBro',
            url: 'photo.html',
            rect: {
                x: 0,
                y: 0,
                h: headerH
            },
            pageParam: {page:arr.length,index:i},
            bounces: false
        });
        api.addEventListener({name:'closePhoto'}, function (ret, err) {
            photoBrowser.close();
            api.removeEventListener({name: 'changePhoto'});
            api.removeEventListener({name: 'closePhoto'});
            api.closeFrame({name:'photoBro'});
        });
        if (ret) {

            api.openFrame({
                name: 'photoBro',
                url: 'photo.html',
                rect: {
                    x: 0,
                    y: 0,
                    h: headerH
                },
                pageParam: {page:arr.length,index:i},
                bounces: false
            });
            //alert(JSON.stringify(ret));
            if(ret.eventType=='change'){
                //lbb.toast(ret.index);
                api.sendEvent({
                    name: 'changePhoto',
                    extra: {key1: ret.index}
                });
            }
            if(ret.eventType=='click'){
                photoBrowser.close();
                api.removeEventListener({name: 'changePhoto'});
                api.removeEventListener({name: 'closePhoto'});
                api.closeFrame({name:'photoBro'});
            }
        } else {
            //alert(JSON.stringify(err));
        }
    });
}
var tmHF,tmHa,tmHb,oY,nY,dss=0;

function headTa(o,e){
    tmHa = new Date().getTime();
    oY = e.targetTouches[0].clientY;
    $('body').attr('class','head_fix');
    clearTimeout(tmHF);
}

function headTb(o,e){
    tmHb = new Date().getTime() - tmHa;
    nY = e.targetTouches[0].clientY -oY;
}
function headTc(){
    if(nY/tmHb>0){
        //alert(2);
        headFix()
    }
}

function headFix(){
    //console.log($('.invite_det .main').scrollTop());
    ////发现，邀请详情页往上推到顶时状态栏反白处理
    clearTimeout(tmHF);
    $('body').addClass('head_fix');
    if(nY/tmHb<0){
        dss = nY/tmHb * -200
    }else{
        dss = nY/tmHb * 100
    }
    tmHF=setTimeout(function(){
        //lbb.toast(dss);
        var gd = $('.invite_top').height()*3/4;
        if($('.invite_det .main').scrollTop()<gd){
            $('body').removeClass('head_fix');
        }
    },dss+100)
}

/**
 * doT模板 --杨澍
 * @param obj Dom
 * @param typ 0为替换，1为增加
 * @param Tid 模板id
 * @param data
 */
function T(obj,Tid,data,typ){
    var html=doT.template(document.getElementById(Tid).innerHTML)(data);
    if(typ==0){
        $(obj).html(html)
    }else{
        $(obj).append(html)
    }
}

//显示加载完毕没有更多数据
var _thend = true;
function theend(){
    $('body').addClass('loaded');
    if(_thend){
        setTimeout(function(){$('body').addClass('loaded_end')},1000);
        setTimeout(function(){$('body').removeClass('loaded_end').removeClass('loaded');_thend = true;},2000);
        _thend = false;
    }
}