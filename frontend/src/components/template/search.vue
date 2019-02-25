<template lang='jade'>
.fixbody
    header.seach_header
        .left
            i.icon-search(@click="storage()")
            form(action="" class="input-kw-form")
              input.searchInp#Inp(type="search" name="keyword" v-model="keyword" placeholder="搜索医生/疾病" @click="focusinput")
            i.icon-close-c(v-if="keyword" @click="reset()")
        .right(onclick="back()") 取消
    #wrap
        .zjsc 最近搜索
        ul.list-group.search_his
           li(v-for="k in keywords" @click="search(k)")
              i.icon-time
              span {{k}}
           li.clear
                span(@click="clear()") 清除搜索历史

</template>
<script>
    export default{
        created(){
            $("#Inp").focus();
            var history = window.localStorage.keywords;
            if(history){
                this.keywords = JSON.parse(history);
            }
        },
        mounted(){
          $("#Inp").focus();//无效

          var _this=this;
          $('.searchInp').bind('keypress', function(event) {
            if (event.keyCode == "13") {  //js监测到为为回车事件时 触发
              _this.storage();
              event.preventDefault();   //阻止页面自动刷新，重复加载123
            }
          });
        },
        data(){
            return {
                keyword: '',
                keywords: []
            }
        },
        methods:{
          focusinput(){
            $("#Inp").focus();
          },
            storage(){
                var keyword = this.keyword;
                if(!keyword){
                    $api.pop('请输入搜索关键词');return;
                }
                if(!this.keywords){
                    this.keywords.push(keyword);
                    window.localStorage.keywords = JSON.stringify(this.keywords);
                }else{
                    if(this.keywords.indexOf(keyword) <= -1){
                        this.keywords.push(keyword);
                        window.localStorage.keywords = JSON.stringify(this.keywords);
                    }
                }
                this.$router.push({ path: '/search_result', query:{keyword: keyword}});
            },
            clear(){
                window.localStorage.clear();
                this.keywords = [];
            },
            reset(){
                this.keyword = '';
                $('.icon-close-c').hide();
            },
            search(name){
                this.keyword = name;
                this.$router.push({ path: '/search_result', query:{keyword: name}});
            },
        }
    }
</script>
