<template>
    <div v-if="$gate.isAdmin()" class="mt-5">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Class Teachers</h3>
                <div class="card-tools p-1">
                    <button class="btn btn-success btn-sm" @click="addClassTeacherModal">Add Class Teacher <i class="fas fa-user-plus"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-center">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Class</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="teacher in this.teacher.data" :key="teacher.id">
                      <td>{{teacher.id}}</td>
                      <td>{{teacher.first_name}}</td>
                      <td>{{teacher.class}}</td>
                      <td><div href="#" @click="editClassTeacherModal(teacher)" class="btn btn-secondary btn-sm text-light"><i class="fas fa-edit"></i> Edit</div>
                          <div href="#" @click="deleteClassTeacher(teacher.id)" class="btn btn-danger btn-sm text-light"><i class="fas fa-trash"></i> Delete</div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                 <!-- <pagination :data="users" @pagination-change-page="getResults"></pagination> -->
                 <pagination :data="teacher" @pagination-change-page="getResults">
                    <span slot="prev-nav"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Previous</span>
                    <span slot="next-nav">Next <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span>
                </pagination>
              </div>
            </div>
            <!-- /.card -->
        <div class="modal fade" id="ClassTeacherModal" tabindex="-1" role="dialog" aria-labelledby="ClassTeacherModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editMode" id="ClassTeacherModalLabel">Add New Staff</h5>
                    <h5 class="modal-title" v-show="editMode" id="ClassTeacherModalLabel">Edit Staff</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editMode ? updateClassTeacher() : createClassTeacher()" @keydown="form.onKeydown($event)">
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
                            <label>Class *</label>
                            <select name="class" @change="onClassTeacherChange()" type="text" v-model="form.class" id="class" class="form-control custom-select">
                                <option v-for="allclass in this.allclass" :key="allclass.id" :value="allclass.class">{{allclass.class_name}}</option>
                            </select>
                            <has-error v-if="valid == false" :form="form" field="class"></has-error>
                            </div>
                            
                            <div class="form-group">
                            <label v-if="percent > 0">Uploading: {{percent}}%</label>
                            <label v-if="percent == 100">Please wait....</label>
                            </div>
                        </div> 
                        <div class="modal-footer">
                            <button :disabled="form.busy" v-show="!editMode" type="submit" class="btn btn-success btn-sm">Add Class Teacher</button>
                            <button :disabled="form.busy" v-show="editMode" type="submit" class="btn btn-success btn-sm">Update Class Teacher</button>
                            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cancel</button>
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
                allclass:{},
                teacher: {},
                percent:0,
                valid: false,
                valid: false,
                editMode: false,
                form: new Form({
                    id: '',
                    first_name: '',
                    middle_name: '',
                    last_name: '',
                    class: ''
                })
            }
        },
        methods:{
            getResults(page = 1) {
                let loader = this.$loading.show() 
                axios.get('/api/teacher?page=' + page)
                .then(response => {
                  this.teacher = response.data
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
            onClassTeacherChange(){
                this.valid= true
            },
            addClassTeacherModal(){
                this.editMode = false;
                this.form.reset();
                $('#ClassTeacherModal').modal({
                    backdrop: 'static',
                    keyboard: false
                },'show');
            },
            editClassTeacherModal(teacher){
                this.editMode = true;
                this.form.reset();
                $('#ClassTeacherModal').modal({
                    backdrop: 'static',
                    keyboard: false
                },'show');
                this.form.fill(teacher);
            },
            updateClassTeacher(){
                this.$Progress.finish()
                this.form.put('/api/teacher/'+this.form.id,{
                  onUploadProgress: progressEvent => {
                      if(progressEvent.lengthComputable){
                      this.percent = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))
                      }
                  }
                })
                .then(()=>{
                    $('#ClassTeacherModal').modal('hide')
                    Toast.fire({
                        icon: 'success',
                        title: 'Teacher successfully Updated'
                    })
                    this.$Progress.finish()
                })
                .catch(()=>{
                    this.$Progress.fail()
                })
                .finally(()=>{
                    Fire.$emit('ReloadClassTeacher')
                    this.percent = 0;
                })
            },
            createClassTeacher(){
                this.$Progress.start()
                this.form.post('/api/teacher',{
                  onUploadProgress: progressEvent => {
                      if(progressEvent.lengthComputable){
                      this.percent = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))
                      }
                  }
                })
                .then(() => {
                    $('#ClassTeacherModal').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: 'Teacher added successfully'
                    })
                    this.$Progress.finish()
                })
                .catch(() => {
                    this.$Progress.fail()
                })
                .finally(()=>{
                    Fire.$emit('ReloadClassTeacher')
                    this.percent = 0;
                })
            },
            deleteClassTeacher(id){
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
                        this.form.delete('api/teacher/'+id)
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
                            Fire.$emit('ReloadClassTeacher');
                       })
                    }
                    })
            }
        },
        mounted() {
            if(this.$gate.isAdmin()){
                this.getResults();
                this.loadClass();
            }
            Fire.$on('ReloadClassTeacher',()=>{
                this.getResults();
            })
        }
    }
</script>
