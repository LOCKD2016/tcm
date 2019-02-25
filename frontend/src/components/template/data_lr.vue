<template lang='jade'>
.fixbody
  header
    .left(@click='back')
      i.icon-arrow-left
    .center(v-if="type==0") 健康数据
    .center(v-if="type==1") 手动补录
    .right(@click="cl(titType)") 保存

  //血压

  #wrap.lrBox(v-if="titType==0")

      .container(style="width:100%;height:100%;position:fixed;bottom:0;z-index=999")

      ul.list-group
          li
                a 日期
                .val
                    input.chooseDate(type='datetime-local' placeholder='请选择日期' v-model='pressure.date')

      ul.list-group.data_lr
          li
              a 收缩压
              .val
                  input.systolic(type="text" placeholder="" @click="setData('.systolic')")

                  span mmHg

          li
              a 舒张压
              .val
                  input.diastolic(type="text" @click="setData('.diastolic')")
                  span mmHg
          li
              a 心率
              .val
                  input.heart_rate(type="text" @click="setData('.heart_rate')")
                  span bpm
  //血糖
  #wrap.lrBox(v-if="titType==1")
      ul.list-group
          li
            a 时间
            .val
                input.chooseDate(type='datetime-local' placeholder='请选择时间' v-model="sugar.date")
      ul.list-group.data_lr
          li(@click="zt()")
              a 状态
              .val
                  input(type="text" placeholder="" readonly v-model="status")
              i.icon-arrow-right
          li
              a 血糖值
              .val
                  input.sugar_val(type="text" placeholder="" v-model="sugar.sugar_val" @click="setData('.sugar_val')")
                  span mmol/L
  //血氧
  #wrap.lrBox(v-if="titType==2")
      ul.list-group.data_lr
          li
              a 时间
              .val
                input.chooseDate(type='datetime-local' placeholder='请选择时间' v-model="oxygen.date")

          li
              a 血氧值
              .val
                  input.oxygen_val(type="text" placeholder="" v-model="oxygen.oxygen_val" @click="setData('.oxygen_val')")
                  span %
  //体温
  #wrap.lrBox(v-if="titType==3")
      ul.list-group.data_lr
          li
              a 时间
              .val
                input.chooseDate(type='datetime-local' placeholder='请选择时间' v-model="temperature.date")

          li
              a 体温值
              .val
                  input.temp_val(type="text" placeholder="" v-model="temperature.temp_val" @click="setData('.temp_val')")
                  span ℃
  //体质
  #wrap.lrBox(v-if="titType==4")
      ul.list-group
          li
              a 时间
              .val
                input.chooseDate(type='datetime-local' placeholder='请选择时间' v-model="constitution.date")
      ul.list-group.data_lr
          li
              a 身高
              .val
                  input.height(type="text" placeholder="" v-model="constitution.height" @click="setData('.height')")
                  span cm
          li
              a 体重
              .val
                  input.weight(type="text" placeholder="" v-model="constitution.weight" @click="setData('.weight')")
                  span kg
          li
              a BMI
              .val
                  p.BMI
  //选择城市
  .cityBox.none
      header
        .left(@click="backTo()")
            i.icon-arrow-left
        .center 状态
      ul
          li(:class="{active: before_meal == 1}" @click="setMealTime(1)") 餐前
              i.icon-check
          li(:class="{active: after_meal == 1}" @click="setMealTime(2)") 餐后
              i.icon-check

  //提示信息

  .layer_pop.none
      .content
          .txt {{ msg }}
          .pop_btn.clearfix
              .p_btn.l(@click="dodel()" style='width:100%') 确定
