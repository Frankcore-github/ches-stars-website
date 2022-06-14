<template>
  <div class="mt-5">
    <div class="card text-center">
      <div class="card-header">Classroom Tutorials</div>
      <div class="card-body">
        <center><div class="player-container w-75">
            <vue-core-video-player :src="`${file}`"></vue-core-video-player>
        </div></center>
      </div>
    </div>
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
        if(this.$gate.isStudent()){
            this.file = '/storage/video/student-video.mp4'
        }
        else if(this.$gate.isStaff()){
            this.file = '/storage/video/staff-video.mp4'
        }
    },
};
</script>