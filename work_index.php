<?php
session_start();
require_once("connMysql.php");
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
$query_RecWork = "SELECT Work.WorkID , Work.WorkName , Work.WorkPrice, WorkPicture.PictureURL, count( WorkPicture.PictureID ) AS pictureNum FROM Work LEFT JOIN WorkPicture ON Work.WorkID = WorkPicture.WorkID GROUP BY Work.WorkID, Work.WorkName, Work.WorkPrice ORDER BY WorkID DESC";
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
<style type="text/css"></style>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#cccccc">
<table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
   <td><div class="titleDiv"> 作品集<br />
   </div>
    <div class="menulink"><a href="work_index.php">作品集首頁</a> 
    <?php if (isset($_SESSION["loginStore"]) || ($_SESSION["loginStore"]!="")) {?>
    <a href="work_manage.php">作品管理</a>
    <?php }?>
    </div></td>
  </tr>
  <tr>
   <td><div id="mainRegion">
     <table width="90%" border="0" align="center" cellpadding="4" cellspacing="0">
       <tr>
         <td><div class="subjectDiv"> 作品總覽 </div>
           <div class="actionDiv">作品總數: <?php echo $total_records;?></div>  
           <div class="normalDiv"></div>
<?php	while($row_RecWork=$RecWork->fetch_assoc()){ ?>
           <div class="albumDiv">
           <div class="picDiv"><a href="work_show.php?id=<?php echo $row_RecWork["WorkID"];?>"><?php if($row_RecWork["pictureNum"]==0){?><img src="workpicture/nopicture.jpg" alt="暫無照片" width="120" height="120" border="0" /><?php }else{?><img src="workpicture/<?php echo $row_RecWork["PictureURL"];?>" alt="<?php echo $row_RecWork["WorkName"];?>" width="120" height="120" border="0" /><?php }?></a></div>
           <div class="albuminfo"><a href="work_show.php?id=<?php echo $row_RecWork["WorkID"];?>"><?php echo $row_RecWork["WorkName"];?></a><br />
             <span class="smalltext">共 <?php echo $row_RecWork["pictureNum"];?> 張</span><br>
             <br>
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