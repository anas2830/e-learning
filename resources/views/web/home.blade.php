@extends('layouts.default')

@section('content')
<!--Konnect Slider -->
<div class='konnect-carousel carousel-image carousel-image-pagination carousel-image-arrows flexslider'>
    <ul class='slides'>
    <!--Slider One-->
    <li class='item'>
        <div class='container'>
        <div class='row pos-rel'>
            <div class='col-sm-12 col-md-6 animate'>
            <h1 class='big fadeInDownBig animated'>Online and Class Room Training</h1>
            <p class='normal fadeInUpBig animated delay-point-five-s'>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris in tincidunt mauris. Etiam arcu enim, laoreet vitae orci vel, rutrum feugiat nibh. Integer feugiat ligula tellus, non pulvinar justo pharetra eu. Nullam vehicula lorem ut diam tincidunt sagittis. Morbi est ligula, posuere in laoreet ac, porta porttitor dui</p>
            <a class='btn btn-bordered btn-white btn-lg fadeInRightBig animated delay-one-s' href='#'> Show more </a> </div>
            <div class='col-md-6 animate pos-sta hidden-xs hidden-sm'> <img class="img-responsive img-right fadeInUpBig animated delay-one-point-five-s" alt="iPhone" src="{{ asset('web/img/slider/student-1.png') }}" /> </div>
        </div>
        </div>
    </li>
    
    <!--Slider Two-->
    <li class='item'>
        <div class='container'>
        <div class='row pos-rel'>
            <div class='col-md-6 animate pos-sta hidden-xs hidden-sm'> <img class="img-responsive img-left fadeInUpBig animated" alt="Circle" src="{{ asset('web/img/slider/student-2.png') }}" /> </div>
            <div class='col-sm-12 col-md-6 animate'>
            <h2 class='big fadeInUpBig animated delay-point-five-s'>Who we are ?</h2>
            <p class='normal fadeInDownBig animated delay-one-s'>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris in tincidunt mauris. Etiam arcu enim, laoreet vitae orci vel, rutrum feugiat nibh. Integer feugiat ligula tellus, non pulvinar justo pharetra eu. Nullam vehicula lorem ut diam tincidunt sagittis. Morbi est ligula, posuere in laoreet ac, porta porttitor dui</p>
            <a class='btn btn-bordered btn-white btn-lg fadeInLeftBig animated delay-one-point-five-s' href='#'> Show more </a> </div>
        </div>
        </div>
    </li>
    
    <!--Slider Three-->
    <li class='item'>
        <div class='container'>
        <div class='row pos-rel'>
            <div class='col-sm-12 col-md-6 animate'>
            <h2 class='big fadeInLeftBig animated'>Clean and Flat</h2>
            <p class='normal fadeInRightBig animated delay-point-five-s'>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris in tincidunt mauris. Etiam arcu enim, laoreet vitae orci vel, rutrum feugiat nibh. Integer feugiat ligula tellus, non pulvinar justo pharetra eu. Nullam vehicula lorem ut diam tincidunt sagittis. Morbi est ligula, posuere in laoreet ac, porta porttitor dui</p>
            <a class='btn btn-bordered btn-white btn-lg fadeInUpBig animated delay-one-s' href='#'> Show more </a> </div>
            <div class='col-md-6 animate pos-sta hidden-xs hidden-sm'> <img class="img-responsive img-right fadeInUpBig animated delay-one-point-five-s" alt="Man" src="{{ asset('web/img/slider/student-3.png') }}" /> </div>
        </div>
        </div>
    </li>
    </ul>
</div>
<!--/. Konnect Slider --> 

