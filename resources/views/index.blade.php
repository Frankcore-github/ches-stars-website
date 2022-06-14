<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Ches' Stars Secondary School</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="/storage/img/logo.png" type="image/icon type">
        <link href="{{asset('css/app.css')}}" rel="stylesheet">
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/emailjs-com@2/dist/email.min.js"></script>
        <script type="text/javascript">
        (function() {
        emailjs.init("user_Qs6QkCU1iMJrmBKAij3J5");
        })();
        </script>
    </head>
    <body>
        <div id="app" class="index">
            <div class="navigation-wrap bg-light start-header start-style">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <nav class="navbar navbar-expand-md navbar-light">
                                <div class="navbar-brand font-weight-bold">
                                    <img src="/storage/img/logo.png" class="mr-2" alt="">Ches' Stars Secondary School
                                </div>                                
                                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                                
                                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                    <ul class="navbar-nav ml-auto py-4 py-md-0 nav-index">
                                        <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4">
                                            <router-link to="/" class="nav-link">Home</router-link>
                                        </li>
                                        <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4">
                                            <router-link to="/about" class="nav-link">About us</router-link>
                                        </li>
                                        <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4">
                                            <router-link to="/academics" class="nav-link">Academics</router-link>
                                        </li>
                                        <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4">
                                            <router-link to="/staff" class="nav-link">Staff</router-link>
                                        </li>
                                        {{-- <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4">
                                            <router-link to="/admission" class="nav-link">Admission</router-link>
                                        </li> --}}
                                        <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4 dropdown">
                                            <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Admission
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                              <router-link to="/admission" class="dropdown-item">Procedure</router-link>
                                              {{-- <router-link to="/onlineadmission" class="dropdown-item">Class XI Arts</router-link> --}}
                                            </div>
                                        </li>
                                        <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4">
                                            <router-link to="/contact" class="nav-link">Contact</router-link>
                                        </li>
                                        <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4">
                                            <router-link to="/gallery" class="nav-link">Gallery</router-link>
                                        </li>
                                        <li class="pl-4 pl-md-0 ml-0 ml-md-4">
                                            <a style="border-radius: 20px" class="btn btn-outline-primary" href="/login">Login</a>
                                        </li>
                                    </ul>
                                </div>
                            </nav>		
                        </div>
                    </div>
                </div>
            </div>

            <div class="main-content">
                <router-view></router-view>
            </div> 
            
            <footer class="footer pt-4 bg-dark">
                <div class="container text-center">
                        <strong>Copyright &copy; 2019 Ches' Stars Secondary School, Jowai.</strong><br> All rights reserved.
                </div>
              </footer>
        </div>

    </body>
    <script src="{{asset('js/app.js')}}"></script>
    <script>
        (function($) { "use strict";

        $(function() {
            var header = $(".start-style");
            $(window).scroll(function() {    
                var scroll = $(window).scrollTop();
            
                if (scroll >= 10) {
                    header.removeClass('start-style').addClass("scroll-on");
                } else {
                    header.removeClass("scroll-on").addClass('start-style');
                }
            });
        });		
            
        //Menu On Hover
            
        $('body').on('mouseenter mouseleave','.nav-item',function(e){
                if ($(window).width() > 750) {
                    var _d=$(e.target).closest('.nav-item');_d.addClass('show');
                    setTimeout(function(){
                    _d[_d.is(':hover')?'addClass':'removeClass']('show');
                    },1);
                }
        });	

        //Switch light/dark

        $("#switch").on('click', function () {
            if ($("body").hasClass("dark")) {
                $("body").removeClass("dark");
                $("#switch").removeClass("switched");
            }
            else {
                $("body").addClass("dark");
                $("#switch").addClass("switched");
            }
        });

        // hide menus on click
        $('.navbar-collapse li').click(function(){
            $(".navbar-collapse").collapse('hide');
        }); 

})(jQuery); 
    </script>
</html>