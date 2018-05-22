<?php
require_once ("connMysql.php");
session_start();
// 檢查是否經過登入
if (! isset($_SESSION["loginCustomer"]) || ($_SESSION["loginCustomer"] == "")) {
    header("Location: login.php");
}
// 執行登出
if (isset($_GET["logout"]) && ($_GET["logout"] == "true")) {
    unset($_SESSION["loginCustomer"]);
    unset($_SESSION["customerid"]);
    header("Location: login.php");
}
?>
<?php
function GetSQLValueString($theValue, $theType)
{
    switch ($theType) {
        case "string":
            $theValue = ($theValue != "") ? filter_var($theValue, FILTER_SANITIZE_MAGIC_QUOTES) : "";
            break;
        case "int":
            $theValue = ($theValue != "") ? filter_var($theValue, FILTER_SANITIZE_NUMBER_INT) : "";
            break;
        case "email":
            $theValue = ($theValue != "") ? filter_var($theValue, FILTER_VALIDATE_EMAIL) : "";
            break;
        case "url":
            $theValue = ($theValue != "") ? filter_var($theValue, FILTER_VALIDATE_URL) : "";
            break;
    }
    return $theValue;
}
require_once ("connMysql.php");
$sid = 0;
if (isset($_GET["id"]) && ($_GET["id"] != "")) {
    $sid = GetSQLValueString($_GET["id"], "int");
}
// 顯示相簿資訊SQL敘述句
$query_RecWork = "SELECT * FROM Work WHERE WorkID={$sid}";
// 顯示照片SQL敘述句
$query_RecPicture = "SELECT * FROM WorkPicture WHERE WorkID={$sid} ORDER BY PictureID DESC";
// 將二個SQL敘述句查詢資料儲存到 $RecAlbum、$RecPhoto 中
$RecWork = $db_link->query($query_RecWork);
$RecPicture = $db_link->query($query_RecPicture);
// 計算照片總筆數
$total_records = $RecPicture->num_rows;
// 取得相簿資訊
$row_RecWork = $RecWork->fetch_assoc();
?>
<?php

require_once ("connectmysql.php");

$link = create_connection();
// 搜尋全部店家
$sql99 = "SELECT * FROM store";
$result99 = execute_sql($link, "handstory", $sql99);
$total_records99 = mysqli_num_rows($result99);
$j99 = 1;

// 搜尋常用店家
$CustomerID = $_SESSION["customerid"];
$sql100 = "SELECT CustomerUse.StoreID,StoreName FROM CustomerUse,store WHERE CustomerUse.CustomerID='$CustomerID' AND CustomerUse.StoreID=store.StoreID ";
$result100 = execute_sql($link, "handstory", $sql100);
$total_records100 = mysqli_num_rows($result100);
$i100 = 1;
// 釋放資源並關閉資料連接


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width= device-width" />
<link rel="stylesheet" href="style.css" type="text/css">
	<title>Hand's Story--作品</title>
	<!--彈出框-->
<link rel="stylesheet" href="remodal.css">
<link rel="stylesheet" href="remodal-default-theme.css">
<link
	href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/themes/hot-sneaks/jquery-ui.css"
	rel="stylesheet">
	<style>
h2 {
	padding-top: 25px;
	text-align: center;
	font-family: "翩翩體-繁";
	font-size: 26px;
	margin: 0px;
	color:#a7a5a7;
	display: inline;
}

.content {
	background-image: url(images/作品背景.jpg);
	width: 1380px;
	height: 600px;
}

table {
	position: absolute;
	top: 170px;
	left: 400px;
	font-family: "翩翩體-繁";
	font-size: 18px;
	color:#a7a5a7;
	background-color: none;
}

.inp {
	border: none;
	border-bottom: 1px solid darkgray;
	width: 300px;
	height: 25px;
	padding-left: 10px;
	font-family: "翩翩體-繁";
	font-size: 18px;
	background-color: none;
}

.buttons {
	margin: 2px;
}

.update {
	font-family: "翩翩體-繁";
	font-size: 24px;
	border: none;
	/* 	text-align: center; */
	color: #000;
	text-decoration: none;
}

.buttona {
	margin-left: 5px;
}

.pic {
	-webkit-transform: scale(1);
	-webkit-transition: 1s;
	border-radius:5px;
}

.pic:hover {
	-webkit-transform: scale(3);
	border-radius:5px;
	z-index:3;
}
</style>
	<script type="text/javascript"
	src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
<script type="text/javascript"
	src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script language="javascript">
function deletesure(){
    if (confirm('\n您確定要刪除整個作品嗎?\n刪除後無法恢復!\n')) return true;
    return false;
}
</script>

</head>
<body>
	<!--頁首-->
	<div id="apDiv7">
		<h1>美甲店一覽</h1>
		<div id="apDiv9"> <?php

