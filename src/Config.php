<?php

declare(strict_types=1);

namespace Vendi\BASE;

use Symfony\Component\Yaml\Yaml;
use Webmozart\PathUtil\Path;

final class Config
{
    private static $settings;

    final public static function get_config() : array
    {
        if (self::$settings !== null) {
            return self::$settings;
        }

        $files = [
            '/config/config.dist.yaml',
            '/config/config.local.yaml',
        ];

        self::$settings = [];
        foreach($files as $file){
            $abs_path = Path::join(VENDI_BASE_ROOT_DIR, $file);
            if(\is_file($abs_path) && \is_readable($abs_path)){
                self::$settings = array_merge(self::$settings, Yaml::parseFile($abs_path));

            }
        }

        return self::$settings;
    }
}
