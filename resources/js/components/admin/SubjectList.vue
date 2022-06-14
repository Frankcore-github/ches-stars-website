<template>
    <div v-if="$gate.isAdmin()" class="mt-5">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Subject List</h3>
                
                <div class="card-tools p-1">
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-light dropdown-toggle text-dark" data-flip="false" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           Class {{cls | upText }}
                        </button>
                        <div  class="dropdown-menu dropdown-menu-right">
                            <div v-for="allclass in this.allclass" :key="allclass.id" @click="viewSubjects(cls=allclass.class)" class="dropdown-item" href="#">{{allclass.class_name}}</div>
                        </div>
                    </div>
                    <button class="btn btn-success btn-sm" @click="addSubjectListModal">Add Subject <i class="fas fa-book"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-center">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Subject</th>
                      <th>Teacher</th>
                      <th>Background Color</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="subject in this.subject.data" :key="subject.id">
                      <td>{{subject.id}}</td>
                      <td>{{subject.subject_name}}</td>
                      <td v-if="subject.staff">{{subject.staff.first_name+' '+subject.staff.last_name}}</td>
                      <td v-else>N/A</td>
                      <td>{{subject.background_color}}
                        <svg height="30" width="30">
                            <circle cx="16" cy="13" r="12" :fill="subject.background_color" />
                        </svg></td>
                      <td><div  href="#" @click="editSubjectListModal(subject)" class="btn btn-secondary btn-sm text-light"><i class="fas fa-edit"></i> Edit</div> 
                        <div href="#" @click="deleteSubjectList(subject.id)" class="btn btn-danger btn-sm text-light"><i class="fas fa-trash"></i> Delete</div></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                 <!-- <pagination :data="users" @pagination-change-page="getResults"></pagination> -->
                 <pagination :data="subject" @pagination-change-page="getResults">
                    <span slot="prev-nav"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Previous</span>
                    <span slot="next-nav">Next <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span>
                </pagination>
              </div>
            </div>
            <!-- /.card -->
            <div class="modal fade" id="SubjectListModal" tabindex="-1" role="dialog" aria-labelledby="SubjectListModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editMode" id="SubjectListLabel">Add New Subject</h5>
                    <h5 class="modal-title" v-show="editMode" id="SubjectListLabel">Edit Subject</h5>
                    <button type="button" @click.prevent="cancel" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editMode ? updateSubjectList() : createSubjectList()" @keydown="form.onKeydown($event)" enctype="multipart/form-data">
                         <div class="modal-body">
                            <div class="form-group">
                            <label>Class *</label>
                            <select name="class_name" @change="onSubjectListChange" type="text" v-model="form.class_name" id="class_name" class="form-control custom-select">
                                <option v-for="allclass in this.allclass" :key="allclass.id" :value="allclass.class">{{allclass.class_name}}</option>
                            </select>
                            <has-error v-if="valid == false" :form="form" field="class_name"></has-error>
                            </div>

                            <div class="form-group">
                            <label>Subject Name *</label>
                            <input v-model="form.subject_name" type="text" name="subject_name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('subject_name') }" placeholder="Enter Subject Name Here">
                            <has-error :form="form" field="subject_name"></has-error>
                            </div>
                            
                            <div class="form-group">
                            <label>Teacher *</label>
                            <select name="staff_id" @change="onStaffChange" type="text" v-model="form.staff_id" id="staff_id" class="form-control custom-select">
                                <option v-for="staff in this.staff" :key="staff.id" :value="staff.id">{{staff.first_name+' '+staff.last_name}}</option>
                            </select>
                            <has-error v-if="s_valid == false" :form="form" field="staff_id"></has-error>
                            </div>

                            <div class="form-group">
                            <label>Background Color *</label>
                                <ColorPicker class="m-auto" :width="200" :height="200" :disabled="false" startColor="#ff0000" @color-change="onColorChange"></ColorPicker>
                                <div class="selected-color-info">
                                    Selected color:
                                    <svg height="32" width="32">
                                    <circle cx="16" cy="16" r="15" :fill="form.background_color" />
                                    </svg>
                                </div>
                            </div>

                            <div class="form-group">
                            <label v-if="percent > 0">Uploading: {{percent}}%</label>
                            <label v-if="percent == 100">Please wait....</label>
                            </div>
                        </div> 
                        <div class="modal-footer">
                            <button :disabled="form.busy" v-show="!editMode" type="submit" class="btn btn-success btn-sm">Add Subject</button>
                            <button :disabled="form.busy" v-show="editMode" type="submit" class="btn btn-success btn-sm">Update Subject</button>
                            <button @click="cancel" type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cancel</button>
                        </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ColorPicker from 'vue-color-picker-wheel' 
    export default {
        components: {
            ColorPicker
        },
        data() {
            return{
                allclass:{},
                cls:'tinytots',
                percent: 0,
                editMode: false,
                valid: false,
                s_valid: false,
                subject: {},
                staff:{},
                form: new Form({
                    id: '',
                    class_name: '',
                    subject_name: '',
                    background_color:'#ff0000',
                    staff_id:''
                })
            }
        },
        methods: {
            getResults(page = 1) {
                let loader = this.$loading.show() 
                axios.get('/api/viewsubjects/'+this.cls+'/?page=' + page)
                    .then(response => {
                        this.subject = response.data
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
              this.form.clear()
            },
            onColorChange(color) {
                this.form.background_color = color
            },
            onSubjectListChange(){
                this.valid= true
            },
            onStaffChange(){
                this.s_valid= true
            },
            addSubjectListModal(){
                axios.get('/api/allstaff').then(response => { this.staff = response.data });
                this.editMode = false
                this.form.clear()
                this.form.reset()
                $('#SubjectListModal').modal({
                    backdrop: 'static',
                    keyboard: false
                },'show')
            },
            editSubjectListModal(subject){
                axios.get('/api/allstaff').then(response => { this.staff = response.data });
                this.editMode = true
                this.form.clear()
                this.form.reset()
                $('#SubjectListModal').modal({
                    backdrop: 'static',
                    keyboard: false
                },'show');
                this.form.fill(subject)
            },  
            searchSubject(){
                axios.get('/api/searchsubject?query='+ this.$parent.search +'&class='+this.cls)
                .then((data) =>{
                    this.subject = data.data
                })
                .catch(() =>{

                })
            },
            createSubjectList(){
                this.$Progress.start()
                this.form.post('/api/subjectlist',{
                  onUploadProgress: progressEvent => {
                      if(progressEvent.lengthComputable){
                      this.percent = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))
                      }
                  }
                })
                .then(function (response) {
                    $('#SubjectListModal').modal('hide')
                    Toast.fire({
                        icon: 'success',
                        title: 'Subject added successfully'
                    })
                    this.$Progress.finish()
                })
                .catch(() => {
                    this.$Progress.fail()
                })
                .finally(() => {
                    Fire.$emit('ReloadSubjectList')
                    this.percent = 0;
                })

                
            },
            updateSubjectList(){
                    this.$Progress.start()
                    this.form.put('/api/subjectlist/'+this.form.id,{
                  onUploadProgress: progressEvent => {
                      if(progressEvent.lengthComputable){
                      this.percent = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))
                      }
                  }
                })
                    .then(()=>{
                        $('#SubjectListModal').modal('hide')
                        Toast.fire({
                            icon: 'success',
                            title: 'Subject successfully Updated'
                        })  
                        this.$Progress.finish()
                    })
                    .catch(()=>{
                        this.$Progress.fail()
                    })
                    .finally(() => {
                        Fire.$emit('ReloadSubjectList')
                        this.percent = 0;
                    })
            },
            deleteSubjectList(id){
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
                       this.form.delete('/api/subjectlist/'+id)
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
                       .finally(() => {
                            Fire.$emit('ReloadSubjectList')
                        })
                    }
                    })
            },
            viewSubjects(cls){
                let loader = this.$loading.show() 
                axios.get('/api/viewsubjects/'+cls).then(({data}) => (this.subject = data)).finally(()=>{
                    loader.hide()
                });
            }
        },
        mounted() {  
            Fire.$on('searching',() =>{
                this.searchSubject();
            })
            if(this.$gate.isAdmin()){
                this.viewSubjects('tinytots')
                this.loadClass()
            }
            Fire.$on('ReloadSubjectList',()=>{
                this.getResults()
            })
        }
    }
</script>
