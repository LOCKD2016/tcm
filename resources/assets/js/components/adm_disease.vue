
<template>
  <div class="tit_nav">
    <div class="container">
      <div class="pull-left">常见疾病</div>
      <div class="pull-right"><a @click="add()" class="btn btn-primary btn-sm"><i class="icon-plus"></i><span>添加疾病</span></a></div>
    </div>
  </div>
  <div class="container main_warp">
    <div class="user_table_box table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>疾病名称</th>
            <th>图标</th>
            <th>操作功能</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="val in data">
            <td>{{val.disease.name}}</td>
            <td><img v-bind:src="val.icon" style="width:120px;"/></td>
            <td><span @click="detail(val.id)">修改</span><span @click="deletes(val.id)" style="color:red;">删除</span></td>
          </tr>
        </tbody>
      </table>
    </div>
    <diseasecommon :id.sync="id"></diseasecommon>
    <diseasecomadd></diseasecomadd>
  </div>
</template>
<script type="text/javascript">
  import diseasecommon from  './module/disease_common.vue'
  import diseasecomadd from  './module/disease_com_add.vue'
  export default{
      components: {
          diseasecommon,
          diseasecomadd
      },
      data(){
          return {
              cur: 0,
              all: 0,
              data:{},
              id:0
          };
      },
      created(){
          this.getData();
      },
      events: {
          update(){
              this.getData();
          }
      },
      ready(){
          headNav(5);
      },
      methods:{
          deletes(id){
              this.$http({url: 'disease_common/'+id, method: 'delete'}).then(function (res) {
                  if(res.data.status){
                      this.getData();
                  }
              });
          },
          add(){
              $('#diseasecomadd').modal('show');
          },
          detail(id){
              this.id = id;
              $('#diseasecommon').modal('show');
          },
          getData(){
              this.$http({url: 'disease_common', method: 'GET'}).then(function (res) {
                  this.$set('data', res.data.data);
              });
          }
      }
  }
</script>