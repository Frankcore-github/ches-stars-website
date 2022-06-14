
<template>
    <div v-if="$gate.allowedExam()" class="mt-5">
        <div class="mt-5">
        <div class="row sticky-top">
          <div class="col-12">
            <!-- Default box -->
            <div class="card">
              <div class="card-header black">
                  <div class="btn-group float-right">
                        <button type="button" class="btn btn-sm btn-outline-light dropdown-toggle text-dark" data-flip="false" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           {{subjectname}}
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div v-for="info in this.subjects" :key="info.id" @click="getQuestionPaper(subjectname = info.subject_name)" class="dropdown-item" href="#">{{info.subject_name}}</div>
                        </div>
                    </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
          <div v-show="initial" class="card">
              <div class="card-header">
                <h5 class="card-title m-0">Read the following instructions before you appear the exam.</h5>
              </div>
              <div class="card-body">
                <ol>
                  <li>Answer script should be pages from your own copy.</li>
                  <li>Mention your name, class, roll number and subject in the first page.</li>
                  <li>Write down the page number in every page.</li>
                  <li>Upload your answer script in a PDF format only.</li>
                  <li>If you have trouble in the point 4, <a href="https://smallpdf.com/jpg-to-pdf" target="_blank">click here</a> to convert image to pdf.</li>
                  <li><a href="/storage/video/exam_tutorial.mp4" target="_blank">Click here</a> to watch a video tutorial on how to convert image to pdf and upload it.</li>
                  <li>Within 24 hours of the exam, the answer script must be submitted.</li>
                  <li>Double check your work before submitting, once you upload it you won't be able to undo.</li>
                  <li>If you didn't receive the question paper, try to reload the page and select the subject again.</li>
                  <li>Students who are unable to submit their answer script before the deadline of 24 hours after the exam should report to the numbers listed below:-
                      <ol type="i">
                        <li>9612730973</li>
                        <li>9863115781</li>
                      </ol>
                  </li>
                  <p class="text-center">********** ALL THE BEST **********</p>
                </ol>
              </div>
            </div>

          <div class="card card-widget" v-for="questionpaper in this.questionpaper" :key="questionpaper.id"> <!-- Note start here -->
                <div class="card-header">
                  <div class="user-block">
                    <h6>Shared on - {{questionpaper.created_at | classDate}}</h6>
                  </div>
                   <div v-if="answerpaper == ''" class="card-tools">
                      <button @click="addAnswerModal" class="btn btn-success btn-sm">Upload Answer scripts <i class="fas fa-file-upload"></i></button>
                  </div>
                  <div v-if="answerpaper != ''" class="card-tools">
                      <span v-if="status == 'seen'" class="badge badge-success">Seen</span>
                      <span v-else class="badge badge-danger">Not Seen</span>
                      <a :href="`/storage/classroom/exam/${answerpaper}`" target="_blank"><button class="btn btn-primary btn-sm">View Answer Script <i class="fas fa-file-pdf"></i></button></a>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="border-bottom">
                  <!-- post text -->
                  <strong>Text:</strong>
                  <p class="indent selectable">{{questionpaper.exam_text}}</p>
                  </div>
                  <!-- Attachment -->
                  <div v-if="questionpaper.file_name">
                    <strong>Attachment:</strong>
                    <div class="attachment-block clearfix ml-5">
                      <img style="width: 4rem" class="attachment-img img-fluid m-1" src="/storage/img/attached-file.png" alt="Attachment Image">
                      <div class="attachment-pushed">
                        <div class="dropdown float-right pr-2">
                          <i class="fa fa-ellipsis-v" data-toggle="dropdown" aria-hidden="true"></i>
                          <ul class="dropdown-menu dropdown-menu-right text-center">
                          <li><a :href="`/storage/classroom/exam/${questionpaper.file_name}`" target="_blank">View <i class="fas fa-file-pdf" aria-hidden="true"></i></a></li>
                          </ul>
                        </div>
                        <!-- /.attachment-text -->
                      </div>
                      
                        <div>
                            {{questionpaper.file_name}}
                        </div>
                      <!-- /.attachment-pushed -->
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                               
                <!-- /.card-footer -->
          </div>  <!-- Note end here --> 
          
      </div> 
      <!-- modal -->

        <div class="modal fade" id="AnswerModal" tabindex="-1" role="dialog" aria-labelledby="AnswerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="AnswerModalLabel">Add Answer Scripts</h5>
                    <button type="button" @click.prevent="cancel" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="createAnswer" @keydown="form.onKeydown($event)">
                         <div class="modal-body">


                            <div class="form-group">
                            <label>Attach file [PDF only]</label>
                              <div class="custom-file">
                                  <input @change="selectFile" name="file" type="file" :class="{ 'is-invalid': form.errors.has('file_name') }" class="custom-file-input" id="file">
                                  <label class="custom-file-label" style="overflow: hidden" for="file">{{file_name}}</label>
                              </div>
                              <has-error :form="form" field="file_name"></has-error>
                              <has-error :form="form" field="extension"></has-error>
                            </div>

                            <div class="form-group">
                            <label v-if="percent > 0">Uploading: {{percent}}%</label>
                            <label v-if="percent == 100">Please wait....</label>
                            </div>
                        </div> 
                        <div class="modal-footer">
                            <button :disabled="form.busy" type="submit" class="btn btn-success btn-sm">Upload</button>
                            <button @click="cancel" type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cancel</button>
                        </div>
                </form>
                </div>
            </div>
        </div>
        
        <!-- Model ends -->
    </div>
