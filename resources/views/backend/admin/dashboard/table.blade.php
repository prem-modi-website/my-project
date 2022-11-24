@extends('common.common')
@section('content')
<div class="bg-dark">
   <div class="container-fluid m-b-30">
      <div class="row p-b-60 p-t-60">
            <div class="col-md-6 text-white">
               <div class="media">
                  <div class="media-body">
                        <!-- <h1> <span><a href="javascript:void(0)" class="breadcrumb-text active">{{--$id--}}</a></span></h1> -->
                        <h1 class="text-white"> <span>{{$id}}</span></h1>
                        @if(request()->from && request()->to)
                        <a class="btn btn-warning export-btn"  style="background: red;" href="{{route('export' , $id)}}?from={{ request()->from}}&to={{ request()->to }}"><i class="fa fa-sign-out" aria-hidden="true"></i>Export Scan Data</a>
                        @else
                        <a class="btn btn-warning export-btn" style="background: red;" href="{{route('export' , $id)}}"><i class="fa fa-download text-white ml-2" aria-hidden="true"></i> Export Scan Data</a>
                        @endif
                        <p class="opacity-75 text-white"><a href="{{ route('Dashboard') }}" class="breadcrumb-text">Scan Records / Partner ID : {{$id}}</a></p>
                  </div>
               </div>
            </div>
            <div class="col-md-5 m-b-30 ml-auto">
               <div class="row align-items-end justify-content-center">
                  <div class="col-lg-9 col-md-12 col-sm-12">
                        <div class="row">
                           <div class="col-6">
                              <!-- <label  class="text-white">Select From Date</label> -->
                              <input type="text" class="search_from_datepicker form-control" name="from" id="initial_date" value="{{ request()->from }}" placeholder="Select a From Date" />
                           </div>
                           <div class="col-6">
                              <!-- <label  class="text-white">Select To Date</label> -->
                              <input type="text" class="search_to_datepicker form-control" name="to" id="end_date" value="{{ request()->to }}" placeholder="dd-mm-yy" />
                           </div>
                        </div>
                  </div>
                  <div class="col-lg-3 col-md-6 col-sm-6 col-6 mt-2 text-center">
                        <button type="button" class="btn btn-lg ml-2 mr-2 btn-light"  type="submit" id="filter">Apply</button>
                  </div>
               </div>
            </div>
      </div>
   </div>
</div>
<div class="container-fluid pull-up">
   <div class="row">
      <div class="card-body">
            <div class="row">
               <div class="col-lg-12 col-md-12 col-sm-12 m-b-30 ">
                  <div class="card">
                     <div class="card-body">
                        <div class="table-responsive p-t-10">
                              <table id="scan_record_search_list_table" class="table" style="width:100%">
                              @if(request()->from && request()->to)
                                 <p class="small-text">Showing <b>{{count($txtresults)}}</b> Results From Partner Id : <b>{{$id}}</b> From <b>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $from_date)->format('d-m-Y')}}</b> To <b>{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $to_date)->format('d-m-Y')}}</b></p>
                              @else
                                 <p class="small-text">Showing <b>{{count($txtresults)}}</b> Results for Partner Id : <b>{{$id}}</b></p>
                              @endif
                                 <thead>
                                 <tr>
                                    <th class="small-text" scope="col">No</th>
                                    <th class="small-text" scope="col">Vin</th>
                                    <th class="small-text" scope="col">RegNo</th>
                                    <th class="small-text" scope="col">UploadDate</th>
                                    <th class="small-text" scope="col" >Dents</th>
                                    <th class="small-text" scope="col" >modifiedDate</th>
                                    <th class="small-text" scope="col" >Scan Of Count</th>
                                 </tr>
                                 </thead>
                                 <tbody>
                                    @if(count($txtresults)>0) @foreach($txtresults as $txtresult)
                                    <tr>
                                       <td lass="small-text" scope="row">{{ $loop->iteration }}</td>
                                       <td class="small-text" scope="row">{{$txtresult['originalVIN']}}</td>
                                       <td class="small-text" scope="row">{{$txtresult['regNo']}}</td>
                                       <td class="small-text" scope="row">{{$txtresult['uploadDate']}}</td>
                                       <td class="small-text" scope="row">{{$txtresult['dents']}}</td>
                                       <td class="small-text" scope="row">{{$txtresult['modifiedDate']}}</td>
                                       <td class="small-text" scope="row">{{$txtresult['count']}}</td>
                                    </tr>
                                    @endforeach @else
                                    <tr>
                                       <td colspan="5">
                                          <p>
                                          No Records found!
                                          </p>   
                                    </td>
                                    </tr>
                                    @endif
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.js" integrity="sha512-mh+AjlD3nxImTUGisMpHXW03gE6F4WdQyvuFRkjecwuWLwD2yCijw4tKA3NsEFpA1C3neiKhGXPSIGSfCYPMlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script>
   $("#initial_date").datepicker( {
      format: "dd/mm/yyyy",
   });

   $("#end_date").datepicker( {
      format: "dd/mm/yyyy",
   });
   
   $('#scan_record_search_list_table').DataTable({
      // scrollY:'50vh',
      searching: true,
      scrollCollapse: true,
      dom: 'Bfrtip',
        buttons: [
         'csv',
        ]
   }); 

   $(document).on("click", "#todaydate", function () {
        $(this).css("background-color", "red");
   });

   $(document).on("blur", "#filter", function () {
        $(this).css("background-color", "red");
   });

   $(document).on("click", "#filter", function (e) {
      e.preventDefault();

      var initial_date = $("#initial_date").val();
      console.log((initial_date));
   
      var end_date = $("#end_date").val();
      console.log(end_date);
   
      if (initial_date == "" && end_date == "") 
      {
               $("#error_log").html("<span style='color:white'> You must select both (start and end) Month. </span>");
               $("#error_log").css({"background-color": "red","padding-top" : "3px","padding-bottom" : "3px","margin-top" : "4px"});
      } 
      else
      {

         var job_start_date = initial_date.split('/');
         var  job_end_date = end_date.split('/');

         var new_start_date = new Date(job_start_date[2],job_start_date[0],job_start_date[1]);
         var new_end_date = new Date(job_end_date[2],job_end_date[0],job_end_date[1]);

         if((new_start_date) <= (new_end_date)) {
            // your code
               var partnerid = $("#partnerid").val();   
               var url = "{{ route('dynamictable', $id) }}?to=" + end_date + "&from=" + initial_date;
                     
            
                  console.log(url);
                  window.location.href = url;
         }
         else
         {
            $("#error_log").html("<span style='color:white'> From month is smaller than to month . </span>");
               $("#error_log").css({"background-color": "red","padding-top" : "3px","padding-bottom" : "3px","margin-top" : "4px"});

         }
         
      }; 

      $("#resetBtn").on("click", function (e) {
            e.preventDefault();
            var initial_date = $("#initial_date").val("");
            var end_date = $("#end_date").val("");
      });
      
   });

  
</script>
@endsection