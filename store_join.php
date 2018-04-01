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

if(isset($_POST["action"]) && ($_POST["action"] == "join")){
    require_once ("connMysql.php");
    $query_RecFindUser = "SELECT StoreUsername FROM store WHERE StoreUsername='{$_POST["account"]}'";
    $RecFindUser = $db_link->query($query_RecFindUser);
    if($RecFindUser->num_rows>0){
        header("Location: store_join.php?errMsg=1&account={$_POST["account"]}");
    }else{
        $query_insert = "INSERT INTO store (StoreUsername, StorePassword, StoreName, StorePhone, StoreAddressCity, StoreAddressDistriction, StoreAddress, StoreStory, StorePhoto) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db_link->prepare($query_insert);
        $stmt->bind_param("sssssssss", 
            GetSQLValueString($_POST["account"], 'string'), 
            password_hash($_POST["passwd"], PASSWORD_DEFAULT), 
            GetSQLValueString($_POST["name"], 'string'), 
            GetSQLValueString($_POST["phone"], 'string'), 
            GetSQLValueString($_POST["city"], 'string'), 
            GetSQLValueString($_POST["distriction"], 'string'), 
            GetSQLValueString($_POST["address"], 'string'), 
            GetSQLValueString($_POST["story"], 'string'), 
            GetSQLValueString($_FILES["photo"]["name"], 'string'));
        $stmt->execute();
        if(!move_uploaded_file($_FILES["photo"]["tmp_name"] , "storephoto/" . $_FILES["photo"]["name"])) die("照片上傳失敗！");
        $stmt->close();
        $db_link->close();
        header("Location: store_join.php?loginStats=1");
    }
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>店家會員系統</title>
<link href="style.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
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
		alert("請填寫店名!");
		document.formJoin.name.focus();
		return false;}
	if(document.formJoin.phone.value==""){
		alert("請填寫電話!");
		document.formJoin.phone.focus();
		return false;}
	if(document.formJoin.city.value==""){
		alert("請填寫店家所在城市!");
		document.formJoin.city.focus();
		return false;}
	if(document.formJoin.distriction.value==""){
		alert("請填寫店家所在鄉鎮市!");
		document.formJoin.distriction.focus();
		return false;}
	if(document.formJoin.address.value==""){
		alert("請填寫店家地址!");
		document.formJoin.address.focus();
		return false;}
	if(document.formJoin.story.value==""){
		alert("請填寫店家故事!");
		document.formJoin.story.focus();
		return false;}
	if(document.formJoin.photo.value==""){
		alert("請務必上傳店家照片!");
		document.formJoin.photo.focus();
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
</script>
</head>
<body>
<?php if(isset($_GET["loginStats"]) && ($_GET["loginStats"]=="1")){?>
<script language="javascript">
alert('店家新增成功\n請用申請的帳號密碼登入。');
window.location.href='login.php';		  
</script>
<?php }?>
<table>
<tr>
	<td class="tdbline"><table>
	<tr valign="top">
	<td class="tdrline"><form action="" method="POST" name="formJoin" id="formJoin" onSubmit="return checkForm();" enctype="multipart/form-data">
	<p class="title">加入店家</p>
	<?php if(isset($_GET["errMsg"]) && ($_GET["errMsg"]=="1")){?>
	<div class="errDiv">帳號<?php echo $_GET["account"];?>已經有人使用</div>
	<?php }?>
	<div class="dataDiv">
	<hr size="1" />
	<p class="heading">帳號資料</p>
	<p><strong>使用帳號</strong>:
	<input name="account" type="text" class="normalinput" id="account">
	<font color ="#FF0000">*</font><br><span class="smalltext">請填入小寫英文字母、數字以及＿符號。</span></p>
	<p><strong>使用密碼</strong>:
	<input name="passwd" type="password" id="passwd" class="normalinput">
	<font color="#FF0000">*</font><br><span class="smalltext">請填入8個字元以上的英文字母、數字以及各種符號組合。</span></p>
	<p><strong>確認密碼</strong>:
	<input name="passwdrecheck" type="password" class="normalinput" id="passwdrecheck">
	<font color="#FF0000">*</font><br><span class="smalltext">再次輸入密碼</span></p>
	<hr size="1" />
	<p class="heading">店家資料</p>
	<p><strong>店名</strong>:
	<input name="name" type="text" class="normalinput" id="name">
	<font color="#FF0000">*</font></p>
	<p><strong>電話</strong>:
	<input name="phone" type="text" class="normalinput" id="phone">
	<font color="#FF0000">*</font></p>
	<p><strong>店家所在縣市</strong>:
	<input name="city" type="text" class="normalinput" id="city">
	<font color="#FF0000">*</font></p>
	<p><strong>店家所在市鎮區</strong>:
	<input name="distriction" type="text" class="normalinput" id="distriction">
	<font color="#FF0000">*</font></p>
	<p><strong>店家地址</strong>:
	<input name="address" type="text" class="normalinput" id="address">
	<font color="#FF0000">*</font></p>
	<p><strong>店家故事</strong>:
	<textarea name="story" id="story" rows="10" cols="40"></textarea>
	<font color="#FF0000">*</font></p>
	<p><strong>店家照片</strong>:
	<input type="file" name="photo" id="photo" />
	<font color="#FF0000">*</font></p>
	<p><font color="#FF0000">*</font>表示為必填欄位</p>
	</div>
	<hr size="1" />
	<p align="center">
	<input name="action" type="hidden" id="action" value="join">
	<input type="submit" name="Submit2" value="送出申請">
	<input type="reset" name="Submit3" value="重設資料">
	<input type="button" name="Submit" value="回上一頁" onClick="window.history.back();"></p>
	</form></td>
	</tr>
	</table></td>
	</tr>
</table>
</body>
</html>