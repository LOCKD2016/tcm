
<template>
  <div class="tit_nav">
    <div class="container clearfix">
      <div class="pull-left">问诊单详情</div>
    </div>
  </div>
  <div class="container main_warp">
    <!--/ctype 1:个性化问诊单|2:普通问诊单|3:处方|4:已回答个性化问诊单-->
    <form role="form" v-if="ctype ==2" class="form-horizontal">
      <div class="form-group">
        <div class="col-sm-8">
          <label>病名:</label><span>{{data.user_clinic_title}}</span>
        </div>
        <div class="col-sm-8">
          <label>描述:</label><span>{{data.user_clinic_content}}</span>
        </div>
      </div>
    </form>
    <form role="form" v-if="ctype ==1" class="form-horizontal">
      <div v-for="ddd in data.options" class="form-group sel_ti">
        <label v-if="ddd.type =='radio'" class="col-sm-2 control-label">单选题 ：</label>
        <label v-if="ddd.type =='checkbox'" class="col-sm-2 control-label">复选题 ：</label>
        <label v-if="ddd.type =='text'" class="col-sm-2 control-label">问答题 ：</label>
        <label v-if="ddd.type =='photo'" class="col-sm-2 control-label">图片题 ：</label>
        <div class="itemss"><span>{{ddd.title}}</span></div>
        <template v-if="ddd.type == 'checkbox' || ddd.type =='radio'" track-by="$index">
          <p v-for="answer in ddd.option" class="result"><span> {{answer.val}}</span></p>
        </template>
        <template v-if="ddd.type == 'photo'">
          <p v-for="val in ddd.option" class="result"><img v-bind:src="val"/></p>
        </template>
      </div>
    </form>
    <form role="form" v-if="ctype ==3" class="form-horizontal">
      <label class="modal-title">处方 {{data.recipe_head}} 副</label>
      <div class="form-group">
        <div class="col-sm-8"><span v-for="val in data.recipe">
            <p>{{val.name}} {{val.g}}g  {{val.other}}</p></span></div>
        <div class="col-sm-8">
          <label>医嘱:</label><span>{{data.recipe_remark}}</span>
        </div>
      </div>
    </form>
    <form role="form" v-if="ctype ==4" class="form-horizontal">
      <div v-for="ddd in data.options" class="form-group sel_ti">
        <label v-if="ddd.type =='radio'" class="col-sm-2 control-label">单选题 ：</label>
        <label v-if="ddd.type =='checkbox'" class="col-sm-2 control-label">复选题 ：</label>
        <label v-if="ddd.type =='text'" class="col-sm-2 control-label">问答题 ：</label>
        <label v-if="ddd.type =='photo'" class="col-sm-2 control-label">图片题 ：</label>
        <div class="itemss"><span>{{ddd.title}}</span></div>
        <template v-if="ddd.type == 'checkbox' || ddd.type =='radio'" track-by="$index">
          <p v-for="answer in ddd.option" class="result"><span> {{answer.val}}</span></p>
        </template>
        <template v-if="ddd.type == 'photo'">
          <p v-for="val in ddd.option" class="result"><img v-bind:src="val"/></p>
        </template>
      </div>
    </form>
  </div>
</template>
<script type="text/javascript">
  export default {
      created(){
          console.log(1);
          this.ctype = this.$route.params.ctype;
          this.id = this.$route.params.id;
          this.family_id = this.$route.params.family_id;
          this.getData();
      },
      ready(){
          headNav(0);
      },
      data(){
          return {
              data: {},
              ctype:0,
              id:0,
              family_id:0
          }
      },
  
      methods: {
          getData(){
              this.$http({
                  url: 'card_detail',
                  method: 'get',
                  params: {ctype: this.ctype, id: this.id, family_id: this.family_id}
              }).then(function (res) {
                  this.$set('data', res.data.data);
              })
          },
      }
  }
</script>