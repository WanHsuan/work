<?php
require_once ("connMysql.php");
session_start();
if (! isset($_SESSION["loginStore"]) || ($_SESSION["loginStore"] == "")) {
    header("Location: login.php");
}
// 執行登出動作
if (isset($_GET["logout"]) && ($_GET["logout"] == "true")) {
    unset($_SESSION["loginStore"]);
    unset($_SESSION["storeid"]);
    header("Location: login.php");
}
?>
<?php

function GetSQLValueString($theValue, $theType)
{
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

function resize_photo($src_file, $src_ext, $dest_name, $max_size)
{
    switch ($src_ext)
    {
        case ".jpg":
            $src = imagecreatefromjpeg($src_file);
            break;
        case ".jpeg":
            $src = imagecreatefromjpeg($src_file);
            break;
        case ".png":
            $src = imagecreatefrompng($src_file);
            break;
        case ".gif":
            $src = imagecreatefromgif($src_file);
            break;
    }
    
    $src_w = imagesx($src);
    $src_h = imagesy($src);
    
    //建立新的空圖片
    if($src_w > $src_h)
    {
        $thumb_w = $max_size;
        $thumb_h = intval($src_h / $src_w * $thumb_w);
    }
    else
    {
        $thumb_h = $max_size;
        $thumb_w = intval($src_w / $src_h * $thumb_h);
    }
    
    $thumb = imagecreatetruecolor($thumb_w, $thumb_h);
    
    //進行複製並縮圖
    imagecopyresized($thumb, $src, 0, 0, 0, 0, $thumb_w, $thumb_h, $src_w, $src_h);
    
    //儲存相片
    imagejpeg($thumb, $dest_name, 100);
    
    //釋放影像佔用的記憶體
    imagedestroy($src);
    imagedestroy($thumb);
}

// 更新相簿
if (isset($_POST["action"]) && ($_POST["action"] == "update")) {
    // 更新相簿資訊
    $query_update = "UPDATE Work SET WorkName=?, WorkPrice=? WHERE WorkID=?";
    $stmt = $db_link->prepare($query_update);
    $stmt->bind_param("ssi", GetSQLValueString($_POST["workname"], "string"), GetSQLValueString($_POST["price"], "string"), GetSQLValueString($_POST["workid"], "int"));
    $stmt->execute();
    $stmt->close();
    
    // 執行檔案刪除
    for ($i = 0; $i < count($_POST["delcheck"]); $i ++) {
        $delid = $_POST["delcheck"][$i];
        $query_del = "DELETE FROM WorkPicture WHERE PictureID={$_POST["picid"][$delid]}";
        $db_link->query($query_del);
        unlink("workpicture/" . $_POST["delfile"][$delid]);
    }
    // 執行照片新增及檔案上傳
    for ($i = 0; $i < count($_FILES["picture"]); $i ++) {
        if ($_FILES["picture"]["tmp_name"][$i] != "") {
            
            $src_file = $_FILES["picture"]["tmp_name"][$i];
            $src_file_name = $_FILES["picture"]["name"][$i];
            $src_ext = strtolower(strrchr($_FILES["picture"]["name"][$i], "."));
            $desc_file_name = uniqid() . ".jpg";
            
            $photo_file_name = "./workpicture/$desc_file_name";
            resize_photo($src_file, $src_ext, $photo_file_name, 600);
            
            $query_insert = "INSERT INTO WorkPicture (WorkID, PictureURL) VALUES (?, ?)";
            $stmt = $db_link->prepare($query_insert);
            $stmt->bind_param("is", GetSQLValueString($_POST["workid"], "int"), $desc_file_name);
            $stmt->execute();
            $stmt->close();
            
        }
    }
    // 重新導向回到本畫面
    header("Location: ?id=" . $_POST["workid"]);
}
// 顯示相簿資訊SQL敘述句
$sid = 0;
if (isset($_GET["id"]) && ($_GET["id"] != "")) {
    $sid = GetSQLValueString($_GET["id"], "int");
}
$query_RecWork = "SELECT * FROM Work WHERE WorkID={$sid}";
// 顯示照片SQL敘述句
$query_RecPicture = "SELECT * FROM WorkPicture WHERE WorkID={$sid} ORDER BY PictureID DESC";
// 將二個SQL敘述句查詢資料到 $RecAlbum、$RecPhoto 中
$RecWork = $db_link->query($query_RecWork);
$RecPicture = $db_link->query($query_RecPicture);
// 計算照片總筆數
$total_records = $RecPicture->num_rows;
// 取得相簿資訊
$row_RecWork = $RecWork->fetch_assoc();
?>
<?php

require_once("connectmysql.php");
//建立資料連接
$link = create_connection();
// 搜尋全部店家
$sql99 = "SELECT * FROM store";
$result99 = execute_sql($link, "handstory", $sql99);
$total_records99 = mysqli_num_rows($result99);
$j99 = 1;
//待評價預約數
$StoreID = $_SESSION["storeid"];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width= device-width" />
<link rel="stylesheet" href="style.css" type="text/css">
<title>Hand's Story--修改作品</title>
<!--彈出框-->
<link rel="stylesheet" href="remodal.css">
<link rel="stylesheet" href="remodal-default-theme.css">
<link
	href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/themes/hot-sneaks/jquery-ui.css"
	rel="stylesheet">
<style>
h2 {
	padding-top: 25px;
	text-align: center;
	font-family: "翩翩體-繁";
	font-size: 26px;
	margin: 0px;
		color:#a7a5a7;
	display: inline;
}

.content {
	background-image: url(images/作品更新.jpg);
	width: 1380px;
	height: 800px;
}

table {
	position: absolute;
	top: 170px;
	left: 300px;
	padding:0px 100px;
	font-family: "翩翩體-繁";
	font-size: 18px;
		color:#a7a5a7;
	background-color: #FFF;
	
}

.inp {
	border: none;
	border-bottom: 1px solid darkgray;
	width: 300px;
	height: 25px;
	padding-left: 10px;
	font-family: "翩翩體-繁";
	font-size: 18px;
		color:#a7a5a7;
	background-color: none;
}

.buttons {
	margin: 2px;
	
}


.buttona {
	margin-left: 5px;
	
}
.pic {
	-webkit-transform: scale(1);
	-webkit-transition: 1s;
	border-radius:5px;
	box-shadow: #efefef 0px 0px 2px 2px;
}

.pic:hover {
	-webkit-transform: scale(3);
	border-radius:5px;
	z-index:3;
}

</style>
<script type="text/javascript"
	src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
<script type="text/javascript"
	src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script language="javascript">
function deletesure(){
    if (confirm('\n您確定要刪除整個作品嗎?\n刪除後無法恢復!\n')) return true;
    return false;
}
</script>
</head>
<body>
	<!--頁首-->
	<div id="apDiv7">
		<h1>美甲店一覽</h1>
		<div id="apDiv9"> <?php

		while ($row99 = mysqli_fetch_assoc($result99) and $j99 <= 10 and $j99 <= $total_records99) {
		    echo "<a class='const' href=' storest.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
		    $j99 ++;
}
?> </div>
		<div id="apDiv10"><?php
		echo "<a class='const' href=' storest.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
		while ($row99 = mysqli_fetch_assoc($result99) and $j99 < 20 and $j99 >= 11 and $j99 <= $total_records99) {
		    echo "<a class='const' href=' storest.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
    $j99 ++;
}
?></div>
		<div id="apDiv11"><?php
		echo "<a class='const' href=' storest.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
		while ($row99 = mysqli_fetch_assoc($result99) and $j99 < 29 and $j99 >= 20 and $j99 <= $total_records99) {
		    echo "<a class='const' href=' storest.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
    $j99 ++;
}
?></div>
		<div id="apDiv12"><?php
		echo "<a class='const' href=' storest.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
		while ($row99 = mysqli_fetch_assoc($result99) and $j99 < 38 and $j99 >= 29 and $j99 <= $total_records99) {
		    echo "<a class='const' href=' storest.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
    $j99 ++;
}
?></div>
	<a class='more' href="#modals" style='position:absolute;top:450px;left:1230px;cursor:pointer;color:#948096;text-decoration:none;'>Read more</a>
	</div>
	
	<!--頁首-->
	<header> <img src="images/登入後LOGO.fw.png" width="1380" usemap="#Map"
		border="0" /> <map name="Map" id="Map">
		<area shape="rect" coords="128,15,477,82" href="hstindex.php"
			target="_parent" />
	</map> </header>
	<!-- 會員中心 -->
	<div id="membercenterst">
		<ul id="center">
			<li><a href="storest.php?StoreID=<?php echo $StoreID?>" target="_parent">我的頁面</a></li>
			<li><a href="#" target="_parent">店家管理+</a>
				<ul>
					<li><a class="sub" href="store_update.php" target="_parent">修改資料</a></li>
					<li><a class="sub" href="work_manage.php" target="_parent" style="color:#e08fe0;">作品集管理</a></li>
					<li><a class="sub" href="marketing_show.php" target="_parent">活動管理</a></li>
				</ul></li>
			<li><a href="storehistorycomment.php" target="_parent" style='text-align:left;'>我的評論<?php 


// $link = create_connection();
//已經評論的
$sql3 =" SELECT StoreComment.TransactionID FROM StoreComment WHERE StoreComment.StoreID='$StoreID'";
$result3=execute_sql($link, "handstory", $sql3);
$total_records3=mysqli_num_rows($result3);

//選擇已經完成交易的
$sql4 = "SELECT TransactionID FROM Transaction WHERE TransactionActualDate!='0000-00-00 00:00:00' AND StoreID='$StoreID'";
$result4=execute_sql($link, "handstory", $sql4);
$total_records4=mysqli_num_rows($result4);

$total =$total_records4-$total_records3;
echo "<div class='countst' style='position:absolute;top:10px;left:260px;'>" .$total. "</div>";
mysqli_free_result($result3);
mysqli_free_result($result4);
?></a>
				<ul>
					<li><a class="sub" href="storehistorycomment.php" target="_parent">待評價</a></li>
					<li><a class="sub" href="storehistorycomment.php#history" target="_parent">評價紀錄</a></li>
				</ul></li>
			<li><a class="con" href="?logout=true">登出</a></li>

		</ul>



	</div>
	<!--menu-->
	<div id="menu">
		<ul id="buttonst">
			<li><a href="reservest.php" target="_parent">預約<?php 

//待審核預約
//已經評論的
$sql5 ="SELECT TransactionID FROM Transaction WHERE Transaction.StoreID='$StoreID' AND TransactionYesOrNO='0' AND TransactionCancel='0'";
$result5=execute_sql($link, "handstory", $sql5);
$total_records5=mysqli_num_rows($result5);

echo "<div class='countst1' style='position:absolute;top:97px;left:180px;'>" .$total_records5. "</div>";
mysqli_free_result($result5);
?>
			</a></li>
			<li><a id="st"">美甲店</a></li>
			<li><a href="searchpartialstorest.php" target="_parent">美甲地圖</a></li>
			<li><a href="groupst.php" target="_parent">論壇</a></li>
			<li><a href="#" target="_parent">我的賣場<?php 
//銷售平台待出貨訂單
//已經評論的
$sql5 ="SELECT ShoppingRecordID FROM ShoppingRecord WHERE ShoppingRecordProcessing='處理中' AND StoreID='$StoreID'";
$result5=execute_sql($link, "handstory", $sql5);
$total_records5=mysqli_num_rows($result5);

echo "<div class='countst1' style='position:absolute;top:97px;left:1300px;'>" .$total_records5. "</div>";
mysqli_free_result($result5);
?></a>
				<ul>
					<li><a class="sub" href="product_show.php" target="_parent">上傳/下架商品</a></li>
					<li><a class="sub" href="shoppingrecord_search_store.php" target="_parent">訂單管理</a></li>
				</ul></li>
		</ul>
	</div>
	<div class="content">
		<table width="800" border="0" align="center" cellpadding=""
			cellspacing="20" style="padding-left:0px 100px;">
			<tr>
				<td colspan="4" align="center">
					<h2>作品更新</h2>
				</td>
			</tr>
			<tr>

				<td colspan="4" align="right">照片總數: <?php echo $total_records;?></td></tr>
              <form action="" method="post"
						enctype="multipart/form-data" name="form1" id="form1">
						<tr><td colspan="3">作品名稱：<input name="workname" type="text" class='inp'
									value="<?php echo $row_RecWork["WorkName"];?>" /> <input
									name="workid" type="hidden" id="workid"
									value="<?php echo $row_RecWork["WorkID"];?>" /></td></tr>
								
							<tr><td colspan="3">作品價格：<input name="price" type="text" class='inp'
									value="<?php echo $row_RecWork["WorkPrice"];?>" /></td></tr>
								
						
                <?php
                
$total_count = ceil($total_records / 4) * 8;
$checkid = 0;
                for ($j = 0; $j < $total_count; $j ++) {
                    if ($j % 4 == 0) {
                        echo '<tr>';
                    }
                    if ($row_RecPicture = $RecPicture->fetch_assoc()) {
                        echo '<td style="width:150px;">';
                        
                        ?>
                
                <img
								src="workpicture/<?php echo $row_RecPicture["PictureURL"];?>" class='pic'
								alt="" width="120" height="120" border="0" />

							<br>
								<input name="picid[]" type="hidden" id="picid[]"
									value="<?php echo $row_RecPicture["PictureID"];?>" /> <input
									name="delfile[]" type="hidden" id="delfile[]"
									value="<?php echo $row_RecPicture["PictureURL"];?>"> <br /> <input
									name="delcheck[]" type="checkbox" id="delcheck[]"
									value="<?php echo $checkid;$checkid++?>" />刪除?
							</br>
                  
                
                <?php
                        
echo '</td>';
                    } else {
                        echo '<td style="width:150px;"></td>';
                    }
                    if ($j % 4 == 3) {
                        echo '</tr>';
                    }
                }
                ?>
                
						
							
							<tr><td colspan="4"><strong>新增照片</strong></td></tr>
							<tr><td colspan="4">
								照片1<input type="file" name="picture[]" id="picture[]" accept="image/jpg, image/gif, image/png, image/jpeg" /></td></tr>
							
							
							<tr><td colspan="4">
								照片2<input type="file" name="picture[]" id="picture[]" accept="image/jpg, image/gif, image/png, image/jpeg" /></td></tr>
							
							
							<tr><td colspan="4">
								照片3<input type="file" name="picture[]" id="picture[]" accept="image/jpg, image/gif, image/png, image/jpeg" /></td></tr>
							
							
							<tr><td colspan="4">
								照片4<input type="file" name="picture[]" id="picture[]" accept="image/jpg, image/gif, image/png, image/jpeg" /></td></tr>
							
							
							<tr><td colspan="4">
								照片5<input type="file" name="picture[]" id="picture[]" accept="image/jpg, image/gif, image/png, image/jpeg" /></td></tr>
							
							<tr><td colspan="4" align="center">
								<input name="action" type="hidden" id="action" value="update"> <input
									type="submit" name="button" class="buttons" value="確定修改" /> <input
									type="button" name="button2" class="buttons" value="回上一頁"
									onClick="window.history.back()" />
					
					</form>
			
	</table>
	</div>
	<!--彈出框-->
	<div class="remodal" data-remodal-id="modals" style='overflow: scroll; height: 500px;width:350px;'>
		<button data-remodal-action="close" class="remodal-close"></button>
		<?php
		$sql99 = "SELECT * FROM store";
		$result99 = execute_sql($link, "handstory", $sql99);
		$total_records99 = mysqli_num_rows($result99);
		$j99 = 1;
		
		while ($row99 = mysqli_fetch_assoc($result99) and  $j99 <= $total_records99) {
    echo "<a class='const' href=' storest.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
    $j99 ++;
}?>
	</div>
	<!--彈出框-->
	<script src="remodal.js"></script>
	<script>
		$(document).ready(function(){
			$('#st,#apDiv7').hover(function(){
				$('#apDiv7').show();
			},
			function(){
				$('#apDiv7').hide();
			})
			
		
		})

	</script>
</body>
</html>
<?php
// 釋放記憶體空間
mysqli_free_result($result99);

mysqli_free_result($RecWork);
mysqli_free_result($RecPicture);
// mysqli_free_result($stmt);

// 關閉資料連接
mysqli_close($link);
$db_link->close();
?>