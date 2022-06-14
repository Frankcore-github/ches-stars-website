<template>
    <div class="nav-padding">
        <section class="container custom-header" id="staff">
            <h1 class=text-center>Staff</h1>
            <center><div class="box">
                <img src="/storage/img/staff.jpg" class="img-fluid">
            </div></center>
            <div>
                <p>
                    Our school recruited only experienced teachers and for lower classes minimum qualification a degree holder either BSc with
                    Maths or BA and compulsory either B.Ed or D.EL.Ed and for higher classes a minimum qualification degree or master holder in Maths, 
                    Science or MA with BeD. 
                </p>
            </div>
            <div class="container p-2">
                <table class="table table-bordered table-sm text-center">
                    <thead class="bg-dark">
                        <tr>
                            <th>Name</th>
                            <th>Class Teacher</th>
                        </tr>
                    </thead>
                    <tbody class="bg-secondary text-capitalize">
                        <tr v-for="staff in this.staff" :key="staff.id">
                            <td>{{staff.first_name +' ' +isNull(staff.middle_name)+' '+ staff.last_name }}</td>
                            <td>{{staff.class}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>  
        </section>
    </div>
</template>

<script>
     export default {
        data(){
            return{
                staff:{}
            }   
        },
        methods:{
            load(){
                axios.get('/api/homestaff/')
                    .then(response => {
                        this.staff = response.data
                    })
            },
            isNull(name){
                if(name!=null) return name
                else return ""
            }
        },
        mounted() {
            this.load()        
        }
    }
</script>
