<?php
session_start();
// 檢查是否登入
if ((! isset($_SESSION["loginCustomer"])) && (! isset($_SESSION["loginStore"]))) {
    header("Location: login.php?loginStats=1");
}
// 執行登出
if (isset($_GET["logout"]) && ($_GET["logout"] == "true")) {
    unset($_SESSION["loginCustomer"]);
    unset($_SESSION["customerid"]);
    header("Location: login.php");
}
require_once 'connMysql.php';
// 預設每頁筆數
$pageRow_records = 5;
// 預設頁數
$num_pages = 1;
// 若有翻頁，將頁數更新
if (isset($_GET['page'])) {
    $num_pages = $_GET['page'];
}
// 本頁開始記錄筆數
$startRow_records = ($num_pages - 1) * $pageRow_records;
// 未加限制顯示筆數的sql敘述句
if ($_POST["subject"] != "") {
    $query_RecQuestion = "SELECT * FROM Question WHERE QuestionSubject like '%" . $_POST["subject"] . "%'";
} else {
    $query_RecQuestion = "SELECT * FROM Question ORDER BY QuestionDateTime DESC";
}
// 加上限制顯示筆數的SQL敘述句，由本頁開始記錄筆數開始，每頁顯示預設筆數
$query_limit_RecQuestion = $query_RecQuestion . " LIMIT {$startRow_records}, {$pageRow_records} ";
// 以加上限制顯示筆數的SQL敘述句查詢資料到 $RecQuestion 中
$RecQuestion = $db_link->query($query_limit_RecQuestion);
// 以未加上限制顯示筆數的SQL敘述句查詢資料到 $all_RecQuestion 中
$all_RecQuestion = $db_link->query($query_RecQuestion);
// 計算總筆數
$total_records = $all_RecQuestion->num_rows;
// 計算總頁數=(總筆數/每頁筆數)後無條件進位。
$total_pages = ceil($total_records / $pageRow_records);
?>
<?php 
require_once("connectmysql.php");


$Arr2=array();
//建立資料連接
$link = create_connection();

// 搜尋全部店家
$sql99 = "SELECT * FROM store";
$result99 = execute_sql($link, "handstory", $sql99);
$total_records99 = mysqli_num_rows($result99);
$j99 = 1;

// 搜尋常用店家
$CustomerID = $_SESSION["customerid"];
$sql100 = "SELECT CustomerUse.StoreID,StoreName FROM CustomerUse,store WHERE CustomerUse.CustomerID='$CustomerID' AND CustomerUse.StoreID=store.StoreID ";
$result100 = execute_sql($link, "handstory", $sql100);
$total_records100 = mysqli_num_rows($result100);
$i100 = 1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width= device-width" />
<link rel="stylesheet" href="style.css" type="text/css">
	<title>Hand's Story--論壇</title>
	<!--彈出框-->
<link rel="stylesheet" href="remodal.css">
<link rel="stylesheet" href="remodal-default-theme.css">
<link
	href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/themes/hot-sneaks/jquery-ui.css"
	rel="stylesheet">
	<style>
.group {
	width: 1380px;
	height: 600px;
	background-color:#f8f0f8; 
}

h2 {
	/* 	padding-top: 25px; */
	text-align: center;
	font-family: "翩翩體-繁";
	font-size: 20px;
	font-weight: bold;
	color:#a7a5a7;
	margin: 0px;
	display: inline;
}

#g {
	padding-top: 15px;
}

table {
	border-radius: 5px;
	z-index: 1;
}
table tr{
	border-radius: 5px;
	z-index: 1;
	color:#a7a5a7;
}
table td{
    border:1px #dcd8dc solid;
	border-radius: 5px;
	z-index: 1;
	color:#a7a5a7;
}

.postname {
	text-align: center;
	font-family: "翩翩體-繁";
	font-size: 16px;
	margin: 0px;
}

.context {
	text-align: left;
	font-family: "翩翩體-繁";
	font-size: 16px;
	margin: 0px;
}

