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
//新增相簿
if(isset($_POST["action"])&&($_POST["action"]=="upload")){
    $query_insert = "INSERT INTO Work (StoreID, WorkName, WorkPrice) VALUES (?, ?, ?)";
    $stmt = $db_link->prepare($query_insert);
    $stmt->bind_param("sss",
        GetSQLValueString($_SESSION["storeid"], "string"),
        GetSQLValueString($_POST["workname"], "string"),
        GetSQLValueString($_POST["price"], "string"));
    $stmt->execute();
    
    //取得新增的相簿編號
    $productid = $stmt->insert_id;
    $stmt->close();
    
    for ($i=0; $i<count($_FILES["picture"]["name"]); $i++) {
        if ($_FILES["picture"]["tmp_name"][$i] != "") {
            $query_insert = "INSERT INTO WorkPicture (WorkID, PictureURL) VALUES (?, ?)";
            $stmt = $db_link->prepare($query_insert);
            $stmt->bind_param("is",
                GetSQLValueString($productid, "int"),
                GetSQLValueString($_FILES["picture"]["name"][$i], "string"));
            $stmt->execute();
            if(!move_uploaded_file($_FILES["picture"]["tmp_name"][$i] , "workpicture/" . $_FILES["picture"]["name"][$i])) die("檔案上傳失敗！");
            $stmt->close();
        }
    }
    
    //重新導向到修改畫面
    header("Location: work_index.php");
}
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
    <td><div class="titleDiv"> 上傳作品<br />
      </div>
      <div class="menulink"><a href="store_index.php">會員專區</a> <a href="?logout=true">登出</a></div></td>
  </tr>
  <tr>
    <td><div id="mainRegion">
        <table width="90%" border="0" align="center" cellpadding="4" cellspacing="0">
          <tr>
            <td><div class="subjectDiv"> 新增作品集</div>
              <div class="actionDiv"><a href="#" onClick="window.history.back();">回上一頁</a></div>
              <div class="normalDiv">
                <form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
                  <p>作品名稱：<input type="text" name="workname" id="workname" /></p>
                  <p>作品價格：<input name="price" type="text" id="price" /></p>
                  <hr />
                  <p>照片1<input type="file" name="picture[]" id="picture[]" />
                  <p>照片2<input type="file" name="picture[]" id="picture[]" />
                  <p>照片3<input type="file" name="picture[]" id="picture[]" />
                  <p>照片4<input type="file" name="picture[]" id="picture[]" />
                  <p>照片5<input type="file" name="picture[]" id="picture[]" />
                  <p>
                    <input name="action" type="hidden" id="action" value="upload">    
                    <input type="submit" name="button" id="button" value="確定新增" />
                    <input type="button" name="button2" id="button2" value="回上一頁" onClick="window.history.back();" />
                  </p>
                </form>
              </div></td>
          </tr>
        </table>
      </div></td>
  </tr>
</table>
</body>
</html>