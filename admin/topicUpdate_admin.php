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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "update")) {
  $updateSQL = sprintf("UPDATE topic SET class_ID=%s, topic_Subject=%s, topic_Author=%s, topic_Content=%s, topic_Time=%s WHERE topic_ID=%s",
                       GetSQLValueString($_POST['class_ID'], "int"),
                       GetSQLValueString($_POST['topic_Subject'], "text"),
                       GetSQLValueString($_POST['topic_Author'], "text"),
                       GetSQLValueString($_POST['topic_Content'], "text"),
                       GetSQLValueString($_POST['topic_Time'], "date"),
                       GetSQLValueString($_POST['topic_ID'], "int"));

  mysql_select_db($database_friday, $friday);
  $Result1 = mysql_query($updateSQL, $friday) or die(mysql_error());

  $updateGoTo = "topicAdmin_admin.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_topicupdate_admin = "-1";
if (isset($_GET['topic_ID'])) {
  $colname_topicupdate_admin = $_GET['topic_ID'];
}
mysql_select_db($database_friday, $friday);
$query_topicupdate_admin = sprintf("SELECT * FROM topic WHERE topic_ID = %s", GetSQLValueString($colname_topicupdate_admin, "int"));
$topicupdate_admin = mysql_query($query_topicupdate_admin, $friday) or die(mysql_error());
$row_topicupdate_admin = mysql_fetch_assoc($topicupdate_admin);
$totalRows_topicupdate_admin = "-1";
if (isset($_POST['topic_ID'])) {
  $totalRows_topicupdate_admin = $_POST['topic_ID'];
}
$colname_topicupdate_admin = "-1";
if (isset($_POST['topic_ID'])) {
  $colname_topicupdate_admin = $_POST['topic_ID'] ;
}
mysql_select_db($database_friday, $friday);
$query_topicupdate_admin = sprintf("SELECT * FROM topic WHERE topic_ID = %s", GetSQLValueString($colname_topicupdate_admin, "int"));
$topicupdate_admin = mysql_query($query_topicupdate_admin, $friday) or die(mysql_error());
$row_topicupdate_admin = mysql_fetch_assoc($topicupdate_admin);
$totalRows_topicupdate_admin = mysql_num_rows($topicupdate_admin);

mysql_select_db($database_friday, $friday);
$query_topicupadate_class = "SELECT * FROM classify";
$topicupadate_class = mysql_query($query_topicupadate_class, $friday) or die(mysql_error());
$row_topicupadate_class = mysql_fetch_assoc($topicupadate_class);
$totalRows_topicupadate_class = mysql_num_rows($topicupadate_class);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>文章修改/刪除</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<link href="../css/html5reset.css" rel="stylesheet" type="text/css">
<link href="../css/admin_style.css" rel="stylesheet">
<script src="ckeditor/ckeditor.js"></script>
</head>

<body>
<div id="main" class="container">
		<div id="topic-aside">
        <p><a href="topicAdd_admin.php">文章新增</a>　<a href="topicAdmin_admin.php">文章修改/刪除</a></p>
		</div>
        <div id="topic-content">
        <form method="POST" action="<?php echo $editFormAction; ?>" name="update" id="update">
	<div id="topic_S">標題：
	  <label for="topic_Author"></label>
	  <input name="topic_Subject" type="text" class="formText" id="topic_Subject" value="<?php echo $row_topicupdate_admin['topic_Subject']; ?>" size="20" maxlength="100">
	</div>
    <div id="topic_A">作者：
	  <label for="topic_Author"></label>
	  <input name="topic_Author" type="text" class="formText" id="topic_Author" value="<?php echo $row_topicupdate_admin['topic_Author']; ?>" size="20" maxlength="100">
	</div>
    <div id="class">分類：
      <label for="class_ID"></label>
      <select name="class_ID" class="formText" id="class_ID">
        <?php
do {  
?>
        <option value="<?php echo $row_topicupadate_class['class_ID']?>"><?php echo $row_topicupadate_class['class_Name']?></option>
        <?php
} while ($row_topicupadate_class = mysql_fetch_assoc($topicupadate_class));
  $rows = mysql_num_rows($topicupadate_class);
  if($rows > 0) {
      mysql_data_seek($topicupadate_class, 0);
	  $row_topicupadate_class = mysql_fetch_assoc($topicupadate_class);
  }
?>
      </select>
    </div>
    <div id="topic_C">
      <label for="topic_Content"></label>
      <textarea name="topic_Content" cols="64" rows="20" class="ckeditor" id="topic_Content"><?php echo $row_topicupdate_admin['topic_Content']; ?></textarea>
    </div>
    <div id="topic_B">
      <input name="button" type="submit" class="formText" id="button" value="修　改">
      <input name="topic_ID" type="hidden" id="topic_ID" value="<?php echo $row_topicupdate_admin['topic_ID']; ?>">
      <input name="topic_Time" type="hidden" id="topic_Time" value="<?php echo date("Y-m-d H:i:s"); ?>">
    </div>
    <input type="hidden" name="MM_update" value="update">
        </form>
  </div>
</div>
</body>
</html>
<?php
mysql_free_result($topicupdate_admin);

mysql_free_result($topicupadate_class);
?>
