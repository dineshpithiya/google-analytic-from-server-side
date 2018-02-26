# Example-1

##Google Analytics API PHP

Simple class to set up Oauth 2.0 with Google and query the Google Analytics API v3 with PHP. Curl is required!
The class supports getting the access tokens for *web applications* and *service accounts* registered in the Google APIs console.   
See the documentation for further informations: https://developers.google.com/accounts/docs/OAuth2

##1. Basic Setup

* Create a Project in the Google APIs Console: https://code.google.com/apis/console/
* Enable the Analytics API under Services
* Under API Access: Create an Oauth 2.0 Client-ID
* Give a Product-Name, choose *Web Application* or *Service Account* depending on your needs
* Web Application: Set a redirect-uri in the project which points to your apps url
* Service Account: Download the private key (.p12 file)

##2. Set up Auth

Depending on the chosen application type, the setup is slightly different. This section describes both ways independently.

###2.1 Web applications

```php
include('GoogleAnalyticsAPI.class.php');

$ga = new GoogleAnalyticsAPI(); 
$ga->auth->setClientId('your_client_id'); // From the APIs console
$ga->auth->setClientSecret('your_client_secret'); // From the APIs console
$ga->auth->setRedirectUri('redirect_uri'); // Url to your app, must match one in the APIs console

// Get the Auth-Url
$url = $ga->auth->buildAuthUrl();
```

Provide a link to the Auth-Url. The user has to log in with his Google Account, accept that your App will access the Analytics Data. After completing this steps, 
the user will be redirected back to the redirect-uri along with a code.
This code is needed to get the tokens.

```php
$code = $_GET['code'];
$auth = $ga->auth->getAccessToken($code);

// Try to get the AccessTokenDescription
===========

Server Side Google Analytics (SSGA) is a simple PHP 5 class, which allows to track server-side events and data within Google Analytics.

Drop-in solution for WordPress plugins (uses the WP HTTP API if available).

Usage
-----

Google Analytics Server Side can be used simply in the following manner:


Easy:

	ssga_track( 'UA-YOUR_NUMBER', 'yoursite.com', '/page.php' )

Advanced:
	
	//create new ssga object
	include 'lib/ss-ga.class.php';
	$ssga = new ssga( 'UA-YOUR_NUMBER', 'yoursite.com' );

	//Set a pageview
	$ssga->set_page( '/page.php' );
	$ssga->set_page_title( 'Page Title' );

	// Send
	$ssga->send();
	$ssga->reset();

Set an event (based on http://code.google.com/apis/analytics/docs/tracking/eventTrackerGuide.html) 
	
	//$ssga as created above
	$ssga->set_event( 'Feed', 'Categories', $label, $value );
	$ssga->send();

Ecommerce tracking (update and test by @nczz)
	
	$ssga_step1 = new ssga( 'UA-12345678-1','domain.tw' );
	//$transaction_id, $affiliation, $total, $tax, $shipping, $city, $region, $country
	$ssga_step1->send_transaction("20159527001", "MXP", 280, 0, 80,"Taiwan", "", "TW");
	
	$ssga_step2 = new ssga( 'UA-12345678-1','domain.tw' );
	//$transaction_id, $sku, $product_name, $variation, $unit_price, $quantity
	$ssga_step2->send_item("20159527001", "1229001", "TEST-PRODUCT", "", 50, 4);
	

if ($auth['http_code'] == 200) {
	$accessToken = $auth['access_token'];
	$refreshToken = $auth['refresh_token'];
	$tokenExpires = $auth['expires_in'];
	$tokenCreated = time();
} else {
	// error...
}
```

With the accessToken you can query the API for the given time (seconds) in *$tokenExpires*.
If you need to query the API beyond this time, you should store the refreshToken along with a timestamp in the Database / Session.
If the accessToken expires, you can get a new one with the refreshToken.

```php
// Check if the accessToken is expired
if ((time() - $tokenCreated) >= $tokenExpires) {
	$auth = $ga->auth->refreshAccessToken($refreshToken);
	// Get the accessToken as above and save it into the Database / Session
}
```

###2.2 Service accounts

