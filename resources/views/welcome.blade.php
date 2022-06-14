<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Ches' Stars Secondary School</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="/storage/img/logo.png" type="image/icon type">
        <link href="{{asset('css/app.css')}}" rel="stylesheet">
    </head>
    <body>
        <div class="container" id="app">
            <header>
              <nav class="navbar navbar-expand-lg navbar-dark">
                  <img src="{{asset('/storage/img/logo.png')}}" width="60" class="d-inline-block align-top" alt="" loading="lazy">
                  <a class="navbar-brand mr-auto" href="#">
                      <strong>&nbsp; &nbsp;Ches' Stars Secondary School</strong>
                      <br>&nbsp; &nbsp;
                      <span>"To Learn To Shine"</span>
                  </a>
                  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon text-dark"></span>
                  </button>
                  <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav ml-auto">
                      <li class="nav-item">
                        <router-link to="/home">
                          <a class="nav-link" data-toggle="collapse" data-target=".navbar-collapse.show">Home</a>
                        </router-link>
                      </li>
                      <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" aria-haspopup="true" aria-expanded="false">
                          About Us
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                          <router-link to="/about">
                            <a class="dropdown-item" data-toggle="collapse" data-target=".navbar-collapse.show">About Us</a>
                          </router-link>
                          <router-link to="/history">
                            <a class="dropdown-item" data-toggle="collapse" data-target=".navbar-collapse.show">History &amp; Events</a>
                          </router-link>
                          <router-link to="/principal">
                            <a class="dropdown-item" data-toggle="collapse" data-target=".navbar-collapse.show">Principal</a>
                          </router-link>
                        </div>
                      </li>
                      <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" aria-haspopup="true" aria-expanded="false">
                          Academics
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                          <router-link to="/academics">
                            <a class="dropdown-item" data-toggle="collapse" data-target=".navbar-collapse.show">Academics</a>
                          </router-link>
                          <router-link to="/schoolnotice">
                            <a class="dropdown-item" data-toggle="collapse" data-target=".navbar-collapse.show">Notice Board</a>
                          </router-link>
                        </div>
                      </li>
                      <li class="nav-item">
                        <router-link to="/schoolstaff">
                          <a class="nav-link">Staff</a>
                        </router-link>
                      </li>
                      <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" aria-haspopup="true" aria-expanded="false">
                          Admission
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                          <router-link to="/admission">
                            <a class="dropdown-item" data-toggle="collapse" data-target=".navbar-collapse.show">Admission</a>
                          </router-link>
                          <router-link to="/contact">
                            <a class="dropdown-item" data-toggle="collapse" data-target=".navbar-collapse.show">Contact</a>
                          </router-link>
                        </div>
                      </li>
                      <li class="nav-item">
                        <router-link to="/schoolgallery">
                          <a class="nav-link" data-toggle="collapse" data-target=".navbar-collapse.show">Gallery</a>
                        </router-link>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" href="{{url('/login')}}">Login</a>
                      </li>
                    </ul>
                  </div>
              </nav>
            </header>

            <main>
              <router-view></router-view>
            </main> 

            <footer id="sticky-footer" class="py-4 bg-dark text-dark-50">
              <div class="container text-center">
                <small>Copyright &copy; 2019 Ches' Stars Secondary School, Jowai. All rights reserved.</small>
              </div>
            </footer>

        </div>
    </body>
    <script src="{{asset('js/app.js')}}"></script>
</html>
