<template>
    <div  v-if="$gate.isAdmin()" class="mt-5">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users</h3>
                <div class="card-tools p-1">
                    <button class="btn btn-success btn-sm" @click="addUserModal">Add User <i class="fas fa-user-plus"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-center">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Username</th>
                      <th>Usertype</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="user in this.users.data" :key="user.id">
                      <td>{{user.id}}</td>
                      <td>{{user.username}}</td>
                      <td>{{user.usertype | upText}}</td>
                      <td><div href="#" @click="editUserModal(user)" class="btn btn-secondary btn-sm text-light"><i class="fas fa-edit"></i> Edit</div>
                         <div href="#" @click="deleteUser(user.id)" class="btn btn-danger btn-sm text-light"><i class="fas fa-trash"></i> Delete</div></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                 <!-- <pagination :data="users" @pagination-change-page="getResults"></pagination> -->
                 <pagination :data="users" @pagination-change-page="getResults">
                    <span slot="prev-nav"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Previous</span>
                    <span slot="next-nav">Next <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span>
                </pagination>
              </div>
            </div>
            <!-- /.card -->
        <div class="modal fade" id="UserModal" tabindex="-1" role="dialog" aria-labelledby="UserModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editMode" id="UserModalLabel">Add New User</h5>
                    <h5 class="modal-title" v-show="editMode" id="UserModalLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editMode ? updateUser() : createUser()" @keydown="form.onKeydown($event)">
                         <div class="modal-body">
                            <div class="form-group">
                            <label>Username</label>
                            <input v-model="form.username" type="text" name="username"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('username') }" placeholder="Enter Username Here">
                            <has-error :form="form" field="username"></has-error>
                            </div>

                         <div v-show="!editMode" class="form-group">
                            <label>Usertype</label>
                            <select name="usertype" @change="onUserChange()" type="text" v-model="form.usertype" id="usertype" class="form-control custom-select">
                                <option value="admin">Admin</option>
                                <option value="staff">Staff</option>
                                <option value="student">Student</option>
                            </select>
                            <has-error v-if="valid == false" :form="form" field="usertype"></has-error>
                            </div>

                            <div class="form-group">
                            <label>Password</label>
                            <input v-model="form.password" type="password" name="password"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('password') }" placeholder="Enter Password Here">
                            <has-error :form="form" field="password"></has-error>
                            </div>

                            <div class="form-group">
                            <label>Repeat Password</label>
                            <input v-model="form.repeat_password" type="password" name="repeat_password"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('repeat_password') }" v-on:key="checkPassword" placeholder="Repeat Password Here">
                            <has-error :form="form" field="repeat_password"></has-error>
                            </div>
                            
                            <div class="form-group">
                            <label v-if="percent > 0">Uploading: {{percent}}%</label>
                            <label v-if="percent == 100">Please wait....</label>
                            </div>
                        </div> 
                        <div class="modal-footer">
                            <button :disabled="form.busy" v-show="!editMode" type="submit" class="btn btn-success btn-sm">Add User</button>
                            <button :disabled="form.busy" v-show="editMode" type="submit" class="btn btn-success btn-sm">Update User</button>
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
                users: {},
                form: new Form({
                    id: '',
                    username: '',
                    usertype: '',
                    password: '',
                    repeat_password:'',
                })

            }
        },
        methods: {
            getResults(page = 1) {
                let loader = this.$loading.show() 
                axios.get('/api/user?page=' + page)
                    .then(response => {
                        this.users = response.data
                    }).finally(()=>{
                        loader.hide()
                    })
            },
            cancel(){
              this.form.clear();
            },
            onUserChange(){
                this.valid= true
            },
            checkPassword(){
                
            },
            addUserModal(){
                this.editMode = false;
                this.form.clear();
                this.form.reset();
                $('#UserModal').modal({
                    backdrop: 'static',
                    keyboard: false
                },'show');
            },
            editUserModal(user){
                this.editMode = true;
                this.form.clear();
                this.form.reset();
                $('#UserModal').modal({
                    backdrop: 'static',
                    keyboard: false
                },'show');
                this.form.fill(user);
            },

            searchUser(query){
                axios.get('/api/searchuser?query='+ query)
                .then((data) =>{
                    this.users = data.data
                })
                .catch(() =>{

                })
            },

            createUser(){
                this.$Progress.start()
                this.form.post('api/user',{
                  onUploadProgress: progressEvent => {
                      if(progressEvent.lengthComputable){
                      this.percent = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))
                      }
                  }
                })
                .then(() => {
                    $('#UserModal').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: 'User added successfully'
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
                    Fire.$emit('ReloadUser')
                    this.percent = 0;
                })
                
            },
            updateUser(){
                    if(this.form.password == ""){
                        this.form.password = undefined;
                        }
                    if(this.form.repeat_password == ""){
                        this.form.repeat_password = undefined;
                    }
                     if(this.form.password != this.form.repeat_password ){
                        this.form.errors.set({
                        repeat_password:  'The repeat password and password must match.'
                    })
                }else if(this.form.password == this.form.repeat_password ){
                    this.$Progress.start()
                    this.form.put('api/user/'+this.form.id,{
                  onUploadProgress: progressEvent => {
                      if(progressEvent.lengthComputable){
                      this.percent = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))
                      }
                  }
                })
                    .then(()=>{
                        $('#UserModal').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: 'User successfully Updated'
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
                        Fire.$emit('ReloadUser')
                        this.percent = 0;
                    })

                }
            },
            deleteUser(id){
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
                        this.form.delete('/api/user/'+id)
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
                            Fire.$emit('ReloadUser')
                         })
                    }
                    })
            }
        },
        mounted() { 
            Fire.$on('searching',() =>{
                this.searchUser(this.$parent.search);
            })
            if(this.$gate.isAdmin()){
                this.getResults();
            }
            Fire.$on('ReloadUser',()=>{
                this.getResults();
            })
        }
    }
</script>
