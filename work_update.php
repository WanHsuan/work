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
session_start();
//檢查是否經過登入
if(!isset($_SESSION["loginStore"]) || ($_SESSION["loginStore"]=="")){
    header("Location: login.php");
}
//執行登出動作
if(isset($_GET["logout"]) && ($_GET["logout"]=="true")){
    unset($_SESSION["loginStore"]);
    unset($_SESSION["storeid"]);
    header("Location: work_index.php");
}
//更新相簿
if(isset($_POST["action"])&&($_POST["action"]=="update")){
    //更新相簿資訊
    $query_update = "UPDATE Work SET WorkName=?, WorkPrice=? WHERE WorkID=?";
    $stmt = $db_link->prepare($query_update);
    $stmt->bind_param("ssi",
        GetSQLValueString($_POST["workname"], "string"),
        GetSQLValueString($_POST["price"], "string"),
        GetSQLValueString($_POST["workid"], "int"));
    $stmt->execute();
    $stmt->close();

    //執行檔案刪除
    for ($i=0; $i<count($_POST["delcheck"]); $i++) {
        $delid = $_POST["delcheck"][$i];
        $query_del = "DELETE FROM WorkPicture WHERE PictureID={$_POST["picid"][$delid]}";
        $db_link->query($query_del);
        unlink("workpicture/".$_POST["delfile"][$delid]);
    }
    //執行照片新增及檔案上傳
    for ($i=0; $i<count($_FILES["picture"]); $i++) {
        if ($_FILES["picture"]["tmp_name"][$i] != "") {
            $query_insert = "INSERT INTO WorkPicture (WorkID, PictureURL) VALUES (?, ?)";
            $stmt = $db_link->prepare($query_insert);
            $stmt->bind_param("is",
                GetSQLValueString($_POST["workid"], "int"),
                GetSQLValueString($_FILES["picture"]["name"][$i], "string"));
            $stmt->execute();
            $stmt->close();
            if(!move_uploaded_file($_FILES["picture"]["tmp_name"][$i] , "workpicture/" . $_FILES["picture"]["name"][$i])) die("檔案上傳失敗！");
        }
    }
    //重新導向回到本畫面
    header("Location: ?id=".$_POST["workid"]);
}
//顯示相簿資訊SQL敘述句
$sid = 0;
if(isset($_GET["id"])&&($_GET["id"]!="")){
    $sid = GetSQLValueString($_GET["id"],"int");
}
$query_RecWork = "SELECT * FROM Work WHERE WorkID={$sid}";
//顯示照片SQL敘述句
$query_RecPicture = "SELECT * FROM WorkPicture WHERE WorkID={$sid} ORDER BY PictureID DESC";
//將二個SQL敘述句查詢資料到 $RecAlbum、$RecPhoto 中
$RecWork = $db_link->query($query_RecWork);
$RecPicture = $db_link->query($query_RecPicture);
//計算照片總筆數
$total_records = $RecPicture->num_rows;
//取得相簿資訊
$row_RecWork=$RecWork->fetch_assoc();
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
    <td height="124" valign="top"><div class="titleDiv"> 作品更新<br />
      </div>
      <div class="menulink"><a href="work_manage.php">作品管理</a> <a href="?logout=true">登出</a></div></td>
  </tr>
  <tr>
    <td background="images/album_r2_c1.jpg"><div id="mainRegion">
        <table width="90%" border="0" align="center" cellpadding="4" cellspacing="0">
          <tr>
            <td><div class="subjectDiv"> 修改作品資訊</div>
              <div class="actionDiv">照片總數: <?php echo $total_records;?></div>
              <form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
                <div class="normalDiv">
                  <p class="heading">作品內容</p>
                  <p>作品名稱：<input name="workname" type="text" id="workname" value="<?php echo $row_RecWork["WorkName"];?>" />
                  <input name="workid" type="hidden" id="workid" value="<?php echo $row_RecWork["WorkID"];?>" /></p>
                  <p>作品價格：<input name="price" type="text" id="price" value="<?php echo $row_RecWork["WorkPrice"];?>" /></p>
                  <hr />
                </div>
                <?php
			   $checkid=0;
			   while($row_RecPicture=$RecPicture->fetch_assoc()){
			   ?>
                <div class="albumDiv">
                  <div class="picDiv"><img src="workpicture/<?php echo $row_RecPicture["PictureURL"];?>" alt="" width="120" height="120" border="0" /></div>
                  <div class="picinfo">
                    <p>
                      <input name="picid[]" type="hidden" id="picid[]" value="<?php echo $row_RecPicture["PictureID"];?>" />
                      <input name="delfile[]" type="hidden" id="delfile[]" value="<?php echo $row_RecPicture["PictureURL"];?>">
                      <br />
                      <input name="delcheck[]" type="checkbox" id="delcheck[]" value="<?php echo $checkid;$checkid++?>" />刪除?</p>
                  </div>
                </div>
                <?php }?>
                <div class="normalDiv">
                  <hr />
                  <p class="heading">新增照片</p>
                  <div class="clear"></div>
                  <p>照片1<input type="file" name="picture[]" id="picture[]" />
                  <p>照片2<input type="file" name="picture[]" id="picture[]" />
                  <p>照片3<input type="file" name="picture[]" id="picture[]" />
                  <p>照片4<input type="file" name="picture[]" id="picture[]" />
                  <p>照片5<input type="file" name="picture[]" id="picture[]" />
                  <p>
                    <input name="action" type="hidden" id="action" value="update">
                    <input type="submit" name="button" id="button" value="確定修改" />
                    <input type="button" name="button2" id="button2" value="回上一頁" onClick="window.history.back()" />
                  </p>
                </div>
              </form></td>
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