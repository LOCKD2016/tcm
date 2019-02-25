<template lang='jade'>
.fixbody
    .point_img_box
        img.point_img(v-bind:src="img")
</template>
<script>
    export default{
        created(){
            this.id = this.$route.query.path;
            this.pointimg(this.id);
        },
        data(){
            return {
                img:''
            }
        },
        methods:{
            pointimg(id){
                this.$http.get(this.$store.state.apiUrl+'pointimg/'+ id).then(function (res) {
                    if(res.data.status){
                        this.img = res.data.data.img;
                    }else{
                        $api.pop(res.data.msg);
                    }

                });
            }

        }
    }
</script>
