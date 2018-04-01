<?php
require_once 'connMysql.php';
session_start();
if(!isset($_SESSION["loginStore"]) || ($_SESSION["loginStore"]=="")){
    header("Location: login.php");
}
if(isset($_GET["logout"])&&($_GET["logout"]=="true")){
    unset($_SESSION["loginStore"]);
    unset($_SESSION["storeid"]);
    header("Location: login.php");
}

//echo $_SESSION["loginStore"];
//echo $_SESSION["storeid"];

$query_RecMember = "SELECT * FROM store WHERE StoreUsername = '{$_SESSION["loginStore"]}'";
$RecMember = $db_link->query($query_RecMember);
$row_RecMember=$RecMember->fetch_assoc();
setcookie("storeid",$row_RecMember["StoreID"]);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>會員系統</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<table><td>
        <div class="boxtl"></div><div class="boxtr"></div>
		<div class="regbox">
          <p class="heading"><strong>店家會員專區</strong></p>
          <p>會員資料</p>
          <p>店名：<?php echo $row_RecMember["StoreName"];?></p>
          <p>聯絡電話：<?php echo $row_RecMember["StorePhone"];?></p>
          <p>地址：<?php echo $row_RecMember["StoreAddressCity"]; echo$row_RecMember["StoreAddressDistriction"]; echo $row_RecMember["StoreAddress"];?></p>
          <p>店家故事：<?php echo $row_RecMember["StoreStory"]?>
          <p>店家風格：<?php echo $row_RecMember["StoreStyle"];?></p>
          <p>店家臉書名稱：<?php echo $row_RecMember["StoreFBName"];?></p>
          <p>店家臉書網址：<?php echo $row_RecMember["StoreFBAddress"];?></p>
          <p>店家IG名稱：<?php echo $row_RecMember["StoreIGName"];?></p>
          <p>店家IG網址：<?php echo $row_RecMember["StoreIGAddress"];?></p>
          <p>產品價格區間：<?php echo $row_RecMember["StorePriceSection"];?></p>
          <p>店家星等：<?php echo $row_RecMember["StoreRate"];?></p>
          <p>Line ID：<?php echo $row_RecMember["StoreLineID"];?></p>
          <p>休息日：<?php echo $row_RecMember["StoreOffday"];?></p>
          <p>營業時間：<?php echo $row_RecMember["StoreWorkingtime"];?></p>
          <p>店家照片：<img src="storephoto/<?php echo $row_RecMember["StorePhoto"];?>" alt="店家照片" width="120" height="120" border="0" /></p>
           <p align="center"><a href="store_update.php">修改資料</a> | 
           <a href="?logout=true">登出系統</a></p>
         </div>
         </td>
</table>
</body>
</html>
<?php 
    $db_link->close();
?>