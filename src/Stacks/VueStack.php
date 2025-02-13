<?php

namespace CodeCNG\LaravelInertiaTranslations\Stacks;

use CodeCNG\LaravelInertiaTranslations\Contracts\StackContract;

class VueStack extends StackContract
{
    protected static function javascriptFile(string $lang_csv, string $import_files): string
    {
        return <<<VUE
{$import_files}
import { usePage } from '@inertiajs/vue3'


const translations = { {$lang_csv} };

export const __ = (key) => {
  // eslint-disable-next-line
  const { language } = usePage().props ?? 'en';

  return translations[language]?.[key] || key;
};

VUE;
    }

    protected static function typescriptFile(string $lang_csv, string $import_files): string
    {
        return <<<TS
{$import_files}
import { usePage } from '@inertiajs/vue3';

type TranslationKey = string;

// @ts-expect-error This is a valid use
const translations: Record<string, Record<string, string>> = { {$lang_csv} };

export const __ = (key: TranslationKey) => {
    // @ts-expect-error This should be added to the types of Page
    const { language }: { language: string } = usePage().props ?? {
        language: 'en',
    };

    return translations[language]?.[key] || key;
};

TS;
    }
}
