
<template>
     <div v-if="$gate.isStaff()" class="mt-5">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Student Attendance [{{$route.params.subject}}]</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-center">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="student in this.student.data" :key="student.id">
                      <td>{{student.first_name}} {{student.middle_name}} {{student.last_name}}</td>
                      <td>
                        <button class="btn btn-outline-primary btn-sm" @click="viewAttendance(student.id,student.first_name,student.last_name)">View <i class="fas fa-eye" aria-hidden="true"></i></button>
                      </td>
                    </tr> 
                  </tbody>
                </table>
              </div>

            <div class="modal fade" id="AttendanceModal" tabindex="-1" role="dialog" aria-labelledby="AttendanceModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="AttendanceModalLabel">{{name}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <div class="modal-body">
                          <h6>Class taken : {{countnotes}}</h6>
                          <h6>Class attended: {{countattendance}}</h6>
                          <h6>Total attendance: {{countattendance}}/{{countnotes}}</h6>
                          <h6>{{percentage}}% Attendance</h6>
                          <div class="progress bg-danger">
                            <div class="progress-bar" :style="`width: ${percentage}%`">{{percentage}}%</div>
                          </div>
                    </div> 
                </div>
            </div>
        </div>

              <!-- /.card-body -->
              <div class="card-footer">
                 <pagination :data="student" :limit="-1" @pagination-change-page="getResults">
                    <span slot="prev-nav"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Previous</span>
                    <span slot="next-nav">Next <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span>
                </pagination>
              </div>
            </div>
            <!-- /.card -->
    </div>
</template>

<script>
     export default {
        data() {
            return{
                percentage:0,
                name:'',
                countnotes:0,
                countattendance:0,
                student:{}
            }
        },
        methods:{
            getResults(page = 1) {
                let loader = this.$loading.show() 
                axios.get('/api/viewstudents/'+ this.$route.params.cls+'/?page=' + page)
                    .then(response => {
                        this.student = response.data
                    }).finally(()=>{
                        loader.hide()
                    })
            },
            viewAttendance(id, firstname, lastname){ 
                axios.get('/api/countattendance/'+ id+'/'+ this.$route.params.subject+'/'+ this.$route.params.cls)
                    .then(response => {
                        this.countnotes = response.data.notes,
                        this.countattendance = response.data.attendance
                    }).finally(()=>{
                        this.name = firstname +' '+lastname;
                        this.percentage = this.countattendance * 100 / this.countnotes;

                        $('#AttendanceModal').modal({
                            backdrop: 'static',
                            keyboard: false
                        },'show');
                    })
            }

            
        },
        mounted() {
          if(this.$gate.isStaff()){
                this.getResults()
            }
        }
    }
</script>