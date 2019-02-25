
<template>
  <div class="container main_warp">
    <div class="tit_nav">
      <div class="container clearfix">
        <div class="pull-left">中药剂型管理</div>
      </div>
    </div>
    <div class="search_box">
      <dl>
        <dt>筛选</dt>
        <dd class="row">
          <div class="col-sm-3">
            <div class="input-group">
              <input type="search" v-model="name" placeholder="输入药品名称搜索" class="form-control auto_inp"/>
              <div v-on:click="getData(1)" class="input-group-btn">
                <div class="btn btn-default"><i class="icon-search"></i></div>
              </div>
            </div>
          </div>
          <div class="col-sm-2"><span>共 {{total}} 条记录</span></div>
        </dd>
      </dl>
    </div>
    <div class="user_table_box table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>序号</th>
            <th>名称</th>
            <th>单位</th>
            <th>价格（元）</th>
            <th>类型</th>
            <!--th 操作-->
          </tr>
        </thead>
        <tbody>
          <tr v-for="val in data">
            <td>{{$index+1}}</td>
            <td>{{val.name}}</td>
            <td>{{val.unit}}</td>
            <td>{{val.amount}}</td>
            <td>{{val.type}}</td>
            <!--td-->
            <!--    span(@click="save(val)") 修改-->
            <!--    span(@click="deletes(val.id)",style="color:red;") 删除-->
          </tr>
        </tbody>
      </table>
    </div>
    <paginate :cur.sync="cur" :all.sync="all" v-on:btn-click="listen" v-if="cur" v-on:gopage="listen">
      <medicinal_type :val.sync="val"></medicinal_type>
    </paginate>
  </div>
</template>
<script type="text/javascript">
  import medicinal_type from  './module/medicinal_type.vue'
  export default {
      components:{
          medicinal_type
      },
      data(){ //页面用到的数据
          return {
              data:{},
              val:{},
              name:'',
              cur:'',
              total:'',
              all:'',
          };
      },
      created(){
          headNav(4);
          this.getData();
      },
      events: {
          userupdate(){
              this.getData();
          }
      },
      methods:{
          getData(page=''){
              if (page) {
                  this.page = page;
              }
              this.$http({url:'medicine/index',method:'GET',params:{page: this.page,name:this.name}}).then(function(res){
                  this.$set('data', res.data.data);
                  var pagination = res.data.meta.pagination;
                  this.$set('cur', pagination.current_page);
                  this.$set('all', pagination.total_pages);
                  this.$set('total', pagination.total);
              });
          },
          deletes(id){
              this.$http({url: 'medicine/del/'+id, method: 'delete'}).then(function (res) {
                  layer.msg(res.data.msg);
                  if(res.data.status){
                      this.getData();
                  }
              });
          },
          save(val){
              this.$set('val',val);
              $("#medicinal_type").modal("show");
          },
          listen(data) {
              this.getData(data);
              this.$router.go({name: 'medicinal_type', params: {id: data}});
          },
      }
  }
</script>