@extends('common.common')

@section('content')
<div class="bg-dark">
    <div class="container-fluid m-b-30">
        <div class="row p-b-60 p-t-60">
            <div class="col-md-6 text-white">
                <div class="media">
                    <div class="media-body">
                        <h1 class="text-white">List View</h1>
                    </div>
                </div>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-2">
                @if(request()->flag == "day")
                    <select class="dropdown show btn btn-danger" id="totalscan" value=`${localStorage.content}`>
                            <option value="totalscanstoday" selected>TOTAL SCANS TODAY</option>
                            <option value="totalscansweek">TOTAL SCANS THIS WEEK</option>
                            <option value="totalscansmonth">TOTAL SCANS THIS MONTH</option>
                    </select>
                @elseif(request()->flag == "week")
                    <select class="dropdown show btn btn-danger" id="totalscan" value=`${localStorage.content}`>
                            <option value="totalscanstoday">TOTAL SCANS TODAY</option>
                            <option value="totalscansweek" selected>TOTAL SCANS THIS WEEK</option>
                            <option value="totalscansmonth">TOTAL SCANS THIS MONTH</option>
                    </select>
                @elseif(request()->flag == "month")
                    <select class="dropdown show btn btn-danger" id="totalscan" value=`${localStorage.content}`>
                            <option value="totalscanstoday">TOTAL SCANS TODAY</option>
                            <option value="totalscansweek" >TOTAL SCANS THIS WEEK</option>
                            <option value="totalscansmonth" selected>TOTAL SCANS THIS MONTH</option>
                    </select>
                @else
                    <select class="dropdown show btn btn-danger" id="totalscan" value=`${localStorage.content}`>
                            <option value="totalscanstoday">TOTAL SCANS TODAY</option>
                            <option value="totalscansweek" >TOTAL SCANS THIS WEEK</option>
                            <option value="totalscansmonth">TOTAL SCANS THIS MONTH</option>
                    </select>
                @endif
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-1">
                <input type="button" class="btn btn-danger" id="btn" value="submit">
            </div>
            <div class="col-md-1">
                @if(request()->order == "asc")
                    <select class="dropdown show btn btn-danger" id="order">
                            <option>select</option>
                            <option value="asc" selected>asc</option>
                            <option value="desc">desc</option>
                    </select>
                @elseif(request()->order == "desc")
                    <select class="dropdown show btn btn-danger" id="order">
                            <option>select</option>
                            <option value="asc">asc</option>
                            <option value="desc" selected>desc</option>
                    </select>
                @else
                    <select class="dropdown show btn btn-danger" id="order">
                            <option>select</option>
                            <option value="asc">asc</option>
                            <option value="desc">desc</option>
                    </select>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="container-fluid pull-up">
    @include('partial.state')
    <div class="row">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 m-b-30 ">
                    <div class="card">

                            <div class="card-body">
                                <div class="table-responsive p-t-10">
                                    <table id="scan_record_list_table" class="table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th scope="row">No</th>
                                            <th>Partner-Id</th>
                                            <th>Total Scans Today</th>
                                            <th>Total Scans This Week</th>
                                            <th>Total Scans This Month</th>
                                            <th>Total Used Scan</th>
                                            <th>Remaining Scans</th>
                                            <th>Total Scans</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($partner_datas as $singlepartnerdata)
                                        @if(!empty($singlepartnerdata))
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$singlepartnerdata['partner_id']}}</td>
                                                <td>{{$singlepartnerdata['total_no_of_scans_this_today']}}</td>
                                                <td>{{$singlepartnerdata['total_no_of_scans_this_week']}}</td>
                                                <td>{{$singlepartnerdata['total_no_of_scans_this_month']}}</td>
                                                <td>{{$singlepartnerdata['total_used_scan']}}</td>
                                                <td>{{$singlepartnerdata['total_remaining_scans']}}</td>
                                                <td>{{$singlepartnerdata['total_scan']}}</td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                    </table>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="{{asset('assets/js/DataTables/datatables.min.js')}}"></script>
    <script>
        $(document).ready(function (e) {
            $(".partner_id_dropdown").select2();
            //jQueryUI Slider
            $(".input-slider").slider({
                range: "min",
            });
        });

        console.log("yes");
        $('#btn').on('click',function(){

            var scanurl = $('#totalscan').val();

            console.log(scanurl);

            var order = $('#order').val();
            if (scanurl)
            {
                //total scan of today
                if (scanurl == "totalscanstoday")
                {
                    var d = new Date();
                    var fromdate = d.getFullYear() + "/" + (d.getMonth()+1) + "/" + d.getDate();
                    var todate = d.getFullYear() + "/" + (d.getMonth()+1) + "/" + d.getDate();

                    if (order == "asc" || order == "desc")
                    {
                        var url = "{{ route('listview') }}?from=" + fromdate + "&to=" + todate + "&order=" +order+ "&flag=day";
                        window.location.href = url;
                        console.log(url);
                        console.log("yes");
                    }
                    else
                    {
                        var url = "{{ route('listview') }}?from=" + fromdate + "&to=" + todate+ "&flag=day";
                        window.location.href = url;
                        console.log("no");
                    }
                }
                //

                //total scan of week
                if (scanurl == "totalscansweek")
                {
                    var today = new Date();
                    var nextweek = new Date(today.getFullYear(), today.getMonth(), today.getDate()-7);
                    var todate = nextweek.getFullYear() + "/" + (nextweek.getMonth()+1) + "/" + nextweek.getDate();

                    var fromdate = today.getFullYear() + "/" + (today.getMonth()+1) + "/" + today.getDate();

                    if (order == "asc" || order == "desc")
                    {
                        var url = "{{ route('listview') }}?from=" + fromdate + "&to=" + todate + "&order=" +order+ "&flag=week";
                        window.location.href = url;
                        console.log(url);
                    }
                    else
                    {
                        var url = "{{ route('listview') }}?from=" + fromdate + "&to=" + todate + "&flag=week";
                        window.location.href = url;
                        console.log(url);
                    }
                }
                //

                //total scan of month
                if (scanurl == "totalscansmonth")
                {
                    var today = new Date();
                    var nextmonth = new Date(today.getFullYear(), today.getMonth()-1, today.getDate());
                    var todate = nextmonth.getFullYear() + "/" + (nextmonth.getMonth()+1) + "/" + nextmonth.getDate();

                    var fromdate = today.getFullYear() + "/" + (today.getMonth()+1) + "/" + today.getDate();
                    if (order == "asc" || order == "desc")
                    {
                        var url = "{{ route('listview') }}?from=" + fromdate + "&to=" + todate + "&order=" +order+ "&flag=month";
                        window.location.href = url;
                        console.log(url);
                    }
                    else
                    {
                        var url = "{{ route('listview') }}?from=" + fromdate + "&to=" + todate+ "&flag=month";
                        window.location.href = url;
                        console.log(url);
                    }

                }
                //
            }
        });
    </script>
@endsection
