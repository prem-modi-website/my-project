<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>

<script>
    $(document).ready(function(){
        var firebaseConfig = {
            databaseURL: "https://blognewone.firebaseio.com",
            apiKey: config('constants.firebaseConstant.apiKey'),
            authDomain: config('constants.firebaseConstant.authDomain'),
            projectId: config('constants.firebaseConstant.projectId'),
            storageBucket: config('constants.firebaseConstant.storageBucket'),
            messagingSenderId: config('constants.firebaseConstant.messagingSenderId'),
            appId: config('constants.firebaseConstant.appId'),
            measurementId: config('constants.firebaseConstant.measurementId')
        };
            
        firebase.initializeApp(firebaseConfig);
         const messaging = firebase.messaging();

   
    $(document).ready(function(){
                    
        messaging
            .requestPermission()
            .then(function () {
                return messaging.getToken();
            })
            .then(function (token) {
                console.log(token);

                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });

                $.ajax({
                    url: '{{ route("save-token") }}',
                    type: "POST",
                    data: {
                        token: token,
                    },
                    dataType: "JSON",
                    success: function (response) {
                        if(response.success == true)
                        {
                            console.log("Token saved successfully.");   

                        }
                        else
                        {
                            alert("token Not saved");
                        }
                    },
                    error: function (err) {
                        console.log("User Chat Token Error" + err);
                    },
                });
            })
            .catch(function (err) {
                console.log("User Chat Token Error" + err);
            });
    });

    messaging.onMessage(function (payload) {
        console.log("message received");
        console.log(payload.notification.title);
        const title = payload.notification.title;
        $.notify({
            // options
            title: payload.notification.title,
            message: payload.notification.body
        }, {
            placement: {
                align: "right",
                from: "bottom"
            },
            showProgressbar: true,
            timer: 95000,
            // settings
            type: 'warning',
            template: '<div data-notify="container" class=" bootstrap-notify alert bg-dark text-white !important" role="alert">' +
            '<div class="progress" data-notify="progressbar">' +
            '<div class="progress-bar bg-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
            '</div>' +
            '<div class="media-body"><div class="font-secondary" data-notify="title" style="font-size: 18px;color: white;"> {1}</div> ' +
            '<span class="opacity-75 text-white" data-notify="message" style="font-size: 15px;color: white;">{2}</span></div>' +
            '<a href="{3}" target="{4}" data-notify="url"></a>' +
            ' <button type="button" aria-hidden="true" class="close" data-notify="dismiss"><span>x</span></button></div></div>'

        });
        
        const options = {
            body: payload.notification.body,
            icon: payload.notification.icon,
        };
        new Notification(title, options);
    });

    });
</script>