@extends('common.common')

@section('css')
<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

<style>
  .error
  {
    color:red;
    font-size:15px;
  }
  .data-card:hover{
        box-shadow: 0px 0px 10px 2px rgb(126 124 124 / 25%) ;
    }
    label{
        display: inline-block !important;
    margin-bottom: 0.5rem !important;
    font-size: .9rem;
    font-weight: 400 !important;
    color: #111213;
    font-family: Roboto,-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji','Segoe UI Symbol';
    }
</style>
@endsection
@section('content')
<p>
    @foreach(['success', 'danger'] as $key)
        @if(Session::has($key))
        <div class="alert alert-{{ $key }} alert-block">
            <button type="button" class="close" data-dismiss='alert'>x</button>
            <p class="text-center">

                {{ Session::get($key) }}
            </p>
        </div>
        @endif
    @endforeach
    </p>  
<section class="login-main">
    <div class="container-fluid">
        <div class="row justify-content-center">
        <div class="col-lg-4 col-md-6 mb-20">
                <div class="dashboard-card">
                    <div class="card data-card" >
                        <div class="card-header ">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <div class="card-header-text">
                                        <h3 class="card-title card-header-title text-left first-card pt-10">Migration Utility</b></b></h3>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="card-body">
                            <form class="mt-50 mb-10" id="migrationForm" method="post" action="{{route('migration.store')}}">
                                  @csrf
                                    <div class="form-group mt-20">
                                        <label for="vin">Vin:</label>
                                        <input type="text" id="vin" class="form-control" name="vin" placeholder="vin" />
                                        @error('vin')
                                        {{$messages}}
                                        @enderror
                                    </div>
                                    <div class="form-group mt-25">
                                        <label for="partnerId">partnerId:</label>
                                        <input type="text" id="partnerId" class="form-control" name="partnerId" placeholder="partnerId">
                                        @error('partnerId')
                                            {{$messages}}
                                        @enderror
                                    </div>
                                    <button type="submit" id="submitButton" class="btn  create-token login-btn w-100 mt-20 btn-danger">Create</button>
                                </form>
                            
                        </div>
                    </div>
                </div>
            </div>
        
        </div>
    </div>
</section>
@endsection
@section('script')
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.js" ></script>

<script>
  $(document).ready(function(){
  

    $("#migrationForm").validate({
            rules: {
                vin: "required",
                partnerId: "required"
            },
            messages: {
                vin: "Vin is required.",
                partnerId: "partnerId is required."
            },
            submitHandler: function(form) {
                if ($("#migrationForm").valid()) {
                    $.blockUI({ css: { 
                        border: 'none', 
                        padding: '15px', 
                        backgroundColor: '#000', 
                        '-webkit-border-radius': '10px', 
                        '-moz-border-radius': '10px', 
                        opacity: .5, 
                        color: '#fff' 
                        },
                        message: 'Please Wait...',
                    }); 
                    form.submit();
                }
                return false;
            }
        });
  });
</script>

@endsection



