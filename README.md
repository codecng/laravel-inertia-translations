# Laravel Inertia Translations

[![Latest Version on Packagist](https://img.shields.io/packagist/v/codecng/laravel-inertia-translations.svg?style=flat-square)](https://packagist.org/packages/codecng/laravel-inertia-translations)
[![Total Downloads](https://img.shields.io/packagist/dt/codecng/laravel-inertia-translations.svg?style=flat-square)](https://packagist.org/packages/codecng/laravel-inertia-translations)

A zero-configuration Laravel package that automatically exports your Laravel translations for use with Inertia.js. Supports both React and Vue, with full TypeScript support!

## Installation
```bash
composer require codecng/laravel-inertia-translations
```

That's it! No additional configuration needed.

## Usage

Whenever you add or modify translations in your Laravel application, simply run:
```bash
php artisan translate
```

This command will:
1. Check if language files exist (if not, it will publish them automatically)
2. Process all your translation files (both JSON and PHP)
3. Generate JSON translation files in `resources/js/lang/`
4. Create appropriate utility files based on your stack (React/Vue + TypeScript)

### What Gets Processed

- ✅ JSON files in `lang/` directory
- ✅ PHP files in language subdirectories
- ✅ Automatically merges all translations by locale

### Generated Files Structure
```
resources/js/
├── lang/
│   ├── en.json
│   ├── es.json
│   └── fr.json
└── lib/
    └── translations.(js|ts|jsx|tsx)  # Based on your stack
```

## Framework Support

### React
```jsx
import { __ } from '@/lib/translations'

function Welcome() {
    return (
        <div>
            <h1>{__('welcome.title')}</h1>
            <p>{__('welcome.message')}</p>
            <p>{__('Users')}</p>
        </div>
    )
}
```

### Vue
```vue
<script setup>
import { __ } from '@/lib/translations'
</script>

<template>
    <div>
        <h1>{{ __('welcome.title') }}</h1>
        <p>{{ __('welcome.message') }}</p>
    </div>
</template>
```

### Inertia Setup

Make sure to include the current language in your Inertia shared props (in your HandleInertiaRequests middleware):
```php
public function share(Request $request): array
{
    return array_merge(parent::share($request), [
        'language' => request()->user()->language ?? app()->getLocale(),
    ]);
}
```

## Type Support

When using TypeScript, you get full type support for your translation keys:

```typescript
// The __ function is fully typed
__('welcome.title') // ✓ Valid
__('invalid.key')   // ✗ TypeScript error
```

## Benefits

- 🚀 Zero configuration required
- 🔄 Simple one-command updates
- 🛠 Works with both JSON and PHP translation files
- 💪 Full TypeScript support
- ⚡️ Supports both React and Vue
- 🔍 Automatic type generation for translation keys

## Credits

- [Christian Negron](https://github.com/codecng)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information. 
