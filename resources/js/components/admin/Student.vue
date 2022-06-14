<template>
    <div v-if="$gate.isAdmin()" class="mt-5">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Student</h3>
                
                <div class="card-tools p-1">
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-light dropdown-toggle text-dark" data-flip="false" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           Class {{cls | upText}}
                        </button>
                        <div class="dropdown-menu">
                            <div v-for="allclass in this.allclass" :key="allclass.id" @click="viewStudents(cls=allclass.class)" class="dropdown-item" href="#">{{allclass.class_name}}</div>
                        </div>
                    </div>
                    <button class="btn btn-success btn-sm" @click="addStudentModal">Add Student <i class="fas fa-user-plus"></i></button>
                </div>
              </div>
              <!-- card begin -->
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-student-tabContent">
                  <div class="tab-pane fade active show" id="custom-tabs-student-home" role="tabpanel" aria-labelledby="custom-tabs-student-home-tab">
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-center">
                            <thead>
                                <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Class</th>
                                <th>User Id</th>
                                <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="student in this.student.data" :key="student.id">
                                <td>{{student.id}}</td>
                                <td>{{student.first_name | upText}}</td>
                                <td>{{student.class | upText}}</td>
                                <td>{{student.user_id}}</td>
                                <td><div href="#" @click="editStudentModal(student)" class="btn btn-secondary btn-sm text-light"><i class="fas fa-edit"></i> Edit</div>
                                   <router-link :to="`/admin/profile/${student.user_id}`" class="btn btn-primary btn-sm text-light"><i class="fas fa-user"></i> profile</router-link></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="card-footer">
                 <!-- <pagination :data="users" @pagination-change-page="getResults"></pagination> -->
                 <pagination :data="student" @pagination-change-page="getResults">
                    <span slot="prev-nav"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Previous</span>
                    <span slot="next-nav">Next <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span>
                </pagination>
              </div>
              <!-- /.card end -->
            </div>
        <div class="modal fade" id="StudentModal" tabindex="-1" role="dialog" aria-labelledby="StudentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editMode" id="StudentModalLabel">Add New Student</h5>
                    <h5 class="modal-title" v-show="editMode" id="StudentModalLabel">Edit Student</h5>
                    <button type="button" @click.prevent="cancel" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editMode ? updateStudent() : createStudent()" @keydown="form.onKeydown($event)" enctype="multipart/form-data">
                         <div class="modal-body">
                            <div class="form-group">
                            <label>First-name *</label>
                            <input v-model="form.first_name" type="text" name="first_name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('first_name') }" placeholder="Enter First Name Here">
                            <has-error :form="form" field="first_name"></has-error>
                            </div>

                            <div class="form-group">
                            <label>Middle-name</label>
                            <input v-model="form.middle_name" type="text" name="middle_name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('middle_name') }" placeholder="Enter Middle Name Here">
                            <has-error :form="form" field="middle_name"></has-error>
                            </div>

                            <div class="form-group">
                            <label>Last-name *</label>
                            <input v-model="form.last_name" type="text" name="last_name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('last_name') }" placeholder="Enter Last Name Here">
                            <has-error :form="form" field="last_name"></has-error>
                            </div>

                            <div class="form-group">
                            <label>Phone Number</label>
                            <input v-model="form.phoneno" type="text" name="phoneno"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('phoneno') }" placeholder="Enter Phone Number Here">
                            <has-error :form="form" field="phoneno"></has-error>
                            </div>

                            <div class="form-group">
                            <label>Address</label>
                            <textarea v-model="form.address" row="4" name="address"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('address') }" placeholder="Enter Address Here"></textarea>
                            <has-error :form="form" field="address"></has-error>
                            </div>

                            <div class="form-group">
                            <label>User id *</label>
                            <input v-model="form.user_id" type="text" name="user_id"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('user_id') }" placeholder="Enter a valid user id Here">
                            <has-error :form="form" field="user_id"></has-error>
                            </div>

                            <div class="form-group">
                            <label>Class *</label>
                            <select name="class" @change="onStudentChange" type="text" v-model="form.class" id="class" class="form-control custom-select">
                                <option v-for="allclass in this.allclass" :key="allclass.id" :value="allclass.class">{{allclass.class_name}}</option>
                            </select>
                            <has-error v-if="valid == false" :form="form" field="class"></has-error>
                            </div>

                            <div class="form-group">
                            <label>Exam *</label>
                            <select name="exam" type="text" v-model="form.examstatus" id="exam" class="form-control custom-select">
                                <option value="allowed">Allowed</option>
                                <option value="disallowed">Disllowed</option>
                            </select>
                            <has-error :form="form" field="examstatus"></has-error>
                            </div>

                            <div class="form-group">
                            <label>Profile Image</label>
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
                            <label v-if="percent > 0">Uploading: {{percent}}%</label>
                            <label v-if="percent == 100">Please wait....</label>
                            </div>
                        </div> 
                        <div class="modal-footer">
                            <button :disabled="form.busy" v-show="!editMode" type="submit" class="btn btn-success btn-sm">Add Student</button>
                            <button :disabled="form.busy" v-show="editMode" type="submit" class="btn btn-success btn-sm">Update Student</button>
                            <button @click="cancel" type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cancel</button>
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
                cls: 'tinytots',
                student: {},
                allclass:{},
                percent: 0,
                valid: false,
                editMode: false,
                showImage: null,
                file_name: 'Choose file',
                form: new Form({
                    id: '',
                    first_name: '',
                    middle_name: '',
                    last_name: '',
                    class: '',
                    address:'',
                    phoneno:'',
                    user_id: '',
                    photo:'',
                    extension:'',
                    examstatus:''
                })
            }
        },
        methods:{
           getResults(page = 1) {
                let loader = this.$loading.show() 
                axios.get('/api/viewstudents/'+this.cls+'/?page=' + page)
                    .then(response => {
                        this.student = response.data
                    }).finally(()=>{
                        loader.hide()
                    })
            },
            loadClass(){
                let loader = this.$loading.show() 
                axios.get('api/allclass')
                    .then(response => {
                        this.allclass = response.data
                    }).finally(()=>{
                        loader.hide()
                    })
            },
            cancel(){
              this.form.clear();
            },
            onStudentChange(){
                this.valid= true
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
                  if(type != 'png' && type != 'jpg' && type != 'jpeg'){
                    this.form.errors.set({
                    photo:  'File format should be png, jpg or jpeg only.'
                    })
                  } 
                  else if(file['size'] > 1572864){
                    this.form.errors.set({
                    photo:  'File size should be less than 1.5 MB.'
                    })
                  }
                  else{
                  this.showImage = URL.createObjectURL(file);
                  this.form.errors.clear("photo")
                  }
                }
            },
            addStudentModal(){
                this.editMode = false;
                this.form.clear();
                this.form.reset();
                this.file_name = 'Choose file'
                $('#StudentModal').modal({
                    backdrop: 'static',
                    keyboard: false
                },'show');
            },
            editStudentModal(student){
                this.editMode = true;   
                this.form.clear();
                this.form.reset();
                this.file_name = 'Choose file'
                $('#StudentModal').modal({
                    backdrop: 'static',
                    keyboard: false
                },'show');
                this.form.fill(student);
            },

            searchStudent(){
                axios.get('/api/searchstudent?query='+ this.$parent.search +'&class='+ this.cls)
                .then((data) =>{
                    this.student = data.data
                })
                .catch(() =>{

                })
            },

            updateStudent(){
                this.$Progress.start()
                this.form.put('/api/student/'+this.form.id,{
                  onUploadProgress: progressEvent => {
                      if(progressEvent.lengthComputable){
                      this.percent = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))
                      }
                  }
                })
                .then(()=>{
                    $('#StudentModal').modal('hide')
                    Toast.fire({
                        icon: 'success',
                        title: 'Student successfully Updated'
                    })
                    this.$Progress.final()
                })
                .catch(()=>{
                    this.$Progress.fail()
                })
                .finally(() => {
                    Fire.$emit('ReloadStudent')
                    this.percent = 0;
                })
            },
            createStudent(){
                this.$Progress.start()
                this.form.post('/api/student',{
                  onUploadProgress: progressEvent => {
                      if(progressEvent.lengthComputable){
                      this.percent = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))
                      }
                  }
                })
                .then(() => {
                    $('#StudentModal').modal('hide')
                    Toast.fire({
                        icon: 'success',
                        title: 'Student added successfully'
                    })
                    this.$Progress.finish()
                })
                .catch(() => {
                    this.$Progress.fail()
                })
                .finally(() => {
                    Fire.$emit('ReloadStudent')
                    this.percent = 0;
                })
            },
            viewStudents(cls){
                let loader = this.$loading.show() 
                axios.get('/api/viewstudents/'+cls).then(({data}) => (this.student = data)).finally(() => {loader.hide()})
            }

        },
        mounted() {
            Fire.$on('searching',() =>{
                this.searchStudent();
            })
            if(this.$gate.isAdmin()){
                this.viewStudents('tinytots')
                this.loadClass()
            }
            Fire.$on('ReloadStudent',()=>{
                this.getResults(this.cls)
            })
        }
    }
</script>