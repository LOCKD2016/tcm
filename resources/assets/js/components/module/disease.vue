
<template>
  <div id="disease" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
        </div>
        <div class="modal-body">
          <div style="margin-bottom: 15px;" class="col-sm-12">
            <div class="col-sm-4">
              <input type="text" v-model="name" class="form-control"/>
            </div>
            <div class="col-sm-1">
              <button type="button" @click="add()" class="btn btn-primary">添加</button>
            </div>
          </div>
        </div>
        <table class="user_table_box table-responsive table table-bordered">
          <thead>
            <tr>
              <th class="col-sm-1">序号</th>
              <th class="col-sm-1">名称</th>
              <!--th.col-sm-1 排序-->
              <th class="col-sm-1">操作</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="val in val.disease">
              <td>{{1+$index}}</td>
              <td>{{val.name}}</td>
              <!--td
              span(@click="sort(val.id,1)") ↑
              span(@click="sort(val.id,-1)") ↓
              -->
              <td><span @click="del(val.id,val.name)">删除</span></td>
            </tr>
          </tbody>
        </table>
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      props:['val'],
      data(){
          return {
              data:{},
              name:'',
          };
      },
      methods:{
          add(){
              this.$http({url: 'disease/create',method:"POST",params:{section_id:this.val.id,name:this.name}}).then(function (res) {
                  if (res.data.status == 1) {
                      this.val.disease.push({id:res.data.data,name:this.name});
                      console.log(this.val.disease);
                      this.name = '';
                  } else {
                      layer.msg(res.data.msg);
                  }
              });
          },
  
          del(id,name){
              var _this = this;
              var confirm = layer.confirm('您确定删除 '+ name+'？', {
                  btn: ['确定', '取消']
              }, function () {
                  _this.$http({url: 'disease/diseasedel/' + id, method: "delete"}).then(function (res) {
                      if (res.data.status == 1) {
                          for (var i = 0; i < _this.val.disease.length; i++) {
                              if (_this.val.disease[i].id == id) {
                                  _this.val.disease.splice(i, 1);
                              }
                          }
                      } else {
                          layer.msg(res.data.msg);
                      }
  
                  });
                  layer.close(confirm);
              });
  
          },
      },
      watch:{
          'id':function(value){
              if(value>0){
                  this.getDetail(value);
              }
          }
      }
  }
</script>