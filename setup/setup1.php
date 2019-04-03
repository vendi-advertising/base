<?php

use Webmozart\PathUtil\Path;
use Vendi\Shared\utils as vendi_utils;

require_once dirname(__DIR__) . '/includes/vendi_boot.php';

session_start();

define( "_BASE_INC", 1 );
include("../includes/base_setup.inc.php");
include("../includes/base_state_common.inc.php");

if (file_exists('../base_conf.php'))
	die ("If you wish to re-run the setup routine, please either move OR delete your previous base_conf file first.");

$errorMsg = '';

/* build array of languages */
$i = 0;
if ($handle = opendir('../languages')) {
   while (false !== ($file = readdir($handle))) {
       if ($file != "." && $file != ".." && $file != "CVS" && $file != "index.php") {
           $filename = explode(".", $file);
           $languages[$i] = $filename[0];
           $i++;

       }
   }
   closedir($handle);
}

//Loaded from Composer now, hardcode it
$adodb_file_path = Path::join(VENDI_BASE_ROOT_DIR, '/vendor/adodb/adodb-php/');
$_SESSION['adodbpath'] = $adodb_file_path;

if (vendi_utils::is_post()) {
    $_SESSION['language'] = ImportHTTPVar("language", "", $languages);
    header("Location: setup2.php");
    exit;
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
<tr><td colspan=2 align="center" class="setupTitle">Step 1 of 5</td><tr>
<tr><td class="setupKey" width="50%">Pick a Language:</td><td class="setupValue"><select name="language">
<?php
    $langCount = count($languages);
    for ($y = 0; $y < $langCount; $y++) {
        /* If there is language saved from session then make it selected.
         * If there was no session language - make 'english' selected.
         */
        if (array_key_exists('language', $_SESSION))
        {
            if (
                 ($languages[$y] == vendi_utils::get_session_value('language')) ||
                 ($_SESSION['language'] == '' && $languages[$y] == 'english')
               )
            {
              echo("<OPTION name='".$languages[$y]."' SELECTED>".$languages[$y]);
            }
            else
            {
              echo("<OPTION name='".$languages[$y]."'>".$languages[$y]);
            }
        }
        else
        {
            if ($languages[$y] == 'english')
            {
               echo("<OPTION name='".$languages[$y]."' SELECTED>".$languages[$y]);
            }
            else
            {
              echo("<OPTION name='".$languages[$y]."'>".$languages[$y]);
            }
        }
    }
?>
</select>
[<a href="../help/base_setup_help.php#language" onClick="javascript:window.open('../help/base_setup_help.php#language','helpscreen','width=300,height=300'); return false;">?</a>]
</td></tr>
<tr><td colspan=2 align="center"><input type="submit" value="Continue"></td></tr>
</table></center></form>
</BODY>
</HTML>
