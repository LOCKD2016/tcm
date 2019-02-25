<template lang='jade'>
.cfpy_history
    header
        .left(onclick="back()")
            i.icon-arrow-left
        .center 传方抓药

    #wrap
        .swiper-container.swiper-container-list
            .orderBOX.swiper-wrapper
                .swiper-slide
                    ul.list-group(v-for="list in lists")
                        li(v-if="list.type==0" v-on:click="noprice(list.id)")
                            span.nr 你的药方正在划价，请耐心等待
                            span.time {{list.date}}
                            i.icon-arrow-right
                        li(v-if="list.type==1" v-on:click="haveprice(list.id)")
                            span.nr 你的药方已划价，请前往支付
                            span.time {{list.date}}
                            i.icon-arrow-right
                        li(v-if="list.type==3" v-on:click="pay(list.id)")
                            span.nr 你的药方已支付成功
                            span.time {{list.date}}
                            i.icon-arrow-right
                        li(v-if="list.type==4" v-on:click="pay(list.id)")
                            span.nr 你的药方已发货
                            span.time {{list.date}}
                            i.icon-arrow-right
                        li(v-if="list.type==5" v-on:click="pay(list.id)")
                            span.nr 你的药方已发货
                            span.time {{list.date}}
                            i.icon-arrow-right
                        li(v-if="list.type==6" v-on:click="pay(list.id)")
                            span.nr 你的药方已过期
                            span.time {{list.date}}
                            i.icon-arrow-right
                        li(v-if="list.type==7" v-on:click="pay(list.id)")
                            span.nr 你的药方正在退款
                            span.time {{list.date}}
                            i.icon-arrow-right
                        li(v-if="list.type==8" v-on:click="pay(list.id)")
                            span.nr 你的药方已退款
                            span.time {{list.date}}
                            i.icon-arrow-right
                    .visible


</template>
<script>
    export default {
        data() {
            return{
                page:1,
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
                this.$http({url:this.$store.state.apiUrl+'recipe/lists',method:'GET',params:{page:this.page}}).then(function (res) {
                    res.data.data.list.forEach(e =>{
                        self.lists.push(e);
                    })
                    self.page ++;
                    if(res.data.data.total > 8){
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
                    }

                    oLoadTip.show();
                    this.total = res.data.data.perPage;
                });
            },
            noprice:function(id){
              this.$router.push({path:'/cfpy_noprice',query: { id: id }});
            },
            haveprice:function(id){
              this.$router.push({path:'/cfpy_haveprice',query: { id: id }});
            },
            pay:function(id){
             this.$router.push({path:'/cfpy_pay',query: { id: id }});
           }
        }
    };
</script>
