<?php
$db_host = "localhost";
$db_username = "root";
$db_password = "root";
$db_name = "handstory";

$db_link = mysqli_connect($db_host, $db_username, $db_password);
if(!$db_link) die("鞈���蝺仃���");
mysqli_query($db_link,"SET NAMES 'utf8'");
$seldb = @mysqli_select_db($db_link, $db_name);
if(!$seldb) die("鞈�澈���仃���");
?>