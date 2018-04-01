<?php
session_start();
//檢查是否登入
if((!isset($_SESSION["loginCustomer"])) && (!isset($_SESSION["loginStore"]))){
    header("Location: login.php?loginStats=1");
}
require_once 'connMysql.php';
//預設每頁筆數
$pageRow_records = 5;
//預設頁數
$num_pages = 1;
//若有翻頁，將頁數更新
if (isset($_GET['page'])){
    $num_pages=$_GET['page'];
}
//本頁開始記錄筆數
$startRow_records = ($num_pages -1) * $pageRow_records;
//未加限制顯示筆數的sql敘述句
$query_RecQuestion ="SELECT * FROM Question ORDER BY QuestionDateTime DESC";
//加上限制顯示筆數的SQL敘述句，由本頁開始記錄筆數開始，每頁顯示預設筆數
$query_limit_RecQuestion = $query_RecQuestion." LIMIT {$startRow_records}, {$pageRow_records} ";
//以加上限制顯示筆數的SQL敘述句查詢資料到 $RecQuestion 中
$RecQuestion = $db_link->query($query_limit_RecQuestion);
//以未加上限制顯示筆數的SQL敘述句查詢資料到 $all_RecQuestion 中
$all_RecQuestion = $db_link->query($query_RecQuestion);
//計算總筆數
$total_records = $all_RecQuestion->num_rows;
//計算總頁數=(總筆數/每頁筆數)後無條件進位。
$total_pages = ceil($total_records/$pageRow_records);
?>
<html>
<head>
<title>問答</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#ffffff">
	<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  		<tr>
    			<td><table align="left" border="0" cellpadding="0" cellspacing="0" width="700">
        <tr>
			<?php if (isset($_SESSION["loginCustomer"]) || ($_SESSION["loginCustomer"]!="")) {?>
			<td><a href="question.php">提問</a></td>
			<?php }?>
        </tr>
      		</table></td>
  		</tr>
  <tr>
	<?php while ($row_RecQuestion=$RecQuestion->fetch_assoc()){ ?>
	<table width="90%" border="0" align="center" cellpadding="4" cellspacing="0">
		<tr valign="top">
			
			<?php echo $row_RecQuestion["QuestionSubject"]; ?>
			<td width="60" align="center" class="underline">
			<span class="postname">
			<?php $query_RecCustomer = "SELECT * FROM Customer WHERE CustomerID='{$row_RecQuestion["CustomerID"]}'";
                  $RecCustomer = $db_link->query($query_RecCustomer);
                  $row_RecCustomer = $RecCustomer->fetch_assoc();
                  if ($row_RecCustomer["CustomerID"]==$row_RecQuestion["CustomerID"]){
                  echo $row_RecCustomer["CustomerName"];
                  }?> :</span>
			</td>
			<td class="underline">
				<p><?php echo nl2br($row_RecQuestion["QuestionContent"]);?> <a href="answer.php?id=<?php echo $row_RecQuestion["QuestionID"]?>">回覆</a></p>
				<p align="right" class="smalltext">				
				<?php echo $row_RecQuestion["QuestionDateTime"];?>
				</p>				
				<?php $query_RecAnswer = "SELECT * FROM Answer WHERE QuestionID='{$row_RecQuestion["QuestionID"]}'";
                       $RecAnswer = $db_link->query($query_RecAnswer);
                       $i=0;
                       echo '<select>';
                       while ($row_RecAnswer=$RecAnswer->fetch_assoc()){
                           $i++;
                           echo "<option value=$i>";
				if ($row_RecQuestion["QuestionID"]==$row_RecAnswer["QuestionID"] && $row_RecAnswer["AnswerContent"]!=""){
				    if ($row_RecAnswer["StoreID"]==0){
				        $query_RecCustomer2 = "SELECT * FROM Customer WHERE CustomerID='{$row_RecAnswer["CustomerID"]}'";
				        $RecCustomer2 = $db_link->query($query_RecCustomer2);
				        $row_RecCustomer2 = $RecCustomer2->fetch_assoc();
				        if ($row_RecCustomer2["CustomerID"]==$row_RecAnswer["CustomerID"]){
				        echo $row_RecCustomer2["CustomerName"];
				        }}elseif ($row_RecAnswer["CustomerID"]==0){
				        $query_RecStore = "SELECT * FROM Store WHERE StoreID='{$row_RecAnswer["StoreID"]}'";
				        $RecStore = $db_link->query($query_RecStore);
				        $row_RecStore = $RecStore->fetch_assoc();
				        if ($row_RecStore["StoreID"]==$row_RecAnswer["StoreID"]){
				        echo $row_RecStore["StoreName"];
				        }}?> ：
				<?php echo nl2br($row_RecAnswer["AnswerContent"]);?>
				<p align="right" class="smalltext">
				<?php echo $row_RecAnswer["AnswerDateTime"];
				      echo "</option>";?>
				</p>
				<?php }}
				      echo '</select>';?>
			</td>
		</tr>
	</table>
	<?php }?>
	<table width="90%" border="0" align="center" cellpadding="4" cellspacing="0">
		<tr>
			<td valign="middle"><p>資料筆數：<?php echo $total_records;?></p></td>
			<td align="right"><p>
			<?php if ($num_pages > 1) { //若不是第一頁則顯示 ?>
				<a href="?page=1">第一頁</a> | <a href="?page=<?php echo $num_pages-1;?>">上一頁</a> |
			<?php }?>
			<?php if ($num_pages < $total_pages) { //若不是最後一頁則顯示 ?>
				<a href="?page=<?php echo $num_pages+1;?>">下一頁</a> | <a href="?page=<?php echo $total_pages;?>">最末頁</a>
			<?php }?>
			</p></td>
		</tr>
	</table>
</body>
</html>
<?php
$db_link->close();
?>