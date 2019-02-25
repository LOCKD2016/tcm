<template lang="jade">
    ul.nav.nav-foot
        router-link(to="/index" tag="li")
            a
                i.index
                p 首页
        router-link(v-if="$store.state.tcmuser" to="/order" tag="li")
            a
                   i.my
                p 我的预约
        li(v-else v-on:click="jk")
            a
                i.health
                p 健康商城
        router-link(to="/my" tag="li")
            a
                i.mine
                p 我
</template>
<script>
  export default {
      methods:{
          jk(){
              $api.pop('更多精彩，敬请期待！')
          }
      }
  }
</script>
