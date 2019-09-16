<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Token Generation for testing</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/hellojs/1.8.2/hello.all.js"></script>
    <script>
        hello.on('auth.login', function(auth) {

            // Call user information, for the given network
            hello(auth.network).api('/me').then(function(r) {
                // Inject it into the container
                var label = document.getElementById('profile_' + auth.network);
                if (!label) {
                    label = document.createElement('div');
                    label.id = 'profile_' + auth.network;
                    document.getElementById('profile').appendChild(label);
                }
                label.innerHTML = '<img src="' + r.thumbnail + '" /> Hey ' + r.name;
            });
        });
	//1698070390427682
        hello.init({
<<<<<<< HEAD
            facebook: '459308954257383',
            google: '52324465379-k1crqanv88fr16oonnln5p03rvhdv6s6.apps.googleusercontent.com'
        }, {
            redirect_uri: 'http://api.dev',
=======
            facebook: '863455530404549',
            google: '52324465379-k1crqanv88fr16oonnln5p03rvhdv6s6.apps.googleusercontent.com'
        }, {
            redirect_uri: 'http://api.sourc.in',
>>>>>>> 2cc02ad2370126da31c5164767092f909260f0d7
            scope: 'email'
        });

        function google () {
            hello( 'google' ).login( function() {
              var token = hello( 'google' ).getAuthResponse().access_token;
              var test = hello( 'google' ).getAuthResponse();
              console.log(test);
              document.getElementById('google-token').innerHTML = 'Google Token:' + token;
            });
        }

        function facebook () {
            hello( 'facebook' ).login( function() {
              var token = hello( 'facebook' ).getAuthResponse().access_token;
              var test = hello( 'facebook' ).getAuthResponse();
              console.log(test);
              document.getElementById('facebook-token').innerHTML = 'Facebook Token:' + token;
            });
        }
    </script>
</head>
<body>
    <div id="google-token"></div>
    <div id="facebook-token"></div>
    <button onclick="google()">Google</button>
    <button onclick="facebook()">Facebook</button>
</body>
</html>
