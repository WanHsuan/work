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
require_once("connectmysql.php");

$Arr2=array();
//建立資料連接
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

$StoreID = $_GET["StoreID"];
// $StoreID = '45';
$sql2 = "SELECT * FROM store WHERE StoreID='$StoreID'";
$result2 = execute_sql($link, "handstory", $sql2);
$total_records2 = mysqli_num_rows($result2);
$sql3 = "SELECT SUBSTR(CustomerName,1,1),CustomerCommentScore,CustomerCommentContent FROM CustomerComment,Transaction,Customer WHERE Customer.CustomerID=Transaction.CustomerID AND CustomerComment.TransactionID= Transaction.TransactionID AND Transaction.StoreID='$StoreID' ORDER BY CustomerComment.TransactionID DESC";
$result3 = execute_sql($link, "handstory", $sql3);
$total_records3 = mysqli_num_rows($result3);
$sql4 = "SELECT AVG(CustomerCommentScore) FROM CustomerComment,Transaction WHERE CustomerComment.TransactionID= Transaction.TransactionID AND StoreID='$StoreID' ORDER BY CustomerComment.TransactionID DESC";
$result4 = execute_sql($link, "handstory", $sql4);
$sql5 = "SELECT PictureURL,WorkPicture.WorkID FROM WorkPicture,Work WHERE WorkPicture.WorkID=Work.WorkID AND StoreID='$StoreID' GROUP BY `WorkID` ASC";
$result5 = execute_sql($link, "handstory", $sql5);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width= device-width" />
<link rel="stylesheet" href="style.css" type="text/css">
	<title>Hand's Story--美甲店</title> <!--jcarousel-->
	<link rel="stylesheet"
		href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.2.4/assets/owl.carousel.min.css"></link>
	<link rel="stylesheet" href="jcarouselstyle.css">
	<!--彈出框-->
<link rel="stylesheet" href="remodal.css">
<link rel="stylesheet" href="remodal-default-theme.css">
<link
	href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/themes/hot-sneaks/jquery-ui.css"
	rel="stylesheet">
		<style>
body {
	background-color: #FFF;
}

#apDiv3 {
	position: absolute;
	left: 0px;
	top: 240px;
	width: 1360px;
	height: 460px;
	z-index: 1;
}

#apDiv4 {
	position: absolute;
	left: 40px;
	top: 760px;
	width: 1300px;
	height: 500px;
	z-index: 1;
}

#apDiv5 {
	position: absolute;
	left: 0px;
	top: 1230px;
	width: 1380px;
	height: 581px;
	z-index: 1;
}

#apDiv8 {
	position: absolute;
	left: 0px;
	top: 1830px;
	width: 1380px;
	height: 219px;
	z-index: 1;
}
table {

	font-family: "翩翩體-繁";
	font-size: 18px;
	color: #a7a5a7;
}
.comment {
	
	font-family: "翩翩體-繁";
	font-size: 18px;
	color: #a7a5a7;
	background-color: #FFF;
	opacity: 0.9;
}

.comment tr {
	height: 50px;
}

.comment td {
	border: 1px #dcd8dc solid;
	border-radius: 5px;
}



h3 {
	text-align: center;
	font-family: "翩翩體-繁";
	font-size: 20px;
	color:#a7a5a7;
	margin: 0px;
}

.smalltext {
	text-align: center;
	font-family: "翩翩體-繁";
	font-size: 16px;
	color:#a7a5a7;
	margin: 0px;
}
.pic,.pic1 {
	-webkit-transform: scale(1);
	-webkit-transition: 1s;
	border-radius:5px;
	box-shadow: #efefef 0px 0px 10px 10px;
}

.pic:hover {
	-webkit-transform: scale(1.5);
	border-radius:5px;
	z-index:3;
}

</style>

<script type="text/javascript"
	src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
<script type="text/javascript"
	src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		
		<script
			src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.2.4/owl.carousel.min.js"></script>

</head>

