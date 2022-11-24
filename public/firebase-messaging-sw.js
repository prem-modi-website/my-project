
// function updateData() {
   /*
Give the service worker access to Firebase Messaging.
Note that you can only use Firebase Messaging here, other Firebase libraries are not available in the service worker.
*/
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js');
   
/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
* New configuration for app@pulseservice.com
*/
console.log('Fire base Messageing JS File Is Called.....');

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
        console.log(
            "[firebase-messaging-sw.js] Received background message ",
            payload,
        );

        /* Customize notification here */
        const notificationTitle = "Background Message Title";

        
        // var NewData = Object.values(message)

        /*   if (NewData[0] === 'Success') {
                console.log('Success Fully MSG Received');
        } */

        const notificationOptions = {
            body: "Background Message body.",
            icon: "/itwonders-web-logo.png",
        };

      
        return self.registration.showNotification(
            notificationTitle,
            notificationOptions,
        );
        
    }); 
    
// }
