/**
 * Created by 张鹏 on 2017-06-09.
 */

var webim = {
	'local': 'ws://tcm.vmh5.com:9504/',
	'dev': 'ws://tcm.vmh5.com:9504',
	'pro': 'wss://app.taiheguoyi.com/socket.io/',
	'master': '//'+window.location.host,
	'voiceUrl': 'http://static.taiheguoyi.com/',
};
!function (win, $) {
	var zpChat = {
		debug: true,
		isSendMsg: true,
		isSendMsgTip: '',
		is_sync:false,
		selfType: 'user',// user system
		otherType: 'doctor',// user system
		isLoading:false,// true 正在请求历史记录
		historyInt: 1,//1：获取会话列表 2：下滑加载更多历史记录
		request: false,//是否正在请求
		timeOut: 80000,//请求超时80秒请求超时
		timeDelay: 30000,//30秒展示时间
		tipShowTime: 5000,//提示信息统一显示5秒后隐藏
		doctorFzPrice:'0',
		subPayRead:0,//支付弹窗
		subscribeId:0,//预约id
		tipText: {
			'3': '追问指问诊前面所问的问题中不懂的，不能问 其他无关的',
			'5': '15天以内才能进行追问',
		},
		isFz: 0,//能否复诊 超过15天不能复诊;
		newTimestamp: Math.round(new Date().getTime()),
		oldTimestamp: 0,
		timeShow: 0,
		loadHistory: {
			INT: 1,
			LOADING: 2
		},
		type: {
			user: 1,
			doctor: 2,
			system: 3,
		},
		msg_type: {
			TEXT: 'text',
			IMG: 'image',
			VOICE: 'audio',
			CARD: 'card'
		},
		from: {
			LEFT: '',
			MIDDLE: 'cen',
			RIGHT: 'self',
		},
		reg: /\[.+?\]/g,
		face: {
			'[微笑]': '<img src="/static/img/emotion/Expression_1.png" class="emotion"/>',
			'[撇嘴]': '<img src="/static/img/emotion/Expression_2.png" class="emotion"/>',
			'[色]': '<img src="/static/img/emotion/Expression_3.png" class="emotion"/>',
			'[发呆]': '<img src="/static/img/emotion/Expression_4.png" class="emotion"/>',
			'[得意]': '<img src="/static/img/emotion/Expression_5.png" class="emotion"/>',
			'[流泪]': '<img src="/static/img/emotion/Expression_6.png" class="emotion"/>',
			'[害羞]': '<img src="/static/img/emotion/Expression_7.png" class="emotion"/>',
			'[闭嘴]': '<img src="/static/img/emotion/Expression_8.png" class="emotion"/>',
			'[睡]': '<img src="/static/img/emotion/Expression_9.png" class="emotion"/>',
			'[大哭]': '<img src="/static/img/emotion/Expression_10.png" class="emotion"/>',
			'[尴尬]': '<img src="/static/img/emotion/Expression_11.png" class="emotion"/>',
			'[发怒]': '<img src="/static/img/emotion/Expression_12.png" class="emotion"/>',
			'[调皮]': '<img src="/static/img/emotion/Expression_13.png" class="emotion"/>',
			'[呲牙]': '<img src="/static/img/emotion/Expression_14.png" class="emotion"/>',
			'[惊讶]': '<img src="/static/img/emotion/Expression_15.png" class="emotion"/>',
			'[难过]': '<img src="/static/img/emotion/Expression_16.png" class="emotion"/>',
			'[酷]': '<img src="/static/img/emotion/Expression_17.png" class="emotion"/>',
			'[冷汗]': '<img src="/static/img/emotion/Expression_18.png" class="emotion"/>',
			'[抓狂]': '<img src="/static/img/emotion/Expression_19.png" class="emotion"/>',
			'[吐]': '<img src="/static/img/emotion/Expression_20.png" class="emotion"/>',
			'[偷笑]': '<img src="/static/img/emotion/Expression_21.png" class="emotion"/>',
			'[愉快]': '<img src="/static/img/emotion/Expression_22.png" class="emotion"/>',
			'[白眼]': '<img src="/static/img/emotion/Expression_23.png" class="emotion"/>',
			'[傲慢]': '<img src="/static/img/emotion/Expression_24.png" class="emotion"/>',
			'[饥饿]': '<img src="/static/img/emotion/Expression_25.png" class="emotion"/>',
			'[困]': '<img src="/static/img/emotion/Expression_26.png" class="emotion"/>',
			'[恐惧]': '<img src="/static/img/emotion/Expression_27.png" class="emotion"/>',
			'[流汗]': '<img src="/static/img/emotion/Expression_28.png" class="emotion"/>',
			'[憨笑]': '<img src="/static/img/emotion/Expression_29.png" class="emotion"/>',
			/*从这*/
			'[悠闲]': '<img src="/static/img/emotion/Expression_30.png" class="emotion"/>',
			'[奋斗]': '<img src="/static/img/emotion/Expression_31.png" class="emotion"/>',
			'[咒骂]': '<img src="/static/img/emotion/Expression_32.png" class="emotion"/>',
			'[疑问]': '<img src="/static/img/emotion/Expression_33.png" class="emotion"/>',
			'[嘘]': '<img src="/static/img/emotion/Expression_34.png" class="emotion"/>',
			'[晕]': '<img src="/static/img/emotion/Expression_35.png" class="emotion"/>',
			'[疯了]': '<img src="/static/img/emotion/Expression_36.png" class="emotion"/>',
			'[衰]': '<img src="/static/img/emotion/Expression_37.png" class="emotion"/>',
			'[骷髅]': '<img src="/static/img/emotion/Expression_38.png" class="emotion"/>',
			'[敲打]': '<img src="/static/img/emotion/Expression_39.png" class="emotion"/>',
			'[再见]': '<img src="/static/img/emotion/Expression_40.png" class="emotion"/>',
			'[擦汗]': '<img src="/static/img/emotion/Expression_41.png" class="emotion"/>',
			'[抠鼻]': '<img src="/static/img/emotion/Expression_42.png" class="emotion"/>',
			'[鼓掌]': '<img src="/static/img/emotion/Expression_43.png" class="emotion"/>',
			'[糗大了]': '<img src="/static/img/emotion/Expression_44.png" class="emotion"/>',
			'[坏笑]': '<img src="/static/img/emotion/Expression_45.png" class="emotion"/>',
			'[左哼哼]': '<img src="/static/img/emotion/Expression_46.png" class="emotion"/>',
			'[右哼哼]': '<img src="/static/img/emotion/Expression_47.png" class="emotion"/>',
			'[哈欠]': '<img src="/static/img/emotion/Expression_48.png" class="emotion"/>',
			'[鄙视]': '<img src="/static/img/emotion/Expression_49.png" class="emotion"/>',
			'[委屈]': '<img src="/static/img/emotion/Expression_50.png" class="emotion"/>',
			'[快哭了]': '<img src="/static/img/emotion/Expression_51.png" class="emotion"/>',
			'[阴险]': '<img src="/static/img/emotion/Expression_52.png" class="emotion"/>',
			'[亲亲]': '<img src="/static/img/emotion/Expression_53.png" class="emotion"/>',
			'[吓]': '<img src="/static/img/emotion/Expression_54.png" class="emotion"/>',
			'[可怜]': '<img src="/static/img/emotion/Expression_55.png" class="emotion"/>',
			'[菜刀]': '<img src="/static/img/emotion/Expression_56.png" class="emotion"/>',
			'[西瓜]': '<img src="/static/img/emotion/Expression_57.png" class="emotion"/>',
			'[啤酒]': '<img src="/static/img/emotion/Expression_58.png" class="emotion"/>',
			'[篮球]': '<img src="/static/img/emotion/Expression_59.png" class="emotion"/>',
			'[乒乓]': '<img src="/static/img/emotion/Expression_60.png" class="emotion"/>',
			'[咖啡]': '<img src="/static/img/emotion/Expression_61.png" class="emotion"/>',
			'[饭]': '<img src="/static/img/emotion/Expression_62.png" class="emotion"/>',
			'[猪头]': '<img src="/static/img/emotion/Expression_63.png" class="emotion"/>',
			'[玫瑰]': '<img src="/static/img/emotion/Expression_64.png" class="emotion"/>',
			'[凋谢]': '<img src="/static/img/emotion/Expression_65.png" class="emotion"/>',
			'[嘴唇]': '<img src="/static/img/emotion/Expression_66.png" class="emotion"/>',
			'[爱心]': '<img src="/static/img/emotion/Expression_67.png" class="emotion"/>',
			'[心碎]': '<img src="/static/img/emotion/Expression_68.png" class="emotion"/>',
			'[蛋糕]': '<img src="/static/img/emotion/Expression_69.png" class="emotion"/>',
			'[闪电]': '<img src="/static/img/emotion/Expression_70.png" class="emotion"/>',
			'[炸弹]': '<img src="/static/img/emotion/Expression_71.png" class="emotion"/>',
			'[刀]': '<img src="/static/img/emotion/Expression_72.png" class="emotion"/>',
			'[足球]': '<img src="/static/img/emotion/Expression_73.png" class="emotion"/>',
			'[瓢虫]': '<img src="/static/img/emotion/Expression_74.png" class="emotion"/>',
			'[便便]': '<img src="/static/img/emotion/Expression_75.png" class="emotion"/>',
			'[月亮]': '<img src="/static/img/emotion/Expression_76.png" class="emotion"/>',
			'[太阳]': '<img src="/static/img/emotion/Expression_77.png" class="emotion"/>',
			'[礼物]': '<img src="/static/img/emotion/Expression_78.png" class="emotion"/>',
			'[拥抱]': '<img src="/static/img/emotion/Expression_79.png" class="emotion"/>',
			'[强]': '<img src="/static/img/emotion/Expression_80.png" class="emotion"/>',
			'[弱]': '<img src="/static/img/emotion/Expression_81.png" class="emotion"/>',
			'[握手]': '<img src="/static/img/emotion/Expression_82.png" class="emotion"/>',
			'[胜利]': '<img src="/static/img/emotion/Expression_83.png" class="emotion"/>',
			'[抱拳]': '<img src="/static/img/emotion/Expression_84.png" class="emotion"/>',
			'[勾引]': '<img src="/static/img/emotion/Expression_85.png" class="emotion"/>',
			'[拳头]': '<img src="/static/img/emotion/Expression_86.png" class="emotion"/>',
			'[差劲]': '<img src="/static/img/emotion/Expression_87.png" class="emotion"/>',
			'[爱你]': '<img src="/static/img/emotion/Expression_88.png" class="emotion"/>',
			'[NO]': '<img src="/static/img/emotion/Expression_89.png" class="emotion"/>',
			'[OK]': '<img src="/static/img/emotion/Expression_90.png" class="emotion"/>',
			'[爱情]': '<img src="/static/img/emotion/Expression_91.png" class="emotion"/>',
			'[飞吻]': '<img src="/static/img/emotion/Expression_92.png" class="emotion"/>',
			'[跳跳]': '<img src="/static/img/emotion/Expression_93.png" class="emotion"/>',
			'[发抖]': '<img src="/static/img/emotion/Expression_94.png" class="emotion"/>',
			'[怄火]': '<img src="/static/img/emotion/Expression_95.png" class="emotion"/>',
			'[转圈]': '<img src="/static/img/emotion/Expression_96.png" class="emotion"/>',
			'[磕头]': '<img src="/static/img/emotion/Expression_97.png" class="emotion"/>',
			'[回头]': '<img src="/static/img/emotion/Expression_98.png" class="emotion"/>',
			'[跳绳]': '<img src="/static/img/emotion/Expression_99.png" class="emotion"/>',
			'[投降]': '<img src="/static/img/emotion/Expression_100.png" class="emotion"/>',
			'[激动]': '<img src="/static/img/emotion/Expression_101.png" class="emotion"/>',
			'[街舞]': '<img src="/static/img/emotion/Expression_102.png" class="emotion"/>',
			'[献吻]': '<img src="/static/img/emotion/Expression_103.png" class="emotion"/>',
			'[左太极]': '<img src="/static/img/emotion/Expression_104.png" class="emotion"/>',
			'[右太极]': '<img src="/static/img/emotion/Expression_105.png" class="emotion"/>'
		},
	};
	zpChat.log = function (msg) {
		var self = this;
		if(self.debug) {
			console.log('出错' + JSON.stringify(msg));
		}
	};
	zpChat.setTime=function(time){
		if(isEmpty(time)){
			return false;
		}
		//return zpChat.formatDate(time*1000);
		var result='';
		var currentTime = Date.parse(new Date());
		var dateTime = time*1000;//后台传递来的时间
		var d=new Date(dateTime);
		var d_day = Date.parse(d);
		var day = Math.abs(parseInt((d_day - currentTime)/1000/3600/24));//计算日期
		var hour = parseInt(d.getHours());//小时
		var minutes = zpChat.extra(parseInt(d.getMinutes()));//分钟
		if(day>7){
			var year=d.getFullYear();
			var month= zpChat.extra(d.getMonth()+1);
			var date= zpChat.extra(d.getDate());
			result= year+'/'+month+'/'+date +' ' +(hour+":"+minutes+'').toString() ;;
		}else if(day<=7&&day >= 2){
			result=zpChat.week(d)+' '+(hour+":"+minutes+'').toString();
		}else if(day > 0 && day < 2){
			result=("昨天").toString() +' '+(hour+":"+minutes+'').toString() ;
		}else {
			var str=hour<12?'上午':'下午';
			result=  str+' '+(hour+":"+minutes+'').toString();
		}
		return result;
	},
		zpChat.extra=function(x){
			//如果传入数字小于10，数字前补一位0。///
			if (parseInt(x) < 10) {
				return "0" + parseInt(x);
			} else {
				return x;
			}
		},
		zpChat.week=function(d){
			var w='';
			switch (d.getDay()) {
				case 0:w="星期天";break;
				case 1:w="星期一";break;
				case 2:w="星期二";break;
				case 3:w="星期三";break;
				case 4:w="星期四";break;
				case 5:w="星期五";break;
				case 6:w="星期六";break;
			}
			return w;
		},

		zpChat.initWs = function (cb) {
			connectWS(cb);
		};
	zpChat.init = function (self, params, callback) {

		this.list_id=params.list_id;
		this.token=params.token;
		typeof callback == 'function' && callback();
	};

	zpChat.setFromMsgRead = function (messageId) {
		zpRequest.onBtn('unReadNews', messageId);
	};
	zpChat.isCurrentChatObject=function(result){
		return result[0].list_id==zpChat.list_id?true:false;
	};
	zpChat.showBtn = function () {
        zpRequest.showBtn();
    };
    //1：进行中，2：已结束:3：追问中
    zpChat.showBtn_1 = function () { //显示结束按钮 聊天框
		//zpChat.log('show1');
		$(".aglinC").hasClass('active') || $(".aglinC").addClass("active");
		$(".zw").hasClass('none') || $(".zw").addClass('none');
		$(".fz").hasClass('none') || $(".zw").addClass('none');
		$(".jswz").hasClass('none') && $(".jswz").removeClass("none");

		$(".warningTit").hasClass('none') || $(".warningTit").addClass('none'); // 追问提醒信息
		$(".warningFz").hasClass('none') || $(".warningFz").addClass('none'); //   复诊提醒信息

		$(".btn-fix").hasClass('active') || $(".btn-fix").addClass("active"); //  支付放弃按钮提醒信息


		$('.fs').hasClass('none') && $('.fs').removeClass('none');
		$('.inputImg').hasClass('none') && $('.inputImg').removeClass('none');
	};

	zpChat.showBtn_2 = function () {  //显示追问 显示复诊   隐藏聊天窗口 隐藏结束问诊
		//zpChat.log('show2');
		$(".aglinC").hasClass('active') || $(".aglinC").addClass("active");
		$(".zw").hasClass('none') && zpChat.isZw==1 && $(".zw").removeClass('none');
		$(".fz").hasClass('none') && zpChat.isFz == 1 && $(".fz").removeClass('none');
		$(".jswz").hasClass('none') || $(".jswz").addClass("none");

		$(".warningTit").hasClass('none') || $(".warningTit").addClass('none'); // 追问提醒信息
		$(".warningFz").hasClass('none') || $(".warningFz").addClass('none'); //   复诊提醒信息

		$(".btn-fix").hasClass('active') || $(".btn-fix").addClass("active");

		$('.fs').hasClass('none') || $('.fs').addClass('none');
		$('.inputImg').hasClass('none') || $('.inputImg').addClass('none');
		pageDown(200);
	};


	zpChat.showBtn_3 = function (show) {  //显示复诊 显示聊天窗口  显示提示信息  隐藏追问 隐藏结束按钮
		$('#zpTips').text(zpChat.tipText[show]);
		//zpChat.log('show3');
		$(".aglinC").hasClass('active') || $(".aglinC").addClass("active");
		$(".zw").hasClass('none') || $(".zw").addClass('none');
		$(".fz").hasClass('none') && zpChat.isFz == 1 && $(".fz").removeClass('none');
		$(".jswz").hasClass('none') || $(".jswz").addClass("none");

		$(".warningTit").hasClass('none') && $(".warningTit").removeClass('none'); // 追问提醒信息
		$(".warningFz").hasClass('none') || $(".warningFz").addClass('none'); //   复诊提醒信息

		$(".btn-fix").hasClass('active') || $(".btn-fix").addClass("active");

		show == 3 ? $('.fs').hasClass('none') && $('.fs').removeClass('none')
			: $('.fs').hasClass('none') || $('.fs').addClass('none');
		show == 3 ? $('.inputImg').hasClass('none') && $('.inputImg').removeClass('none')
			: $('.inputImg').hasClass('none') || $('.inputImg').addClass('none');

		setTimeout(function () {
			$(".warningTit").hasClass('none') || $(".warningTit").addClass('none');
		}, zpChat.tipShowTime);
		pageDown(200);
	};
	zpChat.showSubPayTip = function () {
		zpChat.subPayRead==0 && $('.tips').hasClass('none') && $('.tips').removeClass('none');
		setTimeout(function () {
			zpChat.subPayRead==0 && $('.tips').hasClass('none') || $('.tips').addClass('none');
		}, 5000);
		zpChat.subPayRead==0 && zpChat.upSubPayRead();
	};
	zpChat.upSubPayRead=function(){
		zpRequest.onBtn('upSubPayRead');
	};
	//点击追问--显示发送按钮，复诊---隐藏追问
	zpChat.zw = function () {
		zpRequest.onBtn('zw');
	};
	//点击复诊 --显示立即支付和放弃
	zpChat.fz = function () {  //显示复诊提示  显示立即支付  隐藏聊天窗口  隐藏追问 隐藏复诊 隐藏结束按钮
		$(".aglinC").hasClass('active') && $(".aglinC").removeClass("active");
		$(".zw").hasClass('none') || $(".zw").addClass('none');
		$(".fz").hasClass('none') || $(".fz").addClass('none');
		$(".jswz").hasClass('none') || $(".jswz").addClass("none");

		$(".warningTit").hasClass('none') || $(".warningTit").addClass('none'); // 追问提醒信息
		$(".warningFz").hasClass('none') && $(".warningFz").removeClass('none'); //   复诊提醒信息

		$(".btn-fix").hasClass('active') && $(".btn-fix").removeClass("active");     //显示支付按钮

		$('.fs').hasClass('none') && $('.fs').removeClass('none');
		$('.inputImg').hasClass('none') && $('.inputImg').removeClass('none');

		setTimeout(function () {
			$(".warningFz").hasClass('none') || $(".warningTit").addClass('none');
		}, zpChat.tipShowTime);
		pageDown(200);
	};

	zpChat.showPay = function () {
		//跳转到支付页面

		//更新 clinicId

	};

	zpChat.cancelPay = function () {
		//
		if(zpChat.showBtnStatus == 2) {
			zpChat.showBtn_2();
		} else if(zpChat.showBtnStatus == 3 || zpChat.showBtnStatus == 5) {
			zpChat.showBtn_3(zpChat.showBtnStatus);
		}

	};
	//点击结束诊疗 -显示追问，复诊---隐藏发送按钮
	zpChat.endSick = function () {
		zpRequest.onBtn('endSick');
	};
	zpChat.transExtra = function (arg) {
		var result = '';
		try {
			result = eval('(' + arg + ')');
		} catch (e) {
			result = arg.slice(1, -1);
		} finally {

		}
		return result;
	};
	zpChat.returnHtml = function (result, vueObj,append,cb) {
		console.log('append:'+JSON.stringify(append));
		var list = vueObj.lists;
		var len = result.length - 1;
		for (var i = 0; i <= len; i++) {
			//var extra = zpChat.transExtra(result[i].content.extra);
			var extraTmp = result[i].content.extra;
			var type = zpChat.type;
			var from = '';
			if(result[i].type == type.user || result[i].type == type.doctor) {
				from = result[i].type == type[zpChat.selfType] ? zpChat.from.RIGHT : zpChat.from.LEFT;
			} else {
				from = zpChat.from.MIDDLE;
			}
			var img = "";
			if(from == zpChat.from.LEFT) {
				img = zpChat[zpChat.otherType]['img'];
			} else if(from == zpChat.from.RIGHT) {
				img = zpChat[zpChat.selfType]['img'];
			} else {
				img = '';//系统
			}
			var extra = {};
			switch (result[i].msg_type) {
				case 'text' :
					extra.text = result[i].content.text.replace(zpChat.reg, function (a, b) {
						return zpChat.face[a] ? zpChat.face[a] : a;
					});
					break;
				case 'card' :
					extra.text = result[i].content.text;
					extra.con = extraTmp.con;
					extra.cid = extraTmp.id;
					extra.clinic_id = extraTmp.clinic_id;
					extra.cType = extraTmp.cType;
					extra.option_id=extraTmp.option_id;
					extra.option_title=extraTmp.option_title;
					extra.option_type=extraTmp.option_type;
					extra.recipe=extraTmp.recipe;
					extra.is_price=extraTmp.is_price;
					extra.textW='推荐您在【泰和国医】抓药';
					break;
				case 'audio':
					extra.voicePath = result[i].content.voicePath;
					extra.duration = result[i].content.duration;
					break;
				case 'image':
					console.log(result[i].content.key);
					extra.key = webim.voiceUrl+result[i].content.key;
					extra.hash = result[i].content.hash;
					break;
			}
			var ret = {
				messageId: result[i].id,
				from: from,   //消息  1：患者 2 医生 3系统
				img: img,
				extra: extra,
				msg_type: result[i].msg_type,
				timeShow: extraTmp.timeShow,
				created_ed: zpChat.setTime(result[i].created_ed),
				time: zpChat.setTime(result[i].created_ed),
			};

			if(append=='new') {
				list.push(ret);
			} else {
				list.unshift(ret);
			}
		}
		vueObj.lists = list;
		typeof cb =='function' && cb();


	};
	//获取会话列表信息
	zpChat.getLatestMessages = function () {
		zpRequest.getHistory({
			count: 10,
			historyInt: zpChat.loadHistory.INT,
		});
	};
	//上滑加载更多
	zpChat.getHistoryMessages = function (id) {
		var oldestMessageId = id;
		//console.log('oldestMessageId:'+oldestMessageId);
		if(oldestMessageId && isEmpty(zpChat.isLoading)) {
			zpChat.isLoading=true;
			zpRequest.getHistory({
				oldestMessageId: oldestMessageId,
				count: 10,
				historyInt: zpChat.loadHistory.LOADING,
			});
		} else {
			//console.log('不能上滑加载数据');
		}

	};

	zpChat.sendImgMessage=function(data,callback){
		var self = this;
		self.newTimestamp = Math.round(new Date().getTime());
		if(self.newTimestamp - self.oldTimestamp > self.timeDelay) {
			self.timeShow = 1;
			self.oldTimestamp = self.newTimestamp;
		} else {
			self.timeShow = 0;
		}
		var extra = {timeShow: self.timeShow};
		console.log('sendImg:'+JSON.stringify(data));
		zpRequest.sendImgMessage({
			hash:data.hash,
			key: data.key,
			type:data.type?data.type:0,
			msg_type:data.msg_type?data.msg_type:zpChat.msg_type.IMG,
			extra:extra
		}, function (ret, err) {
			if(ret) {
				callback(ret);
			}
		});
	};
	zpChat.sendTextMessage = function (data, callback) {
		var self = this;
		self.newTimestamp = Math.round(new Date().getTime());
		if(self.newTimestamp - self.oldTimestamp > self.timeDelay) {
			self.timeShow = 1;
			self.oldTimestamp = self.newTimestamp;
		} else {
			self.timeShow = 0;
		}
		var extra = {timeShow: self.timeShow};
		zpRequest.sendTextMessage({
			text: data.text,
			type: data.type ? data.type : 0,
			//extra:JSON.stringify(extra)
			extra: extra
		}, function (ret) {
			if(ret) {
				callback(ret);
			}
		});
	};
	zpChat.getSyncMsg=function(_self){
		if(zpChat.is_sync){
			return false;
		}
		var len=_self.lists.length;
		var firstId=0;
		if(len>0){
			firstId=_self.lists[len-1].messageId;
		}
		console.log('当前消息视图中最后一条消息id:'+firstId);
		var data={cmd:'getSyncMsg',type:'1',userId:zpChat.doctor.uid,listId:zpChat.list_id,firstId:firstId};
		zpRequest.send(data);
	};

	zpChat.onBtn = function (cb, params) {
		var param = {};
		param.cmd = cb;
		if(params){
			$.fn.extend(param,params);
		}
		console.log('cb:'+cb);
		zpRequest.send(param);

	};



	var zpRequest = {};
	zpRequest.send = function (data) {
		data.listId = zpChat.list_id;
		data.userId = !isEmpty(zpChat.doctor)&&!isEmpty(zpChat.doctor.uid)?zpChat.doctor.uid:0;
		ws.send(JSON.stringify(data));
	};
	zpRequest.sendMsg = function (data, callback) {
		data.cmd = data.cmd ? data.cmd : 'message';
		data.type = data.type ? data.type : (zpChat.selfType == 'doctor' ? '2' : '1');
		zpRequest.send(data);
		data.time = Math.round(new Date().getTime()) / 1000;
		zpChat.historyInt = zpChat.loadHistory.INT;
		typeof callback == 'function' && callback({result: [data], status: 'prepare'});
	};
	zpRequest.getMsg = function (data, callback) {
		data.cmd = 'getHistory';
		zpRequest.send(data);
		zpChat.historyInt = data.historyInt;
		typeof callback == 'function' && callback();
	};
	zpRequest.sendImgMessage = function(params,callback){
		var param={};
		params.type ?param.type=params.type:'';
		param.msg_type= params.msg_type ?params.msg_type: zpChat.msg_type.IMG;
		param.content={key:params.key,hash:params.hash,extra:params.extra};
		this.sendMsg(param,callback);
	};
	zpRequest.sendTextMessage = function (params, callback) {
		var param = {};
		params.type ? param.type = params.type : '';
		param.msg_type = zpChat.msg_type.TEXT;
		param.content = {text: params.text, extra: params.extra};
		this.sendMsg(param, callback);
	};

	// 获取会话消息
	zpRequest.getHistory = function (params) {
		var param = {
			firstId: params.oldestMessageId || 0,
			count: params.count || 10,
			historyInt: params.historyInt
		};
		this.getMsg(param);
	};
	zpRequest.onBtn = function (cb, params) {
		var param = {};
		param.cmd = cb;
		if(params){
			$.fn.extend(param,params);
		}
		this.sendMsg(param);

	};
	zpRequest.showBtn = function () {
		var param = {};
		param.cmd = 'showBtn';
		this.send(param);
	};
	zpChat.rmStorage=function(){
		localStorage.removeItem("webImOffline");
	};
	zpChat.getStorage=function(){
		return localStorage.getItem("webImOffline");
	};
	zpChat.setStorage=function(){
		localStorage.setItem("webImOffline", "1");
	};
	win.zpChat = zpChat;



}(window, jQuery);


