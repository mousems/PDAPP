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

?>
<!doctype html>

<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head><?php include_once("analyticstracking.php") ?>
    <title>php-sdk</title>
    <style>
      body {
        font-family: 'Lucida Grande', Verdana, Arial, sans-serif;
      }
      h1 a {
        text-decoration: none;
        color: #3b5998;
      }
      h1 a:hover {
        text-decoration: underline;
      }
    </style>
  </head>
  <body>
    <h1>MouseMs FB Photo Downloader</h1>
    <h3>select friend</h3>
    <p><a href="showalbums.php">檢視自己的相簿</a> | <a href="selectalbum.php">選擇朋友的相簿</a></p>

    <?php if ($user): ?>
	<img src="https://graph.facebook.com/<?php echo $user; ?>/picture"><?php echo $user_profile[name]; ?><a href="<?php echo $logoutUrl; ?>">登出</a>
    <?php else: ?>
      <div>
        <a href="<?php echo $loginUrl; ?>">以Facebook帳號登入</a>
      </div>
    <p>
        <?php exit(); endif ?>
  </p>
    <hr>
		  <?php
	  if ($user) {
	  
	  ?>
<h3>朋友列表(點擊相片檢視該人相簿)：</h3>
	<a href="showalbums.php">自己</a><br />
	<?php
		//讀取原始碼
		$person = $facebook->api('/me/friends?fields=picture,name');
		
		$data=$person[data];
		

		 foreach ($data as $myfriend)
		 	{	 
			
				$picture=$myfriend[picture];
				$url=$picture[data];
				echo '<a href="showalbums.php?id='.$myfriend[id].'"><img src="'.$url[url].'"></a>'.$myfriend[name].'<br />';
			}	
		
	?>

<?php
		} else {

		echo "<br /><a target='blank' href='".$loginUrl."'>點我登入APP</a>";
		}
	  ?>


	
  </body>
</html>

