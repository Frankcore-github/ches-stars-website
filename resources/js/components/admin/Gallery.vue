
<template>
     <div v-if="$gate.isAdmin()" class="mt-5">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Gallery</h3>
                <div class="card-tools p-1">
                    <button class="btn btn-success btn-sm" @click="addGalleryImageModal">Add Image <i class="fas fa-image" aria-hidden="true"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-center">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Caption</th>
                      <th>Image</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="gallery in this.gallery.data" :key="gallery.id">
                      <td>{{gallery.id}}</td>
                      <td v-if="gallery.image_caption">{{gallery.image_caption}}</td>
                      <td v-else>(No caption)</td>
                      <td><img :src="image(gallery.image_name)"  class="img-thumbnail" width="150" alt=""></td>
                      <td><div href="#" @click="deleteGalleryImage(gallery.id)" class="btn btn-danger btn-sm text-light"><i class="fas fa-trash"></i> Delete</div></td>
                      
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                 <!-- <pagination :data="users" @pagination-change-page="getResults"></pagination> -->
                 <pagination :data="gallery" @pagination-change-page="getResults">
                    <span slot="prev-nav"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Previous</span>
                    <span slot="next-nav">Next <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span>
                </pagination>
              </div>
            </div>
            <!-- /.card -->
        <div class="modal fade" id="GalleryModal" tabindex="-1" role="dialog" aria-labelledby="GalleryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="GalleryModalLabel">Add New Image</h5>
                    <button type="button" @click="cancel" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="createGalleryImage" @keydown="form.onKeydown($event)">
                         <div class="modal-body">

                             <div class="form-group">
                            <label>Gallery Image</label>
                              <div class="custom-file">
                                  <input @change="selectFile" name="photo" type="file" class="custom-file-input" id="photo">
                                  <label class="custom-file-label" for="photo">{{file_name}}</label>
                              </div>
                              <has-error :form="form" field="photo"></has-error>
                            </div>

                            <div v-if="showImage">
                                <img :src="showImage"  class="img-thumbnail" alt="">
                            </div>
                            
                            <div class="form-group">
                            <label>Caption </label>
                            <input v-model="form.image_caption" type="text" name="image_caption"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('image_caption') }" placeholder="Enter Image Caption Here">
                            <has-error :form="form" field="image_caption"></has-error>
                            </div>

                            <div class="form-group">
                            <label v-if="percent > 0">Uploading: {{percent}}%</label>
                            <label v-if="percent == 100">Please wait....</label>
                            </div>
                        </div> 
                        <div class="modal-footer">
                            <button :disabled="form.busy" type="submit" class="btn btn-success btn-sm">Add Image</button>
                            <button type="button" @click="cancel" class="btn btn-danger btn-sm" data-dismiss="modal">Cancel</button>
                        </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
     export default {
        data() {
            return{
                file_name: 'Choose file',
                gallery: {},
                percent: 0,
                showImage:null,
                form: new Form({
                    id: '',
                    image_name: '',
                    extension:'',
                    photo: null
                })
            }
        },
        methods:{
            getResults(page = 1) {
                let loader = this.$loading.show() 
                axios.get('/api/gallery?page=' + page)
                .then(response => {
                  this.gallery = response.data
                }).finally(() => {
                    loader.hide()
                })
            },
            image(image_name){
                return "/storage/img/gallery/"+ image_name
            },
            cancel(){
                this.form.clear();
                this.form.reset();
                this.showImage = null
            },
            selectFile(e){
                let file = e.target.files[0];
                let reader = new FileReader();
                reader.onloadend = (file) => {
                  console.log('RESULT', reader.result) 
                  this.form.photo = reader.result;
                }
                if(file){
                  reader.readAsDataURL(file);
                  this.file_name = file['name'];
                  const type = file['name'].slice((Math.max(0, file['name'].lastIndexOf(".")) || Infinity) + 1)
                  this.form.extension = type;
                  if(type.toLowerCase() != 'png' && type.toLowerCase() != 'jpg' && type.toLowerCase() != 'jpeg'){
                    this.form.errors.set({
                    photo:  'File format should be png, jpg or jpeg only.'
                    })
                  } 
                  else if(file['size'] > 2097152){
                    this.form.errors.set({
                    photo:  'File size should be less than 2 MB.'
                    })
                  }
                  else{
                  this.showImage = URL.createObjectURL(file);
                  this.form.errors.clear("photo")
                  }
                }
            },
            addGalleryImageModal(){
                this.form.clear();
                this.form.reset();
                this.showImage = null;
                this.file_name = 'Choose file'
                $('#GalleryModal').modal({
                    backdrop: 'static',
                    keyboard: false
                },'show');
            },
            createGalleryImage(){
                if(this.form.photo != null){
                    this.$Progress.start()
                    this.form.post('/api/gallery',{
                  onUploadProgress: progressEvent => {
                      if(progressEvent.lengthComputable){
                      this.percent = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))
                      }
                  }
                })
                    .then(() => {
                        $('#GalleryModal').modal('hide')
                        Toast.fire({
                            icon: 'success',
                            title: 'Image added successfully'
                        })
                        this.$Progress.finish()
                    })
                    .catch(() => { 
                        this.$Progress.fail()
                    })
                    .finally(()=>{
                        Fire.$emit('ReloadGallery')
                        this.percent = 0;
                    })
                }else{
                    this.form.errors.set({
                    photo:  'Please select an image.' 
                    })
                }
                
            },
            deleteGalleryImage(id){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                    if (result.value) {
                        this.$Progress.start()
                        this.form.delete('api/gallery/'+id)
                        .then(()=>{
                            Swal.fire(
                                'Deleted!',
                                'Record has been deleted.',
                                'success'
                            )  
                        this.$Progress.finish()
                       })
                       .catch(()=>{
                            this.$Progress.fail()
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!'
                            })
                       })
                       .finally(()=>{
                            Fire.$emit('ReloadGallery')
                       })
                    }
                    })
            }
        },
        mounted() {
             if(this.$gate.isAdmin()){
                this.getResults();
            }
            Fire.$on('ReloadGallery',()=>{
                this.getResults();
            })
        }
    }
</script>