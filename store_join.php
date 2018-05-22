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

function resize_photo($src_file, $src_ext, $dest_name, $max_size)
{
    switch ($src_ext)
    {
        case ".jpg":
            $src = imagecreatefromjpeg($src_file);
            break;
        case ".jpeg":
            $src = imagecreatefromjpeg($src_file);
            break;
        case ".png":
            $src = imagecreatefrompng($src_file);
            break;
        case ".gif":
            $src = imagecreatefromgif($src_file);
            break;
    }
    
    $src_w = imagesx($src);
    $src_h = imagesy($src);
    
    //建立新的空圖片
    if($src_w > $src_h)
    {
        $thumb_w = $max_size;
        $thumb_h = intval($src_h / $src_w * $thumb_w);
    }
    else
    {
        $thumb_h = $max_size;
        $thumb_w = intval($src_w / $src_h * $thumb_h);
    }
    
    $thumb = imagecreatetruecolor($thumb_w, $thumb_h);
    
    //進行複製並縮圖
    imagecopyresized($thumb, $src, 0, 0, 0, 0, $thumb_w, $thumb_h, $src_w, $src_h);
    
    //儲存相片
    imagejpeg($thumb, $dest_name, 100);
    
    //釋放影像佔用的記憶體
    imagedestroy($src);
    imagedestroy($thumb);
}

if (isset($_POST["action"]) && ($_POST["action"] == "join")) {
    require_once ("connMysql.php");
    $query_RecFindUser = "SELECT StoreUsername FROM store WHERE StoreUsername='{$_POST["account"]}'";
    $RecFindUser = $db_link->query($query_RecFindUser);
    if ($RecFindUser->num_rows > 0) {
        header("Location: store_join.php?errMsg=1&account={$_POST["account"]}");
    } else {
        $src_file = $_FILES["photo"]["tmp_name"];
        $src_file_name = $_FILES["photo"]["name"];
        $src_ext = strtolower(strrchr($_FILES["photo"]["name"], "."));
        $desc_file_name = uniqid() . ".jpg";
        
        $photo_file_name = "./storephoto/$desc_file_name";
        resize_photo($src_file, $src_ext, $photo_file_name, 600);
        $query_insert = "INSERT INTO store (StoreUsername, StorePassword, StoreName, StorePhone, StoreAddressCity, StoreAddressDistriction, StoreAddress, StoreStory, StorePhoto) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db_link->prepare($query_insert);
        $stmt->bind_param("sssssssss", GetSQLValueString($_POST["account"], 'string'), password_hash($_POST["passwd"], PASSWORD_DEFAULT), GetSQLValueString($_POST["name"], 'string'), GetSQLValueString($_POST["phone"], 'string'), GetSQLValueString($_POST["city"], 'string'), GetSQLValueString($_POST["distriction"], 'string'), GetSQLValueString($_POST["address"], 'string'), GetSQLValueString($_POST["story"], 'string'), $desc_file_name);
        $stmt->execute();
        
        $stmt->close();
        $db_link->close();
        mysqli_free_result($stmt);
        header("Location: store_join.php?loginStats=1");
    }
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="style.css" type="text/css">
<meta name="viewport" content="width= device-width" />
<title>Hand's Story--店家註冊</title>

<style>
#apDiv1 {
	position: absolute;
	left: 750px;
	top: 100px;
	width: 550px;
	height: 655px;
	z-index: 1;
	background-color: #FFF;
	opacity: 0.9;
	margin:0px;
}

#apDiv1 h1 {
	margin: 0px;
	padding: 0px;
	padding-top:2px;
	font-family: "翩翩體-繁";
	color: #a7a5a7;
	text-align: center;
	font-size: 24px;
}

.heading {
	margin: 0 px;
	padding: 0px;
	font-family: "翩翩體-繁";
	color: #a7a5a7;
	text-align: center;
	font-size: 16px;
}

.context {
	margin: 0 px;
	padding: 0px;
	font-family: "翩翩體-繁";
	color: #a7a5a7;
	font-size: 16px;
}

.smalltext {
	margin: 0 px;
	padding: 0px;
	font-family: "翩翩體-繁";
	color: #a7a5a7;
	font-size: 12px;
}

#re {
	padding: 0px;
	margin: 0px;
}
hr {
	padding: 0px;
	margin: 0px;
	color:#dcd8dc;
}
</style>

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
		<h1>店家註冊
		<span class="smalltext"><font color="#FF0000">*</font>表示為必填欄位</span></h1>
		<div id="re">
			<table class="register" width="500" border="0" align="center" cellpadding="0"
			cellspacing="0" style='padding:1px 20px;'>
				<tr>
					<td class="tdbline"><table width="500" border="0" cellspacing="0"
						cellpadding="10">
							<tr valign="top">
								<td class="tdrline"><form action="" method="POST"
										name="formJoin" id="formJoin" onSubmit="return checkForm();"
										enctype="multipart/form-data">
										
	<?php if(isset($_GET["errMsg"]) && ($_GET["errMsg"]=="1")){?>
	<div class="errDiv">帳號<?php echo $_GET["account"];?>已經有人使用</div>
	<?php }?>
	<div class="dataDiv">
											
											<p class="heading">帳號資料</p>
											<p class="context">
												<font color="#FF0000">*</font><strong>使用帳號</strong>: <input name="account" type="text"
													class="normalinput" id="account"> <br>
												<span class="smalltext">請填入小寫英文字母、數字以及＿符號。</span>
											</p>
											<p class="context">
												<font color="#FF0000">*</font><strong>使用密碼</strong>: <input name="passwd" type="password"
													id="passwd" class="normalinput"> <br>
												<span class="smalltext">請填入8個字元以上的英文字母、數字以及各種符號組合。</span>
											</p>
											<p class="context">
												<font
													color="#FF0000">*</font><strong>確認密碼</strong>: <input name="passwdrecheck"
													type="password" class="normalinput" id="passwdrecheck"> <br>
												<span class="smalltext">再次輸入密碼</span>
											</p>
											<hr size="1" />
											<p class="heading">店家資料</p>
											<p class="context">
												<font color="#FF0000">*</font><strong>店名</strong>: <input name="name" type="text"
													class="normalinput" id="name"> 
											</p>
											<p class="context">
												<font color="#FF0000">*</font><strong>電話</strong>: <input name="phone" type="text"
													class="normalinput" id="phone"> 
											</p>
											<p class="context">
												 <font color="#FF0000">*</font><strong>地址</strong>: <input name="city" type="text"
													class="normalinput" id="city" placeholder="縣市" style="width:80px;">
													<input name="distriction"
													type="text" class="normalinput" id="distriction" placeholder="鄉鎮區" style="width:80px;"> 
													 <input name="address" type="text"
													class="normalinput" id="address" placeholder="街道">
											</p>
											<p class="context">
												<font color="#FF0000">*</font><strong>關於</strong>:
												<textarea name="story" id="story"style="width: 300px; height: 60px;"></textarea>
												
											</p>
											<p class="context">
												  <font color="#FF0000">*</font><strong>店家照片</strong>: <input type="file" name="photo"
													id="photo" />
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