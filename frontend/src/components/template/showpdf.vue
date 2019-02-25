
<template lang="jade">


    .jianyao
      .pic
        img(src='/static/img/jianyao.png')
        .data1 {{ total }}
        .data2 {{ day }}
        .data3 {{ time }}

</template>

<script>

  export default {

  	data(){

  		return{

        total:'',
        day:'',
        time:''

  		}

  	},

    created(){

      this.total = this.$route.query.total

      this.day = this.$route.query.day

      this.time = this.$route.query.time

    }

  }

</script>
