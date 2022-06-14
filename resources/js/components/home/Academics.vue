<template>
    <div class="nav-padding">
        <section class="container custom-header" id="academics">
            <h1 class="text-center">Academics</h1>
            <div>
				<p>
				The school since its inception, till date, has followed the syllabus of Meghalaya 
				board of school education which is excellent enough to prepare a child for the next higher studies. The teaching staffs have strictly
				given all their efforts that course curriculum should be completed timely along with revision and monthly test.
				</p>
				<p>
				Extra curricular activities as part of the educational system have been actively
				participated such as sports, educational tour visits and other indoor activities like quiz, debate, singing,  dancing, drawing etc.
				</p>
				<p>
				The school being an English medium, every student while in the school campus must
				learn to communicate each other in English only.
                </p>
            </div>
			<h4 class="highlight">Test/Examination and promotion</h4>
            <div>
				<p>
				The school conduct annually two terminal examinations and two terminal test
				(One before half yearly and one before the promotion examination).
				</p>
				<p>
				Marks obtained from the terminal tests will be added to the annual
				examinations. The children attendance must not fall short below 75% during the year otherwise the pupil shall not be allowed to sit
				for the examinations<br>
				</p>
            </div>
			<h4 class="highlight">School timings</h4>
            <div>
                <p class="no-indent">
                    <ol>
                        <li>Tiny Tots - Nursery : 9.00 a.m - 12.30 p.m</li>
                        <li>KG - Class X : 9.00 a.m - 3.00 p.m</li>
                    </ol>
                </p>
            </div>

            <h1 class="text-center">Dress Code</h1>
            <div class="row">
                <div class="col-sm-6">
                    <h4 class="highlight">Boys : Monday - Thursday </h4>
                    <p class="no-indent">
                        <ol>
                            <li>Pant - Navy blue</li>
                            <li>Shirt - White</li>
                            <li>Neck Tie - Blue with White stripes</li>
                            <li>Socks - White with blue stripes</li>
                            <li>Sweater - Blue with white stripes</li>
                            <li>Shoe - Black</li>
                        </ol>
                    </p>
                    <h4 class="highlight">Friday</h4>
                    <p class="no-indent">
                        <ol>
                            <li>Tracksuit in grey colour both pant and sweat-shirt</li>
                            <li>White T-shirt, with blue stripes</li>
                            <li>Socks with blue stripes and white canvas</li>
                        </ol>
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-md-5 card-aca hover">
                            <div class="card-aca-img" style="background-image:url('/storage/img/mondayboy.jpg');"></div>
                        </div>
                        <div class="col-md-5 card-aca hover">
                            <div class="card-aca-img" style="background-image:url('/storage/img/mondaygirl.jpg');"></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <h4 class="highlight">Girls : Monday - Thursday </h4>
                    <p class="no-indent">
                        <ol>
                            <li>Blue and white checked pleated skirt</li>
                            <li>Shirt - White</li>
                            <li>Neck Tie - Blue with White stripes</li>
                            <li>Socks - White with blue stripes</li>
                            <li>Sweater - Blue with white stripes</li>
                            <li>Shoe - Black</li>
                        </ol>
                    </p>
                    <h4 class="highlight">Friday</h4>
                    <p class="no-indent">
                        <ol>
                            <li>Tracksuit in grey colour both pant and sweat-shirt</li>
                            <li>White T-shirt, with blue stripes</li>
                            <li>Socks with blue stripes and white canvas</li>
                        </ol>
                    </p>
                    <div class="row justify-content-center">
                        <div class="col-md-5 card-aca hover">
                            <div class="card-aca-img" style="background-image:url('/storage/img/fridayboy.jpg');"></div>
                        </div>
                        <div class="col-md-5 card-aca hover">
                            <div class="card-aca-img" style="background-image:url('/storage/img/fridaygirl.jpg');"></div>
                        </div>
                    </div>
                </div>
            </div>
            <h1 class="text-center">Pass percentage</h1>
             <div class="container p-2">
					<table class="table table-bordered table-sm text-center">
						<thead class="bg-dark">
							<tr>
								<th>Year</th>
								<th>Percentage</th>
							</tr>
						</thead>
						<tbody class="bg-secondary">
							<tr v-for="academics in this.academics" :key="academics.id">
								<td>{{academics.year}}</td>
								<td>{{(academics.no_of_passed_student / academics.no_of_student * 100).toFixed(2)}} %</td>
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
                academics:{}
            }   
        },
        methods:{
            load(){
                axios.get('/api/homeacademics/')
                    .then(response => {
                        this.academics = response.data
                    })
			}
        },
        mounted() {
            this.load()        
        }
    }
</script>
