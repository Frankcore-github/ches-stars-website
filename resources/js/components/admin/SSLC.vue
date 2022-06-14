
<template>
     <div v-if="$gate.isAdmin()" class="mt-5">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">SSLC</h3>
                <div class="card-tools p-1">
                    <button class="btn btn-success btn-sm" @click="addSSLCModal">Add SSLC <i class="fas fa-calendar-plus nav-icon"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-center">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>No Of Students</th>
                      <th>NO Of Passed Student</th>
                      <th>Year</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="sslc in this.sslc.data" :key="sslc.id">
                      <td>{{sslc.id}}</td>
                      <td>{{sslc.no_of_student}}</td>
                      <td>{{sslc.no_of_passed_student}}</td>
                      <td>{{sslc.year}}</td>
                      <td><div href="#" @click="editSSLCModal(sslc)" class="btn btn-secondary btn-sm text-light"><i class="fas fa-edit"></i> Edit</div>   
                      <div href="#" @click="deleteSSLC(sslc.id)" class="btn btn-danger btn-sm text-light"><i class="fas fa-trash"></i> Delete</div></td>
                      
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                 <!-- <pagination :data="users" @pagination-change-page="getResults"></pagination> -->
                 <pagination :data="sslc" @pagination-change-page="getResults">
                    <span slot="prev-nav"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Previous</span>
                    <span slot="next-nav">Next <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span>
                </pagination>
              </div>
            </div>
            <!-- /.card -->
        <div class="modal fade" id="SSLCModal" tabindex="-1" role="dialog" aria-labelledby="SSLCModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editMode" id="SSLCModalLabel">Add New User</h5>
                    <h5 class="modal-title" v-show="editMode" id="SSLCModalLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editMode ? updateSSLC() : createSSLC()" @keydown="form.onKeydown($event)">
                         <div class="modal-body">
                            <div class="form-group">
                            <label>No Of Students</label>
                            <input v-model="form.no_of_student" type="text" name="no_of_student"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('no_of_student') }" placeholder="Enter The # Of Students Here">
                            <has-error :form="form" field="no_of_student"></has-error>
                            </div>

                            <div class="form-group">
                            <label>No Of Passed Students</label>
                            <input v-model="form.no_of_passed_student" type="text" name="no_of_passed_student"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('no_of_passed_student') }" placeholder="Enter The # Of Passed Students Here">
                            <has-error :form="form" field="no_of_passed_student"></has-error>
                            </div>

                            <div class="form-group">
                            <label>Year</label>
                            <input v-model="form.year" type="number" name="year"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('year') }" placeholder="Enter The Year Here">
                            <has-error :form="form" field="year"></has-error>
                            </div>

                            <div class="form-group">
                            <label v-if="percent > 0">Uploading: {{percent}}%</label>
                            <label v-if="percent == 100">Please wait....</label>
                        </div>
                        </div> 
                        <div class="modal-footer">
                            <button :disabled="form.busy" v-show="!editMode" type="submit" class="btn btn-success btn-sm">Add SSLC</button>
                            <button :disabled="form.busy" v-show="editMode" type="submit" class="btn btn-success btn-sm">Update SSLC</button>
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
                sslc: {},
                percent: 0,
                editMode: false,
                form: new Form({
                    id: '',
                    no_of_student: '',
                    no_of_passed_student: '',
                    year: ''
                })
            }
        },
        methods:{
            getResults(page = 1) {
                let loader = this.$loading.show() 
                axios.get('/api/sslc?page=' + page)
                .then(response => {
                  this.sslc = response.data
                }).finally(()=>{
                    loader.hide()
                })
            },
            addSSLCModal(){
                this.editMode = false
                this.form.reset();
                $('#SSLCModal').modal({
                    backdrop: 'static',
                    keyboard: false
                },'show');
            },
            editSSLCModal(sslc){
                this.editMode = true
                this.form.reset()
                $('#SSLCModal').modal({
                    backdrop: 'static',
                    keyboard: false
                },'show')
                this.form.fill(sslc)
            },
            updateSSLC(){
                this.$Progress.start()
                this.form.put('/api/sslc/'+this.form.id,{
                  onUploadProgress: progressEvent => {
                      if(progressEvent.lengthComputable){
                      this.percent = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))
                      }
                  }
                })
                .then(()=>{
                    $('#SSLCModal').modal('hide')
                    Toast.fire({
                        icon: 'success',
                        title: 'SSLC successfully Updated'
                    })
                    this.$Progress.finish()
                })
                .catch(()=>{
                    this.$Progress.fail()
                })
                .finally(() => {
                    Fire.$emit('ReloadSSLC')
                    this.percent = 0;
                })
            },
            createSSLC(){
                this.$Progress.start()
                this.form.post('/api/sslc',{
                  onUploadProgress: progressEvent => {
                      if(progressEvent.lengthComputable){
                      this.percent = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))
                      }
                  }
                })
                .then(() => {
                    $('#SSLCModal').modal('hide')
                    Toast.fire({
                        icon: 'success',
                        title: 'SSLC added successfully'
                    })
                    this.$Progress.finish()
                })
                .catch(() => {
                    this.$Progress.fail()
                })
                .finally(() => {
                    Fire.$emit('ReloadSSLC')
                    this.percent = 0;
                })
            },
            deleteSSLC(id){
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
                        this.form.delete('api/sslc/'+id)
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
                            Fire.$emit('ReloadSSLC')
                        })
                    }
                    })
            }
        },
        mounted() {
             if(this.$gate.isAdmin()){
                this.getResults();
            }
            Fire.$on('ReloadSSLC',()=>{
                this.getResults();
            })
        }
    }
</script>