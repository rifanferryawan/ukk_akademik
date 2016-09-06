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

$maxRows_dt = 10;
$pageNum_dt = 0;
if (isset($_GET['pageNum_dt'])) {
  $pageNum_dt = $_GET['pageNum_dt'];
}
$startRow_dt = $pageNum_dt * $maxRows_dt;

$colname_dt = "-1";
if (isset($_GET['cr'])) {
  $colname_dt = $_GET['cr'];
}
mysql_select_db($database_konek, $konek);
$query_dt = sprintf("SELECT * FROM siswa WHERE siswa_nama LIKE %s", GetSQLValueString("%" . $colname_dt . "%", "text"));
$query_limit_dt = sprintf("%s LIMIT %d, %d", $query_dt, $startRow_dt, $maxRows_dt);
$dt = mysql_query($query_limit_dt, $konek) or die(mysql_error());
$row_dt = mysql_fetch_assoc($dt);

if (isset($_GET['totalRows_dt'])) {
  $totalRows_dt = $_GET['totalRows_dt'];
} else {
  $all_dt = mysql_query($query_dt);
  $totalRows_dt = mysql_num_rows($all_dt);
}
$totalPages_dt = ceil($totalRows_dt/$maxRows_dt)-1;

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
        <h3> Siswa</h3>
        <p><a href="swt.php">Tambah</a></p>
        
  
          <form action="sw.php" method="get" name="form1" class="crf" id="form1">
          <label> pencarian :
            <input type="text" name="cr" id="cr" />
          </label>
          <label>
          <input type="submit" name="cari" id="cari" value="Cari" />
          </label>
        </form>
        <?php if ($totalRows_dt > 0) { // Show if recordset not empty ?>
          <table border="1" cellpadding="0" cellspacing="0">
            <tr>
              <th>NISN</th>
              <th>Kode Kompetensi</th>
              <th>Nama</th>
              <th class="ket">Alamat</th>
              <th class="ket">Keterangan</th>
            </tr>
            <?php do { ?>
              <tr>
                <td><?php echo $row_dt['siswa_NISN']; ?></td>
                <td><?php echo $row_dt['kompetensi_kode']; ?></td>
                <td><?php echo $row_dt['siswa_nama']; ?></td>
                <td><?php echo $row_dt['siswa_alamat']; ?></td>
                <td><a href="swe.php?id=<?php echo $row_dt['siswa_NISN']; ?>">Edit</a> | <a href="swh.php?id=<?php echo $row_dt['siswa_NISN']; ?>">Hapus</a> | <a href="swd.php?id=<?php echo $row_dt['siswa_NISN']; ?>">Detail</a></td>
            </tr>
              <?php } while ($row_dt = mysql_fetch_assoc($dt)); ?>
        </table>
          <?php } // Show if recordset not empty ?>
        <p>&nbsp;
          <?php if ($totalRows_dt == 0) { // Show if recordset empty ?>
            data tidak ditemukan
  <?php } // Show if recordset empty ?>
<table border="0" align="center" class="pgnum">
          <tr>
            <td><?php if ($pageNum_dt > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_dt=%d%s", $currentPage, 0, $queryString_dt); ?>"><img src="img/First.gif" border="0" /></a>
            <?php } // Show if not first page ?>            </td>
            <td><?php if ($pageNum_dt > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_dt=%d%s", $currentPage, max(0, $pageNum_dt - 1), $queryString_dt); ?>"><img src="img/Previous.gif" border="0" /></a>
            <?php } // Show if not first page ?>            </td>
            <td><?php if ($pageNum_dt < $totalPages_dt) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_dt=%d%s", $currentPage, min($totalPages_dt, $pageNum_dt + 1), $queryString_dt); ?>"><img src="img/Next.gif" border="0" /></a>
            <?php } // Show if not last page ?>            </td>
            <td><?php if ($pageNum_dt < $totalPages_dt) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_dt=%d%s", $currentPage, $totalPages_dt, $queryString_dt); ?>"><img src="img/Last.gif" border="0" /></a>
            <?php } // Show if not last page ?>            </td>
          </tr>
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