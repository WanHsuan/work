<?php
require_once ("connMysql.php");
session_start();

if (isset($_SESSION["loginCustomer"]) && ($_SESSION["loginCustomer"] != "")) {
    header("Location:hcindex.php");
}
if (isset($_SESSION["loginStore"]) && ($_SESSION["loginStore"] != "")) {
    header("Location: hstindex.php");
}

if (isset($_POST["cusername"]) && isset($_POST["cpasswd"])) {
    $query_RecCustomer = "SELECT CustomerUsername, CustomerPassword, CustomerID FROM Customer WHERE CustomerUsername=?";
    $stmt = $db_link->prepare($query_RecCustomer);
    $stmt->bind_param("s", $_POST["cusername"]);
    $stmt->execute();
    // 取出帳號密碼值綁定結果
    $stmt->bind_result($username, $passwd, $id);
    $stmt->fetch();
    $stmt->close();
    // 比對密碼，若成功則顯示登入狀態
    if (password_verify($_POST["cpasswd"], $passwd)) {
        // 設定登入者的名稱及ID
        $_SESSION["loginCustomer"] = $username;
        $_SESSION["customerid"] = $id;
        
        if (isset($_POST["rememberme"]) && ($_POST["rememberme"] == "true")) {
            setcookie("remUser", $_POST["cusername"], time() + 365 * 24 * 60);
            setcookie("remPass", $_POST["cpasswd"], time() + 365 * 24 * 60);
        } else {
            if (isset($_COOKIE["remUser"])) {
                setcookie("remUser", $_POST["cusername"], time() - 100);
                setcookie("remPass", $_POST["cpasswd"], time() - 100);
            }
        }
        
        header("Location: hcindex.php");
    } else {
        header("Location: login.php?errMsg=1");
    }
}
if (isset($_POST["susername"]) && isset($_POST["spasswd"])) {
    $query_RecLogin = "SELECT StoreUsername, StorePassword, StoreID FROM store WHERE StoreUsername=?";
    $stmt = $db_link->prepare($query_RecLogin);
    $stmt->bind_param("s", $_POST["susername"]);
    $stmt->execute();
    
    $stmt->bind_result($account, $passwd, $id);
    $stmt->fetch();
    $stmt->close();
    
    if (password_verify($_POST["spasswd"], $passwd)) {
        // 設定登入者的名稱和ID
        $_SESSION["loginStore"] = $account;
        $_SESSION["storeid"] = $id;
        
        if (isset($_POST["rememberme"]) && ($_POST["rememberme"] == "true")) {
            setcookie("remUser", $_POST["susername"], time() + 365 * 24 * 60);
            setcookie("remPass", $_POST["spasswd"], time() + 365 * 24 * 60);
        } else {
            if (isset($_COOKIE["remUser"])) {
                setcookie("remUser", $_POST["susername"], time() - 100);
                setcookie("remPass", $_POST["spasswd"], time() - 100);
            }
        }
        header("Location: hstindex.php");
    } else {
        header("Location: login.php?errMsg=1");
    }
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="style.css" type="text/css">
	<!--彈出框-->
	<link rel="stylesheet" href="remodal.css">
		<link rel="stylesheet" href="remodal-default-theme.css">
<title>Hand's Story--會員登入</title>
<link
	href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/themes/hot-sneaks/jquery-ui.css"
	rel="stylesheet">
	<script type="text/javascript"
		src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script type="text/javascript"
		src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
        $("#m").click(function(){
			$("#m").css({"color":"#e08fe0"});
			$("#s").css({"color":" #999"});
			$("#store").hide();
			$("#mem").show();
			$("#store").hide();
		});
		 $("#s").click(function(){
			 $("#m").css({"color":" #999"});
			$("#s").css({"color":"#e08fe0"});
			$("#mem").hide();
			$("#store").show();
		});
		
    });
	</script>
<style>
#apDiv1 {
	position: absolute;
	left: 800px;
	top: 130px;
	width: 480px;
	height: 550px;
	z-index: 1;
	background-color: #FFF;
	opacity: 0.9;
}

#apDiv1 h1 {
	margin: 0px;
	padding: 15px;
	font-family: "翩翩體-繁";
	color: #a7a5a7;
	text-align: center;
	font-size: 24px;
}

