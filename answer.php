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
// session_start();
// 檢查是否登入
// if ((! isset($_SESSION["loginCustomer"])) && (! isset($_SESSION["loginStore"]))) {
//     header("Location: login.php");

// }
// echo $_GET["id"];
// echo $_SESSION["storeid"];

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


// require_once ("connMysql.php");

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


require_once("connectmysql.php");

$CustomerID=$_SESSION["customerid"];

$Arr2=array();
//建立資料連接
$link = create_connection();

// 搜尋全部店家
$sql99 = "SELECT * FROM store";
$result99 = execute_sql($link, "handstory", $sql99);
$total_records99 = mysqli_num_rows($result99);
$j99 = 1;

// 搜尋常用店家

$sql100 = "SELECT CustomerUse.StoreID,StoreName FROM CustomerUse,store WHERE CustomerUse.CustomerID='$CustomerID' AND CustomerUse.StoreID=store.StoreID ";
$result100 = execute_sql($link, "handstory", $sql100);
$total_records100 = mysqli_num_rows($result100);
$i100 = 1;
?>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width= device-width" />
<link rel="stylesheet" href="style.css" type="text/css">
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
	color: #a7a5a7;
	font-family: "翩翩體-繁";
	text-align: center;
	margin-buttom: 20px;
	padding-top: 20px;
}

p {
	font-family: "翩翩體-繁";
	font-size: 22px;
	color: #a7a5a7;
}

h1 {
	padding-top: 25px;
	text-align: center;
	font-family: "翩翩體-繁";
	font-size: 26px;
	color: #a7a5a7;
	margin: 0px;
}

table {
	font-family: "翩翩體-繁";
	font-size: 20px;
	color: #a7a5a7;
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
			<li><a class="focus" href="group.php" target="_parent">論壇</a></li>
			<li><a href="product_showall_customer.php" target="_parent">銷售平台</a></li>
		</ul>
	</div>

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
mysqli_free_result($stmt);
// 關閉資料連接
mysqli_close($link);




?>
