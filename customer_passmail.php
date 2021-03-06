<?php

function GetSQLValueString($theValue, $theType)
{
    switch ($theType) {
        case "string":
            $theValue = ($theValue != "") ? filter_var($theValue, FILTER_SANITIZE_MAGIC_QUOTES) : "";
            break;
        case "email":
            $theValue = ($theValue != "") ? filter_var($theValue, FILTER_VALIDATE_EMAIL) : "";
            break;
    }
    return $theValue;
}
require_once 'connMysql.php';
session_start();

// 函式：自動產生指定長度的密碼
function MakePass($length)
{
    $possible = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $str = "";
    while (strlen($str) < $length) {
        $str .= substr($possible, rand(0, strlen($possible)), 1);
    }
    return ($str);
}
if (isset($_SESSION["loginCustomer"]) && ($_SESSION["loginCustomer"] != "")) {
    header("Location: hcindex.php");
}
// 檢查是否為會員
if (isset($_POST["account"])) {
    $muser = GetSQLValueString($_POST["account"], 'string');
    // 找尋該會員資料
    $query_RecFindUser = "SELECT CustomerUsername, CustomerMail, CustomerPassword FROM Customer WHERE CustomerUsername='{$muser}'";
    $RecFindUser = $db_link->query($query_RecFindUser);
    if ($RecFindUser->num_rows == 0) {
        header("Location: login.php?errMsg=1&account={$muser}");
    } else {
        // 取出帳號密碼的值
        $row_RecFindUser = $RecFindUser->fetch_assoc();
        $account = $row_RecFindUser["CustomerUsername"];
        $email = $row_RecFindUser["CustomerMail"];
        $password = $row_RecFindUser["CustomerPassword"];
        
        // 產生新密碼並更新
        $newpasswd = MakePass(10);
        $pass1 = password_hash($password, PASSWORD_DEFAULT);
        $mpass = password_hash($newpasswd, PASSWORD_DEFAULT);
        // 密碼信
        $mailcontent = "您好，<br />您的帳號為：{$account} <br/>您的新密碼為：{$newpasswd} <br/>";
        $mailFrom = "=?UTF-8?B?" . base64_encode("會員管理系統") . "?= <audrey.35921@gmail.com>";
        $mailto = $email;
        $mailSubject = "=?UTF-8?B?" . base64_encode("補記密碼信") . "?=";
        $mailHeader = "From:" . $mailFrom . "\r\n";
        $mailHeader .= "Content-type:text/html;charset=UTF-8";
        if (! @mail($mailto, $mailSubject, $mailcontent, $mailHeader)) {
            ?><script>alert('郵寄失敗!請在嘗試一次，若多次失敗，請聯絡我們!!');window.location.href='login.php';</script><?php
            die("郵寄失敗");
            $mpass = $pass1;
            


        }
        $query_update = "UPDATE Customer SET CustomerPassword='{$mpass}' WHERE CustomerUsername='{$account}'";
        $db_link->query($query_update);
        header("Location: login.php?mailStats=1");
    }
}
mysqli_free_result($RecFindUser);

?>
<html>
<head>
</head>
<body>
<?php if(isset($_GET["mailStats"]) && ($_GET["mailStats"]=="1")){?>
<script>alert('密碼信補寄成功！');window.location.href='login.php';</script>
<?php }?>
<table width="780" border="0" align="center" cellpadding="4"
		cellspacing="0">
		<tr>
			<td width="200">
				<div class="boxtl"></div>
				<div class="boxtr"></div>
				<div class="regbox">
<?php if(isset($_GET["errMsg"]) && ($_GET["errMsg"]=="1")){?>
<script>
						alert('帳號「 <?php echo $_GET["account"];?> 」沒有人使用！');window.location.href='login.php#modal';
					</script>
<?php }?>
<p class="heading">忘記密碼？</p>
					<form name="form1" method="post" action="">
						<p>請輸入您申請的帳號，系統將自動產生一個十位數的密碼寄到您註冊的信箱。</p>
						<p>
							<strong>帳號</strong>：<br> <input name="account" type="text"
								class="logintextbox" id="mail">
						</p>
						<p align="center">
							<input type="submit" name="button" id="button" value="寄密碼信"> <input
								type="button" name="button2" id="button2" value="回上一頁"
								onClick="window.history.back();">
						</p>
					</form>
					<hr size="1" />
					<p class="heading">還不是會員?</p>
					<p align="right">
						<a href="customer_join.php">加入會員</a>
					</p>
				</div>
				<div class="boxbl"></div>
				<div class="boxbr"></div>
			</td>
		</tr>
	</table>
</body>
</html>