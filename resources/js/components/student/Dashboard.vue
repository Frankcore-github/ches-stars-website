
<template>
    <div v-if="$gate.isStudent()" class="mt-5">
        <div class="card">
            <div class="card-header text-center">Dashboard</div>
            <div class="card-body">
                <div class="row">
                    <div class="col justify-content-center">
                        <!-- Widget: user widget style 1 -->
                        <div class="card card-widget widget-user">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-info">
                                <h3 class="widget-user-username font-comic-sans-ms text-uppercase">{{data.name}}</h3>
                                <h6 class="widget-user-desc font-comic-sans-ms text-uppercase">Class: {{data.class}}</h6>
                            </div>
                            <div class="widget-user-image">
                                <img class="img-circle elevation-2"  :src=getPhoto() alt="User Avatar">
                            </div>
                            <div class="card-footer text-center">
                                <strong class="text-center p-3 font-comic-sans-ms text-uppercase text-primary">Welcome to the online classroom</strong>
                            </div>
                        </div>
                        <!-- /.widget-user -->
                    </div>
                </div>
                <div class="row">
                    <!-- ./col -->
                    <div class="col-sm">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><animated-number :value="data.subject" :round="1" :duration="duration" /></h3>

                            <p>Subject</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-book"></i>
                        </div>
                        </div>
                    </div>

                    <div class="col-sm">
                        <!-- small box -->
                        <div class="small-box bg-primary">
                        <div class="inner">
                            <h3><animated-number :value="data.note" :round="1" :duration="duration" /></h3>

                            <p>Note</p>
                        </div>
                        <div class="icon">
                           <i class="fas fa-book-open"></i>
                        </div>
                        </div>
                    </div>

                    <div class="col-sm">
                        <!-- small box -->
                        <div class="small-box bg-success">
                        <div class="inner">
                            <h3><animated-number :value="data.assignment" :round="1" :duration="duration" /></h3>

                            <p>Assignment</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-edit"></i> 
                        </div>
                        </div>
                    </div>
                </div>
                <div>
                    <vc-calendar :attributes='attrs' is-dark is-expanded :min-date="new Date()"/>
                </div>
            </div>
        </div>
        <div class="modal fade" id="voteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-body">
                    <p>Dear parents, as discussed in the parents meeting held on the 3rd of December 2021, that concerning class 5, final examination should be held offline at the school premises. I would like to know your opinion on this matter. 
                        To know if you would like to send your child to school for final examination, please reply simply by clicking yes or no on the button below.</p>
                    <div class="row">
                    <div class="col-md-6">
                        <button type="button" @click='response("YES")' class="btn btn-block btn-success"><i class="fas fa-check-circle"></i> Yes</button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" @click='response("NO")' class="btn btn-block btn-danger"><i class="fas fa-times-circle"></i> No</button>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AnimatedNumber from 'animated-number-vue'
    export default {
        components: {
            AnimatedNumber,
        },
        data() {
            return {
            duration:500,
            data:{},
            attrs: [
                {
                key: 'today',
                highlight: true,
                dates: new Date(),
                },
            ],
            };
        },
        methods:{
            load(){
                console.log("load");
                let loader = this.$loading.show() 
                axios.get('/api/studentdashboard')
                    .then(response => {
                        this.data = response.data
                        console.log(this.data)
                        if(['Five'].includes(this.data.class) && this.data.vote == 0){
                            $('#voteModal').modal('show')
                            console.log('CLass: '+this.data.class);
                        }
                    }).finally(()=>{
                        loader.hide()
                    })
            },
            getPhoto(){
                if(this.data.photo === null)
                    return "/storage/img/profile/default.png" 
                else
                    return "/storage/img/profile/"+ this.data.photo

            },
            response(message){
                axios.post('api/vote', {name: this.data.name, class_name: this.data.class.toLowerCase(), vote: message})
                    .then(
                        $('#voteModal').modal('hide'),
                        NotificationToast.fire({
                            icon: 'info',
                            title: "Thank you for your response!"    
                        })
                    );
                
            }
        },
        mounted() {
            if(this.$gate.isStudent()){
                this.load();
            }
            
        }
    }
</script>
