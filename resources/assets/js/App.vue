<template>
<div >
  <navbar></navbar>
  <sidebar></sidebar>
</div>
  <div id="app">
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Admin Panel
          <small>Everything is here.</small>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content">
        <!-- Your Page Content Here -->
        <!-- route outlet -->
        <router-view></router-view>
      </section>
      <!-- /.content -->
    </div>
  </div>
</template>


<script>
import Navbar from './components/layouts/Navbar.vue'
import Sidebar from './components/layouts/Sidebar.vue'



export default {
  data(){ //页面用到的数据
    return {

    };
  },
  created(){//实例创建后调用

  },
  beforeCompile(){//编译前调用

  },
  compiled(){//编译完成调用

  },
  ready(){//dom渲染完毕调用

  },
  beforeDestroye(){//实例销毁前调用

  },
  destroyed(){//实例销毁后调用

  },
  methods:{
    subCoverB(){//定义也没可以使用的函数

    }
  },
  components:{
    //页面使用的组件
  },
  replace: false
}
</script>
