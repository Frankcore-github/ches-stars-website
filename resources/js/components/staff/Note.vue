<template>
    <div v-if="$gate.isStaff()" class="container">
      <div class="mt-5">
        <div class="row sticky-top">
          <div class="col-12">
            <!-- Default box -->
            <div class="card">
              <div class="card-header black">
                  <h6 style="display: inline">{{form.class_name | upText}} | {{form.subject_name}}</h6>
                  <button @click="addNoteModal" class="btn btn-success btn-sm float-right">Add New Note <i class="fa fa-plus" aria-hidden="true"></i></button>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <div v-if="count > 0">
          <div class="card card-widget" v-for="note in this.note.data" :key="note.id"> <!-- Note start here -->
                <div class="card-header">
                  <div class="user-block">
                    <h6>Shared publicly - {{note.created_at | classDate}}</h6>
                  </div>
                  <!-- /.user-block -->
                  <div class="card-tools">
                    <button @click="deleteNote(note.id)" type="button" class="btn btn-outline-danger btn-sm">Delete <i class="fa fa-trash"></i></button>
                  </div>
                  <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="border-bottom">
                    <strong>Chapter:</strong>
                    <p class="indent">{{note.chapter_name}}</p>
                  </div>

                  <div class="border-bottom">
                    <!-- post text -->
                    <strong>Text:</strong>
                    <p class="indent selectable">{{note.notes_text}}</p>
                  </div>

                  <div v-if="note.url">
                    <div class="border-bottom">
                      <!-- post text -->
                      <strong>Links:</strong>
                      <div class="bg-light">
                        <p class="py-3">
                          <a :href="`${note.url}`" target="_blank" rel="noopener noreferrer">{{note.url}}</a>
                        </p></div>
                    </div>
                  </div>

                  <!-- Attachment -->
                  <div v-if="note.file_name">
                    <strong>Attachment:</strong>
                    <div class="attachment-block clearfix ml-5">
                      <img style="width: 4rem" class="attachment-img img-fluid m-1" src="/storage/img/attached-file.png" alt="Attachment Image">
                      <div class="attachment-pushed">
                        <div class="dropdown float-right pr-2">
                          <i class="fa fa-ellipsis-v" data-toggle="dropdown" aria-hidden="true"></i>
                          <ul class="dropdown-menu dropdown-menu-right text-center">
                          <li><a v-if="!check(note.file_name)" :href="`/storage/classroom/note/${note.file_name}`">View <i class="fa fa-file-pdf" aria-hidden="true"></i></a></li>
                          <li><router-link v-if="check(note.file_name)" :to="`/player/${note.id}`">Play <i class="fa fa-play-circle" aria-hidden="true"></i></router-link> </li>
                          </ul>
                        </div>
                        <!-- /.attachment-text -->
                      </div>
                      
                        <div>
                            {{note.file_name}}
                        </div>
                      <!-- /.attachment-pushed -->
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->

                <comment-note :id="note.id"></comment-note>
                <!-- /.card-footer -->
          </div>  <!-- Note end here --> 
        </div>
          
      </div>      
        
        <!-- modal -->

        <div class="modal fade" id="NoteModal" tabindex="-1" role="dialog" aria-labelledby="NoteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="NoteModalLabel">Add New Note</h5>
                    <button type="button" @click.prevent="cancel" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="createNote" @keydown="form.onKeydown($event)">
                         <div class="modal-body">
                            <div class="form-group">
                            <label>Text</label>
                            <textarea v-model="form.notes_text" style="resize:none" name="notes_text" id="notes_text" rows="5"
                              class="form-control" :class="{ 'is-invalid': form.errors.has('notes_text') }" placeholder="Enter text here...">
                            </textarea>
                            <has-error :form="form" field="notes_text"></has-error>
                            </div>

                            <div class="form-group">
                            <label>Attach file [PDF only]</label>
                              <div class="custom-file">
                                  <input @change="selectFile" name="file" type="file" class="custom-file-input" id="file">
                                  <label class="custom-file-label" style="overflow: hidden" for="file">{{file_name}}</label>
                              </div>
                              <has-error :form="form" field="file_name"></has-error>
                              <has-error :form="form" field="extension"></has-error>
                            </div>

                            <div class="form-group">
                            <label>External Links</label>
                            <input v-model="form.url" type="text" name="url"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('url') }" placeholder="Paste link here (if any)">
                            <has-error :form="form" field="url"></has-error>
                            </div>
                            <div class="form-group">
                            <label>Chapter Name</label>
                            <input v-model="form.chapter_name" type="text" name="chapter_name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('chapter_name') }" placeholder="Enter Chapter Name Here">
                            <has-error :form="form" field="chapter_name"></has-error>
                            </div>
                            <div class="form-group">
                            <label v-if="percent > 0">Uploading: {{percent}}%</label>
                            <label v-if="percent == 100">Please wait....</label>
                            </div>
                        </div> 
                        <div class="modal-footer">
                            <button :disabled="form.busy" type="submit" class="btn btn-success btn-sm">Add Note</button>
                            <button @click="cancel" type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cancel</button>
                        </div>
                </form>
                </div>
            </div>
        </div>
        
        <!-- Model ends -->
    <pagination :data="note" :limit="-1" @pagination-change-page="getResults">
      <span slot="prev-nav"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Previous</span>
      <span slot="next-nav">Next <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span>
    </pagination>
    </div>
