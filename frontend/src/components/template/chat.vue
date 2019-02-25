<template lang='jade'>
#wrap
    header
        .left(@click="goOrder()")
            i.icon-arrow-left
        .center#nick 某某某
    footer.foot_inp.fs.liaotiankuang
        textarea#sendText
        .btn.btn-jv#send 发送
    //再次聊天
    footer.foot_inp.aglinC.active.setFontSize.liaotian
       span.inputImg#inputImg(@click="appUpload")
          input(type="file" accept="image/png,image/jpg",@change="upload($event)" v-if="!$store.state.tcmuser")
          .icon
            i
       span.inputImg(@click="video" v-if="isVideo&&$store.state.tcmuser")
          .video
            i
       span.zw.none(@click="zw()") 追问
       span.fz.none(@click="fz()") 复诊
       span.jswz.none(@click="js()") 结束问诊
       span.jszw.none(@click="jszw()") 结束追问
    //复诊
    .btn.btn-fix.active
        .left(@click="fq()")
            span 放弃
        .right.buy(@click="pay()")  立即支付
    .tips.none
      .icon-tit
        i
      span 支付成功，开始咨询
    .swiper-container.swiper-container-list
        .chat_body.swiper-wrapper
          .swiper-slide
              .visible 下拉加载
              .chat_li(v-for="(item,index) in lists" v-bind:messagesId="item.messageId")
                  ///| {{item.messageId}}
                  .time(v-if="item.timeShow==1")  {{item.time}}
                  .time.js(v-if="item.from=='cen'") {{item.extra.text}}
                  .msg(v-else v-bind:class=" item.from ? 'self': '' ")
                      .avatar(v-if='item.img' v-bind:style="bg(item.img)")
                      .avatar(v-else v-bind:style="bg('/img/doctor_default.png')")
                      .imgBox(v-if="item.msg_type == 'image'")
                        //.img(v-bind:style="bg(item.extra.key)" @click="scare($event)")
                        img.imgShow(v-bind:src="item.extra.key" v-on:click="imgScare(item.extra.key)")
                      .cont.bor.link(v-show="item.msg_type=='card'",@click="showCard(index)")
                          h3
                              i.icon-help
                              span {{item.extra.text}}
                          p {{item.extra.con}}
                          p.pCor {{item.extra.recipe}}
                          p(v-if="item.extra.text=='处方'") {{item.extra.textW}}
                          h5
                              span 点击查看详情
                              i.icon-arrow-right
                          .cancel(v-if="item.extra.text=='处方' && item.extra.is_price == 8")
                              .cancelbg
                                 span 作废
                      .cont.bor(v-if="item.msg_type == 'text'")
                          p(v-html="item.extra.text")

                      .cont.bor.audioBox(v-if="item.msg_type == 'audio'")
                          .icon-rss(@click="play($event)")
                          audio.audio(v-bind:src="item.extra.voicePath | voice ")
                      span.second(v-if="item.msg_type=='audio'") {{item.extra.duration | duration }}
              .chat_li.min_h
                  .time.js.none 医生结束诊疗
                  .msg.warningTit.none
                      .cont.bor.warning
                          i.icon-warning
                          span#zpTips
                  .msg.warningFz.none
                        .cont.bor.warning
                            i.icon-warning
                            span 复诊需要重新下单支付
                            h5(@click="rePay")
                              span 点击查看详情
                              i.icon-arrow-right
    .layer_pop.none
        .content
            .txt 您确定结束本次在线咨询？结束后将无法继续本次在线咨询服务。
            .pop_btn.clearfix
                .p_btn.l(@click="confirm()") 确定
                .p_btn(@click="close()") 取消

    .pop.popimg.imgScare(v-on:click="closeImg()")
        img(:src="recipe_photo")

