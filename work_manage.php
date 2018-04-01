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
    header("Location: login.php?loginStats=2");
}
//執行登出動作
if(isset($_GET["logout"]) && ($_GET["logout"]=="true")){
    unset($_SESSION["loginStore"]);
    unset($_SESSION["storeid"]);
    header("Location: work_index.php");
}
$sid = 0;
if(isset($_GET["id"])&&($_GET["id"]!="")){
    $sid = GetSQLValueString($_GET["id"],"int");
}
//刪除相簿
if(isset($_GET["action"])&&($_GET["action"]=="delete")){
    //刪除所屬相片
    $query_delpicture = "SELECT * FROM WorkPicture WHERE WorkID={$sid}";
    $delpicture = $db_link->query($query_delpicture);
    while($row_delpicture=$delpicture->fetch_assoc()){
        unlink("workpicture/".$row_delpicture["PictureURL"]);
    }
    //刪除相簿
    $query_del1 = "DELETE FROM Work WHERE WorkID={$sid}";
    $query_del2 = "DELETE FROM WorkPicture WHERE WorkID={$sid}";
    $db_link->query($query_del1);
    $db_link->query($query_del2);
    //重新導向回到主畫面
    header("Location: work_manage.php");
}
//預設每頁筆數
$pageRow_records = 8;
//預設頁數
$num_pages = 1;
//若已經有翻頁，將頁數更新
if (isset($_GET['page'])) {
    $num_pages = $_GET['page'];
}
//本頁開始記錄筆數 = (頁數-1)*每頁記錄筆數
$startRow_records = ($num_pages -1) * $pageRow_records;
//未加限制顯示筆數的SQL敘述句
$query_RecWork = "SELECT Work.WorkID , Work.WorkName , Work.WorkPrice, WorkPicture.PictureURL, count( WorkPicture.PictureID ) AS pictureNum FROM Work LEFT JOIN WorkPicture ON Work.WorkID = WorkPicture.WorkID WHERE Work.StoreID='{$_SESSION["storeid"]}' GROUP BY Work.WorkID, Work.WorkName, Work.WorkPrice ORDER BY WorkID DESC";
//加上限制顯示筆數的SQL敘述句，由本頁開始記錄筆數開始，每頁顯示預設筆數
$query_limit_RecWork = $query_RecWork." LIMIT {$startRow_records}, {$pageRow_records}";
//以加上限制顯示筆數的SQL敘述句查詢資料到 $RecAlbum 中
$RecWork = $db_link->query($query_limit_RecWork);
//以未加上限制顯示筆數的SQL敘述句查詢資料到 $all_RecAlbum 中
$all_RecWork = $db_link->query($query_RecWork);
//計算總筆數
$total_records = $all_RecWork->num_rows;
//計算總頁數=(總筆數/每頁筆數)後無條件進位。
$total_pages = ceil($total_records/$pageRow_records);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>作品集</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script language="javascript">
function deletesure(){
    if (confirm('\n您確定要刪除整個作品嗎?\n刪除後無法恢復!\n')) return true;
    return false;
}
</script>
</head>
<body bgcolor="#cccccc">
<table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
   <td><div class="titleDiv"> 作品管理<br />
   </div>
    <div class="menulink"><a href="work_manage.php">管理首頁</a> <a href="?logout=true">登出</a></div></td>
  </tr>
  <tr>
   <td><div id="mainRegion">
     <table width="90%" border="0" align="center" cellpadding="4" cellspacing="0">
       <tr>
         <td><div class="subjectDiv"> 作品管理 </div>
           <div class="actionDiv">作品總數: <?php echo $total_records;?>，<a href="	work_upload.php">新增作品</a></div>  
           <div class="normaldesc"></div>
           <?php while($row_RecWork=$RecWork->fetch_assoc()){ ?>
           <div class="albumDiv">
           <div class="picDiv"><a href="work_update.php?id=<?php echo $row_RecWork["WorkID"];?>"><?php if($row_RecWork["pictureNum"]==0){?><img src="workpicture/nopicture.jpg" alt="暫無照片" width="120" height="120" border="0" /><?php }else{?><img src="workpicture/<?php echo $row_RecWork["PictureURL"];?>" alt="<?php echo $row_RecWork["PictureName"];?>" width="120" height="120" border="0" /><?php }?></a></div>
           <div class="workinfo"><a href="work_update.php?id=<?php echo $row_RecWork["WorkID"];?>"><?php echo $row_RecWork["WorkName"];?></a><br />
             <span class="smalltext">共 <?php echo $row_RecWork["pictureNum"];?> 張</span><br>
             <a href="?action=delete&id=<?php echo $row_RecWork["WorkID"];?>" class="smalltext" onClick="javascript:return deletesure();">(刪除作品)</a><br>
           </div>
           </div>
           <?php }?>
           <div class="navDiv">
		   <?php if ($num_pages > 1) { // 若不是第一頁則顯示 ?>
             <a href="?page=1">|&lt;</a> <a href="?page=<?php echo $num_pages-1;?>">&lt;&lt;</a>
           <?php }else{?>
             |&lt; &lt;&lt;  
		   <?php }?>
             <?php
		  	  for($i=1;$i<=$total_pages;$i++){
		  	  	  if($i==$num_pages){
		  	  	  	  echo $i." ";
		  	  	  }else{
		  	  	      echo "<a href=\"?page=$i\">$i</a> ";
		  	  	  }
		  	  }
		  	 ?>
             <?php if ($num_pages < $total_pages) { // 若不是最後一頁則顯示 ?>
             <a href="?page=<?php echo $num_pages+1;?>">&gt;&gt;</a> <a href="?page=<?php echo $total_pages;?>">&gt;|</a>
             <?php }else{?>
             &gt;&gt; &gt;|
			 <?php }?>
           </div></td>
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