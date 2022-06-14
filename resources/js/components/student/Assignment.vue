
<template>
    <div v-if="$gate.isStudent()" class="mt-5">
        <div class="mt-5">
        <div class="row sticky-top">
          <div class="col-12">
            <!-- Default box -->
            <div class="card">
              <div class="card-header black">
                <h3 v-if="!isEmpty" class="card-title">{{count}} Assignments</h3>
                  <div class="btn-group float-right">
                        <button type="button" class="btn btn-sm btn-outline-light dropdown-toggle text-dark" data-flip="false" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           {{subject_name}}
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div v-for="info in this.info" :key="info.id" @click="loadAssignment(subject_name = info.subject_name)" class="dropdown-item" href="#">{{info.subject_name}}</div>
                        </div>
                    </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <div v-if="count > 0">
          <div class="card card-widget" v-for="assignment in this.assignment.data" :key="assignment.id"> <!-- Note start here -->
                <div class="card-header">
                  <div class="user-block">
                    <h6>Submitted on - {{assignment.created_at | classDate}}</h6>
                  </div>
                  <!-- /.card-tools -->
                  
                  <div class="card-tools">
                    <button @click="deleteNote(assignment.id)" type="button" class="btn btn-outline-danger btn-sm">Delete <i class="fa fa-trash"></i></button>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="border-bottom">
                    <strong>Chapter:</strong>
                    <p class="indent">{{assignment.chapter_name}}</p>
                  </div>
                  <!-- Attachment -->
                  <div v-if="assignment.file_name">
                    <strong>Attachment:</strong>
                    <div class="attachment-block clearfix ml-5">
                      <img style="width: 4rem" class="attachment-img img-fluid m-1" src="/storage/img/attached-file.png" alt="Attachment Image">
                      <div class="attachment-pushed">
                        <div class="dropdown float-right pr-2">
                          <i class="fa fa-ellipsis-v" data-toggle="dropdown" aria-hidden="true"></i>
                          <ul class="dropdown-menu dropdown-menu-right text-center">
                          <li><a :href="`/storage/classroom/assignment/${assignment.file_name}`" target="_blank">View <i class="fas fa-file-pdf" aria-hidden="true"></i></a></li>
                          </ul>
                        </div>
                        <!-- /.attachment-text -->
                      </div>
                      
                        <div>
                            {{assignment.file_name}}
                        </div>
                      <!-- /.attachment-pushed -->
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->

                <comment-assignment :id="assignment.id"></comment-assignment>
                <!-- /.card-footer -->
          </div>  <!-- Note end here --> 
        </div>
          
      </div> 
      
      <pagination :data="assignment" :limit="-1" @pagination-change-page="getResults">
        <span slot="prev-nav"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Previous</span>
        <span slot="next-nav">Next <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span>
      </pagination>
    </div>
</template>

<script>
    export default {
        data(){
            return{ 
                isEmpty: true,
                count: 0,
                subject_name: 'Subject',
                info:{},
                assignment:{}
            }
        },
        methods:{ 
            getResults(page = 1) {
                let loader = this.$loading.show() 
                axios.get('/api/selfassignment/'+this.subject_name +'?page=' + page)
                    .then(response => {
                        this.assignment = response.data.object1,
                        this.count = response.data.object2
                    }).finally(()=>{
                      loader.hide()
                    })
            },  
            loadAssignment(subj){
                let loader = this.$loading.show() 
                axios.get('/api/selfassignment/'+subj).then(({data}) => (this.assignment = data.object1, this.count = data.object2 )).finally(() => {
                    loader.hide()
                    this.isEmpty = false
                });
            },
            loadSubjects(){
                let loader = this.$loading.show() 
                axios.get('/api/showsubjects').then(({data}) => (this.info = data)).finally(()=>{loader.hide()});
            },
            deleteNote(id){
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
                        axios.delete('/api/assignment/'+id)
                        .then(()=>{
                              Swal.fire(
                                  'Deleted!',
                                  'Your file has been deleted.',
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
                       }).finally(()=>{ 
                           this.getResults()
                       })
                    }
                    })
            },
        },
        mounted() { 
           if(this.$gate.isStudent()){
                this.loadSubjects()
            }
        }
    }
</script>
