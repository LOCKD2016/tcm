<template lang='jade'>
.fixbody
    header
        .left(onclick="back()")
            i.icon-arrow-left
        .center 取穴
    #wrap
        .tips(v-if="note != 0" onclick="$('.pop').fadeIn()")
            //i.icon-sound
            .icon-tit
              i
            span 贴穴位前
            span.cor 请点击查看
            span 注意事项
        .tips
            //i.icon-sound
            .icon-tit
              i
            span 贴敷必读
            span.cor(@click="tfbd(id)") 请点击查看 >
        .pop
            .box
                .head.bz
                    i.icon-warning
                    span {{note}}

                .foot(onclick="$('.pop').fadeOut()") 我知道了

        ul.point_box
            li.clearfix(v-for="re in res",@click="find(re.id)")
                span {{re.name}}
                i.icon-arrow-right.r
</template>
<script>
    export default{
        data() {
            return{
                id:0,
                note:'',
                res:[],
                msg:"",
                goods_id: 1
            }
        },
        created(){
            this.id = this.$route.query.id;
            //this.getPoint();
        },
        watch: {
          id(newValue){
              this.getPoint(newValue);
          }
        },
        methods:{
            getPoint(id){
                var self = this;
                if(!id){
                    layer.msg('数据错误');
                    return false;
                }
                this.$http.get(self.$store.state.apiUrl+'point/'+ id).then(function (res) {
                    if(res.data.status){
                        self.res = res.data.data.point_arr;
                        self.note = res.data.data.note;
                        self.goods_id = res.data.data.goods_id;
                    }else{
                        layer.msg(res.data.msg);
                    }

                });
            },
            find(id){
                this.$router.push({path:'/point_img',query: { path: id }});
            },
            tfbd(id){
                if(id > 2022){
                    this.$router.push({path:'/tfbd_new'});
                }else{
                    this.$router.push({path:'/tfbd'});
                }
            }
        }
    }
</script>
