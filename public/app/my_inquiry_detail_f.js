var vueObj=new Vue({
	el: '#data',
	data: {  //todo:新建的 isNew=true  buttonShow=true      //编辑 初次进来 isNew=false buttonShow=false  点击编辑按钮  isNew=false buttonShow=true
		value:'',//当前输入框临时变量不可超过两行
		isNew:true,  //判断是否新建
		buttonShow:true,  //判断是否编辑
		info:[
			// {
			// 	"type": 'radio',
			// 	"title": '',
			// 	"option": [
			// 		{"val": 'dsfasd9 '},
			// 		{"val": '后跑到房间啊'},
			// 		{"val": '后跑到房间啊'}
			// 	]
			// },
			// {
			// 	"type": 'photo',
			// 	"title": '',
			// 	"option": ["http://static.googleadsserving.cn/pagead/imgad?id=CICAgKDLr9K-lQEQ2AUYWjIIbQDzPOlgMEM","https://gss0.bdstatic.com/70cFfyinKgQIm2_p8IuM_a/daf/pic/item/0b7b02087bf40ad1445506445d2c11dfa8eccee0.jpg"]
			// }
		]
	},
	methods: {
		change:function(e,index){
			if (e.target.value.length > 100){
				e.target.value=this.value;
			}else{
				this.value = e.target.value;
			}
		},
		//添加不同类型的题
		addDataTpl:function(type){
			zpFrame.dataTplType=type;
			vueObj.buttonShow=true;
			var tpl=zpFrame.copyObj(zpFrame[type]);
			vueObj.info.push(tpl);
			console.log('vueObj:'+JSON.stringify(vueObj.info)+'tpl:'+JSON.stringify(tpl));
		},
		//添加选项
		addOptionTpl:function(index){
			vueObj.info[index].option.push({"val":''});
		},
		//添加图片
		upImg:function(key,index){
			if(this.buttonShow){
				zpFrame.imgUpTmp.key=key;
				zpFrame.imgUpTmp.index=index;
				var ie = !-[1,];
				if(ie){
					$('input:file').trigger('click').trigger('change');
				}else{
					$('input:file').trigger('click');
				}


				// zpFrame.upload(obj,function(img){
				// 	//console.log('头像'+JSON.stringify(img));
				// 	//Vue.set( vueObj.info[index].option,key,img);
				// });
			}
		},
		//添加图片
		delImg:function(key,index){
			window.event.cancelBubble = true;
			vueObj.info[index].option.splice( key,1,"" );
		}

	},
});

