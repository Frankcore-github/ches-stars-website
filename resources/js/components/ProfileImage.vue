<template> 
    <div class="image">
        <img :src="profile()" class="img-circle elevation-2" alt="User Image">
    </div> 
</template>

<script>
    export default {
        data() {
          return {
              profileImage: ''
          }  
        },
        methods: {
            profile(){
                if(this.profileImage === '')
                    return "/storage/img/profile/default.png"
                else
                    return "/storage/img/profile/"+this.profileImage
            },
        },
        mounted() {
            if(!this.$gate.isAdmin()){
            axios.get('/api/profileimage').then(({data}) => (this.profileImage = data));
            }
            console.log('Component mounted.')
        }
    }
</script>