.smalltext {
	text-align: right;
	font-family: "翩翩體-繁";
	color: #999;
	font-size: 12px;
	padding-left: 0px;
}

.num {
	text-align: center;
	font-family: "翩翩體-繁";
	font-size: 16px;
	margin: 0px;
	color:#a7a5a7;
}


.inp {
	border-radius: 5px;
	border: 1px solid darkgray;
	height: 25px;
	padding-left: 10px;
	font-family: "翩翩體-繁";
	font-size: 18px;
	outline: none;
}

.answer {
	width: 850px;
	font-size: 16px;
	font-family: "翩翩體-繁";
	background-color: #FFF;
	opacity: 0.8;
	border-radius: 5px;
	word-break: break-all;
}

#an {
	font-size: 16px;
	font-family: "翩翩體-繁";
	margin: 5px;
	padding: 0px;
	width: 850px;
	overflow: hidden; /* 超過範圍隱藏 */
	white-space: nowrap; /* 不斷行 */
}

#an li {
	/* 讓各個清單元素靠左對齊，由原本的垂直排列變成水平排列，形成水平清單 */
	padding-left: 5px;
	padding-right: 5px;
	float: left;
	text-align: center;
	background-color: #fff;
	list-style-type: none;
}

#an li a {
	/* 連結範圍充滿整個區塊 */
	display: block;
	/* 單個連結範圍的框度和高度*/
	width: 850px;
	height: 30px;
	line-height: 30px;
	/* 連結的文字設為白色以及無裝飾(無底線)*/
	color: #000;
	text-decoration: none;
	cursor: pointer;
}

#an li a:hover {
	color: #e08fe0;
}

#an li:hover>.sub {
	display: block;
}

#an .sub {
	display: none;
	border: solid 1px #B4B4B4;
	border-radius: 1px;
	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
	-webkit-box-shadow: 2px 2px 3px #222222;
	-moz-box-shadow: 2px 2px 3px #222222;
	box-shadow: 2px 2px 3px #222222;
	margin: 0;
	padding: 0;
	width: 830px;
	height: auto;
	z-index: 2;
}
</style>
	<script type="text/javascript"
	src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
<script type="text/javascript"
	src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	
</head>

<body>
	<!--頁首-->
	<div id="apDiv7">
		<h1>美甲店一覽</h1>
		<div id="apDiv9"> <?php

while ($row99 = mysqli_fetch_assoc($result99) and $j99 <= 10 and $j99 <= $total_records99) {
    echo "<a class='const' href=' store.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
    $j99 ++;
}
?> </div>
		<div id="apDiv10"><?php
echo "<a class='const' href=' store.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
while ($row99 = mysqli_fetch_assoc($result99) and $j99 < 20 and $j99 >= 11 and $j99 <= $total_records99) {
    echo "<a class='const' href=' store.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
    $j99 ++;
}
?></div>
		<div id="apDiv11"><?php
echo "<a class='const' href=' store.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
while ($row99 = mysqli_fetch_assoc($result99) and $j99 < 29 and $j99 >= 20 and $j99 <= $total_records99) {
    echo "<a class='const' href=' store.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
    $j99 ++;
}
?></div>
		<div id="apDiv12"><?php
echo "<a class='const' href=' store.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
while ($row99 = mysqli_fetch_assoc($result99) and $j99 < 38 and $j99 >= 29 and $j99 <= $total_records99) {
    echo "<a class='const' href=' store.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
    $j99 ++;
}
?></div>
	<a class='more' href="#modals" style='position:absolute;top:450px;left:1230px;cursor:pointer;color:#948096;text-decoration:none;'>Read more</a>
	</div>
	<div id="apDiv6">
		<h1>您的收藏店家</h1>
		<div id="apDiv13"> <?php

while ($row100 = mysqli_fetch_assoc($result100) and $i100 <= 5 and $i100 <= $total_records100) {
    echo "<a class='const' href=' store.php?StoreID=" . ($row100["StoreID"]) . "'>" . $row100["StoreName"] . "</a>" . "<br>";
    $i100 ++;
}
?></div>
		<div id="apDiv14"><?php
