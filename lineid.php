<?php
/**
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require 'facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '298463930212360',
  'secret' => '2ce2dbee5725742deb38fdc131537fa3',
));

// Get User ID
$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {

  $loginUrl = $facebook->getLoginUrl(array(
    'canvas' => 1,
    'fbconnect' => 0,
    'scope' => 'email,user_photos,friends_photos',
    'next' => 'https://apps.facebook.com/mousems-photo-packer/',
    'redirect_uri' => 'https://apps.facebook.com/mousems-photo-packer/'
));

}
	$times=0;
	$exit=1;
	while($times<1420){

		$comment = $facebook->api('/'.$_GET['id'].'/comments');
		$data=$comment[data];
		
		$count=0;
		$jump=0;
		for($count=0;$count<25;$count++){
		
			
			$message=$data[$count];
			$from=$message[from];

			echo($from[id].',,,'.$message[message]."<br />");
			$count++;
		}
			
		
		$comment = $facebook->api(str_replace('https://graph.facebook.com/','',$comment[paging][next]));
		$times++;
	
	}
?>