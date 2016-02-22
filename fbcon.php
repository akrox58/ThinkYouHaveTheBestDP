<?php

// added in v4.0.0
require_once 'autoload.php';
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;
session_start();
$app_secret='Enter your App Password Here';
FacebookSession::setDefaultApplication( 'Enter Your App Id Here', $app_secret);
FacebookSession::enableAppSecretProof(true);
    $helper = new FacebookRedirectLoginHelper('PATH TO FBCON.php/fbcon.php' );
try {
  $session = $helper->getSessionFromRedirect();
} catch( FacebookRequestException $ex ) {
} catch( Exception $ex ) {
}
if ( isset( $session ) ) {


$request = new FacebookRequest(
  $session,
  'GET',
  '/me',
 array(
    'fields' => 'id,name,email,friends.summary(true),albums{photos{id,likes.summary(true),comments.summary(true),images,name}}'
  )
);

$response = $request->execute();
$graphObject = $response->getGraphObject();
$_SESSION['array'] = $graphObject->asArray();

  header("Location: HomePage.php");
} else {

$permissions=['user_posts','user_photos','email','user_friends'];
  $loginUrl = $helper->getLoginUrl($permissions);
 header("Location: ".$loginUrl);
}
?>
