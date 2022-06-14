<template>
    <div v-if="$gate.isStaff()" class="container">
        <div class="row mt-5">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Subject(s)</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row justify-content-center">
                  <div class="col-sm-4 p-1" v-for="subject in this.subject" :key="subject.id">
                    <div class="position-relative p-3 bg-secondary ribbon-height font-comic-sans-ms">
                      <div class="ribbon-wrapper ribbon-xl">
                        <div class="ribbon" :style="{background: subject.background_color}">  
                            {{$route.params.cls}}
                        </div>
                      </div>
                      <div class="font-times-new-roman finger-pointer"  @click="gotoNotes($route.params.cls, subject.subject_name)">
                            {{subject.subject_name}}
                      </div>
                      <br>
                      <small class="finger-pointer text-warning" @click="attendance($route.params.cls, subject.subject_name)"><u>View Attendance </u><i class="fas fa-eye" aria-hidden="true"></i></small>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- <div class="card-deck mt-3">
            <div class="card" v-for="subject in this.subject" :key="subject.id" @click="gotoNotes($route.params.cls, subject.subject_name)">
                <div class="card-header text-center text-uppercase font-comic-sans-ms"  :style="{background: subject.background_color}">{{subject.subject_name}}</div>
            </div>
        </div> -->
    </div>
</template>

<script>
    export default {
        data(){
            return {
                subject:{}
            }
        },
        methods:{
            loadSubject(){
                let loader = this.$loading.show() 
                axios.get('/api/subjects/'+ this.$route.params.cls).then(({data}) => (this.subject = data)).finally(()=>{
                  loader.hide()
                });
            },
            gotoNotes(cls,subject){
                this.$router.push('/staff/note/'+ cls +'/'+ subject);
            },
            attendance(cls,subject){
              this.$router.push('/staff/attendance/'+ cls+'/'+ subject);
            }

        },
        mounted() {
          if(this.$gate.isStaff()){
            this.loadSubject()
            }
            console.log('Component mounted.')
        }
    }
</script>
