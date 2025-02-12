<?php

namespace CodeCNG\LaravelInertiaTranslations\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class LaravelInertiaTranslationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export PHP translations to JSON files for the frontend';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {

        $langPath = lang_path();

        $outputPath = resource_path('js/lang');

        if (! File::exists($langPath)) {
            $this->info("Language folder not found: $langPath");
            $this->info('Publishing language files...');
            // return self::FAILURE;
            $this->call('lang:publish');
            $this->info('Language files published successfully.');
        }

        File::ensureDirectoryExists($outputPath, 0755);

        $translations = [];

        // Handle JSON files in root lang directory
        foreach (File::files($langPath) as $file) {
            if ($file->getExtension() === 'json') {
                $locale = $file->getBasename('.json');
                $translations[$locale] = json_decode(File::get($file), true) ?? [];
            }
        }

        // Handle subdirectories
        foreach (File::directories($langPath) as $langDir) {
            $locale = basename($langDir);
            if (! isset($translations[$locale])) {
                $translations[$locale] = [];
            }

            // Load and merge all translation files for this locale
            foreach (File::allFiles($langDir) as $file) {
                $data = require $file;
                if (is_array($data)) {
                    $translations[$locale] = array_merge($translations[$locale], $data);
                }
            }
        }

        // Write all translations to JSON files
        foreach ($translations as $locale => $data) {
            File::put(
                "$outputPath/{$locale}.json",
                json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );
            $this->info("Generated translations for: $locale");
        }

        $this->exportTranslationsTSXFile(array_keys($translations));

        $this->info("All translations have been exported to $outputPath");

        return self::SUCCESS;
    }

    public function exportTranslationsTSXFile(array $languages)
    {
        sort($languages);
                              
        // Create resources/js/utils directory if it doesn't exist
        $utilsPath = resource_path('js/lib');
        File::ensureDirectoryExists($utilsPath);

        $import_files = '';

        foreach ($languages as $language) {
            $import_files .= "import $language from '@/lang/$language.json';\n";
        }

        $languages_list = implode(', ', $languages);
                        
        // Create the translations.tsx file
        $translationsContent = <<<TSX
{$import_files}
import { usePage } from '@inertiajs/react';

type TranslationKey = string;

// @ts-expect-error This is a valid use
const translations: Record<string, Record<string, string>> = { {$languages_list} };

export const __ = (key: TranslationKey) => {
  // eslint-disable-next-line react-hooks/rules-of-hooks
  const { language } = usePage().props ?? 'en';

  return translations[language]?.[key] || key;
};

TSX;

        File::put($utilsPath . '/translations.tsx', $translationsContent);
    
    }
}