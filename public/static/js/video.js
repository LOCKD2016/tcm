var LBBVideo = {
    rtcApi: null,
    appId: '72311',
    appKey: '8d51c439-7cac-42a1-beaa-31655c3323e5',
    type: 'user'
};
/**
 * 设置回调函数，监听客户端全局状态
 * @param data
 "ClientListener:onInit,result=":result为初始化结果
 "获取token: reason:":获取token结果，reason为失败原因
 "DeviceListener:onNewCall,call=":有新来电，call为来电信息，其中"ci"为对方携带的呼叫信息
 （即callInfo参数），"t"为呼叫类型（1：音频，3：音+视频），"dir"为呼叫方向（1：去电，2：来电），
 "uri"为对方账号
 "DeviceListener:rejectIncomingCall call=":当前已处在通话中，拒绝新的点对点来电，call为来电信息，
 其中"ci"为对方携带的呼叫信息（即callInfo参数），"t"为呼叫类型（1：音频，3：音+视频），
 "dir"为呼叫方向（1：去电，2：来电），"uri"为对方账号
 "ConnectionListener:onConnecting":通话请求连接中
 "ConnectionListener:onConnected":通话已接通
 "ConnectionListener:onVideo":通话接通后，媒体建立成功
 "ConnectionListener:onDisconnect,code=":通话连接中断，code为错误码
 "StateChanged,result=200":登录成功
 "StateChanged,result=-1001":没有网络
 "StateChanged,result=-1002":切换网络
 "StateChanged,result=-1003":网络较差
 "StateChanged,result=-1004":重连失败需要重新登录
 "StateChanged,result=-1500":同一账号在多个终端登录被踢下线
 "StateChanged,result=-1501":同一账号在多个设备类型登录
 "call hangup":主动挂断
 "onReceiveIm:from:,msg:":接收到文本消息，from为发送账号，msg为消息内容
 "TaponVideo":仅适用于iOS，当单击视频窗口时触发
 "APNs:xxx":仅适用于iOS，xxx为推送内容。以下三种情况会触发此回调。
 a)当应用未启动的情况下收到通知后，点击或滑动通知会触发应用启动，并触发回调；
 b)当应用在前台运行的情况下收到来电，不会弹出通知，但会触发回调；
 c)当应用在后台运行的情况下收到通知后，点击或滑动通知会进入应用，并触发回调。

 */
LBBVideo.onGlobalStatus = function (data) {
    console.log('onGlobalStatus='+data);
    if("DeviceListener:onNewCall" == data.substring(0,24)){//视频创建之后在画面之上浮动一个frame
        var call = JSON.parse(data.split('=')[1]);
        if(call.dir == 2){
            var name = call.ci;
            var headimg = '';
            if(call.ci.indexOf(',')){
                name = call.ci.split(',')[0]
                headimg = call.ci.split(',')[1]
            }
            api.openWin({
                name: 'accept',
                url: 'widget://html/video.html',
                pageParam: {
                    type: 'accept',
                    uid: call.uri,
                    name: name,
                    headimg: headimg
                },
                slidBackEnabled:false,
                animation:{type:'movein',subType:'from_bottom'},
                bgColor: 'rgba(0,0,0,1)'
            });
        }
    }else if("ConnectionListener:onVideo" == data.substring(0,26)){//视频创建之后在画面之上浮动一个frame
        //api.openFrame({name: 'page2',url: './page2.html',rect:{x:0,y:0,w:288,h:352},bgColor: 'rgba(0,0,0,0)'});
        api.sendEvent({name:'video'})
    }
};
/**
 * 设置客户端注册至RTC平台的回调函数
 * @param data
 *
 “OK:LOGIN”:登录成功
 “OK:LOGOUT”:退出成功
 “ERROR:PARM_ERROR”:登录参数有误
 “ERROR:UNINIT”:RTC平台未初始化
 “ERROR:error_msg”:error_msg为其他错误信息，如获取token失败
 */
LBBVideo.showStr = '';
LBBVideo.cbLogStatus = function(data){
    console.log('cbLogStatus='+data);
    if ("OK" == data.substring(0, 2)) {
        var status = data.split("OK:")[1];
        if ("LOGIN" == status) {
            LBBVideo.showStr = "Logout";
        }
        else if ("LOGOUT" == status) {
            LBBVideo.showStr = "Login";
        }
        LBBVideo.setVideoAttr(2);
    }
    else {
        alert(data+',请重启App');
    }
};
/**
 * 设置呼叫状态的回调函数
 * @param data
 *
 “OK:NORMAL”：正常状态（未通话，也无来电）
 “OK:INCOMING”：有来电，等待接听
 “OK:CALLING”：呼叫或通话中
 “ERROR:PARM_ERROR”：呼叫参数有误
 “ERROR:UNREGISTER”：未注册至RTC平台
 */
