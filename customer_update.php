<?php
require_once ("connMysql.php");
session_start();
// 檢查是否經過登入
if (! isset($_SESSION["loginCustomer"]) || ($_SESSION["loginCustomer"] == "")) {
    header("Location: hcindex.php");
}
// 執行登出動作
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

$sql100 = "SELECT CustomerUse.StoreID,StoreName FROM CustomerUse,store WHERE CustomerUse.CustomerID='$CustomerID' AND CustomerUse.StoreID=store.StoreID ";
$result100 = execute_sql($link, "handstory", $sql100);
$total_records100 = mysqli_num_rows($result100);
$i100 = 1;


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
    }
    return $theValue;
}

// 重新導向頁面
$redirectUrl = "hcindex.php";
// 執行更新動作
if (isset($_POST["action"]) && ($_POST["action"] == "update")) {
    $query_update = "UPDATE Customer SET CustomerPassword=?, CustomerName=?, CustomerBirth=?,
    CustomerPhone=?, CustomerMail=? WHERE CustomerID='{$_SESSION["customerid"]}'";
    $stmt = $db_link->prepare($query_update);
    // 檢查是否有修改密碼
    $mpass = $_POST["passwdo"];
    if (($_POST["passwd"] != "") && ($_POST["passwd"] == $_POST["passwdrecheck"])) {
        $mpass = password_hash($_POST["passwd"], PASSWORD_DEFAULT);
    }
    $stmt->bind_param("sssss", $mpass, GetSQLValueString($_POST["name"], 'string'), GetSQLValueString($_POST["birthday"], 'string'), GetSQLValueString($_POST["phone"], 'string'), GetSQLValueString($_POST["email"], 'email'));
    $stmt->execute();
    mysqli_free_result($stmt);
    $stmt->close();
    // 若有修改密碼則登出回到首頁
    if (($_POST["passwd"] != "") && ($_POST["passwd"] == $_POST["passwdrecheck"])) {
        unset($_SESSION["loginCustomer"]);
        unset($_SESSION["customerid"]);
        $redirectUrl = "hcindex.php";
    }
    header("Location: $redirectUrl");
}
$query_RecMember = "SELECT * FROM Customer WHERE CustomerUsername='{$_SESSION["loginCustomer"]}'";
$RecMember = $db_link->query($query_RecMember);
$row_RecMember = $RecMember->fetch_assoc();

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width= device-width" />
<link rel="stylesheet" href="style.css" type="text/css">
<title>Hand's Story--修改資料</title>
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
	color:#a7a5a7;
	margin: 0px;
}

.content {
	margin: 0px;
	padding: 0px;
	width: 1380px;
	height: 800px;
	background-image: url(images/修改資料背景.jpg);
	opacity: 0.75;
}

.heading {
	margin: 0px;
	padding: 20px;
	font-family: "翩翩體-繁";
	color:#a7a5a7;
	text-align: center;
	font-size: 20px;
}

.context {
	margin: 0px;
	padding: 10px;
	font-family: "翩翩體-繁";
	color:#a7a5a7;
	font-size: 16px;
}

.data {
	position: absolute;
	margin-top: 50px;
	padding-top: 10px;
	margin-left: 400px;
	padding-left: 50px;
	padding-right: 50px;
	background-color: #FFF;
	border-radius:5px;
	opacity: 0.9;
	width: 550px;
	z-index: 1;
}
.normalinput{
    font-family: "翩翩體-繁";
	color:#a7a5a7;
	font-size: 16px;
	border-top:none;
	border-left:none;
	border-right:none;
	border-bottom:solid;
	border-bottom-width:1px;
	border-bottom-color:#0a7a5a7;
	width:200px;
}

.smalltext {
	margin: 0 px;
	padding: 0px;
	font-family: "翩翩體-繁";
	color:#a7a5a7;
	font-size: 12px;
}


.con {
	margin: 0px;
	padding: 0px;
	font-family: "翩翩體-繁";
	font-size: 16px;
	color: #03F;
}
</style>
<script type="text/javascript"
	src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
<script type="text/javascript"
	src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script language="javascript">
