<?php
function GetSQLValueString($theValue,$theType){
    switch ($theType){
        case "string":
            $theValue=($theValue != "") ? filter_var($theValue,FILTER_SANITIZE_MAGIC_QUOTES) : "";
            break;
        case "int":
            $theValue=($theValue != "") ? filter_var($theValue,FILTER_SANITIZE_NUMBER_INT) : "";
            break;
    }
    return $theValue;
}
session_start();
//檢查是否登入
if((!isset($_SESSION["loginCustomer"])) && (!isset($_SESSION["loginStore"]))){
    header("Location: login.php");
}
//echo $_GET["id"];
//echo $_SESSION["storeid"];

if(isset($_POST["action"])&&($_POST["action"]=="add")){
    require_once("connMysql.php");
    if (isset($_SESSION["loginStore"]) || ($_SESSION["loginStore"]!="")){
    $query_insert = "INSERT INTO Answer (QuestionID, AnswerContent, AnswerDateTime, StoreID, CustomerID) VALUES (?, ?, NOW(), ?, ?)";
    $stmt = $db_link->prepare($query_insert);
    $stmt->bind_param("ssii",
        GetSQLValueString($_GET["id"], "string"),
        GetSQLValueString($_POST["content"], "string"),
        GetSQLValueString($_SESSION["storeid"], "int"),
        GetSQLValueString(0, "int"));
    $stmt->execute();
    $stmt->close();
    //重新導向回到主畫面
    header("Location: groupst.php");
    }elseif (isset($_SESSION["loginCustomer"]) || ($_SESSION["loginCustomer"]!="")){
        $query_insert = "INSERT INTO Answer (QuestionID, AnswerContent, AnswerDateTime, StoreID, CustomerID) VALUES (?, ?, NOW(), ?, ?)";
        $stmt = $db_link->prepare($query_insert);
        $stmt->bind_param("ssii",
            GetSQLValueString($_GET["id"], "string"),
            GetSQLValueString($_POST["content"], "string"),
            GetSQLValueString(0, "int"),
            GetSQLValueString($_SESSION["customerid"], "int"));
        $stmt->execute();
        $stmt->close();
        header("Location: group.php");
    }
}
?>
<html>
<head>
<title>問答</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="style.css" rel="stylesheet" type="text/css">
<script language="javascript">
function checkForm(){	 
	if(document.formPost.content.value==""){
		alert("請填寫回覆內容!");
		document.formPost.content.focus();
		return false;
	}
		return confirm('確定送出嗎？');
}
</script>
</head>
<body>
 <table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
   <tr>
    <td><div id="mainRegion">
 	   <form action="" method="post" name="formPost" id="formPost" onSubmit="return checkForm();">
          <table width="90%" border="0" align="center" cellpadding="4" cellspacing="0">
            <tr valign="top">
              <td width="80" align="center"><span class="heading">回覆</span></td>
             <td>
			  <p><textarea name="content" id="content" rows="10" cols="40"></textarea></p>
			 </td>
		   </tr>
		   <tr valign="top">
              <td colspan="3" align="center" valign="middle">
    			<input name="action" type="hidden" id="action" value="add">
                <input type="submit" name="button" id="button" value="送出">
                <input type="reset" name="button2" id="button2" value="清空">
                <input type="button" name="button3" id="button3" value="回上一頁" onClick="window.history.back();"></td>
           </tr>
		 </table>
	   </form>
    </div></td>
   </tr>
 </table>
</body>
</html>
<?php 
    $db_link->close();
?>