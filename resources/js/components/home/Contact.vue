<template>
    <div>
    <section class="contact bg-contact">
        <h1 class="text-white text-center">Contact Us</h1>
        <div class="contact-container">
            <div class="contact-info">
                <div class="contact-box">
                    <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="text">
                        <h3>Address</h3>
                        <p class="no-indent">Lower Chutwakhu, <br>
						District: West Jaintia Hills Jowai-793150, <br>
						Meghalaya(India)</p>
                    </div>
                </div>

                <div class="contact-box">
                    <div class="icon"><i class="fas fa-at"></i></div>
                    <div class="text">
                        <h3>Email</h3>
                        <p class="no-indent"><i>chesstarssecondaryschool@gmail.com</i></p>
                    </div>
                </div>

                <div class="contact-box">
                    <div class="icon"><i class="fas fa-phone-alt"></i></div>
                    <div class="text">
                        <h3>Phone</h3>
                        <p class="no-indent">-8729945134<br>
						    -9402173944</p>
                    </div>
                </div>
            </div>

            <div class="contact-form">
                <form @submit.prevent="sendMessage">
                    <h2>Send Message</h2>
                    <div class="input-box">
                        <input  type="text" name="name" id="name" required="required">
                        <span>Full Name</span>
                    </div>
                    <div class="input-box">
                        <input  type="text" name="email" id="email" required="required">
                        <span>Email</span>
                    </div>
                    <div class="input-box">
                        <textarea  name="message" id="message" required="required"></textarea>
                        <span>Your message</span>
                    </div>
                    <div class="input-box">
                        <input type="submit" name="" id="button" value="send">
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section class="pt-5 custom-header">
        <h1 class="text-center">&nbsp;&nbsp;Map</h1>
        <div class="embed-responsive embed-responsive-16by9">
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d4729.128075020102!2d92.19709676323976!3d25.437061368709188!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x375013889576e1db%3A0x3627e4a3457fcd8b!2sChes&#39;%20Stars%20Secondary%20School!5e0!3m2!1sen!2sin!4v1613217126546!5m2!1sen!2sin" frameborder="0" style="border:0;" allowfullscreen aria-hidden="false" tabindex="0"></iframe>
        </div>
    </section>

    <section>
        <div class="icon-bar">
            <a href="https://www.facebook.com/Ches-Stars-Secondary-School-Jowai-107021964080201/?modal=admin_todo_tour" class="facebook"><i class="fab fa-facebook-f"></i></a>
        </div>
    </section>
    </div>
</template>

<script>
    export default {
        methods:{
            sendMessage: (e) => {
                document.getElementById("button").value="Please wait...";
                emailjs.sendForm('service_jmzrrav', 'template_uk4u3dv', e.target, 'user_Qs6QkCU1iMJrmBKAij3J5')
                    .then((result) => {
                        Toast.fire({
                            icon: 'success',
                            title: 'Message sent successfully'
                        })
                        document.getElementById("button").value="send";
                        document.getElementById("name").value="";
                        document.getElementById("email").value="";
                        document.getElementById("message").value="";
                        console.log('SUCCESS!', result.status , result.text);
                    }, (error) => {
                        Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!'
                            })
                        document.getElementById("button").value="send";
                        document.getElementById("name").value="";
                        document.getElementById("email").value="";
                        document.getElementById("message").value="";
                        console.log('FAILED...', error);
                    });
            }
        },
        mounted() {
            console.log('Component mounted.') 
        }
    }
</script>
