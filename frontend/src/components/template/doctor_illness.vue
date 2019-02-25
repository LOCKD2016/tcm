<template lang='jade'>
.fixbody
    header
        .left(onclick="back()")
            i.icon-arrow-left
        .center
            span 全部常见疾病
        .right(@click="linkSearch()")
            i.icon-search

    #wrap
       template(v-for="list in lists")
        .illness
           span {{list.name}}
           .itemsBox.clearfix
             template(v-for="d in list.disease")
                dl(@click="doctor(d.disease.name)")
                  dt(v-bind:style="bg(d.icon)")
                  dd(@click="doctor()") {{d.disease.name}}



</template>
<script>
    export default {
        data() {
            return{
                lists:[]
            }
        },
        created:function () {
            this.getList();
        },
        methods:{
            getList:function () {
                this.$http({url:this.$store.state.apiUrl+'section/disease', method:'GET'}).then(function(res){
                    if(res.data.status){
                        this.lists = res.data.data;
                    }
                })
            },
            doctor(disease){
                this.$router.push({ path: '/doctor/type/2', query:{disease: disease}});
            },
            bg: function (url) {
                if (url) return 'background-image:url(' + url + ')'
            },
            linkSearch:function (){
                this.$router.push({ path: '/search', query:{type: 2}});
            }
        }
    };
</script>