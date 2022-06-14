
<template>
    <div v-if="$gate.isAdmin()" class="container">
        <div class="card bg-light mt-5">
                <div class="card-header text-muted border-bottom-0">

                </div>
                <div class="card-body pt-2">
                  <div class="row">
                    <div class="col-7">
                      <i class="fas fa-id-card"></i><p class="text-muted text-sm"><b>Name: </b>{{ profile.first_name +' '+ isNull(profile.middle_name) +' '+  profile.last_name}} </p>
                      <p v-if="profile.class" class="text-muted text-sm text-capitalize"><b>Class: </b> {{profile.class }}</p>
                      <p class="text-muted text-sm"><b>User Id: </b> {{profile.user_id}}</p> 
                      <p v-if="profile.classes" class="text-muted text-sm"><b>Classes: </b> {{profile.classes}} </p>
                      <ul class="ml-4 mb-0 fa-ul text-muted">
                        <li class="small p-2"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Address: {{profile.address}}</li>
                        <li class="small p-2"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> Phone #: {{profile.phoneno}}</li>
                      </ul>
                    </div>
                    <div class="col-3 text-center">
                      <img :src="getProfilePhoto()" alt="" @click="showProfileImage" class="img-circle img-fluid">
                    </div> 
                    
                  </div>
                </div>
                <div class="card-footer">
                </div>
              </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                file_name: 'Choose file',
                photo: null,
                profile: {},
                id: this.$route.params.id
            }
        },
        methods: {
            loadProfile(){   
                let loader = this.$loading.show() 
                axios.get('/api/user/'+this.id)
                .then(({data}) => (this.profile = data))
                .finally(()=>{loader.hide()})
            },
            getProfilePhoto(){
                if(this.profile.photo === null)
                    return "/storage/img/profile/default.png" 
                else
                    return "/storage/img/profile/"+ this.profile.photo
            },
            showProfileImage(){
                if(this.profile.photo != null){
                     Swal.fire({
                        imageUrl: "/storage/img/profile/"+ this.profile.photo,
                        imageWidth: 500,
                        imageHeight: 500,
                        imageAlt: 'Custom image',
                        })
                }
                   
            },
            isNull(name){
                if(name!=null) return name
                else return ""
            }
        },
        mounted() { 
            if(this.$gate.isAdmin()){
                this.loadProfile();
            }
        }
    }
</script>
