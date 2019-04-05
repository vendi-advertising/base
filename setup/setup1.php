<?php

use Webmozart\PathUtil\Path;
use Vendi\Shared\utils as vendi_utils;
use Vendi\BASE\LanguageHelper;

require_once dirname(__DIR__) . '/includes/vendi_boot.php';

session_start();

define( "_BASE_INC", 1 );
include("../includes/base_setup.inc.php");
include("../includes/base_state_common.inc.php");

if (file_exists('../base_conf.php')){
    throw new \Exception("If you wish to re-run the setup routine, please either move OR delete your previous base_conf file first.");
}

$errorMsg = '';

/* build array of languages */
$languages = LanguageHelper::get_all_languages();

if (vendi_utils::is_post()) {
    //Loaded from Composer now, hardcode it
    $_SESSION['adodbpath'] = Path::join(VENDI_BASE_ROOT_DIR, '/vendor/adodb/adodb-php/');
    $_SESSION['language'] = ImportHTTPVar("language", "", $languages);
    header("Location: setup2.php");
    exit;
}

$loader = new \Twig\Loader\FilesystemLoader(VENDI_BASE_ROOT_DIR . '/templates');
$twig = new \Twig\Environment($loader, [
    'cache' => VENDI_BASE_ROOT_DIR . '/var/cache/twig',
    'debug' => true,
    'auto_reload' => true,
    'strict_variables' => true,
]);

$body = '<form method="POST">';
$body .= '<center><table width="50%" border=1 class ="query">';
$body .= '<tr><td colspan=2 align="center" class="setupTitle">Step 1 of 5</td><tr>';
$body .= '<tr><td class="setupKey" width="50%">Pick a Language:</td><td class="setupValue"><select name="language">';
$user_lang = vendi_utils::get_session_value('language') ? vendi_utils::get_session_value('language') : 'english';
foreach($languages as $idx => $lang){
    $selected = '';
    if($user_lang === $lang){
        $selected = 'selected="selected"';
    }
    $body .= sprintf('<option name="%1$s"%2$s>%1$s</option>', $lang, $selected);
}
$body .= '</select>';
$body .= '[<a href="../help/base_setup_help.php#language" onClick="javascript:window.open(\'../help/base_setup_help.php#language\',\'helpscreen\',\'width=300,height=300\'); return false;">?</a>]';
$body .= '</td></tr>';
$body .= '<tr><td colspan=2 align="center"><input type="submit" value="Continue"></td></tr>';
$body .= '</table></center></form>';

echo $twig
        ->render(
            'setup/index.html.twig',
            [
                'body' => $body,
            ]
        );
exit;
