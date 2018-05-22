<?php require_once ("connMysql.php");
session_start();
// 檢查是否經過登入
if (! isset($_SESSION["loginStore"]) || ($_SESSION["loginStore"] == "")) {
    header("Location: login.php");
}
// 執行登出
if (isset($_GET["logout"]) && ($_GET["logout"] == "true")) {
    unset($_SESSION["loginStore"]);
    unset($_SESSION["storeid"]);
    header("Location: login.php");
}?>
<?php

require_once("connectmysql.php");
//建立資料連接
$link = create_connection();
date_default_timezone_set('Asia/Taipei');
$ActualDate=date("Y-m-d");
$sql="SELECT StoreMarketingPictureDMFilename,StoreID FROM StoreMarketing,StoreMarketingPicture WHERE StoreMarketing.StoreMarketingID=StoreMarketingPicture.StoreMarketingID  AND  StoreMarketingBeginDate BETWEEN DATE_SUB('$ActualDate', INTERVAL '14' DAY) AND DATE_ADD('$ActualDate', INTERVAL '14' DAY)  AND StoreMarketingEndDate>'$ActualDate' ORDER BY StoreMarketing.StoreMarketingID DESC";
$result=execute_sql($link, "handstory", $sql);

$sql1="SELECT StorePhoto,Transaction.StoreID FROM Transaction,store WHERE Transaction.StoreID=store.StoreID GROUP BY Transaction.StoreID ORDER BY COUNT(Transaction.StoreID) DESC";
$result1=execute_sql($link, "handstory", $sql1);

// $sql2="SELECT PictureURL FROM Work,WorkPicture WHERE WorkPicture.WorkID=Work.WorkID GROUP BY WorkPicture.WorkID ORDER BY WorkPicture.WorkID DESC";
$sql2="SELECT PictureURL,WorkPicture.WorkID,StoreID FROM WorkPicture,Work WHERE WorkPicture.WorkID=Work.WorkID GROUP BY `WorkID` DESC";
$result2=execute_sql($link, "handstory", $sql2);

// 搜尋全部店家
$sql99 = "SELECT * FROM store";
$result99 = execute_sql($link, "handstory", $sql99);
$total_records99 = mysqli_num_rows($result99);
$j99 = 1;
//待評價預約數
$StoreID = $_SESSION["storeid"];

//釋放資源並關閉資料連接

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
		    echo "<a class='const' href=' storest.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
		    $j99 ++;
}
?> </div>
		<div id="apDiv10"><?php
		echo "<a class='const' href=' storest.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
		while ($row99 = mysqli_fetch_assoc($result99) and $j99 < 20 and $j99 >= 11 and $j99 <= $total_records99) {
		    echo "<a class='const' href=' storest.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
    $j99 ++;
}
?></div>
		<div id="apDiv11"><?php
		echo "<a class='const' href=' storest.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
		while ($row99 = mysqli_fetch_assoc($result99) and $j99 < 29 and $j99 >= 20 and $j99 <= $total_records99) {
		    echo "<a class='const' href=' storest.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
    $j99 ++;
}
?></div>
		<div id="apDiv12"><?php
		echo "<a class='const' href=' storest.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
		while ($row99 = mysqli_fetch_assoc($result99) and $j99 < 38 and $j99 >= 29 and $j99 <= $total_records99) {
		    echo "<a class='const' href=' storest.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
    $j99 ++;
}
?></div>
	<a class='more' href="#modals" style='position:absolute;top:450px;left:1230px;cursor:pointer;color:#948096;text-decoration:none;'>Read more</a>
	</div>
	
	<!--頁首-->
	<header> <img src="images/登入後LOGO.fw.png" width="1380" usemap="#Map"
		border="0" /> <map name="Map" id="Map">
		<area shape="rect" coords="128,15,477,82" href="hstindex.php"
			target="_parent" />
	</map> </header>
	<!-- 會員中心 -->
	<div id="membercenterst">
		<ul id="center">
			<li><a href='storest.php?StoreID=<?php echo $StoreID?>' target='_parent'>我的頁面</a></li>
			<li><a href="#" target="_parent">店家管理+</a>
				<ul>
					<li><a class="sub" href="store_update.php" target="_parent">修改資料</a></li>
					<li><a class="sub" href="work_manage.php" target="_parent">作品集管理</a></li>
					<li><a class="sub" href="marketing_show.php" target="_parent">活動管理</a></li>
				</ul></li>
			<li><a href="storehistorycomment.php" target="_parent" style='text-align:left;'>我的評論<?php 


// $link = create_connection();
//已經評論的
$sql3 =" SELECT StoreComment.TransactionID FROM StoreComment WHERE StoreComment.StoreID='$StoreID'";
$result3=execute_sql($link, "handstory", $sql3);
$total_records3=mysqli_num_rows($result3);

