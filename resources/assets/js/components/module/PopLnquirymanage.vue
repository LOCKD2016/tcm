
<!--项目库管理-->
<template>
  <div id="lnquirymanage" class="modal fade">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">方案管理</h4>
        </div>
        <div class="modal-body">
          <table class="table table-bordered check_list">
            <thead>
              <tr>
                <th>序号</th>
                <th>就诊人</th>
                <th>购买类型</th>
                <th>处理人</th>
                <th>指定处理人</th>
                <th>创建日期</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(index,ln) in lnquiries">
                <td>{{index+1}}</td>
                <td>{{ln.realname}}</td>
                <td>{{ln.goods_type}}</td>
                <td>{{ln.doctor_id}}</td>
                <td>
                  <select v-model="project.shared_investors" v-on:change="people(project.project_id,project.shared_investors)" class="form-control">
                    <option v-for="u in roleUsers" v-bind:value="u.user_id">{{u.user_realname}}</option>
                  </select>
                </td>
                <td>{{ln.created_at}}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <nav class="clearfix">
          <ul class="pagination pages">
            <paginate :cur.sync="cur" :all.sync="all" v-on:btn-click="listen" v-if="cur"></paginate>
          </ul><span class="total">总数：{{num}}</span>
        </nav>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" v-on:click="dismiss()" class="btn btn-primary">取消</button>
          <button type="button" v-on:click="addMoney(2)" class="btn btn-primary">确定</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="javascript">
  export default{
      ready(){
          this.getLnquiry(1);
          this.getRoleUser();
      },
      data(){
          return{
              lnquiries:{},
              cur:0,
              all:0,
              num:0,
              roleUsers:{}
          }
      },
      events: {
          managelnquiry(){
              this.getLnquiry(this.cur);
              this.getRoleUser();
          }
      },
      methods: {
          dismiss(){
              this.getLnquiries(1);
          },
          add(id){
              if (this.checkedNames.indexOf(id) > -1) {
                  for (var i = 0; i < this.checkedNames.length; i++) {
                      if (this.checkedNames[i] == id) {
                          this.checkedNames.splice(i, 1);
                          break;
                      }
                  }
              } else {
                  this.checkedNames.push(id);
              }
          },
          getLnquiry(page){
              this.$http({
                  url: 'law/index',
                  method: 'GET',
                  params: {page: page}
              }).then(function (res) {
                  this.$set('lnquiries', res.data.data);
                  var pagination = res.data.meta.pagination;
                  this.$set('cur', pagination.current_page);
                  this.$set('all', pagination.total_pages);
                  this.$set('num', pagination.total);
              });
          },
          getRoleUser(){
              this.$http.get('user/roleUser').then(function (res) {
                  this.$set('roleUsers', res.data.data);
              });
          },
          listen(data){
              this.getLnquiry(data);
          },
      }
  }
</script>