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
                                                                    <div>
                                                                        <h4 class="font-size-16 text-primary"> </h4>
                                                                        <p class="font-size-14 mb-0">- Codice 1% User</p>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                            <div class="item">
                                                                <div class="py-3">
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
                        <title>Register Company</title>
                        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                        <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
                        
                        <div class="container mt-12">
                            <div class="row justify-content-center">
                                <div class="col-xl-10">
                                    <div class="auth-full-page-content p-md-5 p-4">
                                        <div class="w-100">
                                            <div class="d-flex flex-column h-100">
                                                <div class="mb-4 mb-md-5">
                                                    <a href="index" class="d-block auth-logo">
                                                        <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="75" width="100" class="auth-logo-dark mx-auto">
                                                        <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="18" class="auth-logo-light">
                                                    </a>
                                                </div>
                                                <div class="my-auto">
                                                    <div>
                                                        <h5 class="text-primary">Register Company</h5>
                                                        <p class="text-muted">Enter your company details below.</p>
                                                    </div>

                                                    <div class="mt-4">
                                                        <form method="POST" class="form-horizontal" action="{{ route('company.store') }}">
                                                            @csrf
                                                            <div class="mb-3">
                                                                <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name"
                                                                    value="{{ old('company_name') }}" name="company_name" placeholder="Enter company name" autofocus required>
                                                                @error('company_name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address"
                                                                    placeholder="Enter address" autofocus required>{{ old('address') }}</textarea>
                                                                @error('address')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="vat_number" class="form-label">VAT Number <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control @error('vat_number') is-invalid @enderror" id="vat_number"
                                                                    value="{{ old('vat_number') }}" name="vat_number" placeholder="Enter VAT number" required>
                                                                @error('vat_number')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>

                                                            <div class="mt-4 d-grid">
                                                                <button class="btn btn-primary waves-effect waves-light" type="submit">Submit & Next</button>
                                                            </div>
                                                        </form>

                                                        <div class="mt-3 text-center">
                                                            <!-- <p>Don't have an account? <a href="{{ url('register') }}" class="fw-medium text-primary">Signup now</a></p> -->
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mt-4 mt-md-3 text-center">
                                                    <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> Crafted with <i class="mdi mdi-heart text-danger"></i> by Codice 1%</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container-fluid -->
        </div>

    @endsection
    @section('script')
        <script src="{{ URL::asset('build/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
        <!-- owl.carousel js -->
        <script src="{{ URL::asset('build/libs/owl.carousel/owl.carousel.min.js') }}"></script>
        <!-- auth-2-carousel init -->
        <script src="{{ URL::asset('build/js/pages/auth-2-carousel.init.js') }}"></script>
    @endsection