echo "<a class='const' href=' store.php?StoreID=" . ($row100["StoreID"]) . "'>" . $row100["StoreName"] . "</a>" . "<br>";
while ($row100 = mysqli_fetch_assoc($result100) and $i100 < 10 and $i100 >= 6 and $i100 <= $total_records100) {
    echo "<a class='const' href=' store.php?StoreID=" . ($row100["StoreID"]) . "'>" . $row100["StoreName"] . "</a>" . "<br>";
    $i100 ++;
}
?></div>
		<div id="apDiv15"><?php
		echo "<a class='const' href=' store.php?StoreID=" . ($row100["StoreID"]) . "'>" . $row100["StoreName"] . "</a>" . "<br>";
		while ($row100 = mysqli_fetch_assoc($result100) and $i100 < 14 and $i100 >= 10 and $i100 <= $total_records100) {
    echo "<a class='const' href=' store.php?StoreID=" . ($row100["StoreID"]) . "'>" . $row100["StoreName"] . "</a>" . "<br>";
    $i100 ++;
}
?></div>
		<div id="apDiv16"><?php
		echo "<a class='const' href=' store.php?StoreID=" . ($row100["StoreID"]) . "'>" . $row100["StoreName"] . "</a>" . "<br>";
		while ($row100 = mysqli_fetch_assoc($result100) and $i100 < 18 and $i100 >= 14 and $i100 <= $total_records100) {
    echo "<a class='const' href=' store.php?StoreID=" . ($row100["StoreID"]) . "'>" . $row100["StoreName"] . "</a>" . "<br>";
    $i100 ++;
}
?></div>
		<a class='more' href="#modalf" style='position:absolute;top:350px;left:1230px;cursor:pointer;color:#948096;text-decoration:none;'>Read more</a>
	</div>
	<!--我要提問按鈕-->
	<div id="apDiv2">
		<a class="buttona" href="question.php" target="_parent" style="font-size:20px;">我要提問</a>
	</div>
	<!--頁首-->
	<header> <img src="images/登入後LOGO.fw.png" width="1380" usemap="#Map"
		border="0" /> <map name="Map" id="Map">
      <?php if (isset($_SESSION["loginCustomer"]) || ($_SESSION["loginCustomer"]!="")) {?>
        <area shape="rect" coords="128,15,477,82" href="hcindex.php"
			target="_parent" /><?php }?>
      </map> </header>
	<!-- 會員中心 -->
	<div id="membercenter">
		<ul id="center">
			<li><a href="customer_update.php" target="_parent">修改資料</a></li>
			<li><a href="shoppingrecord_search_customer.php" target="_parent">我的訂單</a></li>
			<li><a href="consumerhistorycomment.php" target="_parent"
				style='text-align: left;'>我的評論<?php
  
    // 已經評論的
    $sql333 = " SELECT CustomerComment.TransactionID FROM CustomerComment WHERE CustomerComment.CustomerID='$CustomerID'";
    $result333 = execute_sql($link, "handstory", $sql333);
    $total_records333 = mysqli_num_rows($result333);
    
    // 選擇已經完成交易的
    $sql444 = "SELECT TransactionID FROM Transaction WHERE TransactionActualDate!='0000-00-00 00:00:00' AND CustomerID='$CustomerID'";
    $result444 = execute_sql($link, "handstory", $sql444);
    $total_records444 = mysqli_num_rows($result444);
    
    $totalr = $total_records444 - $total_records333;
    echo "<div class='countc' style='position:absolute;top:10px;left:260px;'>" . $totalr . "</div>";
    // echo "<div class='count'>" . $total . "</div>";
    mysqli_free_result($result333);
    mysqli_free_result($result444);
    ?></a>
				<ul>
					<li><a class="sub" href="consumerhistorycomment.php" target="_parent">待評價</a></li>
					<li><a class="sub" href="consumerhistorycomment.php#history" target="_parent">評價紀錄</a></li>
				</ul></li>
			<li><a class="con" href="?logout=true">登出</a></li>

		</ul>

	</div>
	<!--menu-->
	<div id="menu">
		<ul id="button">
			<li><a href="reserve.php" target="_parent">我的預約</a></li>
			<li><a id="st">美甲店</a></li>
			<li><a href="searchpartialstore.php" target="_parent">美甲地圖</a></li>
			<li><a id="fav">收藏店家</a></li>
			<li><a class='focus' href="group.php" target="_parent">論壇</a></li>
			<li><a href="product_showall_customer.php" target="_parent">銷售平台</a></li>
		</ul>
	</div>
	<!--論壇背景-->
	<div class="group">

		<table width="200" border="0" align="center" cellpadding="10"
			cellspacing="0">
			<form name="form1" method="post" id="form1" action="">


				<tr>
					<td width="150" style="border:none;"><input name="subject" id="subject" type="search"
						placeholder="搜尋主題" class="inp"></input></td>
					<td style="border:none;"><input type="submit" name="submit" class="buttons" value="搜尋"></td>
				</tr>
			</form>
		</table>
  		
	<?php while ($row_RecQuestion=$RecQuestion->fetch_assoc()){ ?>
	<table width="1000" border="0" align="center" cellpadding="4"
			cellspacing="0">
			<tr valign="top" >
				<td colspan="2" align="center" style="color:#dcd8dc;"><h2>討論主題:
			<?php echo $row_RecQuestion["QuestionSubject"]; ?></h2></td>
			</tr>
			<tr style="border-color:#dcd8dc;">
				<td width="100" align="center" class="underline" ><span
					class="postname">
			<?php
    
    $query_RecCustomer = "SELECT * FROM Customer WHERE CustomerID='{$row_RecQuestion["CustomerID"]}'";
    $RecCustomer = $db_link->query($query_RecCustomer);
    $row_RecCustomer = $RecCustomer->fetch_assoc();
    if ($row_RecCustomer["CustomerID"] == $row_RecQuestion["CustomerID"]) {
        echo $row_RecCustomer["CustomerName"];
    }
    ?> </span></td>
				<td class="underline" >
					<p style="word-break: break-all;"><?php echo nl2br($row_RecQuestion["QuestionContent"]);?> 
					</p>
					<p align="right" class="smalltext">
						<a class="buttona"
							href="answer.php?id=<?php echo $row_RecQuestion["QuestionID"];?>">我要回答</a>			
				<?php echo $row_RecQuestion["QuestionDateTime"];?>
				<br>
				<?php
    
    $query_RecAnswer = "SELECT * FROM Answer WHERE QuestionID='{$row_RecQuestion["QuestionID"]}'";
    $query_RecAnswerC = "SELECT * FROM AnswerCustomer WHERE QuestionID='{$row_RecQuestion["QuestionID"]}'";
    $RecAnswer = $db_link->query($query_RecAnswer);
    $RecAnswerC = $db_link->query($query_RecAnswerC);
    $i = 0;
    $j = 0;
    echo '<div class="answer"><ul id="an"><li><a>查看回答</a><div class="sub">';
    // echo "<option class='answer' value='' selected='selected'>查看回答...</option>";
    while ($row_RecAnswer = $RecAnswer->fetch_assoc()) {
        
        $i ++;
        // echo "<div>";
        if ($row_RecQuestion["QuestionID"] == $row_RecAnswer["QuestionID"] && $row_RecAnswer["AnswerContent"] != "") {
            $query_RecStore = "SELECT * FROM store WHERE StoreID='{$row_RecAnswer["StoreID"]}'";
            $RecStore = $db_link->query($query_RecStore);
            $row_RecStore = $RecStore->fetch_assoc();
            if ($row_RecStore["StoreID"] == $row_RecAnswer["StoreID"]) {
                echo $row_RecStore["StoreName"];
                ?>
                <p align="left" class="smalltext" style='display: inline;'>
                <?php
                
                echo $row_RecAnswer["AnswerDateTime"];
                // echo "</div>";
                ?></p><?php 
            }
            ?> 
				 <div style=' table-layout: fixed; word-wrap: break-word; overflow: scroll; width: 830px;'><?php echo $row_RecAnswer["AnswerContent"];?></div>

					
				<hr size="1" style='color:#ccc;'></hr>
				<?php
        }
    }
    while ($row_RecAnswerC = $RecAnswerC->fetch_assoc()) {
        $j ++;
        
        // echo "<div>";
        if ($row_RecQuestion["QuestionID"] == $row_RecAnswerC["QuestionID"] && $row_RecAnswerC["AnswerContent"] != "") {
            
            $query_RecCustomer2 = "SELECT * FROM Customer WHERE CustomerID='{$row_RecAnswerC["CustomerID"]}'";
            $RecCustomer2 = $db_link->query($query_RecCustomer2);
            $row_RecCustomer2 = $RecCustomer2->fetch_assoc();
            if ($row_RecCustomer2["CustomerID"] == $row_RecAnswerC["CustomerID"]) {     
                echo $row_RecCustomer2["CustomerName"];
                ?>
                <p align="left" class="smalltext" style='display: inline;'>
                <?php
                
                echo $row_RecAnswerC["AnswerDateTime"];
                // echo "</a></li>";
                ?>
				 </p> <?php 
            }
            ?> 
				        <div style='table-layout: fixed; word-wrap: break-word; overflow: scroll; width: 830px;'><?php echo $row_RecAnswerC["AnswerContent"];?></div>

					    
				    <?php
        }
    }
    echo '</div></li></ul><div>';
    ?>
			
				
				
				
				</td>
			</tr>
		</table>
	<?php }?>
	<table width="400" border="0" align="center" cellpadding="4"
			cellspacing="0">
			<tr>
				<td valign="middle" style="border:none;"><p class='num'>資料筆數：<?php echo $total_records;?></p></td>
				<td align="right" style="border:none;"><p>
			<?php if ($num_pages > 1) { //若不是第一頁則顯示 ?>
				<a class='num' href="?page=1">第一頁</a> | <a
							href="?page=<?php echo $num_pages-1;?>">上一頁</a> |
			<?php }?>
			<?php if ($num_pages < $total_pages) { //若不是最後一頁則顯示 ?>
				<a class='num' class='num' href="?page=<?php echo $num_pages+1;?>">下一頁</a>
						| <a class='num' href="?page=<?php echo $total_pages;?>">最末頁</a>
			<?php }?>
			</p></td>
			</tr>
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
    echo "<a class='const' href=' store.php?StoreID=" . ($row99["StoreID"]) . "'>" . $row99["StoreName"] . "</a>" . "<br>";
    $j99 ++;
}?>
	</div>
	<div class="remodal" data-remodal-id="modalf" style='overflow: scroll; height: 500px;width:350px;'>
		<button data-remodal-action="close" class="remodal-close"></button>
		<?php 
		$sql100 = "SELECT CustomerUse.StoreID,StoreName FROM CustomerUse,store WHERE CustomerUse.CustomerID='$CustomerID' AND CustomerUse.StoreID=store.StoreID ";
		$result100 = execute_sql($link, "handstory", $sql100);
		$total_records100 = mysqli_num_rows($result100);
		$i100 = 1;
		while ($row100 = mysqli_fetch_assoc($result100) and  $i00 <= $total_records100) {
		    echo "<a class='const' href=' store.php?StoreID=" . ($row100["StoreID"]) . "'>" . $row100["StoreName"] . "</a>" . "<br>";
		    $i ++;
		}
		?>
	</div>
	<!--彈出框-->
	<script src="remodal.js"></script>

	
	<script>
		$(document).ready(function(){
			$('#fav,#apDiv6').hover(function(){
				$('#apDiv6').show();
			},
			function(){
				$('#apDiv6').hide();
			})
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
mysqli_free_result($result100);
mysqli_free_result($RecQuestion);
mysqli_free_result($RecAnswer);
mysqli_free_result($RecAnswerC);
mysqli_free_result($RecCustomer);
// mysqli_free_result($RecCustomer2);
// mysqli_free_result($RecStore);

// 關閉資料連接
mysqli_close($link);
$db_link->close();
?>
