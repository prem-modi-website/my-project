@extends('common.common')
@section('css')
    <link rel="stylesheet" href="https://phpcoder.tech/multiselect/css/jquery.multiselect.css">
    <style>
        .error
        {
            color:red;
        }
    </style>
@endsection
@section('content')
<div class="bg-dark">
    <div class="container-fluid m-b-30">
        <div class="row p-b-60 p-t-60">
            <div class="col-md-6 ">
                <div class="media">
                    <div class="media-body">
                        <h1 class="text-white">Dashboard</h1>
                        
                    </div>
                </div>
            </div>
            <div class="col-md-5 text-center m-b-30 ml-auto">
                <div class="row">
                    <div class="col-lg-8 col-md-12 col-sm-12">
                        <div class="form-dark">
                            <div class="form-group partner_id_dropdown_main">
                                <form id="form">
                                <select class="form-control partner_id_dropdown" id="partnerid">
                                    <option>Select partner ID</option>
                                    @foreach($partner_datas as $data)
                                        @if(request()->has('partnerId'))
                                                @if($data['is_active'] == 1)
                                                <option disabled selected>{{$data['partner_id']}}</option>
                                                @endif
                                                @if($data['is_active'] == 0)
                                                    <option>{{$data['partner_id']}}</option>
                                                @endif
                                        @else
                                                <option>{{$data['partner_id']}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <p id="error_log"></p>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12">
                        <button type="button" id="filterpartnerid" class="btn btn-lg ml-2 mr-2 btn-light">Apply</button>
                    </div>
                    <!-- <div class="col-lg-3 col-md-12 col-sm-12">
                        <button type="button" id="clear" class="btn btn-lg ml-2 mr-2 btn-light">Clear</button>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid pull-up">
    @include('partial.state')

    @if (request()->partnerId)
        <p>Results For PartnerId Is <b>{{request()->partnerId}}</b></p>
    @endif
    <div class="row partner_card_view"> 
        <div class="card-body">
            <div class="row">
            @if(request()->partnerId)
                @foreach($partner_datas as $data)
                    @if($data['is_active'] == 1)
                        <div class="col-lg-4 col-md-12 col-sm-12 m-b-30 d-flex align-items-stretch">
                            <div class="card p-b-30">
                                <div class="card-header">
                                    <h5 class="card-title m-b-0">Scanner ID: </b>{{ $data['partner_id']  }}</b></h5>
                                    <div class="card-controls">
                                        <div class="dropdown">
                                            <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="icon mdi mdi-dots-vertical"></i> </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="{{ route('chart' , $data['partner_id'])}}"><button class="dropdown-item" type="button"><i class="mdi mdi-chart-bar m-r-10"></i>Chart View</button></a>
                                                <a href="{{ route('dynamictable' ,$data['partner_id'])}}"><button class="dropdown-item" type="button"><i class="mdi mdi-table-large m-r-10"></i>Table View</button></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="pi-chart-main">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-6 p-0">
                                                <div class="pi-chart-title-main">
                                                    <ul class="pi-chart-ul-main">
                                                        <!-- <li><div class="pi-span-one color-span"></div></li> -->
                                                        <!-- <li class="ml-10"><p class="small-text">Total No Of Scans Today</p></li> -->
                                                    </ul>
                                                    <div class="pi-chart">
                                                        <div id="chart_{{ $data['partner_id']  }}" class="chart-card"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-6 p-0">
                                                <div class="pi-chart-title-main">
                                                    <ul class="pi-chart-ul-main">
                                                        <!-- <li><div class="pi-span-two color-span"></div></li> -->
                                                        <!-- <li class="ml-10"><p class="small-text">Total No Of Scans Today</p></li> -->
                                                    </ul>
                                                    <div class="pi-chart">
                                                        <div id="chart1_{{ $data['partner_id']  }}" class="chart-card"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-6 p-0">
                                                <div class="pi-chart-title-main">
                                                    <ul class="pi-chart-ul-main">
                                                        <!-- <li><div class="pi-span-three color-span"></div></li> -->
                                                        <!-- <li class="ml-10"><p class="small-text">Total No Of Scans Today</p></li> -->
                                                    </ul>
                                                    <div class="pi-chart">
                                                        <div id="chart2_{{ $data['partner_id']  }}" class="chart-card"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-6 p-0">
                                                <div class="pi-chart-title-main">
                                                    <ul class="pi-chart-ul-main">
                                                        <!-- <li><div class="pi-span-four color-span"></div></li> -->
                                                        <!-- <li class="ml-10"><p class="small-text">Total No Of Scans Today</p></li> -->
                                                    </ul>
                                                    <div class="pi-chart">
                                                        <div id="chart3_{{ $data['partner_id']  }}" class="chart-card"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-sm-12 my-auto ">
                                            <h5 class="m-0">Total Sales Report  <a href="#" class="mdi mdi-information text-muted" data-toggle="tooltip" data-placement="top" title="" data-original-title="Tooltip on top"></a></h5>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="row mt-3 align-items-center">
                                                <div class="col-6 pr-0">
                                                    <p class="text-muted p-0">
                                                        Remaining Scans
                                                    </p>
                                                </div>
                                                <div class="col-6 my-auto text-right">
                                                    <span class="badge badge-dark ">{{$data['total_remaining_scans']}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="row mt-3 align-items-center">
                                            <div class="col-6 pr-0">
                                                    <p class="text-muted">
                                                        Total Scans
                                                    </p>
                                                </div>
                                                <div class="col-6 my-auto text-right">
                                                    <span class="badge badge-dark ">{{$data['total_scan']}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-t-15 m-b-10 overview_slider">
                                        <label>{{ $data['total_used_scan'] }}</label>
                                        <div id="inputslider_{{ $data['partner_id']  }}" min="0" max="3000"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                @if(isset($partner_datas))
                    @if(!$partner_datas)
                        <p>Data Not Found</p>
                    @else
                        @foreach($partner_datas as $data)
                            <div class="col-lg-4 col-md-12 col-sm-12 m-b-30 d-flex align-items-stretch">
                                <div class="card p-b-30">
                                    <div class="card-header">
                                        <h5 class="card-title m-b-0">Scanner ID: </b>{{ $data['partner_id']  }}</b></h5>
                                        <div class="card-controls">
                                            <div class="dropdown">
                                                <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="icon mdi mdi-dots-vertical"></i> </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="{{ route('chart' , $data['partner_id'])}}"><button class="dropdown-item" type="button"><i class="mdi mdi-chart-bar m-r-10"></i>Chart View</button></a>
                                                    <a href="{{ route('dynamictable' , $data['partner_id'])}}"><button class="dropdown-item" type="button"><i class="mdi mdi-table-large m-r-10"></i>Table View</button></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="pi-chart-main">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-6 p-0">
                                                    <div class="pi-chart-title-main">
                                                        <ul class="pi-chart-ul-main">
                                                            <!-- <li><div class="pi-span-one color-span"></div></li> -->
                                                            <!-- <li class="ml-10"><p class="small-text">Total No Of Scans Today</p></li> -->
                                                        </ul>
                                                        <div class="pi-chart">
                                                            <div id="chart_{{ $data['partner_id']  }}" class="chart-card"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-6 p-0">
                                                    <div class="pi-chart-title-main">
                                                        <ul class="pi-chart-ul-main">
                                                            <!-- <li><div class="pi-span-two color-span"></div></li> -->
                                                            <!-- <li class="ml-10"><p class="small-text">Total No Of Scans Today</p></li> -->
                                                        </ul>
                                                        <div class="pi-chart">
                                                            <div id="chart1_{{ $data['partner_id']  }}" class="chart-card"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-6 p-0">
                                                    <div class="pi-chart-title-main">
                                                        <ul class="pi-chart-ul-main">
                                                            <!-- <li><div class="pi-span-three color-span"></div></li> -->
                                                            <!-- <li class="ml-10"><p class="small-text">Total No Of Scans Today</p></li> -->
                                                        </ul>
                                                        <div class="pi-chart">
                                                            <div id="chart2_{{ $data['partner_id']  }}" class="chart-card"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-6 p-0">
                                                    <div class="pi-chart-title-main">
                                                        <ul class="pi-chart-ul-main">
                                                            <!-- <li><div class="pi-span-four color-span"></div></li> -->
                                                            <!-- <li class="ml-10"><p class="small-text">Total No Of Scans Today</p></li> -->
                                                        </ul>
                                                        <div class="pi-chart">
                                                            <div id="chart3_{{ $data['partner_id']  }}" class="chart-card"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-sm-12 my-auto ">
                                                <h5 class="m-0">Total Sales Report  <a href="#" class="mdi mdi-information text-muted" data-toggle="tooltip" data-placement="top" title="" data-original-title="Tooltip on top"></a></h5>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="row mt-3 align-items-center">
                                                    <div class="col-6 pr-0">
                                                        <p class="text-muted p-0">
                                                            Remaining Scans
                                                        </p>
                                                    </div>
                                                    <div class="col-6 my-auto text-right">
                                                        <span class="badge badge-dark ">{{$data['total_remaining_scans']}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="row mt-3 align-items-center">
                                                <div class="col-6 pr-0">
                                                        <p class="text-muted">
                                                            Total Scans
                                                        </p>
                                                    </div>
                                                    <div class="col-6 my-auto text-right">
                                                        <span class="badge badge-dark ">{{$data['total_scan']}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-t-15 m-b-10 overview_slider">
                                            <label>{{ $data['total_used_scan'] }}</label>
                                            <div id="inputslider_{{ $data['partner_id']  }}" min="0" max="3000"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                @endif
            
            @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://phpcoder.tech/multiselect/js/jquery.multiselect.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
<script>
    $(document).ready(function (e) {
          
        $('#languageSelect').multiselect({
            columns: 1,
            placeholder: 'Select Partner Id',
        });

        $('#clear').on('click',function(){
            var url = "{{ route('Dashboard')}}";
            console.log(url);

            window.location.href = url;
        });

        $('#filterpartnerid').on('click',function(){

            var partnerId = $('#partnerid').val();
            console.log('Ready >>> ');
            console.log(partnerId)
            
            if (partnerId)
            {
                if(partnerId == "Select partner ID")
                {
                    $("#error_log").html("<span style='color:white'> please enter the partner Id . </span>");
                    $("#error_log").css({"background-color": "red","padding-top" : "3px","padding-bottom" : "3px","margin-top" : "4px"});
                }
                else
                {
                    var partner_id = $.trim(partnerId);
                    console.log(partner_id);
                    console.log("yes");
                    var url = "{{route('Dashboard')}}?partnerId="+partnerId;
                    console.log(url);
                    // exit;
                    window.location.href = url;
                }
            }
            else
            {
                console.log("cj");
                // exit;
                $("#error_log").html("<span style='color:white'> please enter the partner Id . </span>");
                $("#error_log").css({"background-color": "red","padding-top" : "3px","padding-bottom" : "3px","margin-top" : "4px"});
            }
        });

        $(".partner_id_dropdown").select2();
        //jQueryUI Slider
        // $(".input-slider").slider({
        //     value:50       
        // });

        var array_month = @json($partner_datas);

      
        console.log(users);

        if(array_month)
        {
            jQuery.each( array_month, function( i, val ) {
                var todayscan =  val['total_no_of_scans_this_today'];
                var weekscan =  val['total_no_of_scans_this_week'];
                var monthscan =  val['total_no_of_scans_this_month'];
                var totalusedscan =  val['total_used_scan'];
            
                var todayscanid =  val['partner_id'];
                var makeid = '#chart_'+todayscanid+'';
                var makeidweek = '#chart1_'+todayscanid+'';
                var makeidmonth = '#chart2_'+todayscanid+'';
                var usedscan = '#chart3_'+todayscanid+'';
        
                var todayusedscan = '#inputslider_'+todayscanid+'';

                $(todayusedscan).slider({
                    value:totalusedscan,
                    min:0,
                    max:300,
                    disabled : true,
                    gradientToColors: ["#f20000"]
                });

                if ($(makeid).length) {
                    // console.log(makeid);
                    var options = {
                        colors: ['#f20000'],
                        chart: {
                            height: 350,
                            type: 'radialBar',
                        },
                        stroke: {
                            lineCap: "round",
                        },
                        legend: {
                            show: true,
                            lineCap: "square",
                            showForSingleSeries: false,
                            showForNullSeries: true,
                            showForZeroSeries: true,
                            position: 'top',
                            horizontalAlign: 'center', 
                        },
                        fill: {
                            type: "gradient",
                            gradient: {
                            shade: "dark",
                            type: "vertical",
                            gradientToColors: ["#f20000"],
                            stops: [0, 100]
                            }
                        },
                        plotOptions: {
                            radialBar: {
                                dataLabels: {
                                    name: {
                                        fontSize: '22px',
                                    },
                                    value: {
                                        fontSize: '16px',
                                    },
                                    total: {
                                        show: true,
                                        formatter: function (w) {
                                            // By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
                                            return todayscan
                                        }
                                    }
                                }
                            }
                        },
                        series: [(todayscan/150)*100],
                        labels: ['Total Scans Today'],
                    }
                    var chart = new ApexCharts(
                        document.querySelector(makeid),
                        options
                    );
                    chart.render();
                }
        
                if ($(makeidweek).length) {
                    // console.log(makeid);
                    var options = {
                        colors: ['#000000'],
                        chart: {
                            height: 350,
                            type: 'radialBar',
                        },
                        stroke: {
                            lineCap: "round",
                        },
                        legend: {
                            show: true,
                            showForSingleSeries: false,
                            showForNullSeries: true,
                            showForZeroSeries: true,
                            position: 'top',
                            horizontalAlign: 'center', 
                        },
                        fill: {
                            type: "gradient",
                            gradient: {
                            shade: "dark",
                            type: "vertical",
                            gradientToColors: ["#000001"],
                            stops: [0, 100]
                            }
                        },
                        plotOptions: {
                            radialBar: {
                                dataLabels: {
                                    name: {
                                        fontSize: '22px',
                                    },
                                    value: {
                                        fontSize: '16px',
                                    },
                                    total: {
                                        show: true,
                                        formatter: function (w) {
                                            // By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
                                            return weekscan
                                        }
                                    }
                                }
                            }
                        },
                        series: [(weekscan/750)*100],
                        labels: ['Total Scans This Week'],
                    }
                    var chart = new ApexCharts(
                        document.querySelector(makeidweek),
                        options
                    );
                    chart.render();
                }
        
                if ($(makeidmonth).length) {
                    // console.log(makeid);
                    var options = {
                        colors: ['#808080'],
                        chart: {
                            height: 350,
                            type: 'radialBar',
                        },
                        stroke: {
                            lineCap: "round",
                        },
                        legend: {
                            show: true,
                            showForSingleSeries: false,
                            showForNullSeries: true,
                            showForZeroSeries: true,
                            position: 'top',
                            horizontalAlign: 'center', 
                        },
                        fill: {
                            type: "gradient",
                            gradient: {
                            shade: "dark",
                            type: "vertical",
                            gradientToColors: ["#808080"],
                            stops: [0, 100]
                            }
                        },
                        plotOptions: {
                            radialBar: {
                                dataLabels: {
                                    name: {
                                        fontSize: '22px',
                                    },
                                    value: {
                                        fontSize: '16px',
                                    },
                                    total: {
                                        show: true,
                                        formatter: function (w) {
                                            // By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
                                            return monthscan
                                        }
                                    }
                                }
                            }
                        },
                        series: [(monthscan/3000)*100],
                        labels: ['Total Scans This Month'],
                    }
                    var chart = new ApexCharts(
                        document.querySelector(makeidmonth),
                        options
                    );
                    chart.render();
                }
        
                if ($(usedscan).length) {
                    // console.log(makeid);
                    var options = {
                        colors: ['#800000'],
                        chart: {
                            height: 350,
                            type: 'radialBar',
                        },
                        stroke: {
                            lineCap: "round",
                        },
                        fill: {
                            type: "gradient",
                            gradient: {
                            shade: "dark",
                            type: "vertical",
                            gradientToColors: ["#F20000"],
                            stops: [0, 100]
                            }
                        },
                        legend: {
                            show: true,
                            showForSingleSeries: false,
                            showForNullSeries: true,
                            showForZeroSeries: true,
                            position: 'top',
                            horizontalAlign: 'center', 
                        },
                        plotOptions: {
                            radialBar: {
                                dataLabels: {
                                    name: {
                                        fontSize: '22px',
                                    },
                                    value: {
                                        fontSize: '16px',
                                    },
                                    total: {
                                        show: true,
                                        formatter: function (w) {
                                            // By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
                                            return totalusedscan
                                        }
                                    }
                                }
                            }
                        },
                        series: [(totalusedscan/6000)*100],
                        labels: ['Total Scans Lifetime'],
                    }
                    var chart = new ApexCharts(
                        document.querySelector(usedscan),
                        options
                    );
                    chart.render();
                }
             
            });
        }
        else
        {
            console.log("no");
        }
        
        var firebaseConfig = {
            databaseURL: "https://blognewone.firebaseio.com",
            apiKey: "AIzaSyDd2RGcI0ECUqAAZ4t_Z_ALjsqwlu6RHoE",
            authDomain: "firstdemoproject-ae91a.firebaseapp.com",
            projectId: "firstdemoproject-ae91a",
            storageBucket: "firstdemoproject-ae91a.appspot.com",
            messagingSenderId: "709941260641",
            appId: "1:709941260641:web:0e3065081686f9f3f81520",
            measurementId: "G-155NJ5MHBC"
        };
            
        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        var users = @json(auth()->user()->id);

        if (array_month) {
            jQuery.each( array_month, function( i, val ) {
                var licno =  val['partner_id'];

                // function initFirebaseMessagingRegistration() {
                    messaging
                        .requestPermission()
                        .then(function () {
                            console.log(messaging.getToken());
                            return messaging.getToken()
                        })
                        .then(function(token) {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                url: '{{ route("getToken") }}?licno='+licno,
                                type: 'POST',
                                data: {
                                    token: token,
                                    users: users
                                },
                                dataType: 'JSON',
                                success: function (response) {
                                    console.log('Token Successfully Saved');
                                },
                                error: function (err) {
                                    console.log('User Device Token Save Error.'+ err);
                                },
                            });
                
                        }).catch(function (err) {
                            console.log('User Device Token Save Error.'+ err);
                        });
                //  } 

                
                

                messaging.onMessage(function(payload) {

                    const partneId = payload.notification.title;
                    
                    updateData(partneId);

                    new Notification(partneId);
                });
            });
        }
        
    });
   
        function updateData(partneId) {
            $.ajax({
                url : "{{ route('UpdateData') }}?parnerId="+partneId,
                type: 'POST',
                dataType: 'JSON',
                success: function(data) {
                    console.log('data');
                    console.log(data);

                    if (data.partner_datas.length) {
                        $.each(data.partner_datas, function(key,val) {
                            var partnerid = "chart_"+val.partner_id;
                            var todayscan =  val['total_no_of_scans_this_today'];
                            var weekscan =  val['total_no_of_scans_this_week'];
                            var monthscan =  val['total_no_of_scans_this_month'];
                            var totalusedscan =  val['total_used_scan'];
                            
                            var totalremainingscans  = val['total_remaining_scans'];
                            var totalscans = val['total_scan'];

                            var todayscanid =  val['partner_id'];
                            var makeid = '#chart_'+todayscanid+'';
                            var makeidweek = '#chart1_'+todayscanid+'';
                            var makeidmonth = '#chart2_'+todayscanid+'';
                            var usedscan = '#chart3_'+todayscanid+'';

                            var todayusedscan = '#inputslider_'+todayscanid+'';
                            // var todayremainscan = '#inputslider1_'+todayscanid+'';
                            
                            
                            $(todayusedscan).slider({
                                value:totalusedscan,
                                min:0,
                                max:300,
                                disabled : true,
                                gradientToColors: ["#f20000"]
                            });

                            if ($(makeid).length) {
                                // console.log(makeid);
                                var options = {
                                    colors: ['#f20000'],
                                    chart: {
                                        height: 350,
                                        type: 'radialBar',
                                    },
                                    stroke: {
                                        lineCap: "round",
                                    },
                                    legend: {
                                        show: true,
                                        lineCap: "square",
                                        showForSingleSeries: false,
                                        showForNullSeries: true,
                                        showForZeroSeries: true,
                                        position: 'top',
                                        horizontalAlign: 'center', 
                                    },
                                    fill: {
                                        type: "gradient",
                                        gradient: {
                                        shade: "dark",
                                        type: "vertical",
                                        gradientToColors: ["#f20000"],
                                        stops: [0, 100]
                                        }
                                    },
                                    plotOptions: {
                                        radialBar: {
                                            dataLabels: {
                                                name: {
                                                    fontSize: '22px',
                                                },
                                                value: {
                                                    fontSize: '16px',
                                                },
                                                total: {
                                                    show: true,
                                                    formatter: function (w) {
                                                        // By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
                                                        return todayscan
                                                    }
                                                }
                                            }
                                        }
                                    },
                                    series: [(todayscan/150)*100],
                                    labels: ['Total Scans Today'],
                                }
                                var chart = new ApexCharts(
                                    document.querySelector(makeid),
                                    options
                                );
                                chart.render();
                            }
                    
                            if ($(makeidweek).length) {
                                // console.log(makeid);
                                var options = {
                                    colors: ['#000000'],
                                    chart: {
                                        height: 350,
                                        type: 'radialBar',
                                    },
                                    stroke: {
                                        lineCap: "round",
                                    },
                                    legend: {
                                        show: true,
                                        showForSingleSeries: false,
                                        showForNullSeries: true,
                                        showForZeroSeries: true,
                                        position: 'top',
                                        horizontalAlign: 'center', 
                                    },
                                    fill: {
                                        type: "gradient",
                                        gradient: {
                                        shade: "dark",
                                        type: "vertical",
                                        gradientToColors: ["#000001"],
                                        stops: [0, 100]
                                        }
                                    },
                                    plotOptions: {
                                        radialBar: {
                                            dataLabels: {
                                                name: {
                                                    fontSize: '22px',
                                                },
                                                value: {
                                                    fontSize: '16px',
                                                },
                                                total: {
                                                    show: true,
                                                    formatter: function (w) {
                                                        // By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
                                                        return weekscan
                                                    }
                                                }
                                            }
                                        }
                                    },
                                    series: [(weekscan/750)*100],
                                    labels: ['Total Scans This Week'],
                                }
                                var chart = new ApexCharts(
                                    document.querySelector(makeidweek),
                                    options
                                );
                                chart.render();
                            }
                    
                            if ($(makeidmonth).length) {
                                // console.log(makeid);
                                var options = {
                                    colors: ['#808080'],
                                    chart: {
                                        height: 350,
                                        type: 'radialBar',
                                    },
                                    stroke: {
                                        lineCap: "round",
                                    },
                                    legend: {
                                        show: true,
                                        showForSingleSeries: false,
                                        showForNullSeries: true,
                                        showForZeroSeries: true,
                                        position: 'top',
                                        horizontalAlign: 'center', 
                                    },
                                    fill: {
                                        type: "gradient",
                                        gradient: {
                                        shade: "dark",
                                        type: "vertical",
                                        gradientToColors: ["#808080"],
                                        stops: [0, 100]
                                        }
                                    },
                                    plotOptions: {
                                        radialBar: {
                                            dataLabels: {
                                                name: {
                                                    fontSize: '22px',
                                                },
                                                value: {
                                                    fontSize: '16px',
                                                },
                                                total: {
                                                    show: true,
                                                    formatter: function (w) {
                                                        // By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
                                                        return monthscan
                                                    }
                                                }
                                            }
                                        }
                                    },
                                    series: [(monthscan/3000)*100],
                                    labels: ['Total Scans This Month'],
                                }
                                var chart = new ApexCharts(
                                    document.querySelector(makeidmonth),
                                    options
                                );
                                chart.render();
                            }
                    
                            if ($(usedscan).length) {
                                // console.log(makeid);
                                var options = {
                                    colors: ['#800000'],
                                    chart: {
                                        height: 350,
                                        type: 'radialBar',
                                    },
                                    stroke: {
                                        lineCap: "round",
                                    },
                                    fill: {
                                        type: "gradient",
                                        gradient: {
                                        shade: "dark",
                                        type: "vertical",
                                        gradientToColors: ["#F20000"],
                                        stops: [0, 100]
                                        }
                                    },
                                    legend: {
                                        show: true,
                                        showForSingleSeries: false,
                                        showForNullSeries: true,
                                        showForZeroSeries: true,
                                        position: 'top',
                                        horizontalAlign: 'center', 
                                    },
                                    plotOptions: {
                                        radialBar: {
                                            dataLabels: {
                                                name: {
                                                    fontSize: '22px',
                                                },
                                                value: {
                                                    fontSize: '16px',
                                                },
                                                total: {
                                                    show: true,
                                                    formatter: function (w) {
                                                        // By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
                                                        return totalusedscan
                                                    }
                                                }
                                            }
                                        }
                                    },
                                    series: [(totalusedscan/6000)*100],
                                    labels: ['Total Scans Lifetime'],
                                }
                                var chart = new ApexCharts(
                                    document.querySelector(usedscan),
                                    options
                                );
                                chart.render();
                            }
                            
                            $('#inputfooter1_'+val.partner_id).html(totalremainingscans);
                            $('#inputfooter2_'+val.partner_id).html(totalusedscan);
                        
                        });
                    } else {
                        console.log('Data Length Are Not Available.');
                    }
                },
                error: function(err ,xhr, textStatus) {
                    console.log('Error >>>'+ err);
                },
            });
        }
</script>

@endsection