LBBVideo.cbCallStatus = function(data){
    console.log('cbCallStatus='+data);
    if ("OK" == data.substring(0, 2)) {
        var status = data.split("OK:")[1];
        if ("NORMAL" == status) {
            LBBVideo.showStr = callBtnTexNormal;
            api.sendEvent({name:'hangUp'})
        }
        else if ("INCOMING" == status) {
            LBBVideo.showStr = callBtnTexIncoming;
        }
        else if ("CALLING" == status) {
            LBBVideo.showStr = callBtnTexCalling;
        }
    }
    else {
        alert(data);
    }
};
/**
 * 设置截屏的回调函数
 * @param data
 */
LBBVideo.cbRemotePicPath = function(data){
    console.log('cbCallStatus='+data);
};
LBBVideo.cbMessageStatus = function(data){
    console.log('cbCallStatus='+data);
};
LBBVideo.cbGroupStatus = function(data){
    console.log('cbCallStatus='+data);
    /*if("OK:groupMember,list=" == data.substring(0,20))
    {
        var jsonStr = data.split("OK:groupMember,list=")[1];
        var jsonO = eval("("+jsonStr+")");
        alert(jsonO[0].appAccountID);
    }
    else if("OK:groupList,list=" == data.substring(0,18))
    {
        var jsonStr = data.split("OK:groupList,list=")[1];
        var jsonO = eval("("+jsonStr+")");
        alert(jsonO[0].callid);
    }*/
};
LBBVideo.init = function () {
    if (this.rtcApi == null) {
        this.rtcApi = api.require('tyRTC');
    }
    this.rtcApi.setGlobalStatusListener(LBBVideo.onGlobalStatus);
    this.rtcApi.setLogStatusListener(LBBVideo.cbLogStatus);
    this.rtcApi.setCallStatusListener(LBBVideo.cbCallStatus);
    this.rtcApi.setRemotePicPathListener(LBBVideo.cbRemotePicPath);
    this.rtcApi.setMessageListener(LBBVideo.cbMessageStatus);
    this.rtcApi.setGroupStatusListener(LBBVideo.cbGroupStatus);
    this.setAppKeyAndAppId();
};
LBBVideo.setAppKeyAndAppId = function () {
    this.rtcApi.setAppKeyAndAppId({
        appId: this.appId,
        appKey: this.appKey
    });
};
/**
 * 登录
 * @param userName
 */
LBBVideo.login = function(userName) {
    var scale = 0.75; //宽比高 3/4
    var now_scale = api.winWidth/api.winHeight;

    //自己小窗口
    var y1 = 25;
    var w1 = parseInt(api.winWidth/3);
    var h1 = parseInt(w1/0.75);
    var x1 = api.winWidth-w1-25;

    //远程大窗口
    var x2 = 0,y2 = 0,w2 = api.winWidth,h2 = api.winHeight;
    if(scale<now_scale){//w2大，不变  h2小，放大   w2/h2 == 0.75
        h2 = parseInt(w2/scale);
        y2 = -parseInt((h2-api.winHeight)/2);
    }else if(scale>now_scale){//w2小，变大  h2大，不变  w2/h2 == 0.75
        w2 = parseInt(h2*scale);
        x2 = -parseInt((w2-api.winWidth)/2);
    }
    var json = {
        localView: {
            x: x1+'',
            y: y1+'',
            w: w1+'',
            h: h1+''
        },
        remoteView: {
            x: x2+'',
            y: y2+'',
            w: w2+'',
            h: h2+''
        }
    };
    if (this.rtcApi == null) {
        this.rtcApi = api.require('tyRTC');
    }
    console.log('json='+JSON.stringify(json),api.fsDir);
    this.rtcApi.login({
        jsonViewConfig: JSON.stringify(json),
        userName: userName
    });
};
/**
 * 退出登录
 */
LBBVideo.logout = function () {
    if (this.rtcApi == null) {
        this.rtcApi = api.require('tyRTC');
    }
    this.rtcApi.logout();
};
var callBtnTexNormal = "Call";
var callBtnTexIncoming = "Accept";
var callBtnTexCalling = "Calling";
/**
 * 呼叫
 * @param calledUserName
 * @param callInfo
 */
