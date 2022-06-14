
<template>
    <div v-if="$gate.isStaff()" class="mt-5">
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
                            <h3><animated-number :value="data.class" :round="1" :duration="duration" /></h3>

                            <p>Class</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        </div>
                    </div>

                    <div class="col-sm">
                        <!-- small box -->
                        <div class="small-box bg-success">
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
                            <h3><animated-number :value="data.assignment" :round="1" :duration="duration" /></h3>

                            <p>Asssignment</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        </div>
                    </div>

                    <div class="col-sm">
                        <!-- small box -->
                        <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3><animated-number :value="data.note" :round="1" :duration="duration" /></h3>

                            <p>Uploaded Files</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-upload"></i>
                        </div>
                        </div>
                    </div>
                </div>
                <div>
                    <vc-calendar :attributes='attrs' is-dark is-expanded :min-date="new Date()"/>
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
                let loader = this.$loading.show() 
                axios.get('/api/staffdashboard')
                    .then(response => {
                        this.data = response.data
                    }).finally(()=>{
                        loader.hide()
                    })
            },
            getPhoto(){
                if(this.data.photo === null)
                    return "/storage/img/profile/default.png" 
                else
                    return "/storage/img/profile/"+ this.data.photo
            }    
        },
        mounted() {
            if(this.$gate.isStaff()){
                this.load();
            }
        }

    }
</script>
