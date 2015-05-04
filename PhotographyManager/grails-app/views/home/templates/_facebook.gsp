<script type="text/javascript">
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '366406530233802',
            xfbml      : true,
            version    : 'v2.3'
        });
    };

    $(function(){
        console.log("Checking FB status");

        FB.getLoginStatus(function handleLogin(response){
            console.log("Handling the change");
            if (response.status === "connected") {
                // the user is logged in and has authenticated your
                // app, and response.authResponse supplies
                // the user's ID, a valid access token, a signed
                // request, and the time the access token
                // and signed request each expire
                var uid 		= response.authResponse.userID;
                var accessToken	= response.authResponse.accessToken;
                console.log("Connected uid: " + uid + " accessToken: " + accessToken);
                window.location = "/PhotographyManager/home/login?token=" + accessToken + "&current=" + window.location;
            }
            else if (response.status === "not_authorized") {
                console.log("The user is logged in to Facebook but has not authenticated your app");
                login();
            }
            else {
                console.log("The user isn't logged in to Facebook");
                login();
            }
        });
        FB.Event.subscribe("auth.statusChange", handleLogin);

        function handleLogin(response){
            console.log("Handling the change");
            if (response.status === "connected") {
                // the user is logged in and has authenticated your
                // app, and response.authResponse supplies
                // the user's ID, a valid access token, a signed
                // request, and the time the access token
                // and signed request each expire
                var uid 		= response.authResponse.userID;
                var accessToken	= response.authResponse.accessToken;
                console.log("Connected uid: " + uid + " accessToken: " + accessToken);
                window.location = "/PhotographyManager/home/login?token=" + accessToken + "&current=" + window.location;
            }
            else if (response.status === "not_authorized") {
                console.log("The user is logged in to Facebook but has not authenticated your app");
                login();
            }
            else {
                console.log("The user isn't logged in to Facebook");
                login();
            }
        }

        function login(){
            FB.login(function(response) {
                if (response.authResponse) {
                    console.log('Welcome!  Fetching your information.... ');
                    FB.api('/me', function(response) {
                        console.log('Good to see you, ' + response.name + '.');
                    });
                } else {
                    console.log('User cancelled login or did not fully authorize.');
                }
            });
        }
    });

    /*(function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));*/
</script>
<script type="text/javascript" src="//connect.facebook.net/en_US/sdk.js" id="facebook-jssdk"></script>