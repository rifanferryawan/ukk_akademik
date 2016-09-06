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
  $insertSQL = sprintf("INSERT INTO wali_murid (wali_id, siswa_NISN, wali_nama_ayah, wali_pekerjaan_ayah, wali_nama_ibu, wali_pekerjaan_ibu, wali_alamat, wali_telpon) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['wali_id'], "text"),
                       GetSQLValueString($_POST['siswa_NISN'], "text"),
                       GetSQLValueString($_POST['wali_nama_ayah'], "text"),
                       GetSQLValueString($_POST['wali_pekerjaan_ayah'], "text"),
                       GetSQLValueString($_POST['wali_nama_ibu'], "text"),
                       GetSQLValueString($_POST['wali_pekerjaan_ibu'], "text"),
                       GetSQLValueString($_POST['wali_alamat'], "text"),
                       GetSQLValueString($_POST['wali_telpon'], "text"));

  mysql_select_db($database_konek, $konek);
  $Result1 = mysql_query($insertSQL, $konek) or die(mysql_error());

  $insertGoTo = "wm.php?cr=";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_konek, $konek);
$query_sw = "SELECT * FROM siswa ORDER BY siswa_NISN ASC";
$sw = mysql_query($query_sw, $konek) or die(mysql_error());
$row_sw = mysql_fetch_assoc($sw);
$totalRows_sw = mysql_num_rows($sw);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ukk akademik</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
<!--
function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
    for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
      if (val) { nm=val.name; if ((val=val.value)!="") {
        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
    } if (errors) alert('The following error(s) occurred:\n'+errors);
    document.MM_returnValue = (errors == '');
} }
//-->
</script>
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
        <h3> Tambah Wali Murid</h3>
        <p>&nbsp;</p>
    
        <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" onsubmit="MM_validateForm('wali_id','','R','siswa_NISN','','R','wali_nama_ayah','','R','wali_pekerjaan_ayah','','R','wali_nama_ibu','','R','wali_pekerjaan_ibu','','R','wali_alamat','','R','wali_telpon','','R');return document.MM_returnValue">
          <table align="center">
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">ID Wali:</td>
              <td><input name="wali_id" type="text" id="wali_id" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">NISN:</td>
              <td><label>
                <select name="siswa_NISN" id="siswa_NISN">
                  <?php
do {  
?>
                  <option value="<?php echo $row_sw['siswa_NISN']?>"><?php echo $row_sw['siswa_NISN']?></option>
                  <?php
} while ($row_sw = mysql_fetch_assoc($sw));
  $rows = mysql_num_rows($sw);
  if($rows > 0) {
      mysql_data_seek($sw, 0);
	  $row_sw = mysql_fetch_assoc($sw);
  }
?>
                                </select>
              </label></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">Nama Ayah:</td>
              <td><input name="wali_nama_ayah" type="text" id="wali_nama_ayah" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">Pekerjaan Ayah:</td>
              <td><input name="wali_pekerjaan_ayah" type="text" id="wali_pekerjaan_ayah" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">Nama Ibu:</td>
              <td><input name="wali_nama_ibu" type="text" id="wali_nama_ibu" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">Pekerjaan Ibu:</td>
              <td><input name="wali_pekerjaan_ibu" type="text" id="wali_pekerjaan_ibu" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">Alamat Wali:</td>
              <td><input name="wali_alamat" type="text" id="wali_alamat" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">Telepon Wali:</td>
              <td><input name="wali_telpon" type="text" id="wali_telpon" value="" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">&nbsp;</td>
              <td><input type="submit" value="Simpan" /></td>
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
mysql_free_result($sw);
?>