@extends('common.common')

@section('css')
<style>
  .error
  {
    color:red;
    font-size:15px;
  }
</style>
@endsection
@section('content')

<div class="login_page container-fluid">
  <div class="row align-items-center justify-content-center h-100">
      <div class="col-xl-4 col-lg-5 col-md-6 col-sm-12">
          <div class="login_bg_color">
              <div class="form-image text-center">
                  <img src="assets/img/logo.png" alt="DNS logo"/>
                </div>
                <div class="form_top_content">
                  <h5>Welcome Back,</h5>
                  <p>Login to your Account</p>
                  <p>
                    @foreach(['success', 'danger'] as $key)
                      @if(Session::has($key))
                        <div class="alert alert-{{ $key }} alert-block">
                          <button type="button" class="close" data-dismiss='alert'>x</button>
                          {{ Session::get($key) }}
                        </div>
                      @endif
                    @endforeach
                  </p>  
                </div>
              <form  id="loginForm" method="post" action="{{route('authenticate')}}">
                  @csrf
                  <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="text" id="email" class="form-control" name="email" placeholder="Email" />
                    @error('email')
                        <span style="color:red">{{$message}}</span>
                    @enderror 
                  </div>
                  <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input type="password" id="password" class="form-control" name="password" placeholder="Password">
                    @error('password')
                        <span style="color:red">{{$message}}</span>
                    @enderror                                 
                  </div>
                  <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
</div>

@endsection
@section('script')
<script src="{{asset('assets/js/jquery/jquery.validate.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $("#loginForm").validate({
          onfocusout: function (element) {
              $(element).valid();
          },
            rules: {
              email: {
                    required:true
                    },
                    password: "required"
            },
            messages: {
                email: "First is required.",
                password: "Last is required."
            },
            submitHandler: function(form) {
            if ($("#loginForm").valid()) {
                form.submit();
            }
            return false;

            }
        });
    });
</script>
@endsection



