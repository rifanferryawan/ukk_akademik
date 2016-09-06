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

$colname_dt = "-1";
if (isset($_GET['id'])) {
  $colname_dt = $_GET['id'];
}
mysql_select_db($database_konek, $konek);
$query_dt = sprintf("SELECT * FROM nilai WHERE siswa_NISN = %s", GetSQLValueString($colname_dt, "text"));
$dt = mysql_query($query_dt, $konek) or die(mysql_error());
$row_dt = mysql_fetch_assoc($dt);
$totalRows_dt = mysql_num_rows($dt);

$colname_Sw = "-1";
if (isset($_GET['id'])) {
  $colname_Sw = $_GET['id'];
}
mysql_select_db($database_konek, $konek);
$query_Sw = sprintf("SELECT * FROM siswa WHERE siswa_NISN = %s", GetSQLValueString($colname_Sw, "text"));
$Sw = mysql_query($query_Sw, $konek) or die(mysql_error());
$row_Sw = mysql_fetch_assoc($Sw);
$totalRows_Sw = mysql_num_rows($Sw);

$colname_Gr = "-1";
if (isset($_GET['gr'])) {
  $colname_Gr = $_GET['gr'];
}
mysql_select_db($database_konek, $konek);
$query_Gr = sprintf("SELECT * FROM guru WHERE guru_kode = %s", GetSQLValueString($colname_Gr, "text"));
$Gr = mysql_query($query_Gr, $konek) or die(mysql_error());
$row_Gr = mysql_fetch_assoc($Gr);
$totalRows_Gr = mysql_num_rows($Gr);

$colname_Sk = "-1";
if (isset($_GET['sk'])) {
  $colname_Sk = $_GET['sk'];
}
mysql_select_db($database_konek, $konek);
$query_Sk = sprintf("SELECT * FROM standar_kompetensi WHERE sk_kode = %s", GetSQLValueString($colname_Sk, "text"));
$Sk = mysql_query($query_Sk, $konek) or die(mysql_error());
$row_Sk = mysql_fetch_assoc($Sk);
$totalRows_Sk = mysql_num_rows($Sk);
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
        <h3> Detail Nilai</h3>
        <table width="250" border="0" cellspacing="0">
          <tr>
            <td width="24%">NISN</td>
            <td width="2%">:</td>
            <td width="74%"><?php echo $row_Sw['siswa_NISN']; ?></td>
          </tr>
          <tr>
            <td>Nama SIswa</td>
            <td>:</td>
            <td><?php echo $row_Sw['siswa_nama']; ?></td>
          </tr>
          <tr>
            <td>Alamat Siswa</td>
            <td>:</td>
            <td><?php echo $row_Sw['siswa_alamat']; ?></td>
          </tr>
          <tr>
            <td>Tanggal Lahir Siswa</td>
            <td>:</td>
            <td><?php echo $row_Sw['siswa_tgl_lahir']; ?></td>
          </tr>
          <tr>
            <td>Foto Siswa</td>
            <td>:</td>
            <td><?php echo $row_Sw['siswa_foto']; ?></td>
          </tr>
          <tr>
            <td>Kode Guru</td>
            <td>:</td>
            <td><?php echo $row_dt['guru_kode']; ?></td>
          </tr>
          <tr>
            <td>Nama Guru</td>
            <td>:</td>
            <td><?php echo $row_Gr['guru_nama']; ?></td>
          </tr>
          <tr>
            <td>Kode SK</td>
            <td>:</td>
            <td><?php echo $row_dt['sk_kode']; ?></td>
          </tr>
          <tr>
            <td>Nama SK</td>
            <td>:</td>
            <td><?php echo $row_Sk['sk_nama']; ?></td>
          </tr>
          <tr>
            <td>Nilai</td>
            <td>:</td>
            <td><?php echo $row_dt['nilai_angka']; ?></td>
          </tr>
          <tr>
            <td>Huruf</td>
            <td>:</td>
            <td><?php echo $row_dt['nilai_huruf']; ?></td>
          </tr>
        </table>
        <p>&nbsp;</p>
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

mysql_free_result($Sw);

mysql_free_result($Gr);

mysql_free_result($Sk);
?>
