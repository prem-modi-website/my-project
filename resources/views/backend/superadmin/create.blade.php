@extends('common.common')
@section('css')
    <style>
        .error
        {
            color:red
        }
    </style>
@endsection
@section('content')
<div class="bg-dark">
    <div class="container-fluid m-b-30">
        <div class="row p-b-60 p-t-60">
            <div class="col-md-6 text-white">
                <div class="media">
                    <div class="media-body">
                        <h1 class="text-white">Create User</h1>
                    </div>
                </div>
            </div>
            <div class="col-md-5 m-b-30 ml-auto">
            </div>
        </div>
    </div>
</div>
<div class="container-fluid pull-up graph_single_view create_user_main">
    <div class="row">
        <div class="col-md-12">
            <div class="card m-t-30 m-b-20 p-t-20 p-b-20">
                <div class="card-body">
                <form method="post" id="form" action="{{route('usermanagement.store')}}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="firstname">Firstname</label>
                            <input type="text" class="form-control"  value="{{ old('firstname') }}" id="firstname" name="firstname" placeholder="Firstname">
                            
                            @error('firstname')
                                <span style="color:red">{{$message}}</span>
                            @enderror 
                        </div>
                        <div class="form-group col-md-6">
                            <label for="lastname">Lastname</label>
                            <input type="text" class="form-control" value="{{ old('lastname') }}" name="lastname" id="lastname" placeholder="Lastname">
                            <span style="color:red">
                                @error('lastname')
                                    <span style="color:red">{{$message}}</span>
                                @enderror 
                            </span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputEmail4">Email</label>
                            <input type="email" class="form-control" value="{{ old('email') }}" id="email" name="email" placeholder="Email">
                            @error('email')
                                <span style="color:red">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPassword4">Password</label>
                            <input type="password" class="form-control" value="{{ old('password') }}"   name="password" placeholder="Password" id="inputPassword4">
                            @error('password')
                                <span style="color:red"> {{$message}}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6 select_roles_dropdown">
                            <label for="inputAddress">Roles</label>
                            <select class="form-control" name="role">
                                <option value="2">Admin</option>
                                <option value="3">Devloper</option>
                            </select>
                        </div>
                        <div class="form-group col-md-12 mt-3 text-center submit_btn">
                            <a href="#"><button type="submit" class="btn btn-danger">Submit</button></a>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $("#form").validate({
            rules: {
                firstname: {
                    required:true,
                    
                    },
                lastname: "required",
                email: {
                    required:true,
                    email:true
                },
                password: "required",
            },
            messages: {
                firstname: "First is required.",
                lastname: "Last is required.",
                email:
                {
                    required:"email is required",
                  email: "Email format is required.",
                },
                password: "Password is required.",
            },
            submitHandler: function(form) {
                if ($("#form").valid()) {
                    form.submit();
                }
                return false;
            }
        });
    });
</script>
@endsection