Copy the email address from the APIs console (xxxxxxxx@developer.gserviceaccount.com). Visit your GA admin and add this email
as a user to your properties.

```php
include('GoogleAnalyticsAPI.class.php');

$ga = new GoogleAnalyticsAPI('service');
$ga->auth->setClientId('your_client_id'); // From the APIs console
$ga->auth->setEmail('your_email_addy'); // From the APIs console
$ga->auth->setPrivateKey('/super/secure/path/to/your/privatekey.p12'); // Path to the .p12 file
```

To query the API, you need to obtain an access token. This token is valid *one hour*, afterwards you'll need to get a new
token. You can store the token in the database/session.

```php
$auth = $ga->auth->getAccessToken();

// Try to get the AccessToken
if ($auth['http_code'] == 200) {
	$accessToken = $auth['access_token'];
	$tokenExpires = $auth['expires_in'];
	$tokenCreated = time();
} else {
	// error...
}
```

##3. Find the Account-ID

Before you can query the API, you need the ID of the Account you want to query the data.
The ID can be found like this:

```php
// Set the accessToken and Account-Id
$ga->setAccessToken($accessToken);
$ga->setAccountId('ga:xxxxxxx');

// Load profiles
$profiles = $ga->getProfiles();
$accounts = array();
foreach ($profiles['items'] as $item) {
	$id = "ga:{$item['id']}";
	$name = $item['name'];
	$accounts[$id] = $name;
}
// Print out the Accounts with Id => Name. Save the Id (array-key) of the account you want to query data. 
// See next chapter how to set the account-id.
print_r($accounts);
```
##4. Query the Google Analytics API

Once you have a valid accessToken and an Account-ID, you can query the Google Analytics API.
You can set some default Query Parameters that will be executed with every query.

```php
// Set the accessToken and Account-Id
$ga->setAccessToken($accessToken);
$ga->setAccountId('ga:xxxxxxx');

// Set the default params. For example the start/end dates and max-results
$defaults = array(
	'start-date' => date('Y-m-d', strtotime('-1 month')),
	'end-date' => date('Y-m-d'),
);
$ga->setDefaultQueryParams($defaults);

// Example1: Get visits by date
$params = array(
	'metrics' => 'ga:visits',
	'dimensions' => 'ga:date',
);
$visits = $ga->query($params);

// Example2: Get visits by country
$params = array(
	'metrics' => 'ga:visits',
	'dimensions' => 'ga:country',
	'sort' => '-ga:visits',
	'max-results' => 30,
	'start-date' => '2013-01-01' //Overwrite this from the defaultQueryParams
); 
$visitsByCountry = $ga->query($params);

// Example3: Same data as Example1 but with the built in method:
$visits = $ga->getVisitsByDate();

// Example4: Get visits by Operating Systems and return max. 100 results
$visitsByOs = $ga->getVisitsBySystemOs(array('max-results' => 100));

// Example5: Get referral traffic
$referralTraffic = $ga->getReferralTraffic();

// Example6: Get visits by languages
$visitsByLanguages = $ga->getVisitsByLanguages();
```
###Metrics & Dimensions Reference:
https://developers.google.com/analytics/devguides/reporting/core/dimsmets

###Google Analytics Query Explorer for testing queries and results:
http://ga-dev-tools.appspot.com/explorer/


# Example-2
## Using the Measurement Protocol 
>> You can call direct using get or post method with PHP
```
http://www.google-analytics.com/collect?v=1&t=pageview&tid=UA-114573654-1&cid=1703612498.1507881615&dh=www.domain.com&dp=%2Fkishan&dt=ReceiptTested%20Page&ti=D12345&ta=Google%20Store%20-%20Online&tr=1000&tt=1000&ts=1000&tcc=SUMMER2013&pa=purchase&pr1id=P12345&pr1nm=Android%20Warhol%20T-Shirt&pr1ca=Apparel&pr1br=Google&pr1va=Black&pr1ps=1
```

>> Get refrence prom here about parameter [Click here] (https://developers.google.com/analytics/devguides/collection/protocol/v1/devguide#page)

```You can use in PHP curl or file_get_contents```