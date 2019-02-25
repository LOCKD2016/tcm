<template lang='jade'>
.fixbody
    header
        .left(@click="order()")
            i.icon-arrow-left
        .center 排队记录
    #wrap
        .swiper-container.swiper-container-list
            .orderBOX.swiper-wrapper
                .swiper-slide
                    .order_list(v-for="list in lists" v-on:click="getDetail(list.id)")
                        .mark
                        .head(v-if="list.status==0") 排队中
                        .head(v-if="list.status==1") 排队成功
                        .head(v-if="list.status==2") 排队取消
                        .main
                            .clearfix
                                .left 就诊人
                                .right {{list.family.name}}
                            .doctors
                                .avatar(v-bind:style="bg(list.doctor.avatar)")
                                h3 {{list.doctor.name}}
                                p {{list.doctor.title}}
                            .hospital
                                i.icon-location
                                h3 {{list.clinique.store}}
                                //p 北京市朝阳区高碑店华膳园国际传媒文化产业园3号楼A3
                    .visible 上拉加载

</template>
<script>
    export default {
        data(){
            return {
                page: 1,
                perPage: 0,
                lists:[],
                swiper:"",
                statusOrder:true
            }
        },
        mounted(){
            this.getList();
        },
        methods:{
            getList(){
                let self = this;
                let oLoadTip = $('.orderBOX').find('.visible');
                this.$http({url:this.$store.state.apiUrl+'queue/lists',method:'GET',params:{page:this.page}}).then(function (res) {
                    res.data.data.list.forEach(e =>{
                        self.lists.push(e);
                    })
                    self.page ++;
                    if(self.page> res.data.data.totalPage){
                        oLoadTip.html('没有更多数据了');
                        self.statusOrder = false;
                    } else {
                        oLoadTip.html('上拉加载');
                    }
                    self.$nextTick(function(){
                        var myswiper = new Swiper('.swiper-container', {
                            direction: 'vertical',
                            slidesPerView: 'auto',
                            observer: true,
                            observeParents: true,
                            mousewheelControl: true,
                            freeMode: true,
                            resistanceRatio : 0.7,
                            preventLinksPropagation : true,
                            preventClicks : true,
                            onTouchEnd: function(s){
                                var _viewHeight = s.height;
                                var _contentHeight = s.virtualSize;
                                if(s.translate <= _viewHeight - _contentHeight - 50 && s.translate < 0) {
                                    if(self.statusOrder){
                                        $(".visible").html('正在加载...');
                                        self.getList();
                                        setTimeout(function(){
                                            s.update();
                                        },400);

                                    }
                                }
                            }
                        });
                    });
                    oLoadTip.show();
                    this.total = res.data.data.perPage;
                });
            },
            events: {
                update(){
                    this.getList();
                    self.swiper.update();
                }
            },
            getDetail:function(id){
                this.$router.push({path:'/waitline_detail',query: { id: id}});
            },
            order:function(){
                this.$router.push({path:'/order'});
            },
            bg: function (url) {
                if (url) return 'background-image:url(' + url + ')'
            }
        }
    };
</script>
