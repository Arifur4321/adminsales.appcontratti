@extends('layouts.master-without-nav')

@section('title')
    @lang('translation.Register') 2
@endsection

@section('css')
    <!-- owl.carousel css -->
    <link rel="stylesheet" href="{{ URL::asset('build/libs/owl.carousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('build/libs/owl.carousel/assets/owl.theme.default.min.css') }}">
    <link href="{{ URL::asset('build/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('body')

    <body class="auth-body-bg">
    @endsection

    @section('content')

        <div>
            <div class="container-fluid p-0">
                <div class="row g-0">

                    <div class="col-xl-8">
                        <div class="auth-full-bg pt-lg-5 p-4">
                            <div class="w-100">
                                <div class="bg-overlay"></div>
                                <div class="d-flex h-100 flex-column">

                                    <div class="p-4 mt-auto">
                                        <div class="row justify-content-center">
                                            <div class="col-lg-7">
                                                <div class="text-center">

                                                    <h4 class="mb-3"><i
                                                            class="bx bxs-quote-alt-left text-primary h1 align-middle me-3"></i><span
                                                            class="text-primary">5k</span>+  Codice 1% clients</h4>

                                                    <div dir="ltr">
                                                        <div class="owl-carousel owl-theme auth-review-carousel"
                                                            id="auth-review-carousel">
                                                            <div class="item">
                                                                <div class="py-3">
                                                                       <!--   <p class="font-size-16 mb-4">" Fantastic theme with a
                                                                        ton of options. If you just want the HTML to
                                                                        integrate with your project, then this is the
                                                                        package. You can find the files in the 'dist'
                                                                        folder...no need to install git and all the other
                                                                        stuff the documentation talks about. "</p>
                                                                         -->

                                                                    <div>
                                                                        <h4 class="font-size-16 text-primary"> </h4>
                                                                        <p class="font-size-14 mb-0">- Codice 1% User</p>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                            <div class="item">
                                                                <div class="py-3">
                                                                <!--    <p class="font-size-16 mb-4">" If Every Vendor on Envato
                                                                        are as supportive as Themesbrand, Development with
                                                                        be a nice experience. You guys are Wonderful. Keep
                                                                        us the good work. "</p>  
                                                                        -->

                                                                    <div>
                                                                        <h4 class="font-size-16 text-primary"> </h4>
                                                                        <p class="font-size-14 mb-0">-  Codice 1% User</p>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->

                    <div class="col-xl-4">
                        <div class="auth-full-page-content p-md-5 p-4">
                            <div class="w-100">

                                <div class="d-flex flex-column h-100">
                                    <div class="mb-4 mb-md-5">
                                        <a href="index" class="d-block auth-logo">
                                            <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" 
                                            height="55" width="100" class="auth-logo-dark mx-auto"
                                                >
                                            <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="18"
                                                class="auth-logo-light">
                                        </a>
                                    </div>
                                    <div class="my-auto">

                                        <div>
                                            <h5 class="text-primary">Register Your Company account</h5>
                                            <p class="text-muted">Get your account now.</p>
                                        </div>

                                        <div class="mt-4">
                                            
                                            <form method="POST" class="form-horizontal"   action="{{ route('register') }}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="useremail" class="form-label">Email <span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="useremail"
                                                    value="{{ old('email') }}" name="email" placeholder="Enter email" autofocus required>
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
        
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                    value="{{ old('name') }}" id="name" name="name" autofocus required
                                                        placeholder="Enter name">
                                                    @error('name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
            
                                                <div class="mb-3">
                                                        <label for="userpassword" class="form-label">Password <span class="text-danger">*</span></label>
                                                        
                                                        <div class="input-group">

                                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="userpassword" name="password"
                                                            placeholder="Enter password"    
                                                            aria-label="Password" aria-describedby="password-addon" autofocus required  >

                                                            <button class="btn btn-light " type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button> 
                                                        </div>      
                                                            @error('password')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                    
                                                        @enderror
                                                        
                                                    </div>
         

                                                <div class="mb-3">
                                                    <label for="confirmpassword" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                                    
                                                    <div class="input-group">
                                                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                                        id="confirmpassword"
                                                        name="password_confirmation" placeholder="Enter Confirm password" 
                                                        aria-label="password_confirmation" aria-describedby="password_confirmation" autofocus required>
                                                        
                                                        <button class="btn btn-light" type="button" id="togglePassword"><i class="mdi mdi-eye-outline"></i></button>
                                                    </div>    
                                                    
                                                    @error('password_confirmation')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>  

                                                <script>
                                                    document.getElementById('togglePassword').addEventListener('click', function (e) {
                                                        const passwordInput = document.getElementById('confirmpassword');
                                                        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                                                        passwordInput.setAttribute('type', type);

                                                        // Toggle the eye icon
                                                        this.innerHTML = type === 'password' ? '<i class="mdi mdi-eye-outline"></i>' : '<i class="mdi mdi-eye-off-outline"></i>';
                                                    });
                                                </script>

                                                
        
                                              <!-- Date of Birth -->
                                               <!-- Date of Birth -->
<div class="mb-3">
    <label for="userdob">Date of Birth <span class="text-danger">*</span></label>
    <div class="input-group" id="datepicker1">
        <input type="text" class="form-control @error('dob') is-invalid @enderror" placeholder="dd-mm-yyyy"
            name="dob" autofocus required id="dob" value="{{ old('dob') }}">
        <span class="input-group-text" id="dob-icon"><i class="mdi mdi-calendar"></i></span>
        @error('dob')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

        
                                                <div class="mb-3">
                                                    <label for="avatar">Profile Picture <span class="text-danger">  </span></label>
                                                    <div class="input-group">
                                                        <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="inputGroupFile02" name="avatar"  >
                                                        <label class="input-group-text" for="inputGroupFile02">Upload</label>
                                                    </div>
                                                    @error('avatar')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>




        
                                                <div class="mt-4 d-grid">
                                                    <button class="btn btn-primary waves-effect waves-light"
                                                        type="submit">Register</button>
                                                </div>
        
                                                <div class="mt-4 text-center">
                                                    <h5 class="font-size-14 mb-3">Sign up using</h5>
        
                                                    <ul class="list-inline">
                                                        <li class="list-inline-item">
                                                            <a href="#"
                                                                class="social-list-item bg-primary text-white border-primary">
                                                                <i class="mdi mdi-facebook"></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item">
                                                            <a href="#"
                                                                class="social-list-item bg-info text-white border-info">
                                                                <i class="mdi mdi-twitter"></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item">
                                                            <a href="#"
                                                                class="social-list-item bg-danger text-white border-danger">
                                                                <i class="mdi mdi-google"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
        
                                                <div class="mt-4 text-center">
                                                    <p class="mb-0">By registering you agree to the  Codice 1% <a href="#"
                                                            class="text-primary">Terms of Use</a></p>
                                                </div>
                                            </form>

                                            <div class="mt-3 text-center">
                                                <p>Already have an account ? <a href="{{ url('login') }}"
                                                        class="fw-medium text-primary"> Login</a> </p>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="mt-4 mt-md-3 text-center">
                                        <p class="mb-0">© <script>
                                                document.write(new Date().getFullYear())

                                            </script>   Crafted with <i class="mdi mdi-heart text-danger"></i> by
                                             Codice 1%</p>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container-fluid -->
        </div>


        <!-- Bootstrap Modal -->
<div class="modal fade" id="registrationSuccessModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Success!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                New user registered successfully! Please check your email for details.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="modalOkButton">OK</button>
            </div>
        </div>
    </div>
</div>


    @endsection
    @section('script')
        <script src="{{ URL::asset('build/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
        <!-- owl.carousel js -->
        <script src="{{ URL::asset('build/libs/owl.carousel/owl.carousel.min.js') }}"></script>
        <!-- auth-2-carousel init -->
        <script src="{{ URL::asset('build/js/pages/auth-2-carousel.init.js') }}"></script>

          <!-- Initialize Datepicker and Handle Icon Click -->
 
        <!-- SweetAlert2 -->
        <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

 
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>


    <!-- Bootstrap Datepicker CSS and JS from CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <script>
        // Initialize Datepicker
        $('#dob').datepicker({
            format: 'dd-mm-yyyy',         // Date format
            endDate: '0d',                // Restrict future dates
            autoclose: true,              // Close datepicker after date selection
            todayHighlight: true          // Highlight today's date
        });

        // Open datepicker when calendar icon is clicked
        document.getElementById('dob-icon').addEventListener('click', function () {
            $('#dob').datepicker('show');  // Trigger datepicker to show on icon click
        });
     

        $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success')) {
            Swal.fire({
                title: 'Success!',
                text: 'New user registered successfully! Please check your email for details.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/Admin-List"; // Adjust if needed
                }
            });
        }
    });

    </script>
 



    @endsection