function checkForm(){
	if(document.formJoin.passwd.value!="" || document.formJoin.passwdrecheck.value!=""){
		if(!check_passwd(document.formJoin.passwd.value,document.formJoin.passwdrecheck.value)){
			document.formJoin.passwd.focus();
			return false;
		}
	}
	if(document.formJoin.m_email.value!=""){
		if(!checkmail(document.formJoin.m_email)){
			document.formJoin.m_email.focus();
			return false;
		}
	}
	return confirm('確定送出嗎？');
}
function check_passwd(pw1,pw2){
	if(pw1==''){
		alert("密碼不可以空白!");
		return false;
	}
	for(var idx=0;idx<pw1.length;idx++){
		if(pw1.charAt(idx) == ' ' || pw1.charAt(idx) == '\"'){
			alert("密碼不可以含有空白或雙引號 !\n");
			return false;
		}
		if(pw1.length<5 || pw1.length>10){
			alert( "密碼長度只能5到10個字母 !\n" );
			return false;
		}
		if(pw1!= pw2){
			alert("密碼二次輸入不一樣,請重新輸入 !\n");
			return false;
		}
	}
	return true;
}
function checkmail(myEmail) {
	var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(filter.test(myEmail.value)){
		return true;
	}
	alert("電子郵件格式不正確");
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
			<li><a href="customer_update.php" target="_parent" style="color:#e08fe0;">修改資料</a></li>
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

	<div class="content">
		<div class="data">
			<table width="500" border="0" align="center" cellpadding="0"
				cellspacing="0" style="padding:0px 40px;">
				<tr>
					<td class="tdbline"><table width="500" border="0" cellspacing="0"
							cellpadding="10">
							<tr valign="top">
								<td class="tdrline"><form action="" method="POST"
										name="formJoin" id="formJoin" onSubmit="return checkForm();">
										<h2>修改資料</h2>
										<p class="context">
											<strong><?php echo $row_RecMember["CustomerName"];?></strong>
											您好。
										</p>
										<div class="dataDiv">
										

											<p class="context">
												<strong>帳號：    <?php echo $row_RecMember["CustomerUsername"];?></strong>
											</p>
											<p class="context">
												<strong>使用密碼</strong>： <input name="passwd" type="password"
													class="normalinput" id="passwd"> <input name="passwdo"
													type="hidden" id="passwdo"
													value="<?php echo $row_RecMember["CustomerPassword"];?>">
											</p>
											<p class="context">
												<strong>確認密碼</strong> ： <input name="passwdrecheck"
													type="password" class="normalinput" id="passwdrecheck"><br>
												<span class="smalltext">若不修改密碼，請不要填寫。若要修改，請輸入密碼</span><span
													class="smalltext">二次。<br>若修改密碼，系統會自動登出，請用新密碼登入。
												</span>
											</p>
										

											<p class="context">
												<strong>姓 名</strong>： <input name="name" type="text"
													class="normalinput" id="name"
													value="<?php echo $row_RecMember["CustomerName"];?>">
											</p>
											<p class="context">
												<strong>生 日</strong>： <input name="birthday" type="text"
													class="normalinput" id="birthday"
													value="<?php echo $row_RecMember["CustomerBirth"];?>"> <br>
												<span class="smalltext">為西元格式(YYYY-MM-DD)。</span>
											</p>
											<p class="context">
												<strong>手機號碼</strong>： <input name="phone" type="text"
													class="normalinput" id="phone"
													value="<?php echo $row_RecMember["CustomerPhone"];?>">
											</p>
											<p class="context">
												<strong>電子郵件</strong>： <input name="email" type="text"
													class="normalinput" id="email"
													value="<?php echo $row_RecMember["CustomerMail"];?>">
											</p>
										</div>
										<!--  <hr size="1" />-->
										<p align="center">
											<input class="button_r" name="action" type="hidden"
												id="action" value="update"> <input class="buttons"
												type="submit" name="Submit2" value="修改資料"> <input
												class="buttons" type="reset" name="Submit3" value="重設資料"> <input
												class="buttons" type="button" name="Submit" value="回上一頁"
												onClick="window.history.back();">
										</p>
										
									</form></td>

							</tr>
						</table></td>
				</tr>
			</table>
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
// mysqli_free_result($stmt);
// 關閉資料連接
mysqli_free_result($RecMember);
$db_link->close();
?>