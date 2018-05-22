
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
?><?php

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
require_once ("connMysql.php");
session_start();
// 檢查是否經過登入
if (! isset($_SESSION["loginStore"]) || ($_SESSION["loginStore"] == "")) {
    header("Location: login.php");
}
// 執行登出動作
if (isset($_GET["logout"]) && ($_GET["logout"] == "true")) {
    unset($_SESSION["loginStore"]);
    unset($_SESSION["storeid"]);
    header("Location: login.php");
}
// 重新導向頁面
$redirectUrl = "hstindex.php";
// 執行更新動作
if (isset($_POST["action"]) && ($_POST["action"] == "update")) {
    $query_update = "UPDATE store SET StorePassword=?, StoreName=?, StoreAddressCity=?,
    StoreAddressDistriction=?, StoreAddress=?, StoreStory=?, StorePhone=?, StoreStyle=?, 
    StoreFBName=?, StoreFBAddrss=?, StoreIGName=?, StoreIGAddress=?, StorePriceSection=?, 
    StoreRate=?, StoreLineID=?, StoreOffday=?, StoreWorkingtime=? WHERE StoreID='{$_SESSION["storeid"]}'";
    $stmt = $db_link->prepare($query_update);
    // 檢查是否有修改密碼
    $mpass = $_POST["passwdo"];
    if (($_POST["passwd"] != "") && ($_POST["passwd"] == $_POST["passwdrecheck"])) {
        $mpass = password_hash($_POST["passwd"], PASSWORD_DEFAULT);
    }
    $stmt->bind_param("sssssssssssssssss", $mpass, GetSQLValueString($_POST["name"], 'string'), GetSQLValueString($_POST["city"], 'string'), GetSQLValueString($_POST["distriction"], 'string'), GetSQLValueString($_POST["address"], 'string'), GetSQLValueString($_POST["story"], 'string'), GetSQLValueString($_POST["phone"], 'string'), GetSQLValueString($_POST["style"], 'string'), GetSQLValueString($_POST["fbname"], 'string'), GetSQLValueString($_POST["fburl"], 'string'), GetSQLValueString($_POST["igname"], 'string'), GetSQLValueString($_POST["igurl"], 'string'), GetSQLValueString($_POST["price"], 'string'), GetSQLValueString($_POST["rate"], 'string'), GetSQLValueString($_POST["lineid"], 'string'), GetSQLValueString($_POST["offday"], 'string'), GetSQLValueString($_POST["workingtime"], 'string'));
    $stmt->execute();
    $stmt->close();
    // 若有修改密碼則登出回到首頁
    if (($_POST["passwd"] != "") && ($_POST["passwd"] == $_POST["passwdrecheck"])) {
        unset($_SESSION["loginStore"]);
        unset($_SESSION["storeid"]);
        $redirectUrl = "login.php";
    }
    header("Location: $redirectUrl");
}
$query_RecMember = "SELECT * FROM store WHERE StoreUsername='{$_SESSION["loginStore"]}'";
$RecMember = $db_link->query($query_RecMember);
$row_RecMember = $RecMember->fetch_assoc();
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
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width= device-width" />
<link rel="stylesheet" href="style.css" type="text/css">
<title>Hand's Story--修改資料</title>
<style>
h2 {
	padding-top: 25px;
	text-align: center;
	font-family: "翩翩體-繁";
	font-size: 26px;
	color: #a7a5a7;
	margin: 0px;
}

.content {
	margin: 0px;
	padding: 0px;
	width: 1380px;
	height: 800px;
}

.heading {
	margin: 0px;
	padding: 20px;
	font-family: "翩翩體-繁";
	color: #a7a5a7;
	text-align: center;
	font-size: 20px;
}

.context {
	margin: 0px;
	padding: 10px;
	font-family: "翩翩體-繁";
	color: #a7a5a7;
	font-size: 16px;
}

.data {
	position: absolute;
	margin-top: 50px;
	padding-top: 10px;
	margin-left: 400px;
	padding-left: 50px;
	padding-right: 50px;
	z-index: 1;
	background-color: #FFF;
	opacity: 0.9;
	border-radius:5px;
	width: 550px;
	z-index: 1;
}

