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
    'next' => 'http://app.mousems.com/',
    'redirect_uri' => 'http://app.mousems.com/'
));
}

?>
<!DOCTYPE html>
<html lang="en">
  <head><?php include_once("analyticstracking.php") ?>
    <meta charset="utf-8">
    <title>MouseMs Photo Packer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">
                                   <link rel="shortcut icon" href="ico/favicon.png">
  </head>

  <body>

<?php include("banner.php"); ?>

    <div class="container">

	<h2>搜尋結果</h2>
	
    <?php
		include("ad.php");
	?>
<?php
		if(preg_match('/[\d]+\./',$_POST['username'])&&preg_match('/\bfacebook.com\b/i',$_POST['username'])){
			preg_match('/[\d]+/',$_POST['username'], $a);
				$person = $facebook->api('/'.$a[0]);
				
				if($person[error]!=''){ echo '發生錯誤'; exit();}
				if($person[name]!=''){
					$from = $person[from];
					
					echo '<h3>找到一本屬於：'.$from[name].'的相簿<img src="https://graph.facebook.com/'.$from[id].'/picture"></h3>';
					echo '<h4>以下是該相簿詳細資料</h4>';
					$coverphoto= $facebook->api('/'.$person[cover_photo]);
					
	?>
					
    <table border="1">
      <tr>
        <td><div align="center">相簿封面</div></td>
        <td><div align="center">相簿名稱</div></td>
        <td><div align="center">數量</div></td>
        <td><div align="center">估計<br>花費<br>時間</div></td>
      </tr>
      <tr>
        <td><div align="center"><?php echo '<img src="'.$coverphoto[picture].'">'; ?></div></td>
        <td><div align="center"><?php echo '<a href=showphotos.php?id='.$person[id].'>'.$person[name].'</a> <br />'; ?></div></td>
        <td><div align="center"><?php echo '共'.$person[count].'張'; ?></div></td>
        <td><div align="center"><?php echo ($person[count]*0.2+$person[count]/5)."秒"; ?></div></td>
      </tr>

    </table>
					
					<?php
					
					
				}else{
					echo 'album not found.';
				}
				
				
		}else{
				
				$person = $facebook->api('/'.$_POST['username']);
				
				if($person[name]!=''){
					
					echo '<h3>您輸入的是：'.$person[name].'<img src="https://graph.facebook.com/'.$person[id].'/picture"></h3>';
					echo '<h4>以下是他的相簿列表</h4>';
					
					$person = $facebook->api('/'.$_POST['username'].'/albums?fields=name,count,cover_photo');
					$data=$person[data];
					?>
					
					
    </p>
    
    <table border="1">
      <tr>
        <td><div align="center">相簿封面</div></td>
        <td><div align="center">相簿名稱</div></td>
        <td><div align="center">數量</div></td>
        <td><div align="center">估計<br>
          花費<br>
          時間</div></td>
      </tr>
      <?php
      
             foreach ($data as $albumblock)
                {	 
					if($albumblock[count]!=''){
						$albumcount = $albumblock[count];
						$photocoverget = $facebook->api('/'.$albumblock[cover_photo]);
      ?>
      <tr>
        <td><div align="center"><?php echo '<img src="'.$photocoverget[picture].'">'; ?></div></td>
        <td><div align="center"><?php echo '<a href=showphotos.php?id='.$albumblock[id].'>'.$albumblock[name].'</a> <br />'; ?></div></td>
        <td><div align="center"><?php echo '共'.$albumcount.'張'; ?></div></td>
        <td><div align="center"><?php echo ($albumcount*0.2+$albumcount/5)."秒"; ?></div></td>
      </tr>
      <?php	
				
				
				}
				
			}	
		
	?>
    </table>
					
					
					
					
					<?php
				}else{
					echo 'person not found.';
				}
				
		}
		
		
		
	?>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap-transition.js"></script>
    <script src="js/bootstrap-alert.js"></script>
    <script src="js/bootstrap-modal.js"></script>
    <script src="js/bootstrap-dropdown.js"></script>
    <script src="js/bootstrap-scrollspy.js"></script>
    <script src="js/bootstrap-tab.js"></script>
    <script src="js/bootstrap-tooltip.js"></script>
    <script src="js/bootstrap-popover.js"></script>
    <script src="js/bootstrap-button.js"></script>
    <script src="js/bootstrap-collapse.js"></script>
    <script src="js/bootstrap-carousel.js"></script>
    <script src="js/bootstrap-typeahead.js"></script>

  </body>
</html>