</template>
<script>

      export default {

            data() {
              return{
                  pressure: {}, //血压
                  sugar: {}, //血糖
                  oxygen: {}, //血氧
                  temperature: {}, //体温
                  constitution: {}, //体质
                  before_meal: 0, //饭前
                  after_meal: 0,  //饭后
                  status: '',
                  msg:''          //提示信息
              }
            },

            created:function () {
              this.type = this.$route.query.type;//0数据录入，1手动补录
              this.titType = this.$route.query.titType;//0血压，1血糖，2血氧，3体温，4体质

            },

            mounted(){

              this.getFormat();

            },

            methods:{

            //返回健康数据

            back(){

              this.$router.push('/data/1/0')

            },

            //格式化时间

            getFormat(){
              var format = "";
              var nTime = new Date();
              format += nTime.getFullYear()+"-";
              format += (nTime.getMonth()+1)<10?"0"+(nTime.getMonth()+1):(nTime.getMonth()+1);
              format += "-";
              format += nTime.getDate()<10?"0"+(nTime.getDate()):(nTime.getDate());
              format += "T";
              format += nTime.getHours()<10?"0"+(nTime.getHours()):(nTime.getHours());
              format += ":";
              format += nTime.getMinutes()<10?"0"+(nTime.getMinutes()):(nTime.getMinutes());
              format += ":00";

              document.getElementsByClassName("chooseDate")[0].value = format;

              this.pressure.date=this.sugar.date=this.oxygen.date=this.temperature.date=this.constitution.date=format;

            },

            //确定按钮事件

            dodel(){

              $('.layer_pop').addClass('none');

            },

            //健康数据数值录入

            setData(e){
                var self=this;
                $(e).blur();
                var data1=[{'id': '100', 'value': '0'},{'id': '101', 'value': '1'},{'id': '102', 'value': '2'},{'id': '103', 'value': '3'},{'id': '104', 'value': '4'},{'id': '105', 'value': '5'},{'id': '106', 'value': '6'},{'id': '107', 'value': '7'},{'id': '108', 'value': '8'},{'id': '109', 'value': '9'}];
                var data2=[{'id': '200', 'value': '0'},{'id': '201', 'value': '1'},{'id': '202', 'value': '2'},{'id': '203', 'value': '3'},{'id': '204', 'value': '4'},{'id': '205', 'value': '5'},{'id': '206', 'value': '6'},{'id': '207', 'value': '7'},{'id': '208', 'value': '8'},{'id': '209', 'value': '9'}];
                var data3=[{'id': '300', 'value': '0'},{'id': '301', 'value': '1'},{'id': '302', 'value': '2'},{'id': '303', 'value': '3'},{'id': '304', 'value': '4'},{'id': '305', 'value': '5'},{'id': '306', 'value': '6'},{'id': '307', 'value': '7'},{'id': '308', 'value': '8'},{'id': '309', 'value': '9'}];
                var showDom = document.querySelector(e);// 绑定一个触发元素
                var valDom = document.querySelector(e);  // 绑定一个存储结果的元素
                var oneId = showDom.dataset['one_id'];
                var oneValue = showDom.dataset['one_value'];
                var twoId = showDom.dataset['two_id'];
                var twoValue = showDom.dataset['two_value'];
                var threeId = showDom.dataset['three_id'];
                var threeValue = showDom.dataset['three_value'];
                //var title = showDom.dataset['value'];        // 获取元素的data-value属性值
                // 实例化组件

                var example = new IosSelect(3,               // 第一个参数为级联层级，演示为1
                    [data1,data2,data3],                             // 演示数据
                    {
                        container: '.p_data_lr',             // 容器class
                        title: '健康数据',                    // 标题
                        itemHeight: 40,                      // 每个元素的高度
                        itemShowCount: 3,                    // 每一列显示元素个数，超出将隐藏
                        oneLevelId: oneId,                    // 第一级默认值
                        twoLevelId: twoId,
                        threeLevelId: threeId,
                        callback: function (selectOneObj,selectTwoObj,selectThreeObj) {  // 用户确认选择后的回调函数

                          var value

                          if(e==".sugar_val"||e==".temp_val"){

                            value=parseInt(selectOneObj.value+selectTwoObj.value+selectThreeObj.value)/10;

                            value=value.toFixed(1)

                          }else{

                            value=parseInt(selectOneObj.value+selectTwoObj.value+selectThreeObj.value)+'';

                          }

                            showDom.value=value;

                            console.log(value)

                            showDom.dataset['one_id'] = selectOneObj.id;
                            showDom.dataset['two_id'] = selectTwoObj.id;
                            showDom.dataset['three_id'] = selectThreeObj.id;
                            showDom.dataset['one_value'] = selectOneObj.value;
                            showDom.dataset['two_value'] = selectTwoObj.value;
                            showDom.dataset['three_value'] = selectThreeObj.value;

                            if(e==".systolic"){
                                self.pressure.systolic=value;
                            }else if(e==".diastolic"){
                                self.pressure.diastolic=value;
                            }else if(e==".heart_rate"){
                                self.pressure.heart_rate=value;
                            }else if(e==".sugar_val"){
                                self.sugar.sugar_val=value;
                            }else if(e==".oxygen_val"){
                                self.oxygen.oxygen_val=value;
                            }else if(e==".temp_val"){
                                self.temperature.temp_val=value;
                            }else if(e==".height"){
                                self.constitution.height=value;
                            }else if(e==".weight"){
                                self.constitution.weight=value;
                            }

                            if(self.constitution.weight&&self.constitution.height){

                              var weight=parseInt(self.constitution.weight);
                              var height=parseInt(self.constitution.height);

                              self.constitution.BMI=((weight/(height*height))*10000).toFixed(1)

                              $(".BMI").html(self.constitution.BMI)

                            }
                        }
                    });

                //录入小数点提示信息

                if(this.titType==1||this.titType==3){

                  var tips='<p class="tip">此为小数位</p>';

                  $(tips).appendTo('.layer');

                }
            },
          cl:function(titType){
              //血压
              if(titType == 0){
                  if(this.pressure.systolic&&this.pressure.diastolic&&this.pressure.heart_rate&&this.pressure.date){

                      if(this.pressure.systolic-0<this.pressure.diastolic-0){

                        this.msg='收缩压应大于舒张压！'

                        $('.layer_pop').removeClass('none');

                      }else{

                        var sobj = {};
                        sobj.date = this.pressure.date.replace("T"," ");
                        sobj.type = 1;
                        sobj.content = JSON.stringify({ systolic : this.pressure.systolic,diastolic : this.pressure.diastolic,heart_rate : this.pressure.heart_rate});
                        console.log(sobj)

                        this.$http.post(this.$store.state.apiUrl+'health/save',sobj).then(function (res) {
                          console.log(res)
                              if(res.data.status == 0){
                                  $api.pop(res.data.msg);
                              }else{
                                  this.$router.push({path:'/data_cl',query: { titType:titType}});
                              }
                        })

                      }

                  }else{

                    if(!this.pressure.date){
                      this.msg='请输入时间'
                    }
                    else if(!this.pressure.systolic){
                      this.msg='请输入收缩压数值'
                    }else if(!this.pressure.diastolic){
                      this.msg='请输入舒张压数值'
                    }else if(!this.pressure.heart_rate){
                      this.msg='请输入心率数值'
                    }

                    $('.layer_pop').removeClass('none');

                  }
              }


              // 血糖
              if(titType == 1){
                  if(this.sugar.sugar_val&&this.sugar.date&&this.status){
                      var aobj = {};
                      aobj.date = this.sugar.date.replace("T"," ");
                      aobj.type = 2;
                      aobj.content = JSON.stringify({ status : this.status,sugar_val : this.sugar.sugar_val});
                      this.$http.post(this.$store.state.apiUrl+'health/save',aobj).then(function (res) {
                          if(res.data.status == 0){
                              $api.pop(res.data.msg);
                          }else{
                              this.$router.push({path:'/data_cl',query: { titType:titType}});
                          }
                      })
                  }else{

                    if(!this.sugar.date){
                      this.msg='请输入时间'
                    }
                    else if(!this.status){
                      this.msg='请输入状态'
                    }
                    else if(!this.sugar.sugar_val){
                      this.msg='请输入血糖数值'
                    }

                    $('.layer_pop').removeClass('none');

                  }
              }

              // 血氧
              if(titType == 2){
                  if(this.oxygen.oxygen_val&&this.oxygen.date){
                      var bobj = {};
                      bobj.date = this.oxygen.date.replace("T"," ");
                      bobj.type = 3;
                      bobj.content = JSON.stringify({ oxygen_val : this.oxygen.oxygen_val});
                      this.$http.post(this.$store.state.apiUrl+'health/save',bobj).then(function (res) {
                          if(res.data.status == 0){
                              $api.pop(res.data.msg);
                          }else{
                              this.$router.push({path:'/data_cl',query: { titType:titType}});
                          }
                      })
                  }else{

                    if(!this.oxygen.date){
                      this.msg='请输入时间'
                    }
                    else if(!this.oxygen.oxygen_val){
                      this.msg='请输入血氧数值'
                    }

                    $('.layer_pop').removeClass('none');

                  }
              }


              // 体温
              if(titType == 3){
                  if(this.temperature.temp_val&&this.temperature.date){
                      var cobj = {};
                      cobj.date = this.temperature.date.replace("T"," ");
                      cobj.type = 4;
                      cobj.content = JSON.stringify({ temp_val : this.temperature.temp_val});
                      this.$http.post(this.$store.state.apiUrl+'health/save',cobj).then(function (res) {
                          if(res.data.status == 0){
                              $api.pop(res.data.msg);
                          }else{
                              this.$router.push({path:'/data_cl',query: { titType:titType}});
                          }
                      })
                  }else{

                    if(!this.temperature.date){
                      this.msg='请输入时间'
                    }
                    else if(!this.temperature.temp_val){
                      this.msg='请输入体温数值'
                    }

                    $('.layer_pop').removeClass('none');

                  }
              }

              // 体质
              if(titType == 4){
                  if(this.constitution.height&&this.constitution.weight&&this.constitution.date){
                      var dobj = {};
                      dobj.date = this.constitution.date.replace("T"," ");
                      dobj.type = 5;
                      dobj.content = JSON.stringify({ height : this.constitution.height,weight : this.constitution.weight,BMI : this.constitution.BMI});
                      this.$http.post(this.$store.state.apiUrl+'health/save',dobj).then(function (res) {
                          if(res.data.status == 0){
                              $api.pop(res.data.msg);
                          }else{
                              this.$router.push({path:'/data_cl',query: { titType:titType}});
                          }
                      })
                  }else{

                    if(!this.constitution.date){
                      this.msg='请输入时间'
                    }
                    else if(!this.constitution.height){
                      this.msg='请输入身高数值'
                    }else if(!this.constitution.weight){
                      this.msg='请输入体重数值'
                    }

                    $('.layer_pop').removeClass('none');

                  }
              }

          },
          backTo(){
            $('.cityBox').addClass('none');
            $('.lrBox').removeClass('none');
          },
          zt(){
            $('.cityBox').removeClass('none');
            $('.lrBox').addClass('none');
          },
          setMealTime(type){
            if(type == 1){
                this.before_meal = 1;
                this.after_meal = 0;
                this.status = '餐前';
            }else{
                this.before_meal = 0;
                this.after_meal = 1;
                this.status = '餐后';
            }

            $('.cityBox').addClass('none');
            $('.lrBox').removeClass('none');

            //this.getFormat();

          }
        }
      };
</script>
