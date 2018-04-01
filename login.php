<?php
require_once ("connMysql.php");
session_start();

if(isset($_SESSION["loginCustomer"]) && ($_SESSION["loginCustomer"] != "")){
    header("Location: customer_center.php");
}
if(isset($_SESSION["loginStore"]) && ($_SESSION["loginStore"] != "")){
    header("Location: store_center.php");
}

if(isset($_POST["cusername"]) && isset($_POST["cpasswd"])){
    $query_RecCustomer = "SELECT CustomerUsername, CustomerPassword, CustomerID FROM Customer WHERE CustomerUsername=?";
    $stmt=$db_link->prepare($query_RecCustomer);
    $stmt->bind_param("s", $_POST["cusername"]);
    $stmt->execute();
    //取出帳號密碼值綁定結果
    $stmt->bind_result($username, $passwd, $id);
    $stmt->fetch();
    $stmt->close();
    //比對密碼，若成功則顯示登入狀態
    if(password_verify($_POST["cpasswd"],$passwd)){
        //設定登入者的名稱及ID
        $_SESSION["loginCustomer"] = $username;
        $_SESSION["customerid"] = $id;
        
        if(isset($_POST["rememberme"]) && ($_POST["rememberme"]=="true")){
            setcookie("remUser", $_POST["cusername"], time()+365*24*60);
            setcookie("remPass", $_POST["cpasswd"], time()+365*24*60);
        }else{
            if(isset($_COOKIE["remUser"])){
                setcookie("remUser", $_POST["cusername"], time()-100);
                setcookie("remPass", $_POST["cpasswd"], time()-100);
            }
        }
        
        header("Location: customer_center.php");
    }else {
        header("Location: login.php?errMsg=1");
    }
}
if(isset($_POST["susername"]) && isset($_POST["spasswd"])){
    $query_RecLogin = "SELECT StoreUsername, StorePassword, StoreID FROM store WHERE StoreUsername=?";
    $stmt=$db_link->prepare($query_RecLogin);
    $stmt->bind_param("s", $_POST["susername"]);
    $stmt->execute();
    
    $stmt->bind_result($account, $passwd, $id);
    $stmt->fetch();
    $stmt->close();
    
    if(password_verify($_POST["spasswd"], $passwd)){
        //設定登入者的名稱和ID
        $_SESSION["loginStore"] = $account;
        $_SESSION["storeid"] = $id;
        
        if(isset($_POST["rememberme"]) && ($_POST["rememberme"]=="true")){
            setcookie("remUser", $_POST["susername"], time()+365*24*60);
            setcookie("remPass", $_POST["spasswd"], time()+365*24*60);
        }else{
            if(isset($_COOKIE["remUser"])){
                setcookie("remUser", $_POST["susername"], time()-100);
                setcookie("remPass", $_POST["spasswd"], time()-100);
            }
        }
        header("Location: store_center.php");
    }else {
        header("Location: login.php?errMsg=1");
    }
}
?>
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Handstory會員系統</title>
		<link href="style.css" rel="stylesheet" type="text/css">
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
	<table width="780" border="0" align="center" cellpadding="4" cellspacing="0">
	<tr>
		<td width="200px">
	<div class="boxtl"></div><div class="boxtr"></div>
<div class = "regbox"><?php if(isset($_GET["errMsg"]) && ($_GET["errMsg"]=="1")){?>
		<div class="errDiv"> 帳號密碼錯誤</div>
		<?php }?>
		<p class="heading"> 登入會員</p>
		<form name="form1" method="post" action="">
            <p>帳號：
              <br>
              <input name="cusername" type="text" class="logintextbox" id="cusername" value="<?php if(isset($_COOKIE["remUser"]) && ($_COOKIE["remUser"]!="")) echo $_COOKIE["remUser"];?>">
            </p>
            <p>密碼：<br>
              <input name="cpasswd" type="password" class="logintextbox" id="cpasswd" value="<?php if(isset($_COOKIE["remPass"]) && ($_COOKIE["remPass"]!="")) echo $_COOKIE["remPass"];?>">
            </p>
            <p>
              <input name="rememberme" type="checkbox" id="rememberme" value="true">
記住我的帳號密碼。</p>
		<p align="center">
			<input type="submit" name="button" id="button" value="登入會員">
		</p>
		</form>
		<p align="center"><a href="customer_passmail.php">忘記密碼</a></p>
		<hr size="1" />
		<p class="heading">還不是會員？</p>
		<p align="right"><a href ="customer_join.php">加入會員</a></p>
	</div>
	<div class="boxbl"></div><div class="boxbr"></div></td>
			<p class="heading"> 登入店家會員</p>
		<form name="form1" method="POST" action="">
			<p>帳號：
				<br>
			<input name="susername" type="text" class="logintextbox" id="susername" value="<?php if(isset($_COOKIE["remUser"]) && ($_COOKIE["remUser"]!="")) echo $_COOKIE["remUser"];?>">
			</p>
			<p>密碼：<br>
		<input name="spasswd" type="password" class="logintextbox" id="spasswd" value="<?php if(isset($_COOKIE["remPass"]) && ($_COOKIE["remPass"]!="")) echo $_COOKIE["remPass"];?>">
			</p>
			<p>
		<input name="rememberme" type="checkbox" id="rememberme"
		value="true">
		記住我的帳號密碼</p>
		<p align="center">
		<input type="submit" name="button" id="button" value="登入店家">
		</p>
		</form>
		<p align="center"><a href="store_passmail.php">忘記密碼</a></p>
		<hr size="1" />
		<p class="heading">還不是店家會員？</p>
		<p align="right"><a href ="store_join.php">加入店家會員</a></p>
	</div>
	<div class="boxbl"></div><div class="boxbr"></div></td>
	</tr>
	</table>
	</body>
</html>
<?php 
$db_link->close();
?>