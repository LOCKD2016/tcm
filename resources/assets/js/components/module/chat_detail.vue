
<template>
  <div id="chat_detail" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <label class="modal-title">详情</label>
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <div class="col-sm-8">
                <p>订单编号： {{order.order_sn}}</p>
                <p>患  者： {{user.realname}}</p>
                <p>就诊医师： {{doctor.name}}</p>
                <p>类型： {{data.type}} {{data.first}}</p>
              </div>
            </div>
          </form>
          <hr/>
          <form role="form" v-if="message.message" class="form-horizontal">
            <label class="modal-title"> 诊断内容</label>
            <div class="form-group">
              <div v-for="val in message.message" class="col-sm-8"><span v-if="val.type ==2">
                  <label>医生 :</label><span>{{doctor.name}}</span></span><span v-if="val.type ==1">
                  <label>患者 :</label><span>{{user.realname}}</span></span><span>&nbsp;&nbsp;{{val.created_at}}</span>
                <div v-if="val.msg_type == 'text'">
                  <div v-bind:class="val.type ==3 ? 'message_font_color' : ''">{{val.content.text}}</div>
                </div>
                <div v-if="val.msg_type == 'audio'">
                  <audio v-bind:src="val.content.text"></audio>
                </div>
                <div v-if="val.msg_type == 'image'"><span onclick="window.open('http://oy3x9vo5r.bkt.clouddn.com/{{val.content.key}}')"> http://oy3x9vo5r.bkt.clouddn.com/{{val.content.key}}</span></div>
                <div v-if="val.msg_type == 'card'" @click="card_detail(val.content.extra.ctype,val.content.extra.id)">
                  <div v-if="val.content.extra.ctype ==1" class="message_font_color"> 个性问诊单</div>
                  <div v-if="val.content.extra.ctype ==2" class="message_font_color"> 标准问诊单</div>
                  <div v-if="val.content.extra.ctype ==3" class="message_font_color"> 处方问诊单</div>
                  <div v-if="val.content.extra.ctype ==4" class="message_font_color"> 用户填写个性问诊单</div>
                </div>
              </div>
            </div>
          </form>
          <hr/>
          <form role="form" v-if="prescription" class="form-horizontal">
            <label class="modal-title">处方 {{recipeHead.sum}} 副</label>
            <div class="form-group">
              <div class="col-sm-8"><span v-for="val in prescription.recipe">
                  <p>{{val.name}} {{val.dosage}} {{val.unit}}  {{val.other}}</p></span></div>
              <div class="col-sm-8">
                <label>医嘱:</label><span>{{prescription.recipe_remark}}</span>
              </div>
            </div>
          </form>
          <hr/>
          <form role="form" v-if="prescriptionOrder" class="form-horizontal">
            <label class="modal-title">其他</label>
            <div class="form-group">
              <div class="col-sm-8">
                <label>抓药</label><span v-if="prescription.tisane">代煎{{prescription.recipe_head}}副</span><span v-else="v-else"> 不代煎</span>
              </div>
              <div class="col-sm-8">
                <label>订单号:</label><span>{{order.order_sn}}</span>
              </div>
              <div v-if="prescription.express" class="col-sm-8">
                <label>快递:</label><span v-if="prescription.express">{{prescriptionOrder.province}}{{prescriptionOrder.city}}{{prescriptionOrder.district}}{{prescriptionOrder.address}}</span>
              </div>
              <div v-else="v-else" class="col-sm-8">
                <label>自取</label>
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
      props:['id'],
      data(){
          return {
              data:{},
              user:{},
              doctor:{},
              order:{},
              bespeak:{},
              prescription:{},
              recipeHead:{},
              prescriptionOrder:{},
              message:{},
          };
      },
      methods:{
          card_detail(ctype, id){
              window.open('/admin/card_detail/' + ctype + '/' + id);
          },
          getDetail(id){
              this.$http({url:'clinic/show/'+id}).then(function (res) {
                  this.$set('data',res.data.data);
                  this.bespeak = this.data.bespeak;
                  this.order = this.bespeak.order;
                  this.doctor = this.data.doctor;
                  this.user = this.data.user;
                  this.message = this.data.message;
                  this.prescription = this.data.prescription;
                  if(this.prescription){
                      this.recipeHead = this.prescription.recipe_head;
                      this.prescriptionOrder = this.prescription.order;
                  }
              });
          }
      },
      watch:{
          'id':function(value){
              if(value>0){
                  this.getDetail(value);
              }
          }
      }
  }
</script>