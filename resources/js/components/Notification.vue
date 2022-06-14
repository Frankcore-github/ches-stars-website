<template>
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown show">
        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
          <i class="fas fa-bell fa-2x"></i>
          <span class="badge badge-warning navbar-badge">{{count}}</span>
        </a>
        <div v-if="count > 0" class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span @click="readAll()" class="dropdown-item dropdown-header">Mark all as read <i class="fa fa-check float-right" aria-hidden="true"></i></span>
        <template v-for="notifications in this.notifications">
          <div class="dropdown-item"  :key="notifications.id"> <!-- loop here -->
            <span class="right badge badge-success">New </span>
            <span v-if="$gate.isStudent()" class="finger-pointer" @click="student(notifications.id, notifications.data.message.subject)">{{notifications.data.message.message}}</span>
            <span v-else class="finger-pointer"  @click="staff(notifications.id)">{{notifications.data.message.message}}</span>
            <div class="dropdown-divider"></div>
          </div>
        </template>
        </div>
      </li>
    </ul>
</template>

<script>
    export default {
        data(){
            return{
                notifications: {},
                count: 0,
                oldcount: null
            }
        },
        methods:{
            readAll(){
                axios.get('/api/markallasread').then(() =>{
                    this.loadNotifications();
                })
            },
            student(id, subject){
                axios.get('/api/markasread/'+id).then(() =>{
                    this.loadNotifications(),
                    this.$router.push('/student/note/'+subject);
                })
            },
            staff(id){
                axios.get('/api/markasread/'+id).then(() =>{
                    this.loadNotifications(),
                    this.$router.push('/staff/assignment')
                })
            },
            loadNotifications(){
             axios.get('/api/shownotifications')
             .then(({data}) => (this.notifications = data.object1, this.count = data.object2))
             .finally(()=>{
                if(this.count > 0){
                    if(this.$gate.isStaff()){
                        NotificationToast.fire({
                            icon: 'info',
                            title: this.count+' Assignments Submitted'    
                        })
                     }else{
                         NotificationToast.fire({
                                icon: 'info',
                                title: 'You Have '+this.count+' New Notes'    
                            })
                     }
                }
             })
            },
            Notifications(){
             axios.get('/api/shownotifications')
             .then(({data}) => (this.notifications = data.object1, this.oldcount = data.object2))
             .finally(()=>{
                if(this.oldcount != this.count){
                    if(this.$gate.isStaff()){
                        NotificationToast.fire({
                            icon: 'info',
                            title: this.count+' Assignments Submitted'    
                        })
                     }else{
                         NotificationToast.fire({
                                icon: 'info',
                                title: 'You Have '+this.count+' New Notes'    
                            })
                     }
                }
             })
            }
        },
        mounted() {
            if (Notification.permission !== 'denied' || Notification.permission === "default") {
                Notification.requestPermission(function (permission){
                    console.log(Notification.permission)
                });
            }
            console.log(Notification.permission)
            this.loadNotifications()
            Echo.channel('Notification')
                  .listen('NotificationEvent',()=>{
                    this.loadNotifications() 
                    if(this.$gate.isStudent()){
                        navigator.serviceWorker.ready.then(function(registration) {
                             registration.showNotification("Ches' Stars Secondary School",{
                            body: "You Have "+parseInt(this.count+1)+" New Notes",
                            icon: "/storage/img/logo.png",
                            vibrate: [200, 100, 200]
                        })
                         
                         registration.onclick = (e) =>{
                            window.location.href = "https://www.csssj.in/login"
                        }
                         });
                        const noti = new Notification("Ches' Stars Secondary School",{
                            body: "You Have "+parseInt(this.count+1)+" New Notes",
                            icon: "/storage/img/logo.png"
                        })
                        noti.onclick = (e) =>{
                            window.location.href = "https://www.csssj.in/login"
                        }
                    } 
                    else {navigator.serviceWorker.ready.then(function(registration) {
                             registration.showNotification("Ches' Stars Secondary School",{
                            body: parseInt(this.count+1)+" Assignments Submitted",
                            icon: "/storage/img/logo.png",
                            vibrate: [200, 100, 200]
                        })
                        
                         registration.onclick = (e) =>{
                            window.location.href = "https://www.csssj.in/login"
                        }
                         });
                        const noti = new Notification("Ches' Stars Secondary School",{
                            body: parseInt(this.count+1)+" Assignments Submitted",
                            icon: "/storage/img/logo.png"
                        })
                        noti.onclick = (e) =>{
                            window.location.href = "https://www.csssj.in/login"
                        }
                    }    
                             
                  }) 
        }
    }
</script>
