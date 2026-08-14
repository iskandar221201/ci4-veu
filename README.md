# CodeIgniter 4 Production Grade Kit
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square&logo=php&logoColor=white)
![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4.x-EF4223?style=flat-square&logo=codeigniter&logoColor=white)
![Shield](https://img.shields.io/badge/Shield-Auth-22c55e?style=flat-square)
![License](https://img.shields.io/badge/license-MIT-blue?style=flat-square)
![Status](https://img.shields.io/badge/status-stable-brightgreen?style=flat-square)
![Version](https://img.shields.io/badge/version-4.0.0-blue?style=flat-square)

A Production-grade CI4 starter kit — full-stack monolith or pure REST API backend. Pairs with any frontend framework. Shield auth · Service layer · Audit trail · SSO · PDF export · Resumable uploads · WebSocket. Clone, extend, ship in a day

---

## Contents

- [Requirements](#requirements)
- [Quick Start](#quick-start)
- [Architecture Overview](#architecture-overview)
- [Project Structure](#project-structure)
- [API Response Envelope](#api-response-envelope)
- [Filter Stack](#filter-stack)
- [Authentication Flow](#authentication-flow)
- [Web UI Layer](#web-ui-layer)
- [Logging](#logging)
- [File Uploads](#file-uploads)
- [Transformers](#transformers)
- [Audit Trail](#audit-trail)
- [SSO Layer](#sso-layer)
- [PDF Export](#pdf-export)
- [TUS Chunked Upload](#tus-chunked-upload)
- [Compatibility Matrix](#compatibility-matrix)
- [How to Add a New Resource](#how-to-add-a-new-resource)
- [Server Requirements](#server-requirements)

---

## Requirements

| Dependency | Version |
|---|---|
| PHP | 8.2+ |
| Composer | latest |
| MySQL | 8.0+ |
| MariaDB | 10.5+ *(alternative)* |
| Web Server | Apache / Nginx / `php spark serve` |

---

## Quick Start

Click **Use this template** → **Create a new repository**, then:

```bash
# 1. Enter the project
cd my-project

# 2. Copy environment template and fill in DB credentials
cp .env.example .env

# 3. Install dependencies
composer install

# 4. Run all migrations (CI4 + Shield tables)
php spark migrate --all

# 5. Seed the admin user
php spark db:seed AdminSeeder

# 6. Start the development server
php spark serve

# 7. Open the web UI
open http://localhost:8080/login
# Email: admin@example.com  |  Password: password123

# 8. Verify the API
curl http://localhost:8080/api/ping
# Expected: {"status":true,"code":200,"message":"pong","data":null}
```

---

## Architecture Overview

```
Request → Filter Stack → Controller → Service → Model → Database
                                           ↓
                                     Transformer
```

| Layer | Responsibility |
|---|---|
| **Web Controller** | Serves HTML views. No business logic — just passes data to views. |
| **API Controller** | Receives JSON requests, delegates to Service, returns JSON response. Never accesses a Model directly. |
| **Service** | Holds business logic, validates input, orchestrates Model calls. |
| **Transformer** | Shapes and sanitizes response payloads before they reach the API response layer. |
| **Model** | App models extend `BaseModel` (soft delete, search/dateRange scopes). Shield-based models extend `ShieldUserModel` directly. |

### Optional Layers (v2.0+)

| Layer | Files | Notes |
|---|---|---|
| **SSO Layer** | `SSOConfig`, `JWTService`, `SSOFilter` | JWT RS256 auth for cross-app requests. Disabled by default (`SSO_ENABLED=false`). |
| **PDF Export** | `BasePdfExporter` | Abstract base for mPDF-based PDF generation. Extend per module. |
| **TUS Chunked Upload** | `TusConfig`, `TusUploader`, `TusController`, `TusCleanupCommand` | TUS protocol-based resumable uploads for large files. Requires `composer require ankitpokhrel/tus-php`. |
| **WebSocket Server** | `WsConfig`, `WsPublisher`, `WsServer`, `WsServeCommand` | Ratchet-based real-time events. CI4 publishes via internal HTTP. Disabled by default (`WS_ENABLED=false`). |

### Lifecycle Hooks

Override any of these in your Service to react to CRUD events without touching `BaseService`:

```php
protected function afterCreate(int|string $id, array $data): void
protected function afterUpdate(int|string $id, array $data): void
protected function afterDelete(int|string $id, array $oldData): void
```

Hook failures are non-blocking — they log to `log_message()` and never break the main operation.

> Note: `BaseService::update()` now returns the updated entity instead of `true`. Controllers receive the fresh record directly without a second database query.

---

## Project Structure

```
app/
├── Config/
│       ├── AppConstants.php      # HTTP status codes, pagination caps, and app-wide constants
│   ├── Filters.php           # Filter aliases and route bindings
│   ├── Routes.php            # Route definitions (web + API)
│   ├── SSOConfig.php         # SSO toggle + RSA key config (v2.0)
│   ├── TusConfig.php         # TUS upload dir, max size, expiry (v3.0)
│   └── WsConfig.php          # WebSocket host, port, enabled, secret (v4.0)
├── Controllers/
│   ├── BaseController.php    # Base for all controllers (traits wired here)
│   ├── Api/
│   │   ├── BaseApiController.php   # Forces JSON response, populates $apiUser
│   │   ├── AuthController.php      # Token-based login endpoint
│   │   ├── PingController.php      # Health check endpoints
│   │   ├── TusController.php       # TUS protocol handler (v3.0)
│   │   └── UserController.php      # Full CRUD reference implementation
│   └── Web/
│       ├── DashboardController.php # Serves dashboard view
│       └── UserWebController.php   # Serves user management views
├── Exceptions/
│   ├── ServiceException.php        # General service-layer exception
│   └── ValidationException.php     # Wraps validation errors (422)
├── Filters/
│   ├── ApiKeyFilter.php      # Validates Bearer token via Shield AccessTokens
│   ├── AuthFilter.php        # Session auth guard for web routes
│   ├── CorsFilter.php        # CORS headers + OPTIONS preflight
│   ├── JsonBodyFilter.php    # Rejects non-JSON bodies on POST/PUT/PATCH
│   └── SSOFilter.php         # JWT Bearer token verification for SSO (v2.0)
├── Helpers/
│   └── response_helper.php   # api_success() / api_error() for filter context
├── Commands/
│   ├── TusCleanupCommand.php  # php spark tus:cleanup — removes expired TUS uploads (v3.0)
│   └── WsServeCommand.php     # php spark ws:serve — starts Ratchet WebSocket server (v4.0)
├── Contracts/
│   └── StorageDriverInterface.php  # Abstraction for pluggable storage backends
├── Libraries/
│   ├── AppLogger.php              # Static facade for structured JSON logging
│   ├── BasePdfExporter.php        # Abstract base for PDF export via mPDF (v2.0)
│   ├── FileUploader.php           # Standardized upload handler for module files
│   ├── JWTService.php             # JWT RS256 sign and verify (v2.0)
│   ├── TusUploader.php            # TUS protocol server wrapper + cleanup (v3.0)
│   ├── VoidExceptionHandler.php   # Prevents double-response on API error routes
│   ├── WsPublisher.php            # HTTP publisher from CI4 to Ratchet server (v4.0)
│   ├── WsServer.php               # Ratchet WebSocket server wrapper (v4.0)
│   └── Storage/
│       ├── LocalDriver.php   # Default local filesystem storage driver
│       └── S3Driver.php      # Optional S3-compatible storage driver
├── Models/
│   ├── BaseModel.php         # Timestamps, soft delete, search/dateRange scopes
│   └── UserModel.php         # Extends Shield's UserModel + QueryScopesTrait
├── Services/
│   ├── BaseService.php       # CRUD + pagination + validation wiring
│   └── UserService.php       # User resource — full reference implementation
├── Traits/
│   ├── ApiResponseTrait.php  # success(), error(), created(), paginate(), noContent()
│   ├── AuditTrailTrait.php   # auditCreate(), auditUpdate(), auditDelete(), auditRestore()
│   ├── LoggableTrait.php     # logInfo(), logWarning(), logError() with JSON payload
│   └── QueryScopesTrait.php  # search(), dateRange(), active() — used by BaseModel and Shield-based models
├── Transformers/
│   └── BaseTransformer.php   # Abstract base for sanitizing and shaping API payloads
├── Validation/
│   └── BaseValidator.php     # Thin wrapper around CI4 Validation service
└── Views/
    ├── _layouts/
    │   ├── auth.php          # Minimal layout for login page
    │   └── main.php          # App shell layout (sidebar + navbar)
    ├── _partials/
    │   ├── navbar.php        # Top bar — reads username from localStorage
    │   ├── sidebar.php       # Side nav — app name from APP_NAME env
    │   ├── page_header.php   # Page title + breadcrumb + optional action button
    │   ├── datatable.php     # Reusable table with pagination
    │   ├── search_bar.php    # Debounced search input
    │   ├── form.php          # Generic form wrapper (Alpine formHandler)
    │   ├── confirm_dialog.php# Confirmation modal for destructive actions
    │   ├── error_toast.php   # Global toast notification
    │   ├── empty_state.php   # Empty data illustration
    │   ├── detail_card.php   # Label-value detail card
    │   ├── badge.php         # Status badge
    │   ├── loading_overlay.php # Full-page loading overlay
    │   └── flash.php         # Server-side flash messages
    ├── auth/
    │   └── login.php         # Login page
    ├── dashboard/
    │   └── index.php         # Dashboard home
    ├── users/
    │   ├── index.php         # User list with search + pagination
    │   ├── create.php        # Create user form (username, email, password)
    │   └── detail.php        # User detail + inline edit + delete
    └── exports/              # PDF export templates — plain HTML, no layout (v2.0)
```

```
public/assets/js/
├── auth.js        # Token + username storage (localStorage), auth guard
├── api.js         # Fetch wrapper — attaches Bearer token, unwraps envelope
├── error.js       # Global errorHandler toast driver
├── components.js  # Alpine component definitions (dataTable, formHandler, etc.)
└── tus-client.js  # Optional TUS chunked upload helper (require per-view, v3.0)
```

> Files marked `(v2.0)` or `(v3.0)` are additive. Projects using earlier versions are unaffected.

---

## API Response Envelope

All responses follow a consistent JSON structure:

**Success**
```json
{
  "status": true,
  "code": 200,
  "message": "Success",
  "data": { }
}
```

**Error / Validation**
```json
{
  "status": false,
  "code": 422,
  "message": "Validation failed",
  "errors": { "email": "The email field is required." }
}
```

**Paginated**
```json
{
  "status": true,
  "code": 200,
  "message": "Success",
  "data": [ ],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 100,
    "total_pages": 7
  }
}
```

---

## Filter Stack

```
Request → CorsFilter → JsonBodyFilter → ApiKeyFilter / SSOFilter / AuthFilter → Controller
```

| Filter | Applied To | Purpose |
|---|---|---|
| `CorsFilter` | `api/*` (before + after) | Injects CORS headers; handles OPTIONS preflight with `204`; passes TUS OPTIONS through to controller |
| `JsonBodyFilter` | `api/*` (before) | Rejects POST/PUT/PATCH without `Content-Type: application/json`; skips `api/upload/tus` routes |
| `ApiKeyFilter` | `api/*` protected group | Validates Bearer token via Shield AccessTokens |
| `SSOFilter` | `api/*` protected group (opt-in) | Verifies JWT Bearer token via RS256. Pass-through when `SSO_ENABLED=false`. |
| `AuthFilter` | web routes | Checks session login; redirects to `/login` if missing |

Filter registration: `app/Config/Filters.php`

---

## Authentication Flow

This kit uses **token-based authentication** for all API calls. The web UI stores the token in `localStorage` and attaches it as a `Bearer` header on every request via `api.js`.

### Login

```
POST /api/auth/login
Content-Type: application/json

{ "email": "admin@example.com", "password": "password123" }
```

Response:
```json
{
  "status": true,
  "code": 200,
  "message": "Login berhasil",
  "data": {
    "token": "<shield-access-token>",
    "email": "admin@example.com",
    "username": "admin"
  }
}
```

The `AuthController` validates credentials directly via Shield's user provider and `service('passwords')->verify()` — **without touching the PHP session at all**. This avoids Shield's session-state conflict error (`The user has User Info in Session`) when hitting the login endpoint multiple times or from stateless API clients.

Credential validation flow:
1. `findByCredentials(['email' => ...])` — fetch user + populate `password_hash` from `auth_identities`
2. `service('passwords')->verify(...)` — bcrypt comparison, no session side-effect
3. `user->active` check — reject inactive accounts with `403`
4. `generateAccessToken('api-login')` — issue Shield access token
   - Previous tokens from other devices are **not revoked** — users stay logged in across devices

After login, `auth.js` stores the token and username in `localStorage`:

```js
auth.setToken(data.token)
auth.setUsername(data.username || data.email)
```

### Auth Guard (client-side)

`auth.js` runs `checkAuthRoute()` automatically on every page load:

- If authenticated → blocks access back to `/login`
- If not authenticated → redirects to `/login` from any protected path

### Logout

```js
auth.logout() // clears localStorage and redirects to /login
```

### `api.js` behavior

| HTTP Status | Behavior |
|---|---|
| `401` on non-login routes | `auth.logout()` — clear token and redirect |
| `401` on `/api/auth/login` | Throw `{ message }` — display error in form |
| `422` | Throw `{ errors }` — mapped per-field in form |
| Other non-OK | `errorHandler.catch()` — show toast |

---

## Web UI Layer

The web UI is a server-rendered PHP shell with Alpine.js components calling the API. It is **stateless on the server** — all auth state lives in `localStorage`.

### Routes

| Method | Path | Controller | Description |
|---|---|---|---|
| GET | `/login` | `Web\UserWebController::loginPage` | Login page |
| GET | `/dashboard` | `Web\DashboardController::index` | Dashboard |
| GET | `/users` | `Web\UserWebController::index` | User list |
| GET | `/users/create` | `Web\UserWebController::create` | Create user form |
| GET | `/users/:id` | `Web\UserWebController::detail` | User detail + edit |

### App Name

The app name displayed in the sidebar and login page is read from `.env`:

```
APP_NAME="My App"
```

Rendered via `env('APP_NAME', 'CI4 Kit')` — no hardcoding in views.

### Username Display

The logged-in user's name in the navbar is read from `localStorage` via Alpine:

```html
<span x-data="{ displayName: auth.getUsername() }" x-text="displayName"></span>
```

`auth.setUsername()` is called at login with `data.username || data.email` from the API response.

### JS Load Order

Scripts must be loaded in this order so Alpine can reference `auth`, `api`, and `errorHandler` on init:

```html
<script src="/assets/js/auth.js"></script>
<script src="/assets/js/error.js"></script>
<script src="/assets/js/api.js"></script>
<script src="/assets/js/components.js"></script>
<script defer src="alpinejs CDN"></script>
```

Alpine uses `defer` so it initializes after the CI4 Kit globals are defined.

### User Management

Administrators can create users from the web UI at `/users/create`. The form accepts:

| Field | Notes |
|---|---|
| Username | Required, min 3 chars, unique |
| Email | Required, valid email, unique |
| Password | Required, min 8 chars — set by admin at creation time, shown as plain text input |

Users created via the web UI are **automatically activated** (`active = 1`) — no email verification required.

The user list at `/users` includes a **Tambah User** button in the page header that links to `/users/create`.

---

## UI Design System

All views follow a consistent clean white style — no blue/purple accents. The design system is:

| Element | Style |
|---|---|
| Background | `bg-white` — no colored backgrounds |
| Border | `border-gray-200` — thin neutral borders |
| Primary button | `bg-gray-900 hover:bg-gray-700` — charcoal black |
| Secondary button | `border-gray-300 text-gray-600` — outlined neutral |
| Input focus | `focus:ring-gray-400` — neutral, not blue |
| Danger action | `text-red-600` — red only for destructive actions |
| Toast (info) | `bg-gray-800` — dark neutral |
| Active nav item | `bg-gray-900 text-white` — same as primary button |

The app name in the sidebar and login page title is always read from `env('APP_NAME')` — never hardcoded in views.

---

## Logging

`AppLogger` can be called statically from anywhere:

```php
use App\Libraries\AppLogger;

AppLogger::info('payment.success', ['amount' => 50000, 'user_id' => 12]);
AppLogger::warning('rate.limit.hit', ['ip' => $ip]);
AppLogger::error('webhook.failed', ['payload' => $raw], $exception);
```

Inside a Controller via `LoggableTrait`:

```php
$this->logInfo('user.created', ['id' => $userId]);
$this->logError('user.create.failed', [], $e);
```

Every log entry is a structured JSON line written to `writable/logs/`:

```json
{
  "timestamp": "2026-07-20T08:44:00+07:00",
  "level": "INFO",
  "action": "user.created",
  "user_id": 3,
  "ip": "127.0.0.1",
  "context": { "id": 42 }
}
```

> **Warning:** Never pass sensitive data (passwords, tokens, PII) in the `$context` array.

---

## File Uploads

The kit provides two upload mechanisms: a simple single-request handler and a TUS protocol-based chunked uploader for large files.

### Single-Request Upload — `FileUploader`

[app/Libraries/FileUploader.php](app/Libraries/FileUploader.php) handles module uploads in a single request.

It supports:
- configurable max size and allowed extensions
- UUID-based filenames by default
- structured storage under `writable/uploads/{module}/{year}/{month}/`
- pluggable storage drivers with Local as the default and optional S3-compatible support
- deletion of old files when replacing uploads
- **streaming upload via `$file->move()`** — files are moved directly from temp to target directory, never loaded into memory. A 100MB file uses ~0 extra PHP memory.

Example usage:

```php
$uploader = new \App\Libraries\FileUploader();
$result = $uploader->upload($file, 'avatar');
```

Optional S3 usage:

```php
$uploader = new \App\Libraries\FileUploader([], new \App\Libraries\Storage\S3Driver([
    'bucket' => env('S3_BUCKET'),
    'region' => env('S3_REGION'),
    'key'    => env('S3_KEY'),
    'secret' => env('S3_SECRET'),
]));
```

> S3 uploads are retried up to 3 times with exponential backoff (100ms/200ms/400ms) on transient failures. CURL errors and HTTP 5xx are retried; 4xx are not.

### Chunked/Resumable Upload — TUS Protocol

For large files that exceed PHP's `upload_max_filesize` or `post_max_size`, the kit provides an optional TUS protocol layer. TUS splits files into chunks and uploads them via multiple `PATCH` requests, allowing pause and resume.

**Requires one-time setup:**

```bash
composer require ankitpokhrel/tus-php
```

**Files involved:**

| File | Purpose |
|---|---|
| `app/Config/TusConfig.php` | Config — upload dir, max size (default 1 GB), expiry |
| `app/Libraries/TusUploader.php` | Implements `StorageDriverInterface`, wraps tus-php server |
| `app/Controllers/Api/TusController.php` | TUS protocol handler at `/api/upload/tus` |
| `app/Commands/TusCleanupCommand.php` | `php spark tus:cleanup` — remove expired incomplete uploads |
| `public/assets/js/tus-client.js` | Optional Alpine-compatible JS client |

**TUS Endpoints:**

| Method | Path | Behavior |
|---|---|---|
| `OPTIONS` | `/api/upload/tus` | Capability discovery (`Tus-Resumable`, `Tus-Version`) |
| `POST` | `/api/upload/tus` | Create upload, returns `Location` header |
| `HEAD` | `/api/upload/tus/{id}` | Get current upload offset |
| `PATCH` | `/api/upload/tus/{id}` | Upload a chunk |
| `DELETE` | `/api/upload/tus/{id}` | Cancel upload |

**Env config (in `.env`):**

```
TUS_UPLOAD_DIR=writable/uploads/tus
TUS_MAX_SIZE=1073741824
TUS_EXPIRY_HOURS=24
```

**Cleanup expired uploads (cron):**

```bash
php spark tus:cleanup
```

**JS client usage in a view:**

```php
<script src="https://cdn.jsdelivr.net/npm/tus-js-client@latest/dist/tus.min.js"></script>
<script src="<?= base_url('assets/js/tus-client.js') ?>"></script>

<div x-data="tusUploader({ endpoint: '/api/upload/tus', onSuccess: (url) => console.log(url) })">
  <input type="file" @change="start($event.target.files[0])">
  <template x-if="isUploading">
    <progress :value="progress" max="100"></progress>
  </template>
  <template x-if="isComplete">
    <a :href="result" x-text="result"></a>
  </template>
</div>
```

All TUS endpoints are behind `apiKeyFilter` (Bearer token auth). The JS client reads the token from `auth.js` automatically.

---

## Transformers

Extend `BaseTransformer` to sanitize and shape model data before it reaches the API response layer — strip sensitive fields, rename keys, or add computed values.

```php
// app/Transformers/UserTransformer.php
class UserTransformer extends BaseTransformer
{
    public function transform(array $item): array
    {
        return $this->only($item, ['id', 'name', 'email']) + [
            'joined_at' => $item['created_at'] ?? null,
        ];
    }
}
```

Usage in a Controller:

```php
$result = $this->userService->findAll($filters);
$transformer = new UserTransformer();
return $this->success($transformer->collection($result['data']));
```

Helper methods available: `only(array $data, array $keys)`, `except(array $data, array $keys)`.

---

## Audit Trail

This kit includes an optional audit trail layer for important create/update/delete operations. It is wired at the service layer, so controllers stay clean and audit logging is transparent.

### What gets recorded

Each audit log entry stores:
- actor information (`user_id`, `user_type`)
- action (`create`, `update`, `delete`, `restore`)
- target model and record id
- old/new values
- request metadata (`ip_address`, `user_agent`)
- creation timestamp

### Files involved

- [app/Database/Migrations/20260718120000_CreateAuditLogsTable.php](app/Database/Migrations/20260718120000_CreateAuditLogsTable.php) — includes composite index on `(model, action, created_at)` for common query patterns
- [app/Database/Migrations/20260720100000_AddAuthIdentitiesIndex.php](app/Database/Migrations/20260720100000_AddAuthIdentitiesIndex.php) — adds index on `auth_identities.secret` for email search
- [app/Models/AuditLogModel.php](app/Models/AuditLogModel.php)
- [app/Traits/AuditTrailTrait.php](app/Traits/AuditTrailTrait.php)
- [app/Services/BaseService.php](app/Services/BaseService.php)

### Run the migration

```bash
php spark migrate
```

### Notes

- `auditUpdate()` only records fields that actually changed, so the log stays compact and useful.
- Audit logging is non-blocking by design; failures are logged and do not break normal request flow.

---

## SSO Layer

The kit includes an optional JWT RS256-based Single Sign-On layer for cross-application authentication. It is **disabled by default** — set `SSO_ENABLED=true` to activate.

### How it works

```
SSO Server                    Resource Server
──────────────                ──────────────────────────────
POST /api/auth/login          Authorization: Bearer <JWT>
    ↓                                  ↓
JWTService::sign()            SSOFilter::before()
    ↓                                  ↓
JWT (RS256) → client          JWTService::verify() — offline, no HTTP call
                                       ↓
                              Valid → $request->ssoUser injected
                              Invalid → 401 Unauthorized
```

### Setup

**1. Generate an RSA key pair** (run once on the SSO Server):

```bash
openssl genrsa -out private.pem 2048
openssl rsa -in private.pem -pubout -out public.pem
```

- `private.pem` — stays on the SSO Server only. Never committed to version control.
- `public.pem` — distributed to all Resource Server apps via `.env`.

**2. Configure `.env`**

On the **SSO Server** (signs tokens):

```
SSO_ENABLED=true
SSO_PRIVATE_KEY="-----BEGIN RSA PRIVATE KEY-----\n<key>\n-----END RSA PRIVATE KEY-----"
SSO_TOKEN_TTL=3600
```

On each **Resource Server** (verifies tokens):

```
SSO_ENABLED=true
SSO_PUBLIC_KEY="-----BEGIN PUBLIC KEY-----\n<key>\n-----END PUBLIC KEY-----"
```

**3. Apply the filter to routes**

```php
$routes->group('api', ['filter' => 'ssoFilter'], static function ($routes) {
    $routes->get('profile', 'Api\ProfileController::index');
});
```

### Issue a token (SSO Server)

```php
// app/Controllers/Api/AuthController.php
public function login(): ResponseInterface
{
    // 1. Validate credentials
    // 2. Sign JWT
    $token = (new \App\Libraries\JWTService())->sign([
        'sub'   => (string) $user->id,
        'email' => $user->email,
    ]);

    return $this->success(['token' => $token]);
}
```

### Access the payload (Resource Server)

```php
$ssoUser = $this->request->ssoUser; // ['sub' => '1', 'email' => '...', 'iat' => ..., 'exp' => ...]
```

### Install

```bash
composer require firebase/php-jwt:^7.0
```

> `firebase/php-jwt` is already in `require` since v2.0. No extra install needed if you cloned this kit.

### Notes

- The `sub` claim is required in every token. `JWTService::sign()` will throw if absent.
- Default TTL is 3600 seconds (1 hour). Override via `SSO_TOKEN_TTL`.
- When `SSO_ENABLED=false`, `SSOFilter` is a complete pass-through — zero overhead.

---

## PDF Export

The kit includes `BasePdfExporter`, an abstract base class for generating and streaming PDFs via [mPDF](https://mpdf.github.io/). It is **optional** — install mPDF only when your project needs PDF export.

### Install

```bash
composer require mpdf/mpdf:^8.2
```

### Create an exporter

```php
// app/Libraries/UserPdfExporter.php
class UserPdfExporter extends BasePdfExporter
{
    protected function buildHtml(array $data): string
    {
        return view('exports/users_pdf', ['users' => $data], ['saveData' => true]);
    }
}
```

### Use in a controller

```php
public function exportPdf(): ResponseInterface
{
    $data = (new UserService())->findAll([])['data'];

    try {
        (new UserPdfExporter())->export($data, 'users-' . date('Ymd') . '.pdf');
        exit;
    } catch (\RuntimeException $e) {
        AppLogger::error('pdf.export.failed', [], $e);
        return $this->error('Failed to generate PDF', 500);
    }
}
```

### Notes

- One exporter subclass per resource.
- Templates in `app/Views/exports/` must not extend any CI4 layout.
- Always use `esc()` for user-controlled data in templates.

---

## WebSocket Server (v4.0)

The kit includes an optional real-time event layer powered by [Ratchet](http://socketo.me/). CI4 publishes events to a separate Ratchet process via internal HTTP; the Ratchet server broadcasts them to connected WebSocket clients. It is **disabled by default** — set `WS_ENABLED=true` to activate.

### How it works

```
┌─ CI4 ──────────────────┐     POST /publish      ┌─ Ratchet Server ───────────┐
│  afterCreate/Update/    │ ────────────────────→  │  HTTP on 127.0.0.1:8082    │
│  Delete → WsPublisher   │                        │         ↓                  │
│                         │                        │  Channel subscription map  │
└─────────────────────────┘                        │         ↓                  │
                                                   │  WebSocket on :8081        │
                                                   │  ws://host:8081            │
                                                   └────────────────────────────┘
                                                             ↓
                                                   Browser / Mobile clients
```

- CI4 stays **stateless** — it only publishes, it never manages connections.
- Ratchet manages all WebSocket connections, channels, and subscriptions in-memory.
- Two separate ports: `WS_PORT` (8081) for WebSocket clients, `WS_HTTP_PORT` (8082) for internal CI4 publish requests. The HTTP port binds to `127.0.0.1` only and is never exposed externally.

### Setup

**1. Install Ratchet:**

```bash
composer require cboden/ratchet:^0.4.4
```

**2. Configure `.env`:**

```
WS_ENABLED=true
WS_HOST=127.0.0.1
WS_PORT=8081
WS_HTTP_PORT=8082
WS_SECRET=your-secret-token-here
```

> `WS_HOST` should be `127.0.0.1` in production — the server binds to localhost only. Change to `0.0.0.0` only if clients connect from other hosts. The HTTP port (`WS_HTTP_PORT`) is always localhost-only.

**3. Start the WebSocket server:**

```bash
php spark ws:serve
```

This starts the Ratchet server in the foreground. For production, use a process manager (Supervisor, systemd) to daemonize it.

### Publish an event

```php
use App\Libraries\WsPublisher;

$publisher = new WsPublisher();
$publisher->publish('orders', [
    'action' => 'created',
    'id'     => 42,
]);
```

`WsPublisher` is automatically called from `BaseService` lifecycle hooks — any Service that overrides `afterCreate`, `afterUpdate`, or `afterDelete` will push events without additional code. Channel names are auto-normalized to `model:{resource}` (e.g. `App\Models\OrderModel` → `model:order`).

**Safety:** By default, the published payload contains only `{action, id}` — no record data is broadcast. To include specific fields in the WebSocket event, override `getWsPayload()` in your Service subclass:

```php
// app/Services/OrderService.php
protected function getWsPayload(string $action, int|string $id, array $data): array
{
    return [
        'action' => $action,
        'id'     => $id,
        'status' => $data['status'] ?? null,
    ];
}
```

> Never include passwords, tokens, or PII in the payload. Only expose data that is safe for WebSocket clients to receive.

### Connect from JavaScript

```javascript
const ws = new WebSocket('ws://localhost:8081');

ws.onopen = () => {
    ws.send(JSON.stringify({ type: 'subscribe', channel: 'orders' }));
};

ws.onmessage = (event) => {
    const msg = JSON.parse(event.data);
    // msg = { channel: 'orders', action: 'created', id: 42, data: {...} }
};
```

Channel names from `BaseService` are auto-normalized to `model:{resource}`:

```javascript
// Subscribe to order events (from App\Models\OrderModel → model:order)
ws.send(JSON.stringify({ type: 'subscribe', channel: 'model:order' }));
```

### Client protocol

| Message (client → server) | Description |
|---|---|
| `{"type":"subscribe","channel":"orders"}` | Subscribe to a channel |
| `{"type":"unsubscribe","channel":"orders"}` | Unsubscribe from a channel |
| `{"type":"ping"}` | Keep-alive (server replies `{"type":"pong"}`) |

| Message (server → client) | Description |
|---|---|
| `{"type":"welcome","connectionId":"..."}` | Sent on successful connection |
| `{"type":"pong"}` | Response to client ping |
| `{"channel":"orders","action":"created","id":42,"data":{...}}` | Event published by CI4 |

### Notes

- When `WS_ENABLED=false`, `WsPublisher::publish()` is a silent no-op — zero overhead.
- The WsServer validates the `X-WS-Secret` header on every publish request. The CI4 and Ratchet processes must share the same `WS_SECRET` value.
- Two separate ports: `WS_PORT` (8081) for WebSocket clients, `WS_HTTP_PORT` (8082) for internal CI4 → Ratchet HTTP publish. Firewall `WS_HTTP_PORT` from external access.
- `WS_HOST` must remain `127.0.0.1` in production. Changing it exposes the internal HTTP publish endpoint to the network. A runtime warning is logged if the host is not `127.0.0.1`.
- Channels are created on first subscription. When the last client unsubscribes, the channel is cleaned up.
- The server runs as a single process. For horizontal scaling, consider a future Redis-backed pub/sub adapter.

---

## Compatibility Matrix

v4.0 is a **strict superset** of v3.x. No database migrations required to upgrade.

| Feature | v1.x | v2.0 | v3.0 | v4.0 |
|---|---|---|---|---|
| BE Layer (API, Service, Model) | ✅ | ✅ | ✅ | ✅ |
| Shield Auth (token-based) | ✅ | ✅ | ✅ | ✅ |
| Audit Trail | ✅ | ✅ | ✅ | ✅ |
| File Upload + Storage Drivers | ✅ | ✅ | ✅ | ✅ |
| Structured JSON Logging | ✅ | ✅ | ✅ | ✅ |
| Transformers | ✅ | ✅ | ✅ | ✅ |
| SSO Layer (JWT RS256) | ❌ | ✅ optional | ✅ optional | ✅ optional |
| PDF Export (mPDF) | ❌ | ✅ optional | ✅ optional | ✅ optional |
| **TUS Chunked Upload** | ❌ | ❌ | ✅ optional | ✅ optional |
| **WebSocket Server (Ratchet)** | ❌ | ❌ | ❌ | ✅ optional |
| **Web UI Layer** | ❌ | ❌ | ✅ | ✅ |
| **Token-based login (no session)** | ❌ | ❌ | ✅ | ✅ |
| **APP_NAME env binding** | ❌ | ❌ | ✅ | ✅ |
| **Admin user creation with password** | ❌ | ❌ | ✅ | ✅ |

### Upgrading from v2.x

Copy these files into your existing v2.x project:

| File | Purpose |
|---|---|
| `app/Controllers/Api/AuthController.php` | Token login — no session conflict |
| `app/Controllers/Web/` | Web controllers for views |
| `app/Views/` | Full views layer (layouts, partials, pages) |
| `public/assets/js/` | `auth.js`, `api.js`, `error.js`, `components.js` |
| `app/Config/Routes.php` | Updated routes (web + API) |

Add to `.env`:
```
APP_NAME="My App"
```

---

## How to Add a New Resource

Example: adding a `Post` resource.

**1. Create the migration**

```bash
php spark make:migration CreatePostsTable
php spark migrate
```

**2. Create the Model** — `app/Models/PostModel.php`

```php
class PostModel extends BaseModel
{
    protected $table                  = 'posts';
    protected $allowedFields          = ['title', 'body', 'user_id'];
    protected array $searchableFields = ['title', 'body'];
}
```

**3. Create the Service** — `app/Services/PostService.php`

```php
class PostService extends BaseService
{
    protected string $modelClass = PostModel::class;
}
```

**4. Create the API Controller** — `app/Controllers/Api/PostController.php`

```php
class PostController extends BaseApiController
{
    public function index(): ResponseInterface
    {
        $result = (new PostService())->findAll($this->request->getGet() ?? []);
        return $this->success($result['data']);
    }
}
```

**5. Create the Web Controller** — `app/Controllers/Web/PostWebController.php`

```php
class PostWebController extends BaseController
{
    public function index()   { return view('posts/index'); }
    public function create()  { return view('posts/create'); }
    public function detail($id) { return view('posts/detail', ['id' => $id]); }
}
```

**6. Register routes** in `app/Config/Routes.php`

```php
// Web routes
$routes->group('', static function ($routes) {
    $routes->get('posts',           'Web\PostWebController::index');
    $routes->get('posts/create',    'Web\PostWebController::create');
    $routes->get('posts/(:num)',    'Web\PostWebController::detail/$1');
});

// API routes
$routes->group('api', ['filter' => 'apiKeyFilter'], static function ($routes) {
    $routes->get('posts',           'Api\PostController::index');
    $routes->post('posts',          'Api\PostController::create');
    $routes->get('posts/(:num)',    'Api\PostController::show/$1');
    $routes->put('posts/(:num)',    'Api\PostController::update/$1');
    $routes->delete('posts/(:num)', 'Api\PostController::delete/$1');
});
```

See `app/Services/UserService.php` and `app/Controllers/Api/UserController.php` for a complete reference implementation.

---

## Server Requirements

PHP 8.2 or higher with the following extensions:

| Extension | Notes |
|---|---|
| `intl` | Required |
| `mbstring` | Required |
| `json` | Enabled by default |
| `mysqlnd` | Required for MySQL |
| `libcurl` | Required if using `HTTP\CURLRequest` |
| `curl` | Required if using `S3Driver` |
| `openssl` | Required for generating RSA key pairs (SSO setup, run once) |

---

## License

This project is open-sourced under the [MIT License](LICENSE).
