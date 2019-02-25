
<template>
  <div class="tit_nav">
    <div class="container clearfix">
      <div class="pull-left">客服手机号列表</div>
      <div class="pull-right"><a onclick="itemPop(undefined,'telephone')" class="btn btn-primary btn-sm"><i class="icon-plus"></i><span>添加客服手机号</span></a></div>
    </div>
  </div>
  <div class="container main_warp">
    <div class="user_table_box table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th class="col-sm-1">序号</th>
            <th class="col-sm-2">客服姓名</th>
            <th class="col-sm-2">诊所</th>
            <th class="col-sm-2">客服手机号</th>
            <th class="col-sm-2">创建时间</th>
            <th class="col-sm-1">客服状态</th>
            <th class="col-sm-2">操作功能</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="d in data">
            <td>{{d.id}}</td>
            <td>{{d.kname}}</td>
            <td>{{d.clinique?d.clinique.name:''}}</td>
            <td>{{d.telephone}}</td>
            <td>{{d.created_at}}</td>
            <td>
              <select v-model="d.status" @change="updatestatus(d.id)" class="form-control">
                <option value="0">不在线</option>
                <option value="1">在线</option>
              </select>
            </td>
            <td><span v-on:click="del(d.id)" style="color:red">删除</span></td>
          </tr>
        </tbody>
      </table>
      <pop-telephone></pop-telephone>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      created(){
          this.getTelephone(1);
      },
      ready(){
          headNav(5);
      },
      data(){
          return {
              data:{}
          }
      },
      events:{
          telphone(){
              this.getTelephone();
          }
      },
      methods: {
          getTelephone(){
              this.$http.get('tel/getTelephone').then(function(res){
                  if(res.data.status){
                      this.data=res.data.data;
                  }else{
                      layer.msg(res.data.msg);
                  }
  
              })
          },
          del(telId){
              var  vue = this;
              layer.confirm('您确定删除？', {
                  btn: ['确定', '取消'] //按钮
              }, function () {
                  vue.$http.delete("tel/delTelephone/" + telId).then(function (res) {
                      if (res.data.status == 1) {
                          layer.msg(res.data.msg);
                          vue.$dispatch("telphone");
                      } else {
                          layer.msg(res.data.msg);
                      }
                  });
              }, function () {
                  layer.msg('取消成功!');
              });
          },
          updatestatus(id){
                this.$http.get('tel/updatestatus/'+id).then(function (res) {
                  layer.msg(res.data.msg);
                })
          }
      }
  }
</script>