</template>

<script>
    export default {
        data(){
            return{ 
                initial: true,
                isClass: true,
                subjectname: 'Select Subject',
                file_name: 'Choose file',
                status:'',
                percent: 0,
                info:{},
                subjects:{},
                questionpaper:{},
                answerpaper:'',
                 form: new Form({
                    id:'',
                    subject_name: '',
                    file_name:'',
                    file: null,
                    extension:''
                  })
            }
        },
        methods:{ 
           loadSubjects(){
                let loader = this.$loading.show() 
                axios.get('/api/showsubjects').then(({data}) => (this.subjects = data)).finally(()=>{loader.hide()});
            },
            selectFile(e){
              this.form.errors.clear("file_name")
              const file = e.target.files[0];
                let reader = new FileReader();
                reader.onloadend = (file) => { 
                  this.form.file = reader.result;
                }
                if(file){ 
                  reader.readAsDataURL(file);
                  this.file_name = file['name'];
                  this.form.file_name = this.file_name;
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
                    file_name:  'File size should be less than 200 MB.'
                    })
                  }
                }
            },
            cancel(){
              this.form.clear()
            },
            addAnswerModal(){
                this.form.clear();
                this.form.reset();
                this.file_name = 'Choose file'
                $('#AnswerModal').modal({
                    backdrop: 'static',
                    keyboard: false
                },'show');
            },
            getQuestionPaper(subj){
                this.initial = false;
                this.answerpaper = '';
                let loader = this.$loading.show() 
                axios.get('/api/questionpaper/' + subj)
                .then((response) => {
                  this.questionpaper = response.data.questionpaper,
                  this.answerpaper = response.data.answerpaper,
                  this.status = response.data.status
                })
                .finally(() => {
                  loader.hide()
                });
            },
            createAnswer(){
                this.form.subject_name = this.subjectname;
                this.$Progress.start()
                this.form.post('/api/studentexam',{
                  onUploadProgress: progressEvent => {
                      if(progressEvent.lengthComputable){
                      this.percent = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))
                      }
                  }
                })
                .then((response)=>{
                  this.status = "unseen";
                  this.answerpaper = response.data.answerscript;
                  $('#AnswerModal').modal('hide') 
                    Toast.fire({
                      icon: 'success',
                      title: 'Answer scripts successfully submitted.'
                  })
                  this.$Progress.finish() 
                                    
                })
                .catch(() =>{
                  this.$Progress.fail()
                })
                .finally(() => {
                    this.percent = 0;
                })
            },
        },
        mounted() { 
           if(this.$gate.allowedExam()){
                this.loadSubjects();
            }
            
        }
    }
</script>