LBBVideo.call = function (calledUserName,callInfo) {
    if (this.rtcApi == null) {
        this.rtcApi = api.require('tyRTC');
    }
    var callType = 2;//呼叫类型，不能为空。1：音频，2：音+视频，3：音+视频单向接收，4：音+视频单向发送
    //创建呼叫
    this.rtcApi.call({
        callType: callType,
        callName: calledUserName,
        callInfo: callInfo
    });
};

LBBVideo.accept = function(){
    if (this.rtcApi == null) {
        this.rtcApi = api.require('tyRTC');
    }
    var callType = 2;
    //接听呼叫
    this.rtcApi.acceptCall({
        callType: callType
    });
};


LBBVideo.hangUp = function () {
    if (this.rtcApi == null) {
        this.rtcApi = api.require('tyRTC');
    }
    this.rtcApi.hangUp();
};
var muteStr = "Mute";
var unmuteStr = "Unmute";
/**
 * 设置静音/取消静音
 * @param status
 * 静音：”true”，取消静音：”false”
 */
LBBVideo.mute= function mute(status) {
    if (this.rtcApi == null) {
        this.rtcApi = api.require('tyRTC');
    }
    if (muteStr == status) {
        this.rtcApi.mute({
            value: "true"
        });
        this.muteValue = unmuteStr;
    }
    else if (unmuteStr == status) {
        this.rtcApi.mute({
            value: "false"
        });
        this.muteValue = muteStr;
    }
};
/**
 * 设置扬声器/电话听筒
 * 扬声器：”true”，电话听筒：”false”
 */
LBBVideo.loudSpeaker = function (status) {
    if (this.rtcApi == null) {
        this.rtcApi = api.require('tyRTC');
    }
    this.rtcApi.loudSpeaker({
        value: status
    });
};
/**
 *
 * @param videoAttr
 * 默认值：0
 * 视频分辨率。0：标清；1：流畅；2：高清；3：720P；4：1080P；
 * 5：高清横屏（仅支持Android）；6：720P横屏（仅支持Android）；
 * 7：1080P横屏（仅支持Android）
 */
LBBVideo.setVideoAttr = function (videoAttr) {
    if (this.rtcApi == null) {
        this.rtcApi = api.require('tyRTC');
    }
    this.rtcApi.setVideoAttr({
        value: videoAttr
    });
};
LBBVideo.switchCStr = "front";
LBBVideo.switchCamera = function () {
    if (this.rtcApi == null) {
        this.rtcApi = api.require('tyRTC');
    }
    if (LBBVideo.switchCStr == "front") {
        this.rtcApi.switchCamera({
            value: "back"
        });
        LBBVideo.switchCStr = "back";
    }
    else if (this.switchCStr == "back") {
        this.rtcApi.switchCamera({
            value: "front"
        });
        LBBVideo.switchCStr = "front";
    }
    console.log(LBBVideo.switchCStr);
};
LBBVideo.switchView = function () {
    if (this.rtcApi == null) {
        this.rtcApi = api.require('tyRTC');
    }
    this.rtcApi.switchView();
};

LBBVideo.hideVStr = "show";
LBBVideo.hideLocalView = function () {
    if (this.rtcApi == null) {
        this.rtcApi = api.require('tyRTC');
    }
    if (this.hideVStr == "show") {
        this.rtcApi.hideLocalView({
            value: "hide"
        });
        this.hideVStr = "hide";
    }
    else if (hideVStr == "hide") {
        this.rtcApi.hideLocalView({
            value: "show"
        });
        this.hideVStr = "show";
    }
};
LBBVideo.openCall = function(uid,name,headimg){
    api.openWin({
        name: 'call',
        url: 'widget://html/video.html',
        pageParam: {type:'call',uid:uid,name:name,headimg:headimg},
        slidBackEnabled:false,
        animation:{type:'movein',subType:'from_bottom'},
        bgColor: 'rgba(0,0,0,0.8)'
    });
};
LBBVideo.openVideo = function(uid,name,headimg){
    api.openFrame({
        name: 'call',
        url: 'widget://html/video.html',
        pageParam: {type:'video'},
        rect:{x:0,y:0,w:api.winWidth,h:api.winHeight},
        bgColor: 'rgba(0,0,0,0)'
    });
};

window.LBBVideo = LBBVideo;