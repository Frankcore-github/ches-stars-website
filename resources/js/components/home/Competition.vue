
<template>
    <div class="mt-5">
        <div class="card">
            <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
                    <li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
                    <li data-target="#carouselExampleCaptions" data-slide-to="3"></li>
                    <li data-target="#carouselExampleCaptions" data-slide-to="4"></li>
                    <li data-target="#carouselExampleCaptions" data-slide-to="5"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                    <img src="storage/img/comp/aria.png" class="d-block w-100" alt="...">
                    </div>
                    <div class="carousel-item" @click="image('storage/img/comp/Damienki.jpg')">
                    <img src="storage/img/comp/fancy.png" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block text-dark">
                        <h5>Click to view</h5>
                    </div>
                    </div>
                    <div class="carousel-item" @click="showVideo('storage/video/carryson.mp4')">
                    <img src="storage/img/comp/extemporaneousSpeech.png" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block text-dark">
                        <h5>Click to view</h5>
                    </div>
                    </div>
                    <div class="carousel-item" @click="showVideo('storage/video/Medonnangjop.mp4')">
                    <img src="storage/img/comp/action.png" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block text-dark">
                        <h5>Click to view</h5>
                    </div>
                    </div>
                    <div class="carousel-item" @click="showVideo('storage/video/joecyl.mp4')">
                    <img src="storage/img/comp/recitation.png" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block text-dark">
                        <h5>Click to view</h5>
                    </div>
                    </div>
                    <div class="carousel-item" @click="showVideo('storage/video/Shekinah.mp4')">
                    <img src="storage/img/comp/story.png" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block text-dark">
                        <h5>Click to view</h5>
                    </div>
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon bg-dark" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a> 
                <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
                    <span class="carousel-control-next-icon bg-dark" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
        <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Winner</h5>
                    <button type="button" class="close" data-dismiss="modal" @click="pause" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div v-show="!isImage">
                        <video id="myVideo" class="w-100" controls :src="video" autoplay controlsList="nodownload"></video>
                    </div>
                    <div v-show="isImage">
                        <img :src="pic" class="w-100 h-100"/>
                    </div>

                </div>
                </div>
            </div>
            </div>
        <!-- modal ends -->
    </div>

    
</template>

<script>
    export default {
        data(){
            return{
            isImage: false,
            pic: '',
            video: '',
            vid:null
            }
        },
        methods:{
            showVideo(text){
            this.isImage = false
            this.video = text
            $('#myModal').modal('show')
            },
            image(img){
            this.isImage = true
            this.pic = img
            $('#myModal').modal('show')
            },
            pause(){
                if(!this.isImage) this.vid.pause();
            }
        },
        mounted() {
            this.vid = document.getElementById("myVideo");
        }
    }
</script>
