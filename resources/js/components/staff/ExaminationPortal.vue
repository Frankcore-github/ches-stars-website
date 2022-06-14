
<template>
    <div v-if="$gate.isStaff()" class="mt-5">
        <div class="mt-5">
        <div class="row sticky-top">
          <div class="col-12">
            <!-- Default box -->
            <div class="card">
              <div class="card-header black">
                <button @click="addQuestionModal" class="btn btn-success btn-sm">Upload Questions <i class="fas fa-file-upload"></i></button>
                  <div class="btn-group float-right">
                        <button type="button" class="btn btn-sm btn-outline-light dropdown-toggle text-dark" data-flip="false" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           {{classname | upText}} | {{subjectname}}
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div v-for="info in this.info" :key="info.id" @click="getQuestionPaper(classname = info.class_name, subjectname = info.subject_name)" class="dropdown-item" href="#">{{info.class_name | upText }} | {{info.subject_name}}</div>
                        </div>
                    </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <div v-show="initial" class="card">
              <div class="card-header">
                <h5 class="card-title m-0">Instructions</h5>
              </div>
              <div class="card-body">
                <ol>
                  <li>Question paper should be in a PDF format only.</li>
                  <li>If you have trouble in the point 2, 
                    <ol type="i">
                      <li><a href="https://smallpdf.com/jpg-to-pdf" target="_blank">click here</a> for converting image to pdf.</li>
                      <li><a href="https://smallpdf.com/word-to-pdf" target="_blank">click here</a> for converting word to pdf.</li>
                    </ol>
                  </li>
                  <li>Use the refresh button to refresh the page.</li>
                  <li>Track down the student for their sumission soon after the 24 hours of their exam by maintaining a record in your notebook or e-notebook.</li>
                  <li>Check the question paper properly before uploading, once you upload it you wont be able to revert it.</li>         
                </ol>
              </div>
            </div>

          <div class="card card-widget" v-for="questionpaper in this.questionpaper" :key="questionpaper.id"> <!-- Note start here -->
                <div class="card-header">
                  <div class="user-block">
                    <h6>Shared on - {{questionpaper.created_at | classDate}}</h6>
                  </div>
                  <div class="card-tools">
                      <button @click="getQuestionPaper(classname,subjectname)" class="btn btn-outline-dark btn-sm">Refresh <i class="fas fa-sync"></i></button>
                  </div>
                  <!-- /.card-tools -->
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
                <div v-if="!isEmpty" class="card-footer">
                  <div class="table-responsive">
                  <table class="table m-0">
                    <thead>
                    <tr>
                      <th>Name</th>
                      <th>Submitted On</th>
                      <th>Status</th>
                      <th>File</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="student in students" :key="student.id">
                      <td>{{student.student.first_name}} {{student.student.middle_name}} {{student.student.last_name}} </td>
                      <td>{{student.created_at|classDate}}</td>
                      <td>
                        <span v-if="student.status == 'seen'" class="badge badge-success">Seen</span>
                        <span v-else class="badge badge-danger">Not Seen</span>
                      </td>
                      <td>
                        <a @click="setStatus(student.id)" :href="`/storage/classroom/exam/${student.file_name}`" target="_blank">View <i class="fas fa-file-pdf" aria-hidden="true"></i></a>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </div>
                </div>
                
                <!-- /.card-footer -->
          </div>  <!-- Note end here --> 
          
      </div> 
      <!-- modal -->

        <div class="modal fade" id="QuestionModal" tabindex="-1" role="dialog" aria-labelledby="QuestionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="QuestionModalLabel">Add Question Paper</h5>
                    <button type="button" @click.prevent="cancel" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="createQuestion" @keydown="form.onKeydown($event)">
                         <div class="modal-body">
                            <div class="form-group">
                            <label>Text</label>
                            <textarea v-model="form.exam_text" style="resize:none" name="exam_text" id="exam_text" rows="5"
                              class="form-control" :class="{ 'is-invalid': form.errors.has('exam_text') } " placeholder="Enter text here...">
                            </textarea>
                            <has-error :form="form" field="exam_text"></has-error>
                            </div>

                            <div class="form-group">
                            <label>Class</label>
                            <select name="class_name" @change="onClassChange" type="text" v-model="form.class_name" id="class_name" :class="{ 'is-invalid': form.errors.has('class_name') }" class="form-control custom-select text-capitalize">
                                <option class="text-capitalize" v-for="info in this.classes" :key="info.id" :value="info.class_name">{{info.class_name}}</option>
                            </select>
                            <has-error :form="form" field="class_name"></has-error>
                            </div>

                            <div class="form-group">
                            <label>Subject</label>
                            <select name="subject_name" @change="onSubjectChange" type="text" v-model="form.subject_name" id="subject_name" :class="{ 'is-invalid': form.errors.has('subject_name') }" class="form-control custom-select text-capitalize" :disabled="isClass">
                                <option class="text-capitalize" v-for="info in this.subjects" :key="info.id" :value="info.subject_name">{{info.subject_name}}</option>
                            </select>
                            <has-error :form="form" field="subject_name"></has-error>
                            </div>

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
                isEmpty: true,
                isClass: true,
                selectclass:'',
                selectsubject:'',
                classname:'Class',
                subjectname: 'Subject',
                file_name: 'Choose file',
                percent: 0,
                info:{},
                classes:{},
                subjects:{},
                questionpaper:{},
                answerpaper:{},
                students:{},
                 form: new Form({
                    id:'',
                    class_name: '',
                    subject_name: '',
                    exam_text:'',
                    file_name:'',
                    file: null,
                    extension:''
                  })
            }
        },
        methods:{         
            loadCAS(){
                axios.get('/api/showclassesandsubjects').then(({data}) => (this.info = data))
            },
            loadClass(){
                let loader = this.$loading.show() 
                axios.get('/api/showclasses').then(({data}) => (this.classes = data)).finally(()=>{loader.hide()});
            },
            onClassChange(){
              this.subjects={};
                let loader = this.$loading.show() 
                axios.get('/api/subjects/'+ this.form.class_name).then(({data}) => (this.subjects = data)).finally(()=>{
                  loader.hide();
                  this.isClass= false;
                  this.form.errors.clear("class_name");
                });
            },
            onSubjectChange(){
              this.form.errors.clear("subject_name");
            },
            setStatus(id){
              this.form.put('/api/studentexam/'+id)
                .then(()=>{
                  Fire.$emit('ReloadQuestions');
                })
                .catch(()=>{})
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
                  if(type != 'PDF' && type != 'pdf'){
                    this.form.errors.set({
                    file_name:  'File format should be pdf only.'
                    })
                  } 
                   if(file['size'] > 209715200){
                    this.form.errors.set({
                    file:  'File size should be less than 200 MB.'
                    })
                  }
                }
            },
            cancel(){
              this.form.clear()
              this.isClass = true;
            },
            addQuestionModal(){
                this.form.clear();
                this.form.reset();
                this.file_name = 'Choose file'
                $('#QuestionModal').modal({
                    backdrop: 'static',
                    keyboard: false
                },'show');
            },
            getQuestionPaper(cls, subj){
              this.initial =false;
                let loader = this.$loading.show() 
                axios.get('/api/staffquestionpaper/'+ cls + '/' + subj)
                .then((response) => {
                  this.questionpaper = response.data.staffexam, 
                  this.students = response.data.studentsexam
                  this.isEmpty = false;
                })
                .finally(() => {
                      loader.hide()
                });
            },
            createQuestion(){
              this.initial =false;
                this.$Progress.start()
                this.form.post('/api/staffexam',{
                  onUploadProgress: progressEvent => {
                      if(progressEvent.lengthComputable){
                      this.percent = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))
                      }
                  }
                })
                .then((response)=>{
                  $('#QuestionModal').modal('hide')
                  if(response.data.errors == true){
                      Toast.fire({
                      icon: 'error',
                      title: 'Question paper already exist!'
                  })
                  this.$Progress.fail() 
                  }else{
                    this.questionpaper = response.data;
                    Toast.fire({
                      icon: 'success',
                      title: 'Question paper successfully uploaded.'
                  })
                  this.$Progress.finish() 

                  }
                                    
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
           if(this.$gate.isStaff()){
                this.loadClass()
                this.loadCAS()
            }
             Fire.$on('ReloadQuestions',()=>{
                this.getQuestionPaper(this.classname, this.subjectname);
            })
            
        }
    }
</script>
