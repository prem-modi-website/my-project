<header class="admin-header">
                <a href="#" class="sidebar-toggle" data-toggleclass="sidebar-open" data-target="body"></a>
                <nav class="ml-auto">
                    <ul class="nav align-items-center">
                        <li class="nav-item">
                            <a href="{{ url()->current() }}"><button type="button" class="btn ml-2 mr-2 btn-primary"><i class="mdi mdi-refresh mdi-16px"></i></button></a>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="btn ml-2 mr-2 btn-primary"><i class="mdi mdi-timer"></i> {{\Carbon\Carbon::now()->setTimezone('Europe/Paris')->format('d-m-Y h:i:sa')}}</button>
                        </li>
                        <li class="nav-item">
                            <!-- Theme: Switch Theme -->
                            <div class="form-check form-switch theme-switch">
                                <input class="form-check-input" type="checkbox" id="theme-switch" />
                            </div>
                        </li>
                        @if (auth()->user()->role->name == "SuperAdmin")
                            <button  type="submit" class="btn btn-primary text-light" data-toggle="tooltip" data-placement="bottom" title="Refresh Stats">                       
                                <!-- <a href="{{route('Dashboard')}}">  -->
                                <a href="{{ route('migration-create') }}"> Migrate</a>                                                      
                            </button>
                        @endif
                        <a href="{{route('settingpage')}}"><button type="button" class="btn ml-2 mr-2 btn-primary"><i class="mdi mdi-settings mdi-16px"></i></button></a>
                        <li class="nav-item">
                            <div class="dropdown">
                                <a href="#" class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-24px mdi-bell-outline"></i>
                                    <span class="notification-counter"></span>
                                </a>
                                <div class="dropdown-menu notification-container dropdown-menu-right">
                                    <div class="d-flex p-all-15 bg-white justify-content-between border-bottom">
                                        <a href="#!" class="mdi mdi-18px mdi-settings text-muted"></a> <span class="h5 m-0">Notifications</span>
                                        <a href="#!" class="mdi mdi-18px mdi-notification-clear-all text-muted"></a>
                                    </div>
                                    <div class="notification-events bg-gray-300">
                                        <div class="text-overline m-b-5">today</div>
                                        <a href="#" class="d-block m-b-10">
                                            <div class="card">
                                                <div class="card-body"><i class="mdi mdi-circle text-success"></i> All systems operational.</div>
                                            </div>
                                        </a>
                                        <a href="#" class="d-block m-b-10">
                                            <div class="card">
                                                <div class="card-body"><i class="mdi mdi-upload-multiple"></i> File upload successful.</div>
                                            </div>
                                        </a>
                                        <a href="#" class="d-block m-b-10">
                                            <div class="card">
                                                <div class="card-body"><i class="mdi mdi-cancel text-danger"></i> Your holiday has been denied</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="avatar avatar-sm avatar-online">
                                    <img src="{{asset('img/user-logo.png')}}" class="rounded-circle" alt="" />
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" active>{{auth()->user()->first_name.auth()->user()->last_name}}</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}" data-toggle="tooltip" data-placement="bottom" title="Logout"> Logout</a>
                            </div>
                        </li>
                    </ul>
                </nav>
            </header>