<!-- Banner Box -->
<div class="container banner">
    <div class="row" style="display: flex; justify-content: center;">
        <div class="col-sm-4">
            <div class="banner-bar"> <img src="{{ asset('web/img/icons/classroom.png') }}" alt="icon">
            <h3><span>IELTS Academic</span></h3>
            <p>Curabitur ut est a mi fermentum tristique. Aliquam et ante odio. Donec elementum odio eget ex porta, vel laoreet nisl fermentum.</p>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="banner-bar"> <img src="{{ asset('web/img/icons/certificate.png') }}" alt="icon">
            <h3><span>IELTS General</span></h3>
            <p>Curabitur ut est a mi fermentum tristique. Aliquam et ante odio. Donec elementum odio eget ex porta, vel laoreet nisl fermentum.</p>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="banner-bar"> <img src="{{ asset('web/img/icons/job-support.png') }}" alt="icon">
            <h3><span>English Skills</span></h3>
            <p>Curabitur ut est a mi fermentum tristique. Aliquam et ante odio. Donec elementum odio eget ex porta, vel laoreet nisl fermentum.</p>
            </div>
        </div>
    </div>
    <div class="row" style="display: flex; justify-content: center; margin-top: 15px;">
        <div class="col-sm-4">
            <div class="banner-bar"> <img src="{{ asset('web/img/icons/job-support.png') }}" alt="icon">
            <h3><span>Cambrigde English</span></h3>
            <p>Curabitur ut est a mi fermentum tristique. Aliquam et ante odio. Donec elementum odio eget ex porta, vel laoreet nisl fermentum.</p>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="banner-bar"> <img src="{{ asset('web/img/icons/job-support.png') }}" alt="icon">
            <h3><span>IGCSE English</span></h3>
            <p>Curabitur ut est a mi fermentum tristique. Aliquam et ante odio. Donec elementum odio eget ex porta, vel laoreet nisl fermentum.</p>
            </div>
        </div>
    </div>
</div>

<!-- Live Classes -->
<section>
    <div class="container">
    <div class="row">
        <div class="col-md-12"> 
        <!--Services Heading-->
        <h2 class="section-heading">Live Class Schedules</h2>
        <p>Sign up for free to access Live classes</p>
        <div class="template-space"></div>
        </div>
        <div class="live-class">
            <div class="col-md-12 live-class-menu">
                <ul>
                    <li><a href="#" class="liveMenuActive">All Exams</a></li>
                    <li><a href="#">IELTS Academic</a></li>
                    <li><a href="#">IELTS General</a></li>
                    <li><a href="#">English Skills</a></li>
                    <li><a href="#">Cambrigde English</a></li>
                    <li><a href="#">IGCSE English</a></li>
                </ul>
            </div>
            <div class="col-md-12 live-class-data">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Course Name</th>
                            <th>Type</th>
                            <th>Day</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>IELTS Academic</td>
                            <td>Sample</td>
                            <td>Monday</td>
                            <td>14:00 (2pm)(AEDT)</td>
                        </tr>
                        <tr>
                            <td>IELTS Academic</td>
                            <td>Sample</td>
                            <td>Monday</td>
                            <td>14:00 (2pm)(AEDT)</td>
                        </tr>
                        <tr>
                            <td>IELTS Academic</td>
                            <td>Sample</td>
                            <td>Monday</td>
                            <td>14:00 (2pm)(AEDT)</td>
                        </tr>
                        <tr>
                            <td>IELTS Academic</td>
                            <td>Sample</td>
                            <td>Monday</td>
                            <td>14:00 (2pm)(AEDT)</td>
                        </tr>
                        <tr>
                            <td>IELTS Academic</td>
                            <td>Sample</td>
                            <td>Monday</td>
                            <td>14:00 (2pm)(AEDT)</td>
                        </tr>
                        <tr>
                            <td>IELTS Academic</td>
                            <td>Sample</td>
                            <td>Monday</td>
                            <td>14:00 (2pm)(AEDT)</td>
                        </tr>
                        <tr>
                            <td>IELTS Academic</td>
                            <td>Sample</td>
                            <td>Monday</td>
                            <td>14:00 (2pm)(AEDT)</td>
                        </tr>
                        <tr>
                            <td>IELTS Academic</td>
                            <td>Sample</td>
                            <td>Monday</td>
                            <td>14:00 (2pm)(AEDT)</td>
                        </tr>
                        <tr>
                            <td>IELTS Academic</td>
                            <td>Sample</td>
                            <td>Monday</td>
                            <td>14:00 (2pm)(AEDT)</td>
                        </tr>
                        <tr>
                            <td>IELTS Academic</td>
                            <td>Sample</td>
                            <td>Monday</td>
                            <td>14:00 (2pm)(AEDT)</td>
                        </tr>
                        <tr>
                            <td>IELTS Academic</td>
                            <td>Sample</td>
                            <td>Monday</td>
                            <td>14:00 (2pm)(AEDT)</td>
                        </tr>
                        <tr>
                            <td>IELTS Academic</td>
                            <td>Sample</td>
                            <td>Monday</td>
                            <td>14:00 (2pm)(AEDT)</td>
                        </tr>
                        <tr>
                            <td>IELTS Academic</td>
                            <td>Sample</td>
                            <td>Monday</td>
                            <td>14:00 (2pm)(AEDT)</td>
                        </tr>
                        <tr>
                            <td>IELTS Academic</td>
                            <td>Sample</td>
                            <td>Monday</td>
                            <td>14:00 (2pm)(AEDT)</td>
                        </tr>
                        <tr>
                            <td>IELTS Academic</td>
                            <td>Sample</td>
                            <td>Monday</td>
                            <td>14:00 (2pm)(AEDT)</td>
                        </tr>
                        <tr>
                            <td>IELTS Academic</td>
                            <td>Sample</td>
                            <td>Monday</td>
                            <td>14:00 (2pm)(AEDT)</td>
                        </tr>
                        <tr>
                            <td>IELTS Academic</td>
                            <td>Sample</td>
                            <td>Monday</td>
                            <td>14:00 (2pm)(AEDT)</td>
                        </tr>
                        <tr>
                            <td>IELTS Academic</td>
                            <td>Sample</td>
                            <td>Monday</td>
                            <td>14:00 (2pm)(AEDT)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
