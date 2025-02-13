<?php

namespace CodeCNG\LaravelInertiaTranslations\Contracts;

use Illuminate\Support\Facades\File;

abstract class StackContract
{
    abstract protected static function javascriptFile(string $lang_csv, string $import_files): string;

    abstract protected static function typescriptFile(string $lang_csv, string $import_files): string;

    private static function prepareFileContent(array $langs, callable $fileGenerator): string
    {
        sort($langs);

        $import_files = '';
        foreach ($langs as $lang) {
            $import_files .= "import $lang from '@/lang/$lang.json';\n";
        }

        $lang_csv = implode(', ', $langs);

        return $fileGenerator($lang_csv, $import_files);
    }

    public static function generateFile(array $langs, string $file_name)
    {
        self::generateFileContent($langs, $file_name, 'javascriptFile');
    }

    public static function generateTypescriptFile(array $langs, string $file_name)
    {
        self::generateFileContent($langs, $file_name, 'typescriptFile');
    }

    private static function generateFileContent(array $langs, string $file_name, string $fileType)
    {
        $utilsPath = resource_path('js/lib');
        File::ensureDirectoryExists($utilsPath);

        $content = self::prepareFileContent($langs, [static::class, $fileType]);

        File::put("$utilsPath/$file_name", $content);
    }
}
