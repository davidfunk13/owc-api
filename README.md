# OWC API

REST API backend for the OWC Stats Tracker application. Built with Laravel and provides Battle.net OAuth authentication.

## Tech Stack

- **PHP** 8.2+
- **Laravel** 12
- **Laravel Sail** (Docker-based dev environment)
- **Laravel Sanctum** for API token authentication
- **PostgreSQL** database
- **Redis** for caching
- **Socialite** with Battle.net provider for OAuth

## Prerequisites

- [Docker](https://docs.docker.com/get-docker/)
- Battle.net Developer Application credentials (from [developer.battle.net](https://develop.battle.net/))

## Getting Started

### 1. Install Dependencies

No local PHP required — this runs Composer inside a Docker container:

```bash
docker run --rm -v $(pwd):/opt -w /opt laravelsail/php83-composer:latest composer install
```

### 2. Configure Environment

```bash
cp .env.example .env
```

Edit `.env` with your Battle.net OAuth credentials:

```bash
BATTLENET_CLIENT_ID=your_client_id
BATTLENET_CLIENT_SECRET=your_client_secret
BATTLENET_REDIRECT_URI=http://localhost/auth/battlenet/callback

# Post-auth redirects for each platform
AUTH_REDIRECT_MOBILE=owc://auth/callback
AUTH_REDIRECT_WEB=http://localhost:8081/auth/callback
```

### 3. Generate Application Key

```bash
./vendor/bin/sail artisan key:generate
```

### 4. Start the Application

```bash
./vendor/bin/sail up -d
```

This starts the Laravel app (port 80), PostgreSQL (port 5432), and Redis (port 6379).

### 5. Run Migrations

```bash
./vendor/bin/sail artisan migrate
```

## Sail Commands

All commands run inside Docker via Sail — no local PHP needed.

| Command | Description |
|---------|-------------|
| `./vendor/bin/sail up -d` | Start all containers |
| `./vendor/bin/sail down` | Stop all containers |
| `./vendor/bin/sail artisan ...` | Run Artisan commands |
| `./vendor/bin/sail composer ...` | Run Composer commands |
| `./vendor/bin/sail test` | Run tests |
| `./vendor/bin/sail shell` | Open a shell in the app container |

Tip: you can [alias](https://laravel.com/docs/12.x/sail#configuring-a-shell-alias) `sail` to avoid typing the full path:

```bash
alias sail='./vendor/bin/sail'
```

## Testing on a Real Device

The API is accessible on your local network by default (Docker binds to `0.0.0.0`).

### 1. Find Your Local IP

```bash
# Linux
hostname -I | awk '{print $1}'

# macOS
ipconfig getifaddr en0
```

### 2. Update `.env`

```bash
APP_URL=http://192.168.x.x
BATTLENET_REDIRECT_URI=http://192.168.x.x/auth/battlenet/callback
```

### 3. Update Battle.net App Settings

In the [Battle.net Developer Portal](https://develop.battle.net/), add your local IP to the allowed redirect URIs.

## Battle.net Developer Setup

1. Go to the [Battle.net Developer Portal](https://develop.battle.net/)
2. Create a new application
3. Set the redirect URI to your callback URL
4. Copy Client ID and Client Secret to `.env`

Required OAuth scope: `openid`

## Environment Variables

| Variable | Description |
|----------|-------------|
| `APP_URL` | Base URL of the API |
| `DB_*` | PostgreSQL connection (defaults work with Sail) |
| `BATTLENET_CLIENT_ID` | Battle.net app client ID |
| `BATTLENET_CLIENT_SECRET` | Battle.net app client secret |
| `BATTLENET_REDIRECT_URI` | OAuth callback URL |
| `AUTH_REDIRECT_MOBILE` | Post-auth redirect for mobile |
| `AUTH_REDIRECT_WEB` | Post-auth redirect for web |
| `REDIS_*` | Redis connection (defaults work with Sail) |

## License

Private - All rights reserved
