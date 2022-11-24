<script>

// function updateData() {
    
    importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js');
    importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');

    console.log('Fire base Messageing JS File Is Called..BLADE FILE...');

    firebase.initializeApp({
        databaseURL: "https://blognewone.firebaseio.com",
        apiKey: config('constants.firebaseConstant.apiKey'),
        authDomain: config('constants.firebaseConstant.authDomain'),
        projectId: config('constants.firebaseConstant.projectId'),
        storageBucket: config('constants.firebaseConstant.storageBucket'),
        messagingSenderId: config('constants.firebaseConstant.messagingSenderId'),
        appId: config('constants.firebaseConstant.appId'),
        measurementId: config('constants.firebaseConstant.measurementId')
    });
  
    /*
    Retrieve an instance of Firebase Messaging so that it can handle background messages.
    */
    const messaging = firebase.messaging();
    console.log('FIREBASE_MSG_SW');

    messaging.setBackgroundMessageHandler(function(payload) {
        
        console.log( "[firebase-messaging-sw.js] Received background message ", payload );

        /* Customize notification here */
        const notificationTitle = "Background Message Title";

        
        // var NewData = Object.values(message)

        /* if (NewData[0] === 'Success') {
            console.log('Success Fully MSG Received');
        } */

        const notificationOptions = {
            body: "/notificationOptions Body/",
            icon: "/itwonders-web-logo.png/",
        };
        
        updateData();

        // return Notification(notificationOptions);
        return self.registration.showNotification(
            notificationTitle,
            notificationOptions,
        );
        
    }); 
    
// }

</script>