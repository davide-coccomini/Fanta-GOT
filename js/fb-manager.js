window.fbAsyncInit = function() {
    FB.init({
        appId: '290339165008547',
        cookie: true,
        xfbml: true,
        version: 'v2.10'
    });
    FB.AppEvents.logPageView();
    FB.getLoginStatus(function(response) {});
};

function accedi() {
    FB.login(function(response) {
        if (response.status == "connected") {
            FB.api('/me?fields=id,email,name', function(data) {
                FacebookManager.login(data);
            });
        } else {}
    }, {
        scope: 'email, public_profile'
    });
}
var FacebookManager = new function() {
    "use strict";
    return {
        init: function() {},
        login: function(response) {
            $.ajax({
                url: "/php/login-fb-process.php",
                method: "POST",
                data: response,
                dataType: 'json',
                success: function(data, stato) {
                    location.href = "/index/" + data.p + "/" + data.sec;
                },
                error: function(richiesta, stato, errori) {}
            });
        },
    }
}();
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));