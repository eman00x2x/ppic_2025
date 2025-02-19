function statusChangeCallback(response) {  // Called with the results from FB.getLoginStatus().
	if (response.status === 'connected') {   // Logged into your webpage and Facebook.
		testAPI();
	}
}

function checkLoginState() {               // Called when a person is finished with the Login Button.
	FB.getLoginStatus(function (response) {   // See the onlogin handler
		statusChangeCallback(response);
	});
}

window.fbAsyncInit = function () {
	FB.init({
		appId: '{app-id}',
		cookie: true,                     // Enable cookies to allow the server to access the session.
		xfbml: true,                     // Parse social plugins on this webpage.
		version: '{api-version}'           // Use this Graph API version for this call.
	});

	FB.getLoginStatus(function (response) {   // Called after the JS SDK has been initialized.
		statusChangeCallback(response);        // Returns the login status.
	});
};

function testAPI() {                      // Testing Graph API after login.  See statusChangeCallback() for when this call is made.
	console.log('Welcome!  Fetching your information.... ');
	FB.api('/me', { fields: 'id, name, email' }, function (response) {

		$.post(DOMAIN + '/checkSingleSignOn', response, function (data, status) { 
			console.log('Successful login for: ' + response.name);
		});
	});
}