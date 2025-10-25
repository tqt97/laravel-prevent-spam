# Laravel Prevent Spam 🛡️

A lightweight Laravel package to protect your forms from spam bots using a **honeypot** and **timing protection**.  
Simple to use, framework-native, and customizable.

---

## 🚀 Features

- 🧠 Invisible honeypot field to trap spam bots.
- ⏱️ Time-based protection: detects form submissions that are too fast.
- ⚙️ Fully configurable via `config/honeypot.php`.
- 💡 Blade component `<x-prevent-spam::honeypot />` for easy integration.
- 🔒 Middleware-ready — just add it to any form processing route.
- 🧩 Easily override exceptions for custom responses.

---

## 🧩 Installation

Install the package via Composer:

```bash
composer require tuantq/laravel-prevent-spam
```

If you're using **Laravel 10+ or 11+**, package discovery will automatically register the service provider and config.

For older Laravel versions, register manually in `config/app.php`:

```php
'providers' => [
    Tuantq\LaravelPreventSpam\PreventSpamServiceProvider::class,
],
```

---

## ⚙️ Publishing Configuration & Views

After installation, you can publish the config and view files to customize them:

```bash
php artisan vendor:publish --provider="Tuantq\LaravelPreventSpam\PreventSpamServiceProvider" --tag=config
```

```bash
php artisan vendor:publish --provider="Tuantq\LaravelPreventSpam\PreventSpamServiceProvider" --tag=views

```

This will create a file at: `config/honeypot.php`
This will copy the package view to: `resources/views/vendor/prevent-spam/honeypot.blade.php`
You can then edit this file freely to match your form structure or CSS style.

---

## 🧱 Usage

### 1️⃣ Add honeypot field to your form

In your Blade form:

```blade
<form method="POST" action="{{ route('post.store') }}">
    @csrf
    <x-prevent-spam::honeypot />

    <!-- your form inputs -->
    <input type="text" name="title">
    <button type="submit">Submit</button>
</form>
```

This component automatically renders:

```html
<div style="display: none" aria-hidden="true">
    <input id="my_name" name="my_name" type="text" value="" autocomplete="nope" tabindex="-1">
    <input id="my_time" name="my_time" type="text" value="1761397891.6765" autocomplete="off" tabindex="-1">
</div>
```

---

### 2️⃣ Protect your route or controller

Use the provided middleware to check the honeypot and timing:

```php
use Tuantq\LaravelPreventSpam\Middleware\PreventSpam;

Route::post('/post/create', [PostController::class, 'store'])
    ->middleware(PreventSpam::class)
    ->name('posts.store');
```

---

### 3️⃣ Custom exception handling

You can customize the spam handling logic globally or per middleware.

#### Option 1: Globally using Service Provider

```php
// app/Providers/AppServiceProvider.php

use Tuantq\LaravelPreventSpam\Honeypot;

public function boot(): void
{
    Honeypot::abortUsing(function () {
        return response('Custom response for spam', 422);
    });
}
```

#### Option 2: Override in your own middleware

```php
<?php
// app/Http/Middleware/BlockSpam.php

namespace App\Http\Middleware;

use Tuantq\LaravelPreventSpam\Middleware\PreventSpam;

class BlockSpam extends PreventSpam
{
    public function abort()
    {
        return response('Custom response for spam', 422);
    }
}
```

Then register your middleware instead of the default one.

---

## ⚙️ Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag=config
```

Example `config/honeypot.php`:

```php
return [
    'enabled' => env('HONEYPOT_ENABLED', true),
    'field_name' => env('HONEYPOT_NAME', 'my_name'),
    'field_time_name' => env('HONEYPOT_TIME', 'my_time'),
    'seconds' => env('HONEYPOT_SECONDS', 3),
];
```

| Key               | Description                                  | Default   |
| ----------------- | -------------------------------------------- | --------- |
| `enabled`         | Enable or disable honeypot protection        | `true`    |
| `field_name`      | Name of hidden honeypot field                | `my_name` |
| `field_time_name` | Hidden field to track form generation time   | `my_time` |
| `seconds`         | Minimum seconds before form can be submitted | `3`       |

---

## 🧪 Testing

You can test your form by submitting it immediately — you should see the 422 custom response.
Wait more than the configured seconds, and it will pass normally.

---

## 💬 Credits

Developed by [**Tuấn TQ**](https://github.com/tuantq)
Inspired by [Spatie’s Laravel Honeypot](https://github.com/spatie/laravel-honeypot)

---

## 🧾 License

This package is open-sourced software licensed under the [MIT license](LICENSE).
