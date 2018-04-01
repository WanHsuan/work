<?php
function GetSQLValueString($theValue, $theType) {
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
    return  $theValue;
}

if(isset($_POST["action"]) && ($_POST["action"] == "join")){
    require_once ("connMysql.php");
    //找尋帳號是否已註冊
    $query_RecFindUser = "SELECT CustomerUsername FROM Customer WHERE CustomerUsername='{$_POST["account"]}'";
    $RecFindUser = $db_link->query($query_RecFindUser);
    if($RecFindUser->num_rows>0){
        header("Location: customer_join.php?errMsg=1&account={$_POST["account"]}");
    }else{
        //若沒有則執行新增
        $query_insert = "INSERT INTO Customer (CustomerUsername, CustomerPassword, CustomerName, CustomerBirth, CustomerPhone, CustomerMail) VALUES(?, ?, ?, ?, ?, ?)";
        $stmt = $db_link->prepare($query_insert);
        $stmt->bind_param("ssssss",
            GetSQLValueString($_POST["account"], 'string'),
            password_hash($_POST["passwd"], PASSWORD_DEFAULT),
            GetSQLValueString($_POST["name"], 'string'),
            GetSQLValueString($_POST["birthday"], 'string'),
            GetSQLValueString($_POST["phone"], 'string'),
            GetSQLValueString($_POST["email"], 'email'));
        $stmt->execute();
        $stmt->close();
        $db_link->close();
        header("Location: customer_join.php?loginStats=1");
    }
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>會員系統</title>
<link href="style.css" rel="stylesheet" type="text/css">
<script language="javascript">
function checkForm(){
	if(document.formJoin.account.value==""){		
		alert("請填寫帳號!");
		document.formJoin.account.focus();
		return false;
	}else{
		uid=document.formJoin.account.value;
		if(uid.length<5 || uid.length>12){
			alert( "您的帳號長度只能5至12個字元!" );
			document.formJoin.account.focus();
			return false;}
		if(!(uid.charAt(0)>='a' && uid.charAt(0)<='z')){
			alert("您的帳號第一字元只能為小寫字母!" );
			document.formJoin.account.focus();
			return false;}
		for(idx=0;idx<uid.length;idx++){
			if(uid.charAt(idx)>='A'&&uid.charAt(idx)<='Z'){
				alert("帳號不可以含有大寫字元!" );
				document.formJoin.account.focus();
				return false;}
			if(!(( uid.charAt(idx)>='a'&&uid.charAt(idx)<='z')||(uid.charAt(idx)>='0'&& uid.charAt(idx)<='9')||( uid.charAt(idx)=='_'))){
				alert( "您的帳號只能是數字,英文字母及「_」等符號,其他的符號都不能使用!" );
				document.formJoin.account.focus();
				return false;}
			if(uid.charAt(idx)=='_'&&uid.charAt(idx-1)=='_'){
				alert( "「_」符號不可相連 !\n" );
				document.formJoin.account.focus();
				return false;}
		}
	}
	if(!check_passwd(document.formJoin.passwd.value,document.formJoin.passwdrecheck.value)){
		document.formJoin.passwd.focus();
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
alert('會員新增成功\n請用申請的帳號密碼登入。');
window.location.href='login.php';	
</script>
<?php }?>
<table width="780" border="0" align="center" cellpadding="4" cellspacing="0">
	<tr>
    <td class="tdbline"><table width="100%" border="0" cellspacing="0" cellpadding="10">
      <tr valign="top">
        <td class="tdrline"><form action="" method="POST" name="formJoin" id="formJoin" onSubmit="return checkForm();">
	<p class="title">加入會員</p>
	<?php if(isset($_GET["errMsg"]) && ($_GET["errMsg"]=="1")){?>
	<div class="errDiv">帳號「 <strong><?php echo $_GET["account"];?></strong> 」已經有人使用 </div>
		<?php }?>
		<div class="dataDiv">
		<hr size="1" />
		<p class="heading">帳號資料</p>
		<p><strong>使用帳號</strong>:
		<input name="account" type="text" class="normalinput" id="account">
		<font color ="#FF0000">*</font><br><span class="smalltext">請填入6~12個字元以內的小寫英文字母、數字以及＿符號。</span></p>
		<p><strong>使用密碼</strong>:
		<input name="passwd" type="password" id="passwd" class="normalinput">
		<font color="#FF0000">*</font><br><span class="smalltext">請填入5~10個字元的英文字母、數字以及各種符號組合。</span></p>
		<p><strong>確認密碼</strong>:
		<input name="passwdrecheck" type="password" class="normalinput" id="passwdrecheck">
		<font color="#FF0000">*</font><br><span class="smalltext">再次輸入密碼</span></p>
		<hr size="1" />
		<p class="heading">個人資料</p>
		<p><strong>真實姓名</strong>:
		<input name="name" type="text" class="normalinput" id="name">
		<font color="#FF0000">*</font></p>
		<p><strong>生    日</strong>:
		<input name="birthday" type="text" class="normalinput" id="birthday">
		<span class="smalltext">為西元格式(YYYY-MM-DD)</span></p>
		<p><strong>電    話</strong>:
		<input name="phone" type="text" class="normalinput" id="phone">
		<p><strong>電子郵件</strong>:
		<input name="email" type="text" class="cormalinput" id="email">
		<font color="#FF0000">*</font><br><span class="smalltext">請確定此電子郵件為可使用狀態，以方便未來系統使用，如補寄會員密碼信。</span></p>
		<p><font color="#FF0000">*</font>表示為必填欄位</p>
	</div>
	<hr size="1" />
	<p align="center">
		<input name="action" type="hidden" id="action" value="join">
		<input type="submit" name="Submit2" value="送出申請">
		<input type="reset" name="Submit3" value="重設資料">
		<input type="button" name="Submit" value="回上一頁" onClick="window.history.back();">
	</p>
	</form></td>
	</tr>
	</table></td>
	</tr>
</table>
</body>
</html>