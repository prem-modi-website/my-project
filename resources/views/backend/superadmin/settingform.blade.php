@extends('common.common')
@section('css')
<link rel="stylesheet" href="https://phpcoder.tech/multiselect/css/jquery.multiselect.css">
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
                        <h1 class="text-white">Select Partner Id</h1>
                    </div>
                </div>
            </div>
            <div class="col-md-5 m-b-30 ml-auto">
            </div>
        </div>
    </div>
</div>
<div class="container-fluid pull-up graph_single_view setiing_dropdown">
    <div class="row">
        <div class="col-md-12">
            <div class="card m-t-30 m-b-20 p-t-20 p-b-20">
                <div class="card-body">
                <form method="post" id="form" action="{{route('setting')}}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="firstname">Partner Id</label>
                            <input type="hidden" name="id" value="{{auth()->user()->id}}">
                            <select name="languageSelect[]" multiple class="form-control" id="languageSelect">
                                @foreach($partnerid as $singlepartnerid)
                                      @if($singlepartnerid['is_active'] == 1)
                                        <option value="{{$singlepartnerid['partner_id']}}" selected>{{$singlepartnerid['partner_id']}}</option>
                                      @else
                                        <option value="{{$singlepartnerid['partner_id']}}">{{$singlepartnerid['partner_id']}}</option>
                                      @endif
                                @endforeach
                            </select>
                            <p id="check"></p>
                        </div>
                        <div class="form-group col-md-12 mt-3 text-center submit_btn">
                            <a href="#"><button type="submit" id="btn" class="btn btn-danger">Submit</button></a>
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
<!-- JS & CSS library of MultiSelect plugin -->
<script src="{{asset('assets/js/jquery/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/js/select2/js/select2.js')}}"></script>

    <script>
        $(document).ready(function(){

            console.log("yes");
          
            var value = $("#languageSelect").select2().addClass("active");
            console.log($('.select2-selection__choice').attr('title'));
           
            $('form').submit(function(){
                var options = $('#languageSelect > option:selected');
                if(options.length == 0){
                    $("#check").html("<span style='color:white;text-align:center;'> Please enter the partner Id . </span>");
                    $("#check").css({"background-color": "red","padding-top" : "3px","padding-bottom" : "3px","margin-top" : "4px"});
                    return false;
                }
            });

            // $("#form").validate({
            //     onsubmit: true,
            //     rules: {
            //     languageSelect: {
            //     required: {
            //         depends: function(element) {
            //             return $("#dd1").val() == "none";
            //         }
            //     }
            //     },
            //     messages: {
            //         dd1: {
            //         required: "Please select an option from the list, if none are appropriate please select 'Other'",
            //         },
            //     }
            // });
        });
        // $(function() {
        //         jQuery('#languageSelect').multiselect({
        //             columns: 1,
        //             placeholder: 'Select Partner Id',
        //         });
        // });

      
    </script>
@endsection