//選擇已經完成交易的
$sql4 = "SELECT TransactionID FROM Transaction WHERE TransactionActualDate!='0000-00-00 00:00:00' AND StoreID='$StoreID'";
$result4=execute_sql($link, "handstory", $sql4);
$total_records4=mysqli_num_rows($result4);

$total =$total_records4-$total_records3;
echo "<div class='countst' style='position:absolute;top:10px;left:260px;'>" .$total. "</div>";
// echo "<div class='count'>" . $total . "</div>";
mysqli_free_result($result3);
mysqli_free_result($result4);



?></a>
				<ul>
					<li><a class="sub" href="storehistorycomment.php" target="_parent">待評價</a></li>
					<li><a class="sub" href="storehistorycomment.php#history" target="_parent">評價紀錄</a></li>
				</ul></li>
			<li><a class="con" href="?logout=true">登出</a></li>

		</ul>



	</div>
	<!--menu-->
	<div id="menu">
		<ul id="buttonst">
			<li><a href="reservest.php" target="_parent">預約<?php 


//已經評論的
$sql5 ="SELECT TransactionID FROM Transaction WHERE Transaction.StoreID='$StoreID' AND TransactionYesOrNO='0' AND TransactionCancel='0'";
$result5=execute_sql($link, "handstory", $sql5);
$total_records5=mysqli_num_rows($result5);

echo "<div class='countst1' style='position:absolute;top:97px;left:180px;'>" .$total_records5. "</div>";
// echo "<div class='count'>" . $total . "</div>";
mysqli_free_result($result5);
?>
			</a></li>
			<li><a id="st"">美甲店</a></li>
			<li><a href="searchpartialstorest.php" target="_parent">美甲地圖</a></li>
			<li><a href="groupst.php" target="_parent">論壇</a></li>
			<li><a href="#" target="_parent">我的賣場<?php 
//銷售平台待出貨訂單

//已經評論的
$sql5 ="SELECT ShoppingRecordID FROM ShoppingRecord WHERE ShoppingRecordProcessing='處理中' AND StoreID='$StoreID'";
$result5=execute_sql($link, "handstory", $sql5);
$total_records5=mysqli_num_rows($result5);

echo "<div class='countst1' style='position:absolute;top:97px;left:1300px;'>" .$total_records5. "</div>";
mysqli_free_result($result5);
?></a>
				<ul>
					<li><a class="sub" href="product_show.php" target="_parent">上傳/下架商品</a></li>
					<li><a class="sub" href="shoppingrecord_search_store.php" target="_parent">訂單管理</a></li>
				</ul></li>
		</ul>
	</div>
	<!-- 第一塊 -->
    <div class="A">
    	<h1>最新活動 Latest Events</h1>
      	<div id="apDiv3">
    	<div class="owl-carousel ">
    		<?php while ($row = mysqli_fetch_assoc($result))
{  
            $StoreMarketingPictureDMFilename=$row["StoreMarketingPictureDMFilename"];
            ?>
           <div class="item"><?php echo "<a class='const' href='storest.php?StoreID=". ($row["StoreID"]) . "'>"."<img src='marketingpicture/$StoreMarketingPictureDMFilename' height='220' style='border-radius:5px; box-shadow: #efefef 0px 0px 10px 10px;'>"."</a>";?></div>
<?php } 
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
    		<?php while ($row2 = mysqli_fetch_assoc($result2))
{  
    $PictureURL=$row2["PictureURL"];
            ?>
           <div class="item"><?php echo "<a class='const' href='storest.php?StoreID=". ($row2["StoreID"]) . "'>"."<img src='workpicture/$PictureURL' height='220' style='border-radius:5px; box-shadow: #efefef 0px 0px 10px 10px;'>"."</a>";?></div>
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
    		<?php while ($row1 = mysqli_fetch_assoc($result1))
{  
    $StorePhoto=$row1["StorePhoto"];
            ?>
            <div class="item"><?php echo "<a class='const' href='storest.php?StoreID=". ($row1["StoreID"]) . "'>"."<img src='storephoto/$StorePhoto' height='220' style='border-radius:5px; box-shadow: #efefef 0px 0px 10px 10px;'>"."</a>";?></div>
<?php } 

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
	<div class="remodal" data-remodal-id="modals" style='overflow: scroll; height: 500px;width:350px;'>
		<button data-remodal-action="close" class="remodal-close"></button>
		<?php
		$sql99 = "SELECT * FROM store";
		$result99 = execute_sql($link, "handstory", $sql99);
		$total_records99 = mysqli_num_rows($result99);
		$j99 = 1;
		
		while ($row99 = mysqli_fetch_assoc($result99) and  $j99 <= $total_records99) {
    echo "<a class='const' href=' storest.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
    $j99 ++;
}?>
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
mysqli_close($link);

?>

