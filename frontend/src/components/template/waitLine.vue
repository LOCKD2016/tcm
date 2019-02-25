<template lang='jade'>
.fixbody
    header
        .left(onclick="back()")
            i.icon-arrow-left
        .center
            span 排队等号
    #patSet.popp
        .box
            .head.dz
                span 选择就诊人
                a(v-if="long>0" @click="add()") 就诊人管理
            .main
                ul
                    li(v-if="long<1")
                        h3
                            span 尚未添加就诊人，
                            a(@click="add()") 现在添加
                    li(v-else v-for="(a,ind) in patient" v-bind:class="patient_set == ind ? 'active':''" @click="setPat(ind,a.id)")
                            h4 {{a.realName}}
                            p {{a.sex}}，{{a.age}}岁
                            i.icon-check-c
                .foot(onclick="$('#patSet').fadeOut()") 确定

    #wrap
        .wait
          .bg
          i.img
          p 当前排队人数为
            span {{num}}
            |人
        .tips
            .icon-tit
              i
            span 预约号正常释放后系统会第一时间通知排队号
        ul.list-group
            li(onclick="$('#patSet').fadeIn()")
                span 就诊人
                .val {{realName}}
                i.icon-arrow-right
        .btn.btn-block(@click="save()")
            span 排队
        .btn.btn-block.btn_cor(@click="back()")
            span 取消
</template>
<script>
    export default{
        data(){
            return{
                long:0,
                num:0,
                patient: {},
                patient_set:0,
                realName:'',
                age:'',
                sex:'',
                patient_id:0,

            }
        },
        created(){
            this.id = this.$route.query.id;
            this.date = this.$route.query.date;
            this.cliniqueId = this.$route.query.clinicId;
            this.getFamily();
            this.getNum();
        },
        methods:{
            getNum(){
                this.$http({url:this.$store.state.apiUrl+'queue/num/'+ this.cliniqueId + "/" + this.id + "/" + this.date, method:'GET'}).then(function(res){
                    if(res.data.status){
                        this.num = res.data.data;
                    }else{
                        $api.pop(res.data.msg);
                    }
                })
            },
            getFamily:function () {
                this.$http({url:this.$store.state.apiUrl+'family/lists', method:'GET'}).then(function(res){
                    if(res.data.status){
                        this.long = res.data.status;
                        this.patient = res.data.data;
                        this.realName = this.patient[0].realName;
                        this.patient_id = this.patient[0].id;
                    }else{
                        this.long = res.data.status;
                    }
                })
            },
            save() {
                this.$http({url:this.$store.state.apiUrl+'queue/save',method:'POST',params:{doctor_id:this.id,clinique_id:this.cliniqueId,date:this.date,family_id:this.patient_id}}).then(function (res) {
                    if(!res.data.status){
                        $api.pop(res.data.msg);
                    }else{
                        this.$router.push({path:'/waitline_record'});
                    }
                });
            },
            setPat:function(id,p_id){
                this.patient_set = id;
                this.patient_id = p_id;
                this.realName = this.patient[id].realName;
                this.age = this.patient[id].age;
                this.sex = this.patient[id].sex;
            },
            add(){
                this.$router.push({path:'/my_myfml/my'});
            },
        },

    };
</script>
