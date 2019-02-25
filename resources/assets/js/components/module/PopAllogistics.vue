
<!--物流状态-->
<template>
  <div id="allogistics" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">物流状态</h4>
        </div>
        <div class="modal-body">
          <form role="form" class="form-horizontal">
            <div class="form-group">
              <label class="col-sm-2 control-label"><span>物流公司：</span></label>
              <div class="col-sm-10">
                <input type="text" v-model="send.logistics" class="form-control"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><span>物流单号：</span></label>
              <div class="col-sm-10">
                <input type="text" v-model="send.logistics_number" class="form-control"/>
              </div>
            </div>
            <!--.form-group-->
            <!--    label.col-sm-2.control-label-->
            <!--        //span(@click="getExp(send.deal_id)") 物流进度：-->
            <!--        span 物流进度：-->
            <!--    .col-sm-10(v-if="exp.resultcode == 200")-->
            <!--        .lists(v-for="mes in exp.result.list")-->
            <!--            p.time {{mes.datetime}}-->
            <!--            p {{mes.remark}}-->
            <!--    .col-sm-10(v-if="exp.resultcode != 200")-->
            <!--        .lists-->
            <!--            p {{exp.reason}}-->
            
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<!--block js-->
<!--    script(type="text/javascript").-->
<!--        export default {-->
<!--            props: ['id'],-->
<!--            created(){-->
<!--                this.id = this.$route.params.id;-->
<!--            },-->
<!--            data(){-->
<!--                return {-->
<!--                    send: {},-->
<!--                    exp:{},-->
<!--                    reason:{},-->
<!--                };-->
<!--            },-->
<!---->
<!--            methods: {-->
<!--                getSendDetail(id){-->
<!--                    if (id > 0) {-->
<!--                        this.$http.get('deal/sendetail/' + id).then(function (res) {-->
<!--                            this.$set('send', res.data.data);-->
<!--                        });-->
<!--                    }-->
<!--                },-->
<!--                //getExp(id){-->
<!--                //    if (id > 0) {-->
<!--                //        this.$http.get('deal/getExp/' + id).then(function (res) {-->
<!--                //            this.$set('exp', res.data);-->
<!--                //        });-->
<!--                //    }-->
<!--                //},-->
<!--                addSend(send){-->
<!--                    this.$http.post('addsend', send).then(function (res) {-->
<!--                        if (res.data.status == 1) {-->
<!--                            layer.msg(res.data.msg);-->
<!--                            this.$set('send', '');-->
<!--                        }-->
<!--                    }, function (res) {-->
<!--                        var data = res.data;-->
<!--                        layer.msg(data.msg);-->
<!--                    });-->
<!--                    $("#allogistics").modal("hide");-->
<!--                    this.$dispatch("update");-->
<!--                },-->
<!--                goback(){-->
<!--                    this.$router.go("/else_deliver");-->
<!--                },-->
<!--            },-->
<!--            watch: {-->
<!--                id(newValue){-->
<!--                    this.getSendDetail(newValue);-->
<!--                    //this.getExp(newValue);-->
<!--                },-->
<!--            }-->
<!---->
<!--        }-->