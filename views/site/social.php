<div id="fb-root"></div>
<script>
    window.fbAsyncInit = function() {
        FB.init({appId: 'YOUR APP ID', status: true, cookie: true,
            xfbml: true});
    };
    (function() {
        var e = document.createElement('script'); e.async = true;
        e.src = document.location.protocol +
            '//connect.facebook.net/en_US/all.js';
        document.getElementById('fb-root').appendChild(e);
    }());
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('.twitter-login').click(function(){
            $.ajax({
                type: "POST",
                url: "/ajax/getTwitterLoginUrl/",
                dataType: "json",
                success: function (data) {
                    if (data.url != null) {
                        var newWin = window.open(data.url,
                            "Twitter",
                            "width=600,height=400,resizable=yes,scrollbars=yes,status=yes"
                        )
                        newWin.focus();
                    }
                }
            })
        });

        $('.google-login').click(function(){
            $.ajax({
                type: "POST",
                url: "/ajax/getGoogleLoginUrl/",
                dataType: "json",
                success: function (data) {
                    if (data.url != null) {
                        var newWin = window.open(data.url,
                            "Google",
                            "width=600,height=400,resizable=yes,scrollbars=yes,status=yes"
                        )
                        newWin.focus();
                    }
                }
            })
        });

        $('.facebook-login').click(function(){
            FB.login(facebook_auth, {scope: 'email'});
        });

        $('.vkontakte-login').click(function(){
            VK.Auth.login(authInfo);
        });
    });
</script>
