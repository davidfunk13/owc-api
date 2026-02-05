# OWC API

REST API backend for the OWC Stats Tracker application. Built with Laravel and provides Battle.net OAuth authentication.

## Tech Stack

- **PHP** 8.2+
- **Laravel** 12
- **Laravel Sanctum** for API token authentication
- **PostgreSQL** database
- **Redis** for caching
- **Socialite** with Battle.net provider for OAuth

## Prerequisites

- PHP 8.2+
- Composer
- PostgreSQL
- Redis
- Battle.net Developer Application credentials

## Getting Started

### 1. Install Dependencies

```bash
composer install
```

### 2. Configure Environment

Copy the example environment file:

```bash
cp .env.example .env
```

Edit `.env` with your configuration:

```bash
# Application
APP_NAME="OWC API"
APP_URL=http://localhost:8000

# Database (PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=owc
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Battle.net OAuth (from developer.battle.net)
BATTLENET_CLIENT_ID=your_client_id
BATTLENET_CLIENT_SECRET=your_client_secret
BATTLENET_REDIRECT_URI=http://localhost:8000/auth/battlenet/callback

# Post-auth redirects for each platform
AUTH_REDIRECT_MOBILE=owc://auth/callback
AUTH_REDIRECT_WEB=http://localhost:8081/auth/callback

# Redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

### 3. Generate Application Key

```bash
php artisan key:generate
```

### 4. Run Database Migrations

```bash
php artisan migrate
```

### 5. Start Development Server

```bash
# Standard (localhost only)
composer dev

# For real device testing (accessible from network)
composer dev:device
```

## Quick Setup

Run all setup steps at once:

```bash
composer setup
```

This runs: `composer install`, generates `.env`, `key:generate`, and `migrate`.

## Running on a Real Device

When testing with a physical phone, the API must be accessible from your local network.

### 1. Find Your Local IP

```bash
# macOS
ipconfig getifaddr en0

# Linux
hostname -I | awk '{print $1}'
```

### 2. Update Environment

```bash
APP_URL=http://192.168.0.132:8000
BATTLENET_REDIRECT_URI=http://192.168.0.132:8000/auth/battlenet/callback
```

### 3. Start Server on All Interfaces

```bash
composer dev:device
```

This binds to `0.0.0.0` instead of `127.0.0.1`, making the API accessible from other devices on your network.

### 4. Update Battle.net App Settings

In the [Battle.net Developer Portal](https://develop.battle.net/), add your local IP to the allowed redirect URIs:

```
http://192.168.0.132:8000/auth/battlenet/callback
```

## API Endpoints

### Public Routes

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/` | Health check |
| GET | `/auth/battlenet/redirect` | Start OAuth flow |
| GET | `/auth/battlenet/callback` | OAuth callback (internal) |

### Protected Routes (requires `Authorization: Bearer {token}`)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/auth/user` | Get authenticated user |
| POST | `/api/auth/logout` | Logout and revoke token |

### OAuth Flow

Start authentication by redirecting to:

```
GET /auth/battlenet/redirect?platform=mobile
```

Query parameters:
- `platform`: `web` or `mobile` - determines post-auth redirect

## Database Schema

### Users Table

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| sub | string | OAuth subject ID (unique) |
| battlenet_id | bigint | Battle.net account ID (unique) |
| battletag | string | Player's BattleTag |
| created_at | timestamp | Account creation time |
| updated_at | timestamp | Last update time |

### Personal Access Tokens (Sanctum)

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| tokenable_type | string | Model type (User) |
| tokenable_id | bigint | User ID |
| name | string | Token name |
| token | string | Hashed token value |
| abilities | text | Token scopes |
| expires_at | timestamp | Token expiration |

## Available Scripts

| Command | Description |
|---------|-------------|
| `composer setup` | Full setup (install, key, migrate) |
| `composer dev` | Start dev server on localhost |
| `composer dev:device` | Start dev server on all interfaces |
| `composer test` | Run PHPUnit tests |
| `./vendor/bin/pint` | Format code with Laravel Pint |

## Project Structure

```
owc-api/
├── app/
│   ├── Http/Controllers/
│   │   └── AuthController.php    # OAuth and auth logic
│   ├── Models/
│   │   └── User.php              # User model
│   └── Providers/
│       └── AppServiceProvider.php
├── config/
│   ├── services.php              # OAuth credentials
│   └── sanctum.php               # API token settings
├── database/
│   └── migrations/               # Database schema
├── routes/
│   ├── api.php                   # API routes
│   └── web.php                   # Web routes (OAuth)
└── tests/
    ├── Feature/
    └── Unit/
```

## Authentication Flow

1. Client requests `/auth/battlenet/redirect?platform=mobile`
2. API generates state token, caches it, redirects to Battle.net
3. User authenticates on Battle.net
4. Battle.net redirects to `/auth/battlenet/callback`
5. API validates state, exchanges code for user info
6. User created/updated in database
7. Sanctum token generated
8. Redirect to client with token: `owc://auth/callback?token=...`

## Battle.net Developer Setup

1. Go to [Battle.net Developer Portal](https://develop.battle.net/)
2. Create a new application
3. Set the redirect URI to your callback URL
4. Copy Client ID and Client Secret to `.env`

Required OAuth scopes:
- `openid` - For user identity

## Testing

```bash
# Run all tests
composer test

# Run specific test file
./vendor/bin/phpunit tests/Feature/AuthTest.php
```

## Code Formatting

```bash
./vendor/bin/pint
```

## Environment Variables

| Variable | Description |
|----------|-------------|
| `APP_URL` | Base URL of the API |
| `DB_*` | PostgreSQL connection settings |
| `BATTLENET_CLIENT_ID` | Battle.net app client ID |
| `BATTLENET_CLIENT_SECRET` | Battle.net app client secret |
| `BATTLENET_REDIRECT_URI` | OAuth callback URL |
| `AUTH_REDIRECT_MOBILE` | Post-auth redirect for mobile |
| `AUTH_REDIRECT_WEB` | Post-auth redirect for web |
| `REDIS_HOST` | Redis server host |
| `REDIS_PORT` | Redis server port |

## License

Private - All rights reserved
