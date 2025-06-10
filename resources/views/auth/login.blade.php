@extends('admin.layout1')
@section('content')
<main class="login-form">
    <div class="cotainer">
        <div class="row justify-content-center">

           

            <div class="col-md-4" style="padding-top: 40px;">
                <div class="card" style="background-color: none;">

                     @if (Session::has('error'))
                        <div class="alert alert-danger">
                             {{ Session::get('error') }}    
                        </div>
                    @endif
            
                    <h3 class="card-header text-center" style="background: black;color: #bcbcbc;">
                          <!-- <img src="{{ asset('./public/theme/dist/img/sparklogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"> -->
                          Gymni Fitness
                    </h3>
                    <div class="card-body">
                        <form method="POST" action="{{ route('login.custom') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <input type="text" placeholder="Email" id="email" class="form-control" name="email" required
                                    autofocus>
                                @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                            <div class="form-group mb-3">
                                <input type="password" placeholder="Password" id="password" class="form-control" name="password" required>
                                @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                            <div class="form-group mb-3">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>
                            <div class="d-grid mx-auto">
                                <button type="submit" style="background: black; color:#bcbcbc;" class="btn btn-dark btn-block">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection