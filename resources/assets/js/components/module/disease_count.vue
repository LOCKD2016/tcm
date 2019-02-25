
<template>
  <div id="disease_count" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">疾病统计</h4>
        </div>
        <div class="modal-body">
          <div class="user_table_box table-responsive">
            <table class="table table-bordered check_list">
              <thead>
                <tr>
                  <th class="col-sm-1">疾病</th>
                  <th class="col-sm-1">痊愈数</th>
                  <th class="col-sm-1">明显好转</th>
                  <th class="col-sm-1">好转</th>
                  <th class="col-sm-1">没变化</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="data.length" v-for="val in data">
                  <td>{{val[0]}}</td>
                  <td>{{val[1]}}</td>
                  <td>{{val[2]}}</td>
                  <td>{{val[3]}}</td>
                  <td>{{val[4]}}</td>
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
      props: ['doctor_id'],
      data(){
          return {
              data: {}
          };
      },
      methods: {
          save(){
              this.$http.get('doctor/disease/' + this.doctor_id).then(function (res) {
                  this.$set('data',res.data.disease);
              });
          },
      },
      watch: {
          'doctor_id': function (value) {
              if(value){
                  this.save();
              }
          }
      }
  }
</script>