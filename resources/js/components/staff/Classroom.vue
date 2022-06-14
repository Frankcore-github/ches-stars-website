<template>
    <div v-if="$gate.isStaff()" class="container">
        <div class="row mt-5">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Classes</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                  <div class="card-deck mt-3">
                    <div class="card finger-pointer" v-for="classes in this.classes" :key="classes.id"  @click="subject(classes.class_name)">
                        <div class="card-body classroom-bg" :style="{ 'background-image': 'linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(' + image() + ')'}">   
                            <div class="text-center p-3 font-comic-sans-ms text-uppercase text-light">{{classes.class_name}}</div>
                        </div>
                        <div class="card-footer">
                        <small class="text-dark">SUBJECTS : {{ classes.total }}</small><i class="fas fa-folder float-right" aria-hidden="true"></i>
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
    </div>
</template>

<script>
    export default {
        data(){
            return {
                classes:{}
            }
        },
        methods:{
            loadClass(){
                let loader = this.$loading.show() 
                axios.get('/api/showclasses').then(({data}) => (this.classes = data)).finally(()=>{loader.hide()});
            },
            subject(cls){
                this.$router.push('/staff/subjects/'+ cls);
            },
            image(){
                var random = Math.floor(Math.random() * 9)
                return 'storage/img/staffImg/'+random+'.jpg'
            }

        },
        mounted() {
            this.loadClass()
            console.log('Component mounted.')
        }
    }
</script>
