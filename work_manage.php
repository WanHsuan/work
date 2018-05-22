<?php
require_once ("connMysql.php");
session_start();
if (! isset($_SESSION["loginStore"]) || ($_SESSION["loginStore"] == "")) {
    header("Location: login.php");
}
// 執行登出動作
if (isset($_GET["logout"]) && ($_GET["logout"] == "true")) {
    unset($_SESSION["loginStore"]);
    unset($_SESSION["storeid"]);
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

$sid = 0;
if (isset($_GET["id"]) && ($_GET["id"] != "")) {
    $sid = GetSQLValueString($_GET["id"], "int");
}
// 刪除相簿
if (isset($_GET["action"]) && ($_GET["action"] == "delete")) {
    // 刪除所屬相片
    $query_delpicture = "SELECT * FROM WorkPicture WHERE WorkID={$sid}";
    $delpicture = $db_link->query($query_delpicture);
    while ($row_delpicture = $delpicture->fetch_assoc()) {
        unlink("workpicture/" . $row_delpicture["PictureURL"]);
    }
    // 刪除相簿
    $query_del1 = "DELETE FROM Work WHERE WorkID={$sid}";
    $query_del2 = "DELETE FROM WorkPicture WHERE WorkID={$sid}";
    $db_link->query($query_del1);
    $db_link->query($query_del2);
    // 重新導向回到主畫面
    header("Location: work_manage.php");
}
// 預設每頁筆數
$pageRow_records = 8;
// 預設頁數
$num_pages = 1;
// 若已經有翻頁，將頁數更新
if (isset($_GET['page'])) {
    $num_pages = $_GET['page'];
}
// 本頁開始記錄筆數 = (頁數-1)*每頁記錄筆數
$startRow_records = ($num_pages - 1) * $pageRow_records;
// 未加限制顯示筆數的SQL敘述句
$query_RecWork = "SELECT Work.WorkID , Work.WorkName , Work.WorkPrice, WorkPicture.PictureURL, count( WorkPicture.PictureID ) AS pictureNum FROM Work LEFT JOIN WorkPicture ON Work.WorkID = WorkPicture.WorkID WHERE Work.StoreID='{$_SESSION["storeid"]}' GROUP BY Work.WorkID, Work.WorkName, Work.WorkPrice ORDER BY WorkID DESC";
// 加上限制顯示筆數的SQL敘述句，由本頁開始記錄筆數開始，每頁顯示預設筆數
$query_limit_RecWork = $query_RecWork . " LIMIT {$startRow_records}, {$pageRow_records}";
// 以加上限制顯示筆數的SQL敘述句查詢資料到 $RecAlbum 中
$RecWork = $db_link->query($query_limit_RecWork);
// 以未加上限制顯示筆數的SQL敘述句查詢資料到 $all_RecAlbum 中
$all_RecWork = $db_link->query($query_RecWork);
// 計算總筆數
$total_records = $all_RecWork->num_rows;
// 計算總頁數=(總筆數/每頁筆數)後無條件進位。
$total_pages = ceil($total_records / $pageRow_records);


?>
<?php

require_once("connectmysql.php");
//建立資料連接
$link = create_connection();
// 搜尋全部店家
$sql99 = "SELECT * FROM store";
$result99 = execute_sql($link, "handstory", $sql99);
$total_records99 = mysqli_num_rows($result99);
$j99 = 1;
//待評價預約數
$StoreID = $_SESSION["storeid"];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width= device-width" />
<link rel="stylesheet" href="style.css" type="text/css">
<title>Hand's Story--作品集管理</title>
	<!--彈出框-->
<link rel="stylesheet" href="remodal.css">
<link rel="stylesheet" href="remodal-default-theme.css">
<link
	href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/themes/hot-sneaks/jquery-ui.css"
	rel="stylesheet">
<style>
h2 {
	padding-top: 25px;
	font-family: "翩翩體-繁";
	font-size: 26px;
	color:#a7a5a7;
	margin: 0px;
	display:inline;
}

.content {
	background-color: #f8f0f8;
}

table {
	font-family: "翩翩體-繁";
	font-size: 18px;
	color:#a7a5a7;
}

.num {
	font-family: "翩翩體-繁";
	font-size: 18px;
	color:#a7a5a7;
}

.buttons {
	margin: 2px;
	
}
.update {
	font-family: "翩翩體-繁";
	font-size: 24px;
	border: none;
	/* 	text-align: center; */
	color: #a7a5a7;
	font-weight:bold;
	text-decoration: none;
}

.buttona {
	margin-left: 5px;
	
}
.pic {
	-webkit-transform: scale(1);
	-webkit-transition: 1s;
	border-radius:5px;
	box-shadow: #efefef 0px 0px 10px 10px;
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
			<li><a href="storest.php?StoreID=<?php echo $StoreID?>" target="_parent">我的頁面</a></li>
			<li><a href="#" target="_parent">店家管理+</a>
				<ul>
					<li><a class="sub" href="store_update.php" target="_parent">修改資料</a></li>
					<li><a class="sub" href="work_manage.php" target="_parent" style="color:#e08fe0;">作品集管理</a></li>
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

//待審核預約
//已經評論的
$sql5 ="SELECT TransactionID FROM Transaction WHERE Transaction.StoreID='$StoreID' AND TransactionYesOrNO='0' AND TransactionCancel='0'";
$result5=execute_sql($link, "handstory", $sql5);
$total_records5=mysqli_num_rows($result5);

echo "<div class='countst1' style='position:absolute;top:97px;left:180px;'>" .$total_records5. "</div>";
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
	<div class="content">
		<table width="1000" border="0" align="center" cellpadding="0"
			cellspacing="20">
			<tr>
				<td colspan="3"><h2 style="margin-left:430px;">作品集管理</h2><a
					class='buttona' href="work_upload.php" style="margin-left:100px;">新增作品</a></td>
			</tr>
			<tr>
				<td align='right' colspan="3">作品總數: <?php echo $total_records;?>
				</td>
			</tr>
           
           <?php
        
        $total_count = ceil($total_records / 3) * 9;
        for ($j = 0; $j < $total_count; $j ++) {
            if ($j % 3 == 0) {
                echo '<tr>';
            }
            if ($row_RecWork = $RecWork->fetch_assoc()) {
                echo '<td align="center" style="width:330px;padding:10px;border:1px #ccc solid;border-radius:5px;">';
                ?>
           <a href="work_update.php?id=<?php echo $row_RecWork["WorkID"];?>"><?php if($row_RecWork["pictureNum"]==0){?><img
				src="workpicture/nopicture.jpg" alt="暫無照片" width="120" height="120"
				border="0" /><?php }else{?><img
				src="workpicture/<?php echo $row_RecWork["PictureURL"];?>" class='pic'
				alt="<?php echo $row_RecWork["PictureName"];?>" width="120"
				height="120" border="0" /><?php }?></a>
			<p>
				<a class="update"
					href="work_update.php?id=<?php echo $row_RecWork["WorkID"];?>"><?php echo $row_RecWork["WorkName"];?></a><br />
				<span class="smalltext">共 <?php echo $row_RecWork["pictureNum"];?> 張</span><br>
				<a class="buttona"
					href="?action=delete&id=<?php echo $row_RecWork["WorkID"];?>"
					class="smalltext" onClick="javascript:return deletesure();">刪除</a><br>
			
			</p>
           
           <?php
                
                echo '</td>';
            } else {
                echo '<td style="width:330px;"></td>';
            }
            if ($j % 3 == 2) {
                echo '</tr>';
            }
        }
        ?>
           <tr>
				<td colspan='3' align="center">
		   <?php if ($num_pages > 1) { // 若不是第一頁則顯示 ?>
             <a class="num" href="?page=1">|&lt;</a> <a
					href="?page=<?php echo $num_pages-1;?>">&lt;&lt;</a>
           <?php }else{?>
             |&lt; &lt;&lt;  
		   <?php }?>
             <?php
            for ($i = 1; $i <= $total_pages; $i ++) {
                if ($i == $num_pages) {
                    echo $i . " ";
                } else {
                    echo "<a class='num' href=\"?page=$i\">$i</a> ";
                }
            }
            ?>
             <?php if ($num_pages < $total_pages) { // 若不是最後一頁則顯示 ?>
             <a class='num' href="?page=<?php echo $num_pages+1;?>">&gt;&gt;</a> <a
					href="?page=<?php echo $total_pages;?>">&gt;|</a>
             <?php }else{?>
             &gt;&gt; &gt;|
			 <?php }?>
           </td>
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
    echo "<a class='const' href=' storest.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
    $j99 ++;
}?>
	</div>
	<!--彈出框-->
	<script src="remodal.js"></script>
	<script>
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
// 釋放記憶體空間
mysqli_free_result($result99);

mysqli_free_result($all_RecWork);
mysqli_free_result($RecWork);
// mysqli_free_result($delpicture);
// 關閉資料連接
mysqli_close($link);
	$db_link->close();
?>