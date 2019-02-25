<template lang='jade'>
  .fixbody
    header
      .left(onclick="back()")
        i.icon-arrow-left
      .center 健康数据
      .right(@click="qh()") 图表切换
    #wrap
      -var nt=['血压', '血糖','血氧','体温','体质']
      .tab_nv
        each mt,i in nt
          p(v-bind:class="[titType==#{i+1} ? 'active' : '']" @click="checkTit(#{i})")
            span=mt
      .date(v-if='dateChoose')
        .timebox
          p.start(@click="dateDIY('.start')") {{ startDate }}
          p.end(@click="dateDIY('.end')") {{ endDate }}
          .icon-arrow-right-b
        .select(@click="getDIYData") 查  询


      //血压
      .sjBox(v-if="titType==1")
          -var nl=['时间', '收缩压','舒张压','心率']
          .list_tit
              each nv,i in nl
                p=nv
          .table
            .tr(v-for='item in healthData')
              .td.active {{ item.time }}
              .td
                span {{ item.systolic }}  mmHg
              .td
                span {{ item.diastolic }}  mmHg
              .td
                span {{ item.heart_rate }}  bpm
      //血糖
      .sjBox.sjBox2(v-if="titType==2")
          -var nl=['时间', '餐前血糖值','餐后血糖值']
          .list_tit
              each nv,i in nl
                  p=nv
          .table
            .tr(v-for='item in healthData')
              .td.active {{ item.time }}
              .td
                span {{ item.sugar_before }}
                span(v-if='item.sugar_before!="无"')  mmol/L
              .td
                span {{ item.sugar_after }}
                span(v-if='item.sugar_after!="无"')  mmol/L
      //血氧
      .sjBox.sjBox3(v-if="titType==3")
          -var nl=['时间','血氧值']
            .list_tit
                each nv,i in nl
                  p=nv
          .table
            .tr(v-for='item in healthData')
              .td.active {{ item.time }}
              .td
                span {{ item.oxygen }}  %
      //体温
      .sjBox.sjBox3(v-if="titType==4")
          -var nl=['时间','体温']
            .list_tit
              each nv,i in nl
                p=nv
          .table
            .tr(v-for='item in healthData')
              .td.active {{ item.time }}
              .td
                span {{ item.temp }}  ℃
      //体质
      .sjBox(v-if="titType==5")
          -var nl=['时间', '身高','体重','BMI']
              .list_tit
                  each nv,i in nl
                     p=nv
          .table
            .tr(v-for='item in healthData')
              .td.active {{ item.time }}
              .td
                span {{ item.height }}  cm
              .td
                span {{ item.weight }}  kg
              .td
                span {{ item.BMI }}