//进入此页面需传参数  {examType:'创建页面类型 '} 1=男 2=女 3=儿童  此参数为创建
//进入此页面需传参数  {["id": 1,"title": "成人男","option": [] ] }  option 为空数组 也是创建  option 数组不为空则是编辑
// apiready = function () {
//
// };
; !function (win,$){
	var zpFrame={
		winName:'',
		pageParam:{},
		method:'', //add=添加  edit=编辑
		baseUrl:{
			get:"/inquiry/detail",
			save:'/save_exam',//exam/edit/{exam_id}
		},
		doneUrl:'',
		doneParam:{},
		doneMethod:'get',
		getParam:{},
		dataTplType:'radio',//默认单选
		imgUpTmp:{
			key:'',
			index:'',
		},
		imgUrl:location.protocol+'//'+location.host+'/',
		init:function(){
			this.setParam();
			this.setDataTpl();//设置数据模板类型
			this.getData();//获取数据
			this.upload();
		},
		done:function(){
			zpFrame.pushData();
		},
		ajax:function(param,cb){
			$.ajax({
				url:zpFrame.doneUrl,
				type:zpFrame.doneMethod,
				data:param,
				dataType:'json',
				success:function(data){
					 cb(data);
				},
				error:function(XMLHttpRequest, textStatus, errorThrown){
					//alert(XMLHttpRequest.status);
					//alert(XMLHttpRequest.readyState);
					//alert(textStatus);
				},
			});
		},
		setParam:function(){
			var tmp=location.href.split('inquiry/')[1].split('/type/');
			this.pageParam.code=tmp[0];
			this.pageParam.type=tmp[1];
			//console.log(JSON.stringify('pageParam:'+JSON.stringify(this.pageParam)));
		},
		getData:function(){
			var self=this;
			if(!self._getBefore()) return false;
			zpFrame.ajax(self.getParam,function(ret){
				console.log('return:'+JSON.stringify(ret));
				if(ret.status){
					self.setData('get',ret.data);
				}else{
					//lbb.toast(ret.msg);
				}
			});
		},
		_getBefore:function(){
			if(!isEmpty(this.pageParam.code)&&!isEmpty(this.pageParam.type)){
				this.doneUrl    = this.baseUrl['get'];
				this.doneMethod ='get';
				this.getParam={};
				this.getParam  = zpFrame.copyObj(this.pageParam);
				console.log('true:');
				return true;
			}else{
				console.log('false:');
				return false;
			}
		},
		setData:function(type,data){
			switch (type){
				case 'get':
					if(!isEmpty(data)){
						Vue.set(vueObj,'info',data.options);
					}else{
					}
					break;
			}

		},
		pushData:function(){
			var self=this;
			if(!self._pushBefore()) return false;
			zpFrame.ajax(self.doneParam,function(ret,err){
				if(ret.status){
					alert(ret.msg);
					//self._pushAfter(ret.data);
				}else{
					alert(ret.msg);
				}
			},true);
		},
		_pushBefore:function(){
			var self=this;
			if(isEmpty(vueObj.info)){
				alert('请设置至少一道题');
				return false;
			}else {
				this.doneUrl    = this.baseUrl['save'];
				this.doneMethod ='post';

				self.doneParam={};
				self.doneParam={code:self.pageParam.code,type:self.pageParam.type,option:vueObj.info};
				return true;
			}

		},
		_pushAfter:function(data){//成功的操作
			var self=this;
			//跳转至编辑页面
			//if(!isEmpty(data)){
			//	self.pageParam.id=data.id;
			//}
			//self.closeWin();

		},
		setDataTpl:function(){
			this.radio={
				"type": 'radio',
				"title": '',
				"option": [
					{"val": ''},
					{"val": ''}
				]
			},
				this.checkbox={
					"type": 'checkbox',
					"title": '',
					"option": [
						{"val": ''},
						{"val": ''}
					]
				},
				this.text={
					"type": 'text',
					"title": '',
					"option": ''
				},
				this.photo={
					"type": 'photo',
					"title": '',
					"option": ["","",""]
				}
		},
		copyArr: function (arr) {
			var arrObj = [];
			for (var i = 0; i < arr.length; ++i) {
				var type = typeof arr[i];
				switch (type) {
					case 'number':
					case 'string':
						arrObj[i] = arr[i];
						break;
					case 'object':
						arrObj[i] = this.isArray(arr[i]) ? this.copyArr(arr[i]) : this.copyObj(arr[i]);
						break
				}
			}
			return arrObj;
		},
		copyObj: function (obj) {
			var newObj = {};
			for (var attr in obj) {
				var type = typeof obj[attr];
				switch (type) {
					case 'number':
					case 'string':
						newObj[attr] = obj[attr];
						break;
					case 'object':
						newObj[attr] = this.isArray(obj[attr]) ? this.copyArr(obj[attr]) : this.copyObj(obj[attr]);
						break;
				}
			}
			return newObj;
		},
		isArray: function (obj) {
			if(Array.isArray) {
				return Array.isArray(obj);
			} else {
				return obj instanceof Array;
			}
		},
		upload:function(){
			$('input:file').change(function(){
				//dosomthing
				var that=$(this);
				var fd = new FormData();
				fd.append("upload", 1);
				fd.append("file", that.get(0).files[0]);
				$.ajax({
					//url: "http://test.app/test.php",//
					url: "/inquiry/upload",
					type: "POST",
					processData: false,
					contentType: false,
					data: fd,
					success: function(ret) {
						console.log(ret);
						if(ret&&ret.status){
							Vue.set( vueObj.info[zpFrame.imgUpTmp.index].option,zpFrame.imgUpTmp.key,zpFrame.imgUrl+ret.data);
						}else{
							alert(ret.msg);
						}

						//that.get(0).value='';

					}
				});
			});
		},
	};

	win.zpFrame=zpFrame;
	zpFrame.init();
}(window,jQuery);
//判断是否为空
function isEmpty(data) {
	if(isEmpty1(data) || isEmpty2(data)) {
		return true;
	}
	return false;
}

function isEmpty1(data) {
	if(data == undefined || data == null || data == "" || data == 'NULL' || data == false || data == 'false') {
		return true;
	}
	return false;
}

function isEmpty2(v) {
	switch (typeof v) {
		case 'undefined' :
			return true;
		case 'string' :
			if(v.trim().length == 0)
				return true;
			break;
		case 'boolean' :
			if(!v)
				return true;
			break;
		case 'number' :
			if(0 === v)
				return true;
			break;
		case 'object' :
			if(null === v)
				return true;
			if(undefined !== v.length && v.length == 0)
				return true;
			for (var k in v) {
				return false;
			}
			return true;
			break;
	}
	return false;
}