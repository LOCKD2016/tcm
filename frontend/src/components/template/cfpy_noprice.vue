<template lang='jade'>
#wrap.cfpy_noprice
    header
        .left(onclick="back()")
            i.icon-arrow-left
        .center 传方抓药
    ul.list-group.search_his
        li
            span 就诊人
            .val {{family.name}}
        li
            span 手机号
            .val {{family.mobile}}
    .panel.list.cf.cfsc
        .head  处方
        .img(v-bind:style="bg(recipe.recipe_photo)")
        ul.list-group.search_his
            li
                span 中药剂型
                .val
                  input(type="text" placeholder="请选择中药剂型" v-model="recipe.medicinal_type_name" readonly)

    ul.list-group.search_his
        li
            span 煎药方式
            .val(v-if="recipe.is_tisane") 代煎
            .val(v-else) 自煎
        li(v-if="recipe.recipe_self")
            span 自备
            .val {{recipe.recipe_self[0]}} {{recipe.recipe_self[1]}}

    .tips
        .icon-tit
          i
        span 药费尚未划价，请稍等


</template>
<script>
    export default {
        data() {
            return{
                recipe:[],
                recipe_head:[],
                family:[]
            }
        },
        created:function () {
            this.id = this.$route.query.id;
            this.getRecipe();
        },
        methods:{
            getRecipe:function () {
                this.$http({url:this.$store.state.apiUrl+'recipe/detail/'+this.id, method:'GET'}).then(function(res){
                    if(res.data.status){
                        this.recipe = res.data.data;
                        this.recipe_head = res.data.data.recipe_head;
                        this.family = res.data.data.family;
                    }
                })
            },
            bg: function (url) {
                if (url) return 'background-image:url(' + url + ')'
            }

        }
    };
</script>