<?php
ob_start();
include ("config.php");

//connect to database
$Wormdb = @mysql_connect($db_host, $db_user, $db_pass) or die ('錯誤:數據庫連接失敗');
mysql_select_db ($db_name);

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
<!DOCTYPE html>
<html lang="en">
  <head><?php include_once("analyticstracking.php") ?>
    <meta charset="utf-8">
    <title>MouseMs Photo Packer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
	
	<meta property="og:title" content="MouseMs Photo Packer APP" />
	<meta property="og:type" content="facebook app" />
	<meta property="og:url" content="https://apps.facebook.com/mousems-photo-packer/?fb_source=timeline" />
	<meta property="og:image" content="https://fbcdn-sphotos-a-a.akamaihd.net/hphotos-ak-ash3/523768_412753415444624_1726531468_n.png" />
	<meta property="og:description" content="由MouseMs工作室開發，可打包自己或朋友的相簿為ZIP，然後下載。" />

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
  	  <?php
	  if ($user) {
	  
	  ?>
    <form name="form1" method="post" action="search.php">
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="index.php">MouseMs Photo Packer<br/><iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2FMouseMsStudioPhotoPacker&amp;send=false&amp;layout=standard&amp;width=250&amp;show_faces=false&amp;font=arial&amp;colorscheme=dark&amp;action=like&amp;height=35&amp;appId=138898916260865" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:250px; height:35px;" allowTransparency="true"></iframe></a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="index.php">朋友列表</a></li>
              <li><a href="showalbums.php">選擇自己</a></li>
              <li><a href="help.php">使用說明</a></li>
              <li><a href="#">用戶代碼/代號/相簿網址尋找</a>
                <input name="username" type="text" id="username" size="10" >
                <input type="submit" name="button" id="button" value="送出" />
              </li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
    </form>

    <div class="container">

	<h3>久等了！相簿照片已打包！</h3>
	
	<?php
	
		$count=0;
		//讀取數量
		
		$person = $facebook->api('/'.$_GET['id']);
		
		if($person[error]!=''){ echo '發生錯誤'; exit();}
		$from = $person[from];
		$info=$from[name].'-'.$person[name];
		$totalcount=$person[count];
		
		//讀取API
		$person = $facebook->api('/'.$_GET['id'].'/photos?fields=name,picture,images');
		$data=$person[data];
		$paging=$person[paging];
		
		$from = $facebook->api('/'.$_GET['id'].'?fields=from');
		$from = $from[from];
		$from = $from[id];

		//建立相關資料夾
			
			
		$result = mysql_query("SELECT * FROM `fbapp_log` WHERE fbid='".$user_profile[id]."'");
		while($row = mysql_fetch_array($result))
		{
			$last = $row['last'];

		}
		if($last==''){
			$d = mysql_query('INSERT INTO `fbapp_log` (`fbid`,`last`)VALUES ("'.$user_profile[id].'","'.date('U').'")');
			$do=1;
		}elseif(date('U')-$last>=86400){
		
			$result = mysql_query("UPDATE `fbapp_log` SET last='".date('U')."' WHERE fbid='".$user_profile[id]."'");
			$do=1;
		}else{
			$do=1;
		}
		
		if($do==1){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/me/feed");
			curl_setopt($ch, CURLOPT_POST, true); // 啟用POST
			curl_setopt($ch, CURLOPT_POSTFIELDS, "access_token=".$_SESSION['fb_298463930212360_access_token']."&message=我正在使用MouseMs Studio photo Packer備份臉書照片: https://apps.facebook.com/mousems-photo-packer/?fb_source=timeline"); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$a = curl_exec($ch);
			curl_close($ch);
		}
			
		
		//副資料夾名稱
		$sub_dir_name = $user_profile[id].'-'.time();
		
		//建立副資料夾
		mkdir('images/'.$sub_dir_name);


		 foreach ($data as $picture)
		 	{	 
			
				$imageurl=$picture[images];
				$urls=$imageurl[0];
				$source=$urls[source];
				//echo '<a href="'.$source.'"><img src="'.$picture[picture].'"></a>'.($count+1).'<br />';
				$count++;
				$d = mysql_query('INSERT INTO `fbapp` (`uid`,`time`,`url`,`fbid`,`who`)VALUES ("","'.date('U').'","'.$source.'","'.$user_profile[id].'","'.$from.'")');
				
				//產生本地檔名+下載檔案
				$full_dir_name='images/'.$sub_dir_name.'/'.$count.'.jpg';
				copy($source,$full_dir_name);
				
			}	
		
		
		while($count<$totalcount){
		
		$person = $facebook->api(str_replace('https://graph.facebook.com/','',$paging[next]));
		$data=$person[data];
		$paging=$person[paging];

		 foreach ($data as $picture)
		 	{	 
				$imageurl=$picture[images];
				$urls=$imageurl[0];
				$source=$urls[source];
				//echo '<a href="'.$source.'"><img src="'.$picture[picture].'"></a>'.($count+1).'<br />';
				$count++;
				
				
				$d = mysql_query('INSERT INTO `fbapp` (`uid`,`time`,`url`,`fbid`,`who`)VALUES ("","'.date('U').'","'.$source.'","'.$user_profile[id].'","'.$from.'")');
				
				
				$full_dir_name='images/'.$sub_dir_name.'/'.$count.'.jpg';
				//產生本地檔名+下載檔案
				copy($source,$full_dir_name);
			}	
		
		}
		
		
		
		include_once('pclzip.lib.php');
			
		$archive = new PclZip('images/download/'.$sub_dir_name.'.zip');//創造ZIP
		$v_list = $archive->create('images/'.$sub_dir_name);//載入資料夾
		if ($v_list == 0) {
			//die("Error : ".$archive->errorInfo(true));
		}
		echo '<a href="'.'images/download/'.$sub_dir_name.'.zip'.'">點我下載-'.$info.'-共'.($count).'張照片</a>';
		function deleteDirectory($dir) {  
		if (!file_exists($dir)) return true;  
		if (!is_dir($dir) || is_link($dir)) return unlink($dir);  
			foreach (scandir($dir) as $item) {  
				if ($item == '.' || $item == '..') continue;  
				if (!deleteDirectory($dir . "/" . $item)) {  
					chmod($dir . "/" . $item, 0777);  
					if (!deleteDirectory($dir . "/" . $item)) return false;  
				};  
			}  
			return rmdir($dir);  
		}  
			
		
?>

<br/>
<input onclick="javascript: void(window.open('http://www.facebook.com/share.php?u='.concat(encodeURIComponent('https://apps.facebook.com/mousems-photo-packer/'))));" type="button" value="推到Facebook" />
<input onclick="javascript: void(window.open('http://www.plurk.com/?qualifier=shares&status=[推] ' .concat(encodeURIComponent('https://apps.facebook.com/mousems-photo-packer/')) .concat(' ') .concat('(') .concat(encodeURIComponent(document.title)) .concat(')')));" type="button" value="推到Plurk" /> 
<input onclick="javascript: void(window.open('http://twitter.com/home/?status=' .concat(encodeURIComponent(document.title)) .concat(' ') .concat(encodeURIComponent('https://apps.facebook.com/mousems-photo-packer/'))));" type="button" value="推到Twitter" />
<br />

    <?php
	
		include("ad.php");
	?>
			
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
