<template lang='jade'>
.fixbody
    header
        .left(onclick="back()")
            i.icon-arrow-left
        .center 查看标准问诊单
    #wrap.doctor_order
        //- ul.list-group
        //-     li
        //-         span.diseasename 疾病
        //-         .val
        //-             input(type="text" v-model="info.disease")

        .panel(v-if='info.type==1')
            h3 疾病
            .txt
                p {{ info.disease }}

        .panel
            h3 症状描述
            .txt
                p {{ info.desc }}

        .panel(v-if='info.type==0')
            h3 症例
            .txt
                ul
                    li(v-for='item in info.disease')
                        img.diseasepic(:src='item')
</template>
<script>

    export default {
        data(){
            return{

                id: 0,
                info: {}     

            }
        },
        created(){
            this.id = this.$route.query.id;
        },
        
        methods:{
            getStandard:function () {
                this.$http({url:this.$store.state.apiUrl+'inquiry/detail/'+this.id, method:'GET'}).then(function(res){
                    this.info = res.data.data;
                })
            }
        },

        watch:{
            id(val){
                if(val>0){
                  this.getStandard();
                }
            }
        }
    };
</script>
