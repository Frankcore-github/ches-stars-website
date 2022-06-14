
<template>
    <div class="mt-5">
        <div class="card">
            <div class="card-header text-center">Dashboard</div>
            <div class="card-body">
                <div class="row">
                    <div class="col justify-content-center">
                        <!-- Widget: user widget style 1 -->
                        <div class="card card-widget widget-user">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-info">
                                <h3 class="widget-user-username font-comic-sans-ms text-uppercase">Admin</h3>
                            </div>
                            <div class="widget-user-image">
                                <img class="img-circle elevation-2"  src="/storage/img/profile/default.png" alt="User Avatar">
                            </div>
                            <div class="card-footer text-center">
                                <strong class="text-center p-3 font-comic-sans-ms text-uppercase text-primary">Welcome to the admin dashboard</strong>
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
                            <h3><animated-number :value="data.admin" :round="1" :duration="duration" /></h3>

                            <p>Admin</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-cog"></i>
                        </div>
                        </div>
                    </div>

                    <div class="col-sm">
                        <!-- small box -->
                        <div class="small-box bg-success">
                        <div class="inner">
                            <h3><animated-number :value="data.staff" :round="1" :duration="duration" /></h3>

                            <p>Staff</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        </div>
                    </div>

                    <div class="col-sm">
                        <!-- small box -->
                        <div class="small-box bg-primary">
                        <div class="inner">
                            <h3><animated-number :value="data.student" :round="1" :duration="duration" /></h3>

                            <p>Student</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 col-12">
                        <div class="info-box">
                        <span class="info-box-icon bg-secondary"><i class="fas fa-calendar-day"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Events</span>
                            <span class="info-box-number"><animated-number :value="data.event" :round="1" :duration="duration" /></span>
                        </div>
                        <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <div class="col-sm-4 col-12">
                        <div class="info-box">
                        <span class="info-box-icon bg-secondary"><i class="fas fa-chalkboard"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Notice</span>
                            <span class="info-box-number"><animated-number :value="data.notice" :round="1" :duration="duration" /></span>
                        </div>
                        <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <div class="col-sm-4 col-12">
                        <div class="info-box">
                        <span class="info-box-icon bg-secondary"><i class="fas fa-chalkboard-teacher"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Class Teachers</span>
                            <span class="info-box-number"><animated-number :value="data.classTeacher" :round="1" :duration="duration" /></span>
                        </div>
                        <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <div class="col-sm-4 col-12">
                        <div class="info-box">
                        <span class="info-box-icon bg-secondary"><i class="fas fa-images"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Gallery</span>
                            <span class="info-box-number"><animated-number :value="data.gallery" :round="1" :duration="duration" /></span>
                        </div>
                        <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <div class="col-sm-4 col-12">
                        <div class="info-box">
                        <span class="info-box-icon bg-secondary"><i class="fas fa-newspaper"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">News Feed</span>
                            <span class="info-box-number"><animated-number :value="data.newsFeed" :round="1" :duration="duration" /></span>
                        </div>
                        <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <div class="col-sm-4 col-12">
                        <div class="info-box">
                        <span class="info-box-icon bg-secondary"><i class="fas fa-graduation-cap"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">SSLC</span>
                            <span class="info-box-number"><animated-number :value="data.sslc" :round="1" :duration="duration" /></span>
                        </div>
                        <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
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
                axios.get('/api/admindashboard')
                    .then(response => {
                        this.data = response.data
                    }).finally(()=>{
                        loader.hide()
                    })
            },
            format(value){
                alert(value)
            }
        },
        mounted() {
            if(this.$gate.isAdmin()){
                this.load();
            }
        }
    }
</script>