.lbutton {
	font-weight: bolder;
	color: #a7a5a7;
	font-size: 18px;
	background-color: none;
	font-family: "翩翩體-繁";
	margin: 0px;
	padding: 0px;
	overflow: hidden; /* 超過範圍隱藏 */
	white-space: nowrap; /* 不斷行 */
}

.lbutton li {
	float: left;
	text-align: center;
	list-style-type: none;
	background-color: none;
	letter-spacing: 2px;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #CCC;
}

#s {
	border-left-width: 1px;
	border-left-style: solid;
	border-left-color: #CCC;
}

.lbutton li a {
	/* 連結範圍充滿整個區塊 */
	display: block;
	/* 單個連結範圍的框度和高度*/
	width: 238px;
	height: 32px;
	/* 連結的文字設為白色以及無裝飾(無底線)*/
	color: #999;
	text-decoration: none;
}

.lbutton li a:hover {
	font-size: 20px;
}

#m {
	color: #e08fe0;
}

#store {
	display: none;
}

.lo {
	margin-top: 20px;
	padding: 20px;
}

.heading {
	margin: 0px;
	padding: 0px;
	font-family: "翩翩體-繁";
	color: #a7a5a7;
	text-align: center;
	font-size: 22px;
}

.content {
	margin: 0px;
	padding: 0px;
	font-family: "翩翩體-繁";
	color: #a7a5a7;
	font-size: 16px;
}

.con {
	margin: 0px;
	padding: 0px;
	font-family: "翩翩體-繁";
	font-size: 16px;
	color: #03F;
}



.errDiv {
	margin: 0px;
	padding: 0px;
	font-family: "翩翩體-繁";
	font-size: 14px;
	color: #C00;
}

.remodal {
	background-color: #f8f0f8;
	font-family: "翩翩體-繁";
	color: #a7a5a7;
	font-size: 16px;
}
</style>
<script type="text/javascript">
function checkForm() {
	if(document.form1.account.value==""){		
		alert("請填寫帳號!");
		document.form1.account.focus();
		return false;}
	if(document.form1.email.value==""){
		alert("請填寫電子郵件!");
		document.form1.email.focus();
		return false;}
	if(!checkmail(document.form1.email)){
		document.form1.email.focus();
		return false;}
}
function checkmail(myEmail) {
	var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(filter.test(myEmail.value)){
		return true;}
	alert("電子郵件格式不正確");
	return false;
}
</script>
</head>

