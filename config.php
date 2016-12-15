<?php
// Define the needed keys
$strConsumerKey = "XXXXXXXXXXXXXXXX";
$strConsumerSecret = "XXXXXXXXXXXXXXXX";
// The callback URL is the script that gets called after the user authenticates with tumblr
// In this example, it would be the included callback.php
$strCallbackUrl = "http://pieliciouspies.com/tumblr-02/callback.php";

define('CONSUMER_KEY', $strConsumerKey);
define('CONSUMER_SECRET', $strConsumerSecret);
define('CALLBACK', $strCallbackUrl);

