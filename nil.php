<?php require_once('Connections/konek.php'); ?>
<?php require_once('Connections/konek.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "admin";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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

$currentPage = $_SERVER["PHP_SELF"];

$colname_dt = "-1";
if (isset($_GET['cr'])) {
  $colname_dt = $_GET['cr'];
}
mysql_select_db($database_konek, $konek);
$query_dt = sprintf("SELECT * FROM nilai WHERE siswa_NISN LIKE %s ORDER BY siswa_NISN ASC", GetSQLValueString("%" . $colname_dt . "%", "text"));
$dt = mysql_query($query_dt, $konek) or die(mysql_error());
$row_dt = mysql_fetch_assoc($dt);
$totalRows_dt = mysql_num_rows($dt);

$queryString_dt = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_dt") == false && 
        stristr($param, "totalRows_dt") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_dt = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_dt = sprintf("&totalRows_dt=%d%s", $totalRows_dt, $queryString_dt);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ukk akademik</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="header">
  <div class="isi">executive information system</div>
</div>
<div id="menua">
  <div class="isi">
    <ul>
      <li><a href="index.php">#home</a></li>
      <li><a href="login.php">admin</a></li>
      <li><a href="versi.php">version</a></li>
    </ul>
  </div>
</div>
<div id="konten">
  <div class="isi">
    <div id="menu">
      <h3>&nbsp;</h3>
      <ul>
        <li><a href="bs.php?cr=">bidang studi</a></li>
        <li><a href="kk.php?cr=">kompetensi keahlian</a></li>
        <li><a href="sk.php?cr=">standar kompetensi</a></li>
        <li><a href="gr.php?cr=">guru</a></li>
        <li><a href="sw.php?cr=">siswa</a></li>
        <li><a href="wm.php?cr=">wali murid</a></li>
        <li><a href="nil.php?cr=">nilai</a></li>
        <li><a href="logout.php">logout</a></li>
      </ul>
    </div>
      <div id="isi">
        <h3> Nilai</h3>
        <p><a href="nilt.php">Tambah</a></p>
        
      
        <form action="kk.php" method="get" name="form1" class="crf" id="form1">
          <label> pencarian :
            <input type="text" name="cr" id="cr" />
          </label>
          <label>
          <input type="submit" name="cari" id="cari" value="Cari" />
          </label>
        </form>
        
            
        <p>&nbsp;
 data tidak ditemukan

<table border="1" cellpadding="0" cellspacing="0">
  <tr>
    <th>NISN</th>
    <th>Kode Guru</th>
    <th>Kode SK</th>
    <th>Nilai</th>
    <th>Huruf</th>
    <th class="ket">Keterangan</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_dt['siswa_NISN']; ?></td>
      <td><?php echo $row_dt['guru_kode']; ?></td>
      <td><?php echo $row_dt['sk_kode']; ?></td>
      <td><?php echo $row_dt['nilai_angka']; ?></td>
      <td><?php echo $row_dt['nilai_huruf']; ?></td>
      <td><a href="nile.php?id=<?php echo $row_dt['siswa_NISN']; ?>&amp;gr=<?php echo $row_dt['guru_kode']; ?>&amp;sk=<?php echo $row_dt['sk_kode']; ?>">Edit</a> | <a href="nilh.php?id=<?php echo $row_dt['siswa_NISN']; ?>">Hapus</a> | <a href="nild.php?id=<?php echo $row_dt['siswa_NISN']; ?>&amp;gr=<?php echo $row_dt['guru_kode']; ?>&amp;sk=<?php echo $row_dt['sk_kode']; ?>">Detail</a></td>
    </tr>
    <?php } while ($row_dt = mysql_fetch_assoc($dt)); ?>
</table>
</p>
</div>
  </div>
</div>
<div id="footer">
  <div class="isi">&copy; rifan ferryawan - 2014 </div>
</div>
</body>
</html>
<?php
mysql_free_result($dt);
?>