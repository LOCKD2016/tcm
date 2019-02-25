<template lang='jade'>
.fixbody
    header
        .left(onclick="back()")
            i.icon-arrow-left
        .center 疗效反馈
    .btn.btn-fix(@click="save()") 提交
    #wrap.doctor_order
        ul.list-group
            li
                span 疾病
                .val {{info.disease}}
            //li
                span 就诊人
                .val {{family.name}}
        .panel
            h3 评价（
              span#num 0
              | /300）
            .txt
                textarea(placeholder="每一位患者的真实评价，都能帮助到更多的患者！ 感谢您的每一次评价。" v-model="content" maxlength="300" onkeyup="wordStatic(this);")


            .txt
                ul.list-group.fg
                    li
                        span 疗效
                        .val.val_w
                          label(v-for="c in conditions" v-bind:class="condition_set == c.id ? 'active' : ''" @click="status(c.id,c.name)") {{c.name}}

                    li
                        span 态度
                        .val
                          i.icon-nav1(v-for='(i,index) in 5' @click="star(i)" value='i' v-bind:class='manner>=i?"active":""')
</template>
<script>
    import {errorMsg} from '../../vuex/store';
    export default {
        data(){
            return{
                info:[],
                family:[],
                condition_set:1,
                content:'',
                condition:'痊愈',
                manner:5,
                conditions:[
                    {
                        id:1,
                        name:'痊愈'
                    },
                    {
                        id:2,
                        name:'有效'
                    },
                    {
                        id:3,
                        name:'无效'
                    },
                    {
                        id:4,
                        name:'恶化'
                    }
                ],
            }
        },
        created(){
            this.id = this.$route.query.id;
            this.getInfo();
        },

        methods:{
            getInfo(){
                this.$http({url:this.$store.state.apiUrl+'clinic/detail/'+ this.id,method:'GET'}).then(function (res) {
                    if(res.data.status){
                        this.info = res.data.data;
                    }else{
                      $api.pop(res.data.msg);
                    }
                });
            },
            save(){
                if(this.condition_set==0){
                    $api.pop('请选择疗效');
                    return false;
                }if(this.manner==0){
                    $api.pop('态度最少为1');
                    return false;
                }
                var params  = {};
                params.condition =this.condition_set;
                params.manner =this.manner;
                params.content =this.content;
                this.$http.post(this.$store.state.apiUrl+'comment/save/'+this.id, params).then(function (res) {
                  $api.pop(res.data.msg);
                  if(res.data.status){
                      this.$router.back();
                    }
                },function (response) {
                  errorMsg(response.data.data.errors);
                });
            },
            status(ind, condition){
                this.condition_set = ind;
                this.condition = condition;
                console.log(this.condition);
                console.log(this.condition_set);
            },
            star(val){

                // for(var i=0;i<5;i++){
                //     if($('.star').eq(i).attr('value') <= val){
                //         $('.star').eq(i).addClass('active');
                //     }
                //     if($('.star').eq(i).attr('value') > val){
                //         $('.star').eq(i).removeClass('active');
                //     }
                // }
                this.manner = val;
            }
        }
    };
</script>
