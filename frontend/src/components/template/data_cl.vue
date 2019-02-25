<template lang='jade'>
.fixbody
  header
    .left(@click='backToData')
      i.icon-arrow-left
    .center 测量结果
  #wrap
      .tab_list2
        .time
          span {{ healthData.date }}
        //血压
        .bg(v-if="titType==0")
          p
            span 收缩压:
            span.num {{ healthData.content.systolic }}
              i(v-if='ssy')
            span mmHg
          p
            span 舒张压:
            span.num {{ healthData.content.diastolic }}
              i(v-if='szy')
            span mmHg
          p
            span 心率:
            span.num {{ healthData.content.heart_rate }}
              i(v-if='xl')
            span bpm
        //血糖
        .bg(v-if="titType==1")
            p
              span 测量时段：
              span.num.can
              span {{ healthData.content.status }}
            p
              span 血糖值：
              span.num {{ healthData.content.sugar_val }}
                i(v-if='xt')
              span mmol/L
        //血氧
        .bg(v-if="titType==2")
            p
              span 血氧值：
            p
              span.num {{ healthData.content.oxygen_val }} %
                i(v-if='xy')
        //体温
        .bg(v-if="titType==3")
            p
              span 体温值：
            p
              span.num {{ healthData.content.temp_val }} ℃
                i(v-if='tw')
        //体质
        .bg(v-if="titType==4")
            p
              span 身高：
              span.num {{ healthData.content.height }}
              span cm
            p
              span 体重：
              span.num {{ healthData.content.weight }}
              span kg
            p
              span BMI：
              span.num {{ healthData.content.BMI }}
                i(v-if='B')
              span k
      //血压
      ul.data_s(v-if="titType==0")
        li
          span 血压
          .numsBox
            .num_t
              span 低
              span 正常
              span 高
            .nums(v-bind:class='pressure') {{ healthData.content.systolic }}/{{ healthData.content.diastolic }}
        li
          span 心率
          .numsBox
            .num_t
                span 低
                span 正常
                span 高
            .nums(v-bind:class='heart_rate') {{ healthData.content.heart_rate }} bpm

      //血糖
      ul.data_s(v-if="titType==1")
        li
          span 血糖
          .numsBox
            .num_t
              span 正常
              span IFG或IGT
              span 糖尿病
            .nums(v-bind:class='sugar') {{ healthData.content.sugar_val }} mmol/L
      //血痒
      ul.data_s(v-if="titType==2")
        li
          span 血氧
          .numsBox
            .num_t
              span 偏低
              span 供氧不足
              span 正常
            .nums(v-bind:class='oxygen') {{ healthData.content.oxygen_val }} %
      //体温
      ul.data_s(v-if="titType==3")
        li
          span 体温
          .numsBox
            .num_t
                span 低温
                span 正常
                span 高温
            .nums(v-bind:class='temp') {{ healthData.content.temp_val }} ℃
      //体质
      ul.data_s(v-if="titType==4")
        li
          span BMI
          .numsBox
            .num_t
              span 偏瘦
              span 正常
              span 偏胖
            .nums(v-bind:class='BMI') {{ healthData.content.BMI }}

      //- .doc_main.daodu
      //-     .conts
      //-         h3 结果解读
      //-         p 结果导读结果导读结果导读结果导读结果导读结果导读结果导读...

</template>
<script>
  export default {
      data() {
        return{
            titType:0,
            healthData:{
              time:'',
              content:{
                systolic:0,
                diastolic:0,
                heart_rate:0,
                sugar_val:0,
                status:0,
                oxygen_val:0,
                temp_val:0,
                BMI:0
              }
            },
            pressure:'',
            heart_rate:'',
            sugar:'',
            oxygen:'',
            temp:'',
            BMI:'',

            //感叹号

            ssy:0,            //收缩压
            szy:0,            //舒张压
            xl:0,
            xt:0,
            xy:0,
            tw:0,
            B:0
        }
      },

      created() {

        this.titType = this.$route.query.titType;        //0血压，1血糖，2血氧，3体温，4体质

      },

      mounted(){

        this.getInfo(this.titType+1)

      },

      methods:{

        //返回健康数据图表

        backToData(){

          this.$router.push({ path:'/data/1/0' });

        },

        getInfo(type){

          this.$http.get(this.$store.state.apiUrl+'health/last/' + type).then(function (res) {

          console.log(res)

              if(!res.data.status){

                  $api.pop(res.data.msg);

              }else{

                  this.healthData = res.data.data;

                  this.judge();

              }
          })
        },

        //判断范围

        judge(){

          if(this.titType==0){

            //血压判断

            if(this.healthData.content.systolic<90||this.healthData.content.diastolic<60){

              this.pressure='low'

            }else if(this.healthData.content.systolic>120||this.healthData.content.diastolic>90){

              this.pressure='high'

            }else{

              this.pressure='normal'

            }

            //心率判断

            if(this.healthData.content.heart_rate<60){

              this.heart_rate='low'

              this.xl=1

            }else if(this.healthData.content.heart_rate>100){

              this.heart_rate='high'

              this.xl=1

            }else{

              this.heart_rate='normal'

            }

            //感叹号

            if(this.healthData.content.systolic<90||this.healthData.content.systolic>120){

              this.ssy=1

            }

            if(this.healthData.content.diastolic<60||this.healthData.content.diastolic>90){

              this.szy=1

            }

          }else if(this.titType==1){

            //血糖判断

            if(this.healthData.content.status=='餐前'){

              if(this.healthData.content.sugar_val<3.9){

                this.sugar='low'

                this.xt=1

              }else if(this.healthData.content.sugar_val>11.1){

                this.sugar='high'

                this.xt=1

              }else{

                this.sugar='normal'

              }

            }else{

              if(this.healthData.content.sugar_val<6.7){

                this.sugar='low'

                this.xt=1

              }else if(this.healthData.content.sugar_val>11.1){

                this.sugar='high'

                this.xt=1

              }else{

                this.sugar='normal'

              }

            }

          }else if(this.titType==2){

            //血氧判断

            if(this.healthData.content.oxygen_val<95){

              this.oxygen='low'

              this.xy=1

            }else if(this.healthData.content.oxygen_val>95&&this.healthData.content.oxygen_val<97){

              this.oxygen='normal'

            }else{

              this.oxygen='high'

              this.xy=1

            }


          }else if(this.titType==3){

            //体温判断

            if(this.healthData.content.temp_val<36.3){

              this.temp='low'

              this.tw=1

            }else if(this.healthData.content.temp_val>37.2){

              this.temp='high'

              this.tw=1

            }else{

              this.temp='normal'

            }

          }else if(this.titType==4){

            //BMI判断

            if(this.healthData.content.BMI<18.5){

              this.BMI='low'

              this.B=1

            }else if(this.healthData.content.BMI>23.9){

              this.BMI='high'

              this.B=1

            }else{

              this.BMI='normal'

            }
          }
        }
      }
  };
</script>