//滑动到底部
function pageDown(time) {
	setTimeout(function () {
		$('.chat_body').stop().animate({scrollTop: 100000}, 300);
	}, time || 200);

}

function openImage(path) {
	var imageBrowser = api.require('imageBrowser');
	imageBrowser.openImages({
		imageUrls: [path]
	});
}


function play(obj, path) {
	var $play = $(obj);
	$play.addClass('playing');
	api.startPlay({
		path: path
	}, function () {
		$play.removeClass('playing');
	});
}

//因声音短，或者取消发送 而停止录音，并删除录音文件
function stopRecord() {
	api.stopRecord(function (ret, err) {
			if(ret) {
				fs.remove({path: ret.path});
			}
		}
	);
}


var ws = null;
var GET = getRequest();
function Comet(url) {
	this.url = url.replace('ws://', 'http://');
	this.connected = false;
	this.session_id = '';
	this.send_queue = [];
	this.sending = false;
	jQuery.support.cors = true;

	this.send = function (msg) {
		this.send_queue.push(msg);
		//当前状态是否可以发送数据
		if(this.connected && !this.sending) {
			this.sendMessage();
		}
	};

	this.sendMessage = function () {
		if(this.send_queue.length == 0) {
			this.sending = false;
			return;
		}

		var websocket = this;
		var msg = this.send_queue.pop();
		this.sending = true;

		$.ajax({
			type: "POST",
			dataType: "json",
			url: this.url + '&url=pub',
			data: {type: 'pub', message: msg, session_id: websocket.session_id},
			success: function (data, textStatus) {
				//发送数据成功
				if(data.success == "1") {
					//继续发送
					websocket.sendMessage();
				} else {
					console.log("ErrorMessage: " + data);
				}
			},
			error: function (XMLHttpRequest, textStatus, errorThrown) {
				var e = {};
				e.data = textStatus;
				websocket.onerror(e);
			}
		});
	};

	//连接到服务器
	this.connect = function () {
		var websocket = this;
		$.ajax({
			type: "POST",
			dataType: "json",
			url: this.url + '&url=connect',
			data: {'type': 'connect'},
			success: function (data, textStatus) {
				//发送数据成功
				if(data.success == "1") {
					websocket.session_id = data.session_id;
					websocket.connected = true;
					websocket.loop();
					websocket.onopen({});
				} else {
					console.log("ErrorMessage: " + data);
				}
			},
			error: function (XMLHttpRequest, textStatus, errorThrown) {
				var e = {};
				e.data = textStatus;
				alert("connect to server [" + websocket.url + "] failed. Error: " + errorThrown);
			}
		});
	};

	this.loop = function () {
		var websocket = this;
		$.ajax({
			type: "POST",
			dataType: "json",
			url: websocket.url + '&url=sub',
			timeout: 80000,     //ajax请求超时时间80秒
			data: {time: "80", session_id: websocket.session_id, type: 'sub'}, //80秒后无论结果服务器都返回数据
			success: function (data, textStatus) {
				var e = {'data': data.data};
				//从服务器得到数据，显示数据并继续查询
				if(data.success == "1") {
					websocket.onmessage(e);
				}
				//未从服务器得到数据，继续查询
				else if(data.success == "0") {
					//$("#msg").append("<br>[无数据]");
				} else {
					console.log("ErrorMessage: " + data);
				}
				websocket.loop();
			},
			//Ajax请求超时，继续查询
			error: function (XMLHttpRequest, textStatus, errorThrown) {
				if(textStatus == "timeout") {
					websocket.loop();
				} else {
					console.log("Server Error: " + textStatus);
					var e = {};
					e.data = textStatus;
					websocket.onclose(e);
				}
			}
		});
	};
	this.connect();
}
var user = {};
window.cb = null;
var timeout;
var lockReconnect = false;//避免重复连接
function connectWS(cb) {
	timeout = null;
	//使用原生WebSocket
	var im_url = '';

	switch (location.hostname) {
		case "localhost":
			im_url = webim.local;
			break;
		case "dangwen.app":
			im_url = webim.local;
			break;
		case "tcm.vmh5.com":
			im_url = webim.dev;
			break;
        case "":
		default:
			im_url = webim.pro;
			break;
	}
    console.log(im_url);
    window.cb = cb;
	if(ws == null){
        if(window.WebSocket || window.MozWebSocket) {
            try{
                ws = new WebSocket(im_url + '?' + 'type=' + zpChat.selfType + '&token=' + zpChat.token);
            }catch (e){
                console.log(JSON.stringify(e));
            } finally {

            }

        }
        //使用http xhr长轮循
        else {
            ws = new Comet(im_url);
        }
        listenEvent();
	}else{
        zpChat.onBtn('getChatUser');
	}
}
function listenEvent() {
	/**
	 * 连接建立时触发
	 */
	ws.onopen = function (e) {
		//连接成功
		console.log("connect webim server success.");
		//发送登录信息
		heartCheck.reset().start();
	};

	//有消息到来时触发
	ws.onmessage = function (e) {
		var message = JSON.parse(e.data);//
		heartCheck.reset().start();
		console.log('消息监听：' + JSON.stringify(message.cmd));
		var cmd = message.cmd;
		if(cmd == 'connect') {

			zpChat.rmStorage();//
			zpChat.onBtn('getChatUser');//todo:getChatUser
			console.log('连接成功：' + JSON.stringify(e));
		}else if(cmd == 'getChatUser') {
			typeof cb == "function" && cb('getChatUser', message);
		}else if(cmd=="getSyncMsg"){
			console.log('getSyncMsg：' + JSON.stringify(message.syncMsg));
			if(!isEmpty(message.syncMsg)){
				typeof cb == "function" && cb('syncMsg', message.syncMsg);
			}
		}else if(cmd == 'getHistory'){
			typeof cb == "function" && cb('history', message.history);
		}else if(cmd == 'fromMsg') {
			message.append = true;
			console.log('fromMsg' + JSON.stringify(message));
			//zpChat.setFromMsgRead(message.messageId);
			typeof cb == "function" && cb('oneMsg', [message]);
			//新消息来了
		}else if(cmd == 'fromMsgSelf') {
			message.append = true;
			typeof cb == "function" && cb('oneMsg', [message]);
			console.log('fromMsgSelf' + JSON.stringify(message));
			//新消息来了
		}
		else if(cmd == 'showBtn') {
			typeof cb == "function" && cb('showBtn', message);
			//console.log('showBtn'+JSON.stringify(message));
			//新消息来了
		} else if(cmd == 'exit'){

			zpChat.isSendMsg=false;
			zpChat.isSendMsgTip='账号被挤下线,暂时不能发送消息';
			timeout=true;//禁止重连重连
			this.$router.push({path:'/sign'});
		}
	};

	/**
	 * 连接关闭事件
	 */
	ws.onclose = function (e) {
		console.log('close' + JSON.stringify(e));
		if(e.code != 1008) {
			zpChat.setStorage();
			reconnect(cb);
		} else {
			//$('.brand').html(e.reason);
		}
	};

	/**
	 * 异常事件
	 */
	ws.onerror = function (e) {
		// $('.brand').html("服务器[" + webim.server +"]: 拒绝了连接. 请检查服务器是否启动. ");
		console.log("onerror: " + JSON.stringify(e));
		zpChat.setStorage();
		reconnect(cb);
	};
}

