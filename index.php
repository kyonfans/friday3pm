<?php require_once('Connections/friday.php'); ?>
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
$query_class = "SELECT * FROM classify";
$class = mysql_query($query_class, $friday) or die(mysql_error());
$row_class = mysql_fetch_assoc($class);
$totalRows_class = mysql_num_rows($class);

$colname_topic = "-1";
if (isset($_GET['class_ID'])) {
  $colname_topic = $_GET['class_ID'];
}
mysql_select_db($database_friday, $friday);
$query_topic = sprintf("SELECT * FROM topic WHERE class_ID = %s ORDER BY topic_Time DESC", GetSQLValueString($colname_topic, "int"));
$topic = mysql_query($query_topic, $friday) or die(mysql_error());
$row_topic = mysql_fetch_assoc($topic);
$totalRows_topic = mysql_num_rows($topic);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>部落格文章</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<link href="css/html5reset.css" rel="stylesheet" type="text/css">
<link href="css/style.css" rel="stylesheet">
</head>

<body>
<div id="main" class="container">
		<div id="topic-aside"><?php do { ?>
		    <a href="index.php?class_ID=<?php echo $row_class['class_ID']; ?>"><?php echo $row_class['class_Name']; ?></a>
<?php } while ($row_class = mysql_fetch_assoc($class)); ?>
<br>
</div>
        <?php if ($totalRows_topic == 0) { // Show if recordset empty ?>
  <div id="topic-tip">選擇分類
  </div>
  <?php } // Show if recordset empty ?>
<?php do { ?>
          <?php if ($totalRows_topic > 0) { // Show if recordset not empty ?>
          <div id="topic-content">
              <p>標題：<?php echo $row_topic['topic_Subject']; ?><br>
                作者：<?php echo $row_topic['topic_Author']; ?><br>
                內容：<br>
                <?php echo $row_topic['topic_Content']; ?> </p>
            </div>
            <?php } // Show if recordset not empty ?>
          <?php } while ($row_topic = mysql_fetch_assoc($topic)); ?>
</div>
</body>
</html>
<?php
mysql_free_result($class);

mysql_free_result($topic);
?>
