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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "topicAdd")) {
  $insertSQL = sprintf("INSERT INTO topic (class_ID, topic_Subject, topic_Author, topic_Content, topic_Time) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['class_ID'], "int"),
                       GetSQLValueString($_POST['topic_Subject'], "text"),
                       GetSQLValueString($_POST['topic_Author'], "text"),
                       GetSQLValueString($_POST['topic_Content'], "text"),
                       GetSQLValueString($_POST['topic_Time'], "date"));

  mysql_select_db($database_friday, $friday);
  $Result1 = mysql_query($insertSQL, $friday) or die(mysql_error());

  $insertGoTo = "topicAdd_admin.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_friday, $friday);
$query_admin_Class = "SELECT * FROM classify";
$admin_Class = mysql_query($query_admin_Class, $friday) or die(mysql_error());
$row_admin_Class = mysql_fetch_assoc($admin_Class);
$totalRows_admin_Class = mysql_num_rows($admin_Class);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>文章新增</title>
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
        <form action="<?php echo $editFormAction; ?>" name="topicAdd" method="POST" id="topicAdd">
	<div id="topic_S">標題：
	  <label for="topic_Author"></label>
	  <input name="topic_Subject" type="text" class="formText" id="topic_Subject" size="20" maxlength="100">
	</div>
    <div id="topic_A">作者：
	  <label for="topic_Author"></label>
	  <input name="topic_Author" type="text" class="formText" id="topic_Author" size="20" maxlength="10">
	</div>
    <div id="class">分類：
      <label for="class_ID"></label>
      <select name="class_ID" class="formText" id="class_ID">
        <?php
do {  
?>
        <option value="<?php echo $row_admin_Class['class_ID']?>"><?php echo $row_admin_Class['class_Name']?></option>
        <?php
} while ($row_admin_Class = mysql_fetch_assoc($admin_Class));
  $rows = mysql_num_rows($admin_Class);
  if($rows > 0) {
      mysql_data_seek($admin_Class, 0);
	  $row_admin_Class = mysql_fetch_assoc($admin_Class);
  }
?>
      </select>
    </div>
    <div id="topic_C">
      <label for="topic_Content"></label>
      <textarea name="topic_Content" cols="64" rows="20" class="ckeditor" id="topic_Content"></textarea>
    </div>
    <div id="topic_B">
    <input name="topic_Time" type="hidden" id="topic_Time" value="<?php echo date("Y-m-d H:i:s"); ?>">
      <input name="button" type="submit" class="formText" id="button" value="發　佈">
    </div>
    <input type="hidden" name="MM_insert" value="topicAdd">
        </form>
  </div>
</div>
</body>
</html>
<?php
mysql_free_result($admin_Class);
?>
