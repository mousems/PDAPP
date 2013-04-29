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
	
<h3>說明 </h3>
<p>Q1:這個APP在做什麼？</p>
<p>A1:選擇一個人的相簿，APP會幫你打包成ZIP供下載。</p>
<p>&nbsp;</p>
<p>Q2:找不到朋友怎麼辦？</p>
<p>A2:先用FB的搜尋功能找到你的朋友，再利用APP的&quot;以用戶代碼/代號尋找&quot;或是直接在搜尋欄位輸入相簿完整網址。</p>
<p>&nbsp;</p>
<p>Q3:&quot;以用戶代碼/代號/相簿網址尋找&quot;要如何使用？</p>
<p>A3:輸入以下紅字部分就可找到目標</p>
<ul>
  <li>某人的用戶代碼(http://www.facebook.com/<strong class="a">mousems</strong>)</li>
  <li>某人的用戶代號(http://www.facebook.com/profile.php?id=<strong class="a">100000155080998</strong>)</li>
  <li>某相簿的完整網址(<span class="a">https://www.facebook.com/media/set/?set=a.376995711728.190761.20531316728</span>)</li>
</ul>
<p>&nbsp;</p>
<p>Q4:為甚麼無法讀取某些人的相簿列表？</p>
<p>A4:因為他有設定&quot;禁止第三方軟體讀取相簿&quot;這個功能，所以APP無法去抓他的相簿。</p>
<p>&nbsp;</p>
<p>歡迎來信指教：mousems.kuo [at] gmail.com (請把[at]替換為@)</p>
<?php


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