<body>
	<script> 
 function count($StoreID){ 
  <?php 
  $sql555 = "SELECT CustomerUse.StoreID FROM CustomerUse WHERE CustomerUse.CustomerID='$CustomerID' AND CustomerUse.StoreID='$StoreID' ";
  $result555 = execute_sql($link, "handstory", $sql555);
  $total_records555 = mysqli_num_rows($result555);
  mysqli_free_result($result555);
  ?>
//  {
    
   
 if (<?php echo $total_records555 ?> ==1){
	 if (confirm("取消收藏!"))     
 	 location.href = "usestore_delete.php?StoreID=" + $StoreID;

//    alert("取消收藏!")
	 
 }
 else 
 {
	  if (confirm("加入收藏!")){
	 location.href = "usestore_add.php?StoreID=" + $StoreID;
//     alert("加入收藏!");
 }
   }
 }
 </script>

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
?></div><a class='more' href="#modals" style='position:absolute;top:450px;left:1230px;cursor:pointer;color:#948096;text-decoration:none;'>Read more</a>
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
?></div><a class='more' href="#modalf" style='position:absolute;top:350px;left:1230px;cursor:pointer;color:#948096;text-decoration:none;'>Read more</a>
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
			<li><a class='focus' id="st">美甲店</a></li>
			<li><a href="searchpartialstore.php" target="_parent">美甲地圖</a></li>
			<li><a id="fav">收藏店家</a></li>
			<li><a href="group.php" target="_parent">論壇</a></li>
			<li><a href="product_showall_customer.php" target="_parent">銷售平台</a></li>
		</ul>
	</div>
	<!--美甲店區塊-->
	<div class="content" style="width: 1380px; height: 1850px;">
		<?php
$row2 = mysqli_fetch_assoc($result2);

echo "<input class='button' type='button' value='+收藏' style='position:absolute;top:160px;left:780px;'  onclick='count($StoreID)'>";
?>
		<img src="images/美甲店背景.jpg" width="1380" usemap="#Map1" border="0" />
		<map name="Map1" id="Map1">
			<area shape="rect" coords="1136,29,1330,77"
				href="reserve-w.php?StoreID=<?php echo $StoreID ;?>"
				target="_parent" />
		</map>

		<div id="apDiv3">
		
	<?php
$StorePhoto = $row2["StorePhoto"];
// $StoreStory=$row2["StoreStory"];
echo "<table border='0' align='center' width='1200' cellspacing='10'>";

echo "<tr>";
echo "<td width='300' align='center'><p style='margin:2px;padding:5px;widht:250px;height:300px;'><img class='pic' src='storephoto/$StorePhoto' height='300px' style='max-width:350px;_width:expression(this.width >350 ? '350px' : this.width);' ></p></td>";
echo "<td width='300'><p style='margin:2px;padding:5px;widht:250px;height:300px;'>" ;
echo "店名：" . $row2["StoreName"] ."<br>";
echo "風格：" . $row2["StoreStyle"] . "<br>";
echo "臉書：" . "<a href=" . ($row2["StoreFBAddrss"]) . ">" . $row2["StoreFBName"] . '</a><br>';
echo "IG：" . $row2["StoreIGName"] . "<br>";
echo "LineID：" . $row2["StoreLineID"] . "<br>";
echo "價格區間：" . $row2["StorePriceSection"] . "<br>";
echo "RATE：" . $row2["StoreRate"] . "</p></td>";
echo "<td width='300' ><p style='margin:2px;padding:5px;widht:250px;height:300px;'>關於我們：<br>" . $row2["StoreStory"] . "</p></td></tr>";

echo "</table>";
?>
		</div>
		<div id="apDiv4">
			<div class="owl-carousel " style="margin-left: 35px;">
		<?php

while ($row5 = mysqli_fetch_assoc($result5)) {
    $PictureURL = $row5["PictureURL"];
    ?>
           <div class="item"><?php echo "<a class='const' href='work_show.php?id=". ($row5["WorkID"]) ."'>"."<img class='pic1' src='workpicture/$PictureURL' height='230px' style=' border-radius:5px;max-width:280px;_width:expression(this.width >280 ? '280px' : this.width);' >"."</a>";?></div>
			<?php } 
			mysqli_free_result($result5);?>
			 </div>
		</div>
		<div id="apDiv5"><?php

