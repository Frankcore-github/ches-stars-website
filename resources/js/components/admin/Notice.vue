
<template>
     <div v-if="$gate.isAdmin()" class="mt-5">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Notice</h3>
                <div class="card-tools p-1">
                    <button class="btn btn-success btn-sm" @click="addNoticeModal">Add Notice <i class="fas fa-calendar-plus nav-icon"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-center">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Notice</th>
                      <th>Notice Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody> 
                    <tr v-for="notice in this.notice.data" :key="notice.id">
                      <td>{{notice.id}}</td>
                      <td v-html="notice.message"></td>
                      <td>{{notice.updated_at | myDate}}</td>
                      <td><div href="#" @click="editNoticeModal(notice)" class="btn btn-secondary btn-sm text-light"><i class="fas fa-edit"></i> Edit</div>
                         <div href="#" @click="deleteNotice(notice.id)" class="btn btn-danger btn-sm text-light"><i class="fas fa-trash"></i> Delete</div></td>
                      
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                 <!-- <pagination :data="users" @pagination-change-page="getResults"></pagination> -->
                 <pagination :data="notice" @pagination-change-page="getResults">
                    <span slot="prev-nav"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Previous</span>
                    <span slot="next-nav">Next <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span>
                </pagination>
              </div>
            </div>
            <!-- /.card -->
        <div class="modal fade" id="NoticeModal" tabindex="-1" role="dialog" aria-labelledby="NoticeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editMode" id="NoticeModalLabel">Add New Notice</h5>
                    <h5 class="modal-title" v-show="editMode" id="NoticeModalLabel">Edit Notice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editMode ? updateNotice() : createNotice()" @keydown="form.onKeydown($event)">
                         <div class="modal-body">
                            <div class="form-group">
                            <label>Notice</label>
                            <vue-editor :editorToolbar="customToolbar" v-model="form.message" :class="{ 'is-invalid': form.errors.has('message') }"/>
                            <has-error :form="form" field="message"></has-error>
                            </div>
                        </div> 

                        <div class="form-group">
                            <label v-if="percent > 0">Uploading: {{percent}}%</label>
                            <label v-if="percent == 100">Please wait....</label>
                        </div>
                        <div class="modal-footer">
                            <button :disabled="form.busy" v-show="!editMode" type="submit" class="btn btn-success btn-sm">Add Notice</button>
                            <button :disabled="form.busy" v-show="editMode" type="submit" class="btn btn-success btn-sm">Update Notice</button>
                            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cancel</button>
                        </div>
                </form>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import { VueEditor } from "vue2-editor";
var toolbarOptions = [
  ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
  ['blockquote', 'code-block'],
  [{ 'list': 'ordered'}, { 'list': 'bullet' }],
  [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
  [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
  [{ 'direction': 'rtl' }],                         // text direction

  [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
  [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

  [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
  [{ 'font': [] }],
  [{ 'align': [] }],
                                         // remove formatting button
];
     export default {
        components: {
            VueEditor 
        },
        data() {
            return{
                customToolbar: toolbarOptions,
                notice: {},
                percent: 0,
                editMode: false,
                form: new Form({
                    id: '',
                    message: '',
                    updated_at: ''
                })
            }
        },
        methods:{
            getResults(page = 1) {
                let loader = this.$loading.show() 
                axios.get('/api/notice?page=' + page)
                .then(response => {
                  this.notice = response.data
                }).finally(()=>{
                    loader.hide()
                })
            },
            addNoticeModal(){
                this.editMode = false;
                this.form.reset();
                $('#NoticeModal').modal({
                    backdrop: 'static',
                    keyboard: false
                },'show');
            },
            editNoticeModal(notice){
                this.editMode = true;
                this.form.reset();
                $('#NoticeModal').modal({
                    backdrop: 'static',
                    keyboard: false
                },'show');
                this.form.fill(notice);
            },
            updateNotice(){
                this.$Progress.start()
                this.form.put('/api/notice/'+this.form.id,{
                  onUploadProgress: progressEvent => {
                      if(progressEvent.lengthComputable){
                      this.percent = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))
                      }
                  }
                })
                .then(()=>{
                    $('#NoticeModal').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: 'Notice successfully Updated'
                    })
                    this.$Progress.finish()
                })
                .catch(()=>{
                    this.$Progress.fail()
                })
                .finally(()=>{
                    Fire.$emit('ReloadNotice')
                    this.percent = 0;
                })
            },
            createNotice(){
                this.$Progress.start()
                this.form.post('/api/notice',{
                  onUploadProgress: progressEvent => {
                      if(progressEvent.lengthComputable){
                      this.percent = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ))
                      }
                  }
                })
                .then(function (response) {
                    $('#NoticeModal').modal('hide')
                    Toast.fire({
                        icon: 'success',
                        title: 'Notice added successfully'
                    })
                    this.$Progress.finish()
                })
                .catch(() => {
                    this.$Progress.fail()
                })
                .finally(() => {
                    Fire.$emit('ReloadNotice')
                    this.percent = 0;
                })
            },
            deleteNotice(id){
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
                        this.form.delete('api/notice/'+id)
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
                            Fire.$emit('ReloadNotice')
                       })
                    }
                    })
            }
        },
        mounted() {
             if(this.$gate.isAdmin()){
                this.getResults();
            }
            Fire.$on('ReloadNotice',()=>{
                this.getResults();
            })
        }
    }
</script>