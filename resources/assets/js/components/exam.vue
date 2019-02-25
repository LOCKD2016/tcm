
<template>
  <div class="tit_nav">
    <div class="container clearfix">
      <div class="pull-left">系统问诊单</div>
    </div>
  </div>
  <div class="container main_warp">
    <div class="box_css">
      <!--a.btn.btn-primary.btn-sm(onclick="itemPop(#{i},'addtest')")-->
      <!--    span 添加问题-->
      <div class="new_item">
        <div class="user_table_box table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>序号</th>
                <th>名称</th>
                <th>时间</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="dd in data">
                <td>{{$index +1}}</td>
                <td>{{dd.title}}</td>
                <td>{{dd.created_at}}</td>
                <td><span @click="edit(dd.id)">编辑</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
  export default {
      ready(){
          headNav(4);
      },
      created(){
          this.getData();
      },
      data(){
          return {
              data:{},
          }
      },
      methods: {
          edit(id){
              this.$router.go({name:'exam_save',params:{id:id}});
          },
          getData(){
              this.$http.get('exam').then(function (res) {
                  this.$set('data',res.data.data);
              });
          },
      }
  }
</script>