
<!--物流状态-->
<template>
  <div id="sendcode" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">物流状态</h4>
        </div>
        <div class="modal-body">
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <label for="" class="col-sm-2 control-label"><span>优惠码类型：</span></label>
              <div class="col-sm-10">
                <input type="text" value="字母+数字" readonly="readonly" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label"><span>优惠码长度：</span></label>
              <div class="col-sm-10">
                <input type="text" value="6位" readonly="readonly" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label"><span>优惠码数量：</span></label>
              <div class="col-sm-10">
                <input name="total" type="text" v-model="code.total" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label"><span>优惠金额：</span></label>
              <div class="col-sm-10">
                <input name="discount" type="text" v-model="code.discount" class="form-control"/>
              </div>
            </div>
            <!--.form-group-->
            <!--    label.col-sm-2.control-label(for='')-->
            <!--        span 活动链接：-->
            <!--    .col-sm-10-->
            <!--        input.form-control(name="url" type="text", v-model="code.url")-->
            <div class="form-group">
              <label for="" class="col-sm-2 control-label"><span>开始时间：</span></label>
              <div class="col-sm-10">
                <input name="url" type="text" v-model="code.start_time" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label"><span>截止时间：</span></label>
              <div class="col-sm-10">
                <input name="url" type="text" v-model="code.end_time" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label"><span>目标用户：</span></label>
              <div class="col-sm-10">
                <input id="aaaa" type="file" name="file"/>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      props: ['id'],
      created(){
          this.id = this.$route.params.id;//2
      },
      data(){
          return {
              send: {},//
              exp:{},
              reason:{},
              code:{},
              iid:2,
          };
      },
      ready(){
  
      },
      methods: {
          getSendDetail(id){
              if (id > 0) {
                  this.$http.get('promo/detail/' + id).then(function (res) {//
                      this.$set('code', res.data.data);
                  });
              }
          },
          daoru(){
              var self = this;
              layui.use('upload', function () {
                  layui.upload({
                      url: '/api/promo/addfile/'+self.id,
                      title: '发放优惠码',
                      elem: '#aaaa', //指定原始元素，默认直接查找class="layui-upload-file"
                      method: 'post',
                      type: 'file',
                      success: function (res) {
                          if (res.status == 1) {
                              layer.msg(res.msg);
                              window.location.href = "promocode_mobile";
                          }else{
                              layer.msg(res.msg);
                          }
                      }
                  });
              });
          },
      },
      watch: {
          id(newValue){
              this.getSendDetail(newValue);
              this.daoru();
          },
      }
  
  }
</script>