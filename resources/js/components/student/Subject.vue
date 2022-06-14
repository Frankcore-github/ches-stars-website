<template>
    <div v-if="$gate.isStudent()" class="container">
        <div class="card-deck">
            <div class="row">
                <div class="col-sm-12 col-md-4 p-2" v-for="subjects in this.subjects" :key="subjects.id">
                    <div class="card">
                    <img class="card-img-top" :src="image(subjects.subject_name)" alt="Card image cap">
                    <div class="card-body">
                        <router-link :to="`/student/note/${subjects.subject_name}`" class="btn btn-sm btn-block" :style="{'color' : subjects.background_color, 'border' : '2px solid '+subjects.background_color}">{{subjects.subject_name}}</router-link>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                subjects:{}
            }
        },
        methods:{
            loadSubjects(){
                let loader = this.$loading.show() 
                axios.get('/api/showsubjects').then(({data}) => (this.subjects = data)).finally(()=>{loader.hide()});
            },
            image(subject){
                return '/storage/img/studentImg/'+ subject +'.jpg'
            }
        },
        mounted() {
            if(this.$gate.isStudent()){
                this.loadSubjects()
            }
            console.log('Component mounted.')
        }
    }
</script>
