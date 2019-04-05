<?php

use Webmozart\PathUtil\Path;
use Vendi\Shared\utils as vendi_utils;

require_once dirname(__DIR__) . '/includes/vendi_boot.php';

session_start();

if (file_exists('../base_conf.php'))
	die ("If you wish to re-run the setup routine, please either move OR delete your previous base_conf file first.");

$errorMsg = '';

if (vendi_utils::is_post()) {
   // form was submitted do the checks!
   if ($_POST['useuserauth'] == "on" && ($_POST['usrlogin'] == "" || $_POST['usrpasswd'] == "" || $_POST['usrname'] == ""))
   {
      $errorMsg = "You must fill in all of the fields or uncheck \"Use Authentication System\"!";
      $error = 1;
   }
   $_SESSION['useuserauth'] = ($_POST['useuserauth'] == "on") ? 1 : 0;
   $_SESSION['usrlogin'] = $_POST['usrlogin'];   // filtred in setup4.php with filterSql()
   $_SESSION['usrpasswd'] = $_POST['usrpasswd']; // no need to filter. will be taken only md5 hash
   $_SESSION['usrname'] = $_POST['usrname'];     // filtred in setup4.php with filterSql()

   if ($error != 1)
   {
      header("Location: setup4.php");
      exit();
   }

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!-- Basic Analysis and Security Engine (BASE) -->
<HTML>

<HEAD>
  <META HTTP-EQUIV="pragma" CONTENT="no-cache">
  <TITLE>Basic Analysis and Security Engine (BASE)</TITLE>
  <LINK rel="stylesheet" type="text/css" HREF="../styles/base_style.css">
</HEAD>
<BODY>
<TABLE WIDTH="100%" BORDER=0 CELLSPACING=0 CELLPADDING=5>
    <TR>
      <TD class="mainheader"> &nbsp </TD>
      <TD class="mainheadertitle">
         Basic Analysis and Security Engine (BASE) Setup Program
      </TD>
    </TR>
</TABLE>
<br>
<P>
<?php echo("<div class='errorMsg' align='center'>".$errorMsg."</div>"); ?>
<form method="POST">
<center><table width="50%" border=1 class ="query">
<tr><td colspan=2 align="center" class="setupTitle">Step 3 of 5</td><tr>
<tr><td colspan=2 align="center"><input type="checkbox" name="useuserauth" <?php if (vendi_utils::get_session_value('useuserauth')){echo "checked";}?>>Use Authentication System [<a href="../help/base_setup_help.php#useauth" onClick="javascript:window.open('../help/base_setup_help.php#useauth','helpscreen','width=300,height=300'); return false;">?</a>]</td></tr>
<tr><td class="setupKey">Admin User Name:</td><td class="setupValue"><input type="text" name="usrlogin" value="<?php echo vendi_utils::get_session_value('usrlogin');?>"></td></tr>
<tr><td class="setupKey">Password:</td><td class="setupValue"><input type="password" name="usrpasswd" value="<?php echo vendi_utils::get_session_value('usrpasswd');?>"></td></tr>
<tr><td class="setupKey">Full Name:</td><td class="setupValue"><input type="text" name="usrname" value="<?php echo vendi_utils::get_session_value('usrname');?>"></td></tr>
<tr><td colspan=2 align="center"><input type="submit" value="Continue"></td></tr>
</table></center></form>
</BODY>
</HTML>
