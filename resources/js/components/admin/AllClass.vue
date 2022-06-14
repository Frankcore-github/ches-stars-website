<template>
    <div  v-if="$gate.isAdmin()" class="mt-5">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Classes</h3>
                <div class="card-tools p-1">
                    <button class="btn btn-success btn-sm" @click="addClassModal">Add Class <i class="fas fa-plus"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-center">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Class</th>
                      <th>CLass Name</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="allclass in this.allclass" :key="allclass.id">
                      <td>{{allclass.id}}</td>
                      <td>{{allclass.class}}</td>
                      <td>{{allclass.class_name}}</td>
                      <td><div @click="editClassModal(allclass)" class="btn btn-secondary btn-sm text-light"><i class="fas fa-edit"></i> Edit</div>
                         <div @click="deleteClass(allclass.id)" class="btn btn-danger btn-sm text-light"><i class="fas fa-trash"></i> Delete</div></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.card -->
        <div class="modal fade" id="ClassModal" tabindex="-1" role="dialog" aria-labelledby="ClassModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editMode" id="ClassModalLabel">Add New Class</h5>
                    <h5 class="modal-title" v-show="editMode" id="ClassModalLabel">Edit Class</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editMode ? updateClass() : createClass()" @keydown="form.onKeydown($event)">
                         <div class="modal-body">
                            <div class="form-group">
                            <label>Class</label>
                            <input v-model="form.class" type="text" name="class"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('class') }" placeholder="Enter Class Here in lowercase (no space)">
                            <has-error :form="form" field="class"></has-error>
                            </div>

                            <div class="form-group">
                            <label>Class Name</label>
                            <input v-model="form.class_name" type="text" name="class_name"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('class_name') }" placeholder="Enter Class Name Here">
                            <has-error :form="form" field="class_name"></has-error>
                            </div>
                            
                            <div class="form-group">
                            <label v-if="percent > 0">Uploading: {{percent}}%</label>
                            <label v-if="percent == 100">Please wait....</label>
                            </div>
                        </div> 
                        <div class="modal-footer">
                            <button :disabled="form.busy" v-show="!editMode" type="submit" class="btn btn-success btn-sm">Add Class</button>
                            <button :disabled="form.busy" v-show="editMode" type="submit" class="btn btn-success btn-sm">Update Class</button>
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
                isLoading: false,
                fullPage: true,
                percent: 0,
                editMode: false,
                valid: false,
                allclass: {},
                form: new Form({
                    id: '',
                    class: '',
                    class_name: '',
                })

            }
        },
        methods: {
             load(){
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
            addClassModal(){
                this.editMode = false;
                this.form.clear();
                this.form.reset();
                $('#ClassModal').modal({
                    backdrop: 'static',
                    keyboard: false
                },'show');
            },
            editClassModal(allclass){
                this.editMode = true;
                this.form.clear();
                this.form.reset();
                $('#ClassModal').modal({
                    backdrop: 'static',
                    keyboard: false
                },'show');
                this.form.fill(allclass);
            },

            createClass(){
                this.$Progress.start()
                this.form.post('api/allclass',{
                  onUploadProgress: progressEvent => {
                      if(progressEvent.lengthComputable){
                      this.percent = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))
                      }
                  }
                })
                .then(() => {
                    $('#ClassModal').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: 'Class added successfully'
                    })
                    this.$Progress.finish()
                })
                .catch(() => {
                    this.$Progress.fail()
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'You are not authorize to change',
                    })
                })
                .finally(() => {
                    Fire.$emit('ReloadClass')
                    this.percent = 0;
                })
                
            },
            updateClass(){
                    this.$Progress.start()
                    this.form.put('api/allclass/'+this.form.id,{
                  onUploadProgress: progressEvent => {
                      if(progressEvent.lengthComputable){
                      this.percent = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))
                      }
                  }
                })
                    .then(()=>{
                        $('#ClassModal').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: 'Class successfully Updated'
                        }) 
                        this.$Progress.finish()
                    })
                    .catch(()=>{
                        this.$Progress.fail()
                        Swal.fire({
                            icon: 'warning',
                            title: 'Oops...',
                            text: 'You are not authorize to change'
                        })
                    })
                    .finally(() => {
                        Fire.$emit('ReloadClass')
                        this.percent = 0;
                    })
            },
            deleteClass(id){
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
                        this.form.delete('/api/allclass/'+id)
                       .then(()=>{
                            Swal.fire(
                                'Deleted!',
                                'User has been deleted.',
                                'success'
                            )
                        this.$Progress.finish()
                        })
                       .catch(()=>{
                            this.$Progress.fail()
                            Swal.fire({
                                icon: 'warning',
                                title: 'Oops...',
                                text: 'You are not authorize to change',
                            })
                        })
                        .finally(() => {
                            Fire.$emit('ReloadClass')
                         })
                    }
                    })
            }
        },
        mounted() { 
            if(this.$gate.isAdmin()){
                this.load();
            }
            Fire.$on('ReloadClass',()=>{
                this.load();
            })
        }
    }
</script>
