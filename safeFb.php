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
$app_secret='76dac9bb26f6cc18a83605f503142660';
FacebookSession::setDefaultApplication( '1685628418393637', $app_secret);
FacebookSession::enableAppSecretProof(true);
    $helper = new FacebookRedirectLoginHelper('http://localhost/HappyBox/fbcon.php' );
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
    'fields' => 'id,name,email,friends.summary(true)'
  )
);


$res = (new Facebook\FacebookRequest(
    $session,
    'GET',
   '/me/photos?fields=images,name,id,tags,from,likes.summary(true),comments.summary(true)'
))->execute();


$_SESSION['res'] = $res;
$response = $request->execute();
$graphObject = $response->getGraphObject();
$_SESSION['array'] = $graphObject->asArray();

  header("Location: result.php");
} else {

$permissions=['user_posts','user_photos','email','user_friends'];
  $loginUrl = $helper->getLoginUrl($permissions);
 header("Location: ".$loginUrl);
}
?>
