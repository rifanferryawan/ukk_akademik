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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO nilai (siswa_NISN, guru_kode, sk_kode, nilai_angka, nilai_huruf) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['siswa_NISN'], "text"),
                       GetSQLValueString($_POST['guru_kode'], "text"),
                       GetSQLValueString($_POST['sk_kode'], "text"),
                       GetSQLValueString($_POST['nilai_angka'], "double"),
                       GetSQLValueString($_POST['nilai_huruf'], "text"));

  mysql_select_db($database_konek, $konek);
  $Result1 = mysql_query($insertSQL, $konek) or die(mysql_error());

  $insertGoTo = "nil.php?cr=";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_konek, $konek);
$query_RecSiswa = "SELECT * FROM siswa";
$RecSiswa = mysql_query($query_RecSiswa, $konek) or die(mysql_error());
$row_RecSiswa = mysql_fetch_assoc($RecSiswa);
$totalRows_RecSiswa = mysql_num_rows($RecSiswa);

mysql_select_db($database_konek, $konek);
$query_RecGuru = "SELECT * FROM guru";
$RecGuru = mysql_query($query_RecGuru, $konek) or die(mysql_error());
$row_RecGuru = mysql_fetch_assoc($RecGuru);
$totalRows_RecGuru = mysql_num_rows($RecGuru);

mysql_select_db($database_konek, $konek);
$query_RecSK = "SELECT * FROM standar_kompetensi";
$RecSK = mysql_query($query_RecSK, $konek) or die(mysql_error());
$row_RecSK = mysql_fetch_assoc($RecSK);
$totalRows_RecSK = mysql_num_rows($RecSK);
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
        <h3> Tambah Nilai</h3>
        <p>&nbsp;</p>
    
                <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
          <table align="center">
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">Siswa_NISN:</td>
              <td><select name="siswa_NISN">
                  <?php 
do {  
?>
                  <option value="<?php echo $row_RecSiswa['siswa_NISN']?>" ><?php echo $row_RecSiswa['siswa_nama']?></option>
                  <?php
} while ($row_RecSiswa = mysql_fetch_assoc($RecSiswa));
?>
                </select>
              </td>
            </tr>
            <tr> </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">Guru_kode:</td>
              <td><select name="guru_kode">
                  <?php 
do {  
?>
                  <option value="<?php echo $row_RecGuru['guru_kode']?>" ><?php echo $row_RecGuru['guru_nama']?></option>
                  <?php
} while ($row_RecGuru = mysql_fetch_assoc($RecGuru));
?>
                </select>
              </td>
            </tr>
            <tr> </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">Sk_kode:</td>
              <td><select name="sk_kode">
                  <?php 
do {  
?>
                  <option value="<?php echo $row_RecSK['sk_kode']?>" ><?php echo $row_RecSK['sk_nama']?></option>
                  <?php
} while ($row_RecSK = mysql_fetch_assoc($RecSK));
?>
                </select>
              </td>
            </tr>
            <tr> </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">Nilai_angka:</td>
              <td><input type="text" name="nilai_angka" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">Nilai_huruf:</td>
              <td><input type="text" name="nilai_huruf" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">&nbsp;</td>
              <td><input type="submit" value="Insert record" /></td>
            </tr>
          </table>
          <input type="hidden" name="MM_insert" value="form1" />
        </form>
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
mysql_free_result($RecSiswa);

mysql_free_result($RecGuru);

mysql_free_result($RecSK);
?>