<template lang='jade'>
#wrap
    header
        .left(onclick="back()")
            i.icon-arrow-left
        .center 就诊人

    .tips
        //i.icon-sound
        .icon-tit
          i
        span 家庭卡可以绑定5个就诊人，VIP卡可以绑定9个就诊人

    ul.list-group
        li(v-if="status==0")
            a {{lists.nickname}}
            i.icon-arrow-right.i_wid(@click="add()")
        li(v-else v-for="(it,index) in lists")
            a {{it.realName}}
                span.swjt(v-if="vip > 0 && it.type == 0" @click="swjt(index)") 绑定会员卡
                span.swjt(v-if="vip > 0 && it.type == 1") {{it | attr(it)}}
            i.icon-arrow-right.i_wid(@click="det(it.id)")
    .btn.btn-block(@click="addFamily()")
        i.icon-plus
        span 添加就诊人

    .layer_pop.jtcy.none
        .content
            .txt 您要把
              span {{realname}}
              | 加入到你的VIP卡中，
            .txt 享受对应折扣服务，
            .txt 设为VIP成员后不可修改和删除。
            .pop_btn.clearfix
                .p_btn.l(@click="canceldel()") 取消
                .p_btn(@click="confirm()") 确认
</template>
<script>
    export default {
        data() {
            return{
                lists:{},
                info:{},
                name:'',
                realname:'',
                status:0,
                id:0,
                indextmp:0,
                vip:0,
            }
        },
        created:function () {
            this.getFamily();
        },
        filters:{
            attr(value){
                //[0:配偶|1:子女|2:父母|3:亲戚|4:朋友|5:本人]
                switch (value.attr){
                    case 0:
                        return '配偶';
                        break;
                    case 1:
                        return '子女';
                        break;
                    case 2:
                        return '父母';
                        break;
                    case 3:
                        return '亲戚';
                        break;
                    case 4:
                        return '朋友';
                        break;
                    case 5:
                        return '本人';
                        break;
                }
            }
        },
        methods:{
            getFamily:function () {
                this.$http({url:this.$store.state.apiUrl+'family/lists', method:'GET'}).then(function(res){
                    this.status = res.data.status;
                    this.lists = res.data.data;
                    this.vip = window.localStorage.getItem("vip");
                })
            },
            addFamily(){
                this.$http.get(this.$store.state.apiUrl+'users/integral').then(function (res) {
                    if(res.data.status == 1) {
                        this.$router.push({path: '/my_famliadd/my'});
                    }else{
                        $api.pop(res.data.msg);
                    }
                })
            }
            ,det:function(id){
                this.$router.push({path:'/my_fmld/my' ,query: { id: id }});
            }
            ,add:function(){
                this.$router.push({path:'/my_famliadd/my',query: { type: 1 }});
            },
            swjt(index){
                this.realname = this.lists[index].realName;
                this.id = this.lists[index].id;
                this.indextmp = index;
                $('.layer_pop').removeClass('none');
            },
            canceldel(){
                $('.layer_pop').addClass('none');
            },
            confirm(){
                this.$http({url:this.$store.state.apiUrl+'family/setpatient/'+this.id, method:'GET'}).then(function(res){
                    if(res.data.status == 1){
                        $api.pop('绑定成功');
                        this.lists[this.indextmp].type = 1;
                    }else{
                        $api.pop(res.data.msg);
                    }
                });
                $('.layer_pop').addClass('none');
            }

        }
    };
</script>

