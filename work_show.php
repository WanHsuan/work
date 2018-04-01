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
$sid = 0;
if(isset($_GET["id"])&&($_GET["id"]!="")){
    $sid = GetSQLValueString($_GET["id"],"int");
}
//顯示相簿資訊SQL敘述句
$query_RecWork = "SELECT * FROM Work WHERE WorkID={$sid}";
//顯示照片SQL敘述句
$query_RecPicture = "SELECT * FROM WorkPicture WHERE WorkID={$sid} ORDER BY WorkID DESC";
//將二個SQL敘述句查詢資料儲存到 $RecAlbum、$RecPhoto 中
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
<title>作品</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#cccccc">
<table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
   <td><div class="titleDiv">作品</div>
    <div class="menulink"><a href="work_index.php">作品集首頁</a> <a href="work_manage.php">作品管理</a></div></td>
  </tr>
  <tr>
   <td><div id="mainRegion">
     <table width="90%" border="0" align="center" cellpadding="4" cellspacing="0">
       <tr>
         <td><div class="subjectDiv"> <?php echo $row_RecWork["WorkName"];?>
           </div>
           <div class="actionDiv">照片總數：<?php echo $total_records;?></div>
           <div class="normalDiv">
             <p><strong>作品價格</strong>：<?php echo $row_RecWork["WorkPrice"];?></p>
             </div>
           <?php while($row_RecPicture=$RecPicture->fetch_assoc()){?>
           <div class="albumDiv">
           <div class="picDiv"><a href="work_picture.php?id=<?php echo $row_RecPicture["PictureID"];?>"><img src="workpicture/<?php echo $row_RecPicture["PictureURL"];?>" alt="<?php echo $row_RecPicture["PictureName"];?>" width="120" height="120" border="0" /></a></div>
			</div>
           </div>
           <?php }?></td>
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