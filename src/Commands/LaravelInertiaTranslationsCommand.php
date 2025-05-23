<?php

namespace CodeCNG\LaravelInertiaTranslations\Commands;

use CodeCNG\LaravelInertiaTranslations\Stacks\ReactStack;
use CodeCNG\LaravelInertiaTranslations\Stacks\VueStack;
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
        $this->info("All translations have been exported to $outputPath");

        $this->exportTranslationsFile(array_keys($translations));
        $this->info('Translation helper function generated successfully.');

        return self::SUCCESS;
    }

    public function exportTranslationsFile(array $languages)
    {
        $appPath = resource_path('js');

        if (File::exists($appPath.'/app.tsx')) {
            ReactStack::generateTypescriptFile($languages, 'translations.tsx'); // react with typescript
        } elseif (File::exists($appPath.'/app.jsx')) {
            ReactStack::generateFile($languages, 'translations.jsx'); // react
        } elseif (File::exists($appPath.'/app.js')) {
            VueStack::generateFile($languages, 'translations.js'); // vue
        } elseif (File::exists($appPath.'/app.ts')) {
            VueStack::generateTypescriptFile($languages, 'translations.ts'); // typescript
        }
    }
}
