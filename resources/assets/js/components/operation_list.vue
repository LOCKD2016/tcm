
<template>
  <div class="tit_nav">
    <div class="container clearfix">
      <div class="pull-left">操作日志</div>
    </div>
  </div>
  <div class="container main_warp">
    <!--form.form-horizontal(role='form')-->
    <!--    .form-group-->
    <!--        label.col-sm-1.control-label.zdfl  关键词搜索：-->
    <!--        .col-sm-4-->
    <!--            .input-group-->
    <!--                input.form-control.auto_inp(type="search", v-model="name",placeholder="请输入关键词")-->
    <!--                .input-group-btn(@click="getService(1)")-->
    <!--                    .btn.btn-default-->
    <!--                        i.icon-search-->
    <!--                .pull-right.col-sm-9-->
    <!--                    .search_num-->
    <!--                        .more_search.btn.btn-default-->
    <!--                            span 共 {{total}} 条记录-->
    <div class="new_item">
      <div class="item_list">
        <div class="list">
          <div class="find_table_box table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>序号</th>
                  <th>发送人</th>
                  <th>操作内容</th>
                  <th>接收人</th>
                  <th>添加时间</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(index,d) in data">
                  <td>{{index+1}}</td>
                  <td>{{d.send_people}}</td>
                  <td>{{d.operation_detail}}</td>
                  <td>{{d.receive_people}}</td>
                  <td>{{d.created_at}}</td>
                  <td class="com_new"><a @click="del(d.id)" class="btn btn-primary">删除</a><span class="control">|</span><a v-if="d.read_flag==0" @click="read(d.id)" class="btn btn-primary">标记为已读</a><a v-else="v-else" class="btn btn-primary">已读</a></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <paginate :cur.sync="cur" :all.sync="all" v-on:btn-click="listen" v-if="cur" v-on:gopage="listen"></paginate>
  </div>
</template>
<script type="text/javascript">
  export default {
      created(){
          this.getOperations(1);
      },
      data(){
          return {
              data:{},
              cur: 0,
              all: 0,
              total: 0,
              name:'',
              status: 0
          }
      },events: {
          operation(){
              this.getOperations(1);//222
          }
      },
      methods: {
          getOperations(page){
              this.$http({
                  url: 'operation/list',
                  method: 'GET',
                  params: {page: page}
              }).then(function (res) {
                  this.$set('data', res.data.data);
                  var pagination = res.data.meta.pagination;
                  this.$set('cur', pagination.current_page);
                  this.$set('all', pagination.total_pages);
                  this.$set('total', pagination.total);
              });
          },
          listen(data){
              this.getOperations(data);
          },
          del(id){
              this.$http.delete('operation/del/' + id).then(function (res) {
                  if (res.data.status == 1) {
                      layer.msg(res.data.msg);
                      this.$dispatch('operation');
                      this.$dispatch('count');
                  } else {
                      layer.msg(res.data.msg);
                  }
              });
          },
          read(id){
              this.$http.get('operation/read/' + id).then(function (res) {
                  layer.msg(res.data.msg);
                  this.$dispatch('operation');
                  this.$dispatch('count');
              });
          }
      }
  }
</script>