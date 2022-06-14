<template>
  <div v-if="$gate.isAdmin()" class="mt-5">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Staff Statistics</h3>
        <div class="card-tools p-1">
            <div class="input-group">
              <div class="input-group-prepend" v-show="!table">
                <button class="btn btn-outline-secondary" @click="table=true" type="button"><i class="fas fa-arrow-left"></i></button>
              </div>
              <select class="custom-select" id="staff" @change="onChangeStaff($event)">
                <option selected disabled>Select Staff Name</option>
                <option v-for="staff in this.staff" :key="staff.id" :value="staff.user_id">{{staff.first_name+' '+staff.last_name}}</option>
              </select>
            </div> 
        </div>
      </div>
      <!-- /.card-header -->
      <div v-show="table" class="card-body table-responsive p-0">
        <table class="table table-hover text-center">
            <thead>   
            <tr>
                <th>Sl no.</th>
                <th>Classes Taken</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody  class="text-capitalize">
            <tr v-for="(value, name, index) in this.classes" :key="index">
                <td>{{index+1}}</td>
                <td>{{value}}</td>    
                <td><div href="#" @click="viewSubjects(value)" class="btn btn-success btn-sm text-light"><i class="fas fa-eye"></i> View Subjects</div></td>
            </tr>
            </tbody>
        </table>
      </div>
      <div v-show="!table" class="card-body table-responsive p-0">
        <table class="table table-hover text-center">
            <thead>
            <tr>
                <th>Subjects Taken</th>
                <th>Notes uploaded</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody  class="text-capitalize">
            <tr v-for="(value, name, index) in this.subjects" :key="index">
                <td>{{value.subject_name}}</td>    
                <td>{{value.notes_count}}</td> 
                <td><router-link :to="`/admin/notes/${userid}/${value.class_name}/${value.subject_name}`" class="btn btn-success btn-sm text-light"><i class="fas fa-eye"></i> View Notes</router-link></td>
                
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
    return {
        staff:{},
        classes:{},
        subjects:{},
        table:true,
        userid:0,
        barChart:null
    };
  },
  methods: {
      loadStaff(){
          axios.get('/api/allstaff').then(response => { this.staff = response.data });
      },
      onChangeStaff(event){
          this.subjects = {};
          this.table = true;
          this.userid=event.target.value;
          axios.get('/api/getStaffClasses/'+event.target.value).then(response => { this.classes = response.data });
      },
      viewSubjects(classname){ 
          this.table = false;
          axios.get('/api/getStaffSubjects/'+this.userid +'/'+classname).then(response => { this.subjects = response.data});  
      }
  },
  mounted() {
    if (this.$gate.isAdmin()) { 
      this.loadStaff();
    }
  },
};
</script>
