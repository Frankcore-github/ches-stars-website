/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import Gate from './Gate'
import FormWithUpload from './fileUpload'
import Moment from 'moment'
import Swal from 'sweetalert2'
import VueRouter from 'vue-router'
import VCalendar from 'v-calendar'
import VueProgressBar from 'vue-progressbar'
import Loading from 'vue-loading-overlay'
import {Form, HasError, AlertError } from 'vform'
window.Vue = require('vue')
window.Fire = new Vue()
window.Form= Form
window.FormWithUpload = FormWithUpload
window.Swal = Swal
window.Moment = Moment
Vue.config.productionTip = false;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
Vue.component('pagination', require('laravel-vue-pagination'))
Vue.prototype.$gate = new Gate(window.user)
Vue.use(VueRouter)
Vue.use(VCalendar, {
  componentPrefix: 'vc'
})
Vue.use(VueProgressBar, {
  color: '#bffaf3',
  failedColor: '#874b4b',
  thickness: '5px',
  transition: {
    speed: '0.2s',
    opacity: '0.6s',
    termination: 300
  },
  autoRevert: true,
  location: 'top',
  inverse: false
})

Vue.use(Loading,{
  color: '#343148',
  loader: 'bars',
  zIndex: 999,
});

const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  onOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})

const NotificationToast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 4000
})

window.NotificationToast = NotificationToast
window.Toast = Toast

const routes = [

    { path: '/admin/dashboard', component: require('./components/admin/Dashboard.vue').default },
    { path: '/admin/developer', component: require('./components/admin/Developer.vue').default },
    { path: '/admin/staff', component: require('./components/admin/Staff.vue').default },
    { path: '/admin/students', component: require('./components/admin/Student.vue').default },
    { path: '/admin/users', component: require('./components/admin/Users.vue').default },
    { path: '/admin/profile/:id', component: require('./components/admin/Profile.vue').default },
    { path: '/admin/events', component: require('./components/admin/Event.vue').default },
    { path: '/admin/gallery', component: require('./components/admin/Gallery.vue').default },
    { path: '/admin/newsfeed', component: require('./components/admin/NewsFeed.vue').default },
    { path: '/admin/notice', component: require('./components/admin/Notice.vue').default },
    { path: '/admin/teachers', component: require('./components/admin/ClassTeacher.vue').default },
    { path: '/admin/SSLC', component: require('./components/admin/SSLC.vue').default },
    { path: '/admin/subjectList', component: require('./components/admin/SubjectList.vue').default },
    { path: '/admin/allclass', component: require('./components/admin/AllClass.vue').default },
    { path: '/admin/statistics', component: require('./components/admin/Statistics.vue').default },
    { path: '/admin/notes/:userid/:classname/:subjectname', component: require('./components/admin/StaffNotes.vue').default },
    { path: '/admin/examination', component: require('./components/admin/ExaminationPortal.vue').default },
    { path: '/admin/studentvotes', component: require('./components/admin/StudentVotes.vue').default },

    { path: '/staff/dashboard', component: require('./components/staff/Dashboard.vue').default },
    { path: '/staff/subjects/:cls', component: require('./components/staff/Subject.vue').default },
    { path: '/staff/classroom', component: require('./components/staff/Classroom.vue').default },
    { path: '/staff/assignment', component: require('./components/staff/Assignment.vue').default },
    { path: '/staff/uploadedfiles', component: require('./components/staff/UploadedFile.vue').default },
    { path: '/staff/note/:cls/:subject', component: require('./components/staff/Note.vue').default },
    { path: '/staff/examination', component: require('./components/staff/ExaminationPortal.vue').default },
    { path: '/staff/attendance/:cls/:subject', component: require('./components/staff/Attendance.vue').default },
    

    { path: '/student/dashboard', component: require('./components/student/Dashboard.vue').default },
    { path: '/student/assignment', component: require('./components/student/Assignment.vue').default },
    { path: '/student/classroom', component: require('./components/student/Subject.vue').default },
    { path: '/student/examination', component: require('./components/student/ExaminationPortal.vue').default },
    { path: '/student/note/:subject', component: require('./components/student/Note.vue').default },

   { path: '/', component: require('./components/home/Index.vue').default },
   { path: '/about', component: require('./components/home/About.vue').default },
   { path: '/academics', component: require('./components/home/Academics.vue').default },
   { path: '/staff', component: require('./components/home/Staff.vue').default },
   { path: '/admission', component: require('./components/home/Admission.vue').default },
   { path: '/contact', component: require('./components/home/Contact.vue').default },
   { path: '/gallery', component: require('./components/home/Gallery.vue').default },
  //  { path: '/onlineadmission', component: require('./components/home/OnlineAdmission.vue').default },
  
   { path: '/tutorials', component: require('./components/Tutorials.vue').default },
    { path: '/player/:id', component: require('./components/Player.vue').default },
    { path: '*', component: require('./components/FileNotFound.vue').default }
  ]

const router = new VueRouter({
  routes
})

Vue.filter('upText', function(text){
  return text.charAt(0).toUpperCase() + text.slice(1);
})

Vue.filter('percentage', function(value){
   if(value === 100 || value === 0){
     return value;
   }
   return value.toFixed(2);
})

Vue.filter('myDate', function(newdate){
  return Moment(newdate).format('MMMM Do YYYY');
})

Vue.filter('AttendanceDate', function(newdate){
  return Moment(newdate).format('MMMM DD');
})


Vue.filter('classDate', function(newdate){
  return Moment(newdate).format('MMM Do h:mm a');
})

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


Vue.component(
  'passport-clients',
  require('./components/passport/Clients.vue').default
)
Vue.component(
  'profile-image',
  require('./components/ProfileImage.vue').default
)

Vue.component(
  'comment-note',
  require('./components/NoteComment.vue').default
)

Vue.component(
  'comment-assignment',
  require('./components/AssignmentComment.vue').default
)

Vue.component(
  'logo-img',
  require('./components/Logo.vue').default
)

Vue.component(
  'competition',
  require('./components/home/Competition.vue').default
)

Vue.component(
  'notification-bell',
  require('./components/Notification.vue').default
)

Vue.component(
  'passport-authorized-clients',
  require('./components/passport/AuthorizedClients.vue').default
)

Vue.component(
  'passport-personal-access-tokens',
  require('./components/passport/PersonalAccessTokens.vue').default
)

//index page

Vue.component(
  'school-anthem',
  require('./components/home/SchoolAnthem.vue').default
)



const app = new Vue({
  el: '#app',
  router,
  data:{
    search: ''
  },
  methods:{
    loadSearch(){
      Fire.$emit('searching');
    }
  }
})

