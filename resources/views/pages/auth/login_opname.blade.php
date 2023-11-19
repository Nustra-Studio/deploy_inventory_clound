@extends('layout.master2')

@section('content')
<style>
    /* .login{
      background-image: url("{{asset('assets/images/background.png')}}");
      background-size:100%;
    } */
    .form-control {
            background-color: transparent;
            border: none;
            border-bottom: 1px solid rgb(29, 29, 29); /* Add a bottom border for separation */
            color: #000; /* Change text color as needed */
            border-radius: 8px; 
        }
    .card{
      background-color: rgba(255, 255, 255, 0.904);
    }
</style>
<div class="page-content login d-flex align-items-center justify-content-center">

  <div class="row w-100 mx-0 auth-page">
    <div class="col-md-5 pt-3 mt-5 col-xl-5 mx-auto">
      <div class="card mt-5">
        <div class="row">
          <div class="col-md-2 pe-md-0">

          </div>
          <div class="col-md-8 ps-md-0">
            <div class="auth-form-wrapper  py-5">
              <a href="#" class=" text-center noble-ui-logo d-block mb-2">Cinta<span>Bunda</span></a>
              <form class="forms-sample"
                form method="POST"
                action="{{ route('opname.login') }}">
                @csrf
                <div class="mb-3">
                  <label for="userEmail" class="form-label">Username</label>
                  <input type="text" class="form-control" name="username" id="userEmail" placeholder="Username">
                </div>
                <div class="mb-3">
                  <label for="userPassword" class="form-label">Password</label>
                  <input type="password" class="form-control" name="password" id="userPassword" autocomplete="current-password" placeholder="Password">
                </div>
                <div>
                  <button type="submit" class="btn btn-primary me-2 mb-2 mb-md-0">Login</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection