
<template>
  <div class="tit_nav">
    <div class="container">
      <div class="pull-left">患者管理 > {{data.family}}就诊记录
        <label></label>
      </div>
    </div>
  </div>
  <div class="container main_warp">
    <div class="new_item">
      <form role="form" class="form-horizontal">
        <div class="form-group">
          <div class="col-sm-3">
            <p>共有 {{data.count}} 次就诊记录</p>
          </div>
        </div>
      </form>
    </div>
    <div v-for="val in data.clinic" class="new_item">
      <form role="form" class="form-horizontal">
        <div class="form-group">
          <label for="" class="col-sm-1 control-label">就诊时间：</label>
          <div class="col-sm-3">
            <p>{{val.created_at}}</p>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label">就诊方式：</label>
          <div class="col-sm-3">
            <p>{{val.recipe_status}}</p>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label">医师：</label>
          <div class="col-sm-3">
            <p>{{val.doctor}}</p>
          </div>
        </div>
        <div class="form-group">
          <label for="" class="col-sm-1 control-label">诊断详情：</label>
          <div class="col-sm-3">
            <p>{{val.reply_content}}</p>
          </div>
        </div>
        <div v-if="val.recipe.length" v-for="item in val.recipe" class="form-group">
          <label for="" class="col-sm-1 control-label">处方：</label>
          <div class="col-sm-3">
            <p>{{item.recipe_head}} 副</p>
          </div>
          <div class="col-sm-3">
            <p>{{item.recipe}}</p>
          </div>
          <div class="col-sm-3">
            <p>医嘱: {{item.recipe_remark}}</p>
          </div>
        </div>
        <div v-if="val.comment.length" v-for="item in val.comment" class="form-group">
          <label for="" class="col-sm-1 control-label">评价：</label>
          <div class="col-sm-5">
            <p>态度</p>
            <div v-bind:class="item.manner"></div>
          </div>
          <div class="col-sm-5">
            <p>疗效</p>
            <div v-bind:class="item.effect"></div>
          </div>
          <div class="col-sm-3">
            <p>医嘱: {{item.content}}</p>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      created(){
          this.user_id = this.$route.params.user_id;
          this.family_id = this.$route.params.family_id;
          this.getData();
      },
      ready(){
  
      },
      data(){
          return {
              user_id:0,
              family_id:0,
              data:{}
          }
      },
      methods: {
          getData(){
              this.$http.get('appuser/clinic/' + this.user_id+'/'+this.family_id).then(function (res) {
                  this.$set('data', res.data);
                  console.log(this.data);
              });
          }
      }
  }
</script>