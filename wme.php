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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE wali_murid SET siswa_NISN=%s, wali_nama_ayah=%s, wali_pekerjaan_ayah=%s, wali_nama_ibu=%s, wali_pekerjaan_ibu=%s, wali_alamat=%s, wali_telpon=%s WHERE wali_id=%s",
                       GetSQLValueString($_POST['siswa_NISN'], "text"),
                       GetSQLValueString($_POST['wali_nama_ayah'], "text"),
                       GetSQLValueString($_POST['wali_pekerjaan_ayah'], "text"),
                       GetSQLValueString($_POST['wali_nama_ibu'], "text"),
                       GetSQLValueString($_POST['wali_pekerjaan_ibu'], "text"),
                       GetSQLValueString($_POST['wali_alamat'], "text"),
                       GetSQLValueString($_POST['wali_telpon'], "text"),
                       GetSQLValueString($_POST['wali_id'], "text"));

  mysql_select_db($database_konek, $konek);
  $Result1 = mysql_query($updateSQL, $konek) or die(mysql_error());

  $updateGoTo = "wm.php?cr=";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_dt = "-1";
if (isset($_GET['id'])) {
  $colname_dt = $_GET['id'];
}
mysql_select_db($database_konek, $konek);
$query_dt = sprintf("SELECT * FROM wali_murid WHERE wali_id = %s", GetSQLValueString($colname_dt, "text"));
$dt = mysql_query($query_dt, $konek) or die(mysql_error());
$row_dt = mysql_fetch_assoc($dt);
$totalRows_dt = mysql_num_rows($dt);

mysql_select_db($database_konek, $konek);
$query_sw = "SELECT * FROM siswa";
$sw = mysql_query($query_sw, $konek) or die(mysql_error());
$row_sw = mysql_fetch_assoc($sw);
$totalRows_sw = mysql_num_rows($sw);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
        <h3> Edit Wali Murid</h3>
        <p>&nbsp;</p>
    
                
        
        
        <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" onsubmit="MM_validateForm('wali_nama_ayah','','R','wali_pekerjaan_ayah','','R','wali_nama_ibu','','R','wali_pekerjaan_ibu','','R','wali_alamat','','R','wali_telpon','','R');return document.MM_returnValue">
          <table align="center">
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">ID Wali:</td>
              <td><?php echo $row_dt['wali_id']; ?></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">NISN:</td>
              <td><select name="siswa_NISN">
                  <?php 
do {  
?>
                  <option value="<?php echo $row_sw['siswa_NISN']?>" <?php if (!(strcmp($row_sw['siswa_NISN'], htmlentities($row_dt['siswa_NISN'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_sw['siswa_NISN']?></option>
                  <?php
} while ($row_sw = mysql_fetch_assoc($sw));
?>
                </select>
              </td>
            </tr>
            <tr> </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">Nama Ayah:</td>
              <td><input name="wali_nama_ayah" type="text" id="wali_nama_ayah" value="<?php echo htmlentities($row_dt['wali_nama_ayah'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">Pekerjaan Ayah:</td>
              <td><input name="wali_pekerjaan_ayah" type="text" id="wali_pekerjaan_ayah" value="<?php echo htmlentities($row_dt['wali_pekerjaan_ayah'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">Nama Ibu:</td>
              <td><input name="wali_nama_ibu" type="text" id="wali_nama_ibu" value="<?php echo htmlentities($row_dt['wali_nama_ibu'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">Pekerjaan Ibu:</td>
              <td><input name="wali_pekerjaan_ibu" type="text" id="wali_pekerjaan_ibu" value="<?php echo htmlentities($row_dt['wali_pekerjaan_ibu'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">Alamat Wali:</td>
              <td><input name="wali_alamat" type="text" id="wali_alamat" value="<?php echo htmlentities($row_dt['wali_alamat'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">Telepon Wali:</td>
              <td><input name="wali_telpon" type="text" id="wali_telpon" value="<?php echo htmlentities($row_dt['wali_telpon'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">&nbsp;</td>
              <td><input type="submit" value="Update" /></td>
            </tr>
          </table>
          <input type="hidden" name="MM_update" value="form1" />
          <input type="hidden" name="wali_id" value="<?php echo $row_dt['wali_id']; ?>" />
        </form>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
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

mysql_free_result($sw);
?>
