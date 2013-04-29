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
    'scope' => 'email,user_photos,friends_photos,publish_stream',
    'next' => 'https://apps.facebook.com/mousems-photo-packer/',
    'redirect_uri' => 'https://apps.facebook.com/mousems-photo-packer/'
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
	  <?php
	  if ($user) {
	  
	  ?>
	
	<form name="form2" method="post" action="searchfriend.php">

	  
	  <h2>好友列表</h2><label>
	    <input name="name" type="text" id="name" size="5">
        <input type="submit" name="button2" id="button2" value="搜尋好友">
	  </label>
	  </form>
    <?php
		include("ad.php");
	?>
<br />(<a href="index.php?sex=1">男</a>、<a href="index.php?sex=2">女</a>)<table width="750" border="1">
	  <?php
		//讀取原始碼
		
		$person = $facebook->api('/me/friends?fields=picture,name,gender');
		
		$data=$person[data];
		if($person[error]!=''){ echo '發生錯誤'; exit();}
		$count = 1;
		$adcount=0;
		 foreach ($data as $myfriend)
		 	{	 
			
				if($_GET['sex']==1){
					if($myfriend['gender']=='male'){
						if($count%15==1){ echo '<tr>'; }
						
						if($url[url]=='https://fbcdn-profile-a.akamaihd.net/static-ak/rsrc.php/v2/yo/r/UlIqmHJn-SK.gif'
						  |$url[url]=='https://fbcdn-profile-a.akamaihd.net/static-ak/rsrc.php/v2/y9/r/IB7NOFmPw2a.gif'){
							echo '<th scope="col"><a href="showalbums.php?id='.$myfriend[id].'">'.$myfriend[name].'</a><br /></th>';
						}else{
							echo '<th scope="col"><a href="showalbums.php?id='.$myfriend[id].'" title="'.$myfriend[name].'"><img src="https://graph.facebook.com/'.$myfriend[id].'/picture"></a><br /></th>';
						}
						
						if($count%15==0){ echo '</tr>'; }
						if($count%300==0&&$adcount<=3){
							echo'<tr><td colspan="15" align="center">';
							include("ad.php");
							echo'</td></tr>';
							$adcount++;
						}
						$count ++;
					}
					
				}elseif($_GET['sex']==2){
					if($myfriend['gender']=='female'){
						if($count%15==1){ echo '<tr>'; }
						
						if($url[url]=='https://fbcdn-profile-a.akamaihd.net/static-ak/rsrc.php/v2/yo/r/UlIqmHJn-SK.gif'
						  |$url[url]=='https://fbcdn-profile-a.akamaihd.net/static-ak/rsrc.php/v2/y9/r/IB7NOFmPw2a.gif'){
							echo '<th scope="col"><a href="showalbums.php?id='.$myfriend[id].'">'.$myfriend[name].'</a><br /></th>';
						}else{
							echo '<th scope="col"><a href="showalbums.php?id='.$myfriend[id].'" title="'.$myfriend[name].'"><img src="https://graph.facebook.com/'.$myfriend[id].'/picture"></a><br /></th>';
						}
						
						if($count%15==0){ echo '</tr>'; }
						
						if($count%300==0&&$adcount<=3){
							echo'<tr><td colspan="15" align="center">';
							include("ad.php");
							echo'</td></tr>';
							$adcount++;
						}
						$count ++;
					}
					
				}else{
					if($count%15==1){ echo '<tr>'; }
					
					if($url[url]=='https://fbcdn-profile-a.akamaihd.net/static-ak/rsrc.php/v2/yo/r/UlIqmHJn-SK.gif'
					  |$url[url]=='https://fbcdn-profile-a.akamaihd.net/static-ak/rsrc.php/v2/y9/r/IB7NOFmPw2a.gif'){
						echo '<th scope="col"><a href="showalbums.php?id='.$myfriend[id].'">'.$myfriend[name].'</a><br /></th>';
					}else{
						echo '<th scope="col"><a href="showalbums.php?id='.$myfriend[id].'" title="'.$myfriend[name].'"><img src="https://graph.facebook.com/'.$myfriend[id].'/picture"></a><br /></th>';
					}
					
					if($count%15==0){ echo '</tr>'; }
					
						if($count%300==0&&$adcount<=3){
							echo'<tr><td colspan="15" align="center">';
							include("ad.php");
							echo'</td></tr>';
							$adcount++;
						}
					$count ++;
				}
			
				
				
				
				
			}	
			
				if(($count-1)%19!=0){ echo '</tr>'; }
		
	?>  
    
    </table>
	
	  <?php
		} else {

		echo "<br /><a target='blank' href='".$loginUrl."'>點我登入APP</a>";
		}
	  

	include("buttom.html");	
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
