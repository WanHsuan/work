<?php
$db_host = "localhost";
$db_username = "handstory";
$db_password = "4321";
$db_name = "handstory";

$db_link = mysqli_connect($db_host, $db_username, $db_password);
if(!$db_link) die("資料連線失敗");
mysqli_query($db_link,"SET NAMES 'utf8'");
$seldb = @mysqli_select_db($db_link, $db_name);
if(!$seldb) die("資料庫選擇失敗");
?>