.normalinput {
	font-family: "翩翩體-繁";
	color: #a7a5a7;
	font-size: 16px;
	border-top: none;
	border-left: none;
	border-right: none;
	border-bottom: solid;
	border-bottom-width: 1px;
	border-bottom-color: #a7a5a7;
	width: 200px;
}

.normalinput1 {
	font-family: "翩翩體-繁";
	color: #a7a5a7;
	font-size: 16px;
	border-top: none;
	border-left: none;
	border-right: none;
	border-bottom: solid;
	border-bottom-width: 1px;
	border-bottom-color: #a7a5a7;
	width: 50px;
}

.smalltext {
	margin: 0 px;
	padding: 0px;
	font-family: "翩翩體-繁";
	color: #a7a5a7;
	font-size: 12px;
}


.con {
	margin: 0px;
	padding: 0px;
	font-family: "翩翩體-繁";
	font-size: 16px;
	color: #03F;
}

.remodal {
	background-color: #f8f0f8;
	font-family: "翩翩體-繁";
	color: #a7a5a7;
	font-size: 12px;
}
</style>
<!--彈出框-->
<link rel="stylesheet" href="remodal.css">
<link rel="stylesheet" href="remodal-default-theme.css">
<link
	href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/themes/hot-sneaks/jquery-ui.css"
	rel="stylesheet">
<script type="text/javascript"
	src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript"
	src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>

