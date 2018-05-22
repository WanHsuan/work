<?php
function GetSQLValueString($theValue, $theType){
    switch ($theType){
        case "string":
            $theValue = ($theValue != "") ? filter_var($theValue, FILTER_SANITIZE_MAGIC_QUOTES) : "";
            break;
        case "int":
            $theValue = ($theValue != "") ? filter_var($theValue, FILTER_SANITIZE_NUMBER_INT) : "";
            break;
        case "email":
            $theValue = ($theValue != "") ? filter_var($theValue, FILTER_VALIDATE_EMAIL) : "";
            break;
    }
    return  $theValue;
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
    header("Location: login.php");
}
//重新導向頁面
$redirectUrl = "store_center.php";

$query_RecPhoto="SELECT * FROM store WHERE StoreID='{$_SESSION["storeid"]}'";
$RecPhoto=$db_link->query($query_RecPhoto);
$row_RecPhoto=$RecPhoto->fetch_assoc();

if (isset($_POST["action"]) && ($_POST["action"]=="update")){
    $query_delete="DELETE StorePhoto FROM store WHERE StoreID='{$_SESSION["storeid"]}'";
    $db_link->query($query_delete);
    unlink("storephoto/".$row_RecPhoto["StorePhoto"]);
    
    $src_file = $_FILES["photo"]["tmp_name"];
    $src_file_name = $_FILES["photo"]["name"];
    $src_ext = strtolower(strrchr($_FILES["photo"]["name"], "."));
    $desc_file_name = uniqid() . ".jpg";
    
    $photo_file_name = "./storephoto/$desc_file_name";
    resize_photo($src_file, $src_ext, $photo_file_name, 600);
    
    $query_update="UPDATE store SET StorePhoto=? WHERE StoreID='{$_SESSION["storeid"]}'";
    $stmt = $db_link->prepare($query_update);
    $stmt->bind_param("s",
        $desc_file_name);
    $stmt->execute();
    $stmt->close();
    
    header("Location: store_update.php");
    mysqli_free_result($stmt);
    mysqli_free_result($RecPhoto);
   
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>更改店家照片</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
	function checkForm(){
		if(document.form1.photo.value==""){
			alert("請上傳照片！若不更新請按「回上一頁」");
			document.form1.photo.focus();
			return false;
		}
		return confirm("確定送出嗎？");
	}
</script>
</head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
   <td class="tdbline"><table width="100%" border="0" cellspacing="0" cellpadding="10">
  	<tr valign="top">
	 <td class="tdrline"><form action="" method="POST" name="form1" id="form1" enctype="multipart/form-data" onSubmit="return checkForm();">
	  <p class="title">修改照片</p>
	   <div class="dataDiv">
		<hr size="1" />
		 <div class="picDiv"><img src="storephoto/<?php echo $row_RecMember["StorePhoto"];?>" alt="店家照片" width="120" height="120" border="0" /></div>
		 <div class="photpDiv">
		 <p class="heading">新增照片</p>
		 <p><input type="file" name="photo" id="photo" accept="image/jpg, image/gif, image/png, image/jpeg" /></p>
		 <p>
		   <input name="action" type="hidden" id="action" value="update">
		   <input type="submit" name="button" id="button" value="確定修改" />
           <input type="button" name="button2" id="button2" value="回上一頁" onClick="window.history.back()" />
           </p>
		 </div>
		</div>
	   </form>
	  </td>
	 </tr>
	</table>
   </td>
  </tr>
 </table>
</body>
</html>