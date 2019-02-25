<template lang='jade'>

	.fixbody
		header
			.left(onclick='back()')
				i.icon-arrow-left
			.center 物流信息

		#wrap
			.order
				.orderbg
				.orderinfo
					//- p 物流状态: 已签收
					p 订单编号: {{ order.order_sn }}
					p 支付时间: {{ order.pay_time }}
					p 支付方式: 微信支付

			ul.express
				.line
				li(v-for=' (msg,index) in expressMsg' v-bind:class='index==0?"active":""')
					i
					p {{ msg.remark }}
					p {{ msg.datetime }}
			.noexpress

</template>

<script>

	export default{

		data(){

			return{

				expressMsg:'',
				order:{},
				id:''

			}

		},

		created(){

			this.id = this.$route.params.id

			var self = this

			this.$http.get(this.$store.state.apiUrl + "exress/"+this.id)

				.then(function(res){

					if(res.body.data.result && res.body.data.result.status){

						self.expressMsg = res.body.data.result.list

						self.order = res.body.data.order

						self.expressMsg.reverse()

						console.log(self.expressMsg)

						this.$nextTick(function(){

							var h1 = $('li').eq(3).find('i').offset().top

							var h2 = $('li').eq($('li').length-1).find('i').offset().top

							var h = h2 - h1

							$('.line').css({

								'height':h,
								'top':h1

							})

						})

					}else{

						$('.noexpress').html('暂无快递信息')

					}

				})

		}

	}

</script>
