<template>
     <div v-if="$gate.isAdmin()" class="mt-5">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Students Report</h3>
                <div class="card-tools p-1">
                    <div class="btn-group">
                        <select class="custom-select" id="staff" @change="onChangeClass($event)">
                        <option selected disabled>Select Class</option>
                        <option v-for="classes in this.classes" :key="classes.id" :value="classes.class">{{classes.class_name}}</option>
                    </select>
                    </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div v-if="!isEmpty" class="card-body p-0">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                            <tbody>
                                <tr>
                                <td>1.</td>
                                <td>Number of students voted "YES"</td>
                                <td>{{yes}}</td>
                                <td class="w-25">
                                    <div class="progress progress-sm">
                                    <div class="progress-bar bg-success" :style="`width: ${((yes/total)*100).toFixed(2)}%`"></div>
                                    </div>
                                </td>
                                <td><span class="badge bg-success">{{((yes/total)*100) | percentage}}%</span></td>
                                </tr>
                                <tr>
                                <td>2.</td>
                                <td>Number of students voted "NO"</td>
                                <td>{{no}}</td>
                                <td class="w-25">
                                    <div class="progress progress-sm">
                                    <div class="progress-bar bg-danger" :style="`width: ${((no/total)*100).toFixed(2)}%`"></div>
                                    </div>
                                </td>
                                <td><span class="badge bg-danger">{{((no/total)*100) | percentage}}%</span></td>
                                </tr>
                                <tr>
                                <td>3.</td>
                                <td>Remaining votes</td>
                                <td>{{remaining}}</td>
                                <td class="w-25">
                                    <div class="progress progress-sm">
                                    <div class="progress-bar bg-warning" :style="`width: ${((remaining/total)*100).toFixed(2)}%`"></div>
                                    </div>
                                </td>
                                <td><span class="badge bg-warning">{{((remaining/total)*100) | percentage}}%</span></td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row p-1">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-success">
                                    <h6 class="text-center">Students voted YES</h6>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                        <th>Student Name</th>
                                        <th class="w-25">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="studentsYes in this.studentsYes" :key="studentsYes.id">
                                        <td>{{studentsYes.name}}</td>
                                        <td>{{studentsYes.created_at | classDate}}</td>
                                        </tr>
                                    </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-danger">
                                    <h6 class="text-center">Students voted NO</h6>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                        <th>Student Name</th>
                                        <th class="w-25">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="studentsNo in this.studentsNo" :key="studentsNo.id">
                                        <td>{{studentsNo.name}}</td>
                                        <td>{{studentsNo.created_at | classDate}}</td>
                                        </tr>
                                    </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.card -->
    </div>
</template> 
<script>
export default {
    data(){
        return{
            isEmpty: true,
            classes:{},
            studentsNo:{},
            studentsYes:{},
            yes:0,
            no:0,
            remaining:0,
            total: 0
        }
    },
    methods:{
            loadClass(){
                let loader = this.$loading.show() 
                axios.get('api/allclass')
                    .then(response => {
                        this.classes = response.data
                    }).finally(()=>{
                        loader.hide()
                    })
            },
            
            onChangeClass(event){
                let loader = this.$loading.show() 
                axios.get('api/getvotes/'+event.target.value)
                    .then(response => {
                        this.studentsYes = response.data.yesStudents,
                        this.studentsNo = response.data.noStudents,
                        this.yes = response.data.yesVotes,
                        this.no = response.data.noVotes,
                        this.remaining = response.data.remainingVotes,
                        this.total = this.yes+this.no+this.remaining
                    }).finally(()=>{
                        loader.hide()
                        this.isEmpty = false;
                    })
            }
        },
    mounted(){
        if(this.$gate.isAdmin()){
            this.loadClass()
        }
    }
}
</script>