<body>
	<?php if(isset($_GET["loginStats"]) && ($_GET["loginStats"]=="1")){?>
	<script language="javascript">
	alert('請登入會員。');
	window.location.href='login.php';	
	</script>
	<?php }?>
	<?php if(isset($_GET["loginStats"]) && ($_GET["loginStats"]=="2")){?>
	<script language="javascript">
	alert('請登入店家會員。');
	window.location.href='login.php';	
	</script>
	<?php }?>
	<!--登入框-->
	<div id="apDiv1">
		<div id="lmenu">
			<ul class="lbutton">
				<li><a id="m" href="#mem">一般會員</a></li>
				<li><a id="s" href="#store">店家</a></li>
			</ul>
		</div>
		<div class="tab_container">
			<div id="mem" class="tab_content">
				<h1>一般會員登入</h1>
				<div class="lo">
					<table width="440" border="0" align="center" cellpadding="0"
						cellspacing="0">
						<tr>
							<td width="200px">
								<div class="boxtl"></div>
								<div class="boxtr"></div>
								<div class="regbox"><?php if(isset($_GET["errMsg"]) && ($_GET["errMsg"]=="1")){?>
		<div class="errDiv">帳號密碼錯誤</div>
		<?php }?>
		
								<form name="form1" method="post" action="">
										<p class="content">
											帳號： <br> <input name="cusername" type="text"
												class="logintextbox" id="cusername"
												value="<?php if(isset($_COOKIE["remUser"]) && ($_COOKIE["remUser"]!="")) echo $_COOKIE["remUser"];?>">
										</p>
										<p class="content">
											密碼：<br> <input name="cpasswd" type="password"
												class="logintextbox" id="cpasswd"
												value="<?php if(isset($_COOKIE["remPass"]) && ($_COOKIE["remPass"]!="")) echo $_COOKIE["remPass"];?>">
										</p>
										<p class="content">
											<input name="rememberme" type="checkbox" id="rememberme"
												value="true"> 記住我的帳號密碼。
										</p>
										<p align="center">
											<input type="submit" name="button" class="buttons"
												value="登入會員">
										</p>
									</form>
									<p align="center">
										<a class="con" href="#modal">忘記密碼</a>
									</p>
									<hr size="1" />
									<p class="heading">還不是會員？</p>
									<p align="right">
										<a class="con" href="customer_join.php">加入會員</a>
									</p>
								</div>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div id="store" class="tab_content">
				<h1>店家登入</h1>
				<div class="lo">
					<table width="440" border="0" align="center" cellpadding="0"
						cellspacing="0">
						<tr>
							<td width="200px">
								<div class="boxbl"></div>
								<div class="boxbr"></div>

								<div class="regbox"><?php if(isset($_GET["errMsg"]) && ($_GET["errMsg"]=="1")){?>
		<div class="errDiv">帳號密碼錯誤</div>
		<?php }?>
		
							<form name="form1" method="POST" action="">
										<p class="content">
											帳號： <br> <input name="susername" type="text"
												class="logintextbox" id="susername"
												value="<?php if(isset($_COOKIE["remUser"]) && ($_COOKIE["remUser"]!="")) echo $_COOKIE["remUser"];?>">
										</p>
										<p class="content">
											密碼：<br> <input name="spasswd" type="password"
												class="logintextbox" id="spasswd"
												value="<?php if(isset($_COOKIE["remPass"]) && ($_COOKIE["remPass"]!="")) echo $_COOKIE["remPass"];?>">
										</p>
										<p class="content">
											<input name="rememberme" type="checkbox" id="rememberme"
												value="true"> 記住我的帳號密碼
										</p>
										<p align="center">
											<input type="submit" name="button" class="buttons"
												value="登入店家">
										</p>
									</form>
									<p align="center">
										<a class="con" href="#modal1">忘記密碼</a>
									</p>
									<hr size="1" />
									<p class="heading">還不是店家會員？</p>
									<p align="right">
										<a class="con" href="store_join.php">加入店家會員</a>
									</p>
								</div>
								<div class="boxbl"></div>
								<div class="boxbr"></div>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	<!--頁首-->
	<header>
		<img src="images/登入後LOGO.fw.png" width="1380" usemap="#Map" border="0" />
		<map name="Map" id="Map">
			<area shape="rect" coords="128,15,477,82" href="hindex.php"
				target="_parent" />
		</map>
	</header>
	<!--登入畫面背景-->
	<div class="login">
		<img src="images/登入背景.jpg" width="1380" style="opacity: 0.9" />
	</div>
<!--彈出框-->
	<div class="remodal" data-remodal-id="modal">
		<button data-remodal-action="close" class="remodal-close"></button>
		
<table width="600" border="0" align="center" cellpadding="4" cellspacing="0">

<tr><td>忘記密碼？</td></tr>
<form name="form1" method="post" action="customer_passmail.php">
<tr><td>請輸入您申請的帳號，系統將自動產生一個十位數的密碼寄到您註冊的信箱。</td></tr>
<tr><td><strong>帳號</strong>：
<input name="account" type="text" class="logintextbox" id="mail"></td></tr>
<tr align="center"><td>
<input type="submit" name="button" class="buttons" value="寄密碼信">
</td></tr>

</form>

</table>

	</div>
	<!--彈出框-->
	<div class="remodal" data-remodal-id="modal1">
		<button data-remodal-action="close" class="remodal-close"></button>
		<table width="600" border="0" align="center" cellpadding="4" cellspacing="0">
<tr><td>忘記密碼？</td></tr>
<form name="form1" method="POST" action="store_passmail.php" id="form1" onSubmit="return checkForm();">
<tr><td>請輸入您的帳號及信箱，系統將自動產生一個十位數的密碼寄到您輸入的信箱。</td></tr>
<tr><td><strong>帳號</strong>：
<input name="account" type="text" class="logintextbox" id="account"/></td></tr>
<tr><td>
<strong>信箱</strong>：
<input name="email" type="text" class="logintextbox" id="email"></td></tr>
<tr><td align='center'>
<input type="submit" name="button" class="buttons" value="寄密碼信">

</td></tr>
</form>

</table>

	</div>

<!--彈出框-->
	<script src="remodal.js"></script>
</body>
</html>
<?php
// mysqli_free_result($stmt);

$db_link->close();
?>