<template>
    <div v-if="$gate.isStudent()" class="container">
      <div class="mt-5">
        <div class="row sticky-top">
          <div class="col-12">
            <!-- Default box -->
            <div class="card">
              <div class="card-header black">
                  <h6 style="display: inline">{{$route.params.subject}}</h6>
                  <button @click="AssignmentModal" class="btn btn-success btn-sm float-right">Submit Assignment<i class="fa fa-plus" aria-hidden="true"></i></button>
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
                <div v-if="checkDate(note.created_at)" class="card-tools">
                  <div class="icheck-primary d-inline">
                        <input type="radio" id="radioPrimary2" @change="attendance(note.id)">
                        <label for="radioPrimary2"> Attendance</label>
                  </div>
                </div>
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
                      <button id="clipboard" data-toggle="tooltip" title="Copy to clipboard" class="p-1 float-right btn btn-sm" @click="copy(note.url)">copy link <i class="fas fa-copy"></i></button>
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
                        <li><a v-if="!check(note.file_name)" :href="`/storage/classroom/note/${note.file_name}`" target="_blank">View <i class="fas fa-file-pdf" aria-hidden="true"></i></a></li>
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

        <div class="modal fade" id="AssignmentModal" tabindex="-1" role="dialog" aria-labelledby="AssignmentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="AssignmentModalLabel">Add Assignment</h5>
                    <button type="button" @click.prevent="cancel" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="createAssignment" @keydown="form.onKeydown($event)"  autocomplete="off">
                         <div class="modal-body">

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
                            <button :disabled="form.busy" type="submit" class="btn btn-success btn-sm">Submit</button>
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
          percent: 0,
          count: 0,
          note:{},
          file_name: 'Choose file',
            form: new Form({
              id:'',
              subject_name: this.$route.params.subject,
              chapter_name:'',
              file_name:'',
              file: null,
              extension:''
            })
        }
      },
      methods:{
            getResults(page = 1) { 
                let loader = this.$loading.show() 
                axios.get('/api/studentnotes/'+ this.$route.params.subject +'?page=' + page)
                    .then(response => {
                        this.note = response.data.object1,
                        this.count = response.data.object2
                    }).finally(()=>{
                      loader.hide()
                    })
            },  
            checkDate(date2){
              // alert(Moment().diff(Moment(date2), 'hours'));
                if(Moment().diff(Moment(date2), 'hours') <= 24){
                  return true;
                }
            },
            attendance(noteid){
              axios.post('/api/setattendance/'+ this.$route.params.subject + '/' + noteid)
              .then(data => {
                if(data.data.message === 'exist'){
                   Toast.fire({
                        icon: 'warning',
                        title: 'Attendance taken already!'
                    })
                }else if(data.data.message === 'success'){
                  Toast.fire({
                        icon: 'success',
                        title: 'Marked as present!'
                    })
                }
              });
            },
            cancel(){
              this.form.clear();
              this.form.reset();
            },
            AssignmentModal(){
                this.form.clear();
                this.form.reset();
                this.file_name = 'Choose file'
                $('#AssignmentModal').modal({
                    backdrop: 'static',
                    keyboard: false
                },'show');
            },
             selectFile(e){
                const file = e.target.files[0];
                //this.form.file = file; for FormWithUpload
                let reader = new FileReader();
                reader.onloadend = (file) => {
                  console.log('RESULT', reader.result) 
                  this.form.file = reader.result;
                }
                if(file){
                  reader.readAsDataURL(file);
                  this.file_name = file['name']
                  const type = file['name'].slice((Math.max(0, file['name'].lastIndexOf(".")) || Infinity) + 1)
                  this.form.extension = type
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
            createAssignment(){
                this.$Progress.start()
                this.form.post('/api/assignment',{
                  onUploadProgress: progressEvent => {
                      if(progressEvent.lengthComputable){
                      this.percent = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))
                      }
                  }
                })
                .then(()=>{
                    $('#AssignmentModal').modal('hide')
                    Toast.fire({
                        icon: 'success',
                        title: 'Assignment successfully submitted'
                    })
                    this.$Progress.finish()
                })
                .catch(()=>{
                  this.$Progress.fail()
                })
                .finally(() => {
                    Fire.$emit('ReloadNote')
                    this.percent = 0;
                })
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
            async copy(s) {
              await navigator.clipboard.writeText(s);
              Toast.fire({
                icon: 'info',
                title: 'Link Copied!'
              })
            }
      },
        mounted() {
          $('[data-toggle="tooltip"]').tooltip(); 
          if(this.$gate.isStudent()){
                this.getResults()
                Echo.channel('NoteUploaded')
                  .listen('NoteUploadedEvent',(event)=>{
                    this.getResults()
                  }) 
            }
          Fire.$on('ReloadNote',()=>{
                this.getResults()
            })
        }
    }
</script>
