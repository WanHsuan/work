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
    
    $query_update="UPDATE store SET StorePhoto=? WHERE StoreID='{$_SESSION["storeid"]}'";
    $stmt = $db_link->prepare($query_update);
    $stmt->bind_param("s",
        GetSQLValueString($_FILES["photo"]["name"], 'string'));
    $stmt->execute();
    $stmt->close();
    //if (!move_uploaded_file($_FILES["photo"]["tmp_name"], "storephoto/". $_FILES["photo"]["name"])) die("更新失敗！");
    header("Location: store_center.php");
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
		 <div class="picDiv"><img src="storephoto/<?php echo $row_RecPhoto["StorePhoto"];?>" alt=" " width="120" height="120" border="0" /></div>
		 <div class="photpDiv">
		 <p class="heading">新增照片</p>
		 <p><input type="file" name="photo" id="photo" /></p>
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