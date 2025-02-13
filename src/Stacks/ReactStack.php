<?php

namespace CodeCNG\LaravelInertiaTranslations\Stacks;

use CodeCNG\LaravelInertiaTranslations\Contracts\StackContract;

class ReactStack extends StackContract
{
    protected static function javascriptFile(string $lang_csv, string $import_files): string
    {
        return <<<JSX
{$import_files}
import { usePage } from '@inertiajs/react';

const translations = { {$lang_csv} };

export const __ = (key) => {
  // eslint-disable-next-line react-hooks/rules-of-hooks
  const { language } = usePage().props ?? 'en';

  return translations[language]?.[key] || key;
};

JSX;
    }

    protected static function typescriptFile(string $lang_csv, string $import_files): string
    {
        return <<<TSX
    {$import_files}
    import { usePage } from '@inertiajs/react';
    
    type TranslationKey = string;
    
    // @ts-expect-error This is a valid implementation
    const translations: Record<string, Record<string, string>> = { {$lang_csv} };
    
    export const __ = (key: TranslationKey) => {
      // eslint-disable-next-line react-hooks/rules-of-hooks
      const { language } = usePage().props ?? 'en';
    
      return translations[language]?.[key] || key;
    };
    
    TSX;
    }
}
