
<template>
  <div id="clinicdetail" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <label class="modal-title">详情</label>
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <div class="col-sm-8">
                <p>订单编号： {{data.order_sn}}</p>
                <p>患  者： {{data.f_name}}</p>
                <p>就诊医师： {{data.doc_name}}</p>
                <p>类型： {{data.recipe_status == 1 ? '网诊' : '门诊'}} {{data.is_first ==1 ? '复诊' : '初诊'}}</p>
              </div>
            </div>
          </form>
          <hr/>
          <form role="form" v-if="data.messages" class="form-horizontal">
            <label class="modal-title"> 诊断内容</label>
            <div class="form-group">
              <div v-for="val in data.messages" class="col-sm-8"><span v-if="val.type ==1">
                  <label>医生 :</label><span>{{val.doc_name}}</span></span><span v-if="val.type ==2">
                  <label>患者 :</label><span>{{val.doc_name}}</span></span><span v-if="val.type ==3"></span>
                <div v-if="val.msg_type == 'text'">
                  <div v-bind:class="val.type ==3 ? 'message_font_color' : ''">{{val.content}}</div>
                </div>
                <div v-if="val.msg_type == 'audio'">
                  <audio v-bind:src="val.content"></audio>
                </div>
                <div v-if="val.msg_type == 'card'">
                  <div v-if="val.content.extra.ctype ==1"> 个性问诊单</div>
                  <div v-if="val.content.extra.ctype ==2"> 标准问诊单</div>
                  <div v-if="val.content.extra.ctype ==3"> 处方问诊单</div>
                  <div v-if="val.content.extra.ctype ==4"> 用户填写个性问诊单</div>
                </div>
              </div>
            </div>
          </form>
          <hr/>
          <form role="form" v-if="data.has_one_recipe" class="form-horizontal">
            <label class="modal-title">处方 {{data.has_one_recipe.recipe_head}} 副</label>
            <div class="form-group">
              <div class="col-sm-8"><span v-for="val in data.has_one_recipe.recipe">
                  <p>{{val.name}} {{val.g}}g  {{val.other}}</p></span></div>
              <div class="col-sm-8">
                <label>医嘱:</label><span>{{data.has_one_recipe.recipe_remark}}</span>
              </div>
            </div>
          </form>
          <hr/>
          <form role="form" v-if="data.is_take_prescription" class="form-horizontal">
            <label class="modal-title">其他</label>
            <div class="form-group">
              <div class="col-sm-8">
                <label>抓药</label><span v-if="data.is_tisane">代煎{{data.has_one_recipe.recipe_head}}副</span><span v-else="v-else"> 不代煎</span>
              </div>
              <div class="col-sm-8">
                <label>订单号:</label><span>{{data.prescription.order_sn}}</span>
              </div>
              <div class="col-sm-8">
                <label>快递:</label><span>{{data.prescription.province}}{{data.prescription.city}}{{data.prescription.district}}{{data.prescription.address}}</span>
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
      props:['clinic_id'],
      data(){
          return {
              data:{}
          };
      },
      methods:{
          getDetail(id){
              this.$http({url:'clinic/show/'+id}).then(function (res) {
                  this.$set('data',res.data.clinic);
              });
          }
      },
      watch:{
          'clinic_id':function(value){
              alert(123123)
              if(value>0){
                  this.getDetail(value);
              }
          }
      }
  }
</script>