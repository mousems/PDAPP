<?php
$path_l="/home/fbapp/domains/app.mousems.com/public_html/images";
function roundsize($size){
    $i=0;
    $iec = array("B", "KB", "MB", "GB", "TB");
    while (($size/1024)>1) {
        $size=$size/1024;
        $i++;}
    return(round($size,1)." ".$iec[$i]);
}

function filesize_r($path){
	if(!file_exists($path)) return 0;
	if(is_file($path)) return filesize($path);
	$self = __FUNCTION__;
	$ret = 0;
	foreach(glob($path."/*") as $fn)
	$ret += $self($fn);
	return $ret;
}

function deleteDirectory($dir) {
    if (!file_exists($dir)) return true;
    if (!is_dir($dir)) return unlink($dir);
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') continue;
        if (!deleteDirectory($dir.DIRECTORY_SEPARATOR.$item)) return false;
    }
    return rmdir($dir);
}

@session_start();
if($_GET['logout']=='yes'){
	$_SESSION['login']='';
	header("location:admin.php");
}

if($_SESSION['login']!='OK'){
	if(md5($_POST['password']."123456789123456789123456789123456789123456789")=='5ff405bbe76c39e4c2dcb73cda50d876'){
		$_SESSION['login']='OK';
		header("location:admin.php");
	}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>
<body>
<form id="form1" name="form1" method="post" action="https://app.mousems.com/admin.php">
  <label>
    
    <input name="password" type="password" id="password" size="10" />
<input type="submit" name="button" id="button" value="送出" />
  </label>
</form>
<?php
}else{
	
	if($_POST['clean']=='yes'){
		deleteDirectory("images");
		mkdir("images");
		mkdir("images/download");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>
<body>  
<a href="admin.php?logout=yes">登出</a><br/>
<p>目前使用量:<?php echo roundsize(filesize_r($path_l));?></p>
<form id="form2" name="form2" method="post" action="admin.php">
  <label>
    <input type="submit" name="button2" id="button2" value="清空" />
  </label>
  <input name="clean" type="hidden" id="clean" value="yes" />
</form>
<p>&nbsp;</p>
<?php
}
?>
</body>
</html>