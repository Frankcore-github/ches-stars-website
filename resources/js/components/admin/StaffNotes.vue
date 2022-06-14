<template>
    <div v-if="$gate.isAdmin()" class="container">
      <div class="mt-5">
        <div class="row sticky-top">
          <div class="col-12">
            <!-- Default box -->
            <div class="card">
              <div class="card-header black">
                  <h6 style="display: inline" class="text-capitalize">Class {{$route.params.classname}} : {{$route.params.subjectname}}</h6>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
          <div class="card card-widget" v-for="note in this.note.data" :key="note.id"> <!-- Note start here -->
                <div class="card-header">
                  <div class="user-block">
                    <h6>Shared publicly - {{note.created_at | classDate}}</h6>
                  </div>
                  <!-- /.user-block -->
                  <div class="card-tools">
                  </div>
                  <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <div class="border-bottom">
                    <strong>Chapter:</strong>
                    <p class="indent">{{note.chapter_name}}</p>
                  </div>

                  <div class="border-bottom">
                    <!-- post text -->
                    <strong>Text:</strong>
                    <p class="indent selectable">{{note.notes_text}}</p>
                  </div>

                  <!-- Attachment -->
                  <div v-if="note.file_name">
                    <strong>Attachment:</strong>
                    <div class="attachment-block clearfix ml-5">
                      <img style="width: 4rem" class="attachment-img img-fluid m-1" src="/storage/img/attached-file.png" alt="Attachment Image">
                      <div class="attachment-pushed">
                        <div class="dropdown float-right pr-2">
                          <i class="fa fa-ellipsis-v" data-toggle="dropdown" aria-hidden="true"></i>
                          <ul class="dropdown-menu dropdown-menu-right text-center">
                          <li><a v-if="!check(note.file_name)" :href="`/storage/classroom/note/${note.file_name}`">View <i class="fa fa-file-pdf" aria-hidden="true"></i></a></li>
                          <li><router-link v-if="check(note.file_name)" :to="`/player/${note.id}`">Play <i class="fa fa-play-circle" aria-hidden="true"></i></router-link> </li>
                          </ul>
                        </div>
                        <!-- /.attachment-text -->
                      </div>
                      
                        <div>
                            {{note.file_name}}
                        </div>
                      <!-- /.attachment-pushed -->
                    </div>
                  </div>
                </div>
                <!-- /.card-footer -->
          </div>  <!-- Note end here --> 
          
      </div>      
    <pagination :data="note" :limit="-1" @pagination-change-page="getResults">
      <span slot="prev-nav"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Previous</span>
      <span slot="next-nav">Next <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></span>
    </pagination>
    </div>
</template>

<script>
    export default { 
      data(){
        return{
            note:{}
        }
      },
      methods:{
          getResults(page = 1) {
                let loader = this.$loading.show()
                axios.get(`/api/staffNotes/${this.$route.params.userid}/${this.$route.params.classname}/${this.$route.params.subjectname}?page=${page}`)
                    .then(response => {
                        this.note = response.data
                    }).finally(()=>{
                      loader.hide()
                    })
            }, 
            check(url){
              let type = url.slice((Math.max(0, url.lastIndexOf(".")) || Infinity) + 1)
              let valid =  ['mp3', 'mp4']
              if(valid.indexOf(type) >= 0)
                return true
              else return false
            },
            loadNotes(){
                axios.get(`/api/staffNotes/${this.$route.params.userid}/${this.$route.params.classname}/${this.$route.params.subjectname}`).then(({data}) => (this.note = data));
            }
      },
        mounted() {
          if(this.$gate.isAdmin()){
                this.getResults();
            }
        }
    }
</script>
