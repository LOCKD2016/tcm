<template lang='jade'>
.fixbody
    header
        .left(onclick="back()")
            i.icon-arrow-left
        .center 地图
    .map
        #container
</template>
<script>
  var map = new AMap.Map('container', {
      resizeEnable: true,
      zoom:11,
  });
</script>
