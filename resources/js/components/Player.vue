
<template>
    <div class="player-container">
    <vue-core-video-player :src="`/storage/classroom/note/${file}`"></vue-core-video-player>
  </div>
</template>

<script>
    import VueCoreVideoPlayer from 'vue-core-video-player'
    Vue.use(VueCoreVideoPlayer)
    export default {
        data(){
            return{
                file: '/storage/video/test-video.mp4'
            }
        },
        mounted() {
            if(!this.$gate.isAdmin()){
                axios.get('/api/note/'+this.$route.params.id).then(({data}) => (this.file = data));
            }
        }
    }
</script>
