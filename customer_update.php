<?php
function GetSQLValueString($theValue, $theType){
    switch ($theType){
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
    return  $theValue;
}
require_once("connMysql.php");
session_start();
//檢查是否經過登入
if(!isset($_SESSION["loginCustomer"]) || ($_SESSION["loginCustomer"]=="")){
    header("Location: login.php");
}
//執行登出動作
if(isset($_GET["logout"]) && ($_GET["logout"]=="true")){
    unset($_SESSION["loginCustomer"]);
    unset($_SESSION["customerid"]);
    header("Location: login.php");
}
//重新導向頁面
$redirectUrl = "customer_center.php";
//執行更新動作
if (isset($_POST["action"]) && ($_POST["action"]=="update")){
    $query_update = "UPDATE Customer SET CustomerPassword=?, CustomerName=?, CustomerBirth=?,
    CustomerPhone=?, CustomerMail=? WHERE CustomerID='{$_SESSION["customerid"]}'";
    $stmt = $db_link->prepare($query_update);
    //檢查是否有修改密碼
    $mpass = $_POST["passwdo"];
    if (($_POST["passwd"]!="")&&($_POST["passwd"]==$_POST["passwdrecheck"])){
        $mpass = password_hash($_POST["passwd"], PASSWORD_DEFAULT);
    }
    $stmt->bind_param("sssss",
        $mpass,
        GetSQLValueString($_POST["name"], 'string'),
        GetSQLValueString($_POST["birthday"], 'string'),
        GetSQLValueString($_POST["phone"], 'string'),
        GetSQLValueString($_POST["email"], 'email'));
    $stmt->execute();
    $stmt->close();
    //若有修改密碼則登出回到首頁
    if (($_POST["passwd"]!="")&&($_POST["passwd"]==$_POST["passwdrecheck"])){
        unset($_SESSION["loginCustomer"]);
        unset($_SESSION["customerid"]);
        $redirectUrl="login.php";
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
<title>會員資料修改</title>
<link href="style.css" rel="stylesheet" type="text/css">
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
<table width="780" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr>
   <td class="tdbline"><table width="100%" border="0" cellspacing="0" cellpadding="10">
  	<tr valign="top">
	 <td class="tdrline"><form action="" method="POST" name="formJoin" id="formJoin" onSubmit="return checkForm();">
	  <p class="title">修改資料</p>
	   <div class="dataDiv">
		<hr size="1" />
		<p class="heading">帳號資料</p>
		<p><strong>帳號</strong>：<?php echo $row_RecMember["CustomerUsername"];?></p>
		<p><strong>使用密碼</strong>：
		<input name="passwd" type="password" class="normalinput" id="passwd">
    	<input name="passwdo" type="hidden" id="passwdo" value="<?php echo $row_RecMember["CustomerPassword"];?>"></p>
		<p><strong>確認密碼</strong> ：
		<input name="passwdrecheck" type="password" class="normalinput" id="passwdrecheck"><br>
		<span class="smalltext">若不修改密碼，請不要填寫。若要修改，請輸入密碼</span><span class="smalltext">二次。<br>若修改密碼，系統會自動登出，請用新密碼登入。</span></p>
		<hr size="1" />
		<p class="heading">個人資料</p>
		<p><strong>姓   名</strong>：
		<input name="name" type="text" class="normalinput" id="name" value="<?php echo $row_RecMember["CustomerName"];?>">
		</p>
		<p><strong>生　　日</strong>：
		<input name="birthday" type="text" class="normalinput" id="birthday" value="<?php echo $row_RecMember["CustomerBirth"];?>">
		<br><span class="smalltext">為西元格式(YYYY-MM-DD)。</span></p>
		<p><strong>手機號碼</strong>：
		<input name="phone" type="text" class="normalinput" id="phone" value="<?php echo $row_RecMember["CustomerPhone"];?>">
		</p>
		<p><strong>電子郵件</strong>：
		<input name="email" type="text" class="normalinput" id="email" value="<?php echo $row_RecMember["CustomerMail"];?>">
		</p>
	   </div>
	   <hr size="1" />
	   <p align="center">
		<input name="action" type="hidden" id="action" value="update">
		<input type="submit" name="Submit2" value="修改資料">
		<input type="reset" name="Submit3" value="重設資料">
		<input type="button" name="Submit" value="回上一頁" onClick="window.history.back();">
	   </p>
	  </form></td>
	  <td width="200">
	  <div class="boxtl"></div><div class="boxtr"></div>
	  <div class="regbox">
	  	<p><strong><?php echo $row_RecMember["CustomerName"];?></strong> 您好。</p>
		<p align="center"><a href="customer_center.php">會員中心</a> | <a href="?logout=true">登出系統</a></p>
	  </div>
	  <div class="boxbl"></div><div class="boxbr"></div></td>
	 </tr>
	</table>
   </td>
  </tr>
 </table>
</body>
</html>
<?php 
    $db_link->close();
?>