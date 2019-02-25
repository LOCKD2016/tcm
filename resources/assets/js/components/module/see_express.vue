
<!--物流状态-->
<template>
  <div id="see_express" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">物流状态</h4>
        </div>
        <div class="modal-body">
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <label class="col-sm-2 control-label"><span>物流公司：</span></label>
              <div class="col-sm-10">
                <input type="text" v-model="order.express_company" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><span>物流单号：</span></label>
              <div class="col-sm-10">
                <input type="text" v-model="order.express_number" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><span>快递进度：</span></label>
              <div v-if="data.resultcode == 200" class="col-sm-10">
                <div v-for="mes in list" class="lists">
                  <p class="time">{{mes.datetime}}</p>
                  <p>{{mes.remark}}</p>
                </div>
              </div>
              <div v-if="data.resultcode != 200" class="col-sm-10">
                <div class="lists">
                  <p>{{data.reason}}</p>
                </div>
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
      props: ['order'],
      watch:{
          order(){
              this.getData();
          }
      },
      data(){
          return {
              data: {},
              list: []
          };
      },
      methods: {
          getData(){
              this.$http.get('express/see/'+this.order.id).then(function (res) {
                  this.data = res.data;
                  if(res.data.result && res.data.result.status){
                      this.list = res.data.result.list;
                  }
              })
          }
  
      }
  }
</script>