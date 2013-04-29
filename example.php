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
    'req_perms' => 'email,user_photos,friends_photos',
    'next' => 'http://app.mousems.com/example.php',
    'redirect_uri' => 'http://app.mousems.com/example.php',
));
}

?>
<!doctype html>

<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
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
    <h1>php-sdk</h1>

    <?php if ($user): ?>
	<img src="https://graph.facebook.com/<?php echo $user; ?>/picture"><?php echo $user_profile[name]; ?><a href="<?php echo $logoutUrl; ?>">登出</a>
    <?php else: ?>
      <div>
        <a href="<?php echo $loginUrl; ?>">以Facebook帳號登入</a>
      </div>
    <?php endif ?>
	
	<?php if ($user): ?>

    <?php else: ?>

    <?php endif ?>
	
	

    <h3>PHP Session</h3>
	
    <pre><?php print_r($_SESSION); ?></pre>

		
	
    <h3>該相簿</h3>
	
	<?php if ($user):
	else:
	echo'請登入';
	exit();	
	endif ?>
	<?php
		//讀取原始碼
		
		$person = $facebook->api('/217695061578995/photos?format=json&limit=25');
		$data=$person[data];
		
		//副資料夾名稱
		$sub_dir_name = time();
				
		//建立相關資料夾
		if(file_exists($user_profile[id])==false){
			mkdir ($user_profile[id]);
		}
		
		//建立副資料夾
		mkdir($user_profile[id].'/'.$sub_dir_name);
				

		//開始下載	
		$count=1;
		
		
		 foreach ($data as $image)
		 {	 
		 
			$imageurls=$image[images];
			$imageurl_big=$imageurls[0];
			$imageurl_big_url=$imageurl_big[source];//網址
			
			//產生本地檔名
			$full_dir_name=$user_profile[id].'/'.$sub_dir_name.'/'.$count.'.jpg';
			$count++;
			copy($imageurl_big_url,$full_dir_name);
			
			

			echo '<img src="'.$imageurl_big_url.'"><br/>'; 	 

		}	
		
		    include_once('pclzip.lib.php');
			
			$archive = new PclZip($user_profile[id].'/'.$sub_dir_name.'.zip');//創造ZIP
			$v_list = $archive->create($user_profile[id].'/'.$sub_dir_name);//載入資料夾
			if ($v_list == 0) {
				die("Error : ".$archive->errorInfo(true));
			}
			
			echo '<a href="'.$user_profile[id].'/'.$sub_dir_name.'.zip'.'">點我下載-共'.($count-1).'張照片</a><br/>';
			
			$next =$data[paging];
			echo $next[next];
		
	?>




	
  </body>
</html>

