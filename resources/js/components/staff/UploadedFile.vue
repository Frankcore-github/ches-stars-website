
<template>
     <div v-if="$gate.isStaff()" class="mt-5">
            <div class="card">
              <div class="card-header">
                <h3 v-if="!isEmpty" class="card-title">{{count}} Notes Uploaded</h3>
                <div class="card-tools p-1">
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-light dropdown-toggle text-dark" data-flip="false" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           {{class_name | upText}} | {{subject_name}}
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div v-for="info in this.info" :key="info.id" @click="classAndSubject(class_name=info.class_name, subject_name=info.subject_name)" class="dropdown-item" href="#">{{info.class_name | upText }} | {{info.subject_name}}</div>
                        </div>
                    </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div v-if="!isEmpty" class="card-body table-responsive p-0">
                <table class="table table-hover text-center">
                  <thead>
                    <tr>
                      <th>Note text</th>
                      <th>Chapter</th>
                      <th>File</th>
                      <th>Uploaded on</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="note in this.note.data" :key="note.id">
                      <td>{{note.notes_text}}</td>
                      <td>{{note.chapter_name}}</td>
                      <td v-if="note.file_name">{{note.file_name}}</td>
                      <td v-else>N/A</td>
                      <td>{{note.updated_at | myDate}}</td>
                    </tr> 
                  </tbody>
                </table>
              </div>

              <!-- /.card-body -->
              <div class="card-footer">
                 <pagination :data="note" :limit="-1" @pagination-change-page="getResults">
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
                isEmpty: true,
                class_name:'Class',
                subject_name:'Subject',
                count: 0,
                info:{},
                note:{}
            }
        },
        methods:{
            getResults(page = 1) {
                let loader = this.$loading.show() 
                    axios.get('/api/shownotes/'+ this.class_name + '/' + this.subject_name +'/10?page=' + page)
                        .then(response => { 
                            this.note = response.data.object1,
                            this.count = response.data.object2
                        }).finally(() =>{
                            loader.hide()
                        })
            }, 
            loadCAS(){
                let loader = this.$loading.show() 
                axios.get('/api/showclassesandsubjects').then(({data}) => (this.info = data)).finally(()=>{loader.hide()});
            },
            classAndSubject(class_name, subject_name){
                let loader = this.$loading.show() 
                axios.get('/api/shownotes/'+ class_name + '/' + subject_name +'/10')
                        .then(response => { 
                            this.note = response.data.object1,
                            this.count = response.data.object2
                        }).finally(() => {
                            this.isEmpty = false,
                            loader.hide()
                        })
            }
            
        },
        mounted() {
          if(this.$gate.isStaff()){
                this.loadCAS()
            }
        }
    }
</script>