<script language="javascript">
function checkForm(){
	if(document.formJoin.passwd.value!="" || document.formJoin.passwdrecheck.value!=""){
		if(!check_passwd(document.formJoin.passwd.value,document.formJoin.passwdrecheck.value)){
			document.formJoin.passwd.focus();
			return false;
		}
		return confirm('確定送出嗎？');
	}
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
					<li><a class="sub" href="store_update.php" target="_parent" style="color:#e08fe0;">修改資料</a></li>
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
		<div class="data">
			<table width="550" border="0" align="center" cellpadding="4"
				cellspacing="0">
				<tr>
					<td class="tdbline"><table width="550" border="0" cellspacing="0"
							cellpadding="10">
							<tr valign="top">
								<td class="tdrline"><form action="" method="POST"
										name="formJoin" id="formJoin" onSubmit="return checkForm();">
										<h2>修改資料</h2>
										<div class="dataDiv">
											
											<p class="context">
												<strong>帳號</strong>：<?php echo $row_RecMember["StoreUsername"];?></p>
											<p class="context">
												<strong>使用密碼</strong>： <input name="passwd" type="password"
													class="normalinput" id="passwd"> <input name="passwdo"
													type="hidden" id="passwdo"
													value="<?php echo $row_RecMember["StorePassword"];?>">
											</p>
											<p class="context">
												<strong>確認密碼</strong> ： <input name="passwdrecheck"
													type="password" class="normalinput" id="passwdrecheck"><br>
												<span class="smalltext">若不修改密碼，請不要填寫。若要修改，請輸入密碼</span><span
													class="smalltext">二次。<br>若修改密碼，系統會自動登出，請用新密碼登入。
												</span>
											</p>
											
											<p class="context">
												<strong>店名</strong>： <input name="name" type="text"
													class="normalinput" id="name"
													value="<?php echo $row_RecMember["StoreName"];?>">
											</p>
											<p class="context">
												<strong>地址</strong>： <input name="city" type="text"
													class="normalinput1" id="city"
													value="<?php echo $row_RecMember["StoreAddressCity"];?>"> <input
													name="distriction" type="text" class="normalinput1"
													id="distriction"
													value="<?php echo $row_RecMember["StoreAddressDistriction"];?>">
												<input name="address" type="text" class="normalinput"
													id="address"
													value="<?php echo $row_RecMember["StoreAddress"];?>">
											</p>
											<p class="context">
												<strong>關於</strong>：
												<textarea name="story" id="story"
													style="width: 100px; height: 30px; font-family: '翩翩體-繁'; font-size: 16px;border:1px #a7a5a7 solid;"><?php echo $row_RecMember["StoreStory"];?></textarea>
											</p>
											<p class="context">
												<strong>聯絡電話</strong>： <input name="phone" type="text"
													class="normalinput" id="phone"
													value="<?php echo $row_RecMember["StorePhone"];?>">
											</p>
											<p class="context">
												<strong>主打的美甲風格</strong>： <input name="style" type="text"
													class="normalinput" id="style"
													value="<?php echo $row_RecMember["StoreStyle"];?>">
											</p>
											<p class="context">
												<strong>臉書名稱</strong>： <input name="fbname" type="text"
													class="normalinput" id="fbname"
													value="<?php echo $row_RecMember["StoreFBName"];?>">
											</p>
											<p class="context">
												<strong>臉書網址</strong>： <input name="fburl" type="text"
													class="normalinput" id="fburl"
													value="<?php echo $row_RecMember["StoreFBAddrss"];?>">
											</p>
											<p class="context">
												<strong>IG名稱</strong>： <input name="igname" type="text"
													class="normalinput" id="igname"
													value="<?php echo $row_RecMember["StoreIGName"];?>">
											</p>
											<p class="context">
												<strong>IG網址</strong>： <input name="igurl" type="text"
													class="normalinput" id="igurl"
													value="<?php echo $row_RecMember["StoreIGAddress"];?>">
											</p>
											<p class="context">
												<strong>產品價格區間</strong>： <input name="price" type="text"
													class="normalinput" id="price"
													value="<?php echo $row_RecMember["StorePriceSection"];?>">
											</p>
											<p class="context">
												<strong>店家評價星等</strong>： <input name="rate" type="text"
													class="normalinput" id="rate"
													value="<?php echo $row_RecMember["StoreRate"];?>">
											</p>
											<p class="context">
												<strong>Line ID</strong>: <input name="lineid" type="text"
													class="normalinput" id="lineid"
													value="<?php echo $row_RecMember["StoreLineID"];?>">
											</p>
											<p class="context">
												<strong>休息日</strong>： <input name="offday" type="text"
													class="normalinput" id="offday"
													value="<?php echo $row_RecMember["StoreOffday"];?>">
											</p>
											<p class="context">
												<strong>營業時間</strong>： <input name="workingtime" type="text"
													class="normalinput" id="workingtime"
													value="<?php echo $row_RecMember["StoreWorkingtime"];?>">
											</p>
											<div>
												<p class="context">
													<strong>店家照片</strong>： <img
														src="storephoto/<?php echo $row_RecMember["StorePhoto"];?>"
														alt="店家照片" width="120" height="120" border="0" /> <a
														href="#modal">更改照片</a>
												</p>
											</div>
										</div>
										
										<p align="center">
											<input name="action" class="button_r" type="hidden"
												id="action" value="update"> <input type="submit"
												class="buttons" name="Submit2" value="修改資料"> <input
												type="reset" class="buttons" name="Submit3" value="重設資料"> <input
												type="button" class="buttons" name="Submit" value="回上一頁"
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
	<div class="remodal" data-remodal-id="modal">
		<button data-remodal-action="close" class="remodal-close"></button>
		<form action="storephoto_update.php" method="POST" name="form1"
			id="form1" enctype="multipart/form-data">
			<p class="title">修改照片</p>
			<div class="dataDiv">
				<hr size="1" />
				<div class="picDiv">
					<img src="storephoto/<?php echo $row_RecMember["StorePhoto"];?>"
						alt=" " width="120" height="120" border="0" />
				</div>
				<div class="photpDiv">
					<p class="heading">新增照片</p>
					<p>
						<input type="file" name="photo" id="photo" />
					</p>
					<p>
						<input name="action" type="hidden" id="action" value="update"> <input
							type="submit" name="button" class="buttons" value="確定修改" /> <input
							type="button" name="button2" class="buttons" value="回上一頁"
							onClick="window.history.back()" />
					</p>
				</div>
			</div>
		</form>

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
mysqli_free_result($result99);
mysqli_free_result($RecMember);
// mysqli_free_result($stmt);

$db_link->close();
?>