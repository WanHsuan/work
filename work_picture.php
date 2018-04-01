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
        case "url":
            $theValue = ($theValue != "") ? filter_var($theValue, FILTER_VALIDATE_URL) : "";
            break;
    }
    return $theValue;
}
require_once("connMysql.php");
$pid = 0;
if(isset($_GET["id"])&&($_GET["id"]!="")){
    $pid = GetSQLValueString($_GET["id"],"int");
}
//顯示照片SQL敘述句
$query_RecPicture = "SELECT Work.WorkName,WorkPicture.* FROM Work,WorkPicture WHERE (Work.WorkID=WorkPicture.WorkID) AND PictureID={$pid}";
//將SQL敘述句查詢資料到 $result 中
$RecPicture = $db_link->query($query_RecPicture);
//取得相簿資訊
$row_RecPicture=$RecPicture->fetch_assoc();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>作品集</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#cccccc">
<table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><div class="titleDiv">作品照片</div>
      <div class="menulink"><a href="work_index.php">作品集首頁</a> <a href="work_manage.php">作品管理</a></div></td>
  </tr>
  <tr>
    <td><div id="mainRegion">
        <table width="90%" border="0" align="center" cellpadding="4" cellspacing="0">
          <tr>
            <td><div class="subjectDiv"><?php echo $row_RecPicture["WorkName"];?></div>
              <div class="actionDiv"><a href="work_show.php?id=<?php echo $row_RecPicture["WorkID"];?>">回上一頁</a></div>
              <div class="photoDiv"><img src="workpicture/<?php echo $row_RecPicture["PictureURL"];?>" /></div>
              </td>
          </tr>
        </table>
      </div></td>
  </tr>
</table>
</body>
</html>
<?php
	$db_link->close();
?>