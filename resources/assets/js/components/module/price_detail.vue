
<template>
  <div id="price_detail" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <label class="modal-title">详情</label>
          <form role="form" class="form-horizontal">
            <div style="padding-left: 15px;" class="form-group"><span>
                <p>姓名 : {{val.user.realname}}</p>
                <p>性别 : {{val.user.sex}}</p>
                <p>出生日期 : {{val.user.birthday}}</p></span></div>
            <div class="form-group">
              <div class="col-sm-8">
                <p>共{{val.recipe_head.sum}} 剂，每日{{val.recipe_head.dayNum}}剂，1剂分{{val.recipe_head.takingNum}}次服用</p><span v-for="item in val.recipe">
                  <p>{{item.name}} {{item.dosage}}g  {{item.other}}</p></span>
                <!--span
                p 划价人 {{val.admin.user_name}}
                p 药费 {{val.medicine_price}}
                p 调剂费 {{val.dispensing_price}}
                p 代煎费 {{val.tisane_price}}
                p(v-if="val.tisane ==0 && val.recipe_self.length >0") 自备费 {{val.recipe_self_price}}
                //p 划价时间 {{val.price_time}}
                --><span>
                  <div class="col-sm-8"></div>
                  <!--label(v-if="val.type",style="margin-top:10px") 剂量input.form-control(type="number",v-model="val.recipe_head.sum")
                  -->
                  <label style="margin-top:10px">药材费(总价)
                    <input type="text" v-model="val.medicine_price" readonly="readonly" class="form-control"/>
                  </label>
                  <label style="margin-top:10px">调剂费(总价)
                    <input type="text" v-model="val.dispensing_price" readonly="readonly" class="form-control"/>
                  </label>
                  <label style="margin-top:10px">代煎费(总价)
                    <input type="text" v-model="val.tisane_price" readonly="readonly" class="form-control"/>
                  </label>
                  <label>
                    <div v-if="val.tisane ==0 &amp;&amp; val.recipe_self.length &gt;0" style="margin-top:10px" class="col-sm-8">自备费(总价)</div>
                    <input v-if="val.tisane ==0 &amp;&amp; val.recipe_self.length &gt;0" type="text" v-model="val.recipe_self_price" readonly="readonly" class="form-control"/>
                  </label>
                  <!--    .col-sm-8(style="margin-top:10px") 药材总重(g)-->
                  <!--    input.form-control(type="text",v-model="val.recipe_head.sumWeight")-->
                  <!--    .col-sm-8(style="margin-top:10px") 每日服用次数-->
                  <!--    input.form-control(type="text",v-model="val.recipe_head.dayNum")-->
                  <!--    .col-sm-8(style="margin-top:10px") 一剂服用次数-->
                  <!--    input.form-control(type="text",v-model="val.recipe_head.takingNum")-->
                  <!--.col-sm-8(style="margin-top:10px;margin-left:-10px;")-->
                  <!--    button.btn.btn-primary(type='button', v-on:click="send(1)") 保存并发送-->
                  <!--    button.btn.btn-primary(type='button', v-on:click="send(0)") 保存-->
                  <!--    button.btn.btn-primary(type='button', v-on:click="send(2)") 拒绝-->
                  <!--    button.btn.btn-default(type='button', data-dismiss='modal') 取消--></span>
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
      props:['val'],
      data(){
          return {
  
          };
      },
      methods:{
          send(type){
              var preg = /(^[1-9]([0-9]+)?(\.[0-9]{1,2})?$)|(^(0){1}$)|(^[0-9]\.[0-9]([0-9])?$)/;
              if (!preg.test(this.val.medicine_price)) {
                  layer.msg('请输入正确的金额格式 -- 药材费');
                  return;
              }
              if (!preg.test(this.val.dispensing_price)) {
                  layer.msg('请输入正确的金额格式 -- 调剂费');
                  return;
              }
              var data = {};
              data.medicine_price = this.val.medicine_price;
              data.dispensing_price = this.val.dispensing_price;
              data.recipe_head = this.val.recipe_head;
              data.recipe_head.sumWeight = this.val.recipe_head.sumWeight;
              data.recipe_head.dayNum = this.val.recipe_head.dayNum;
              data.recipe_head.takingNum = this.val.recipe_head.takingNum;
              data.recipe_head.sum = this.val.recipe_head.sum;
              if(type == 1){
                  data.send = 1;
                  data.is_price = 1;
              }else if(type == 0){
                  data.is_price = 1;
              }else{
                  data.is_price = 9;
              }
              this.$http({url: 'prescription/setprice/' + this.val.id, method: "PUT", params: {data}}).then(function (res) {
                  layer.msg(res.data.msg);
                  if (res.data.errcode == 200) {
                      this.$dispatch('update');
                      this.init();
                      $('#price_detail').modal('hide');
                  }
              });
          },
      }
  }
</script>