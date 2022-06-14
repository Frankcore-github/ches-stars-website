<template>
    <div id="root">
        <transition name="fade">
            <ul class="child" v-if="isShow">
                <center><h2 class="text-white font-weight-bold pt-5">School Anthem</h2></center>
					<div class="text-center text-white pt-2 pb-5">
							We celebrate our golden past,<br>
							Our future just beginning.<br>
							To learn, to shine, reach for the stars,<br>
							All dreams will be fulfilling.<br>
															<br>
							Friends we value, those who last,<br>
							Friends from all communities,<br>
							Leaders we choose, guidance we follow,<br>
							The future is  our hands.<br>
														<br>
							There is one thread that binds us all,<br>
							A thread of white and blue,<br>
							Spun with creed and spirit true,<br>
							Of faith, hope and love.<br>
															<br>
							As we pursue, our hopes and dreams,<br>
							On the path that lies before us,<br>
							Our future bright, we will reach new height,<br>
							With Ches' Stars as our guide.<br>
					</div>
            </ul>
        </transition>
        <button class="btn btn-info rounded-pill" @click="isShow = !isShow">{{isShow ? 'Close School Anthem' : 'View School Anthem'}}</button>
    </div>
</template>

<script>
    export default {
        data() {
            return{
                isShow:false
            }
        },
        mounted() {
        
        }
    }
</script>
