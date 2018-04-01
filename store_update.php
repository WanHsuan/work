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
if(!isset($_SESSION["loginStore"]) || ($_SESSION["loginStore"]=="")){
    header("Location: login.php");
}
//執行登出動作
if(isset($_GET["logout"]) && ($_GET["logout"]=="true")){
    unset($_SESSION["loginStore"]);
    unset($_SESSION["storeid"]);
    header("Location: login.php");
}
//重新導向頁面
$redirectUrl = "store_center.php";
//執行更新動作
if (isset($_POST["action"]) && ($_POST["action"]=="update")){
    $query_update = "UPDATE store SET StorePassword=?, StoreName=?, StoreAddressCity=?,
    StoreAddressDistriction=?, StoreAddress=?, StoreStory=?, StorePhone=?, StoreStyle=?, 
    StoreFBName=?, StoreFBAddrss=?, StoreIGName=?, StoreIGAddress=?, StorePriceSection=?, 
    StoreRate=?, StoreLineID=?, StoreOffday=?, StoreWorkingtime=? WHERE StoreID='{$_SESSION["storeid"]}'";
    $stmt = $db_link->prepare($query_update);
    //檢查是否有修改密碼
    $mpass = $_POST["passwdo"];
    if (($_POST["passwd"]!="")&&($_POST["passwd"]==$_POST["passwdrecheck"])){
        $mpass = password_hash($_POST["passwd"], PASSWORD_DEFAULT);
    }
    $stmt->bind_param("sssssssssssssssss",
        $mpass,
        GetSQLValueString($_POST["name"], 'string'),
        GetSQLValueString($_POST["city"], 'string'),
        GetSQLValueString($_POST["distriction"], 'string'),
        GetSQLValueString($_POST["address"], 'string'),
        GetSQLValueString($_POST["story"], 'string'),
        GetSQLValueString($_POST["phone"], 'string'),
        GetSQLValueString($_POST["style"], 'string'),
        GetSQLValueString($_POST["fbname"], 'string'),
        GetSQLValueString($_POST["fburl"], 'string'),
        GetSQLValueString($_POST["igname"], 'string'),
        GetSQLValueString($_POST["igurl"], 'string'),
        GetSQLValueString($_POST["price"], 'string'),
        GetSQLValueString($_POST["rate"], 'string'),
        GetSQLValueString($_POST["lineid"], 'string'),
        GetSQLValueString($_POST["offday"], 'string'),
        GetSQLValueString($_POST["workingtime"], 'string'));
    $stmt->execute();
    $stmt->close();
    //若有修改密碼則登出回到首頁
    if (($_POST["passwd"]!="")&&($_POST["passwd"]==$_POST["passwdrecheck"])){
        unset($_SESSION["loginStore"]);
        unset($_SESSION["storeid"]);
        $redirectUrl="login.php";
    }
    header("Location: $redirectUrl");
}
$query_RecMember = "SELECT * FROM store WHERE StoreUsername='{$_SESSION["loginStore"]}'";
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
<table width="780" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr>
   <td class="tdbline"><table width="100%" border="0" cellspacing="0" cellpadding="10">
  	<tr valign="top">
	 <td class="tdrline"><form action="" method="POST" name="formJoin" id="formJoin" onSubmit="return checkForm();">
	  <p class="title">修改資料</p>
	   <div class="dataDiv">
		<hr size="1" />
		<p class="heading">帳號資料</p>
		<p><strong>帳號</strong>：<?php echo $row_RecMember["StoreUsername"];?></p>
		<p><strong>使用密碼</strong>：
		<input name="passwd" type="password" class="normalinput" id="passwd">
    	<input name="passwdo" type="hidden" id="passwdo" value="<?php echo $row_RecMember["StorePassword"];?>"></p>
		<p><strong>確認密碼</strong> ：
		<input name="passwdrecheck" type="password" class="normalinput" id="passwdrecheck"><br>
		<span class="smalltext">若不修改密碼，請不要填寫。若要修改，請輸入密碼</span><span class="smalltext">二次。<br>若修改密碼，系統會自動登出，請用新密碼登入。</span></p>
		<hr size="1" />
		<p class="heading">店家資料</p>
		<p><strong>店名</strong>：
		<input name="name" type="text" class="normalinput" id="name" value="<?php echo $row_RecMember["StoreName"];?>">
		</p>
		<p><strong>店家所在縣市</strong>：
		<input name="city" type="text" class="normalinput" id="city" value="<?php echo $row_RecMember["StoreAddressCity"];?>">
		</p>
		<p><strong>店家所在市鎮區</strong>：
		<input name="distriction" type="text" class="normalinput" id="distriction" value="<?php echo $row_RecMember["StoreAddressDistriction"];?>">
		</p>
		<p><strong>店家地址</strong>：
		<input name="address" type="text" class="normalinput" id="address" value="<?php echo $row_RecMember["StoreAddress"];?>">
		</p>
		<p><strong>店家故事</strong>：
		<textarea name="story" id="story" rows="10" cols="40"><?php echo $row_RecMember["StoreStory"];?></textarea>
		</p>
		<p><strong>聯絡電話</strong>：
		<input name="phone" type="text" class="normalinput" id="phone" value="<?php echo $row_RecMember["StorePhone"];?>">
		</p>
		<p><strong>擅長的美甲風格</strong>：
		<input name="style" type="text" class="normalinput" id="style" value="<?php echo $row_RecMember["StoreStyle"];?>">
		</p>
		<p><strong>店家臉書名稱</strong>：
		<input name="fbname" type="text" class="normalinput" id="fbname" value="<?php echo $row_RecMember["StoreFBName"];?>">
		</p>
		<p><strong>店家臉書網址</strong>：
		<input name="fburl" type="text" class="normalinput" id="fburl" value="<?php echo $row_RecMember["StoreFBAddrss"];?>">
		</p>
		<p><strong>店家IG名稱</strong>：
		<input name="igname" type="text" class="normalinput" id="igname" value="<?php echo $row_RecMember["StoreIGName"];?>">
		</p>
		<p><strong>店家IG網址</strong>：
		<input name="igurl" type="text" class="normalinput" id="igurl" value="<?php echo $row_RecMember["StoreIGAddress"];?>">
		</p>
		<p><strong>產品價格區間</strong>：
		<input name="price" type="text" class="normalinput" id="price" value="<?php echo $row_RecMember["StorePriceSection"];?>">
		</p>
		<p><strong>店家評價星等</strong>：
		<input name="rate" type="text" class="normalinput" id="rate" value="<?php echo $row_RecMember["StoreRate"];?>">
		</p>
		<p><strong>Line ID</strong>:
		<input name="lineid" type="text" class="normalinput" id="lineid" value="<?php echo $row_RecMember["StoreLineID"];?>">
		</p>
		<p><strong>休息日</strong>：
		<input name="offday" type="text" class="normalinput" id="offday" value="<?php echo $row_RecMember["StoreOffday"];?>">
		</p>
		<p><strong>營業時間</strong>：
		<input name="workingtime" type="text" class="normalinput" id="workingtime" value="<?php echo $row_RecMember["StoreWorkingtime"];?>">
		</p>
		<div>
		<p><strong>店家照片</strong>：
		<img src="storephoto/<?php echo $row_RecMember["StorePhoto"];?>" alt="店家照片" width="120" height="120" border="0" />
		<a href="storephoto_update.php">更改照片</a>
		</p></div>
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
	  	<p><strong><?php echo $row_RecMember["StoreName"];?></strong> 您好。</p>
		<p align="center"><a href="store_center.php">會員中心</a> | <a href="?logout=true">登出系統</a></p>
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