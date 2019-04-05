define(['jquery',
    'mage/url',
],function($, url){
    return function(config){
        //do something
       var MsgElem = $('#msg');
       var TokenElem = $('#token');
       var NotisElem = $("#notis");
       var ErrElem = $("#err");
        // Initialize Firebase
        // TODO: Replace with your project's customized code snippet
        var config = {
            apiKey: config.server_key,
            messagingSenderId: config.sender_id,
        };
        
        firebase.initializeApp(config);

        var messaging = firebase.messaging();
        messaging
            .requestPermission()
            .then(function () {
                MsgElem.innerHTML = "Notification permission granted." 
                console.log("Notification permission granted.");
                
                // get the token in the form of promise
                return messaging.getToken()
            })
            .then(function(token) {
                TokenElem.innerHTML = "token is : " + token
                $("#token").text(token);
                updateToken(token);
            })
            .catch(function (err) {
                $("#err").text(err);
                ErrElem.innerHTML =  ErrElem.innerHTML + "; " + err
                console.log("Unable to get permission to notify.", err);
            });

        messaging.onMessage(function(payload) {
            console.log("Message received. ", payload);
            NotisElem.innerHTML = NotisElem.innerHTML + JSON.stringify(payload);
            notificationTitle = payload.data.title;
            notificationOptions = {
                body : payload.data.body,
                icon : payload.data.icon
            };
            
            var notification = new Notification(notificationTitle, notificationOptions);
            
        });
    }
    
    function getBrowserType() {
       
        var browser = 'Unknown';
        
        var isSafari = /constructor/i.test(window.HTMLElement) || (function (p) {
            return p.toString() === "[object SafariRemoteNotification]";
        })(!window['safari'] || safari.pushNotification);
        
        if(isSafari) {
            browser = "Safari";
        }
        else if((!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0) {
            browser = "Opera";
        }
        else if(typeof InstallTrigger !== 'undefined') {
            browser = "FireFox";
        }
        else if( /*@cc_on!@*/false || !!document.documentMode){
            browser = "IE";
        }
        else if(!isIE && !!window.StyleMedia) {
            browser = "IE Edge";
        }
        else if(!!window.chrome && !!window.chrome.webstore) {
            browser = "Google Chrome";
        }
        else if((isChrome || isOpera) && !!window.CSS) {
            browser = "Blink";
        }
        return browser;
    }
    
    function updateToken (token) {
        var fetch_url = url.build('pushnotification/validateToken/');
        var browserName = getBrowserType();
        $.ajax({
            url: fetch_url,
            showLoader: true,
            type: "POST",
            dataType: 'json',
            data : {
                token : token,
                broserType : browserName
            }
        }).success(function (data) {
            var response = data;
            console.log(response);
        }).fail(function () {
            
        });
    };
});


