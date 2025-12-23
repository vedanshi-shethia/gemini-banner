---

# Gemini Banner Generator for Laravel

A **Laravel package** to generate high-quality **website advertisement banners** using **Google Gemini Image API**, designed specifically for **book stores and publishers**.

This package strictly preserves **original book cover text, typography, and layout** by enforcing **copy-paste image usage** instead of AI text recreation.

---

## ✨ Features

* 🎨 AI-generated website banners using **Google Gemini**
* 🔒 **Private input images**, 🌍 **public generated banners**
* ❌ Zero text distortion or hallucination
* 📐 Aspect-ratio locking via reference image
* ⚡ **Sync & Async (Queue) support**
* 🧱 Clean **service-based architecture**
* 🪄 Facade for simple usage
* 🧪 Queue-safe & retry-safe
* ⚙️ Fully configurable storage paths & disks
* 📦 Laravel auto-discovery support

---

## 📦 Installation

### Via Composer

```bash
composer require vedanshi-shethia/gemini-banner
```

---

## ⚙️ Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag=gemini-banner-config
```

This creates:

```
config/gemini-banner.php
```

---

### 🔑 Environment variables

```env
GEMINI_API_KEY=your_google_gemini_api_key

# Storage
GEMINI_INPUT_DISK=local
GEMINI_OUTPUT_DISK=public
```

---

## 🧠 Storage Design (Important)

This package follows **industry best practices**:

| Type              | Disk     | Visibility |
| ----------------- | -------- | ---------- |
| Input images      | `local`  | 🔒 Private |
| Generated banners | `public` | 🌍 Public  |

```
storage/
├── app/
│   ├── private/
│   │   └── gemini/input/
│   └── public/
│       └── gemini/output/
```

---

## ⚙️ Config Reference

```php
return [

    'api_key' => env('GEMINI_API_KEY'),

    'endpoint' => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-image:generateContent',

    'disks' => [
        'input'  => env('GEMINI_INPUT_DISK', 'local'),
        'output' => env('GEMINI_OUTPUT_DISK', 'public'),
    ],

    'paths' => [
        'front'     => 'gemini/input/front',
        'back'      => 'gemini/input/back',
        'reference' => 'gemini/input/reference',
        'output'    => 'gemini/output',
    ],

    'cleanup' => [
        'enabled' => true,
    ],
];
```

---

## 🚀 Usage

### 1️⃣ Sync Generation (Facade)

```php
use Vedanshi\GeminiBanner\Facades\GeminiBanner;
use Vedanshi\GeminiBanner\Http\Requests\GenerateBannerRequest;

function (GenerateBannerRequest $request) {
    $result = GeminiBanner::generate($request->payload());
}
```

Returns a **public URL** of the generated banner.

---

### 2️⃣ Async Generation (Queue)

```php
use Vedanshi\GeminiBanner\Jobs\GenerateGeminiBannerJob;
use Vedanshi\GeminiBanner\Http\Requests\GenerateBannerRequest;

function (GenerateBannerRequest $request) {
    GenerateGeminiBannerJob::dispatch($request->payload());
}

```

✅ Ideal for:

* Heavy image processing
* High-traffic systems
* Background workflows

---

## 🧾 Expected Payload Structure

```php
[
    'front_image' => string,        // path on input disk
    'back_image' => string,         // path on input disk
    'transparent_image' => string,  // path on input disk
    'product_name' => string,
]
```

> ⚠️ Do NOT pass temp paths (`php/tmp`).
> Files must be stored first using Laravel storage.

---

## 🧹 Automatic Cleanup

* Input images are **deleted immediately after successful generation**
* Cleanup is **retry-safe**
* Cleanup can be disabled via config

```php
'cleanup' => [
    'enabled' => false,
],
```

---

## 🧱 Architecture Overview

```
Request / Job
   ↓
GenerateBannerRequest
   ↓
GeminiBannerService
   ↓
Gemini API
   ↓
Public Storage (output)
```

* No controllers are published
* No routes are forced
* You stay in control of your application flow

---

## 📄 Requirements

* PHP **8.1+**
* Laravel **9.x / 10.x / 11.x / 12.x**
* Google Gemini API access

---

## 📜 License

MIT License

---

## 🙌 Credits

Developed by **Vedanshi Shethia**
Powered by **Google Gemini AI**

---

## 🤝 Contributing

Pull requests are welcome.
For major changes, please open an issue first to discuss improvements.

---
