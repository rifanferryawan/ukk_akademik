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
?><?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['nm'])) {
  $loginUsername=$_POST['nm'];
  $password=$_POST['pass'];
  $MM_fldUserAuthorization = "level";
  $MM_redirectLoginSuccess = "user.php";
  $MM_redirectLoginFailed = "loginu.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_konek, $konek);
  	
  $LoginRS__query=sprintf("SELECT username, password, level FROM pengguna WHERE username=%s AND password=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $konek) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'level');
    
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
      <h3>Apa yang harus dilakukan?</h3>
      <p>Untuk dapat menggunakan semua layananan kami, silakan masukkan username dan password Anda pada form yang tersedia. Pastikan username dan password yang Anda masukkan sudah benar. Kemudian tekan tombol login untuk proses validasi.</p>
    </div>
      <div id="isi">
        <h3>Login Eksekutif</h3>
        <form id="form1" name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
          <table width="250" border="0" cellspacing="0">
            <tr>
              <td>Username</td>
            </tr>
            <tr>
              <td><label>
                <input type="text" name="nm" id="nm" />
              </label></td>
            </tr>
            <tr>
              <td>Password</td>
            </tr>
            <tr>
              <td><label>
                <input type="password" name="pass" id="pass" />
              </label></td>
            </tr>
            <tr>
              <td><label>
                <input type="submit" name="login" id="login" value="Login" />
              </label></td>
            </tr>
          </table>
        </form>
        <p><a href="login.php">login admin</a></p>
    </div>
  </div>
</div>
<div id="footer">
  <div class="isi">&copy; rifan ferryawan - 2014 </div>
</div>
</body>
</html>
