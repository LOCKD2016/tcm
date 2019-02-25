<template lang='jade'>
.fixbody
    header
        .left(onclick="back()")
            i.icon-arrow-left
        .center 管理收货地址
    #wrap
        .panel.address_list(v-for="(it,ind) in lists" v-bind:type="it.is_default")
            .phone {{it.mobile}}
            .name {{it.user_name}}
            .addr {{it.province}}{{it.city}}{{it.district}}{{it.address}}
            .clearfix
                .left(@click="defType(ind,it.id)")
                    i.icon-check-c
                    span 默认地址
                .right
                    .btn.btn-o(@click="dets(it.id)") 编辑
                    .btn.btn-o(@click="dels(it.id)") 删除

        router-link.btn.btn-block(to="/my_address/my/myinfo/id" tag="a")
            i.icon-plus
            span 添加收货地址
</template>
<script>
    import {errorMsg} from '../../vuex/store';
    export default {
        data() {
            return{
                lists:[]
            }
        },
        created:function () {
            this.getAddress();
        },

        methods:{
            getAddress:function () {
                this.$http({url:this.$store.state.apiUrl+'address/lists', method:'GET'}).then(function(res){
                    this.lists = res.data.data;
                })
            },
            defType:function(i,id){
                this.$http.put(this.$store.state.apiUrl+'address/setDefault/'+id).then(function(res){
                    if(res.data.status ==1){
                        var _self = this;
                        for(var xs in _self.lists){
                            if(xs==i){
                                _self.lists[xs].is_default = 1
                            }else{
                                _self.lists[xs].is_default = 0
                            }
                        }
                    }
                },function (res) {
                    errorMsg(res.data.data.errors);
                });
            }
            ,dets:function(id){
                this.$router.push({path:'/my_address/my/myinfo/id',query: { id: id }});
            }
            ,dels:function(id){
                var yes = confirm('确定要删除吗？');
                if(yes){
                    var _self = this;
                    this.$http.delete(this.$store.state.apiUrl+'address/delete/'+id).then(function(res){
                        if(res.data.status ==1){
                            this.getAddress();
                        }
                    },function (res) {
                        errorMsg(res.data.data.errors);
                    });
                }
            }

        }
    };
</script>
