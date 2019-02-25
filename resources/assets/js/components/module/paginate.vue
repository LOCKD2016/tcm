
<template>
  <nav class="hasInput">
    <div class="input">
      <input type="number" min="1" v-bind:max="all"/><span @click="goPage()">Go</span>
    </div>
    <ul class="pagination">
      <li v-if="cur!=1" class="disabled"><a v-on:click="btnClick(cur-1)">«</a></li>
      <li v-for="index in indexs" v-bind:class="{ active: cur == index}"><a v-on:click="btnClick(index)">{{ index }}</a></li>
      <li v-if="cur!=all"><a v-on:click="btnClick(cur+1)">»</a></li>
      <li><a>共<i>{{all}}</i>页</a></li>
    </ul>
  </nav>
</template>
<script type="text/javascript">
  export default {
      props: ['cur', 'all'],
      data(){
          return {
  
          };
      },
      computed:{
          indexs: function () {
              var left = 1;
              var right = this.all;
              var ar = [];
              if (this.all >= 11) {
                  if (this.cur > 5 && this.cur < this.all - 4) {
                      left = this.cur - 5;
                      right = this.cur + 4
                  } else {
                      if (this.cur <= 5) {
                          left = 1;
                          right = 10
                      } else {
                          right = this.all;
                          left = this.all - 9
                      }
                  }
              }
              while (left <= right) {
                  ar.push(left);
                  left++
              }
              return ar
          }
      },
      methods: {
          btnClick(data){
              if (data != this.cur) {
                  this.cur = data;
                  this.$dispatch('btn-click', data)
              }
          },
          goPage(){
              var page = $.trim($('input[type=number]').val());
              var preg = /^[1-9]\d*$/;
              if(page ==''|| !preg.test(page)){
                  return false;
              }
              this.$dispatch('gopage', page);
          }
      },
      watch: {
          cur(oldValue, newValue) {
              //console.log(arguments);
              //this.$dispatch('btn-click', newValue)
          }
      }
  }
</script>