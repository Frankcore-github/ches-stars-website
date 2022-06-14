
<template>
     <div v-if="$gate.isAdmin()" class="mt-5">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Event</h3>
                <div class="card-tools p-1">
                    <button class="btn btn-success btn-sm" @click="addEventModal">Add Event <i class="fas fa-calendar-plus nav-icon"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-center">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Event</th>
                      <th>Event Position</th>
                      <th>Event Year</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="event in events.data" :key="event.id">
                      <td>{{event.id}}</td>
                      <td v-html="event.event_name"></td>
                      <td>{{event.event_position}}</td>
                      <td>{{event.event_year}}</td>
                      <td><div href="#" @click="editEventModal(event)" class="btn btn-secondary btn-sm text-light"><i class="fas fa-edit"></i> Edit</div>
                            <div href="#" @click="deleteEvent(event.id)" class="btn btn-danger btn-sm text-light"><i class="fas fa-trash"></i> Delete</div></td>
                      
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                 <!-- <pagination :data="users" @pagination-change-page="getResults"></pagination> -->
                 <pagination :data="events" @pagination-change-page="getResults">
                    <span slot="prev-nav"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Previous</span>
                    <span slot="next-nav">Next <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span>
                </pagination>
              </div>
            </div>
            <!-- /.card -->
        <div class="modal fade" id="EventModal" tabindex="-1" role="dialog" aria-labelledby="EventModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editMode" id="EventModalLabel">Add New User</h5>
                    <h5 class="modal-title" v-show="editMode" id="EventModalLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editMode ? updateEvent() : createEvent()" @keydown="form.onKeydown($event)">
                         <div class="modal-body">
                            <div class="form-group">
                            <label>Event</label>
                            <!-- <vue-editor :editorToolbar="customToolbar" v-model="form.event_name" :class="{ 'is-invalid': form.errors.has('event_name') }"/> -->
                            <textarea v-model="form.event_name" class="form-control" row="3" name="event_position" :class="{ 'is-invalid': form.errors.has('event_name') }"></textarea>
                            <has-error :form="form" field="event_name"></has-error>
                            </div>

                            <div class="form-group">
                            <label>Event Position</label>
                            <input v-model="form.event_position" type="text" name="event_position"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('event_position') }" placeholder="Enter Event Postiton Here">
                            <has-error :form="form" field="event_position"></has-error>
                            </div>

                            <div class="form-group">
                            <label>Event Year</label>
                            <input v-model="form.event_year" type="number" name="event_year"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('event_year') }" placeholder="Enter The Event Year Here">
                            <has-error :form="form" field="event_year"></has-error>
                            </div>

                            <div class="form-group">
                            <label v-if="percent > 0">Uploading: {{percent}}%</label>
                            <label v-if="percent == 100">Please wait....</label>
                            </div>    
                        </div> 
                        <div class="modal-footer">
                            <button :disabled="form.busy" v-show="!editMode" type="submit" class="btn btn-success btn-sm">Add Event</button>
                            <button :disabled="form.busy" v-show="editMode" type="submit" class="btn btn-success btn-sm">Update Event</button>
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
                events: {},
                percent: 0,
                editMode: false,
                form: new Form({
                    id: '',
                    event_name: '',
                    event_position: '',
                    event_year: ''
                })
            }
        },
        methods:{
            getResults(page = 1) {
                let loader = this.$loading.show() 
                axios.get('/api/event?page=' + page)
                .then(response => {
                  this.events = response.data
                }).finally(()=>{
                    loader.hide()
                })
            },
            addEventModal(){
                this.editMode = false;
                this.form.reset();
                $('#EventModal').modal({
                    backdrop: 'static',
                    keyboard: false
                },'show');
            },
            editEventModal(events){
                this.editMode = true;
                this.form.reset();
                $('#EventModal').modal({
                    backdrop: 'static',
                    keyboard: false
                },'show');
                this.form.fill(events);
            },
            updateEvent(){
                this.$Progress.start()
                this.form.put('/api/event/'+this.form.id,{
                  onUploadProgress: progressEvent => {
                      if(progressEvent.lengthComputable){
                      this.percent = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))
                      }
                  }
                })
                .then(()=>{
                    $('#EventModal').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: 'Event successfully Updated'
                    })
                    this.$Progress.finish()
                })
                .catch(()=>{
                    this.$Progress.fail()
                }).finally(()=>{ 
                    Fire.$emit('ReloadEvent');
                    this.percent = 0;
                })
            },
            createEvent(){
                this.$Progress.start()
                this.form.post('/api/event',{
                  onUploadProgress: progressEvent => {
                      if(progressEvent.lengthComputable){
                      this.percent = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))
                      }
                  }
                })
                .then(function (response) {
                    $('#EventModal').modal('hide')
                    Toast.fire({
                        icon: 'success',
                        title: 'Event added successfully'
                    })
                    this.$Progress.finish()
                })
                .catch(() => {
                    this.$Progress.fail()
                })
                .finally(() => {
                    Fire.$emit('ReloadEvent')
                    this.percent = 0;
                })
            },
            deleteEvent(id){
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
                        this.form.delete('api/event/'+id)
                       .then(()=>{                           
                            this.$Progress.finish()
                            Swal.fire(
                                'Deleted!',
                                'Record has been deleted.',
                                'success'
                            ),    
                        Fire.$emit('ReloadEvent');
                       })
                       .catch(()=>{
                            this.$Progress.fail()
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!'
                             })
                       })
                    }
                    })
            }
        },
        mounted() {
             if(this.$gate.isAdmin()){
                this.getResults();
            }
            Fire.$on('ReloadEvent',()=>{
                this.getResults();
            })
        }
    }
</script>