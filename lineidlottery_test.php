<!DOCTYPE html>
<html lang="en" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
	<meta charset="utf-8">
    <meta property="og:title" content="line id 抽抽樂" />
    <meta property="og:url" content="https://app.mousems.com/lineidlottery.php" />
    <meta property="og:image" content="https://app.mousems.com/072.jpg" />
    <meta property="og:description" content="貼圖詐騙集團猖獗，好多人都在裡面留下了line id，我們蒐集了一部分的line id，製作了這個小網站，" />
    <?php include_once("analyticstracking.php") ?>
    <title>line id 抽抽樂</title>
  </head>
  <body>
  <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/zh_TW/all.js#xfbml=1&appId=298463930212360";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
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
    'next' => 'https://app.mousems.com/lineidlottery.php',
    'redirect_uri' => 'https://app.mousems.com/lineidlottery.php'
));
}

?>
<form>
這是個抽抽樂...隨機顯示一筆資料和他的LINE ID，just for fun<br />提醒各位不要被詐騙集團騙走個資囉^_^，<b>未知性別低於1萬時開放指定性別功能！</b>
</form>
<div class="fb-like" data-href="https://app.mousems.com/lineidlottery.php" data-send="true" data-width="450" data-show-faces="true"></div>
<script type="text/javascript"><!--
google_ad_client = "ca-pub-0998348705878758";
/* fbapp */
google_ad_slot = "2758526380";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript"
src="https://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>

<hr />
<?php
	
	
	include ("config.php");
	$Wormdb = @mysql_connect($db_host, $db_user, $db_pass) or die ('錯誤:數據庫連接失敗');
	mysql_select_db ($db_name);
if($user){



	for($i=0;$i<1;$i++){
			
				
			//$id=rand(1,23080);//23080
			//$result = mysql_query("SELECT * FROM `line` WHERE keya='".$id."' ");
			$result = mysql_query("SELECT * FROM `line` WHERE `gender`='' ORDER BY RAND() LIMIT 1");
					
				
				
				while($row = mysql_fetch_array($result))
					 {
		
						$a=$facebook->api('/'.str_replace(" ","",$row['fbid']).'?fields=picture,gender');
							$gender=$a[gender];
						
						$result2 = mysql_query("UPDATE `line` SET `gender`='".$gender."' WHERE keya='".$row[keya]."'");
						  
						
						$a=$a[picture];
							$a=$a[data];
							$a=str_replace("q.jpg","n.jpg",$a[url]);
						$person=$facebook->api('/'.$row['fbid']);
							$name=$person[name];
							$url=$person[link];
					
?>
<img src="<?php echo $a; ?>" /><br />
編號︰<?php echo $row[keya]; ?>/23080<br />
姓名︰<?php echo $name; ?><br />
性別︰<?php echo $gender; ?><br />
FB︰<a href="<?php echo $url; ?>"  target="_blank"><?php echo $url; ?></a><br />
LINE︰<b><?php echo $row['lineid'];  ?></b>(也許會有別的字元請自行刪除)<br /><br />
<?php
					}
	}
?>


<form id="form1" name="form1" method="post" action="https://app.mousems.com/lineidlottery.php">
  <label>
    不喜歡?<input type="submit" name="button" id="button" value="重新抽" />
  </label>
</form>
<?php
	
		
} else {

		echo "<br /><a href='".$loginUrl."'>點我登入</a>，您必須授權才能觀看他人的資料和照片，請放心，我們不能竊取任何您的資料。<br />";
}


	$result = mysql_query("SELECT COUNT(`keya`)  FROM `line` where `gender`='male'");
	while($row = mysql_fetch_array($result))
		{
			$male=$row["COUNT(`keya`)"];
		}
	$result = mysql_query("SELECT COUNT(`keya`)  FROM `line` where `gender`='female'");
	while($row = mysql_fetch_array($result))
		{
			$female=$row["COUNT(`keya`)"];
		}
?>
<hr />

<a>status:</a>
<ul>
	<li>total:23080</li>
	<li>已知為男性:<?php echo $male; ?></li>
	<li>已知為女性:<?php echo $female; ?></li>
	<li>未知性別:<?php echo 23080-$male-$female; ?>，每看一次就會幫我們找出他的性別。<b>未知性別低於1萬時開放指定性別功能！</b></li>
</ul>
<a>information:</a>
<ul>
	<li>facebook api 授權借用:<a href="https://apps.facebook.com/mousems-photo-packer/?fb_source=timeline" target="_blank">Facebook Photo Packer</a></li>
	<li>免責聲明:個人資料皆由公開之粉絲專頁獲取，若您想將您的資料移除請聯絡:mousems.kuo [at] gmail.com</li>
	<li>hosting:<a href="http://mousems.com/vps" target="_blank">VPS合租計畫</a></li>
</ul>
<br />
</body>
</html>