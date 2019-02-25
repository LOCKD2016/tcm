
<template>
  <div id="clinic_detail" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <form role="form" v-if="data.messages" class="form-horizontal">
            <label class="modal-title"> 诊断内容</label>
            <div class="form-group">
              <div v-for="val in data.messages" class="col-sm-8"><span v-if="val.type ==1">
                  <label>医生 :</label><span>{{data.doc_name}}</span></span><span v-if="val.type ==2">
                  <label>患者 :</label><span>{{data.nickname}}</span></span><span>&nbsp;&nbsp;{{val.created_at}}</span><span v-if="val.type ==3"></span>
                <div v-if="val.msg_type == 'text'">
                  <div v-bind:class="val.type ==3 ? 'message_font_color' : ''">{{val.content.text}}</div>
                </div>
                <div v-if="val.msg_type == 'audio'">
                  <audio v-bind:src="val.content.text"></audio>
                </div>
                <div v-if="val.msg_type == 'card'" @click="card_detail(val.content.extra.cType,val.content.extra.id)">
                  <div v-if="val.content.extra.cType ==1" class="message_font_color"> 个性问诊单</div>
                  <div v-if="val.content.extra.cType ==2" class="message_font_color"> 标准问诊单</div>
                  <div v-if="val.content.extra.cType ==3" class="message_font_color"> 处方问诊单</div>
                  <div v-if="val.content.extra.cType ==4" class="message_font_color"> 用户填写个性问诊单</div>
                </div>
              </div>
            </div>
          </form>
          <hr/>
          <form role="form" v-if="data.has_one_recipe" class="form-horizontal">
            <label class="modal-title">处方 {{data.has_one_recipe.recipe_head.sum}} 副</label>
            <div class="form-group">
              <div class="col-sm-8"><span v-for="val in data.has_one_recipe.recipe">
                  <p>{{val.name}} {{val.dosage}}g  {{val.other}}</p></span></div>
              <div class="col-sm-8">
                <label>医嘱:</label><span>{{data.has_one_recipe.recipe_remark}}</span>
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
          card_detail(ctype,id){
              window.open('/admin/card_detail/'+ctype+'/'+id+'/'+this.data.family_id);
          },
          getDetail(id){
              this.$http({url:'clinic/show/'+id}).then(function (res) {
                  this.$set('data',res.data.clinic);
              });
          }
      },
      watch:{
          'clinic_id':function(value){
              if(value>0){
                  this.getDetail(value);
              }
          }
      }
  }
</script>