</template>

<script>
    export default { 
      data(){
        return{
            count: 0,
            note:{},
            file_name: 'Choose file',
            percent: 0,
            form: new Form({
              id:'',
              class_name: this.$route.params.cls,
              subject_name: this.$route.params.subject,
              notes_text:'',
              file_name:'',
              url:'',
              chapter_name:'',
              file: null,
              extension:''
            })
        }
      },
      methods:{
            getResults(page = 1) {
                let loader = this.$loading.show()
                axios.get('/api/shownotes/'+ this.$route.params.cls + '/' + this.$route.params.subject +'?page=' + page)
                    .then(response => {
                        this.note = response.data.object1,
                        this.count = response.data.object2
                    }).finally(()=>{
                      loader.hide()
                    })
            },  
            cancel(){
              this.form.clear();
              this.form.reset();
            },
             selectFile(e){
                const file = e.target.files[0];
                let reader = new FileReader();
                reader.onloadend = (file) => {
                  //console.log('RESULT', reader.result) 
                  this.form.file = reader.result;
                }
                if(file){
                  reader.readAsDataURL(file);
                  this.file_name = file['name'];
                  const type = file['name'].slice((Math.max(0, file['name'].lastIndexOf(".")) || Infinity) + 1)
                  this.form.extension = type;
                  if(type === ''){
                    this.form.errors.set({
                    file_name:  'File format should be pdf only.'
                    })
                    this.form.file = null;
                  }
                  else if(type != 'PDF' && type != 'pdf'){
                    this.form.errors.set({
                    file_name:  'File format should be pdf only.'
                    })
                  }
                   if(file['size'] > 209715200){
                    this.form.errors.set({
                    file:  'File size should be less than 200 MB.'
                    })
                  }
                  else{
                  this.form.errors.clear("file")
                  }
                }
            },
            play(url){
              this.$router.push('/player/'+ url);
            }, 
            check(url){
              let type = url.slice((Math.max(0, url.lastIndexOf(".")) || Infinity) + 1)
              let valid =  ['mp3', 'mp4']
              if(valid.indexOf(type) >= 0)
                return true
              else return false
            },
            addNoteModal(){
                this.form.clear();
                this.form.reset();
                this.file_name = 'Choose file'
                $('#NoteModal').modal({
                    backdrop: 'static',
                    keyboard: false
                },'show');
            },
            createNote(){
                this.$Progress.start()
                this.form.post('/api/note',{
                  onUploadProgress: progressEvent => {
                      if(progressEvent.lengthComputable){
                      this.percent = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))
                      }
                  }
                })
                .then(()=>{
                    $('#NoteModal').modal('hide')
                    Toast.fire({
                        icon: 'success',
                        title: 'Note added successfully'
                    })
                    this.$Progress.finish()
                   
                })
                .catch(() =>{
                  this.$Progress.fail()
                })
                .finally(() => {
                     Fire.$emit('ReloadNote')
                    this.percent = 0;
                })
            },
            deleteNote(id){
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
                       this.form.delete('/api/note/'+id)
                       .then(()=>{
                          Swal.fire(
                              'Deleted!',
                              'Note has been deleted.',
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
                       }).finally(()=>{ 
                          Fire.$emit('ReloadNote');
                       })
                    }
                    })
            },
            loadNotes(){
                axios.get('/api/shownotes/'+ this.$route.params.cls + '/' + this.$route.params.subject).then(({data}) => (this.note = data));
            }
      },
        mounted() {
          if(this.$gate.isStaff()){
                this.getResults();
            }
          Fire.$on('ReloadNote',()=>{
                this.getResults();
            })
        }
    }
</script>
