# Elonn

Elonn is a multi-service platform built around shared identity and runtime composition.

This repository is `elonn.com` — the company's front page and public/business home. It handles login, registration, account state, logout, and the handoff into the runtime shell, and it's also where marketing, funding, and other public/business content live. No other repo is appropriate for that content; the one carve-out is `docs.elonn.local`, the separate user-facing help site. This repo does not own identity data.

## Service map

- `api.elonn.local`: identity authority and member directory
- `web.elonn.local`: browser runtime implementation
- `world.elonn.local`: composition and presentation contract layer
- `mind.elonn.local`: dispatch layer between World and Services, currently integrating with Find
- `find.elonn.local`: search and findings service
- `maps.elonn.local`: canonical field dataset
- `messages.elonn.local`: open member-to-member message threads outside Social context
- `social.elonn.local`: social objects, circles, and Social DMs
- `time.elonn.local`: calendars and events
- `admin.elonn.local`: operator console

## What lives here

- public account surface
- login and registration routes
- account and logout flow
- CalDAV connection settings
- environment-aware handoff into `web.elonn`
- marketing, funding, and other public/business content (e.g. `entrepreneur/`)

## Current stack flow

1. A user visits `elonn.local` or `elonn.com`
2. Login and registration post to `api.elonn`
3. `api.elonn` sets the shared auth token
4. `web.elonn` sends a Runtime-World Call to `world.elonn`
5. `world.elonn` always forwards a World-Mind Call to `mind.elonn`; there is no local or cached response path
6. `mind.elonn` forwards the call to `find.elonn` today — other services are not yet wired into Mind
7. `web.elonn` renders the returned World Dataset

## Routes

```text
GET /
GET /.well-known/caldav
GET /account/login
POST /account/login
GET /login
POST /login
GET /account/register
POST /account/register
GET /start
GET /account
POST /account/caldav-tokens
POST /account/caldav-tokens/revoke
GET /investors
GET /contact
POST /contact
GET /privacy
GET /terms
POST /account/logout
```

The landing page uses progressive enhancement. The account routes must still work without JavaScript.

## Configuration

Create `.env` from `.env.example` and keep it uncommitted.

Important keys:

```env
APP_ENV=
APP_DEBUG=
APP_URL=
ELONN_API_BASE_URL=
ELONN_COOKIE_DOMAIN=
ELONN_WEB_URL=
ELONN_TIME_URL=
ELONN_SERVICES_URL=
ELONN_CONTACT_EMAIL=
```

`ELONN_CONTACT_EMAIL` is where the `/contact` form's submissions are sent (via PHP's `mail()`); defaults to `hello@elonn.com`.

Local defaults point to `.local` hosts. Production defaults point to `.com` hosts.

`public/index.php` loads `vendor/autoload.php`, calls `Dotenv::createImmutable(BASE_PATH)->safeLoad()`, then loads `config/config.php`. `config/config.php` is the only place that reads deployment environment values and returns normalized config arrays.

## Migrations

No database migrations are required for this repo.

If the account or launch flow needs new persistent state, that state belongs in the owning service, not here.

## Layout

```text
config/
  config.php
.env
.env.example
composer.json
composer.lock
vendor/
public/
  index.php
  .htaccess
  assets/
src/
templates/
```

## Verification

```bash
find public src templates -name '*.php' -print0 | xargs -0 -n1 php -l
```

## Local URLs

- `http://elonn.local/`
- `http://elonn.local/account/login`
- `http://elonn.local/account/register`
- `http://elonn.local/account`
- `http://elonn.local/start`
- `http://elonn.local/investors`
- `http://elonn.local/contact`
- `http://elonn.local/privacy`
- `http://elonn.local/terms`

## Production URLs

- `https://elonn.com/`
- `https://elonn.com/account/login`
- `https://elonn.com/account/register`
- `https://elonn.com/account`
- `https://elonn.com/start`
- `https://elonn.com/investors`
- `https://elonn.com/contact`
- `https://elonn.com/privacy`
- `https://elonn.com/terms`
