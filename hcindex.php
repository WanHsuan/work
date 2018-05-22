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

require_once ("connectmysql.php");
// 建立資料連接
$link = create_connection();
date_default_timezone_set('Asia/Taipei');
$ActualDate = date("Y-m-d");
$sql = "SELECT StoreMarketingPictureDMFilename,StoreID FROM StoreMarketing,StoreMarketingPicture WHERE StoreMarketing.StoreMarketingID=StoreMarketingPicture.StoreMarketingID  AND  StoreMarketingBeginDate BETWEEN DATE_SUB('$ActualDate', INTERVAL '14' DAY) AND DATE_ADD('$ActualDate', INTERVAL '14' DAY)  AND StoreMarketingEndDate>'$ActualDate' ORDER BY StoreMarketingPicture.StoreMarketingID DESC";
$result = execute_sql($link, "handstory", $sql);

$sql1 = "SELECT StorePhoto,Transaction.StoreID FROM Transaction,store WHERE Transaction.StoreID=store.StoreID GROUP BY Transaction.StoreID ORDER BY COUNT(Transaction.StoreID) DESC";
$result1 = execute_sql($link, "handstory", $sql1);

// $sql2="SELECT PictureURL FROM Work,WorkPicture WHERE WorkPicture.WorkID=Work.WorkID GROUP BY WorkPicture.WorkID ORDER BY WorkPicture.WorkID DESC";
$sql2 = "SELECT PictureURL,WorkPicture.WorkID,StoreID FROM WorkPicture,Work WHERE WorkPicture.WorkID=Work.WorkID GROUP BY `WorkID` DESC";
$result2 = execute_sql($link, "handstory", $sql2);

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
	<title>Hand's Story</title>
	<!--彈出框-->
<link rel="stylesheet" href="remodal.css">
<link rel="stylesheet" href="remodal-default-theme.css">
<link
	href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/themes/hot-sneaks/jquery-ui.css"
	rel="stylesheet">
	<style>
.A {
	width: 1380px;
	height: 400px;
	background-color: rgb(243, 243, 243);
	padding: 0px;
	margin: 0px;
}

.B {
	width: 1380px;
	height: 400px;
}

h1 {
	padding-top: 25px;
	text-align: center;
	font-family: "翩翩體-繁";
	font-size: 26px;
	color:#a7a5a7;
	margin: 0px;
}
</style>

	<!--jcarousel-->
	<link rel="stylesheet"
		href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.2.4/assets/owl.carousel.min.css"></link>
	<link rel="stylesheet" href="jcarouselstyle.css">
		<style type="text/css">
#apDiv3 {
	position: absolute;
	left: 25px;
	top: 222px;
	width: 1325px;
	height: 228px;
	z-index: 1;
}

#apDiv4 {
	position: absolute;
	left: 25px;
	top: 622px;
	width: 1325px;
	height: 228px;
	z-index: 1;
}

#apDiv5 {
	position: absolute;
	left: 25px;
	top: 1022px;
	width: 1325px;
	height: 228px;
	z-index: 1;
}


</style>
	
	<script type="text/javascript"
	src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
<script type="text/javascript"
	src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.2.4/owl.carousel.min.js"></script>
    
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
    // echo "<div class='count'>" . $total . "</div>";
    mysqli_free_result($result333);
    mysqli_free_result($result444);
    ?></a>
				<ul>
					<li><a class="sub" href="consumerhistorycomment.php" target="_parent">待評價</a></li>
					<li><a class="sub" href="consumerhistorycomment.php#history" target="_parent">評價紀錄</a></li>
				</ul></li>
			<li><a class="con" href="?logout=true">登出</a></li>

		</ul>

	</div>
	<!--menu-->
	<div id="menu">
		<ul id="button">
			<li><a href="reserve.php" target="_parent">我的預約</a></li>
			<li><a id="st">美甲店</a></li>
			<li><a href="searchpartialstore.php" target="_parent">美甲地圖</a></li>
			<li><a id="fav">收藏店家</a></li>
			<li><a href="group.php" target="_parent">論壇</a></li>
			<li><a href="product_showall_customer.php" target="_parent">銷售平台</a></li>
		</ul>
	</div>
	<!-- 第一塊 -->
	<div class="A">
		<h1>最新活動 Latest Events</h1>
		<div id="apDiv3">
			<div class="owl-carousel ">
    		<?php
    
    while ($row = mysqli_fetch_assoc($result)) {
        $StoreMarketingPictureDMFilename = $row["StoreMarketingPictureDMFilename"];
        ?>
         <div class="item"><?php echo "<a class='const' href='store.php?StoreID=". ($row["StoreID"]) . "'>"."<img class='pic' src='marketingpicture/$StoreMarketingPictureDMFilename' class='image' height='220' style='border-radius:5px; box-shadow: #efefef 0px 0px 10px 10px;'>"."</a>";?></div>
<?php } 
mysqli_free_result($result);?>
	  </div>
		</div>
	</div>
	<!--第二塊-->
	<div class="B">
		<h1>新品上市 New Arrival</h1>
		<div id="apDiv4">
			<div class="owl-carousel ">
    		<?php
    
    while ($row2 = mysqli_fetch_assoc($result2)) {
        $PictureURL = $row2["PictureURL"];
        ?>
         <div class="item"><?php echo "<a class='const' href='store.php?StoreID=". ($row2["StoreID"]) . "'>"."<img class='pic' src='workpicture/$PictureURL' class='image' height='220' style='border-radius:5px; box-shadow: #efefef 0px 0px 10px 10px;'>"."</a>";?></div>
<?php } 
mysqli_free_result($result2);?>
	  </div>
		</div>

	</div>
	<!--第三塊-->
	<div class="A">
		<h1>熱門榜 Hot Sale</h1>
		<div id="apDiv5">
			<div class="owl-carousel ">
    		<?php
    
    while ($row1 = mysqli_fetch_assoc($result1)) {
        $StorePhoto = $row1["StorePhoto"];
        ?>
            <div class="item"><?php echo "<a class='const' href='store.php?StoreID=". ($row1["StoreID"]) . "'>"."<img class='pic' src='storephoto/$StorePhoto' class='image' height='220' style='border-radius:5px; box-shadow: #efefef 0px 0px 10px 10px;'>"."</a>";?></div>
<?php } 

mysqli_free_result($result1);?>
	  </div>
		</div>
			
	</div>
	<!--第四塊-->
	<div class="B" style="height:150px;">
		<h1>--Hand's Story--</h1>
		<p style="font-family:'翩翩體-繁';text-align:center;color:#a7a5a7;">聯絡我們:台北市文山區指南路二段64號</p>
	<a class='buttona' href='hcindexm.php' style="margin-left:650px;">行動版</a>
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
		$('.owl-carousel').owlCarousel({
    		loop:false,
    		margin:15,
    		nav:false,	
			items:4,
		})
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


mysqli_free_result($result99);
mysqli_free_result($result100);


mysqli_close($link);

?>