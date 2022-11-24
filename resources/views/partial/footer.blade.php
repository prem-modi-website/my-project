@if(auth()->user())
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="copyright-text">
                    <p class="small-text text-muted " style="padding-left:240px;padding-top:20px">Copyright ©
                        <script>  document.write(new Date().getFullYear());  </script>. All Rights Reserved By
                        <span><a href="#" class="text-danger">DriveNscan</a></span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="{{asset('assets/js/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery-ui/jquery-ui.min.js')}}"></script>
    <script src="{{asset('assets/js/popper/popper.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery-scrollbar/jquery.scrollbar.min.js')}}"></script>
    <script src="{{asset('assets/js/select2/js/select2.js')}}"></script>
    <script src="{{asset('assets/js/hci.js')}}"></script>
    <script src="{{asset('assets/js/DataTables/datatables.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.32.0/apexcharts.min.js" ></script>
    <script src="{{asset('assets/bootstrap-notify/bootstrap-notify.min.js')}}"   ></script>


    @include('partial.firebase')
   
    <!--Page Specific JS-->
    <script>
        $(document).ready(function (e) {
            $(".partner_id_dropdown").select2();
            //jQueryUI Slider
            $(".input-slider").slider({
                range: "min",
            });
        });
    </script>
@else
    <script src="{{asset('assets/js/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery-ui/jquery-ui.min.js')}}"></script>
    <script src="{{asset('assets/js/popper/popper.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap/js/bootstrap.min.js')}}"></script>
    <!-- <script src="{{asset('assets/js/jquery-scrollbar/jquery.scrollbar.min.js')}}"></script> -->
    <script src="{{asset('assets/js/select2/js/select2.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.32.0/apexcharts.min.js" ></script>

    <script src="{{asset('assets/js/hci.js')}}"></script>
@endif