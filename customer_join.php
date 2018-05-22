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
    }
    return $theValue;
}

if (isset($_POST["action"]) && ($_POST["action"] == "join")) {
    require_once ("connMysql.php");
    // 找尋帳號是否已註冊
    $query_RecFindUser = "SELECT CustomerUsername FROM Customer WHERE CustomerUsername='{$_POST["account"]}'";
    $RecFindUser = $db_link->query($query_RecFindUser);
    if ($RecFindUser->num_rows > 0) {
        header("Location: customer_join.php?errMsg=1&account={$_POST["account"]}");
    } else {
        // 若沒有則執行新增
        $query_insert = "INSERT INTO Customer (CustomerUsername, CustomerPassword, CustomerName, CustomerBirth, CustomerPhone, CustomerMail) VALUES(?, ?, ?, ?, ?, ?)";
        $stmt = $db_link->prepare($query_insert);
        $stmt->bind_param("ssssss", GetSQLValueString($_POST["account"], 'string'), password_hash($_POST["passwd"], PASSWORD_DEFAULT), GetSQLValueString($_POST["name"], 'string'), GetSQLValueString($_POST["birthday"], 'string'), GetSQLValueString($_POST["phone"], 'string'), GetSQLValueString($_POST["email"], 'email'));
        $stmt->execute();
        $stmt->close();
        $db_link->close();
        header("Location: customer_join.php?loginStats=1");
        mysqli_free_result($stmt);
    }
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="style.css" type="text/css">
<meta name="viewport" content="width= device-width" />
<title>Hand's Story--會員註冊</title>

<style>
#apDiv1 {
	position: absolute;
	left: 750px;
	top: 120px;
	width: 550px;
	height: 620px;
	z-index: 1;
	background-color: #FFF;
	opacity:0.9;
}
#apDiv1 h1{
	margin:0px;
	padding:0px;
	font-family:"翩翩體-繁";
	color: #a7a5a7;
	text-align:center;
	font-size:24px;
	
}
.heading{
    margin:0 px;
    padding: 0px;
    font-family:"翩翩體-繁";
	color: #a7a5a7;
	text-align:center;
	font-size:16px;
}
.context{
    margin:0 px;
    padding: 0px;
    font-family:"翩翩體-繁";
	color: #a7a5a7;
	font-size:16px;
}
.smalltext{
    margin:0 px;
    padding: 0px;
    font-family:"翩翩體-繁";
	color: #a7a5a7;
	font-size:12px;
}
#re{
    padding:0px;
    margin:0px;
}
hr{
    padding:0px;
    margin:0px;
    color:#dcd8dc;
}
</style>