// 指定每頁顯示幾筆記錄


$row4 = mysqli_fetch_assoc($result4);
echo "<h3>分數：" . $row4["AVG(CustomerCommentScore)"] . "顆星</h3><p class='smalltext'>滿分為5顆星</p><br><br>";
mysqli_free_result($result4);
// 執行 SQL 命令

// //計算總頁數
$records_per_page3 = 3;

//取得要顯示第幾頁的記錄
if (isset($_GET["page"]))
    $page = $_GET["page"];
    else
        $page = 1;
        //計算總頁數
        $total_pages = ceil($total_records3 / $records_per_page3);
        
        //計算本頁第一筆記錄的序號
        $started_record = $records_per_page3 * ($page - 1);
        
        //將記錄指標移至本頁第一筆記錄的序號
        mysqli_data_seek($result3, $started_record);

echo "<table class='comment' border='0' width='800' align='center' cellspacing='0'>";
echo "<tr><td colspan='3' height='60' style='font-weight:bold;'><h3>評論紀錄</h3></td></tr>";
echo "<tr><td width='200' align='center'>評論者</td>";
echo "<td width='200' align='center'>分數</td>";
echo "<td width='400' align='center'>內容</td></tr>";

// 顯示記錄
// $j = 1;
for ($i = 0; $i < $records_per_page3&&$i < $total_records3; $i++)
{
    //取得產品資料
    
    $row3 = mysqli_fetch_assoc($result3);
    
    echo "<tr><td align='center'>".$row3["SUBSTR(CustomerName,1,1)"] . "**</td>";
    echo "<td align='center'>".$row3["CustomerCommentScore"] . "</td>";
    echo "<td>".$row3["CustomerCommentContent"] . "</td></tr>";
}
echo "</table>";

// //產生導覽列
echo "<p align='center'>";

if ($page > 1)
    echo "<a href='store.php?page=". ($page - 1) . "'>上一頁</a> ";
    
    for ($i = 1; $i <= $total_pages; $i++)
    {
        if ($i == $page)
            echo "$i ";
            else
                echo "<a href='store.php?page=$i'>$i</a> ";
    }
    
    if ($page < $total_pages)
        echo "<a href='store.php?page=". ($page + 1) . "'>下一頁</a> ";
        echo "</p>";
        
        mysqli_free_result($result3);
?></div>
		<div id="apDiv8"><?php
$StorePhone = $row2["StorePhone"];
$StoreAddressCity = $row2["StoreAddressCity"];
$StoreAddressDistriction = $row2["StoreAddressDistriction"];
$StoreAddress = $row2["StoreAddress"];
$StoreOffday = $row2["StoreOffday"];
$StoreWorkingtime = $row2["StoreWorkingtime"];
mysqli_free_result($result2);
echo "<table border='0' align='center' width='900' cellspacing='10'>";
echo "<tr><td width='400' style='font-weight:bold;'>電話：" . $StorePhone . "</td>";
echo "<td width='500' style='font-weight:bold;'>地址：" . $StoreAddressCity . $StoreAddressDistriction . $StoreAddress . "</td></tr>";
echo "<tr><td style='font-weight:bold;'>營業時間：" . $StoreWorkingtime . "</td>";
echo "<td style='font-weight:bold;'>休息日：" . $StoreOffday . "</td></tr>";

?></div>
	</div>
<!--彈出框-->
	<div class="remodal" data-remodal-id="modals" style='overflow: scroll; height: 500px;'>
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
	<div class="remodal" data-remodal-id="modalf" style='overflow: scroll; height: 500px;'>
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
// 釋放記憶體空間
mysqli_free_result($result99);
mysqli_free_result($result100);





// 關閉資料連接
mysqli_close($link);
?>    