</section>

<!-- Company profile -->
<section>
    <div class="container">
    <div class="row">
        <div class="col-md-12"> 
        <!--Services Heading-->
        <h2 class="section-heading">Why Choose Us?</h2>
        <div class="template-space"></div>
        </div>
        <div class="col-md-6">
        <h2 class="para-heading">Our Secretes</h2>
        <p>Curabitur ut est a mi fermentum tristique. Aliquam et ante odio. Donec elementum odio eget ex porta, vel laoreet nisl fermentum. Nam risus purus, hendrerit id placerat sit amet, tempor a urna. Maecenas id quam et dolor facilisis pulvinar.</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
        <a class="service-box-button">View More</a> </div>
        <div class="col-md-6"> <img src="{{ asset('web/img/students.jpg') }}" class="img-responsive img-hide-sm" alt="Company"> </div>
    </div>
    </div>
</section>

@endsection

@push('javascript') 
{{-- <script> (function() { var v = document.getElementsByClassName("youtube-player"); for (var n = 0; n < v.length; n++) { v[n].onclick = function () { var iframe = document.createElement("iframe"); iframe.setAttribute("src", "//www.youtube.com/embed/" + this.dataset.id + "?autoplay=1&autohide=2&border=0&wmode=opaque&enablejsapi=1&rel="+ this.dataset.related +"&controls="+this.dataset.control+"&showinfo=" + this.dataset.info); iframe.setAttribute("frameborder", "0"); iframe.setAttribute("id", "youtube-iframe"); iframe.setAttribute("style", "width: 100%; height: 100%; position: absolute; top: 0; left: 0;"); if (this.dataset.fullscreen == 1){ iframe.setAttribute("allowfullscreen", ""); } while (this.firstChild) { this.removeChild(this.firstChild); } this.appendChild(iframe); }; } })(); </script> --}}
@endpush
