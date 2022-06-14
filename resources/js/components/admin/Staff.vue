<template>
    <div v-if="$gate.isAdmin()" class="mt-5">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Staff</h3>
                <div class="card-tools p-1">
                    <button class="btn btn-success btn-sm" @click="addStaffModal">Add Staff <i class="fas fa-user-plus"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-center">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>User Id</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="staff in this.staff.data" :key="staff.id">
                      <td>{{staff.id}}</td>
                      <td>{{staff.first_name | upText}}</td>
                      <td>{{staff.user_id}}</td>
                      <td><div href="#" @click="editStaffModal(staff)" class="btn btn-secondary btn-sm text-light"><i class="fas fa-edit"></i> Edit</div>
                         <router-link :to="`/admin/profile/${staff.user_id}`" class="btn btn-primary btn-sm text-light"><i class="fas fa-user"></i> profile</router-link></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                 <!-- <pagination :data="users" @pagination-change-page="getResults"></pagination> -->
                 <pagination :data="staff" @pagination-change-page="getResults">
                    <span slot="prev-nav"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Previous</span>
                    <span slot="next-nav">Next <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span>
                </pagination>
              </div>
            </div>
            <!-- /.card -->
        <div class="modal fade" id="StaffModal" tabindex="-1" role="dialog" aria-labelledby="StaffModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editMode" id="StaffModalLabel">Add New Staff</h5>
                    <h5 class="modal-title" v-show="editMode" id="StaffModalLabel">Edit Staff</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editMode ? updateStaff() : createStaff()" @keydown="form.onKeydown($event)">
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
                            <label>Phone Number </label>
                            <input v-model="form.phoneno" type="number" name="phoneno"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('phoneno') }" placeholder="Enter Phone Number Here">
                            <has-error :form="form" field="phoneno"></has-error>
                            </div>

                            <div class="form-group">
                            <label>Classes </label>
                            <input v-model="form.classes" type="text" name="classes"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('classes') }" placeholder="Enter Classes Here (seperated with commma)">
                            <has-error :form="form" field="classes"></has-error>
                            </div>

                            <div class="form-group">
                            <label>Address </label>
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
                                <button :disabled="form.busy" v-show="!editMode" type="submit" class="btn btn-success btn-sm">Add Staff</button>
                                <button :disabled="form.busy" v-show="editMode" type="submit" class="btn btn-success btn-sm">Update Staff</button>
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
                file_name: 'Choose file',
                staff: {},
                percent: 0,
                valid: false,
                editMode: false,
                showImage: null,
                form: new Form({
                    id: '',
                    first_name: '',
                    middle_name: '',
                    last_name: '',
                    classes: '',
                    address:'',
                    phoneno:'',
                    user_id: '',
                    photo:'',
                    extension:''
                })
            }
        },
        methods:{
            getResults(page = 1) {
                let loader = this.$loading.show() 
                axios.get('/api/staff?page=' + page)
                .then(response => {
                  this.staff = response.data
                }).finally(()=>{
                    loader.hide()
                })
            },
            cancel(){
              this.form.clear();
            },
            addStaffModal(){
                this.editMode = false;
                this.form.reset();
                $('#StaffModal').modal({
                    backdrop: 'static',
                    keyboard: false
                },'show');
            },
            editStaffModal(staff){
                this.editMode = true;
                this.form.reset();
                $('#StaffModal').modal({
                    backdrop: 'static',
                    keyboard: false
                },'show');
                this.form.fill(staff);
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
            searchStaff(){
                axios.get('/api/searchstaff?query='+ this.$parent.search)
                .then((data) =>{
                    this.staff = data.data
                })
                .catch(() =>{

                })
            },
            updateStaff(){
                this.$Progress.start()
                this.form.put('/api/staff/'+this.form.id,{
                  onUploadProgress: progressEvent => {
                      if(progressEvent.lengthComputable){
                      this.percent = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))
                      }
                  }
                })
                .then(()=>{
                    $('#StaffModal').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: 'Staff successfully Updated'
                    })

                    this.$Progress.finish()
                })
                .catch(()=>{
                    this.$Progress.fail()
                })
                .finally(() => {
                    Fire.$emit('ReloadStaff')
                    this.percent = 0;
                })
            },
            createStaff(){
                this.$Progress.start()
                this.form.post('/api/staff',{
                  onUploadProgress: progressEvent => {
                      if(progressEvent.lengthComputable){
                      this.percent = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))
                      }
                  }
                })
                .then(() => {
                    $('#StaffModal').modal('hide')
                    Toast.fire({
                        icon: 'success',
                        title: 'Staff added successfully'
                    })
                    this.$Progress.finish()
                })
                .catch(() => {
                    this.$Progress.fail()
                })
                .finally(() => {
                    Fire.$emit('ReloadStaff')
                    this.percent = 0;
                })
            }
        },
        mounted() {
            Fire.$on('searching',() =>{
                this.searchStaff();
            })
             if(this.$gate.isAdmin()){
                this.getResults()
            }
            Fire.$on('ReloadStaff',()=>{
                this.getResults()
            })
        }
    }
</script>
