
<template>
  <div class="tit_nav">
    <div class="container clearfix">
      <div class="pull-left">添加优惠码</div>
    </div>
  </div>
  <div class="container main_warp">
    <div class="new_item">
      <form role="form" class="form-horizontal">
        <div class="form-group">
          <label for="" class="col-sm-1 control-label"><span>优惠码类型：</span></label>
          <div class="col-sm-5">
            <input type="text" value="字母+数字" readonly="readonly" class="form-control"/>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label"><span>优惠码长度：</span></label>
          <div class="col-sm-5">
            <input type="text" value="6位" readonly="readonly" class="form-control"/>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label"><span>优惠码数量：</span></label>
          <div class="col-sm-5">
            <input name="total" type="text" placeholder="请输入优惠码数量" v-model="total" class="form-control"/>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label"><span>优惠金额：</span></label>
          <div class="col-sm-5">
            <input name="discount" type="text" placeholder="请输入优惠金额" v-model="discount" class="form-control"/>
          </div>
        </div>
        <!--.form-group-->
        <!--    label.col-sm-1.control-label(for='')-->
        <!--        span 活动链接：-->
        <!--    .col-sm-5-->
        <!--        input.form-control(name="url" type="text",placeholder="请输入活动链接" v-model="url")-->
        <div class="form-group">
          <label for="" class="col-sm-1 control-label"><span>开始时间：</span></label>
          <div class="col-sm-5">
            <!--input.form-control.time_date(name="goods_start" type="date",placeholder="请输入邀请开始时间"  v-model="good.goods_start")-->
            <input type="text" readonly="readonly" name="start_time" @click.stop="open($event,'picker2')" v-model="calendar.items.picker2.value" placeholder="请输入邀请开始时间" class="form-control time_date"/>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label"><span>截止时间：</span></label>
          <div class="col-sm-5">
            <!--input.form-control.time_date(name="goods_end" type="date",placeholder="请输入邀请截止时间" v-model="good.goods_end")-->
            <input type="text" readonly="readonly" name="end_time" @click.stop="open($event,'picker3')" v-model="calendar.items.picker3.value" placeholder="请输入邀请截止时间" class="form-control time_date"/>
            <calendar :show.sync="calendar.show" :type="calendar.type" :value.sync="calendar.value" :x="calendar.x" :y="calendar.y" :begin.sync="calendar.begin" :end.sync="calendar.end" :range.sync="calendar.range" :months="calendar.months"></calendar>
          </div>
        </div>
        <div class="form-group btn_box">
          <button type="button" @click="goback()" class="btn btn-lg btn-default">取消
            <button id="subUpload" type="button" @click="saveGood()" class="btn btn-lg btn-primary">添加</button>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
<script type="text/javascript">
  import calendar from "../../js/calendar.vue"
  export default {
      components: {
          calendar
      },
      created(){
  
      },
      ready(){
          headNav(4);
  
      },
      data(){
          return {
              total:'',
              discount:'',
              url:'',
              calendar: {
                  show: false,
                  x: 0,
                  y: 0,
                  picker: "",
                  type: "date",
                  value: "",
                  begin: "",
                  end: "",
                  //weeks: [],
                  months: [],
                  range: false,
                  items: {
                      // 日期时间模式、、
                      picker3: {
                          type: "datetime",
                          value: "",
                          sep: "-",
                      }, picker2: {
                          type: "datetime",
                          value: "",
                          sep: "-",
                      },
                  }
              }
          }
      },
      methods: {
          saveGood(){
              var vue = this;
              var obj = $("form").serializeArray();
              vue.$http.post('promo/add', obj).then(function (res) {
                  if (res.data.status == 1) {
                      layer.msg(res.data.msg);
                      vue.$router.go("/promocode_list/1");
                  } else {
                      layer.msg(res.data.msg);
                  }
              },
              function (res) {
                  var data = res.data;
                  errorMsg(data.errors);
              });
          },
          goback(){
              this.$router.go("/promocode_list/1");
          },
          open(e, type) {
              // 设置类型123
              this.calendar.picker = type;
              this.calendar.type = this.calendar.items[type].type;
              this.calendar.range = this.calendar.items[type].range;
              this.calendar.begin = this.calendar.items[type].begin;
              this.calendar.end = this.calendar.items[type].end;
              this.calendar.value = this.calendar.items[type].value;
              // 可不用写
              this.calendar.sep = this.calendar.items[type].sep;
              this.calendar.weeks = this.calendar.items[type].weeks;
              this.calendar.months = this.calendar.items[type].months;
  
              this.calendar.show = true;
              this.calendar.x = e.target.offsetLeft;
              this.calendar.y = e.target.offsetTop + e.target.offsetHeight + 8;
          }
      },
      watch: {
          'calendar.value':function (value) {
              this.calendar.items[this.calendar.picker].value = value
          }
      }
  
  }
</script>