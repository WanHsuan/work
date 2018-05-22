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

// 釋放資源並關閉資料連接

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width= device-width" />
<link rel="stylesheet" href="style.css" type="text/css">
	<title>Hand's Story</title> <!--彈出框-->
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
	color: #a7a5a7;
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
	z-index: 2;
}

#apDiv4 {
	position: absolute;
	left: 25px;
	top: 622px;
	width: 1325px;
	height: 228px;
	z-index: 2;
}

#apDiv5 {
	position: absolute;
	left: 25px;
	top: 1022px;
	width: 1325px;
	height: 228px;
	z-index: 2;
}

.button {
	position: absolute;
	left: 1150px;
	top: 27px;
	font-size: 20px;
}

.pic {
	-webkit-transform: scale(1);
	-webkit-transition: 1s;
	border-radius: 5px;
}
</style>
				</link>
				<script type="text/javascript"
					src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
				<script type="text/javascript"
					src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
				<script
					src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.2.4/owl.carousel.min.js"></script>

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
		<a class='more' href="#modals"
			style='position: absolute; top: 450px; left: 1230px; cursor: pointer; color: #948096; text-decoration: none;'>Read
			more</a>
	</div>

	<div id="apDiv6">
		<h1>您的收藏店家</h1>
		<div id="apDiv13"></div>
		<div id="apDiv14"></div>
		<div id="apDiv15"></div>
		<div id="apDiv16"></div>
		<a class='more' href="#modalf"
			style='position: absolute; top: 350px; left: 1230px; cursor: pointer; color: #948096; text-decoration: none;'>Read
			more</a>
	</div>
	<header>
	<img src="images/登入後LOGO.fw.png" width="1380" usemap="#Map" border="0" />
	<map name="Map" id="Map">
		<area shape="rect" coords="123,6,479,87" href="hindex.php"
			target="_parent" />
		<area shape="rect" coords="1118,23,1312,69" href="login.php"
			target="_parent" />
	</map> <a class='button' href="login.php" target="_parent">會員登入 Login</a>
	</header>
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
           <div class="item"><?php echo "<a class='const' href='store.php?StoreID=". ($row["StoreID"]) . "'>"."<img class='pic' src='marketingpicture/$StoreMarketingPictureDMFilename' height='220' >"."</a>";?></div>
<?php
    
}
    mysqli_free_result($result);
    ?>
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
           <div class="item"><?php echo "<a class='const' href='store.php?StoreID=". ($row2["StoreID"]) . "'>"."<img class='pic' src='workpicture/$PictureURL' height='220'>"."</a>";?></div>
<?php
    
}
    
    mysqli_free_result($result2);
    ?>
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
           <div class="item"><?php echo "<a class='const' href='store.php?StoreID=". ($row1["StoreID"]) . "'>"."<img class='pic' src='storephoto/$StorePhoto' height='220'>"."</a>";?></div>
<?php
    
}
    
    mysqli_free_result($result1);
    ?>
	  </div>
		</div>
	</div>
	<!--第四塊-->
	<div class="B" style="height:150px;">
		<h1>--Hand's Story--</h1>
		<p style="font-family:'翩翩體-繁';text-align:center;color:#a7a5a7;">聯絡我們:台北市文山區指南路二段64號</p>
	<a class='buttona' href='hindexm.php' style="margin-left:650px;">行動版</a>
	</div>
	<!--彈出框-->
	<div class="remodal" data-remodal-id="modals"
		style='overflow: scroll; height: 500px; width: 350px;'>
		<button data-remodal-action="close" class="remodal-close"></button>
		<?php
$sql99 = "SELECT * FROM store";
$result99 = execute_sql($link, "handstory", $sql99);
$total_records99 = mysqli_num_rows($result99);
$j99 = 1;

while ($row99 = mysqli_fetch_assoc($result99) and $j99 <= $total_records99) {
    echo "<a class='const' href=' store.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
    $j99 ++;
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

// 關閉資料連接
mysqli_close($link);

?>