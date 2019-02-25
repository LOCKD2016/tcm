
<template>
  <div class="tit_nav">
    <div class="container clearfix">
      <div class="pull-left">疾病管理</div>
      <div class="pull-right"><a @click="add()" class="btn btn-primary btn-sm"><i class="icon-plus"></i><span>添加科室</span></a></div>
    </div>
  </div>
  <div class="container main_warp">
    <div class="user_table_box table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th class="col-sm-1">序号</th>
            <th class="col-sm-1">名称</th>
            <!--th.col-sm-1 排序-->
            <th class="col-sm-1">是否展示</th>
            <th class="col-sm-1">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="val in data">
            <td>{{cur*10-9 + $index}}</td>
            <td>{{val.name}}</td>
            <td>
              <select v-model="val.status" v-on:change="save(val.id,val.status)" class="form-control">
                <option value="1">是</option>
                <option value="0">否</option>
              </select>
            </td>
            <!--td
            span(@click="sort(val.id,1)") ↑
            span(@click="sort(val.id,-1)") ↓
            -->
            <td><span @click="show(val)">查看疾病</span><span @click="updates(val)">修改</span><span @click="deletes(val.id)" style="color:red;">删除</span></td>
          </tr>
        </tbody>
      </table>
      <nav>
        <ul class="pagination">
          <paginate :cur.sync="cur" :all.sync="all" v-on:btn-click="listen" v-if="cur" v-on:gopage="listen"></paginate>
        </ul>
      </nav>
    </div>
    <disease :val.sync="val"></disease>
    <sectionadd></sectionadd>
    <sectionupdate :val.sync="val"></sectionupdate>
  </div>
</template>
<script type="text/javascript">
  import disease from "./module/disease.vue"
  import sectionadd from "./module/section_add.vue"
  import sectionupdate from "./module/section_update.vue"
  export default {
      components: {
          disease,
          sectionadd,
          sectionupdate
      },
      created(){
          this.page = this.$route.params.id;
          this.getData(1);
      },
      ready(){
          headNav(4);
      },
      events:{
          add(){
              this.getData(this.cur);
          }
      },
      data(){
          return {
              data: {},
              val: {},
              disease: {},
              cur: 0,
              all: 0,
              id:0
          }
      },
      methods: {
          save(id, status){
              var data = {};
              data.id = id;
              data.status = status;
              data.type = 'save';
              this.$http.put('section/update', data).then(function (res) {
                  if (res.data.status) {
                      layer.msg('操作成功');
                      this.getData();
                  } else {
                      layer.msg(res.data.msg);
                  }
              })
          },
          updates(val){
              this.$set('val',val);
              $('#section_update').modal('show');
          },
          deletes(id){
              this.$http({
                  url: 'section/del/' + id,
                  method: 'delete',
              }).then(function (res) {
                  if (res.data.status == 1) {
                      this.getData();
                  }
              });
          },
          add(){
              $('#sectionadd').modal('show');
          },
          sort(id,sort){
              this.$http({
                  url: 'section/'+id,
                  method: 'PUT',
                  params: {
                      type:'sort',
                      sort: sort
                  }
              }).then(function (res) {
                  if(res.data.status ==1){
                      this.getData();
                  }
              });
          },
          show(val){
              this.val = val;
              $("#disease").modal("show");
          },
          getData(page=''){
              if (page) {
                  this.page = page;
              }
              this.$http({url: 'section/index', method: 'GET', params: {page: this.page}}).then(function (res) {
                  this.$set('data', res.data.data);
                  this.$set('cur', res.data.meta.pagination.current_page);
                  this.$set('all', res.data.meta.pagination.total_pages);
              });
          },
          listen(data){
              this.getData(data);
              this.$router.go({name: 'section_admin', params: {id: data}});
          }
      }
  }
</script>