<script language="javascript">
function checkForm(){
    if(document.formJoin.account.value==""){
        alert("請填寫帳號!");
        document.formJoin.account.focus();
        return false;
    }else{
        uid=document.formJoin.account.value;
        if(uid.length<6 || uid.length>12){
            alert("您的帳號長度只能6到12個字元");
            document.formJoin.account.focus();
            return false;}
        if(!(uid.charAt(0)>='a' && uid.charAt(0)<='z')){
            alert("帳號第一個字元只能為小寫字母");
            document.formJoin.account.focus();
            return false;}
        for(idx=0;idx<uid.length;idx++){
            if(uid.charAt(idx)>='A' && uid.charAt(idx)<='Z'){
                alert("帳號不可含有大寫字元");
                document.formJoin.account.focus();
                return false;}
            if(!((uid.charAt(idx)>='a'&& uid.charAt(idx)<='z')||(uid.charAt(idx)>='0'&& uid.charAt(idx)<='9')||(uid.charAt(idx)=='_'))){
                alter("您的帳號只能是數字，英文字母及「＿」等符號");
                document.formJoin.account.focus();
                return false;}
            if(uid.charAt(idx)=='_'&& uid.charAt(idx-1)=='_'){
                alert("「＿ 」符號不可相連");
                document.formJoin.account.focus();
                return false;}
        }
    }
	if(!check_passwd(document.formJoin.passwd.value,document.formJoin.passwdrecheck.value)){
		document.formJoin.m_passwd.focus();
		return false;}	
	if(document.formJoin.name.value==""){
		alert("請填寫姓名!");
		document.formJoin.name.focus();
		return false;}
     if(document.formJoin.email.value==""){
     	alert("請填寫電子郵件!");
     	document.formJoin.email.focus();
     	return false;}
  	if(!checkmail(document.formJoin.email)){
     	document.formJoin.email.focus();
     	return false;}
     return confirm('確定送出嗎？');
}
function check_passwd(pw1,pw2){
	if(pw1==''){
		alert("密碼不可以空白!");
		return false;}
	for(var idx=0;idx<pw1.length;idx++){
		if(pw1.charAt(idx) == ' ' || pw1.charAt(idx) == '\"'){
			alert("密碼不可以含有空白或雙引號 !\n");
			return false;}
		if(pw1.length<5 || pw1.length>10){
			alert( "密碼長度只能5到10個字母 !\n" );
			return false;}
		if(pw1!= pw2){
			alert("密碼二次輸入不一樣,請重新輸入 !\n");
			return false;}
	}
	return true;
}
function checkmail(myEmail){
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
alert('會員新增成功\n請用申請的帳號密碼登入。');
window.location.href='login.php';		  
</script>

<?php }?>
	<!--頁首-->
	<header>
		<img src="images/登入後LOGO.fw.png" width="1380" usemap="#Map" border="0" />
		<map name="Map" id="Map">
			<area shape="rect" coords="128,15,477,82" href="hindex.php"
				target="_parent" />
		</map>
	</header>
	<!--註冊畫面背景-->
	<div class="register">
		<img src="images/註冊背景.jpg" width="1378" height="666"
			style="opacity: 0.9" />
	</div>
	<!--註冊框-->
	<div id="apDiv1">
		<h1>一般會員註冊</h1>
		<span class="smalltext"  style="padding-left:20px;"><font color="#FF0000">*</font>表示為必填欄位</span>
		<div id="re">
		<table class="register" width="550" border="0" align="center" cellpadding="0"
			cellspacing="0" style='padding:1px 20px;'>
			<tr>
				<td class="tdbline"><table width="550" border="0" cellspacing="0"
						cellpadding="10">
						<tr valign="top">
							<td class="tdrline"><form action="" method="POST" name="formJoin"
									id="formJoin" onSubmit="return checkForm();">
	<?php if(isset($_GET["errMsg"]) && ($_GET["errMsg"]=="1")){?>
	<div class="errDiv">
										帳號「 <strong><?php echo $_GET["account"];?></strong> 」已經有人使用
									</div>
		<?php }?>
		<div class="dataDiv">
										
										<p class="heading">帳號資料</p>
										<p class="context">使用帳號: <input name="account" type="text"
												class="normalinput" id="account"> <font color="#FF0000">*</font><br>
											<span class="smalltext">請填入6~12個字元以內的小寫英文字母、數字以及＿符號。</span>
										</p>
										<p class="context">使用密碼: <input name="passwd" type="password"
												id="passwd" class="normalinput"> <font color="#FF0000">*</font><br>
											<span class="smalltext">請填入5~10個字元的英文字母、數字以及各種符號組合。</span>
										</p>
										<p class="context">確認密碼: <input name="passwdrecheck"
												type="password" class="normalinput" id="passwdrecheck"> <font
												color="#FF0000">*</font><br> <span class="smalltext">再次輸入密碼</span>
										</p>
										<hr size="1" />
										<p class="heading">個人資料</p>
										<p class="context">真實姓名: <input name="name" type="text"
												class="normalinput" id="name"> <font color="#FF0000">*</font>
										</p>
										<p class="context">生 日: <input name="birthday" type="text"
												class="normalinput" id="birthday"> <span class="smalltext">為西元格式(YYYY-MM-DD)</span>
										</p>
										<p class="context">電 話: <input name="phone" type="text"
												class="normalinput" id="phone">
										</p>
										<p class="context">電子郵件: <input name="email" type="text"
												class="cormalinput" id="email"> <font color="#FF0000">*</font><br>
											<span class="smalltext">請確定此電子郵件為可使用狀態，以方便未來系統使用，如補寄會員密碼信。</span>
										</p>
										
									</div>
									<p align="center">
										<input name="action" type="hidden" id="action" value="join"> 
										<input class="buttons" type="submit" name="Submit2" value="送出申請"> 
										<input class="buttons" type="reset" name="Submit3" value="重設資料"> 
										<input class="buttons" type="button" name="Submit" value="回上一頁"
											onClick="window.history.back();">
									</p>
								</form></td>
						</tr>
					</table></td>
			</tr>
		</table>
		</div>
	</div>
</body>
</html>