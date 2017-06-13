<?php
require 'facebook.php';




// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId' => '66007334968',
  'secret' => 'd1b117f3ff16b84353ee378431fe005b',
));



 
$user = $facebook->getUser();

$isFan = $facebook->api(array(
    "method"    => "pages.isFan",
    "page_id"   => $page_id,
    "uid"       => $user_id
));
if($isFan === TRUE)
    echo "I'm a fan!";
 
if ($user) {
  try {
    $likes = $facebook->api("/me/likes/PAGE_ID");
    if( !empty($likes['data']) )
        echo "I like!";
    else
        echo "not a fan!";
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}
 
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $loginUrl = $facebook->getLoginUrl(array(
    'scope' => 'user_likes'
  ));
}
 
// rest of code here
?>
