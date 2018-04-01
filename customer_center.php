<?php
require_once ("connMysql.php");
session_start();
//檢查是否經過登入
if(!isset($_SESSION["loginCustomer"]) || ($_SESSION["loginCustomer"]=="")){
    header("Location: login.php");
}
//執行登出
if(isset($_GET["logout"])&&($_GET["logout"]=="true")){
    unset($_SESSION["loginCustomer"]);
    unset($_SESSION["customerid"]);
    header("Location: login.php");
}

//echo $_SESSION["loginCustomer"];
//echo $_SESSION["customerid"];

//繫結會員資料
$query_RecMember = "SELECT * FROM Customer WHERE CustomerUsername = '{$_SESSION["loginCustomer"]}'";
$RecMember = $db_link->query($query_RecMember);
$row_RecMember=$RecMember->fetch_assoc();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>會員專區</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="780" border="0" align="center" cellpadding="4" cellspacing="0">
	<td width="200">
        <div class="boxtl"></div><div class="boxtr"></div>
		<div class="regbox">
          <p class="heading"><strong>會員專區</strong></p>
          
          <p><strong><?php echo $row_RecMember["CustomerName"];?></strong> 您好。</p>
          <p>會員資料</p><br>
          <p>聯絡電話：<?php echo $row_RecMember["CustomerPhone"];?></p>
          <p>生日：<?php echo $row_RecMember["CustomerBirth"];?></p>
          <p>電子郵件：<?php echo $row_RecMember["CustomerMail"];?></p>
           <p align="center"><a href="customer_update.php">修改資料</a> | 
           <a href="?logout=true">登出系統</a></p>
         </div>
    </td>
</table>
</body>
</html>
<?php 
    $db_link->close();
?>