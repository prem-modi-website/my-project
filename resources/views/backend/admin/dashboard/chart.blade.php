@extends('common.common')

@section('content')
<div class="bg-dark">
    <div class="container-fluid m-b-30">
        <div class="row p-b-60 p-t-60">
            <div class="col-md-6 text-white">
                <div class="media">
                    <div class="media-body">
                        <h1 class="text-white"><a href="javascript:void(0)" class="breadcrumb-text active">{{$partner_id}}</a></h1>
                        <p class="opacity-75 text-white"><a href="{{ route('Dashboard') }}" class="breadcrumb-text">Scan-Records</a></p>
                    </div>
                </div>
            </div>
            <div class="col-md-5 m-b-30 ml-auto">
                <div class="row align-items-end justify-content-center">
                    <div class="col-lg-9 col-md-12 col-sm-12">
                        <div class="row">
                            <div class="col-6">
                                <!-- <label  class="text-white">Select From Date</label> -->
                                <input type="text" class="scan_record_from_datepicker form-control" name="from" data-date="" id="initial_date" value="{{ request()->from }}" placeholder="Select a From Date" />                
                            </div>
                            <div class="col-6">
                                <!-- <label  class="text-white">Select To Date</label> -->
                                <input type="text" class="scan_record_from_datepicker form-control" name="to" id="end_date" value="{{ request()->to }}" placeholder="Select a To Date" />
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-6 mt-2 text-center">
                        <button type="button" class="btn btn-lg ml-2 mr-2 btn-light" type="submit" id="filterdate">Apply</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid pull-up graph_single_view">
    <div class="row">
        <div class="col-md-12">
            <div class="card m-t-30 m-b-20 tab_view_scan_view">
                <div class="card-header p-t-20 p-b-20">
                    <div class="row">
                        <div class="col-md-6 my-auto">
                            <h4 class="m-0">Partner ID : <Span class="text-muted">{{$partner_id}}</Span></h4>
                        </div>
                        <div class="col-md-6 text-right my-auto">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart-08" class="text-dark">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    
   $(document).ready(function(){

      var id = '{{$partner_id}}';
    //   console.log(id);

    $("#initial_date").datepicker( {
        format: "mm-yyyy",
        viewMode: "months", 
        minViewMode: "months"
    }); 
    // datepicker('setDate', "{{ Request::get('initial_date') }}")
    //                .datepicker('setStartDate', moment().subtract(11,'months').format('M-YYYY'))
    //                .datepicker('setEndDate', moment("{{ Request::get('end_date') }}", 'MMMM-YYYY').subtract(1, 'months').format('M-YYYY'));
    $("#end_date").datepicker( {
        format: "mm-yyyy",
        viewMode: "months", 
        minViewMode: "months"
    });
    

    $('#filterdate').on('click',function(e){

        // e.preventDefault();

        var initial_date = $("#initial_date").val();
        console.log(initial_date);
        // console.log(initial_date);
        // console.log(year);
        // console.log(initial_date.(\d{4}));
        
        var end_date = $("#end_date").val();
       
        // console.log(end_date);
        
        if (($("#initial_date").val() == "")  && ($("#end_date").val() == "")) 
        {
            console.log("JJJJJJJJJJJJJJJJJJJJ");
            $("#error_log").html("<span style='color:white'> You must select both (start and end) Month. </span>");
            $("#error_log").css({"background-color": "red","padding-top" : "3px","padding-bottom" : "3px","margin-top" : "4px"});
        } 
        else if($("#initial_date").val() == "")
        {
            $("#error_log").html("<span style='color:white'> You must select start Month. </span>");
            $("#error_log").css({"background-color": "red","padding-top" : "3px","padding-bottom" : "3px","margin-top" : "4px"});
        }
        else if($("#end_date").val() == "")
        {
            $("#error_log").html("<span style='color:white'> You must select end Month. </span>");
            $("#error_log").css({"background-color": "red","padding-top" : "3px","padding-bottom" : "3px","margin-top" : "4px"});
        }
        else
        {

            var month1 = parseInt(initial_date.slice(0,2));
            // console.log(month1);
            var year1 = parseInt(initial_date.slice(-4));
            console.log("month");
            console.log("" + month1 + year1);
           

            var month2 = parseInt(end_date.slice(0,2));
            var year2 = parseInt(end_date.slice(-4));
        
           
            if(year1 > year2 )
            {    
                $("#error_log").html("<span style='color:green'> From year is smaller than to year . </span>");
                $("#error_log").css({"background-color": "red","padding-top" : "3px","padding-bottom" : "3px","margin-top" : "4px"});
            }
            else if(year1 == year2)
            {    
                if (month1 > month2)
                {
                    $("#error_log").html("<span style='color:green'> From month is smaller than to month . </span>");
                    $("#error_log").css({"background-color": "red","padding-top" : "3px","padding-bottom" : "3px","margin-top" : "4px"});
                }
                else
                {
                    var url = "{{ route('chart',$partner_id) }}?to=" + end_date + "&from=" + initial_date;
                    window.location.href = url;
                }
            }
            else
            {   
                
                var url = "{{ route('chart',$partner_id) }}?to=" + end_date + "&from=" + initial_date;
                window.location.href = url;
                
            } 
            
        }
    
    })
       
        var array_month = @json(array_column($monthofscan,'month'));
        //    console.log($array_month);
        var count_for_scan = @json(array_column($monthofscan,'scan'));
        console.log(count_for_scan);

        var maximum  = @json($maximumvalue);

        //maximum = 26
        //use something like Math.round() to round up to always 100
        var maximumvalue = Math.ceil(maximum/100)*100

        console.log(maximumvalue);

        var dividevalue = (maximumvalue/50);
        console.log(maximumvalue);

        if($("#chart-08").length > 0){
                var options = {
                    chart: {
                        height: 400,
                        type: 'area',
                        toolbar: {
                            show: false
                        }
                    },
                    colors: ['#F20000', '#F3BBBC','#F3BBBC'],
                    stroke:{
                    },
                    dataLabels:{
                        enabled: false,
                    },
                series: [
                        {
                            name: "Month-wise Scans",
                            data: count_for_scan
                        }
                    ],
                    markers: {
                        size: 2
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            inverseColors: false,
                            opacityFrom: 0.7,
                            opacityTo: 0,
                            stops: [0, 90, 100]
                        },
                    },
                    xaxis: {
                        categories: array_month,
                        title: {
                            text: 'Month'
                        }
                    },
                    plotOptions: {
                            bar: {
                            columnWidth: "40%"
                            }
                        },
                    yaxis: {
                        title: {
                            text: 'Month-wise Scans'
                        },
                        tickAmount: dividevalue,
                        min: 0,
                        max: maximumvalue
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'right',
                        floating: true,
                        offsetY: -25,
                        offsetX: -5
                    }
                }
                var chart = new ApexCharts(
                    document.querySelector("#chart-08"),
                    options
                );
                chart.render();
        }   
   }); 
</script>
@endsection