while ($row99 = mysqli_fetch_assoc($result99) and $j99 <= 10 and $j99 <= $total_records99) {
    echo "<a class='const' href=' store.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
    $j99 ++;
}
?> </div>
		<div id="apDiv10"><?php
echo "<a class='const' href=' store.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
while ($row99 = mysqli_fetch_assoc($result99) and $j99 < 20 and $j99 >= 11 and $j99 <= $total_records99) {
    echo "<a class='const' href=' store.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
    $j99 ++;
}
?></div>
		<div id="apDiv11"><?php
echo "<a class='const' href=' store.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
while ($row99 = mysqli_fetch_assoc($result99) and $j99 < 29 and $j99 >= 20 and $j99 <= $total_records99) {
    echo "<a class='const' href=' store.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
    $j99 ++;
}
?></div>
		<div id="apDiv12"><?php
echo "<a class='const' href=' store.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
while ($row99 = mysqli_fetch_assoc($result99) and $j99 < 38 and $j99 >= 29 and $j99 <= $total_records99) {
    echo "<a class='const' href=' store.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
    $j99 ++;
}
?></div>
	<a class='more' href="#modals" style='position:absolute;top:450px;left:1230px;cursor:pointer;color:#948096;text-decoration:none;'>Read more</a>
	</div>
	<div id="apDiv6">
		<h1>您的收藏店家</h1>
		<div id="apDiv13"> <?php

while ($row100 = mysqli_fetch_assoc($result100) and $i100 <= 5 and $i100 <= $total_records100) {
    echo "<a class='const' href=' store.php?StoreID=" . ($row100["StoreID"]) . "'>" . $row100["StoreName"] . "</a>" . "<br>";
    $i100 ++;
}
?></div>
		<div id="apDiv14"><?php
echo "<a class='const' href=' store.php?StoreID=" . ($row100["StoreID"]) . "'>" . $row100["StoreName"] . "</a>" . "<br>";
while ($row100 = mysqli_fetch_assoc($result100) and $i100 < 10 and $i100 >= 6 and $i100 <= $total_records100) {
    echo "<a class='const' href=' store.php?StoreID=" . ($row100["StoreID"]) . "'>" . $row100["StoreName"] . "</a>" . "<br>";
    $i100 ++;
}
?></div>
		<div id="apDiv15"><?php
echo "<a class='const' href=' store.php?StoreID=" . ($row100["StoreID"]) . "'>" . $row100["StoreName"] . "</a>" . "<br>";
while ($row100 = mysqli_fetch_assoc($result100) and $i100 < 14 and $i100 >= 10 and $i100 <= $total_records100) {
    echo "<a class='const' href=' store.php?StoreID=" . ($row100["StoreID"]) . "'>" . $row100["StoreName"] . "</a>" . "<br>";
    $i100 ++;
}
?></div>
		<div id="apDiv16"><?php
