<?php
function GetSQLValueString($theValue, $theType){
    switch($theType){
        case "string":
            $theValue = ($theValue != "") ? filter_var($theValue,FILTER_SANITIZE_MAGIC_QUOTES) : "";
            break;
        case "email":
            $theValue = ($theValue != "") ? filter_var($theValue,FILTER_VALIDATE_EMAIL) : "";
            break;
    }
    return $theValue;
}
require_once 'connMysql.php';
session_start();
function MakePass($length){
    $possible = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $str = "";
    while (strlen($str)<$length){
        $str .= substr($possible, rand(0,strlen($possible)),1);
    }
    return($str);
}
if (isset($_SESSION["loginStore"])&&($_SESSION["loginStore"]!="")){
    header("Location: store_center.php");
}
if (isset($_POST["account"])){
    $muser = GetSQLValueString($_POST["account"], 'string');
    $query_RecFindUser = "SELECT StoreUsername, StorePassword FROM store WHERE StoreUsername='{$muser}'";
    $RecFindUser = $db_link->query($query_RecFindUser);
    if($RecFindUser["StoreUsername"]->num_rows==0){
        header("Location: store_passmail.php?errMsg=1&account={$muser}");
    }else{
        $row_RecFindUser = $RecFindUser->fetch_assoc();
        $account = $row_RecFindUser["StoreUsername"];
        $password = $row_RecFindUser["StorePassword"];
        //        $email = $row_RecFindUser["m_Email"];
        $newpasswd = MakePass(10);
        $mpass = password_hash($newpasswd, PASSWORD_DEFAULT);
        $tpass = password_hash($password, PASSWORD_DEFAULT);
        $mailcontent = "您好，<br />您的帳號為：{$account} <br/>您的新密碼為：{$newpasswd} <br/>";
        $mailForm="=?UTF-8?B?" . base64_encode("HandStory會員管理系統") . "?=<audrey.35921@gmail.com>";
        $mailto = $_POST["email"];
        $mailSubject="=?UTF-8?B?" . base64_encode("補記密碼信") . "?=";
        $mailHeader="From:".$mailForm."\r\n";
        $mailHeader.="Content-type:text/html;charset=UTF-8";
        if(@mail($mailto, $mailSubject, $mailcontent, $mailHeader)) {
            $query_update = "UPDATE store SET StorePassword='{$mpass}' WHERE StoreUsername='{$account}'";
            $db_link->query($query_update);
        }else{
            echo ("郵寄失敗");
            $query_update2 = "UPDATE store SET StorePassword='{$tpass}' WHERE StoreUsername='{$account}'";
            $db_link->query($query_update2);
        }
        header("Location: store_passmail.php?mailStats=1");
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
<?php if(isset($_GET["mailStats"]) && ($_GET["mailStats"]=="1")){?>
<script>alert('密碼信補寄成功！');window.location.href='login.php';</script>
<?php }?>
<table width="780" border="0" align="center" cellpadding="4" cellspacing="0">
<tr>
<td width="200%">
<div class="boxtl"></div><div class="boxtr"></div><div class="regbox">
<?php if(isset($_GET["errMsg"]) && ($_GET["errMsg"]=="1")){?>
<div class="errDiv">帳號「<strong><?php echo $_GET["account"];?></strong>」沒有人使用！</div>
<?php }?>
<p class="heading">忘記密碼？</p>
<form name="form1" method="POST" action="">
<p>請輸入您的帳號及信箱，系統將自動產生一個十位數的密碼寄到您輸入的信箱。</p>
<p><strong>帳號</strong>：<br>
<input name="account" type="text" class="logintextbox" id="account"/></p>
<p align="center">
<p><strong>信箱</strong>：<br>
<input name="email" type="text" class="logintextbox" id="email"></p>
<p align="center">
<input type="submit" name="button" id="button" value="寄密碼信">
<input type="button" name="button2" id="button2" value="回上一頁" onClick="window.history.back();">
</p>
</form>
<hr size="1" />
<p class="heading">還不是會員?</p>
<p align="right"><a href="store_join.php">加入會員</a></p></div>
<div class="boxbl"></div><div class="boxbr"></div></td>
</tr>
</table>
</body>
</html>