<template lang='jade'>

	.fixbody
		header
			.left(onclick='back()')
				i.icon-arrow-left
			.center 联系方式
		#wrap
			ul.hosplist
				li(v-for='item in hospitals')
					span {{ item.name }}
					span {{ item.content.address }}
					span {{ item.telephone }}

</template>

<script>
	
	export default{

		data(){

			return{

				hospitals:''
				
			}

		},

		mounted(){

			this.$http({url:this.$store.state.apiUrl+'clinique/lists',method:'GET' })

				.then(function (res) {

			    	this.hospitals = res.data.data;

				},function (response) {

				    errorMsg(response.data.data.errors);

				});

		}

	}

</script>