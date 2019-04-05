messaging.setBackgroundMessageHandler(function(payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);
  // Customize notification here
  var notificationTitle = payload.data.title;
  var notificationOptions = {
                body : payload.data.body,
                icon : payload.data.icon
            };

  return self.registration.showNotification(notificationTitle,
      notificationOptions);
});
