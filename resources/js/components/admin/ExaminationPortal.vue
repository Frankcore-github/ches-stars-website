<template>
    <div  v-if="$gate.isAdmin()" class="mt-5">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Exam Portal</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
              <div v-for="student in this.students" :key="student.id">
                  <div class="card card-widget widget-user-2 shadow-sm">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header bg-warning">
                <div class="widget-user-image">
                  <img class="img-circle elevation-2" src="/storage/img/profile/default.png" alt="User Avatar">
                </div>
                <!-- /.widget-user-image -->
                <h3 class="widget-user-username">{{student.first_name}} {{student.middle_name}} {{student.last_name}}</h3>
                <h5 class="widget-user-desc text-capitalize">{{student.class}}</h5>
              </div>
            </div>
              </div>
                <table class="table table-hover text-center">
                  <thead>
                    <tr>
                      <th>File id</th>
                      <th>Subject</th>
                      <th>Status</th>
                      <th>File</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="exam in this.exams" :key="exam.id">
                      <td>{{exam.id}}</td>
                      <td>{{exam.subject_name}}</td>
                      <td>
                        <span v-if="exam.status == 'seen'" class="badge badge-success">Seen</span>
                        <span v-else class="badge badge-danger">Not Seen</span>
                      </td>
                      <td><a :href="`/storage/classroom/exam/${exam.file_name}`" target="_blank">{{exam.file_name}}</a></td>
                      <td>
                         <div href="#" @click="deleteExam(exam.id)" class="btn btn-danger btn-sm text-light"><i class="fas fa-trash"></i> Delete</div></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.card -->
    </div>
</template>

<script>
    export default {
        data() {
            return{
                isLoading: false,
                fullPage: true,
                percent: 0,
                valid: false,
                exams: {},
                students:{}

            }
        },
        methods: {

            searchExam(){
                axios.get('/api/searchexam?query='+ this.$parent.search)
                .then((data) =>{
                    this.exams = data.data.exam;
                    this.students = data.data.student;
                })
                .catch(() =>{

                })
            },

         
            deleteExam(id){
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
                        axios.delete('/api/studentexam/'+id)
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
                            Fire.$emit('searching')
                         })
                    }
                    })
            }
        },
        mounted() { 
            Fire.$on('searching',() =>{
                this.searchExam();
            })
        }
    }
</script>