</template>
<script>
  export default {
      data() {
        return{
          titType:1,           //1血压，2血糖，3血氧，4体温，5体质
          timeType:0,          //时间
          todayDate:"",
          startDate:"",
          endDate:"",
          data:[],
          dateChoose:false,
          healthData:[]
        }
      },

      created(){

        if(this.$route.params.startDate){

          this.startDate=this.$route.params.startDate;

          this.endDate=this.$route.params.endDate;

        }else{

          this.startDate=this.endDate=this.today=new Date().toLocaleDateString().replace(/\//g,"-")

        }

        this.titType=this.$route.params.titType;

        this.timeType=this.$route.params.timeType;

        this.startDate=this.$route.params.startDate;

        this.endDate=this.$route.params.endDate;

        if(this.timeType==3){

          this.dateChoose=true

        }

      },

      mounted(){

        if(this.timeType==3){

          this.getDIYData();

        }else{

          this.getHealthData();

        }

      },

      methods:{

        qh:function(){

          if(this.timeType==3){

            this.$router.push({ name:"健康数据",params:{titType:this.titType,timeType:this.timeType,startDate:this.startDate,endDate:this.endDate} });

          }else{

            this.$router.push({ name:"健康数据",params:{ titType:this.titType,timeType:this.timeType }});

          }

        },
        checkTit:function(i){

          this.titType=i+1;

          if(this.timeType==3){

            this.getDIYData();

          }else{

            this.getHealthData();

          }
        },

        //获取自定义日期数据

        getDIYData(){

          this.$http
            .get(this.$store.state.apiUrl + "health/data/" + this.titType,{
                params:{
                    startDate:this.startDate,
                    endDate:this.endDate
                }
              })

            .then(function(res) {

            console.log(res)

            this.custom_data=res.data.data.custom_data;

            this.setHealthData()

            });
        },

        //获取健康数据

        getHealthData(){
          this.$http
            .get(this.$store.state.apiUrl + "health/data/" + this.titType)
            .then(function(res) {
              console.log(res)
              this.dataResult=res;
              this.dayData=res.data.data.day_data;
              this.weekData=res.data.data.week_data;
              this.monthData=res.data.data.month_data;
              this.setHealthData()
            });
        },

        //处理健康数据

        setHealthData(){

          if(this.titType==1){

            this.setDate('systolic','diastolic','heart_rate')

          }else if(this.titType==2){

            this.setDate('sugar_before','sugar_after')

          }else if(this.titType==3){

            this.setDate('oxygen')

          }else if(this.titType==4){

            this.setDate('temp')

          }else if(this.titType==5){

            this.setDate('height','weight','BMI')

          }
        },

        //数据处理

        setData(data,time){

          this.healthData=[];

          var time=data[time];

          for(var i = 0;i < time.length;i++){

            var obj={};

            obj.time=time[i];

            for(var k = 2;k < arguments.length;k++){

              if(arguments[k]){

                //处理血糖空值

                var sugar_before=[];

                var sugar_after=[];

                if(this.timeType==0){

                  if(this.titType==2){

                    var shijian=[];

                    for(var t = 0;t < data['sugar_before'].length;t++){

                      shijian.push(data['sugar_before'][t][0]);

                    }

                    console.log(shijian)

                    for(var e = 0;e < data['sugar_after'].length;e++){

                      shijian.push(data['sugar_after'][e][0]);

                    }

                    //时间排序

                    for(var x=0;x<shijian.length-1;x++){
                      for(var y=x+1;y<shijian.length;y++){
                        if(shijian[x]>shijian[y]){
                          var temp=shijian[x];
                          shijian[x]=shijian[y];
                          shijian[y]=temp;
                        }
                      }
                    }

                    for(var n = 0;n < shijian.length;n++){

                      var idx1 = '';

                      var idx2 = '';

                      //餐前

                      for(var b = 0;b < data['sugar_before'].length;b++){

                        if(data['sugar_before'][b][0]==shijian[n]){

                          idx1=b;

                          break;

                        }

                      }

                      if(idx1!==''){

                        sugar_before.push(data['sugar_before'][idx1][1])

                      }else{

                        sugar_before.push('无')

                      }

                      //餐后

                      for(var a = 0;a < data['sugar_after'].length;a++){

                        if(data['sugar_after'][a][0]==shijian[n]){

                          idx2=a;

                          break;

                        }

                      }

                      if(idx2!==''){

                        sugar_after.push(data['sugar_after'][idx2][1])

                      }else{

                        sugar_after.push('无')

                      }

                    }

                    sugar_before.reverse();

                    sugar_after.reverse();

                  }else{

                    var rightData=[];

                    for(var n = 0;n < data[arguments[k]].length;n++){

                      rightData.push(data[arguments[k]][n][1]);

                    }

                  }


                  console.log(sugar_before)

                }else{

                  if(this.titType==2){

                    for(var m = 0;m < data['sugar_before'][1].length;m++){

                      if(data['sugar_before'][1][m]!=''||data['sugar_after'][1][m]!=''){

                        if(arguments[k]=='sugar_before'){

                          if(!data['sugar_before'][1][m]){

                            data['sugar_before'][1][m]=='无'

                            sugar_before.push('无')

                          }else{

                            sugar_before.push(data['sugar_before'][1][m])

                          }

                        }else{

                          if(!data['sugar_after'][1][m]){

                            data['sugar_after'][1][m]=='无'

                            sugar_after.push('无')

                          }else{

                            sugar_after.push(data['sugar_after'][1][m])

                          }

                        }

                      }

                    }

                    sugar_before.reverse();

                    sugar_after.reverse();

                  }else{

                    var rightData=[];

                    for(var j = 0;j < data[arguments[k]][1].length;j++){

                      if(data[arguments[k]][1][j]!=""){

                        rightData.push(data[arguments[k]][1][j])

                      }

                    }

                    rightData.reverse();

                  }

                }

                if(this.timeType==0){

                  if(this.titType==2){

                    if(arguments[k]=='sugar_before'){

                      obj['sugar_before']=sugar_before[i];

                    }else{

                      obj['sugar_after']=sugar_after[i];

                    }

                  }else{

                    obj[arguments[k]]=data[arguments[k]][i][1];

                  }

                }else{

                  if(this.titType==2){

                    if(arguments[k]=='sugar_before'){

                      obj['sugar_before']=sugar_before[i];

                    }else{

                      obj['sugar_after']=sugar_after[i];

                    }

                  }else{

                    obj[arguments[k]]=rightData[i];

                  }

                }

              }

            }

            this.healthData.push(obj)

            console.log(this.healthData)

          }

           //this.healthData.reverse();

        },

        setDate(){

          if(this.timeType==0){

            this.setData(this.dayData,'used_days',arguments[0],arguments[1],arguments[2])

          }else if(this.timeType==1){

            this.setData(this.weekData,'used_days',arguments[0],arguments[1],arguments[2])

          }else if(this.timeType==2){

            this.setData(this.monthData,'used_days',arguments[0],arguments[1],arguments[2])

          }else if(this.timeType==3){

            this.setData(this.custom_data,'used_days',arguments[0],arguments[1],arguments[2])

          }

        },

        //自定义日期

        dateDIY(e){
            var self=this;

            var selectDateDom = $(e);
            var showDateDom = $(e);
            // 初始化时间
            var now = new Date();
            var nowYear = now.getFullYear();
            var nowMonth = now.getMonth() + 1;
            var nowDate = now.getDate();
            showDateDom.attr('data-year', nowYear);
            showDateDom.attr('data-month', nowMonth);
            showDateDom.attr('data-date', nowDate);
            // 数据初始化
            function formatYear (nowYear) {
                var arr = [];
                for (var i = nowYear - 5; i <= nowYear + 5; i++) {
                    arr.push({
                        id: i + '',
                        value: i + ''
                    });
                }
                return arr;
            }
            function formatMonth () {
                var arr = [];
                for (var i = 1; i <= 12; i++) {
                    arr.push({
                        id: i + '',
                        value: i + ''
                    });
                }
                return arr;
            }
            function formatDate (count) {
                var arr = [];
                for (var i = 1; i <= count; i++) {
                    arr.push({
                        id: i + '',
                        value: i + ''
                    });
                }
                return arr;
            }
            var yearData = function(callback) {

                    callback(formatYear(nowYear))

            }
            var monthData = function (year, callback) {

                    callback(formatMonth());

            };
            var dateData = function (year, month, callback) {

                    if (/^(1|3|5|7|8|10|12)$/.test(month)) {
                        callback(formatDate(31));
                    }
                    else if (/^(4|6|9|11)$/.test(month)) {
                        callback(formatDate(30));
                    }
                    else if (/^2$/.test(month)) {
                        if (year % 4 === 0 && year % 100 !==0 || year % 400 === 0) {
                            callback(formatDate(29));
                        }
                        else {
                            callback(formatDate(28));
                        }
                    }
                    else {
                        throw new Error('month is illegal');
                    }
            };

            var oneLevelId = showDateDom.attr('data-year');
            var twoLevelId = showDateDom.attr('data-month');
            var threeLevelId = showDateDom.attr('data-date');
            var iosSelect = new IosSelect(3,
                [yearData, monthData, dateData],
                {
                    title: '日期选择',
                    itemHeight: 35,
                    oneLevelId: oneLevelId,
                    twoLevelId: twoLevelId,
                    threeLevelId: threeLevelId,
                    showLoading: true,
                    callback: function (selectOneObj, selectTwoObj, selectThreeObj) {
                        showDateDom.attr('data-year', selectOneObj.id);
                        showDateDom.attr('data-month', selectTwoObj.id);
                        showDateDom.attr('data-date', selectThreeObj.id);
                        showDateDom.html(selectOneObj.value + '-' + selectTwoObj.value + '-' + selectThreeObj.value);

                        if(e=='.start'){

                            self.startDate=selectOneObj.value + '-' + selectTwoObj.value + '-' + selectThreeObj.value

                        }else if(e=='.end'){

                            self.endDate=selectOneObj.value + '-' + selectTwoObj.value + '-' + selectThreeObj.value

                        }
                    }
            });
        },

        //确定按钮事件

        dodel(){
          $('.layer_pop').addClass('none');
          $('.start').html(this.today)
          $('.end').html(this.today)
        }
  }
};
</script>
