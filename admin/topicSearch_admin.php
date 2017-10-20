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

$colname_topicSearch = "-1";
if (isset($_GET['keyword'])) {
  $colname_topicSearch = $_GET['keyword'];
}
$colname2_topicSearch = "-1";
if (isset($_GET['keyword'])) {
  $colname2_topicSearch = $_GET['keyword'];
}
mysql_select_db($database_friday, $friday);
$query_topicSearch = sprintf("SELECT topic_ID, topic_Subject, topic_Author, topic_Content,topic_Time FROM topic WHERE ( topic_Subject LIKE %s OR topic_Author LIKE %s ) ORDER BY topic_ID ASC", GetSQLValueString("%" . $colname_topicSearch . "%", "text"),GetSQLValueString("%" . $colname2_topicSearch . "%", "text"));
$topicSearch = mysql_query($query_topicSearch, $friday) or die(mysql_error());
$row_topicSearch = mysql_fetch_assoc($topicSearch);
$totalRows_topicSearch = mysql_num_rows($topicSearch);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>文章查詢</title>
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
  <?php if ($totalRows_topicSearch == 0) { // Show if recordset empty ?>
  <div id="topic-tip">沒有相關的標題/作者
  </div>
  <?php } // Show if recordset empty ?>
<?php if ($totalRows_topicSearch > 0) { // Show if recordset not empty ?>
  <div id="topic-content">
    <?php do { ?>
      <div id="topic-ns" class="col-10"> <?php echo date('Y/m/d',strtotime($row_topicSearch['topic_Time'])); ?> … <?php echo mb_strimwidth(strip_tags($row_topicSearch['topic_Subject']),0,40,'...','utf8'); ?> … <?php echo $row_topicSearch['topic_Author']; ?></div>
      <div id="topic-btn" class="col-2">
        <form name="topicUpdate" method="post" action="topicUpdate_admin.php" style="float:left; margin-right:10px;">
          <input type="submit" name="btn_update" id="btn_update" value="修改">
          <input name="topic_ID" type="hidden" id="topic_ID" value="<?php echo $row_topicSearch['topic_ID']; ?>">
          </form>
        <form name="topicDel" method="post" action="topicDel_admin.php" >
          <input type="submit" name="btn_del" id="btn_del" value="刪除">
          <input name="topic_ID" type="hidden" id="topic_ID" value="<?php echo $row_topicSearch['topic_ID']; ?>">
          </form>
      </div>
      <?php } while ($row_topicSearch = mysql_fetch_assoc($topicSearch)); ?>
  </div>
  <?php } // Show if recordset not empty ?>
</div>
</body>
</html>
<?php
mysql_free_result($topicSearch);
?>
