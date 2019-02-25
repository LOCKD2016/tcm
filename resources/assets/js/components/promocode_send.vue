
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
            <input type="text" value="12位" readonly="readonly" class="form-control"/>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label"><span>优惠码数量：</span></label>
          <div class="col-sm-5">
            <input name="total" type="text" v-model="code.total" class="form-control"/>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label"><span>优惠金额：</span></label>
          <div class="col-sm-5">
            <input name="discount" type="text" v-model="code.discount" class="form-control"/>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label"><span>活动链接：</span></label>
          <div class="col-sm-5">
            <input name="url" type="text" v-model="code.url" class="form-control"/>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label"><span>开始时间：</span></label>
          <div class="col-sm-5">
            <input name="url" type="text" v-model="code.start_time" class="form-control"/>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label"><span>截止时间：</span></label>
          <div class="col-sm-5">
            <input name="url" type="text" v-model="code.end_time" class="form-control"/>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label"><span>目标用户：</span></label>
          <div class="col-sm-5">
            <input name="file" type="file" class="form-control file"/>
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
      created(){
          this.id = this.$route.params.id;
      },
      ready(){
          headNav(4);
          this.daoru();
  
      },
      data(){
          return {
              id:0,
              code:{},
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
          daoru(){
              var self = this;
              layui.use('upload', function () {
                  layui.upload({
                      url: 'promo/addfile',
                      title: '导入手机号',
                      elem: '.file', //指定原始元素，默认直接查找class="layui-upload-file"
                      method: 'post',
                      type: 'file',
                      success: function (res) {
                          layer.msg('文件上传成功');
                      }
                  });
              });
          },
          detail(id){
              if (id > 0) {
                  this.$http.get('promo/detail/' + id).then(function (res) {
                      this.$set('code', res.data.data);
                  });
              }
          },
          goback(){
              this.$router.go("/promocode_list");
          },
      },
      watch: {
          id(newValue){
              this.detail(newValue);
          }
      }
  
  }
</script>