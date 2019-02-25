<template lang='jade'>
.fixbody

  header

    .left(@click="back")
        i.icon-arrow-left
    .center 健康数据
    .right(@click="qh()") 图表切换

  #wrap

    -var nt=['血压', '血糖','血氧','体温','体质']
    .tab_nv
      each mt,i in nt
        p(v-bind:class="[titType==#{i+1} ? 'active' : '']" @click="checkTit(#{i})")
          span=mt

    //自定义范围

    .date
      .timebox(v-if="timeType==3")
          p.start(@click="dateDIY('.start')") {{ startDate }}
          p.end(@click="dateDIY('.end')") {{ endDate }}
          .icon-arrow-right-b
      .select(@click="getDIYData") 查  询

    #chart

    .lr_btn
        span(v-if="timeType==0" @click="lr(0,titType)") 录入数据
        span(v-else @click="lr(1,titType)") 数据补录

    -var nv=["今天","近一周","近一月",'自定义范围']
    .tabs
        each mv,i in nv
          p(v-bind:class="[timeType==#{i} ? 'active' : '']" @click="checkTime(#{i})")=mv

  .layer_pop.none
      .content
          .txt
          .pop_btn.clearfix
              .p_btn.l(@click="dodel()" style='width:100%') 确定


</template>
<script>
  export default {
  data() {
    return {
      titType: 1,             //1血压，2血糖，3血氧，4体温，5体质
      timeType: 0,            //0今天,1近一周,2近一月,3自定义范围
      todayDate:"",
      startDate:"",
      endDate:"",
      option: {},
      healthData:[],
      myChart:{},
      dataResult:{},          //健康数据
      dayData:{},
      weekData:{},
      monthData:{},
      custom_data:{},
      week:[],
      month:[],
      custom_date:[],
      systolic:[],            //收缩压
      diastolic:[],           //舒张压
      heart_rate:[],          //心率
      sugar_before:[],        //餐前血糖
      sugar_after:[],         //餐后血糖
      oxygen:[],              //血氧
      temp:[],                //体温
      BMI:[],                 //BMI
      legend:[],
      dataType:[],
      eChartsData:[],
      y:{},
      end:0,
      color:['#42b689','#c75757','#80c98a'],    //点的颜色
      dataTypeName:[['收缩压','舒张压','心率'],['餐前','餐后'],['血氧'],['体温'],['BMI']],

      //图表默认配置

      option:{
        backgroundColor: "#fff",
        tooltip: {
          trigger: "item"
        },
        legend: {
          data: []
        },
        grid: {
          left: "3%",
          right: "4%",
          bottom: "3%",
          containLabel: true
        },
        xAxis: {
          type: "value",
          min:0,
          max:24,
          interval:3,
          axisLabel: {
            show:true,
            textStyle: {
              color: "#80c98a"
            },
          },
          axisLine: {
            show: false
          },
          axisTick: {
            show:false
          },
          splitLine: {
            show: true
          }
        },
        yAxis: {
          type: "value",
          min: 0,
          max: 300,
          interval: 50,
          axisTick: {
            show: false
          },
          axisLine:{
            show:false
          },
          axisLabel: {
            textStyle: {
              color: "#80c98a"
            }
          }
        },
        series:[]
      }
    };
  },

  created() {

      if(this.$route.params.startDate){

        this.startDate=this.$route.params.startDate;

        this.endDate=this.$route.params.endDate;

      }else{

        this.startDate=this.endDate=this.today=new Date().toLocaleDateString().replace(/\//g,"-")

      }

      this.titType=this.$route.params.titType || 1;

      console.log(this.$route.params.titType)

      this.timeType=this.$route.params.timeType || 0;

  },

  mounted() {

    this.createEchart();

  },

  methods: {

    //返回到主页

    back(){

      this.$router.push('/index')

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
    },

    //自定义日期查询数据

    getDIYData(){

      this.dataType=[];

      this.eChartsData=[];

      this.legend=[];

      this.changeY();

      this.$http
        .get(this.$store.state.apiUrl + "health/data/" + this.titType,{
            params:{
                startDate:this.startDate,
                endDate:this.endDate
            }
          })
        .then(function(res) {
        console.log(res)
            if(!res.data.status){
                $api.pop(res.data.msg);
            }else{
                this.custom_data=res.data.data.custom_data;

                for(var i in this.custom_data){

                  if(i!='used_days'){
                    if(i!='height'&&i!='weight'){
                      this.dataType.push(i)
                    }
                  }
                }

                for(var i = 0;i < this.dataTypeName[this.titType-1].length;i++){

                      var series=
                        {
                          type: "scatter",
                          symbolSize: 16,
                          symbol: "circle",
                          itemStyle: {
                            normal: {
                              color: "", // 会设置点和线的颜色，所以需要下面定制 line
                              borderColor: "#b0e1ce",
                              borderWidth: 2
                            }
                          }
                        };

                  series.name=this.dataTypeName[this.titType-1][i];

                  series.data=this.custom_data[this.dataType[i]][1];

                  series.itemStyle.normal.color=this.color[i];

                  this.custom_date=this.custom_data[this.dataType[i]][0];

                  this.eChartsData.push(series)

                  this.legend.push(series.name)

                }

                if(this.custom_date.length<8){

                  this.end=100;

                }else if(this.custom_date.length>7&&this.custom_date.length<15){

                  this.end=40

                }else if(this.custom_date.length>15&&this.custom_date.length<30){

                  this.end=20

                }else if(this.custom_date.length>31){

                  this.end=10

                }

            }

            this.myChart.setOption({

              tooltip: {
                trigger: "item",
                formatter:function(params){
                  return '时间 :  '+params.name+'号</br>'+params.seriesName+' :  '+params.data
                }
              },

              dataZoom : [{
                 type:'inside',
                 start : 0,
                 end : this.end,
                 xAxisIndex: [0]
              }],
              xAxis: {
                type: "category",
                data:this.custom_date,
                boundaryGap: true,
                interval:1,
                show:true,
                axisLabel: {
                  textStyle: {
                    color: "#80c98a"
                  },
                },
                axisLine: {
                  show: false
                },
                axisTick: {
                  show: false
                },
                splitLine: {
                  show: true
                }
              },
              yAxis:this.y,
              legend:{
                data:this.legend
              },
              series:this.eChartsData
            })
        });
    },

    //生成表格

    createEchart(){
      var _this = this;
      setTimeout(function() {
        _this.myChart= echarts.init(document.getElementById("chart"));
        _this.myChart.setOption(_this.option)

      })

      if(this.timeType==3){

        this.getDIYData();

      }else{

        this.getHealthData();

      }

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

      this.dataType=[];

      this.eChartsData=[];

      this.legend=[];

      this.changeY();

      //今天数据展示格式  图表X轴更改

      if(this.timeType==0){

        for(var i in this.dayData){
          if(i!='used_days'){
            if(i!='height'&&i!='weight'){
              this.dataType.push(i)
            }
          }
        }

        for(var j = 0;j < this.dataTypeName[this.titType-1].length;j++){

          var series=
            {
              type: "scatter",
              symbolSize: 16,
              symbol: "circle",
              itemStyle: {
                normal: {
                  color: "", // 会设置点和线的颜色，所以需要下面定制 line
                  borderColor: "#b0e1ce",
                  borderWidth: 2
                }
              }
            };

          series.name=this.dataTypeName[this.titType-1][j];

          series.data=this.dayData[this.dataType[j]];

          series.itemStyle.normal.color=this.color[j];

          this.eChartsData.push(series)

          this.legend.push(series.name)

        }

          this.myChart.setOption({
            tooltip: {
              trigger: "item",
              formatter:function(params){
              console.log(params)
                var time=params.data[0]+'';

                if(time.indexOf('.')>-1){
                  var minute=time.split('.')[1]*6;
                  if(minute<10){
                    time=time.split('.')[0]+':0'+minute;
                  }else{
                    time=time.split('.')[0]+':'+minute;
                  }

                }else{

                  var minute=':00';
                  time=time+minute;

                }

                return '时间 :  '+time+'</br>'+params.seriesName+' :  '+params.data[1]
              }
            },
            dataZoom : [{
               type:'inside',
               start : 0,
               end : 100,
               xAxisIndex: [0]
            }],
            xAxis: {
              type: "value",
              min:0,
              max:24,
              interval:3,
              axisLabel: {
                show:true,
                textStyle: {
                  color: "#80c98a"
                },
              },
              axisLine: {
                show: false
              },
              axisTick: {
                show:false
              },
              splitLine: {
                show: true
              }
            },
            legend:{
              data:this.legend
            },
            yAxis:this.y,
            series:this.eChartsData
          })

      }else if(this.timeType==1){

          //近一周数据展示格式  图表X轴更改

          for(var i in this.weekData){
            if(i!='used_days'){
              if(i!='height'&&i!='weight'){
                this.dataType.push(i)
              }
            }
          }

          for(var j = 0;j < this.dataTypeName[this.titType-1].length;j++){

            var series=
              {
                type: "scatter",
                symbolSize: 16,
                symbol: "circle",
                itemStyle: {
                  normal: {
                    color: "", // 会设置点和线的颜色，所以需要下面定制 line
                    borderColor: "#b0e1ce",
                    borderWidth: 2,
                    lineStyle: {
                      color: "#e0e0e0",
                      width: 3
                    }
                  }
                }
              };

            series.name=this.dataTypeName[this.titType-1][j];

            series.data=this.weekData[this.dataType[j]][1];

            series.itemStyle.normal.color=this.color[j];

            this.week=this.weekData[this.dataType[j]][0];

            this.eChartsData.push(series)

            this.legend.push(series.name)

          }

          this.myChart.setOption({

            tooltip: {
              trigger: "item",
              formatter:function(params){

                return '时间 :  '+params.name+'号</br>'+params.seriesName+' :  '+params.data

              }
            },

            dataZoom : [{
               type:'inside',
               start : 0,
               end : 100,
               xAxisIndex: [0]
            }],
            xAxis: {
              type: "category",
              data:this.week,
              boundaryGap: true,
              interval:1,
              show:true,
              axisLabel: {
                textStyle: {
                  color: "#80c98a"
                },
              },
              axisLine: {
                show: false
              },
              axisTick: {
                show: false
              },
              splitLine: {
                show: true
              }
            },
            legend:{
              data:this.legend
            },
            yAxis:this.y,
            series:this.eChartsData
          })

      }else if(this.timeType==2){

          //近一月数据展示格式  图表滚动效果

          for(var i in this.monthData){
            if(i!='used_days'){
              if(i!='height'&&i!='weight'){
                this.dataType.push(i)
              }
            }
          }

          for(var j = 0;j < this.dataTypeName[this.titType-1].length;j++){

            var series=
              {
                type: "scatter",
                symbolSize: 16,
                symbol: "circle",
                itemStyle: {
                  normal: {
                    color: "", // 会设置点和线的颜色，所以需要下面定制 line
                    borderColor: "#b0e1ce",
                    borderWidth: 2,
                    lineStyle: {
                      color: "#e0e0e0",
                      width: 3
                    }
                  }
                }
              };

            series.name=this.dataTypeName[this.titType-1][j];

            series.data=this.monthData[this.dataType[j]][1];

            series.itemStyle.normal.color=this.color[j];

            this.month=this.monthData[this.dataType[j]][0];

            this.eChartsData.push(series)

            this.legend.push(series.name)

            console.log(this.eChartsData)

          }

          this.myChart.setOption({

            tooltip: {
              trigger: "item",
              formatter:function(params){
                return '时间 :  '+params.name+'号</br>'+params.seriesName+' :  '+params.data
              }
            },

            dataZoom : [{
               type:'inside',
               start : 0,
               end : 20,
               xAxisIndex: [0]
            }],

            xAxis: {
              type: "category",
              data: this.month,
              boundaryGap: true,
              interval:1,
              show:true,
              axisLabel: {
                textStyle: {
                  color: "#80c98a"
                },
              },
              axisLine: {
                show: false
              },
              axisTick: {
                show: false
              },
              splitLine: {
                show: true
              }
            },
            legend:{
              data:this.legend
            },
            yAxis:this.y,
            series:this.eChartsData
          })
      }
    },

    lr: function(i, titType) {
      this.$router.push({
        path: "/data_lr",
        query: { type: i, titType: titType-1 }
      });
    },

    //图表切换

    qh: function() {

      if(this.timeType==3){

        this.$router.push({ name:"图标切换",params:{titType:this.titType,timeType:this.timeType,startDate:this.startDate,endDate:this.endDate} });

      }else{

        this.$router.push({ name:"图标切换",params:{titType:this.titType,timeType:this.timeType} });

      }

    },

    //Y轴切换

    changeY(){

      if(this.titType==1){
        this.y=
          {
            type: "value",
            min: 0,
            max: 300,
            interval: 50,
            axisTick: {
              show: false
            },
            axisLine:{
              show:false
            },
            axisLabel: {
              textStyle: {
                color: "#80c98a"
              }
            }
          }
      }else if(this.titType==2){
        this.y=
          {
           type: "value",
           min: 0,
           max: 14,
           interval: 2,
           axisTick: {
             show: false
           },
           axisLine:{
             show:false
           },
           axisLabel: {
             textStyle: {
               color: "#80c98a"
             },
             formatter: function (value, index) {
               if(value>0){
                 return value.toFixed(1);
               }else{
                 return 0
               }
             }
           }
          }
      }else if(this.titType==3){
        this.y=
          {
           type: "value",
           min: 40,
           max: 160,
           interval: 20,
           axisTick: {
             show: false
           },
           axisLine:{
             show:false
           },
           axisLabel: {
             textStyle: {
               color: "#80c98a"
             }
           }
         }
      }else if(this.titType==4){
        this.y=
          {
           type: "value",
           min: 36,
           max: 41,
           interval: 1,
           axisTick: {
             show: false
           },
           axisLine:{
             show:false
           },
           axisLabel: {
             textStyle: {
               color: "#80c98a"
             }
           }
         }
      }else if(this.titType==5){
        this.y=
          {
           type: "value",
           min: 18,
           max: 30,
           interval: 2,
           axisTick: {
             show: false
           },
           axisLine:{
             show:false
           },
           axisLabel: {
             textStyle: {
               color: "#80c98a"
             }
           }
         }
      }

    },

    checkTit: function(i) {

      this.titType = i+1;

      this.changeY();

      console.log(this.timeType)

      if(this.timeType!=3){

        this.createEchart()

      }else{
        var _this = this;
        setTimeout(function() {
          //柱状图
          _this.myChart= echarts.init(document.getElementById("chart"));
          _this.myChart.setOption(_this.option)

        })

        this.myChart.setOption({
          yAxis:this.y
        })

        this.getDIYData();

      }

    },

    checkTime: function(i) {

      this.timeType = i;

      if(this.timeType!=3){

        this.createEchart()

      }
    }
  }
};
</script>

