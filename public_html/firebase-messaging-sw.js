importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js');

// Initialize the Firebase app in the service worker by passing in
// your app's Firebase config object.
// https://firebase.google.com/docs/web/setup#config-object
const firebaseConfig = {
        apiKey: "{{ config('app.firebase_api_key') }}",
        authDomain: "{{ config('app.firebase_auth_domain') }}",
        projectId: "{{ config('app.firebase_project_id') }}",
        storageBucket: "{{ config('app.firebase_storage_bucket') }}",
        messagingSenderId: "{{ config('app.firebase_messaging_sender_id') }}",
        appId: "{{ config('app.firebase_app_id') }}",
        measurementId: "{{ config('app.firebase_measurement_id') }}"
    };

  // Initialize Firebase
   firebase.initializeApp(firebaseConfig);
//    const analytics = getAnalytics(app);

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();



messaging.setBackgroundMessageHandler(function(payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  const {title, body} = payload.notification;
  const notificationOptions = {
      body,
  };

  return self.registration.showNotification(title, notificationOptions);
});