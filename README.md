# Elonn

`elonn.local` is the public front door for Elonn. Production is `elonn.com`.

This repo owns the browser-facing account surface: login, registration, account state, logout, and the handoff into the runtime shell. It does not own identity data.

## Project map

- `api.elonn.local`: identity authority and member directory
- `maps.elonn.local`: canonical field dataset service
- `social.elonn.local`: social object service and direct messages
- `time.elonn.local`: calendar and time service
- `world.elonn.local`: composition and presentation contract layer
- `web.elonn.local`: browser runtime implementation
- `admin.elonn.local`: operator console

## Role in the stack

Consumption order:

1. User visits `elonn.local` or `elonn.com`
2. Login and registration post to `api.elonn`
3. Successful login sets the shared `elonn_api_token` cookie
4. User enters `web.elonn`
5. `web.elonn` loads JSON runtime composition from `world.elonn`
6. `world.elonn` composes maps, social, time, and identity data into runtime-ready objects
7. `web.elonn` renders the shell and opens carry panels

## What we kept from `social.elonara.local`

`social.elonn` uses the useful social model from `social.elonara.local` and drops the old product shell.

What carried over:

- conversations and replies for public or semi-public discourse
- direct message threads for member-to-member communication
- communities and memberships
- presence and relationship state
- visibility and permission concepts
- reactions, activity, and notification intent

What did not carry over:

- the old Elonara product UI
- monolithic page structure
- product-specific assumptions about layout or branding

That extraction is the source of the current `social.elonn` boundary.

## Routes

```text
GET /
GET /account/login
POST /account/login
GET /login
POST /login
GET /account/register
POST /account/register
GET /account
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
```

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

Local:

```text
http://elonn.local/
http://elonn.local/account/login
http://elonn.local/account/register
http://elonn.local/account
```

Production:

```text
https://elonn.com/
https://elonn.com/account/login
https://elonn.com/account/register
https://elonn.com/account
```

## Related repos

- `api.elonn.local`: shared identity authority
- `web.elonn.local`: browser runtime
- `world.elonn.local`: composition and service aggregation
- `maps.elonn.local`: canonical field dataset
- `time.elonn.local`: calendar and time service
- `social.elonn.local`: social object service
- `admin.elonn.local`: operator console
