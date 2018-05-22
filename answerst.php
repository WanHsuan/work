<?php
require_once ("connMysql.php");
session_start();
function GetSQLValueString($theValue, $theType)
{
    switch ($theType) {
        case "string":
            $theValue = ($theValue != "") ? filter_var($theValue, FILTER_SANITIZE_MAGIC_QUOTES) : "";
            break;
        case "int":
            $theValue = ($theValue != "") ? filter_var($theValue, FILTER_SANITIZE_NUMBER_INT) : "";
            break;
    }
    return $theValue;
}

// 檢查是否登入
if ((! isset($_SESSION["loginCustomer"])) && (! isset($_SESSION["loginStore"]))) {
    header("Location: login.php");
    
}

if (isset($_POST["action"]) && ($_POST["action"] == "add")) {
    if (isset($_SESSION["loginStore"]) || ($_SESSION["loginStore"] != "")) {
        $query_insert = "INSERT INTO Answer (QuestionID, AnswerContent, AnswerDateTime, StoreID) VALUES (?, ?, NOW(), ?)";
        $stmt = $db_link->prepare($query_insert);
        $stmt->bind_param("ssi", GetSQLValueString($_GET["id"], "string"), GetSQLValueString($_POST["content"], "string"), GetSQLValueString($_SESSION["storeid"], "int"));
        $stmt->execute();
        $stmt->close();
        // 重新導向回到主畫面
        header("Location: groupst.php");
    } elseif (isset($_SESSION["loginCustomer"]) || ($_SESSION["loginCustomer"] != "")) {
        $query_insert = "INSERT INTO AnswerCustomer (QuestionID, AnswerContent, AnswerDateTime, CustomerID) VALUES (?, ?, NOW(), ?)";
        $stmt = $db_link->prepare($query_insert);
        $stmt->bind_param("ssi", GetSQLValueString($_GET["id"], "string"), GetSQLValueString($_POST["content"], "string"), GetSQLValueString($_SESSION["customerid"], "int"));
        $stmt->execute();
        $stmt->close();
        header("Location: group.php");
    }
}
// 檢查是否經過登入
if (! isset($_SESSION["loginStore"]) || ($_SESSION["loginStore"] == "")) {
    header("Location: login.php");
}
// 執行登出
if (isset($_GET["logout"]) && ($_GET["logout"] == "true")) {
    unset($_SESSION["loginStore"]);
    unset($_SESSION["storeid"]);
    header("Location: login.php");
}

require_once("connectmysql.php");

$Arr2=array();
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
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width= device-width" />
<link rel="stylesheet" href="style.css" type="text/css">
	<script type="text/javascript"
		src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<!--彈出框-->
<link rel="stylesheet" href="remodal.css">
<link rel="stylesheet" href="remodal-default-theme.css">
<link
	href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/themes/hot-sneaks/jquery-ui.css"
	rel="stylesheet">
			<style type="text/css">
#apDiv4 {
	position: absolute;
	left: 400px;
	top: 150px;
	width: 584px;
	height: 519px;
	z-index: 1;
}

h2 {
	font-size: 30px;
	color:#a7a5a7;
	font-family: "翩翩體-繁";
	text-align: center;
	margin-buttom: 20px;
	padding-top: 20px;
}

p {
	font-family: "翩翩體-繁";
	font-size: 22px;
	color:#a7a5a7;
}

h1 {
	padding-top: 25px;
	text-align: center;
	font-family: "翩翩體-繁";
	font-size: 26px;
	color:#a7a5a7;
	margin: 0px;
}

table {
	font-family: "翩翩體-繁";
	font-size: 20px;
	color:#a7a5a7;
}


</style>
			<title>Hand's Story--我要回答</title>
			<script type="text/javascript"
	src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
<script type="text/javascript"
	src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	
			 <script language="javascript">
function checkForm(){	 
	if(document.formPost.content.value==""){
		alert("請填寫回覆內容!");
		document.formPost.content.focus();
		return false;
	}
		return confirm('確定送出嗎？');
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
mysqli_free_result($result5);
echo "<div class='countst1' style='position:absolute;top:97px;left:180px;'>" .$total_records5. "</div>";
// echo "<div class='count'>" . $total . "</div>";
?>
			</a></li>
			<li><a id="st"">美甲店</a></li>
			<li><a href="searchpartialstorest.php" target="_parent">美甲地圖</a></li>
			<li><a class='focus' href="groupst.php" target="_parent">論壇</a></li>
			<li><a href="#" target="_parent">我的賣場<?php 

// $link = create_connection();
//已經評論的
$sql5 ="SELECT ShoppingRecordID FROM ShoppingRecord WHERE ShoppingRecordProcessing='處理中' AND StoreID='$StoreID'";
$result5=execute_sql($link, "handstory", $sql5);
$total_records5=mysqli_num_rows($result5);
mysqli_free_result($result5);
echo "<div class='countst1' style='position:absolute;top:97px;left:1300px;'>" .$total_records5. "</div>";

?></a>
				<ul>
					<li><a class="sub" href="product_show.php" target="_parent">上傳/下架商品</a></li>
					<li><a class="sub" href="shoppingrecord_search_store.php" target="_parent">訂單管理</a></li>
				</ul></li>
		</ul>
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
	<!--提問背景-->
	<div class="question">
		<img src="images/我要提問背景.jpg" width="1380" />
		<div id="apDiv4">
			<h2>我要回答</h2>

			<form action="" method="post" name="formPost" id="formPost"
				onSubmit="return checkForm();">
				<table width="800" border="0" align="center" cellpadding="10"
					cellspacing="0">
					<tr valign="top">

						<td><textarea name="content"
								style="margin-left: 50px; width: 470px; height: 200px; border: 1px #dcd8dc solid; border-radius: 5px;"></textarea>
						</td>
					</tr>
					<tr>
						<td style="padding-left: 200px; padding-top: 50px;"><input
							name="action" type="hidden" id="action" value="add"> <input
								type="submit" name="button" class="buttons" value="送出"> <input
									type="reset" name="button2" class="buttons" value="清空"> <input
										type="button" name="button3" class="buttons" value="回上一頁"
										onClick="window.history.back();"></td>
					</tr>
				</table>
			</form>

		</div>
	</div>
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
// mysqli_free_result($stmt);


// 關閉資料連接
mysqli_close($link);


?>