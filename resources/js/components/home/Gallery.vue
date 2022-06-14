<template>
    <div class="nav-padding">
      <vue-easy-lightbox
        escDisabled
        moveDisabled
        :visible="visible"
        :imgs="imgs"
        :index="index"
        @hide="handleHide"
      ></vue-easy-lightbox>
        <section class="container custom-header">
            <h1 class="text-center">Gallery</h1>
            <div class="gallery-image">
            <!-- loop here -->
                <div class="img-box pic" v-for="(gallery, index) in this.gallery.data" :key="index" @click="showImg(index)"> 
                <img :src="gallery.src" :alt="gallery.title"/>
                <div class="transparent-box">
                    <div class="caption">
                    <p>{{gallery.title}}</p>
                    </div>
                </div> 
                </div>
            </div>
            <pagination :data="gallery" @pagination-change-page="getResults" class="px-5">
                <span slot="prev-nav"><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous</span>
                <span slot="next-nav">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></span>
            </pagination>
        </section>
    </div>
</template>
<script>
import VueEasyLightbox from 'vue-easy-lightbox'
export default {
        components: {
          VueEasyLightbox
        },
        data(){
            return{
             gallery: {} ,
             imgs: [],
              visible: false,
              index: 0
            }
        },
        methods:{
            getResults(page = 1) {
                axios.get('/api/homegallery?page=' + page)
                .then(response => {
                  this.gallery = response.data
                  this.imgs = response.data.data
                }).finally(() => {
                })
            },
            showImg (index) {
              this.index = index
              this.visible = true
            },
            handleHide () {
              this.visible = false
            }
        },
        mounted() {
            this.getResults();
        }
    }
</script>
