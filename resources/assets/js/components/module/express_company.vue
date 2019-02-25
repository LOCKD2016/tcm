
<!--覆盖-->
<template>
  <div id="express_company" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">快递公司</h4>
        </div>
        <div class="modal-body">
          <div class="find_table_box table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>快递公司</th>
                  <th>快递公司编号</th>
                </tr>
              </thead>
              <tbody v-if="data.resultcode == 200">
                <tr v-for="l in data.result">
                  <td>{{l.com}}</td>
                  <td>{{l.no}}</td>
                </tr>
              </tbody>
              <tbody v-if="data.resultcode != 200">
                <tr>
                  <td>{{data.reason}}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      created(){
          this.getData();
      },
      data(){
          return {
              data: {}
          };
      },
      methods: {
          getData(){
              this.$http.get('express/company').then(function (res) {
                  this.data = res.data;
              })
          }
      }
  }
</script>