</template>
<script>
	export default {
		data(){
			return{
                isVideo:false,
				user:{
//                    id: 1223,
//                    name: '陈余良',
//                    avatar: ''
				},
				doctors:{
//                    id: 1223,
//                    name: '陈余良',
//                    avatar: 'http://tva3.sinaimg.cn/crop.0.0.199.199.50/83e058b1gw1f4er6ey1pij205k05kjrd.jpg'
				},
				lists:[
//                    {
//	                    messageId: 1,
//	                    from: '',   //消息  1：患者 2 医生 3系统
//	                    img: 'http://tva3.sinaimg.cn/crop.0.0.199.199.50/83e058b1gw1f4er6ey1pij205k05kjrd.jpg',
//	                    extra:{text:'测试'},
//	                    msg_type: 'text',
//	                    clinicId:1,
//	                    timeShow: 1,
//	                    time: '2017-12-08 10:10',
//                    }
//
				],
				swiper:"",
				statusOrder:true,
				recipe_photo: ''

			}
		},
		filters:{
			voice:function(value){
				//console.log('value:'+ JSON.stringify( value));
				return value? webim.voiceUrl+value: '';
			},
			duration:function(value){
				return value? value+'〃': '';
			},
		},
		mounted(){
			var token=localStorage.getItem('imToken');
            this.getChatStatus(this.$route.query.listId,this.$route.query.bespeakId);//获取聊天的状态
			var params={};
			var doctorName = this.$route.query.doctorName;
			console.log('location.hostname='+location.hostname)
            if(location.hostname == 'localhost'||location.hostname == 'tcm.app'){
                params.user={uid:'1',img:'http://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTICbm0lbbaHY5R05D0nk4LHp9tdkZfcty2ibicsCNJOUtGehYZ7RfibPHytIheicghCiaQsSwzp1sibpewA/0',};
                params.list_id=1;
                params.doctor={uid:'1',img:'https://lorempixel.com/640/480/?50410',};
                params.token='20171228151944047171';
            }else{
                if(!token || !this.$route.query.listId){
                    $api.pop('缺失参数 : token:'+token+' listId:'+this.$route.query.listId+'doctorName:'+this.$route.query.doctorName);
                    return false;
                }
                params.list_id=this.$route.query.listId;
                doctorName = this.$route.query.doctorName;
                params.token=token;
            }
			$('#nick').text(doctorName);
			console.log(doctorName+token);
			console.log('params='+JSON.stringify(params));
			var _self= this;
			zpChat.init(_self,params,function(){
				zpChat.initWs(function(type,result){
					switch (type){
						case "getChatUser":
							console.log('getChatUser:'+JSON.stringify(result));
							zpChat.user=result.user;
							zpChat.doctor=result.doctor;
							zpChat.clinicId=result.clinicId;
							var len=_self.lists.length;
							if(len==0){
								zpChat.getLatestMessages();
							}else{
								zpChat.getSyncMsg(_self);
							}

							break;
						case "syncMsg":
							//取页面中最新消息id
							zpChat.returnHtml(result,_self,'new',function(){
								zpChat.is_sync=false;
								zpChat.getSyncMsg(_self)
							});
							$('body').hasClass('loading') && $('body').removeClass('loading');
							setTimeout(function(){
								_self.swiper.update();
								var _viewHeight = _self.swiper.height;
								var _contentHeight = _self.swiper.virtualSize;
								_self.swiper.setWrapperTranslate(_viewHeight-_contentHeight);
							},400);
							break;
						case 'oneMsg':
							//更新未读消息
							zpChat.getSyncMsg(_self);
							break;
						case 'history':
							console.log('history:'+JSON.stringify(result));
							zpChat.isLoading=false;
							var len=_self.lists.length;
							var append="old";
							if(len==0){
								append='new';
							}
							zpChat.returnHtml(result,_self,append);
							if(append=='new'){
								setTimeout(function(){
									_self.swiper.update();
									var _viewHeight = _self.swiper.height;
									var _contentHeight = _self.swiper.virtualSize;
									_self.swiper.setWrapperTranslate(_viewHeight-_contentHeight);
								},400);
							}
							setTimeout(function(){
								oLoadTip.hide();
							},1000);
							$('body').hasClass('loading') && $('body').removeClass('loading');
							break;
						case 'showBtn':
							//网诊的状态：1：进行中，2：已结束:3：追问中，  门诊的状态：0：未开始:1：进行中，2：已结束
							//console.log('showBtn----'+JSON.stringify(result));
							var  show=result.show;
							zpChat.showBtnStatus=show;
							switch (parseInt(show)){
								case 1: zpChat.showBtn_1(); break; //结束问诊显示 聊天框显示
								case 2: zpChat.showBtn_2(); break;  //追问显示 复诊显示 聊天框隐藏
								case 3: zpChat.showBtn_3(show); break;   //复诊显示 聊天框显示
								case 5: zpChat.showBtn_3(show); break;   //复诊显示 聊天框隐藏
							}
							var  isSend=result.isSend;
							if(!isEmpty(isSend)){
								zpChat.sendTextMessage({text:'患者结束诊疗',type:3}, function (ret) {
									// console.log('患者结束诊疗:'+JSON.stringify(ret.result));
									//zpChat.returnHtml(ret.result,_self);

								});
								setTimeout(function(){
									_self.swiper.update();
									var _viewHeight = _self.swiper.height;
									var _contentHeight = _self.swiper.virtualSize;
									_self.swiper.setWrapperTranslate(_viewHeight-_contentHeight);
								},400);
							}
							break;
					}
				});

			});
			let self=this;
			$('footer.foot_inp textarea').on('focus',function(){
				setTimeout(function(){
					self.swiper.update();
					var _viewHeight = self.swiper.height;
					var _contentHeight = self.swiper.virtualSize;
					self.swiper.setWrapperTranslate(_viewHeight-_contentHeight);
                },400);
			});
            var ua = navigator.userAgent.toLowerCase();
            if (/iphone|ipad|ipod/.test(ua)) {
                $('footer.foot_inp textarea').on('focus',function(){
                    setTimeout(function(){
                        self.swiper.update();
                        var _viewHeight = self.swiper.height;
                        var _contentHeight = self.swiper.virtualSize;
                        self.swiper.setWrapperTranslate(_viewHeight-_contentHeight);
                        var h=$("body").height()-_viewHeight-$("header").height()-$(".aglinC").height()-$('.fs').height();
                        var h1=$("body").height()-_viewHeight-$("header").height()-$(".aglinC").height()/2;
                        $('.fs').css({'position':'absolute','bottom':h});
                        $('.aglinC').css({'position':'absolute','bottom':h1});
                    },400);
                });
                $('footer.foot_inp textarea').on('blur',function(){
                    $('.fs').css({'position':'fixed','bottom':0});
                    $('.aglinC').css({'position':'fixed','bottom':'0.85'+'rem'});

                });
            }
			window.addEventListener("resize", function () {
				if (document.activeElement.tagName == "INPUT" || document.activeElement.tagName == "TEXTAREA") {
					window.setTimeout(function () {
						document.activeElement.scrollIntoViewIfNeeded();
					}, 0);
				}
			});
			let oLoadTip = $('.chat_body').find('.visible');
			setTimeout(function(){
				self.swiper = new Swiper('.swiper-container-list', {
					direction: 'vertical',
					slidesPerView: 'auto',
					observer: true,
					observeParents: true,
					mousewheelControl: true,
					freeMode: true,
					resistanceRatio : 0.7,
					onTouchEnd: function(s){
						var _viewHeight = s.height;
						var _contentHeight = s.virtualSize;
						if(s.translate > 0) {
							oLoadTip.show();
							if(self.statusOrder){
								var webImOffline=zpChat.getStorage();
								if(!isEmpty(webImOffline)){
									$api.pop('网络信号不好，请稍后再试');
									return false;
								}
								$(".visible").html('正在加载...');
								$('.tips').hasClass('none') || $('.tips').addClass('none');
								var info=isEmpty(_self.lists[0])?{}:self.lists[0];
								if(!isEmpty(info.messageId)){
									zpChat.getHistoryMessages(info.messageId);
								}
								setTimeout(function(){
									s.update();
								},800);
							}
						}else if(s.translate == 0){
							oLoadTip.hide();
						}
					}
				});
			},400);
			window.addEventListener("popstate", function(e) {
				//alert("我监听到了浏览器的返回按钮事件啦");//根据自己的需求实现自己的功能
				$('textarea').blur();
			}, false);
			$('footer.foot_inp .btn').click(function(){
				if(!zpChat.isSendMsg){
					$api.pop(zpChat.isSendMsgTip);
					return false;
				}
				var txt = $('footer.foot_inp textarea').val();
				if($api.isEmpty(txt)){
					$api.pop('没有输入内容')
				}else{
					var webImOffline=zpChat.getStorage();
					if(!isEmpty(webImOffline)){
						$api.pop('网络信号不好，请稍后再试');
						return false;
					}
					zpChat.sendTextMessage({ text: txt}, function (ret) {
						$('footer.foot_inp textarea').val('');
					});
				}
			});
		},
		methods:{
		    video:function(){
                console.log('params='+JSON.stringify(zpChat));
                //LBBVideo.call('doctor71','lbbniu1')
                LBBVideo.openCall('doctor'+zpChat.doctor.uid,zpChat.doctor.name,zpChat.doctor.img);
            },
			scare:function(event){
				var _self=this;
				$(event.currentTarget).parent(".imgBox").toggleClass('active');
				//            if($(event.currentTarget).parent(".imgBox").hasClass('active')){
				//              $(".p_chat").addClass('active');
				//            }else{
				//              $(".p_chat").removeClass('active');
				//            }
			},
			rePay:function(){
				var self=this;
				self.$router.push({
					path:'/doctor/preOnline',
					query: { id: zpChat.toUser,subscribe_id:zpChat.subscribeId,subscribe_type:0}
				});
			},
			play:function(event){
				//var obj=$(obj)[0];
				var audio=$(event.currentTarget).parent(".cont").children('audio')[0];
				audio.play();

			},
			bg:function(url){
				if(url) return 'background-image:url('+url+')'
			},
			sT:function(i){
				//显示时间，间隔太近不显示
				var _self = this,ts = dateDiff(_self.lists[i-1].time,_self.lists[i].time);
				//console.log(ts);
				if(ts>0.01){
					return true
				}else{
					return false
				}
			},
			showCard:function (index) {

				console.log('card:'+JSON.stringify(this.lists[index]));
				var info=this.lists[index].extra;

				//判断药方是否作废
				if(info.text=='处方'&&0){
					return false
				}else{

					var clinic_id = this.lists[index].clinic_id;
					if(!isEmpty(info.cid)){
						var cType=info.cType;
						var param={};
						var path='';
						switch (parseInt(cType)){
							case 2:
								//标准问诊单详情 查看患者填写的预约信息（患者给医生推送预约的详情）
								//  tit='普通问诊单';
								param={ id: info.cid};
								path='/standard';
								break;
							case 1:  //医生给患者推送的个性问诊单 让患者进行填写
							case 4:  //患者给医生推送填写好的个性问诊单
								// tit="个性问诊单";
	                            //							if(info.option_type == 'radio'){
	                            //								path='/exam/exam_radio';
	                            //							}else if(info.option_type == 'check'){
	                            //								path='/exam/exam_check';
	                            //							}else if(info.option_type == 'photo'){
	                            //								path='/exam/exam_photo';
	                            //							}else{
	                            //								path='/exam/exam_fill';
	                            //							}
	                            path='/exam';
								param={id:info.id,clinic_id:info.clinic_id};
								break;
							case 3: //医生给患者开的处方
								// tit="处方";
								path='/prescription/my/id';
								param={id:info.cid};
								break;
						}
						//console.log(JSON.stringify(param));
	                    if(path=='/exam'){
	                      //this.$router.push({path:'/exam',query: {id:info.cid,listId:this.$route.query.listId,clinic_id:info.clinic_id}});
                            if(this.$store.state.tcmuser && true){
                                this.$router.push({path:'/exam',query:{id:info.cid,listId:this.$route.query.listId,clinic_id:info.clinic_id}});
                            }else{
                                window.location.href = '/wechat/exam?id='+info.cid + '&listId='+this.$route.query.listId + '&clinic_id=' + info.clinic_id;
                            }
	                    }else{
	                      this.$router.push({path:path,query: param});
	                    }

					}

				}

			},

			dateFormat:function (dd){
				return (new Date(dd.replace(/-/g, '/'))).Format("yyyy-MM-dd hh:mm:ss");
			},
			zw:function(){

				$('.jswz').addClass('none');

				$('.fz').removeClass('none');

				this.$http.get(this.$store.state.apiUrl+'message/statusBar/'+this.$route.query.listId+'?is_ask=1').then(function (res) {

		        })

				zpChat.zw();
				this.swiper.update();
		        this.$http.put(this.$store.state.apiUrl + 'message/ask/' + this.$route.query.listId).then(function (res) {
		          if(res.data.status){
		            $('.zw').addClass('none');
		            $('.foot_inp.fs').removeClass('none');
		            $('.inputImg').removeClass('none');
		            $('.jszw').removeClass('none');
		          }else{
		            $api.pop(res.data.msg);
		          }
		        })

			},
			fz:function(){
				zpChat.fz();
				this.swiper.update();
		        this.$router.push({
		          path:'/doctor/preOnline',
		          query: { id: zpChat.doctor.uid}
		        });
			},
			fq:function(){
				zpChat.cancelPay();
				this.swiper.update();
			},
			js:function(){

				$(".layer_pop").removeClass("none");

			},
			jszw(){

				$(".layer_pop").removeClass("none");

			},
			confirm:function(){

				if(!$('.jswz').hasClass('none')){

					var self=this;
			        zpChat.endSick();
			        self.swiper.update();
			        $(".layer_pop").addClass("none");
							//结束问诊接口
					this.$http({url:this.$store.state.apiUrl+'message/closeClinic/'+this.$route.query.listId,method:'put'}).then(function (res) {
			          	$api.pop(res.data.msg);
						if(res.data.status){
							zpChat.endSick();
							self.swiper.update();
							$(".layer_pop").addClass("none");
				            self.getChatStatus(self.$route.query.listId,self.$route.query.bespeakId);
				            self.$router.push({path: '/order'});
						}else{
							$api.pop(res.data.msg);
						}
					});

				}


				if(!$('.jszw').hasClass('none')){

					var self=this;
			        zpChat.endSick();
			        self.swiper.update();
			        $(".layer_pop").addClass("none");
							//结束问诊接口
					this.$http.post(this.$store.state.apiUrl+'message/endAsk',{id:this.$route.query.listId}).then(function (res) {
			          	$api.pop(res.data.msg);
						if(res.data.status){
							//zpChat.endSick();
							self.swiper.update();
							$(".layer_pop").addClass("none");
				            self.getChatStatus(self.$route.query.listId,self.$route.query.bespeakId);
				            self.$router.push({path: '/order'});
						}else{
							$api.pop(res.data.msg);
						}
					});

				}
				

			},
			close:function(){
				this.swiper.update();
				$(".layer_pop").addClass("none")
			},
			pay:function(){
				var self=this;
				this.$http({url:this.$store.state.apiUrl+'clinic/again',method:'GET',params:{id:zpChat.clinicId,type:'clinic'}}).then(function (res) {
					if(res.data.status){
						zpChat.showBtn_1();
						setTimeout(function(){
							//self.$router.push({path:'/payment',query: { id: res.data.data.order_sn,type:0 }});
                            if(this.$store.state.tcmuser && true){
                                this.$router.push({path:'/payment',query:{id:res.data.data.order_sn,type:0}});
                            }else{
                                window.location.href='/wechat/payment/?id='+res.data.data.order_sn+"&type=0";
                            }
						},2000);
					}else{
						$api.pop(res.data.msg);
					}
				});

			},
			goOrder:function () {
				this.$router.push({ path: '/order'});
			},
			imgScare:function(photo){
				this.recipe_photo = photo;
				$('.imgScare').show();
			},
			closeImg:function(){
				$('.imgScare').hide();
			},
            appUpload(){
		        if(!this.$store.state.tcmuser){
		            return;
                }
                var webImOffline=zpChat.getStorage();
                if(!isEmpty(webImOffline)){
                    $api.pop('网络信号不好，请稍后再试');
                    return false;
                }
                if(!zpChat.isSendMsg){
                    $api.pop(zpChat.isSendMsgTip);
                    return false;
                }
                var _self=this;
                AppUpload(function (data) {
                    if(data&&data.status){
                        zpChat.sendImgMessage({ key: data.data.image_thumb_url,hash: data.data.image_url}, function (ret) {
                            setTimeout(function(){
                                _self.swiper.update();
                                var _viewHeight = _self.swiper.height;
                                var _contentHeight = _self.swiper.virtualSize;
                                _self.swiper.setWrapperTranslate(_viewHeight-_contentHeight);
                            },400);
                        });
                    }else{
                        $api.pop('上传失败');
                    }
                },'upload/qiniu');

            },
			upload:function(e){
				var webImOffline=zpChat.getStorage();
				if(!isEmpty(webImOffline)){
					$api.pop('网络信号不好，请稍后再试');
					return false;
				}
				if(!zpChat.isSendMsg){
					$api.pop(zpChat.isSendMsgTip);
					return false;
				}
				$('body').addClass('loading');
				var that=e.target;
				console.log(that)
				var fd = new FormData();
				fd.append("upload", 1);
				fd.append("image", that.files[0]);

				var _self=this;
				this.$http.post(this.$store.state.apiUrl+'upload/qiniu', fd).then(function(msg) {
					console.log(msg)
					//console.log(JSON.stringify(msg)+'');
					//console.log('status:'+msg.status+'data:'+JSON.stringify(msg.data));
					if(msg.status){
						var data=msg.data;
						console.log(data)
						//console.log('file:'+data.data.file+'data:'+JSON.stringify(data));
						if(data.status){
							zpChat.sendImgMessage({ key: data.data.image_thumb_url,hash: data.data.image_url}, function (ret) {
								//					            //客户端发送
								//					            zpChat.returnHtml(ret.result,_self);
								//					            //追加消息后列表自滚动
								//					            pageDown(200);
			               setTimeout(function(){
			                 _self.swiper.update();
			                 var _viewHeight = _self.swiper.height;
			                 var _contentHeight = _self.swiper.virtualSize;
			                 _self.swiper.setWrapperTranslate(_viewHeight-_contentHeight);
			               },400);
							});
						}else{
							alert(data.msg);
						}
						$('body').removeClass('loading');
					}
				});
			},
      getChatStatus(listId,bespeakId){
          this.$http.get(this.$store.state.apiUrl+'message/statusBar/'+listId+'?is_ask=0&bespeakId='+bespeakId).then(function (res) {
              if(res.data.data.clinic){

              	  $('.jswz').removeClass('none'); //结束问诊按钮显示

              }else{

                  $('.jswz').addClass('none');

              }
              if(res.data.data.ask == 1){

                  $('.zw').removeClass('none');//追问显示聊天框隐藏

              }else{

              	  $('.zw').addClass('none');

              }
              if(res.data.data.end == 1){

              	$('.jszw').addClass('none');

              }else if(res.data.data.end == 2){

              	$('.jszw').removeClass('none');

              }

              //图片按钮显示

              if(res.data.data.clinic || !res.data.data.ask && res.data.data.end == 2){

                  $('#inputImg').removeClass('none');
                  if(res.data.data.isVideo){
                        this.isVideo = true;
                  }
              }else if(!res.data.data.clinic && !res.data.data.ask && res.data.data.end == 1){

                  $('#inputImg').addClass('none');
                  this.isVideo = false;

              }else if(res.data.data.ask && !res.data.data.clinic){

              	  $('#inputImg').addClass('none');
                  this.isVideo = false;

              }

              if(res.data.data.visit){
                  $('.fz').removeClass('none');//复诊显示 聊天框显示
              }else{
                  $('.fz').addClass('none');
              }
              if(res.data.data.keyboard){
                  $('.foot_inp.fs').removeClass('none');
              }else{
                  $('.foot_inp.fs').addClass('none');
              }
          },function (response) {
              errorMsg(response.data.data.errors);
          })
      }
		}
	};
</script>

