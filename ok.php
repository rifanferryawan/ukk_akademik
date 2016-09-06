<?php require_once('Connections/konek.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

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
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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

$colname_dt = "-1";
if (isset($_GET['cr'])) {
  $colname_dt = $_GET['cr'];
}
mysql_select_db($database_konek, $konek);
$query_dt = sprintf("SELECT * FROM guru WHERE guru_nama LIKE %s", GetSQLValueString("%" . $colname_dt . "%", "text"));
$dt = mysql_query($query_dt, $konek) or die(mysql_error());
$row_dt = mysql_fetch_assoc($dt);
$totalRows_dt = mysql_num_rows($dt);
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Untitled Document</title>
</head>

<body>
<form name="form1" method="get" action="">
cari
  <label>
  <input type="text" name="cr" id="cr">
  </label>
  <label>
  <input type="submit" name="button" id="button" value="Submit">
  </label>
</form>
<p>&nbsp;</p>
<table border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td>guru_kode</td>
    <td>kompetensi_kode</td>
    <td>guru_NIP</td>
    <td>guru_nama</td>
    <td>guru_alamat</td>
    <td>guru_telpon</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_dt['guru_kode']; ?></td>
      <td><?php echo $row_dt['kompetensi_kode']; ?></td>
      <td><?php echo $row_dt['guru_NIP']; ?></td>
      <td><?php echo $row_dt['guru_nama']; ?></td>
      <td><?php echo $row_dt['guru_alamat']; ?></td>
      <td><?php echo $row_dt['guru_telpon']; ?></td>
    </tr>
    <?php } while ($row_dt = mysql_fetch_assoc($dt)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($dt);
?>
