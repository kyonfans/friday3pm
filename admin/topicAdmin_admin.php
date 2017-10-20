<?php require_once('../Connections/friday.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_friday, $friday);
$query_topicAdmin = "SELECT * FROM topic ORDER BY topic_Time DESC";
$topicAdmin = mysql_query($query_topicAdmin, $friday) or die(mysql_error());
$row_topicAdmin = mysql_fetch_assoc($topicAdmin);
$totalRows_topicAdmin = mysql_num_rows($topicAdmin);

mysql_select_db($database_friday, $friday);
$query_class = "SELECT * FROM classify";
$class = mysql_query($query_class, $friday) or die(mysql_error());
$row_class = mysql_fetch_assoc($class);
$totalRows_class = mysql_num_rows($class);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>文章一覽</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<link href="../css/html5reset.css" rel="stylesheet" type="text/css">
<link href="../css/admin_style.css" rel="stylesheet">
</head>

<body>
<div id="main" class="container">
		<div id="topic-aside">
          <p><a href="topicAdd_admin.php">文章新增</a>　<a href="topicAdmin_admin.php">文章修改/刪除</a></p>
          <form action="topicSearch_admin.php" method="get" name="search" id="search">
          文章標題/作者查詢：
          <input name="keyword" type="text" class="formText" id="keyword" size="20" maxlength="100">
          <input type="submit" name="button2" id="button2" value="查詢">
          </form>
		</div>
        <div id="topic-content">
          <?php do { ?>
          <div id="topic-ns" class="col-10">        
          <?php echo date('Y/m/d',strtotime($row_topicAdmin['topic_Time'])); ?> … <?php echo mb_strimwidth(strip_tags($row_topicAdmin['topic_Subject']),0,40,'...','utf8'); ?> … <?php echo $row_topicAdmin['topic_Author']; ?></div>
          <div id="topic-btn" class="col-2">
            <form name="topicUpdate" method="post" action="topicUpdate_admin.php" style="float:left; margin-right:10px;">
              <input type="submit" name="btn_update" id="btn_update" value="修改">
              <input name="topic_ID" type="hidden" id="topic_ID" value="<?php echo $row_topicAdmin['topic_ID']; ?>">
            </form>
            <form name="topicDel" method="post" action="topicDel_admin.php" >
              <input type="submit" name="btn_del" id="btn_del" value="刪除">
              <input name="topic_ID" type="hidden" id="topic_ID" value="<?php echo $row_topicAdmin['topic_ID']; ?>">
            </form>
          </div>
            <?php } while ($row_topicAdmin = mysql_fetch_assoc($topicAdmin)); ?>
        </div>
</div>
</body>
</html>
<?php
mysql_free_result($topicAdmin);

mysql_free_result($class);
?>
