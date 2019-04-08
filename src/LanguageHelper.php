<?php

declare(strict_types=1);

namespace Vendi\BASE;

final class LanguageHelper
{
    public static function get_all_languages(): array
    {
        //TODO - make less ugly
        $languages = [];

        if ($handle = opendir('../languages')) {
            while (false !== ($file = readdir($handle))) {
                if ('.' != $file && '..' != $file && 'CVS' != $file && 'index.php' != $file) {
                    $filename = explode('.', $file);
                    $languages[] = $filename[0];
                }
            }
            closedir($handle);
        }

        return $languages;
    }
}