echo "<a class='const' href=' store.php?StoreID=" . ($row100["StoreID"]) . "'>" . $row100["StoreName"] . "</a>" . "<br>";
while ($row100 = mysqli_fetch_assoc($result100) and $i100 < 18 and $i100 >= 14 and $i100 <= $total_records100) {
    echo "<a class='const' href=' store.php?StoreID=" . ($row100["StoreID"]) . "'>" . $row100["StoreName"] . "</a>" . "<br>";
    $i100 ++;
}
?></div>
	<a class='more' href="#modalf" style='position:absolute;top:350px;left:1230px;cursor:pointer;color:#948096;text-decoration:none;'>Read more</a>
	</div>
	<!--頁首-->
	<header> <img src="images/登入後LOGO.fw.png" width="1380" usemap="#Map"
		border="0" /> <map name="Map" id="Map">
		<area shape="rect" coords="128,15,477,82" href="hcindex.php"
			target="_parent" />
	</map> </header>
	<!-- 會員中心 -->
	<div id="membercenter">
		<ul id="center">
			<li><a href="customer_update.php" target="_parent">修改資料</a></li>
			<li><a href="shoppingrecord_search_customer.php" target="_parent">我的訂單</a></li>
			<li><a href="consumerhistorycomment.php" target="_parent"
				style='text-align: left;'>我的評論<?php
    // 待平價預約數
    // 已經評論的
    $sql333 = " SELECT CustomerComment.TransactionID FROM CustomerComment WHERE CustomerComment.CustomerID='$CustomerID'";
    $result333 = execute_sql($link, "handstory", $sql333);
    $total_records333 = mysqli_num_rows($result333);
    
    // 選擇已經完成交易的
    $sql444 = "SELECT TransactionID FROM Transaction WHERE TransactionActualDate!='0000-00-00 00:00:00' AND CustomerID='$CustomerID'";
    $result444 = execute_sql($link, "handstory", $sql444);
    $total_records444 = mysqli_num_rows($result444);
    
    $totalr = $total_records444 - $total_records333;
    echo "<div class='countc' style='position:absolute;top:10px;left:260px;'>" . $totalr . "</div>";
    mysqli_free_result($result333);
    mysqli_free_result($result444);
    
    
    ?></a>
				<ul>
					<li><a class="sub" href="consumerhistorycomment.php"
						target="_parent">待評價</a></li>
					<li><a class="sub" href="consumerhistorycomment.php#history"
						target="_parent">評價紀錄</a></li>
				</ul></li>
			<li><a class="con" href="?logout=true">登出</a></li>

		</ul>

	</div>
	<!--menu-->
	<div id="menu">
		<ul id="button">
			<li><a href="reserve.php" target="_parent">我的預約</a></li>
			<li><a class='focus' id="st">美甲店</a></li>
			<li><a href="searchpartialstore.php" target="_parent">美甲地圖</a></li>
			<li><a id="fav">收藏店家</a></li>
			<li><a href="group.php" target="_parent">論壇</a></li>
			<li><a href="product_showall_customer.php" target="_parent">銷售平台</a></li>
		</ul>
	</div>
	<div class="content">
		<table width="600" border="0" align="center" cellpadding="0"
			cellspacing="20">
			<tr>
				<td colspan="4" align="center"><h2>作品名稱:<?php echo $row_RecWork["WorkName"];?></h2>
					</td>
			</tr>

			<tr>
				<td colspan="4" align="right">照片總數：<?php echo $total_records;?></td>
			</tr>
			<tr>
				<td colspan="4" align="center"><strong>價格</strong>：<?php echo $row_RecWork["WorkPrice"];?>
             </td>
			</tr>
           <?php
        $total_count = ceil($total_records / 4) * 8;
        for ($j = 0; $j < $total_count; $j ++) {
            if ($j % 4 == 0) {
                echo '<tr>';
            }
            if ($row_RecPicture = $RecPicture->fetch_assoc()) {
                echo '<td style="width:150px;">';
                ?>
           <a
				href="work_picture.php?id=<?php echo $row_RecPicture["PictureID"];?>"><img
				class='pic'
				src="workpicture/<?php echo $row_RecPicture["PictureURL"];?>"
				alt="<?php echo $row_RecPicture["PictureName"];?>" width="120"
				height="120" border="0" /></a>
           
           <?php
                echo '</td>';
            } else {
                echo '<td style="width:150px;">';
            }
            if ($j % 4 == 3) {
                echo '</tr>';
            }
        }
        ?><tr>
				<td colspan="4" align="center"><input type="button" name="button2"
					class="buttons" value="回上一頁" onClick="window.history.back();" /></td>
			</tr>
		</table>
	</div>
	<!--彈出框-->
	<div class="remodal" data-remodal-id="modals" style='overflow: scroll; height: 500px;width:350px;'>
		<button data-remodal-action="close" class="remodal-close"></button>
		<?php
		$sql99 = "SELECT * FROM store";
		$result99 = execute_sql($link, "handstory", $sql99);
		$total_records99 = mysqli_num_rows($result99);
		$j99 = 1;
		
		while ($row99 = mysqli_fetch_assoc($result99) and  $j99 <= $total_records99) {
    echo "<a class='const' href=' store.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
    $j99 ++;
}?>
	</div>
	<div class="remodal" data-remodal-id="modalf" style='overflow: scroll; height: 500px;width:350px;'>
		<button data-remodal-action="close" class="remodal-close"></button>
		<?php 
		$sql100 = "SELECT CustomerUse.StoreID,StoreName FROM CustomerUse,store WHERE CustomerUse.CustomerID='$CustomerID' AND CustomerUse.StoreID=store.StoreID ";
		$result100 = execute_sql($link, "handstory", $sql100);
		$total_records100 = mysqli_num_rows($result100);
		$i100 = 1;
		while ($row100 = mysqli_fetch_assoc($result100) and  $i00 <= $total_records100) {
		    echo "<a class='const' href=' store.php?StoreID=" . ($row100["StoreID"]) . "'>" . $row100["StoreName"] . "</a>" . "<br>";
		    $i ++;
		}
		?>
	</div>
	<!--彈出框-->
	<script src="remodal.js"></script>
	<script>
		
		$(document).ready(function(){
			$('#fav,#apDiv6').hover(function(){
				$('#apDiv6').show();
			},
			function(){
				$('#apDiv6').hide();
			})
			$('#st,#apDiv7').hover(function(){
				$('#apDiv7').show();
			},
			function(){
				$('#apDiv7').hide();
			})
			
		
		})

	</script>

</body>
</html>
<?php
// 釋放記憶體空間
mysqli_free_result($result99);
mysqli_free_result($result100);
mysqli_free_result($RecWork);
mysqli_free_result($RecPicture);
// 關閉資料連接
mysqli_close($link);
	$db_link->close();
?>