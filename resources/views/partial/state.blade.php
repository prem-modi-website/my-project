<div class="row">
        <div class="col-md-12">
            <div class="card m-t-30 m-b-20 tab_view_scan_view">
                <div class="card-header p-t-20 p-b-20">
                    <div class="row">
                        <div class="col-md-6 my-auto">
                            <h4 class="m-0">Scan View</h4>
                        </div>
                        <div class="col-md-6 text-right my-auto">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                @if (request()->has("today"))
                                    <a href="{{route('Dashboard')}}?today={{date('d/m/Y', strtotime('today'))}}"><button type="button" class="btn btn-white shadow-none total_scan_btn active">Today Scans</button></a>
                                @else
                                <a href="{{route('Dashboard')}}?today={{date('d/m/Y', strtotime('today'))}}"><button type="button" class="btn btn-white shadow-none total_scan_btn">Today Scans</button></a>
                                @endif

                                @if (Request::url() == route('listview'))
                                    <a href="{{route('listview')}}"><button type="button" class="btn btn-white shadow-none list_view_btn active">List View</button></a>
                                @else
                                    <a href="{{route('listview')}}"><button type="button" class="btn btn-white shadow-none list_view_btn">List View</button></a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
