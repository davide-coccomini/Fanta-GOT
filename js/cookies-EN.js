$(
    function () {
        var ck = false;
        if ( document.cookie && document.cookie.match(/cookie=1/) ) {
            ck = true;
        }
         
        if ( ! ck ) {
            $("body").append(
                "<div class='cookie-banner'>\
                 <p><strong>This site uses <br>technician cookies.</strong><br> \
                   Using the site you accept <br>our cookie policy. \
                <a href='/cookies'  id='cookies-link' data-toggle='modal'>More info</a>\
                </p>\
                <a href='#!' id='accept-cookies' class='accept'>OK</a></div>"
            );
             
             
          
             
            $("#accept-cookies").click(
                function (e) {
                    $(".cookie-banner").remove();
                    document.cookie = [
                        encodeURIComponent('cookie'), '=1',
                        '; expires=Sat, 31 Dec 2050 00:00:00 UTC',
                        '; path=/'
                    ].join('');
                }
            );
        }
    }
);
