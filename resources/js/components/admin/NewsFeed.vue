
<template>
     <div v-if="$gate.isAdmin()" class="mt-5">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">News Feed</h3>
                <div class="card-tools p-1">
                    <button class="btn btn-success btn-sm" @click="addNewsFeedModal">Add File <i class="fas fa-file-upload" aria-hidden="true"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-center">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Caption</th>
                      <th>Uploaded Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="newsfeed in this.newsfeed.data" :key="newsfeed.id">
                      <td>{{newsfeed.id}}</td>
                      <td>{{newsfeed.file_caption}}</td>
                      <td>{{newsfeed.created_at | myDate}}</td>
                      <td><div href="#" @click="deleteNewsFeed(newsfeed.id)" class="btn btn-danger btn-sm text-light"><i class="fas fa-trash"></i> Delete</div></td>
                      
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                 <!-- <pagination :data="users" @pagination-change-page="getResults"></pagination> -->
                 <pagination :data="newsfeed" @pagination-change-page="getResults">
                    <span slot="prev-nav"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Previous</span>
                    <span slot="next-nav">Next <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span>
                </pagination>
              </div>
            </div>
            <!-- /.card -->
        <div class="modal fade" id="NewsFeedModal" tabindex="-1" role="dialog" aria-labelledby="NewsfeedModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="NewsFeedModalLabel">Add New File</h5>
                    <button type="button" @click="cancel" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="createNewsFeed" @keydown="form.onKeydown($event)">
                         <div class="modal-body">
                             <div class="form-group">
                            <label>File</label>
                              <div class="custom-file">
                                  <input @change="selectFile" name="file" type="file" class="custom-file-input" id="file">
                                  <label class="custom-file-label" for="file">{{file_name}}</label>
                              </div>
                              <has-error :form="form" field="file"></has-error>
                            </div>
                            
                            <div class="form-group">
                            <label>Caption </label>
                            <input v-model="form.file_caption" type="text" name="file_caption"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('file_caption') }" placeholder="Enter Image Caption Here">
                            <has-error :form="form" field="file_caption"></has-error>
                            </div>

                            <div class="form-group">
                            <label v-if="percent > 0">Uploading: {{percent}}%</label>
                            <label v-if="percent == 100">Please wait....</label>
                            </div>
                        </div> 
                        <div class="modal-footer">
                            <button :disabled="form.busy" type="submit" class="btn btn-success btn-sm">Add File</button>
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
                newsfeed: {},
                percent: 0,
                form: new Form({
                    id: '',
                    file_name: '',
                    file_caption:'',
                    extension:'',
                    file: null
                })
            }
        },
        methods:{
            getResults(page = 1) {
                let loader = this.$loading.show() 
                axios.get('/api/newsfeed?page=' + page)
                .then(response => {
                  this.newsfeed = response.data
                }).finally(()=>{
                    loader.hide()
                })
            },
            cancel(){
                this.form.clear();
                this.form.reset();
            },
            selectFile(e){
                let file = e.target.files[0];
                let reader = new FileReader();
                reader.onloadend = (file) => {
                  console.log('RESULT', reader.result) 
                  this.form.file = reader.result;
                }
                if(file){
                  reader.readAsDataURL(file);
                  this.file_name = file['name'];
                  const type = file['name'].slice((Math.max(0, file['name'].lastIndexOf(".")) || Infinity) + 1)
                  this.form.extension = type;
                  if(type.toLowerCase() != 'pdf' && type.toLowerCase() != 'doc' && type.toLowerCase() != 'txt' && type.toLowerCase() != 'png' && type.toLowerCase() != 'jpg' && type.toLowerCase() != 'jpeg'){
                    this.form.errors.set({
                    file:  'File format should be pdf, doc or txt only.'
                    })
                  } 
                  else if(file['size'] > 2097152){
                    this.form.errors.set({
                    file:  'File size should be less than 2 MB.'
                    })
                  }
                  else{
                  this.form.errors.clear("file")
                  }
                }
            },
            addNewsFeedModal(){
                this.form.clear();
                this.form.reset();
                this.file_name = 'Choose file'
                $('#NewsFeedModal').modal({
                    backdrop: 'static',
                    keyboard: false
                },'show');
            },
            createNewsFeed(){
                if(this.form.file != null){
                    this.$Progress.start()
                    this.form.post('/api/newsfeed',{
                  onUploadProgress: progressEvent => {
                      if(progressEvent.lengthComputable){
                      this.percent = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))
                      }
                  }
                })
                    .then(() => {
                        $('#NewsFeedModal').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: 'File added successfully'
                        })
                        this.$Progress.finish()
                    })
                    .catch(() =>{ 
                        this.$Progress.fail()   
                    })
                    .finally(()=>{
                        Fire.$emit('ReloadNewsFeed')
                        this.percent = 0;
                    })
                }else{
                    this.form.errors.set({
                    file:  'Please select a file.' 
                    })
                }
                
            },
            deleteNewsFeed(id){
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
                       this.form.delete('/api/newsfeed/'+id)
                       .then(()=>{
                            Swal.fire(
                                'Deleted!',
                                'Record has been deleted.',
                                'success'
                            ),    
                            this.$Progress.finish()
                       })
                       .catch(()=>{
                           Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!'
                             })
                       })
                       .finally(()=>{
                            Fire.$emit('ReloadNewsFeed')
                       })
                    }
                    })
            }
        },
        mounted() {
             if(this.$gate.isAdmin()){
                this.getResults();
            }
            Fire.$on('ReloadNewsFeed',()=>{
                this.getResults();
            })
        }
    }
</script>