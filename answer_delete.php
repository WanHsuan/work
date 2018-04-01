<?php
require_once ("connMysql.php");
session_start();
//檢查是否登入
if(!isset($_SESSION["loginStore"]) || ($_SESSION["loginStore"]=="")){
    header("Location: login.php");
}

if(isset($_GET["logout"]) && ($_GET["logout"]=="true")){
    unset($_SESSION["loginStore"]);
    unset($_SESSION["storeid"]);
    header("Location: login.php");
}

//echo $_GET["id"];
//echo $_SESSION["storeid"];

if(isset($_POST["action"])&&($_POST["action"]=="delete")){
    $query_delete = "DELETE FROM Answer WHERE AnswerID=?";
    $stmt = $db_link->prepare($query_delete);
    $stmt->bind_param("s",$_POST["id"]);
    $stmt->execute();
    $stmt->close();
    //重新導向回到主畫面
    header("Location: answer_index.php");
}
$query_RecAnswer = "SELECT AnswerID, AnswerContent FROM Answer WHERE AnswerID=?";
$stmt = $db_link->prepare($query_RecAnswer);
$stmt->bind_param("s",$_GET["id"]);
$stmt->execute();
$stmt->bind_result($id, $content);
$stmt->fetch();
?>
<html>
<head>
<title>問答</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#ffffff">
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><div id="mainRegion">
        <form name="form1" method="post" action="">
          <table width="90%" border="0" align="center" cellpadding="4" cellspacing="0">
            <tr valign="top">
              <td class="heading">刪除回覆
              </td>
            </tr>
            <tr valign="top">
              <td>
                <p><strong>回覆</strong> : <?php echo nl2br($content);?></p>
              </td>
            </tr>
            <tr valign="top">
              <td align="center"><p>
                  <input name="id" type="hidden" id="id" value="<?php echo $id;?>">
                  <input name="action" type="hidden" id="action" value="delete">
                  <input type="submit" name="button" id="button" value="確定刪除">
                  <input type="button" name="button3" id="button3" value="回上一頁" onClick="window.history.back();">
                </p></td>
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
    $stmt->close();
?>