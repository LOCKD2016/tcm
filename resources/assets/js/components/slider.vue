
<template>
  <div class="tit_nav">
    <div class="container">
      <div class="pull-left">轮播图</div>
      <div class="pull-right"><a onclick="itemPop(undefined,'slider_add')" class="btn btn-primary btn-sm"><i class="icon-plus"></i><span>添加轮播</span></a></div>
    </div>
  </div>
  <div class="container main_warp">
    <div class="user_table_box table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th class="col-sm-1">名称</th>
            <th class="col-sm-1">图片</th>
            <th style="width:50%" class="col-sm-2">简介</th>
            <th class="col-sm-2">跳转地址</th>
            <th class="col-sm-1">是否展示</th>
            <th class="col-sm-1">操作功能</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="val in data">
            <td>{{val.title}}</td>
            <td><img v-bind:src="val.image" style="width:128px;height:60px"/></td>
            <td>{{val.desc}}</td>
            <td>{{val.url}}</td>
            <td>
              <select v-model="val.status" v-on:change="save(val)" class="form-control">
                <option value="1">展示</option>
                <option value="0">不展示</option>
              </select>
            </td>
            <td><span @click="detail(val)">编辑</span><span @click="_delete(val.id)" style="color:red;">删除</span></td>
          </tr>
        </tbody>
      </table>
      <slider_add></slider_add>
      <slider_detail :val.sync="val"></slider_detail>
    </div>
  </div>
</template>
<script type="text/javascript">
  import slider_add from "./module/slider_add.vue"
  import slider_detail from "./module/slider_detail.vue"
  export default{
      components: {
          slider_add,
          slider_detail
      },
      data(){
          return {
              data: {},
              val:{}
          };
      },
      created(){
          this.getData();
      },
      ready(){
          headNav(4);
      },
      events: {
          refreshList(){
              this.getData();
          }
      },
      methods: {
          getData(){
              this.$http({url: 'slider/index', method: 'GET'}).then(function (res) {
                  this.$set('data', res.data.data);
              });
          },
          _delete(id){
              this.$http({url: 'slider/del/'+id, method: 'delete'}).then(function (res) {
                  if(res.data.status){
                      layer.msg('操作成功');
                      this.getData();
                  }
              });
          },
          detail(val){
              this.$set('val', val);
              $("#slider_detail").modal("show");
          },
          save(val){
              this.$http.put('slider/update', val).then(function (res) {
                  if (res.data.status) {
                      layer.msg('操作成功');
                      this.getData();
                  }
              });
          }
      }
  }
</script>