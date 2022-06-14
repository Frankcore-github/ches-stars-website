<template>
    <div class="nav-padding">
		<section class="container aboutus custom-header" id="about">
            <h1 class="text-center">The school at a glance</h1>
            <div>
                <div class="row justify-content-center">
                    <div class="cards hover">
                        <div class="cards-img" style="background-image:url('/storage/img/school1.jpg');"></div>
                    </div>
                    <div class="cards hover">
                        <div class="cards-img" style="background-image:url('/storage/img/school4.jpg');"></div>
                    </div>
                    <div class="cards hover">
                        <div class="cards-img" style="background-image:url('/storage/img/school2.jpg');"></div>
                    </div>
                </div>
                <p>
                    The emblem of the school totally signifies the principle and foundation where the
                    school stands for. These elements in the emblem provide the following:-
                    <br><br>1. Motto - "To Learn To Shine."<br>2.  The Star - It symbolic to indicate illumination by the divine Light<br>
                    3. The Shield - It signifies the divine protection.<br>4. Yellow - It indicate to become Precious like Gold.<br>5. Blue- It indicates Learning God's deep love to His children. </p>
                    <p>
                    We focus to make discipline a core value, encourage students to develop a sense
                    of responsibility through a well planned academic schedule, House system, various Inter School, Intra School competitions and Sports activities.
                    </p>
                    <p>
                    The school has a well-planned curriculum where extra curricular activities are also given due
                    importance. In order to better prepare and equips a child for secondary level, where we use MBOSE syllabus, we stress the importance to combine both CBSE
                    and ICSE syllabus right from pre- primary, lower primary upto upper-primary. Every student must possess a school dairy which provides all the important
                    information's of the school like routine, rules and regulation, uniforms, activities and feedback from teachers. The management and our team of experienced
                    teachers are doing their best to give every child the required edge to make his/her mark in academic environment. </p>
                    <center><school-anthem></school-anthem></center>
            </div>
            <h1 class="text-center">Vision and Mission</h1>
            <div>
                <p>
                    It is the mission and aspiration of the school to ensure that every student of the school 
                    will become in course of future, a responsible citizen who will serve the society in various fields and capacities that will contribute to the betterment
                    of social framework.
                </p>
            </div>
            <h1 class="text-center">Goal</h1>
            <div>
                <p>
                    The school aims to impart not only the primary knowledge of our surrounding world but also 
                    to imbibe moral values and principles based on Christian faith and practices.
                </p>
            </div>
        </section>
        <div class="bg-history"> 
        </div>
        <section class="container text-center custom-header" id="history">
            <h1>History</h1>
            <div>
					<p>The school was founded on March 1991 by Shri (Late) Jwan Jones Synkon and his wife Smt (Late)
                    Sibylrease Chullai and their last daughter Smt. Marchelang Jones Chullai.</p>
                    <p>
                    The name Ches'Stars signifies 'Penta1 CrosticCildra' symbolic the five children of the late
                    founder whose every nick name end in Ches so the school derived its title as Ches' stars secondary school. It is located at Lower Chutwakhu, Jowai West
                    Jaintia Hills District within the State of Meghalaya -North East India.
                    </p>
                    <p>
                    At the beginning, the school was started within our residential compound with only a nursery 
                    class of 30 – 35 students. Gradually as a family we totally sacrificed the whole building for proceeding of each class finally till it reached class X level. 
                </p>
            </div>
        </section>
        <section class="container custom-header"> 
            <h1 class="text-center">Achievements and Events</h1>
            <div>
                <p>
                    The school has crossed many milestones and won many achievements over the years.
                    Many students have performed exceptionally well in their SSLC exams. Our students have actively participated in several extracurricular programs 
                    and in the past won numerous accolades in essay competitions, exhibitions, quizzes, sports and other events.
                </p>
            </div>
            <div class="container p-2">
                <table class="table table-bordered table-sm text-center">
                    <thead class="bg-dark">
                        <tr>
                            <th>Events</th>
                            <th>Position</th>
                            <th>Year</th>
                        </tr>
                    </thead>
                    <tbody class="bg-secondary">
                        <tr v-for="about in this.about" :key="about.id">
                            <td>{{about.event_name}}</td>
                            <td>{{about.event_position}}</td>
                            <td>{{about.event_year}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>          
        </section>
		
		
    </div>
</template>

<script>
    $('.cards').delay(1800).queue(function(next) {
		$(this).removeClass('hover');
		$('a.hover').removeClass('hover');
		next();
	});
    export default {
        data(){
            return{
                about:{}
            }   
        },
        methods:{
            load(){
                axios.get('/api/homeabout/')
                    .then(response => {
                        this.about = response.data
                    })
            },
        },
        mounted() {
            this.load()        
        }
    }
</script>
