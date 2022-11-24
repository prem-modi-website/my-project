@extends('common.common')

@section('content')
<div class="bg-dark">
  <div class="container-fluid m-b-30">
      <div class="row p-b-60 p-t-60">
          <div class="col-md-6 text-white">
              <div class="media">
                  <div class="media-body">
                      <h1 class="text-white">View All User</h1>
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
        @foreach($users as $user)
          <div class="col-lg-4 col-ms-6 col-sm-12">
              <div class="card m-b-30 m-t-30">
                  <div class="card-header">
                      <div class="card-title">Role : <span class="text-danger">{{$user->role->name}}</span>
                      </div>

                      <div class="card-controls">
                          <div class="dropdown">
                              <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <i class="icon mdi  mdi-dots-vertical"></i> </a>
                              <div class="dropdown-menu dropdown-menu-right">
                                  <button class="dropdown-item" type="button">Action</button>
                                  <button class="dropdown-item" type="button">Another action</button>
                                  <button class="dropdown-item" type="button">Something else here</button>
                              </div>
                          </div>
                      </div>

                  </div>
                  <div class="card-body">
                      <div class="text-center ">
                          <img src="{{asset('img/user-logo.png')}}" class="rounded-circle" width="80" alt="">
                      </div>
                      <h4 class="text-center m-t-20">
                        {{$user['first_name']." ".$user['last_name']}}
                      </h4>
                      <div class="text-muted text-center m-b-20">
                        {{$user['email']}}
                      </div>
                      <div class="text-center p-b-20 view_user_card_btn">
                          <a href="{{route('usermanagement.edit',$user->uuid)}}" class="text-white"><button class="btn btn-primary edit_button">Edit</button></a>
                          @if($user->is_active == 1)
                            <a href="{{route('deactivate',$user->uuid)}}" class="text-white"><button class="btn btn-primary deactive_btn">DeActivate</button></a>
                          @else
                            <a href="{{route('deactivate',$user->uuid)}}" class="text-white"><button class="btn btn-primary deactive_btn">Activated</button></a>
                          @endif
                      </div>
                  </div>
              </div>
          </div>
        @endforeach
    </div>
</div>
@endsection