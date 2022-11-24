<aside class="admin-sidebar">
            <div class="admin-sidebar-brand">
                <!-- begin sidebar branding-->
                <img class="admin-brand-logo" src="{{asset('assets/img/logo.png')}}" width="70%" alt="hci Logo" />
                <!-- end sidebar branding-->
                <div class="ml-auto">
                    <!-- sidebar pin-->
                    <a href="#" class="admin-pin-sidebar btn-ghost btn btn-rounded-circle"></a>
                    <!-- sidebar close for mobile device-->
                    <a href="#" class="admin-close-sidebar"></a>
                </div>
            </div>
            <div class="admin-sidebar-wrapper js-scrollbar">
                <!-- Menu List Begins-->
                <ul class="menu">
                    <!--list item begins-->
                    <li class="menu-item {{ Request::is('dashboard') || Request::is('dashboard/*') ? 'opened' : '' }}">
                        <a href="{{ route('Dashboard') }}" class="menu-link">
                            <span class="menu-label">
                                <span class="menu-name">Dashboard </span>
                            </span>
                            <span class="menu-icon">
                                <i class="icon-placeholder mdi mdi-view-dashboard"></i>
                            </span>
                        </a>
                    </li>
                    <!--list item ends-->
                    <!--list item begins-->
                    @if (auth()->user()->role->name == "SuperAdmin")  
                    <li class="menu-item {{ Request::is('usermanagement') || Request::is('usermanagement/*') ? 'opened' : '' }} ">
                        <a href="#" class="open-dropdown menu-link">
                            <span class="menu-label">
                                <span class="menu-name">
                                    Users
                                    <span class="menu-arrow"></span>
                                </span>
                            </span>
                            <span class="menu-icon">
                                <i class="icon-placeholder mdi mdi-account"></i>
                            </span>
                        </a>
                        <!--submenu-->
                        <ul class="sub-menu" style="{{ (Request::is('usermanagement/*') || Request::is('usermanagement') ? 'display: block;' : 'display: none;') }}">
                            <li class="menu-item {{ (Request::url() == route('usermanagement.create') ? 'active' : '') }}">
                                <a href="{{route('usermanagement.create')}}" class="menu-link">
                                    <span class="menu-label">
                                        <span class="menu-name">Create</span>
                                    </span>
                                    <span class="menu-icon">
                                        <i class="mdi mdi-account-plus mdi-24px">
                                        </i>
                                    </span>
                                </a>
                            </li>
                            <li class="menu-item {{ (Request::url() == route('usermanagement.index') ? 'active' : '') }}">
                                <a href="{{route('usermanagement.index')}}" class="menu-link">
                                    <span class="menu-label">
                                        <span class="menu-name">Index</span>
                                    </span>
                                    <span class="menu-icon">
                                        <i class="mdi mdi-eye-outline mdi-24px"></i>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    <!--list item ends-->
                </ul>
                <!-- Menu List Ends-->
            </div>
        </aside>