function getRequest() {
	var url = location.search; // 获取url中"?"符后的字串
	var theRequest = new Object();
	if(url.indexOf("?") != -1) {
		var str = url.substr(1);

		strs = str.split("&");
		for (var i = 0; i < strs.length; i++) {
			var decodeParam = decodeURIComponent(strs[i]);
			var param = decodeParam.split("=");
			theRequest[param[0]] = param[1];
		}

	}
	return theRequest;
}




function reconnect(cb) {
	if(lockReconnect) return;
	lockReconnect = true;
	//没连接上会一直重连，设置延迟避免请求过多
	setTimeout(function () {
		connectWS(cb);
		lockReconnect = false;
	}, 2000);
}
(function ($) {
	$.fn.extend({
		insertAtCaret: function (myValue) {
			var $t = $(this)[0];
			if(document.selection) {
				this.focus();
				sel = document.selection.createRange();
				sel.text = myValue;
				this.focus();
			}
			else if($t.selectionStart || $t.selectionStart == '0') {
				var startPos = $t.selectionStart;
				var endPos = $t.selectionEnd;
				var scrollTop = $t.scrollTop;
				$t.value = $t.value.substring(0, startPos) + myValue + $t.value.substring(endPos, $t.value.length);
				this.focus();
				$t.selectionStart = startPos + myValue.length;
				$t.selectionEnd = startPos + myValue.length;
				$t.scrollTop = scrollTop;
			}
			else {

				this.value += myValue;
				this.focus();
			}
		}
	})
})(jQuery);
//心跳检测
var heartCheck = {
	timeout: 30000,//60秒
	timeoutObj: null,
	serverTimeoutObj: null,
	reset: function(){
		clearTimeout(this.timeoutObj);
		clearTimeout(this.serverTimeoutObj);
		zpChat.rmStorage();
		return heartCheck;
	},
	start: function(){
		var self = this;
		this.timeoutObj = setTimeout(function(){
			//这里发送一个心跳，后端收到后，返回一个心跳消息，
			//onmessage拿到返回的心跳就说明连接正常
			ws.send(JSON.stringify({cmd:'heartBeat'}));
			self.serverTimeoutObj = setTimeout(function(){//如果超过一定时间还没重置，说明后端主动断开了
				zpChat.setStorage();
				ws.close();//如果onclose会执行reconnect，我们执行ws.close()就行了.如果直接执行reconnect 会触发onclose导致重连两次
			}, self.timeout)
		}, this.timeout)
	}
}
